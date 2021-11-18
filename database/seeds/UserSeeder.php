<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* ADMIN ACCOUNT */
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@coffee.com',
            'email_verified_at' => now(),
            'password' => 'qwe@123',
            'remember_token' => Str::random(10),
        ]);
        $admin->assignRole('admin');

        /* CUSTOMER ACCOUNT */
        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@coffee.com',
            'email_verified_at' => now(),
            'password' => 'qwe@123',
            'gender' => 'male',
            'dob' => '1990-01-01',
            'phone' => '1111111111',
            'address' => 'Lorem ipsum addresss',
            'remember_token' => Str::random(10),
        ]);
        $customer->assignRole('customer');

        factory(User::class, 10)->create()->each(function ($user) {
            $user->assignRole('customer');
        });
    }
}
