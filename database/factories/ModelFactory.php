<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'role' => 'admin',
        'is_active' => true,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Gallery::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraphs(2, true),
        'is_active' => true,
        'user_id' => $faker->numberBetween(1,10),
    ];
});

$factory->define(App\Photo::class, function (Faker\Generator $faker) {
    $name = str_random(10) . '.jpg';
    return [
        // 'gallery_id' => factory(App\Gallery::class)->create()->id,
        'gallery_id' => $faker->numberBetween(1,10),
        'name' => $name,
        'path' => 'uploads/photos/' . $name,
        'thumbnail_path' => 'uploads/photos/tn-' . $name,
        'is_active' => true,
    ];
});

$factory->define(App\Review::class, function (Faker\Generator $faker) {
    return [
        'user_id' => null,
        'name' => $faker->name,
        'body' => $faker->paragraphs(1, true),
        'rating' => $faker->numberBetween(1,5),
        'response' => null,
        'is_active' => false,
    ];
});

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraphs(3, true),
        'is_active' => true,
        'user_id' => $faker->numberBetween(1,10),
    ];
});