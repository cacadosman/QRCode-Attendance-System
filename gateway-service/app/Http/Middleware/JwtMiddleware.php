<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\DB;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->token;

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

        $request->auth = $credentials->sub;
        return $next($request);
    }
}
