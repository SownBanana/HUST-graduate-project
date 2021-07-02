<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Course;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Course::class, function (Faker $faker) {
    $startTime = Carbon::now()->subWeeks(rand(0, 10));
    $endTime = Carbon::createFromTimestamp($startTime->timestamp)->addDays(rand(15, 90));
    $thumbnails = [
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/c8dae0da-e545-4a40-a6a8-9188027aad75.jpg',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/33178278-704b-4ef1-99d4-ac0fb1e4f931.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/ce0b3e93-ed4f-4971-8f4c-e05e9248ff57.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/a698198a-8092-45e2-aeff-6df7ba831f63.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/9a390b33-8621-47e8-9657-bb9fb933fbe8.png',
        'https://images.viblo.asia/11eaf36a-ca19-4346-9f5a-1a42d3f98b27.jpg',
        'https://cdn6.f-cdn.com/contestentries/1162950/21695385/59ea4d04c6d75_thumb900.jpg',
        'https://www.cm-alliance.com/hubfs/CIPR-Course-Thumbnail.jpg',
        'https://i0.wp.com/www.ltnow.com/wp-content/uploads/2020/03/effective-cyber-security-training.jpg?resize=700%2C368&ssl=1',
        'https://i.ytimg.com/vi/OoO5d5P0Jn4/maxresdefault.jpg',
        'https://m.media-amazon.com/images/M/MV5BMjZmMzYzNjgtMTZjMC00ZGIxLWIyMGEtODIyYzc1YzBiOGM2L2ltYWdlXkEyXkFqcGdeQXVyNzE0MjY2MjY@._V1_.jpg',
        'https://image.pbs.org/video-assets/NlV1Cte-asset-mezzanine-16x9-PxvECNQ.jpg',
        'https://image.pbs.org/video-assets/NlV1Cte-asset-mezzanine-16x9-PxvECNQ.jpg'
    ];
    shuffle($thumbnails);
    return [
        'title' => $faker->name,
        'instructor_id' => \App\Models\User::whereRole(\App\Enums\UserRole::Instructor)->inRandomOrder()->first()->id,
        'status' => random_int(0, 3),
        'type' => random_int(2, 3),
        'thumbnail_url' => $thumbnails[0],
        'introduce' => $faker->text(200),
        'price' => random_int(0, 1500) * 1000,
        'start_time' => $startTime,
        'end_time' => $endTime,
    ];
});
$factory->state(Course::class, 'publish', function (Faker $faker) {
    $startTime = Carbon::now()->subWeeks(rand(0, 10));
    $endTime = Carbon::createFromTimestamp($startTime->timestamp)->addDays(rand(15, 90));
    $thumbnails = [
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/c8dae0da-e545-4a40-a6a8-9188027aad75.jpg',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/33178278-704b-4ef1-99d4-ac0fb1e4f931.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/ce0b3e93-ed4f-4971-8f4c-e05e9248ff57.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/a698198a-8092-45e2-aeff-6df7ba831f63.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/9a390b33-8621-47e8-9657-bb9fb933fbe8.png',
        'https://images.viblo.asia/11eaf36a-ca19-4346-9f5a-1a42d3f98b27.jpg',
        'https://cdn6.f-cdn.com/contestentries/1162950/21695385/59ea4d04c6d75_thumb900.jpg',
        'https://www.cm-alliance.com/hubfs/CIPR-Course-Thumbnail.jpg',
        'https://i0.wp.com/www.ltnow.com/wp-content/uploads/2020/03/effective-cyber-security-training.jpg?resize=700%2C368&ssl=1',
        'https://i.ytimg.com/vi/OoO5d5P0Jn4/maxresdefault.jpg',
        'https://m.media-amazon.com/images/M/MV5BMjZmMzYzNjgtMTZjMC00ZGIxLWIyMGEtODIyYzc1YzBiOGM2L2ltYWdlXkEyXkFqcGdeQXVyNzE0MjY2MjY@._V1_.jpg',
        'https://image.pbs.org/video-assets/NlV1Cte-asset-mezzanine-16x9-PxvECNQ.jpg',
        'https://image.pbs.org/video-assets/NlV1Cte-asset-mezzanine-16x9-PxvECNQ.jpg'
    ];
    shuffle($thumbnails);
    return [
        'title' => $faker->name,
        'instructor_id' => \App\Models\User::whereRole(\App\Enums\UserRole::Instructor)->inRandomOrder()->first()->id,
        'status' => \App\Enums\CourseType::Publish,
        'type' => random_int(2, 3),
        'thumbnail_url' => $thumbnails[0],
        'introduce' => $faker->text(200),
        'price' => random_int(0, 1500) * 1000,
        'start_time' => $startTime,
        'end_time' => $endTime,
    ];
});
$factory->state(Course::class, 'live', function (Faker $faker) {
    $startTime = Carbon::now()->subWeeks(rand(0, 10));
    $endTime = Carbon::createFromTimestamp($startTime->timestamp)->addDays(rand(15, 90));
    $thumbnails = [
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/c8dae0da-e545-4a40-a6a8-9188027aad75.jpg',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/33178278-704b-4ef1-99d4-ac0fb1e4f931.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/ce0b3e93-ed4f-4971-8f4c-e05e9248ff57.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/a698198a-8092-45e2-aeff-6df7ba831f63.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/9a390b33-8621-47e8-9657-bb9fb933fbe8.png',
        'https://images.viblo.asia/11eaf36a-ca19-4346-9f5a-1a42d3f98b27.jpg',
        'https://cdn6.f-cdn.com/contestentries/1162950/21695385/59ea4d04c6d75_thumb900.jpg',
        'https://www.cm-alliance.com/hubfs/CIPR-Course-Thumbnail.jpg',
        'https://i0.wp.com/www.ltnow.com/wp-content/uploads/2020/03/effective-cyber-security-training.jpg?resize=700%2C368&ssl=1',
        'https://i.ytimg.com/vi/OoO5d5P0Jn4/maxresdefault.jpg',
        'https://m.media-amazon.com/images/M/MV5BMjZmMzYzNjgtMTZjMC00ZGIxLWIyMGEtODIyYzc1YzBiOGM2L2ltYWdlXkEyXkFqcGdeQXVyNzE0MjY2MjY@._V1_.jpg',
        'https://image.pbs.org/video-assets/NlV1Cte-asset-mezzanine-16x9-PxvECNQ.jpg',
        'https://image.pbs.org/video-assets/NlV1Cte-asset-mezzanine-16x9-PxvECNQ.jpg'
    ];
    shuffle($thumbnails);
    return [
        'title' => $faker->name,
        'instructor_id' => \App\Models\User::whereRole(\App\Enums\UserRole::Instructor)->inRandomOrder()->first()->id,
        'status' => \App\Enums\CourseType::Publish,
        'type' => \App\Enums\CourseType::LIVE,
        'thumbnail_url' => $thumbnails[0],
        'introduce' => $faker->text(200),
        'price' => random_int(0, 1500) * 1000,
        'start_time' => $startTime,
        'end_time' => $endTime,
    ];
});
$factory->state(Course::class, 'normal', function (Faker $faker) {
    $startTime = Carbon::now()->subWeeks(rand(0, 10));
    $endTime = Carbon::createFromTimestamp($startTime->timestamp)->addDays(rand(15, 90));
    $thumbnails = [
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/c8dae0da-e545-4a40-a6a8-9188027aad75.jpg',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/33178278-704b-4ef1-99d4-ac0fb1e4f931.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/ce0b3e93-ed4f-4971-8f4c-e05e9248ff57.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/a698198a-8092-45e2-aeff-6df7ba831f63.png',
        'https://vlearn-bucket.s3.ap-southeast-1.amazonaws.com/uploads/9a390b33-8621-47e8-9657-bb9fb933fbe8.png',
        'https://images.viblo.asia/11eaf36a-ca19-4346-9f5a-1a42d3f98b27.jpg',
        'https://cdn6.f-cdn.com/contestentries/1162950/21695385/59ea4d04c6d75_thumb900.jpg',
        'https://www.cm-alliance.com/hubfs/CIPR-Course-Thumbnail.jpg',
        'https://i0.wp.com/www.ltnow.com/wp-content/uploads/2020/03/effective-cyber-security-training.jpg?resize=700%2C368&ssl=1',
        'https://i.ytimg.com/vi/OoO5d5P0Jn4/maxresdefault.jpg',
        'https://m.media-amazon.com/images/M/MV5BMjZmMzYzNjgtMTZjMC00ZGIxLWIyMGEtODIyYzc1YzBiOGM2L2ltYWdlXkEyXkFqcGdeQXVyNzE0MjY2MjY@._V1_.jpg',
        'https://image.pbs.org/video-assets/NlV1Cte-asset-mezzanine-16x9-PxvECNQ.jpg',
        'https://image.pbs.org/video-assets/NlV1Cte-asset-mezzanine-16x9-PxvECNQ.jpg'
    ];
    shuffle($thumbnails);
    return [
        'title' => $faker->name,
        'instructor_id' => \App\Models\User::whereRole(\App\Enums\UserRole::Instructor)->inRandomOrder()->first()->id,
        'status' => \App\Enums\CourseType::Publish,
        'type' => \App\Enums\CourseType::NORMAL,
        'thumbnail_url' => $thumbnails[0],
        'introduce' => $faker->text(200),
        'price' => random_int(0, 1500) * 1000,
        'start_time' => $startTime,
        'end_time' => $endTime,
    ];
});
