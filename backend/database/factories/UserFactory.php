<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $avatars = [
        'https://www.uidownload.com/files/790/68/996/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/990/169/304/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/893/712/8/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/762/371/766/free-set-of-material-design-avatars.png',
        'https://assets.materialup.com/uploads/1a6090eb-7ccc-4326-984f-fb5ae45d7f24/avatar-01.png',
        'https://react.semantic-ui.com/images/avatar/large/matthew.png',
        'https://semantic-ui.com/images/avatar2/large/kristy.png',
        'https://semantic-ui.com/images/avatar2/large/molly.png',
        'https://semantic-ui.com/images/avatar2/large/elyse.png',
        'https://semantic-ui.com/images/avatar2/large/matthew.png'
    ];
    shuffle($avatars);
    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'avatar_url' => $avatars[0],
        'role' => random_int(2, 3),
        'email_verified_at' => now(),
        'password' => \Illuminate\Support\Facades\Hash::make('Aa@123123'), // password
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'student', function (Faker $faker) {
    $avatars = [
        'https://www.uidownload.com/files/790/68/996/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/990/169/304/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/893/712/8/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/762/371/766/free-set-of-material-design-avatars.png',
        'https://assets.materialup.com/uploads/1a6090eb-7ccc-4326-984f-fb5ae45d7f24/avatar-01.png',
        'https://react.semantic-ui.com/images/avatar/large/matthew.png',
        'https://semantic-ui.com/images/avatar2/large/kristy.png',
        'https://semantic-ui.com/images/avatar2/large/molly.png',
        'https://semantic-ui.com/images/avatar2/large/elyse.png',
        'https://semantic-ui.com/images/avatar2/large/matthew.png'
    ];
    shuffle($avatars);
    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'role' => \App\Enums\UserRole::Student,
        'avatar_url' => $avatars[0],
        'email_verified_at' => now(),
        'password' => \Illuminate\Support\Facades\Hash::make('Aa@123123'), // password
        'remember_token' => Str::random(10),
    ];
});
$factory->state(User::class, 'instructor', function (Faker $faker) {
    $avatars = [
        'https://www.uidownload.com/files/790/68/996/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/990/169/304/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/893/712/8/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/762/371/766/free-set-of-material-design-avatars.png',
        'https://assets.materialup.com/uploads/1a6090eb-7ccc-4326-984f-fb5ae45d7f24/avatar-01.png',
        'https://react.semantic-ui.com/images/avatar/large/matthew.png',
        'https://semantic-ui.com/images/avatar2/large/kristy.png',
        'https://semantic-ui.com/images/avatar2/large/molly.png',
        'https://semantic-ui.com/images/avatar2/large/elyse.png',
        'https://semantic-ui.com/images/avatar2/large/matthew.png'
    ];
    shuffle($avatars);
    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'role' => \App\Enums\UserRole::Instructor,
        'avatar_url' => $avatars[0],
        'email_verified_at' => now(),
        'password' => \Illuminate\Support\Facades\Hash::make('Aa@123123'), // password
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'admin', function (Faker $faker) {
    $avatars = [
        'https://www.uidownload.com/files/790/68/996/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/990/169/304/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/893/712/8/free-set-of-material-design-avatars.png',
        'https://www.uidownload.com/files/762/371/766/free-set-of-material-design-avatars.png',
        'https://assets.materialup.com/uploads/1a6090eb-7ccc-4326-984f-fb5ae45d7f24/avatar-01.png',
        'https://react.semantic-ui.com/images/avatar/large/matthew.png',
        'https://semantic-ui.com/images/avatar2/large/kristy.png',
        'https://semantic-ui.com/images/avatar2/large/molly.png',
        'https://semantic-ui.com/images/avatar2/large/elyse.png',
        'https://semantic-ui.com/images/avatar2/large/matthew.png'
    ];
    shuffle($avatars);
    return [
        'name' => 'Super Admin',
        'username' => 'superAdmin',
        'email' => $faker->unique()->safeEmail,
        'role' => \App\Enums\UserRole::Admin,
        'avatar_url' => $avatars[0],
        'email_verified_at' => now(),
        'password' => \Illuminate\Support\Facades\Hash::make('Aa@123123'), // password
        'remember_token' => Str::random(10),
    ];
});
