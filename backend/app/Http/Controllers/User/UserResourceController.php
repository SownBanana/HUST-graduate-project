<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::select(['id', 'name', 'role', 'username', 'avatar_url'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::select(['id', 'name', 'role', 'username', 'avatar_url'])->with('ownerCourses')->find($id);
        if (!$user) {
            $user = User::select(['id', 'name', 'role', 'username', 'avatar_url'])->with('ownerCourses')->where('username', $id)->first();
        }
        if (!$user) {
            return response()->json(["status"=>"fail", "message"=>"not found"]);
        }
        return response()->json(["status"=>"success","type"=>$user->role, "data"=>$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user= Auth::user();
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
        $user->save();
        return response()->json(["status"=>"success", "user"=>$user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
