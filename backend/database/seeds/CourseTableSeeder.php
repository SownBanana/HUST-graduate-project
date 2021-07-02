<?php

use Illuminate\Database\Seeder;

class CourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $randomCoursesNumber = 100;
        $publishCourseNumber = 100;
        $normalCourseNumber = 100;
        $liveCourseNumber = 100;
        factory(App\Models\Course::class, $randomCoursesNumber)->create();
        factory(App\Models\Course::class, $publishCourseNumber)->state('publish')->create();
        factory(App\Models\Course::class, $normalCourseNumber)->state('normal')->create();
        factory(App\Models\Course::class, $liveCourseNumber)->state('live')->create();
    }
}
