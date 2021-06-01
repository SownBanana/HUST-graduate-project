<?php

namespace App\Http\Controllers\User;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (Auth::user() && Auth::user()->role == UserRole::Admin) {
            return response()->json([
                'status' => 'success',
                'data' => User::all()
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => User::select(['id', 'name', 'role', 'username', 'avatar_url'])->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::select(['id', 'name', 'role', 'username', 'avatar_url', 'introduce'])->with('ownerCourses', 'socialAccounts')->find($id);
        if (!$user) {
            $user = User::select(['id', 'name', 'role', 'username', 'avatar_url', 'introduce'])->with('ownerCourses', 'socialAccounts')->where('username', $id)->first();
        }
        if (!$user) {
            return response()->json(["status" => "fail", "message" => "not found"]);
        }
        return response()->json(["status" => "success", "type" => $user->role, "data" => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($request->introduce != null) {
            $user->introduce = $request->introduce;
        }
        if ($request->name != null) {
            $user->name = $request->name;
        }
        if ($request->avatar_url != null) {
            $user->avatar_url = $request->avatar_url;
        }
        if ($request->password != null && $request->oldPassword != null
            && $user->password == Hash::make($request->oldPassword)) {
            $user->password = $request->password;
        }
        $settings = $user->title;
        if ($request->filled('settings')) {
            switch ($user->role) {
                case UserRole::Instructor:
                {
                    if (isset($request->settings['receive_bought_notification']))
                        $settings->receive_bought_notification = $request->settings['receive_bought_notification'];
                    if (isset($request->settings['receive_report']))
                        $settings->receive_report = $request->settings['receive_report'];
                    if (isset($request->settings['receive_email']))
                        $settings->receive_email = $request->settings['receive_email'];
                    $settings->save();
                }
                case UserRole::Admin:
                {

                }
                default:
                {
                    if (isset($request->settings['receive_course_change']))
                        $settings->receive_course_change = $request->settings['receive_course_change'];
                    if (isset($request->settings['receive_notification']))
                        $settings->receive_notification = $request->settings['receive_notification'];
                    if (isset($request->settings['receive_flower_new_course']))
                        $settings->receive_flower_new_course = $request->settings['receive_flower_new_course'];
                    if (isset($request->settings['receive_email']))
                        $settings->receive_email = $request->settings['receive_email'];
                    $settings->save();
                }
            }
        }
        $user->save();
        return response()->json([
            "status" => "success",
            "data" => new UserResource(User::with('socialAccounts')->find(Auth::id()))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
