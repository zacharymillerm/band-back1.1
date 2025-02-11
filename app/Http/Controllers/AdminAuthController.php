<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\AdminPasswordMail;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt($credentials)) {
                $token = Auth::user()->createToken('AdminAccess')->plainTextToken;

                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token,
                ], 200);
            }

            return response()->json(['message' => 'Invalid credentials'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'adding' => 'boolean',
            'editing' => 'boolean',
            'deleting' => 'boolean',
        ]);

        try {
            $generatedPassword = $validatedData['password'];
            $admin = User::create([
                'name' => $validatedData['name'],
                'lastname' => $validatedData['lastname'],
                'email' => $validatedData['email'],
                'password' => Hash::make($generatedPassword),
                'adding' => $validatedData['adding'],
                'editing' => $validatedData['editing'],
                'deleting' => $validatedData['deleting'],
            ]);

            Mail::to($admin->email)->send(new AdminPasswordMail($admin, $generatedPassword));

            return response()->json([
                'message' => 'Registration successful',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // Admin Logout
    public function logout(Request $request)
    {
        try {
            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logout successful'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'lastname' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'adding' => 'sometimes|boolean',
            'editing' => 'sometimes|boolean',
            'deleting' => 'sometimes|boolean',
        ]);

        try {
            $user->name = $validatedData['name'] ?? $user->name;
            $user->email = $validatedData['email'] ?? $user->email;

            if ($validatedData['password']) {
                $generatedPassword = $validatedData['password'];
                $user->password = Hash::make($generatedPassword);
                
                Mail::to($user->email)->send(new AdminPasswordMail($user, $generatedPassword));
            }

            $user->adding = $validatedData['adding'] ?? $user->adding;
            $user->editing = $validatedData['editing'] ?? $user->editing;
            $user->deleting = $validatedData['deleting'] ?? $user->deleting;

            $user->save();

            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();
        $request->validate(['email' => 'required|email|unique:users,email,' . $user->id]);
        $user->email = $request->email;
        $generatedPassword = Str::random(8);
        $user->password = Hash::make($generatedPassword);
        Mail::to($user->email)->send(new AdminPasswordMail($user, $generatedPassword));
        $user->save();

        return response()->json(['message' => 'Email updated successfully'], 200);
    }
    
    public function updateEmailAddress(Request $request)
    {
        $user = Auth::user();
        $request->validate(['email' => 'required|email|unique:users,email,' . $user->id]);
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'Email Address updated successfully'], 200);
    }
}
