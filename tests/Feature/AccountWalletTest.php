<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use RoleSeeder;
use Tests\TestCase;

class AccountWalletTest extends TestCase
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
    public function test_only_logged_in_users_can_see_wallet()
    {
        $this->get('/account/wallet')->assertRedirect('/login');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_only_logged_in_customer_users_can_see_wallet()
    {
        $this->actingAs(factory(User::class)->create()->assignRole('customer'));
        $this->get('/account/wallet')->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_cannot_see_wallet()
    {
        $this->actingAs(factory(User::class)->create()->assignRole('admin'));
        $this->get('/account/wallet')->assertForbidden();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_deposit_to_wallet()
    {
        $this->actingAs(factory(User::class)->create()->assignRole('customer'))->withoutMiddleware(VerifyCsrfToken::class);
        $this->patch('/account/wallet/update', [
            'transaction_type' => 'deposit',
            'amount' => 10,
        ])->assertStatus(302);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_withdraw_from_wallet()
    {
        $user = factory(User::class)->create();
        $user->assignRole('customer');
        $this->actingAs($user)->withoutMiddleware(VerifyCsrfToken::class);
        $user->depositFloat(10);
        $this->patch('/account/wallet/update', [
            'transaction_type' => 'withdraw',
            'amount' => 10,
        ])->assertStatus(302);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_cannot_withdraw_more_than_existing_balance_from_wallet()
    {
        $user = factory(User::class)->create();
        $user->assignRole('customer');
        $this->actingAs($user)->withoutMiddleware(VerifyCsrfToken::class);
        $user->depositFloat(10);
        $this->patch('/account/wallet/update', [
            'transaction_type' => 'withdraw',
            'amount' => 100,
        ])->assertSessionHasErrors([
            'amount' => 'Insufficient funds.'
        ]);
    }
}
