<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $request->merge([
            'password' => bcrypt($request->password)
        ]);

        $user = User::query()->create($request->validated());

        $success['token'] = $user->createToken('chatApp')->plainTextToken;
        $success['user'] = new UserResource($user);

        return response()->json([
            'user' => $success['user'],
            'token' => $success['token'],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        if (!auth()->attempt($validator->validated())) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 422);
        }

        $user = auth()->user();

        $success['token'] = $user->createToken('chatApp')->plainTextToken;
        $success['user'] = new UserResource($user);

        return response()->json([
            'user' => $success['user'],
            'token' => $success['token']
        ], 201);

    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }

}
