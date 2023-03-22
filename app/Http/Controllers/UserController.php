<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function __construct(
        protected UserResource $userResource
    ) {
        $this->middleware('auth:api');
    }

    public function collection(array|object $resources): JsonResource
    {
        return $this->userResource->collection($resources);
    }

    public function resource(array|object $resource): JsonResource
    {
        return $this->userResource->make($resource);
    }

    function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    function login(Request $request)
    {
        dd($request);
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
    }

    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'users' => $this->collection($users),
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json([
            'status' => 'success',
            'user' => $this->resource($user),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user = User::find($id);
        $user->title = $request->title;
        $user->description = $request->description;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'user updated successfully',
            'user' => $user,
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'user deleted successfully',
            'user' => $user,
        ]);
    }
}
