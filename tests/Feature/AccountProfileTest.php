<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use RoleSeeder;
use Tests\TestCase;

class AccountProfileTest extends TestCase
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
    public function test_only_logged_in_users_can_see_profile()
    {
        $this->get('/account/profile')->assertRedirect('/login');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_only_logged_in_customer_users_can_see_profile()
    {
        $this->actingAs(factory(User::class)->create()->assignRole('customer'));
        $this->get('/account/profile')->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_cannot_see_profile()
    {
        $this->actingAs(factory(User::class)->create()->assignRole('admin'));
        $this->get('/account/profile')->assertForbidden();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_can_update_profile()
    {
        $this->actingAs(factory(User::class)->create()->assignRole('customer'))->withoutMiddleware(VerifyCsrfToken::class);
        $this->patch('/account/profile/update', [
            'name' => 'New Name',
            'gender' => 'male',
            'dob' =>  Carbon::now()->subYears(20),
            'phone' => '+91 1111111111',
            'address' => "Test address",
        ])->assertStatus(302);
    }
}
