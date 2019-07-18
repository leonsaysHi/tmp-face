<?php

use Faker\Generator as Faker;

$factory->define(\App\Search::class, function (Faker $faker) {
    return [
        'basic_search_text' => $faker->name,
    ];
});
