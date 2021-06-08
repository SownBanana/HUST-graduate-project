<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class GetRecommendController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        $client = new Client([
            'base_uri' => config('app.recommend_url')
        ]);
        $response = $client->request('GET', 'api/users/' . $id);

        return $response;
    }
}
