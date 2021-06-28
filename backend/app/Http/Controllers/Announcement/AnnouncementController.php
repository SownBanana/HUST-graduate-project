<?php

namespace App\Http\Controllers\Announcement;

use App\Announcement;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $matchThese = [];
        $matches = [];
        foreach ($matches as $field) {
            if ($request->filled($field) && $request->$field != 'vlearn_all_value') {
                $matchThese[$field] = $request->$field;
            }
        }
        $search = "";
        if ($request->has('search')) {
            $search = $request->search;
        }
        $perPage = 9;
        $columns = array('*');
        $order = "desc";
        if ($request->has('perPage')) {
            $perPage = $request->perPage;
        }
        if ($request->has('columns')) {
            $columns = $request->columns;
        }
        if ($request->has('order')) {
            $order = $request->order;
        }
        $announcements = Announcement::where('title', 'like', '%' . $search . '%')
            ->where($matchThese)
            ->orderBy('created_at', $order);
        if ($request->filled('targets')) {
            $tg = $request->targets;

            $targets = explode(',', $tg);
            $announcements->where(function ($query) use ($targets) {
                foreach ($targets as $target) {
                    if ($target == 'all') {
                        $query->orWhereNull('target');
                    } else {
                        $query->orWhere('target', 'LIKE', '%' . $target . '%');
                    }
                }
            });
        }
        return response()->json(['status' => 'success',
            'data' => $announcements->paginate($perPage, $columns)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function store(Request $request)
    {
        $input = $request->all();
        $targets = implode(',', $input['target']);
        $input['target'] = $targets;
        if (in_array(null, $request->target, true)) {
            $input['target'] = null;
        }
        $announcement = Announcement::create($input);
        if ($input['target'] !== null) {
            $acceptRoles = $request->target;
            $users = User::whereIn('role', $acceptRoles)->get();
        } else {
            $users = User::all();
        }
        Notification::send($users, new NewAnnouncement($announcement));
        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        if ($announcement->target !== null && Auth::user()->role != UserRole::Admin) {
            $acceptRoles = explode(',', $announcement->target);
            if (!in_array(Auth::user()->role, $acceptRoles)) {
                return response()->json([
                    'status' => 'failed'
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'data' => $announcement
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
