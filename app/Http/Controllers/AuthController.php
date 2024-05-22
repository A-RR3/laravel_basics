<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {

        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|min:3|max:100',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:2|max:100|confirmed',
                ]

            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Validation Failed',
                        'errors' => $validator->errors()
                    ],
                    401
                );
            }
            $user = User::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),

                ]
            );

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Registration Successfull',
                    'data' => $user,
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required|string'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            //  $credentials = $request->only('email', 'password');
            $credentials = $validateUser->validated();


            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'invalid credentials',
                ], 403);
            }
            //check email
            // $user = User::where('email', $request->email)->first();

            $token = $request->user()->createToken("API-TOKEN")->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user' => auth()->user(),
                'token' => $token
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        return $request->user();
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout successful']);
        } else {
            return response()->json(['message' => 'No user authenticated'], 401);
        }
    }

    // update user
    public function update(Request $request)
    {

        // return response($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'image' => 'image|mimes:jpg,jpeg,png,svg,webp',
        ], [
            'name.required' => 'name is required.',
            'image.image' => 'only image is allowed',
            'image.mimes' => 'extention is not allowed for image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()->first()
            ], 401);
        }

        $user = User::find(auth()->user()->id);

        if ($request->hasFile('image')) {
            $fileName = $this->saveImage($request, 'uploads/images');
        }

        $user->update([
            'name' => $request->input('name'),
            'image' => $fileName
        ]);

        return response([
            'message' => 'User updated.',
            'user' => $user,
        ], 200);
    }
}
