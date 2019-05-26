<?php


namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Illuminate\Http\Request;

class MahasiswaController
{
    /**
     * @param Request $request
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function presences(Request $request)
    {
        $client = new Client([
            "base_uri" => env('APP_URL') . env('MAHASISWA_LOCAL')
        ]);

        $response = $client->request('POST', '/presences', [
            'json' => [
                'qrtoken' => $request->qrtoken,
                'auth' => $request->auth,
                'geolocation'=> $request->geolocation
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function notifications(Request $request)
    {
        $client = new Client([
            "base_uri" => env('APP_URL') . env('MAHASISWA_LOCAL')
        ]);

        $response = $client->request('POST', '/notifications', [
            'json' => [
                'auth' => $request->auth
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function courses(Request $request)
    {
        $client = new Client([
            "base_uri" => env('APP_URL') . env('MAHASISWA_LOCAL')
        ]);

        $response = $client->request('POST', '/courses', [
            'json' => [
                'auth' => $request->auth
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
