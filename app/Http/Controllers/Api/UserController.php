<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_image' => 'patient.png'  // Default profile image
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        // Removed dd($token) line
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }
    
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
    }
    
    public function update(Request $request)
    {
        try {
            $user = $request->user();
            
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
                'current_password' => 'required_with:new_password',
                'new_password' => 'nullable|string|min:8|confirmed',
                'new_password_confirmation' => 'required_with:new_password',
                'phone' => 'nullable|string',
                'birth_date' => 'nullable|date',
                'gender' => 'nullable|in:male,female',
                'address' => 'nullable|string',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = array_filter($request->only([
                'name', 'email', 'phone', 'address'
            ]));
            
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                
                // Generate new image name with timestamp
                $imageName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                
                // Store only in public directory
                $file->move(public_path('profiles'), $imageName);
                
                $updateData['profile_image'] = $imageName;
            }

            // Update password verification and setting
            if ($request->filled('new_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Current password is incorrect'
                    ], 422);
                }
                $updateData['password'] = Hash::make($request->new_password);
            }
            
            if ($user->update($updateData)) {
            
            return response()->json([
                'status' => true,
                'message' => 'User ' . $request->name . ' updated successfully',
                'data' => [
                    'user' => $user,
                    'profile_image_url' => $user->profile_image ? url('api/profile-image/' . $user->profile_image) : null
                ]
            ]);}
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function index()
        {
            $users = User::with('department')->get();
            
            return response()->json([
                'status' => true,
                'message' => 'Users retrieved successfully',
                'data' => [
                    'users' => $users,
                    'total' => $users->count()
                ]
            ]);
        }

        public function show($id)
        {
            try {
                $user = User::with('department')->findOrFail($id);
                
                return response()->json([
                    'status' => true,
                    'message' => 'User retrieved successfully',
                    'data' => [
                        'user' => $user,
                        'profile_image_url' => $user->profile_image ? url('storage/' . $user->profile_image) : null
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                    'error' => $e->getMessage()
                ], 404);
            }
        }
    }
