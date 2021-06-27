<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerifyUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $user = User::findOrFail($request->id);
        if ($request->status) {
            $user->confirmation_code = null;
            $user->touchVerifyEmail();
        } else {
            $user->email_verified_at = null;
            $user->save();
        }
        return response()->json([
            'status' => 'success'
        ]);
    }
}
