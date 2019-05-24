<?php

namespace App\Http\Controllers;

class MahasiswaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function notifications(Request $request){
        $user=$request->user;
    }
    public function presences(Request $request){
        $user=$request->user;
        $nim=$request->nim;
        $qrdata=$request->qrdata;
        $geolocation=$request->geolocation;
    }
    public function courses(Request $request){
        $user=$request->user;
    }
    //
}
