<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_and_admin_via_their_respective_routes_can_retrieve_all_orders()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $orderByUser = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/orders');
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/orders');
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/orders');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(11, 'data');
        $response2->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(11, 'data');
        $response3->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1, 'data');
    }

    public function test_all_user_roles_except_rider_role_via_their_respective_routes_can_create_orders()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->postJson('/api/v1/super-admin/orders', [
            'user_id' => $superAdmin->id,
            'order_annuled' => fake()->boolean(),
            'delivered' => fake()->boolean(),
            'paid' => fake()->boolean(),
        ]);
        $response2 = $this->actingAs($admin)->postJson('/api/v1/admin/orders', [
            'user_id' => $admin->id,
            'order_annuled' => fake()->boolean(),
            'delivered' => fake()->boolean(),
            'total_amount' => fake()->randomFloat(2, 0.10, 500),
            'paid' => fake()->boolean(),
        ]);
        $response3 = $this->actingAs($user)->postJson('/api/v1/user/orders', [
            'user_id' => $user->id,
            'order_annuled' => fake()->boolean(),
            'delivered' => fake()->boolean(),
            'paid' => fake()->boolean(),
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ])
            ->assertJsonPath('data.user_id', $superAdmin->id);

        $this->assertDatabaseHas('orders', [
            'user_id' => $superAdmin->id,
        ]);

        $response2->assertStatus(201)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ])
            ->assertJsonPath('data.user_id', $admin->id);

        $this->assertDatabaseHas('orders', [
            'user_id' => $admin->id,
        ]);

        $response3->assertStatus(201)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ])
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);
    }

    public function test_all_user_roles_via_their_respective_routes_can_view_order()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $superAdminOrder = Order::factory()->create(['user_id' => $superAdmin->id]);
        $adminOrder = Order::factory()->create(['user_id' => $admin->id]);
        $userOrder = Order::factory()->create(['user_id' => $user->id]);

        $response1 = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/orders/' . $superAdminOrder->id);
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/orders/' . $adminOrder->id);
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/orders/' . $userOrder->id);

        $response1->assertStatus(200)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ]);

        $response2->assertStatus(200)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ]);

        $response3->assertStatus(200)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ]);
    }

    public function test_only_superadmin_can_update_every_other_users_orders()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $order = Order::factory()->create();

        $total_amount = fake()->randomFloat(2, 0.10, 500);

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/orders/' . $order->id, [
            'total_amount' => $total_amount,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/super-admin/orders/' . $order->id, [
            'total_amount' => $total_amount,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/super-admin/orders/' . $order->id, [
            'total_amount' => $total_amount,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'total_amount'],
            ])
            ->assertJsonPath('data.total_amount', $total_amount);

        $this->assertDatabaseHas('orders', [
            'total_amount' => $total_amount,
        ]);

        $response2->assertStatus(403);

        $response3->assertStatus(403);
    }

    public function test_all_user_roles_except_rider_can_update_order_belonging_to_them()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $superAdminOrder = Order::factory()->create([
            'user_id' => $superAdmin->id,
        ]);
        $adminOrder = Order::factory()->create([
            'user_id' => $admin->id,
        ]);
        $userOrder = Order::factory()->create([
            'user_id' => $user->id,
        ]);
        $riderOrder = Order::factory()->create([
            'user_id' => $rider->id,
        ]);

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/orders/' . $superAdminOrder->id, [
            'order_annuled' => $superAdminOrder->order_annuled,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/admin/orders/' . $adminOrder->id, [
            'order_annuled' => $adminOrder->order_annuled,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/user/orders/' . $userOrder->id, [
            'order_annuled' => $userOrder->order_annuled,
        ]);

        $response4 = $this->actingAs($rider)->putJson('/api/v1/user/orders/' . $riderOrder->id, [
            'order_annuled' => $riderOrder->order_annuled,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'order_annuled'],
            ])
            ->assertJsonPath('data.user_id', $superAdmin->id);

        $this->assertDatabaseHas('orders', [
            'order_annuled' => $superAdminOrder->order_annuled,
        ]);

        $response2->assertStatus(200)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'order_annuled'],
            ])
            ->assertJsonPath('data.user_id', $admin->id);

        $this->assertDatabaseHas('orders', [
            'order_annuled' => $adminOrder->order_annuled,
        ]);

        $response3->assertStatus(200)
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'order_annuled'],
            ])
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('orders', [
            'order_annuled' => $userOrder->order_annuled,
        ]);

        $response4->assertStatus(403);
    }

    public function test_only_superadmin_can_delete_every_other_users_orders()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $order = Order::factory()->create();
        $order2 = Order::factory()->create();
        $order3 = Order::factory()->create();

        $response = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/orders/' . $order->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/orders/' . $order2->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/admin/orders/' . $order3->id);

        $response->assertNoContent();
        $response2->assertStatus(405);
        $response3->assertStatus(405);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'deleted_at' => $order->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('orders', 13);
    }

    public function test_all_user_roles_can_delete_orders_belonging_to_them()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $superAdminOrder = Order::factory()->create([
            'user_id' => $superAdmin->id,
        ]);
        $adminOrder = Order::factory()->create([
            'user_id' => $admin->id,
        ]);
        $userOrder = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $response1 = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/orders/' . $superAdminOrder->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/orders/' . $adminOrder->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/user/orders/' . $userOrder->id);

        $response1->assertNoContent();
        $response2->assertStatus(405);
        $response3->assertNoContent();

        $this->assertDatabaseHas('orders', [
            'id' => $superAdminOrder->id,
            'deleted_at' => $superAdminOrder->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $adminOrder->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $userOrder->id,
            'deleted_at' => $userOrder->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('orders', 13);
    }
}
