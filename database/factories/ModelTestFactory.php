<?php

use Faker\Generator as Faker;

$factory->define(App\ModelTest::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
