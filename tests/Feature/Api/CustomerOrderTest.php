<?php

namespace Tests\Feature\Api;

use App\Models\Order;
use App\Models\Product;
use App\User;
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
    public function test_customer_can_see_orders_list_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        factory(Product::class)->create();
        factory(Order::class, 10)->create(['customer_id' => $user->id]);
        $this->actingAs($user);
        $this->getJson(
            '/api/order',
            [],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_view_an_order_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        factory(Product::class)->create();
        $order = factory(Order::class)->create(['customer_id' => $user->id]);
        $this->actingAs($user);
        $this->getJson(
            '/api/order/' . $order->id,
            [],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_place_an_order_via_wallet_payment_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $user->depositFloat(10);
        $product = factory(Product::class)->create(['price' => 5]);
        $this->actingAs($user);
        $this->postJson(
            '/api/order',
            [
                'customer_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'type' => 'online',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_place_an_order_via_cod_payment_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $product = factory(Product::class)->create(['price' => 5]);
        $this->actingAs($user);
        $this->postJson(
            '/api/order',
            [
                'customer_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'type' => 'cod',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_cannot_place_an_order_via_wallet_payment_when_less_balance_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $user->depositFloat(10);
        $product = factory(Product::class)->create(['price' => 6]);
        $this->actingAs($user);
        $this->postJson(
            '/api/order',
            [
                'customer_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'type' => 'online',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(422);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_cancel_an_order_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        factory(Product::class)->create();
        $order = factory(Order::class)->create(['customer_id' => $user->id, 'status' => 'in-progress']);
        $this->actingAs($user);
        $this->postJson(
            '/api/order/' . $order->id,
            [],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_update_an_order_via_wallet_payment_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $product1 = factory(Product::class)->create(['price' => 5]);
        $product2 = factory(Product::class)->create(['price' => 4]);
        $order = factory(Order::class)->create(
            [
                'product_id' => $product1->id,
                'customer_id' => $user->id,
                'status' => 'in-progress',
                'quantity' => 1,
                'type' => 'online'
            ]
        );
        $this->actingAs($user)->withoutMiddleware(VerifyCsrfToken::class);
        $this->patchJson(
            '/api/order/' . $order->id,
            [
                'product_id'    => $product2->id,
                'quantity'      => 2,
                'type'          => 'online',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_update_an_order_via_cod_payment_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $product1 = factory(Product::class)->create(['price' => 5]);
        $product2 = factory(Product::class)->create(['price' => 4]);
        $order = factory(Order::class)->create(
            [
                'product_id' => $product1->id,
                'customer_id' => $user->id,
                'status' => 'in-progress',
                'quantity' => 2,
                'type' => 'cod'
            ]
        );
        $this->actingAs($user)->withoutMiddleware(VerifyCsrfToken::class);
        $this->patchJson(
            '/api/order/' . $order->id,
            [
                'product_id'    => $product2->id,
                'quantity'      => 2,
                'type'          => 'cod',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_cannot_update_an_order_via_wallet_payment_when_balance_after_returning_earlier_order_does_not_satisfy_new_amount_using_api()
    {
        $user = factory(User::class)->create()->assignRole('customer');
        $product1 = factory(Product::class)->create(['price' => 100]);
        $product2 = factory(Product::class)->create(['price' => 2000]);
        $order = factory(Order::class)->create(
            [
                'product_id' => $product1->id,
                'customer_id' => $user->id,
                'status' => 'in-progress',
                'quantity' => 2,
                'type' => 'online'
            ]
        );
        $this->actingAs($user)->withoutMiddleware(VerifyCsrfToken::class);
        $this->patchJson(
            '/api/order/' . $order->id,
            [
                'product_id'    => $product2->id,
                'quantity'      => 2,
                'type'          => 'online',
            ],
            ['Accept' => 'application/json']
        )->assertStatus(501);
    }
}
