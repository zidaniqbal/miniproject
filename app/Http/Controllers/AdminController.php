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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\Models\Goal;
use Carbon\Carbon;
use App\Models\Visitor;
use Illuminate\Support\Facades\Cache;
use App\Models\Photo;

class AdminController extends Controller
{
    public function index()
    {
        // Data untuk User Growth Chart (30 hari terakhir)
        $userGrowthData = $this->getUserGrowthData();
        
        // Data untuk User Distribution Chart
        $userDistributionData = $this->getUserDistributionData();

        // Add visitor data
        $visitorData = $this->getVisitorData();
        
        return view('admin.dashboard', compact('userGrowthData', 'userDistributionData', 'visitorData'));
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
                
                $articles = collect($data['data'])->map(function($article) {
                    return [
                        'title' => $article['title'],
                        'description' => $article['contentSnippet'] ?? '',
                        'url' => $article['link'],
                        'urlToImage' => $article['image']['large'] ?? null,
                        'publishedAt' => $article['isoDate'],
                        'creator' => $article['creator'] ?? 'CNN Indonesia'
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

    public function goals()
    {
        $goals = Goal::where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('admin.goals', compact('goals'));
    }

    public function getGoal(Goal $goal)
    {
        // Check if the goal belongs to the authenticated user
        if ($goal->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'goal' => $goal
        ]);
    }

    public function storeGoal(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'target_date' => 'required|date|after:today',
                'priority' => 'required|in:low,medium,high',
            ]);

            DB::beginTransaction();

            $goal = Goal::create([
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'target_date' => $validated['target_date'],
                'priority' => $validated['priority'],
                'status' => 'not_started',
                'progress' => 0,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Goal created successfully!',
                'goal' => $goal
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating goal: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the goal.'
            ], 500);
        }
    }

    public function updateGoalProgress(Request $request, Goal $goal)
    {
        try {
            // Check if the goal belongs to the authenticated user
            if ($goal->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validated = $request->validate([
                'progress' => 'required|integer|min:0|max:100',
                'status' => 'required|in:not_started,in_progress,completed',
            ]);

            DB::beginTransaction();

            $goal->update([
                'progress' => $validated['progress'],
                'status' => $validated['status'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Goal progress updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating goal progress: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the goal progress.'
            ], 500);
        }
    }

    public function updateGoalDescription(Request $request, Goal $goal)
    {
        try {
            // Check if the goal belongs to the authenticated user
            if ($goal->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validated = $request->validate([
                'description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $goal->update([
                'description' => $validated['description'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Goal description updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating goal description: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the goal description.'
            ], 500);
        }
    }

    public function deleteGoal(Goal $goal)
    {
        try {
            // Check if the goal belongs to the authenticated user
            if ($goal->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            DB::beginTransaction();

            $goal->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Goal deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting goal: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the goal.'
            ], 500);
        }
    }

    public function getDashboardGoals()
    {
        try {
            // Mengambil goals khusus untuk admin yang sedang login
            $goals = Goal::where('user_id', auth()->id())
                        ->select('id', 'title', 'progress', 'status', 'priority', 'target_date', 'created_at')
                        ->orderBy('created_at', 'desc')
                        ->get();

            return response()->json([
                'success' => true,
                'goals' => $goals
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching dashboard goals: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching goals.'
            ], 500);
        }
    }

    private function getVisitorData()
    {
        try {
            $days = 30;
            $period = now()->subDays($days)->daysUntil(now());
            
            $dates = [];
            $counts = [];
            
            foreach ($period as $date) {
                $dateStr = $date->format('d M');
                $count = Visitor::whereDate('created_at', $date)->count();
                
                // Log untuk debugging
                Log::info("Visitor count for {$dateStr}: {$count}");
                
                $dates[] = $dateStr;
                $counts[] = $count;
            }

            // Log total data
            Log::info('Total visitor data:', [
                'total_days' => count($dates),
                'total_visitors' => array_sum($counts)
            ]);

            return [
                'dates' => $dates,
                'counts' => $counts
            ];
        } catch (\Exception $e) {
            Log::error('Error getting visitor data: ' . $e->getMessage());
            return [
                'dates' => [],
                'counts' => []
            ];
        }
    }

    public function gallery()
    {
        return view('admin.gallery');
    }

    public function searchImages(Request $request)
    {
        try {
            $page = (int)$request->query('page', 1);
            $perPage = 20;
            $images = [];

            // Generate random images
            for ($i = 0; $i < $perPage; $i++) {
                $randomId = rand(1, 1000);
                
                $images[] = [
                    'id' => $randomId,
                    'thumbnail' => "https://picsum.photos/seed/{$randomId}/400/300", // thumbnail
                    'preview' => "https://picsum.photos/seed/{$randomId}/800/600",   // preview
                    'full' => "https://picsum.photos/seed/{$randomId}/2400/1800",   // high quality
                    'download' => "https://picsum.photos/seed/{$randomId}/3840/2160", // 4K quality
                    'source' => 'Picsum'
                ];
            }

            return response()->json([
                'success' => true,
                'images' => $images,
                'hasMore' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Error in searchImages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load images. Please try again later.'
            ], 500);
        }
    }

    public function photobooth()
    {
        return view('admin.photobooth');
    }

    public function photoboothGallery()
    {
        $userId = auth()->id();
        $photos = Storage::disk('public')->files("photobooth/{$userId}");
        return view('admin.photobooth-gallery', compact('photos'));
    }

    public function savePhotoboothImage(Request $request)
    {
        try {
            $userId = auth()->id();
            $image = $request->input('image');
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'photobooth_' . time() . '.png';
            
            // Simpan di folder sesuai user ID
            Storage::disk('public')->put("photobooth/{$userId}/" . $imageName, base64_decode($image));

            return response()->json([
                'success' => true,
                'message' => 'Photo saved successfully',
                'path' => "photobooth/{$userId}/" . $imageName
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save photo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadPhoto(Photo $photo)
    {
        if ($photo->user_id !== auth()->id()) {
            abort(403);
        }

        $path = Storage::path('public/' . $photo->path);
        return response()->download($path, $photo->filename);
    }

    public function deletePhoto(Photo $photo)
    {
        try {
            if ($photo->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            Storage::disk('public')->delete($photo->path);
            $photo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting photo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete photo'
            ], 500);
        }
    }

    public function deletePhotoboothImage($photo)
    {
        try {
            $userId = auth()->id();
            // Pastikan foto berada di folder user yang benar
            if (!Storage::disk('public')->exists("photobooth/{$userId}/" . $photo)) {
                throw new \Exception('Photo not found or unauthorized');
            }
            
            Storage::disk('public')->delete("photobooth/{$userId}/" . $photo);
            return redirect()->route('admin.photobooth.gallery')->with('success', 'Photo deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.photobooth.gallery')->with('error', 'Failed to delete photo');
        }
    }

    public function delete($photo)
    {
        try {
            Storage::delete('public/photos/' . $photo);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
} 