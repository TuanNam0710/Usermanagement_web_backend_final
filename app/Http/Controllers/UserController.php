<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\PasswordReset;
use Carbon\Carbon;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        return response(['message' => 'Register completed!'], 201);
    }

    public function index()
    {
        $user = auth()->user();
        return UserResource::collection(User::all());
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        return response(['message' => 'Create user successfully!'], 201);
    }

    function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        return response(['message' => 'Edit user information successfully!'], 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response(['message' => 'Delete user successfully!'], 204);
    }

    public function forgot(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!isset($user->id)) {
            return response(['error' => 'User with this email does not exists'], 401);
        }

        $token = random_int(100000, 999999);
        $expired_at = Carbon::now()->addMinutes(5);
        while (PasswordReset::where('token', $token)->first()) {
            $token = random_int(100000, 999999);
        };

        Mail::to($user)->send(new ResetPasswordMail($token));
        // dd(1);

        $user_check = PasswordReset::where('email', $request->email);
        if (isset($user_check)) {
            $user_check->delete();
        }

        $passwordReset = new PasswordReset();
        $passwordReset->email = $user->email;
        $passwordReset->token = $token;
        $passwordReset->expired_at = $expired_at;
        $passwordReset->save();
    }

    public function checkOTP(Request $request)
    {
        $passwordReset = PasswordReset::where('token', $request->token)->where('expired_at', '>=', Carbon::now())->first();
        if (!isset($passwordReset->email)) {
            return response()->json(['error' => 'Invalid token'], 401);
        };
        $user = User::where('email', $passwordReset->email)->first();
        return response()->json($user, 200);
    }

    public function resetPassword(Request $request)
    {
        $user = User::find($request->id);
        $passwordReset = PasswordReset::where('email', $user->email)->first();
        $passwordReset->delete();
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['message' => 'Password changed successfully'], 202);
    }
}
