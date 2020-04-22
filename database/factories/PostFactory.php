<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->sentence,
        'image' => 'photo2.png',
        'date' => '10/12/19',
        'views' => $faker->numberBetween(0, 5000),
        'category_id' => 2,
        'user_id' => 3,
        'status' => 1,
        'is_featured' => 0
    ];
});
