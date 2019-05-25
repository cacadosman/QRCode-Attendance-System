<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
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

    public function course_list(Request $request){
        $user_id = $request->user_id;

        $status = DB::table('lecturers AS l')
                     ->join('lecturer_classes AS l_c','l_c.lecturer_id','=','l.id')
                     ->join('classes AS c','c.id','=','l_c.class_id')
                     ->join('courses AS co','co.id','=','c.course_id')
                     ->select('c.name','co.code','co.credit')
                     ->where('l.id','=',$user_id)->get();
        return dd($status);

    }

    //
}
