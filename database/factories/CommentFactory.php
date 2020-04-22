<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'text' => $faker->sentence,
        'user_id' => 0,
        'post_id' => 0,
        'status' => 1,
        'parent_id' => null,
    ];
});
