<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendVerifyEmail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        # Can't make a admin/mod account
        if ($request->role != null && ($request->role == UserRole::Admin || $request->role == UserRole::Mod)) {
            return response()->json(['error'=>'You don\'t have permission to create admin/mod account'], 403);
        }
        $confirmation_code = time().uniqid(true);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['confirmation_code'] = $confirmation_code;
        $user = User::create($input);
        # Send verify email by mails redis queue
        dispatch(new SendVerifyEmail($user))->onQueue('mails');
        $data['confirmation_code'] = $user->confirmation_code;
        $success['username'] =  $user->username;
        return response()->json(['success'=>$success], 201);
    }

    public function login(LoginRequest $request)
    {
        $login_info = $request->login;
        if (filter_var($login_info, FILTER_VALIDATE_EMAIL)) {
            # user sent their email
            $credentials['email'] = $login_info;
        } else {
            # they sent their username instead
            $credentials['username'] = $login_info;
        }
        $credentials['password'] = $request->password;
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            # Check if user verify email
            if ($user->email_verified_at == null) {
                return response()->json(['failed'=>'Verify your email first'], 401);
            }
            $success['token'] =  $user->createToken('VLearn')-> accessToken;
            $success['username'] =  $user->username;
            return response()->json(['success'=>$success], 200);
        } else {
            return response()->json(['failed'=>'Login failed'], 401);
        }
    }

    public function verify($code)
    {
        $user = User::where('confirmation_code', $code)->first();
        if ($user) {
            $user->confirmation_code = null;
            $user->email_verified_at = now();
            $user->save();
            return Redirect::to('http://localhost');
        } else {
            abort(404, 'Link xác thực hết hạn');
        }
    }


    public function check_passport()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], 200);
    }
}
