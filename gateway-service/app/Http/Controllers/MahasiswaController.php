<?php


namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Illuminate\Http\Request;

class MahasiswaController
{
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
}
