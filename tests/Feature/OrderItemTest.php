<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_and_admin_via_their_respective_routes_can_retrieve_all_orders()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $orderItemByUser = OrderItem::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/order-items');
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/order-items');
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/order-items');

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

    public function test_all_user_roles_except_rider_role_via_their_respective_routes_can_create_order_items()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $meal = Meal::all()->random()->first();
        $order = Order::all()->random()->first();

        $response = $this->actingAs($superAdmin)->postJson('/api/v1/super-admin/order-items', [
            'meal_id' => $meal->id,
            'order_id' => $order->id,
            'user_id' => $superAdmin->id,
            'amount_due' => $meal->price,
            'quantity_ordered' => rand(1, 10),
        ]);
        $response2 = $this->actingAs($admin)->postJson('/api/v1/admin/order-items', [
            'meal_id' => $meal->id,
            'order_id' => $order->id,
            'user_id' => $admin->id,
            'amount_due' => $meal->price,
            'quantity_ordered' => rand(1, 10),
        ]);
        $response3 = $this->actingAs($user)->postJson('/api/v1/user/order-items', [
            'meal_id' => $meal->id,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'amount_due' => $meal->price,
            'quantity_ordered' => rand(1, 10),
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ])
            ->assertJsonPath('data.user_id', $superAdmin->id);

        $this->assertDatabaseHas('order_items', [
            'user_id' => $superAdmin->id,
        ]);

        $response2->assertStatus(201)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ])
            ->assertJsonPath('data.user_id', $admin->id);

        $this->assertDatabaseHas('order_items', [
            'user_id' => $admin->id,
        ]);

        $response3->assertStatus(201)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ])
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('order_items', [
            'user_id' => $user->id,
        ]);
    }

    public function test_all_user_roles_via_their_respective_routes_can_view_order()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $superAdminOrderItem = OrderItem::factory()->create(['user_id' => $superAdmin->id]);
        $adminOrderItem = OrderItem::factory()->create(['user_id' => $admin->id]);
        $userOrderItem = OrderItem::factory()->create(['user_id' => $user->id]);

        $response1 = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/order-items/' . $superAdminOrderItem->id);
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/order-items/' . $adminOrderItem->id);
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/order-items/' . $userOrderItem->id);

        $response1->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ]);

        $response2->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ]);

        $response3->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'user_id'],
            ]);
    }

    public function test_only_superadmin_can_update_every_other_users_order_items()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $orderItem = OrderItem::factory()->create();

        $amount_due = fake()->randomFloat(2, 0.10, 500);

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/order-items/' . $orderItem->id, [
            'amount_due' => $amount_due,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/super-admin/order-items/' . $orderItem->id, [
            'amount_due' => $amount_due,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/super-admin/order-items/' . $orderItem->id, [
            'amount_due' => $amount_due,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'amount_due'],
            ])
            ->assertJsonPath('data.amount_due', $amount_due);

        $this->assertDatabaseHas('order_items', [
            'amount_due' => $amount_due,
        ]);

        $response2->assertStatus(403);

        $response3->assertStatus(403);
    }

    public function test_all_user_roles_except_rider_can_update_order_items_belonging_to_them()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $superAdminOrderItem = OrderItem::factory()->create([
            'user_id' => $superAdmin->id,
        ]);
        $adminOrderItem = OrderItem::factory()->create([
            'user_id' => $admin->id,
        ]);
        $userOrderItem = OrderItem::factory()->create([
            'user_id' => $user->id,
        ]);
        $riderOrderItem = OrderItem::factory()->create([
            'user_id' => $rider->id,
        ]);

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/order-items/' . $superAdminOrderItem->id, [
            'quantity_ordered' => $superAdminOrderItem->quantity_ordered,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/admin/order-items/' . $adminOrderItem->id, [
            'quantity_ordered' => $adminOrderItem->quantity_ordered,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/user/order-items/' . $userOrderItem->id, [
            'quantity_ordered' => $userOrderItem->quantity_ordered,
        ]);

        $response4 = $this->actingAs($rider)->putJson('/api/v1/user/order-items/' . $riderOrderItem->id, [
            'quantity_ordered' => $riderOrderItem->quantity_ordered,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'quantity_ordered'],
            ])
            ->assertJsonPath('data.user_id', $superAdmin->id);

        $this->assertDatabaseHas('order_items', [
            'quantity_ordered' => $superAdminOrderItem->quantity_ordered,
        ]);

        $response2->assertStatus(405);

        $response3->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'quantity_ordered'],
            ])
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('order_items', [
            'quantity_ordered' => $userOrderItem->quantity_ordered,
        ]);

        $response4->assertStatus(403);
    }

    public function test_only_superadmin_can_delete_every_other_users_orders()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $orderItem = OrderItem::factory()->create();
        $orderItem2 = OrderItem::factory()->create();
        $orderItem3 = OrderItem::factory()->create();

        $response = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/order-items/' . $orderItem->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/order-items/' . $orderItem2->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/user/order-items/' . $orderItem3->id);

        $response->assertNoContent();
        $response2->assertStatus(405);

        $this->assertDatabaseHas('order_items', [
            'id' => $orderItem->id,
            'deleted_at' => $orderItem->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('order_items', 13);

        $response3->assertStatus(403);
    }

    public function test_all_user_roles_can_delete_orders_belonging_to_them()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $superAdminOrderItem = OrderItem::factory()->create([
            'user_id' => $superAdmin->id,
        ]);
        $adminOrderItem = OrderItem::factory()->create([
            'user_id' => $admin->id,
        ]);
        $userOrderItem = OrderItem::factory()->create([
            'user_id' => $user->id,
        ]);

        $response1 = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/order-items/' . $superAdminOrderItem->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/order-items/' . $adminOrderItem->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/user/order-items/' . $userOrderItem->id);

        $response1->assertNoContent();
        $response2->assertStatus(405);
        $response3->assertNoContent();

        $this->assertDatabaseHas('order_items', [
            'id' => $superAdminOrderItem->id,
            'deleted_at' => $superAdminOrderItem->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ]);

        $this->assertDatabaseHas('order_items', [
            'id' => $adminOrderItem->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('order_items', [
            'id' => $userOrderItem->id,
            'deleted_at' => $userOrderItem->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('order_items', 13);
    }
}
