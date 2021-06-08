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
        $adminsNumber = 1;
        $usersNumber = 2;
        factory(App\Models\User::class, $adminsNumber)->state('admin')->create();
        factory(App\Models\User::class, $usersNumber)->create();
    }
}
