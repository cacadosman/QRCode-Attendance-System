<?php


namespace App\Http\Controllers;

use Validator;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected function jwt($user) {
        if ($user->role === 'dosen') {
            $user = DB::table('users AS u')
                    ->where('u.id', $user->id)
                    ->join('lecturers As l', 'u.id', '=', 'l.user_id')
                    ->select('u.id', 'name', 'email', 'nid', 'study_program_id', 'role')
                    ->first();
        } else {
            $user = DB::table('users AS u')
                    ->where('u.id', $user->id)
                    ->join('students As s', 'u.id', '=', 's.user_id')
                    ->select('u.id', 'name', 'email', 'nim', 'role')
                    ->first();
        }

        $payload = [
            'iss' => "manpro-qr", // Issuer of the token
            'sub' => (array) $user, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('SALT'));
    }

    public function authenticate(Request $request) {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        // Find the user by email
        $user = DB::table('users')->where('email', $request->email)->first();
        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the
            // below respose for now.
            return response()->json([
                'status' => false,
                'error' => 'Email does not exist.'
            ], 200);
        }
        // Verify the password and generate the token
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => true,
                'token' => $this->jwt($user)
            ], 200);
        }
        // Bad Request response
        return response()->json([
            'status' => false,
            'error' => 'Email or password is wrong.'
        ], 200);
    }
}
