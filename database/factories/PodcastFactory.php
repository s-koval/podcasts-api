<?php

use Faker\Generator as Faker;

$factory->define(\App\Podcast::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'description' => $faker->paragraph,
        'marketing_url' => $faker->url,
        'feed_url' => $faker->url,
        'image' => $faker->image(storage_path() . '/app/public/images')
    ];
});
