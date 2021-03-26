<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use App\Models\SocialAccount;
use App\Models\User;
use App\Http\Proxy\AuthenticateProxy;
use App\Traits\PassportToken;

class OauthController extends Controller
{
    // use PassportToken;

    private $authProxy;

    public function __construct(AuthenticateProxy $authProxy)
    {
        $this->authProxy = $authProxy;
    }

    public function loginUrl($social)
    {
        return response()->json([
            'url' => Socialite::driver($social)->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function loginCallback($social)
    {
        $socialProvider = Socialite::driver($social)->stateless()->user();
        $socialAccount = SocialAccount::whereSocialProvider($social)
            ->whereSocialId($socialProvider->getId())
            ->first();

        if ($socialAccount) {
            if ($user = $socialAccount->user) {
                return response($this->authProxy->attemptSocial($social, $socialProvider->getId()));
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

        if (User::whereEmail($socialProvider->getEmail())->first()) {
            return response()->json([
                'status'=>'existed',
                'social_id'=>$socialAccount->id,
            ]);
        } else {
            return response()->json([
                'status'=>'new',
                'social_id'=>$socialAccount->id,
            ]);
        }
    }
}
