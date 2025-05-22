<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AuthRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6',
            'npp'             => 'required|integer|unique:users,npp',
            'npp_supervisor'  => 'nullable|integer|exists:users,npp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'data'    => $validator->errors()
            ], 400);
        }

        $result = $this->authRepository->register($request->only('name', 'email', 'password', 'npp', 'npp_supervisor'));

        return response()->json([
            'status'  => true,
            'message' => 'User registered successfully',
            'data'    => $result
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'data'    => $validator->errors()
            ], 400);
        }

        $token = $this->authRepository->login($request->only('email', 'password'));

        if (!$token) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
                'data'    => null
            ], 401);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'data'    => ['token' => $token]
        ], 200);
    }

    public function me()
    {
        $user = $this->authRepository->me();

        return response()->json([
            'status'  => true,
            'message' => 'User retrieved successfully',
            'data'    => $user
        ], 200);
    }
}
