<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    public function check_alive(Request $request)
    {
        return response()->json(['status'=>'healthy'], 200);
    }
}
