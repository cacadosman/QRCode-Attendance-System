<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
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

    public function courses(Request $request)
    {
        $user_id = json_decode(urldecode($request->auth))->id;

        $data = DB::table('lecturers AS l')
                     ->join('lecturer_classes AS l_c','l_c.lecturer_id','=','l.id')
                     ->join('classes AS c','c.id','=','l_c.class_id')
                     ->join('courses AS co','co.id','=','c.course_id')
                     ->select('c.id', 'c.name','co.code','co.credit')
                     ->where('l.user_id','=',$user_id)->get();

        return response()->json([
            "status" => true,
            "data" => $data
        ], 200);
    }

    public function classSchedules(Request $request)
    {
        $request->auth = json_decode(urldecode($request->auth));

        $data = DB::table('lecturers as L')
                ->join('lecturer_classes as LC', 'L.id', 'LC.lecturer_id')
                ->join('classes as C', 'LC.class_id', 'C.id')
                ->join('class_schedules as CS', 'C.id', 'CS.class_id')
                ->join('class_attendances as CA', 'CS.id', 'CA.class_schedule_id')
                ->where('L.user_id', '=',$request->auth->id)
                ->where('C.id', '=', $request->class_id)
                ->select('CA.id', 'CA.date')
                ->get();

        return response()->json([
            "status" => true,
            "data" => $data
        ], 200);
    }

    public function createSession(Request $request)
    {
        $date = date('Y-m-d');
        $classAttendace = [
            'class_schedule_id' => $request->class_schedule_id,
            'date' => $date
        ];

        $isEligible = DB::table('class_schedules as CS')
                        ->join('classes as C', 'CS.class_id', 'C.id')
                        ->join('lecturer_classes as LC', 'C.id', 'LC.class_id')
                        ->join('lecturers as L', 'LC.lecturer_id', 'L.id')
                        ->where('L.user_id', '=', $request->auth['id'])
                        ->where('CS.id', '=', $request->class_schedule_id)
                        ->count();

        if (!$isEligible)
            return response()->json([
                "status" => false,
                "data" => [
                    "message" => "Jadwal kelas tidak ditemukan."
                ]
            ], 200);

        $hasAnotherClassSession = DB::table('class_attendances')
            ->where('class_schedule_id', '=', $classAttendace['class_schedule_id'])
            ->where('date', '=', $classAttendace['date'])
            ->count();

        if ($hasAnotherClassSession)
            return response()->json([
                "status" => false,
                "data" => [
                    "message" => "Sesi kelas sudah dibuat."
                ]
            ], 200);

        $result = DB::table('class_attendances')
                            ->insert($classAttendace);

        if (!$result)
            return response()->json([
                "status" => false,
                "data" => [
                    "message" => "Pembuatan sesi kelas gagal."
                ]
            ], 200);

        return response()->json([
            "status" => true,
            "data" => [
                "message" => "Pembuatan sesi kelas berhasil."
            ]
        ]);
    }

    public function generateQr(Request $request)
    {
        $isEligible = DB::table('class_attendances as CA')
            ->join('class_schedules as CS', 'CA.class_schedule_id', 'CS.id')
            ->join('classes as C', 'CS.class_id', 'C.id')
            ->join('lecturer_classes as LC', 'C.id', 'LC.class_id')
            ->join('lecturers as L', 'LC.lecturer_id', 'L.id')
            ->where('L.user_id', '=', $request->auth['id'])
            ->where('CA.id', '=', $request->class_attendance_id)
            ->count();

        if (!$isEligible)
            return response()->json([
                "status" => false,
                "data" => [
                    "message" => "Sesi kelas tidak ditemukan."
                ]
            ], 200);

        $client = new Client([
            "base_uri" => env('APP_URL') . env('GENERATOR_LOCAL')
        ]);

        $response = $client->request('POST', '/generate', [
            'json' => [
                'class_attendance_id' => $request->class_attendance_id,
                'time' => $request->time
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

}
