<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\SignupRequest;
use App\Http\Resources\Common\UserResource;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    function login(LoginRequest $request)
    {
        if (!Auth::attempt(collect($request->validated())->except(['language'])->toArray())) {
            return response()->json([
                'message' => 'Wrong email/password.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();
        /* TO PREVENT MULTIPLE TOKEN GENERATION */
        // $user->tokens()->delete();
        $token = $user->createToken('app-token')->plainTextToken;

        return response()->json([
            'message'   => 'Success',
            'user'      => new UserResource($user),
            'token'     => $token
        ], Response::HTTP_OK);
    }

    function signup(SignupRequest $request)
    {
        return DB::transaction(function () use ($request) {
            if (User::create($request->validated())) {
                return response()->json([
                    'message' => 'You have successfully registered!'
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Registration failed! Please try again.'
            ], Response::HTTP_CONFLICT);
        });
    }
}
