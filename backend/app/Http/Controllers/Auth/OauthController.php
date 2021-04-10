<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use App\Models\SocialAccount;
use App\Models\User;
use App\Http\Proxy\AuthenticateProxy;
use App\Models\Instructor;
use App\Models\Student;
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
                $response = $this->authProxy->attemptSocial($social, $socialProvider->getId());
                $response['user'] = $user->toArray();
                return response($response);
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
        // dump($input);
        if ($request->isUsePassword) {
            $input['password'] = Hash::make($input['password']);
        }
        $socialAccount = SocialAccount::whereSocialProvider($request->social)
            ->whereSocialId($request->social_id)
            ->first();
        $user = User::create($input);
        if ($request->role == UserRole::Instructor) {
            $title = new Instructor;
        } else {
            $title = new Student;
        }
        # Send verify email by mails redis queue
        $title->user()->associate($user);
        $title->receive_email = $user->email;
        $title->save();
        $socialAccount->user()->associate($user);
        $socialAccount->save();
        $user->touchVerifyEmail();
        $response = $this->authProxy->attemptSocial($request->social, $request->social_id);
        $response['user'] = $user->toArray();
        return response($response);
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
