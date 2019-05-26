<?php


namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DosenController
{
    public function courses(Request $request)
    {
        $client = new Client([
            "base_uri" => env('APP_URL') . env('DOSEN_LOCAL')
        ]);

        $response = $client->request('GET', '/courses', [
            'query' => [
                'auth' => urlencode(json_encode($request->auth))
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function classSchedules(Request $request)
    {
        $client = new Client([
            "base_uri" => env('APP_URL') . env('DOSEN_LOCAL')
        ]);

        $response = $client->request('GET', '/schedules', [
            'query' => [
                'auth' => urlencode(json_encode($request->auth)),
                'class_id' => $request->class_id
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function createSession(Request $request)
    {
        $client = new Client([
            "base_uri" => env('APP_URL') . env('DOSEN_LOCAL')
        ]);

        $response = $client->request('POST', '/sessions', [
            'json' => [
                'auth' => $request->auth,
                'class_schedule_id' => $request->class_schedule_id
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function generateQr(Request $request)
    {
        $client = new Client([
            "base_uri" => env('APP_URL') . env('DOSEN_LOCAL')
        ]);

        $response = $client->request('POST', '/generate', [
            'json' => [
                'auth' => $request->auth,
                'class_attendance_id' => $request->class_attendance_id,
                'time' => $request->time
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
