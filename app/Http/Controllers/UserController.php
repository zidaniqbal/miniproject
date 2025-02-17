<?php

namespace App\Http\Controllers;

// ... rest of the code remains the same ... use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class Usercontroller extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }

    public function settings()
    {
        return view('user.settings');
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = User::findOrFail(Auth::id());
            
            Log::info('Profile update request:', [
                'has_file' => $request->hasFile('profile_image'),
                'all_data' => $request->all()
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            DB::beginTransaction();

            $user->name = $validated['name'];

            if ($request->hasFile('profile_image')) {
                try {
                    if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                        Storage::disk('public')->delete($user->profile_image);
                    }

                    $path = $request->file('profile_image')->store('profile-images', 'public');
                    
                    if (!$path) {
                        throw new \Exception('Failed to store image');
                    }
                    
                    $path = str_replace('\\', '/', $path);
                    $user->profile_image = $path;
                    
                } catch (\Exception $e) {
                    Log::error('Error handling profile image: ' . $e->getMessage());
                    throw $e;
                }
            }

            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating profile: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating profile.'
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $user = User::findOrFail(Auth::id());

            $validated = $request->validate([
                'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The current password is incorrect.');
                    }
                }],
                'new_password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'different:current_password'
                ],
                'new_password_confirmation' => 'required'
            ]);

            DB::beginTransaction();

            $user->password = Hash::make($validated['new_password']);
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating password: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating password.'
            ], 500);
        }
    }

    public function news()
    {
        return view('user.news');
    }

    public function getNews(Request $request)
    {
        try {
            $category = $request->query('category', 'nasional');
            $baseUrl = match($category) {
                'nasional' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/nasional',
                'internasional' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/internasional',
                'ekonomi' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/ekonomi',
                'olahraga' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/olahraga',
                'teknologi' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/teknologi',
                'hiburan' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/hiburan',
                default => 'https://berita-indo-api-next.vercel.app/api/cnn-news/nasional'
            };
            
            Log::info('Fetching news from CNN Indonesia', [
                'category' => $category,
                'url' => $baseUrl
            ]);

            $response = Http::get($baseUrl);
            
            if ($response->successful()) {
                $data = $response->json();
                
                $articles = collect($data['data'])->map(function($article) {
                    return [
                        'title' => $article['title'],
                        'description' => $article['contentSnippet'],
                        'url' => $article['link'],
                        'urlToImage' => $article['image']['large'],
                        'publishedAt' => $article['isoDate'],
                    ];
                });

                return response()->json([
                    'success' => true,
                    'articles' => $articles
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch news'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error fetching news: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching news: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showNews($category, $title)
    {
        try {
            $baseUrl = match($category) {
                'nasional' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/nasional',
                'internasional' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/internasional',
                'ekonomi' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/ekonomi',
                'olahraga' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/olahraga',
                'teknologi' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/teknologi',
                'hiburan' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/hiburan',
                default => 'https://berita-indo-api-next.vercel.app/api/cnn-news/nasional'
            };

            $response = Http::get($baseUrl);
            
            if ($response->successful()) {
                $data = $response->json();
                $articles = collect($data['data']);
                
                // Cari artikel berdasarkan judul yang di-encode
                $article = $articles->first(function($article) use ($title) {
                    return urlencode($article['title']) === $title;
                });

                if (!$article) {
                    throw new \Exception('Artikel tidak ditemukan');
                }

                $formattedArticle = [
                    'title' => $article['title'],
                    'description' => $article['contentSnippet'],
                    'content' => $article['content'],
                    'image' => $article['image']['large'],
                    'url' => $article['link'],
                    'source' => 'CNN Indonesia',
                    'publishedAt' => $article['isoDate'],
                    'category' => $category
                ];

                return view('user.news-detail', ['article' => (object)$formattedArticle]);
            }

            throw new \Exception('Gagal mengambil data berita');

        } catch (\Exception $e) {
            Log::error('Error showing news detail: ' . $e->getMessage());
            return redirect()->route('user.news')
                ->with('error', 'Berita tidak ditemukan atau terjadi kesalahan.');
        }
    }
} 