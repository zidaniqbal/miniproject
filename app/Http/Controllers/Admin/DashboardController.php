<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
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
} 