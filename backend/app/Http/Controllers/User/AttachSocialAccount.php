<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AttachSocialAccount extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, $social)
    {
        $socialProvider = Socialite::driver($social)->stateless()->user();
        $socialAccount = SocialAccount::whereSocialProvider($social)
            ->whereSocialId($socialProvider->getId())
            ->first();

        if ($socialAccount) {
            if ($user = $socialAccount->user) {
                if ($user != Auth::user())
                    return response()->json(["status" => "fail", "message" => "another account"]);
            }
        } else {
            $socialAccount = SocialAccount::create(
                [
                    'social_id' => $socialProvider->getId(),
                    'social_provider' => $social,
                    'social_email' => $socialProvider->getEmail(),
                    'social_name' => $socialProvider->getName(),
                ]
            );
        }

        $user = Auth::user();
        $socialAccount->user()->associate($user);
        $socialAccount->save();
        return response()->json(["status" => "success", "user" => new UserResource(User::with('socialAccounts')->find(Auth::id()))]);
    }
}
