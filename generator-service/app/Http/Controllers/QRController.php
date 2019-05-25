<?php


namespace App\Http\Controllers;

use App\Http\Services\JwtService;
use Illuminate\Http\JsonResponse;
use PHPUnit\Util\Json;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class QRController
{
    public function __construct()
    {
        $this->jwtService = new JwtService();
    }

    public function generate(Request $request)
    {
        $date = date('Y-m-d');
        $classAttendace = [
            'class_schedule_id' => $request->class_schedule_id,
            'date' => $date
        ];
        $classAttendaceId = DB::table('class_attendances')
                            ->insertGetId($classAttendace);

        $classAttendace['id'] = $classAttendaceId;

        $token = $this->jwtService->generate($classAttendace, $request->time);

        return response()->json([
            "status" => true,
            "data" => [
                "token" => $token
            ]
        ], 200);
    }

    public function decode(Request $request)
    {
        $data = $this->jwtService->decode($request->token);

        if ($data instanceof JsonResponse)
            return $data;

        return response()->json([
            "status" => true,
            "data" => $data
        ], 200);
    }
}
