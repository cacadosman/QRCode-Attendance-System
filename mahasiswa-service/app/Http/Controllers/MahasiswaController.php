<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        $data = DB::table('students as S')
                ->join('student_attendances as SA', 'S.id', '=', 'SA.student_id')
                ->join('class_attendances as CA', 'SA.class_attendance_id', '=', 'CA.id')
                ->join('class_schedules as CS', 'CA.class_schedule_id', '=', 'CS.id')
                ->join('classes as C', 'CS.class_id', '=', 'C.id')
                ->where('S.user_id', '=', $request->auth['id'])
                ->get();

        if (!$data)
            return response()->json(['status' => false],200);

        return response()->json([
            'status' => true,
            'data' => $data
        ],200);

    }
  
    public function presences(Request $request){

        $client = new Client([
            "base_uri" => env('APP_URL') . env('GENERATOR_LOCAL')
        ]);


        $response = $client->request('POST', '/decode', [
            'json' => [
                'token' => $request->qrtoken
            ]
        ]);

        $qrData = json_decode($response->getBody()->getContents());

        if (!$qrData->status)
            return response()->json([
                "status" => false,
                "error" => "Tidak dapat memproses QR."
            ], 200);

        $user_id = 1;
        $class_attendance_id = $qrData->data;
        $geolocation = $request->geolocation;
        $created_at = date("Y-m-d H:i:s", time());

        $point = DB::select(DB::raw("select point(:lat, :lng)"), [
            "lat" => $geolocation['lat'],
            "lng" => $geolocation['lng']
        ]);

        $status = DB::insert("insert into student_attendances (class_attendance_id, student_id, geolocation, created_at) values (?, ?, point(?, ?), ?)", [
            $class_attendance_id,
            $user_id,
            $geolocation['lat'],
            $geolocation['lng'],
            $created_at
        ]);

        if(!$status)
            return response()->json(['status'=> false],200);
            
        return response()->json(['status' => true],200);
        
    }

    public function courses(Request $request){
        $data = DB::table('students AS s')
                    ->join('student_classes AS s_c','s_c.id','=','s.user_id')
                    ->join('classes AS c','c.id','=','s_c.class_id')
                    ->join('courses AS co','co.id','=','c.course_id')
                    ->join('class_schedules as cs', 'c.id', '=', 'cs.class_id')
                    ->join('class_attendances as ca', 'cs.id', '=', 'ca.class_schedule_id')
                    ->join('student_attendances as st', 'ca.id', '=', 'st.class_attendance_id')
                    ->groupBy('co.id', 'co.name')
                    ->select('co.id', 'co.name', DB::raw('count(st.id) as attendances'))
                    ->where('s.user_id','=',$request->auth['id'])
                    ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ],200);
    }
}
