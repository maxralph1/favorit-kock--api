<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_super_admin_and_admin_and_rider_via_their_respective_routes_can_retrieve_deliveries()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/deliveries');
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/deliveries');
        $response3 = $this->actingAs($user)->getJson('/api/v1/admin/deliveries');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
        $response2->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
        $response3->assertStatus(403);
    }

    public function test_rider_can_retrieve_only_deliveries_belonging_to_them()
    {
        $rider1 = User::factory()->create(['role_id' => Role::RIDER_ROLE]);
        $rider2 = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        Delivery::factory()->create(['user_id' => $rider1->id]);
        Delivery::factory()->create(['user_id' => $rider2->id]);
        Delivery::factory()->create(['user_id' => $rider1->id]);
        Delivery::factory()->create(['user_id' => $rider2->id]);
        Delivery::factory()->create(['user_id' => $rider2->id]);
        Delivery::factory()->create(['user_id' => $rider2->id]);

        $response = $this->actingAs($rider1)->getJson('/api/v1/rider/deliveries');
        $response2 = $this->actingAs($rider2)->getJson('/api/v1/rider/deliveries');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2, 'data');

        $response2->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(4, 'data');
    }

    public function test_only_super_admin_and_admin_via_their_respective_routes_can_create_deliveries()
    {
        $order_id = Order::value('id');

        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->postJson('/api/v1/super-admin/deliveries', [
            'order_id' => $order_id,
            'user_id' => $superAdmin->id,
            'delivered' => false,
            'time_delivered' => now(),
        ]);
        $response2 = $this->actingAs($admin)->postJson('/api/v1/admin/deliveries', [
            'order_id' => $order_id,
            'user_id' => $admin->id,
            'delivered' => false,
            'time_delivered' => now(),
        ]);
        $response3 = $this->actingAs($user)->postJson('/api/v1/admin/deliveries', [
            'order_id' => $order_id,
            'user_id' => $user->id,
            'delivered' => false,
            'time_delivered' => now(),
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'delivered'],
            ])
            ->assertJsonPath('data.delivered', false);

        $this->assertDatabaseHas('deliveries', [
            'delivered' => false,
        ]);

        $response2->assertStatus(201)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'delivered'],
            ])
            ->assertJsonPath('data.delivered', false);

        $this->assertDatabaseHas('deliveries', [
            'delivered' => false,
        ]);

        $response3->assertStatus(403);
    }

    public function test_only_superadmin_and_admin_and_rider_via_their_respective_routes_can_view_delivery()
    {
        $delivery_id = Delivery::value('id');

        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/deliveries/' . $delivery_id);
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/deliveries/' . $delivery_id);
        $response3 = $this->actingAs($user)->getJson('/api/v1/admin/deliveries/' . $delivery_id);

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'delivered'],
            ]);

        $response2->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'delivered'],
            ]);

        $response3->assertStatus(403);
    }

    public function test_rider_can_view_only_delivery_belonging_to_them()
    {
        $rider1 = User::factory()->create(['role_id' => Role::RIDER_ROLE]);
        $rider2 = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $delivery1 = Delivery::factory()->create(['user_id' => $rider1->id]);
        $delivery2 = Delivery::factory()->create(['user_id' => $rider2->id]);
        $delivery3 = Delivery::factory()->create(['user_id' => $rider1->id]);
        $delivery4 = Delivery::factory()->create(['user_id' => $rider2->id]);

        $response = $this->actingAs($rider1)->getJson('/api/v1/rider/deliveries/' . $delivery1->id);
        $response2 = $this->actingAs($rider1)->getJson('/api/v1/rider/deliveries/' . $delivery2->id);
        $response3 = $this->actingAs($rider2)->getJson('/api/v1/rider/deliveries/' . $delivery3->id);
        $response4 = $this->actingAs($rider2)->getJson('/api/v1/rider/deliveries/' . $delivery4->id);

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'delivered'],
            ]);

        $response2->assertStatus(403);

        $response3->assertStatus(403);

        $response4->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'delivered'],
            ]);
    }

    public function test_only_superadmin_and_admin_via_their_respective_routes_can_update_delivery()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $delivery = Delivery::factory()->create();

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/deliveries/' . $delivery->id, [
            'delivered' => true,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/admin/deliveries/' . $delivery->id, [
            'delivered' => true,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/admin/deliveries/' . $delivery->id, [
            'delivered' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'delivered'],
            ])
            ->assertJsonPath('data.delivered', true);

        $this->assertDatabaseHas('deliveries', [
            'delivered' => true,
        ]);

        $response2->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'delivered'],
            ])
            ->assertJsonPath('data.delivered', true);

        $this->assertDatabaseHas('deliveries', [
            'delivered' => true,
        ]);

        $response3->assertStatus(403);
    }

    public function test_only_superadmin_and_admin_and_rider_via_their_respective_routes_can_delete_delivery()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $delivery = Delivery::factory()->create();
        $delivery2 = Delivery::factory()->create();
        $delivery3 = Delivery::factory()->create();

        $response = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/deliveries/' . $delivery->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/deliveries/' . $delivery2->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/admin/deliveries/' . $delivery3->id);

        $response->assertNoContent();
        $response2->assertNoContent();

        $this->assertDatabaseHas('deliveries', [
            'id' => $delivery->id,
            'deleted_at' => $delivery->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('deliveries', 13);

        $response3->assertStatus(403);
    }

    public function test_rider_can_delete_only_delivery_belonging_to_them()
    {
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $delivery = Delivery::factory()->create(['user_id' => $rider->id]);

        $response3 = $this->actingAs($rider)->deleteJson('/api/v1/rider/deliveries/' . $delivery->id);

        $response3->assertNoContent();

        $this->assertDatabaseHas('deliveries', [
            'id' => $delivery->id,
            'deleted_at' => $delivery->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('deliveries', 11);
    }
}
