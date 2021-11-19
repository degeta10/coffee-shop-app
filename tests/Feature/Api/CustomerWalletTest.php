<?php

namespace Tests\Feature\Api;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use RoleSeeder;
use Tests\TestCase;

class CustomerWalletTest extends TestCase
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
    public function test_customer_can_see_wallet_balance_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $this->actingAs($user);
        $this->getJson(
            '/api/wallet',
            [],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_non_logged_in_user_cannot_see_wallet_balance_using_api()
    {
        $this->getJson(
            '/api/wallet',
            [],
            ['Accept' => 'application/json']
        )->assertStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_deposit_to_wallet_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $this->actingAs($user);
        $this->postJson(
            '/api/wallet/update',
            [
                'transaction_type' => 'deposit',
                'amount' => '500',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_withdraw_from_wallet_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $user->depositFloat(100);
        $this->actingAs($user);
        $this->postJson(
            '/api/wallet/update',
            [
                'transaction_type' => 'withdraw',
                'amount' => '100',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_cannot_withdraw_from_wallet_when_less_balance_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $user->depositFloat(100);
        $this->actingAs($user);
        $this->postJson(
            '/api/wallet/update',
            [
                'transaction_type' => 'withdraw',
                'amount' => '200',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(422)
            ->assertJsonValidationErrors([
                'amount' => 'Insufficient funds.'
            ]);
    }
}
