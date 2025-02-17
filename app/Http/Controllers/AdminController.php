<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Admincontroller extends Controller
{
    public function index()
    {
        // Data untuk User Growth Chart (30 hari terakhir)
        $userGrowthData = $this->getUserGrowthData();
        
        // Data untuk User Distribution Chart
        $userDistributionData = $this->getUserDistributionData();

        return view('admin.dashboard', compact('userGrowthData', 'userDistributionData'));
    }

    public function users()
    {
        $roles = Role::all();
        return view('admin.users', compact('roles'));
    }

    public function getUsersData()
    {
        try {
            $users = User::select(['id', 'name', 'email', 'role', 'created_at']);

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role_name', function($user) {
                    // Role: 1 = User, 2 = Admin
                    return $user->role == 1 ? 'User' : 'Admin';
                })
                ->addColumn('created_at_formatted', function($user) {
                    return $user->created_at->format('d M Y H:i');
                })
                ->addColumn('action', function($user) {
                    return '<button class="btn btn-sm btn-primary edit-user" data-id="'.$user->id.'">Edit</button> 
                            <button class="btn btn-sm btn-danger delete-user" data-id="'.$user->id.'">Delete</button>';
                })
                ->rawColumns(['action'])
                ->toJson();
        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading data'], 500);
        }
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'role' => 'required|exists:roles,id',
            ]);

            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the user.'
            ], 500);
        }
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$user->id,
                'password' => 'nullable|min:6',
                'role' => 'required|exists:roles,id',
            ]);

            DB::beginTransaction();

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ];

            // Only update password if provided
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user.'
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the user.'
            ], 500);
        }
    }

    private function getUserGrowthData()
    {
        $days = 30;
        $period = now()->subDays($days)->daysUntil(now());
        
        $dates = [];
        $counts = [];
        
        foreach ($period as $date) {
            $dates[] = $date->format('d M');
            $counts[] = User::whereDate('created_at', $date)->count();
        }

        return [
            'dates' => $dates,
            'counts' => $counts
        ];
    }

    private function getUserDistributionData()
    {
        $totalUsers = User::count();
        $regularUsers = User::where('role', 1)->count();
        $adminUsers = User::where('role', 2)->count();

        return [
            'labels' => ['Regular Users', 'Admins'],
            'counts' => [
                round(($regularUsers / $totalUsers) * 100, 1),
                round(($adminUsers / $totalUsers) * 100, 1)
            ]
        ];
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = User::findOrFail(Auth::id());
            
            // Log request data
            Log::info('Profile update request:', [
                'has_file' => $request->hasFile('profile_image'),
                'all_data' => $request->all()
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            DB::beginTransaction();

            // Update name
            $user->name = $validated['name'];

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                try {
                    // Log storage path
                    Log::info('Storage paths:', [
                        'public_path' => public_path(),
                        'storage_path' => storage_path('app/public')
                    ]);

                    // Delete old image if exists
                    if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                        Log::info('Deleting old image: ' . $user->profile_image);
                        Storage::disk('public')->delete($user->profile_image);
                    }

                    // Store new image
                    $file = $request->file('profile_image');
                    Log::info('Uploading new image:', [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);

                    $path = $file->store('profile-images', 'public');
                    
                    if (!$path) {
                        throw new \Exception('Failed to store image');
                    }
                    
                    Log::info('File stored successfully at: ' . $path);
                    
                    // Convert path separators to forward slashes
                    $path = str_replace('\\', '/', $path);
                    $user->profile_image = $path;
                    
                } catch (\Exception $e) {
                    Log::error('Error handling profile image: ' . $e->getMessage());
                    Log::error('Stack trace: ' . $e->getTraceAsString());
                    throw $e;
                }
            }

            $user->save();
            Log::info('User profile updated successfully', ['user_id' => $user->id]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating profile: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating profile: ' . $e->getMessage()
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

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
        return view('admin.news');
    }

    public function getNews(Request $request)
    {
        try {
            $category = $request->query('category', 'nasional');
            // Sesuaikan base URL berdasarkan kategori
            $baseUrl = match($category) {
                'nasional' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/nasional',
                'internasional' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/internasional',
                'ekonomi' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/ekonomi',
                'olahraga' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/olahraga',
                'teknologi' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/teknologi',
                'hiburan' => 'https://berita-indo-api-next.vercel.app/api/cnn-news/hiburan',
                default => 'https://berita-indo-api-next.vercel.app/api/cnn-news/nasional'
            };
            
            // Log request
            Log::info('Fetching news from CNN Indonesia', [
                'url' => $baseUrl
            ]);

            $response = Http::get($baseUrl);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Transform data structure to match frontend expectations
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
} 