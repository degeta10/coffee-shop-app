<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use RoleSeeder;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
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
    public function test_only_logged_in_users_can_see_orders()
    {
        $this->get('/orders')->assertRedirect('/login');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_only_logged_in_customer_can_see_orders()
    {
        $this->actingAs(factory(User::class)->create()->assignRole('customer'));
        $this->get('/orders')->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_only_logged_in_customer_can_search_orders()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $this->actingAs($user)->withoutMiddleware(VerifyCsrfToken::class);
        $product = factory(Product::class)->create(['title' => 'test product']);
        factory(Order::class, 1)->create(['customer_id' => $user->id, 'product_id' => $product->id]);
        $this->post('/orders/search', ['search_key' => 'test product'])->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_only_logged_in_customer_can_see_place_order_page()
    {
        $this->actingAs(factory(User::class)->create()->assignRole('customer'));
        $this->get('/orders/create')->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_place_order_with_cod()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $this->actingAs($user)->withoutMiddleware(VerifyCsrfToken::class);
        $product = factory(Product::class)->create(['title' => 'test product']);
        $this->post('/orders/create', [
            'customer_id'   => $user->id,
            'product_id'    => $product->id,
            'quantity'      => 2,
            'type'          => 'cod',
        ])->assertStatus(302);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_place_order_with_enough_wallet_balance()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $this->actingAs($user)->withoutMiddleware(VerifyCsrfToken::class);
        $user->depositFloat(10);
        $product = factory(Product::class)->create(['title' => 'test product', 'price' => 5]);
        $this->post('/orders/create', [
            'customer_id'   => $user->id,
            'product_id'    => $product->id,
            'quantity'      => 2,
            'type'          => 'online',
        ])->assertStatus(302);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_cannot_place_order_with_less_wallet_balance()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $this->actingAs($user)->withoutMiddleware(VerifyCsrfToken::class);
        $user->depositFloat(5);
        $product = factory(Product::class)->create(['title' => 'test product', 'price' => 10]);
        $this->post('/orders/create', [
            'customer_id'   => $user->id,
            'product_id'    => $product->id,
            'quantity'      => 2,
            'type'          => 'online',
        ])->assertSessionHasErrors([
            'quantity' => 'Insufficient wallet balance. Please select less quantity.'
        ]);
    }
}
