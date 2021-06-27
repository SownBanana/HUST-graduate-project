<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Jobs\SendVerifyEmail;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use phpseclib\Crypt\Hash;

class CreateAdminController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $user = User::create($request->all());
        $title = new Admin();
        $title->user()->associate($user);
        $title->save();
        $user->role = UserRole::Admin;
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->touchVerifyEmail();
        return response()->json(['status' => 'success'], 201);
    }
}
