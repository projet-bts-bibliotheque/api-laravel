<?php

namespace App\Http\Controllers;

use Enums\Status;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller {

    private static function getUser($id) {
        return User::where('id', '=', $id)->firstOr(function () {
            return Status::NOT_FOUND;
        });
    }

    public function index(Request $request) {
        $users = User::all();
        return response()->json($users);
    }

    public function register(Request $request) {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()
            ], 400);
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        
        event(new Registered($user));

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function forgotPassword(Request $request) {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()
            ], 400);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
                    ? response()->json(['message' => __($status)])
                    : response()->json(['message' => __($status)], 400);
    }

    public function forgotPasswordOther(Request $request, $id) {
        $user = $this->getUser($id);
        if($user == Status::NOT_FOUND) return response()->json([
            "message" => "User not found"
        ], 404);

        $status = Password::sendResetLink(
            $user->only('email')
        );

        return $status === Password::ResetLinkSent
                    ? response()->json(['message' => __($status)])
                    : response()->json(['message' => __($status)], 400);
    }

    public function resetPassword(Request $request) {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'token' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
                    ? response()->json(['message' => __($status)])
                    : response()->json(['message' => __($status)], 400);
    }

    public function me(Request $request)
    {
        return $request->user();
    }

    public function sendNotification (Request $request) {
        $request->user()->sendEmailVerificationNotification();
    
        return back()->with('message', 'Verification link sent!');
    }

    public function verifyEmail(EmailVerificationRequest $request) {
        $request->fulfill();

        return response()->json(['message' => 'Email verified']);
    }

    public function destroyOther(Request $request, $id) {
        $user = $this->getUser($id);
        if($user == Status::NOT_FOUND) return response()->json([
            "message" => "User not found"
        ], 404);

        $user->delete();

        return response()->json([
            'message' => 'The user has been deleted.'
        ], 200);
    }

    public function delete(Request $request) {
        $request->user()->tokens()->delete();
        $request->user()->delete();

        return response()->json([
            'message' => 'The user has been deleted.'
        ], 200);
    }
}