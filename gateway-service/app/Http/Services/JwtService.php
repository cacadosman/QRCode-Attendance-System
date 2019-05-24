<?php


namespace App\Http\Services;


use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

class JwtService
{
    public static function handle($token)
    {
        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        try {
            $credentials = JWT::decode($token, env('SALT'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }

        return $credentials->sub;
    }
}
