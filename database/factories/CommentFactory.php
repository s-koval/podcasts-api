<?php

use Faker\Generator as Faker;

$factory->define(\App\Comment::class, function (Faker $faker) {
    return [
        'author_name' => $faker->name,
        'author_email' => $faker->unique()->safeEmail,
        'comment' => $faker->paragraph
    ];
});
