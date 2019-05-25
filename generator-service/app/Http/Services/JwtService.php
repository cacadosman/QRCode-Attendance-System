<?php


namespace App\Http\Services;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

class JwtService
{
    public function generate(array $data, int $time)
    {
        $payload = [
            'iss' => "qr-manpro", // Issuer of the token
            'sub' => $data, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + $time // Expiration time
        ];

        return JWT::encode($payload, env('SALT'));
    }

    public function decode(string $token)
    {
        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        try {
            $data = JWT::decode($token, env('SALT'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }

        return $data->sub;
    }
}
