<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use App\Models\Product;
use App\User;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'order_no'              => $faker->unique()->randomNumber(5, true),
        'customer_id'           => User::all()->random(),
        'product_id'            => Product::all()->random(),
        'quantity'              => $faker->randomNumber(1, true),
        'amount'                => $faker->randomFloat(2, 100, 1000),
        'type'                  => $faker->randomElement(['cod', 'online']),
        'status'                => $faker->randomElement(['in-progress', 'delivered', 'cancelled']),
        'date_of_order'         => $faker->date(),
        'date_of_cancellation'  => $faker->date(),
        'date_of_delivery'      => $faker->date(),
    ];
});
