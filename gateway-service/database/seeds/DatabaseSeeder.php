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
        DB::insert("insert into locations (faculty, area) values ('FMIPA', ST_GeomFromText('POLYGON((-7.7666080333064 110.37558574299,-7.766873793671 110.37644404987,-7.7666133485153 110.37661571125,-7.7670545106229 110.37806410412,-7.7682025929722 110.37760812858,-7.7673734227014 110.37485081772,-7.7669694673611 110.37537116626,-7.7666080333064 110.37558574299))'))");

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

        $students_id = DB::table('students')->where('nim', '=', "123456")->first()->id;

        $study_program_id = DB::table('study_programs')->where('code', '=', $programCode)->first()->id;

        DB::table('lecturers')->insert([
            "user_id" => $dosen->id,
            "nid" => "123456",
            "study_program_id" => $study_program_id
        ]);

        $lecturer_id = DB::table('lecturers')->where('user_id', '=', $dosen->id)->first()->id;

        DB::table('courses')->insert([
            "study_program_id" => $study_program_id,
            "name" => "Manajemen Proyek",
            "code" => "NII94-ABCD",
            "type" => "wajib",
            "semester" => "genap",
            "credit" => 3,
            "status" => "aktif"
        ]);

        $course_id = DB::table("courses")->where("code", "=","NII94-ABCD")->first()->id;

        DB::table('classes')->insert([
            "course_id" => $course_id,
            "name" => "Manajemen Proyek ILKOM-B",
            "lesson_year" => 2019
        ]);

        $class_id = DB::table('classes')->where('name', '=', 'Manajemen Proyek ILKOM-B')->first()->id;

        DB::table('lecturer_classes')->insert([
            "lecturer_id" => $lecturer_id,
            "class_id" => $class_id,
            "period" => "UTS"
        ]);

        $lecturer_class_id = DB::table('lecturer_classes')
            ->where('lecturer_id', '=', $lecturer_id)->first()->id;

        DB::table('student_classes')->insert([
            "student_id" => $students_id,
            "class_id" => $class_id
        ]);

        $student_class_id = DB::table('student_classes')
            ->where('student_id', '=', $students_id)->first()->id;

        DB::table('class_schedules')->insert([
            "class_id" => $class_id,
            "credit" => 3,
            "day" => 1,
            "time" => "14:30:00"
        ]);

        $class_schedule_id = DB::table('class_schedules')
            ->where('class_id', '=', $class_id)->first()->id;

        DB::table('class_attendances')->insert([
            "class_schedule_id" => $class_schedule_id,
            "date" => "2019-05-27"
        ]);

    }
}
