<?php

namespace App\Http\Controllers\Course;

use App\Enums\CourseType;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Notifications\ChangeCourseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use League\Flysystem\Exception;

class SetStatusController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        if ($request->filled('course_id')) {
            $course = Course::findOrFail($request->course_id);
            if (Auth::user()->role == UserRole::Admin || Auth::user()->id == $course->instructor->id) {
                DB::beginTransaction();
                try {
                    DB::commit();
                    if ($request->status != CourseType::Draft &&
                        (trim($course->title) == ''
                            || $course->sections()->count() == 0)) {
                        return response()->json([
                            'status' => 'failed',
                            'message' => 'validate failed'
                        ]);
                    }
                    $course->update(['status' => $request->status]);
                    DB::commit();
                    return \response()
                        ->json([
                            "status" => "success",
                            "course_status" => $course->status
                        ]);
                } catch (Exception $e) {
                    DB::rollBack();
//            throw $e;
                    return \response(["status" => "error", "message" => $e]);
                }
            }
        } elseif ($request->filled('ids')) {
            if (Auth::user()->role == UserRole::Admin) {
                $courses = Course::whereIn('id', $request->ids);
                DB::beginTransaction();
                try {
                    DB::commit();
                    if ($request->status == CourseType::Rejected) {
                        $courses->update([
                            'status' => $request->status,
                            'reject_reason' => $request->reason
                        ]);
                    } else
                        $courses->update([
                            'status' => $request->status,
                            'reject_reason' => null
                        ]);
                    DB::commit();
                    $courses = $courses->get();
                    foreach ($courses as $course) {
                        Notification::send($course->instructor, new ChangeCourseStatus($course));
                    }
                    return \response()
                        ->json([
                            "status" => "success"
                        ]);
                } catch (Exception $e) {
                    DB::rollBack();
//            throw $e;
                    return \response(["status" => "error", "message" => $e]);
                }
            }
        }
        return response()->json([
            'status' => 'failed',
            'message' => 'not have permission'
        ]);
    }
}
