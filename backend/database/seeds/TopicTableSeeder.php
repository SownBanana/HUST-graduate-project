<?php

use Illuminate\Database\Seeder;

class TopicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $baseTopics = [
            "Programming",
            "Java",
            "C/C++",
            "C#",
            "Laravel",
            "PHP",
            "AI",
            "Machine Learning",
            "Computer Vision",
            "Data Science",
            "Science",
            "Life",
            "Art",
            "Adobe",
            "Design",
            "Photoshop",
            "Illustrator",
            "Premiere",
            "Maths",
            "Physics"
        ];

        foreach ($baseTopics as $topic) {
            \Illuminate\Support\Facades\DB::table('topics')->insert([
                'name' => $topic,
            ]);
        }
    }
}
