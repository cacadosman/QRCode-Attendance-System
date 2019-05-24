<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert("insert into locations (faculty, area) values ('FMIPA', ST_GeomFromText('POLYGON((-7.76661 110.3756),(-7.76688 110.37648),(-7.76662 110.37661),(-7.76704 110.37808),(-7.76818 110.37761),(-7.76735 110.37488),(-7.76699 110.37534))'))");

        $programCode = "1337";
        DB::table('study_programs')->insert([
           "code" => $programCode,
            "name" => "Ilmu Komputer"
        ]);

        $mahasiswa = [
            "name" => "Bajigur N",
            "email" => "bajigur@gmail.com",
            "password" => Hash::make('bajigur123'),
            "role" => "mahasiswa"
        ];

        $dosen  = [
            "name" => "ASN",
            "email" => "papi@gmail.com",
            "password" => Hash::make('dosen123'),
            "role" => "dosen"
        ];

        DB::table('users')->insert(
            $mahasiswa
        );

        DB::table('users')->insert(
            $dosen
        );

        $mahasiswa = DB::table('users')->where('email', '=', $mahasiswa["email"])->first();
        $dosen = DB::table('users')->where('email', '=', $dosen["email"])->first();

        DB::table('students')->insert([
            "user_id" => $mahasiswa->id,
            "nim" => "123456"
        ]);

        DB::table('lecturers')->insert([
            "user_id" => $dosen->id,
            "nid" => "123456",
            "study_program_id" => DB::table('study_programs')->where('code', '=', $programCode)->first()->id
        ]);


//        DB::table('locations')->insert
        // $this->call('UsersTableSeeder');
    }
}
