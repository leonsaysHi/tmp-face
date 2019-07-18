<?php

use Faker\Generator as Faker;

$factory->define(\App\Preference::class, function (Faker $faker) {
    return [
        'value' => $faker->name,
    ];
});
