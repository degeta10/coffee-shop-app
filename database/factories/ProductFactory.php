<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'title'     => $faker->colorName,
        'price'     => $faker->randomElement([100.00, 150.50, 125.25, 110.00, 50.75])
    ];
});
