<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function notifications(Request $request){
        $user_id=$request->user_id;
        //$status = DB::table('users AS u')
                      //->join('students AS s','u.id','=','s.user_id')
        $status = DB::table('students AS s')
                      ->join('student_attendances AS s_a','s.id','=','s_a.student_id')
                      ->join('class_attendances AS c_a','c_a.id ','=','s_a.class_attendances_id')
                      ->join('class_schedules AS c_s','c_s.id','=','c_a.class_schedule_id')
                      ->join('classes AS c','c.id','=','c_s.class_id')
                      ->select('s.id','c.class_name','s_a.created_at')
                      ->where('s.user_id','=',$user_id)->get();

        if (!$status)
            return response()->json(['status' => false],403);

        return response()->json(['status' => true],200);

    }
    public function presences(Request $request){
        $user_id=$request->user_id;
        $class_attendance_id=$request->class_attendance_id;
        $geolocation=$request->geolocation;
        $created_at=time();
        $status= DB::table('student_attendances AS s_a')->insert(
            ['class_attendance_id'=>$class_attendance_id,'student_id'=>$user_id,'geolocation'=>$geolocation,'created_at'=>$created_at]
        );

        if(!$status)
            return response()->json(['status'=> false],403);
            
        return response()->json(['status' => true],200);
        
    }

    public function courses(Request $request){
        $user_id=$request->user_id;

        $status=DB::table('students AS s')
                    ->join('student_classes AS s_c','s_c.id','=','s.user_id')
                    ->join('classes AS c','c.id','=','s_c.class_id')
                    ->join('courses AS co','co.id','=','c.course_id')
                    ->select('s.id','group_concat(co.course_name)')->where('s.user_id','=',$user_id);
    }
    //
}
