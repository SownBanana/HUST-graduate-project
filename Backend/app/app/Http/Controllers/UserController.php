<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        if ($request->role != null && $request->role == UserRole::Admin) {
            return response()->json(['error'=>'You don\'t have permission to create admin account'], 403);
        }
        $confirmation_code = time().uniqid(true);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['confirmation_code'] = $confirmation_code;
        $user = User::create($input);
        // $success['token'] =  $user->createToken('VLearn')-> accessToken;
        $success['username'] =  $user->username;
        return response()->json(['success'=>$success], 201);
    }

    public function login(LoginRequest $request)
    {
        $login_info = $request->login;
        // $pass = $request->password;
        if (filter_var($login_info, FILTER_VALIDATE_EMAIL)) {
            //user sent their email
            $credentials['email'] = $login_info;
        } else {
            //they sent their username instead
            $credentials['username'] = $login_info;
        }
        $credentials['password'] = $request->password;
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('VLearn')-> accessToken;
            $success['username'] =  $user->username;
            return response()->json(['success'=>$success], 200);
        } else {
            return response()->json(['fail'=>'Login fail'], 401);
        }
    }

    public function check_passport()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], 200);
    }
}
