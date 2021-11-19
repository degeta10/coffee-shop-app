<?php

namespace Tests\Feature\Api;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use RoleSeeder;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_login_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $this->postJson(
            '/api/login',
            [
                'email'     => $user->email,
                'password'  => 'password'
            ],
            ['Accept' => 'application/json']
        )->assertStatus(200)
            ->assertJsonStructure([
                "message",
                "user" => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'gender',
                    'dob',
                ],
                "token"
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_signup_using_api()
    {
        $this->postJson(
            '/api/signup',
            [
                'name'                  => 'Test customer',
                'email'                 => 'test@test.com',
                'password'              => 'password',
                'password_confirmation' => 'password',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_only_logged_in_customer_can_profile_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $this->actingAs($user);
        $this->getJson(
            '/api/auth/profile',
            [],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_only_logged_in_customer_can_update_profile_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $this->actingAs($user);
        $this->postJson(
            '/api/auth/profile',
            [
                'name'      => 'Test customer',
                'gender'    => 'female',
                'dob'       => '1970-03-04',
                'phone'     => '111111111',
                'address'   => 'test address',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }
}
