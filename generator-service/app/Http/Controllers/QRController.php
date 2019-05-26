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
        $token = $this->jwtService->generate($request->class_attendance_id, $request->time);

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
