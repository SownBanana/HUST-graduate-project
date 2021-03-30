<?php

namespace App\Http\Proxy;

use Illuminate\Foundation\Application;
use Exception;
// use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthenticateProxy
{
    const REFRESH_TOKEN = 'refreshToken';

    private $apiConsumer;

    private $cookie;

    private $request;

    // private $userRepository;

    public function __construct(Application $app)
    {
        $this->apiConsumer = $app->make('apiconsumer');
        $this->cookie = $app->make('cookie');
        $this->request = $app->make('request');
    }

    /**
     * Attempt to create an access token using user credentials
     *
     * @param string $email
     * @param string $password
     */
    public function attemptLogin($email, $password)
    {
        return $this->proxy('password', [
            'username' => $email,
            'password' => $password
        ]);
    }
    /**
     * Attempt to create an access token using user credentials
     *
     * @param string $social_provider
     * @param string $social_id
     */
    public function attemptSocial($social_provider, $social_id)
    {
        return $this->proxy('social', [
            'social_provider' => $social_provider,
            'social_id' => $social_id
        ]);
    }

    /**
     * Attempt to refresh the access token used a refresh token that
     * has been saved in a cookie
     */
    public function attemptRefresh($refreshToken=null)
    {
        if (is_null($refreshToken)) {
            $refreshToken = $this->request->cookie(self::REFRESH_TOKEN);
        }

        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }

    /**
     * Proxy a request to the OAuth server.
     *
     * @param string $grantType what type of grant type should be proxied
     * @param array $data the data to send to the server
     */
    public function proxy($grantType, array $data = [])
    {
        $data = array_merge($data, [
            'client_id'     => env('OAUTH_CLIENT_ID'),
            'client_secret' => env('OAUTH_CLIENT_SECRET'),
            'grant_type'    => $grantType
        ]);

        # Guzzle if deploy AuthServer in other server.
        // $client = new Client();
        // $res = $client->request('POST', env('APP_URL').'/oauth/token', [
        //     'form_params' => $data
        // ]);

        $response = $this->apiConsumer->post('/oauth/token', $data);
        // var_dump(env('OAUTH_CLIENT_SECRET'));
        // dd($response);
        if (!$response->isSuccessful()) {
            throw new Exception();
        }

        $data = json_decode($response->getContent());

        // Create a refresh token cookie
        $this->cookie->queue(
            self::REFRESH_TOKEN,
            $data->refresh_token,
            864000, // 10 days
            null,
            null,
            false,
            true // HttpOnly
        );

        return [
            'status' => 'success',
            'access_token' => $data->access_token,
            'refresh_token' => $data->refresh_token,
            'expires_in' => $data->expires_in,
        ];
    }

    /**
     * Logs out the user. We revoke access token and refresh token.
     * Also instruct the client to forget the refresh cookie.
     */
    public function logout()
    {
        $accessToken = Auth::user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        $this->cookie->queue($this->cookie->forget(self::REFRESH_TOKEN));
    }
}
