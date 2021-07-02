<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminsNumber = 0;
        $usersNumber = 10;
        $instructorsNumber = 20;
        $studentNumber = 20;
        factory(App\Models\User::class, $adminsNumber)->state('admin')->create();
        factory(App\Models\User::class, $studentNumber)->state('student')->create();
        factory(App\Models\User::class, $instructorsNumber)->state('instructor')->create();
        factory(App\Models\User::class, $usersNumber)->create();
    }
}
