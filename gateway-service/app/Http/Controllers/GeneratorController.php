<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GeneratorController
{
    public function generate(Request $request)
    {
        $client = new Client([
            "base_uri" => env('APP_URL') . env('GENERATOR_LOCAL')
        ]);

        $response = $client->request('POST', '/generate', [
            'json' => [
                'class_schedule_id' => $request->class_schedule_id,
                'time' => $request->time
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
