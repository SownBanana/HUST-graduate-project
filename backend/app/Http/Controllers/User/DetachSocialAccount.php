<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class DetachSocialAccount extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $socialAccount = SocialAccount::find($request->social_id);
        if ($socialAccount) {
            if (Auth::id() != $socialAccount->user->id) {
                return response()->json(["status" => "fail", "message" => "another account"]);
            } else {
                $socialAccount->user_id = NULL;
                $socialAccount->save();
                return response()->json(["status" => "success", "user" => new UserResource(User::with('socialAccounts')->find(Auth::id()))]);
            }
        }
        return response()->json(["status" => "fail", "message" => "not found"]);

    }
}
