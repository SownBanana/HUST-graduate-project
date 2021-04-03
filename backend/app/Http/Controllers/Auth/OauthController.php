<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use App\Models\SocialAccount;
use App\Models\User;
use App\Http\Proxy\AuthenticateProxy;
use App\Traits\PassportToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
                'social_provider' => $social,
                'social_id'=>$socialProvider->getId(),
                'social_email' => $socialProvider->getEmail(),
                'social_name' => $socialProvider->getName(),
                'social_avatar' => $socialProvider->getAvatar(),
            ]);
        } else {
            return response()->json([
                'status'=>'new',
                'social_provider' => $social,
                'social_id'=>$socialProvider->getId(),
                'social_email' => $socialProvider->getEmail(),
                'social_name' => $socialProvider->getName(),
                'social_avatar' => $socialProvider->getAvatar(),
            ]);
        }
    }

    public function createAccountWithSocialProvider(Request $request)
    {
        $input = $request->all();
        if ($request->isUsePassword) {
            $input['password'] = Hash::make($input['password']);
        }
        $socialAccount = SocialAccount::whereSocialProvider($request->social)
            ->whereSocialId($request->social_id)
            ->first();
        $user = User::create($input);
        $socialAccount->user()->associate($user);
        $socialAccount->save();
        return response($this->authProxy->attemptSocial($request->social, $request->social_id));
    }
    public function attachUserWithSocialProvider(Request $request)
    {
        $socialAccount = SocialAccount::whereSocialProvider($request->social)
            ->whereSocialId($request->social_id)
            ->first();
        $user = User::whereUsername($request->username)->first();
        $socialAccount->user()->associate($user);
        $socialAccount->save();
        return response($this->authProxy->attemptSocial($socialAccount->social_provider, $socialAccount->social_id));
    }
}
