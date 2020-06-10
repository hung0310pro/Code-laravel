<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Posts;

$factory->define(Posts::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text(),
        'published_at' => 1,
    ];
});
