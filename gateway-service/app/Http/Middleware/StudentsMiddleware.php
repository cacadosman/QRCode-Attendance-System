<?php


namespace App\Http\Middleware;

use App\Http\Services\JwtService;
use Closure;
use Illuminate\Http\JsonResponse;

class StudentsMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $auth = JwtService::handle($request->token);

        if ($auth instanceof JsonResponse)
            return $auth;

        if ($auth->role !== "mahasiswa")
        {
            return response()->json([
                'error' => 'Permission Denied.'
            ], 400);
        }

        $request->auth = $auth;
        return $next($request);
    }
}
