<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
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
} 