<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_and_admin_via_their_respective_routes_can_retrieve_user_addresses()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/user-addresses');
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/user-addresses');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
        $response2->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
    }

    public function test_all_user_roles_via_their_respective_routes_can_create_user_addresses()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $response = $this->actingAs($superAdmin)->postJson('/api/v1/super-admin/user-addresses', [
            'user_id' => $superAdmin->id,
            'house_number' => fake()->buildingNumber(),
            'street' => fake()->streetName(),
            'city' => fake()->city(),
            'post_code' => fake()->postcode(),
            'state' => fake()->state(),
            'landmark' => fake()->text(100),
        ]);
        $response2 = $this->actingAs($admin)->postJson('/api/v1/admin/user-addresses', [
            'user_id' => $admin->id,
            'house_number' => fake()->buildingNumber(),
            'street' => fake()->streetName(),
            'city' => fake()->city(),
            'post_code' => fake()->postcode(),
            'state' => fake()->state(),
            'landmark' => fake()->text(100),
        ]);
        $response3 = $this->actingAs($user)->postJson('/api/v1/user/user-addresses', [
            'user_id' => $user->id,
            'house_number' => fake()->buildingNumber(),
            'street' => fake()->streetName(),
            'city' => fake()->city(),
            'post_code' => fake()->postcode(),
            'state' => fake()->state(),
            'landmark' => fake()->text(100),
        ]);
        $response4 = $this->actingAs($rider)->postJson('/api/v1/rider/user-addresses', [
            'user_id' => $rider->id,
            'house_number' => fake()->buildingNumber(),
            'street' => fake()->streetName(),
            'city' => fake()->city(),
            'post_code' => fake()->postcode(),
            'state' => fake()->state(),
            'landmark' => fake()->text(100),
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'house_number'],
            ])
            ->assertJsonPath('data.user_id', $superAdmin->id);

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $superAdmin->id,
        ]);

        $response2->assertStatus(201)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'house_number'],
            ])
            ->assertJsonPath('data.user_id', $admin->id);

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $admin->id,
        ]);

        $response3->assertStatus(201)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'house_number'],
            ])
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
        ]);

        $response4->assertStatus(201)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'house_number'],
            ])
            ->assertJsonPath('data.user_id', $rider->id);

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $rider->id,
        ]);
    }

    public function test_all_user_roles_via_their_respective_routes_can_view_user_address()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $superAdminAddress = UserAddress::factory()->create(['user_id' => $superAdmin->id]);
        $adminAddress = UserAddress::factory()->create(['user_id' => $admin->id]);
        $userAddress = UserAddress::factory()->create(['user_id' => $user->id]);
        $riderAddress = UserAddress::factory()->create(['user_id' => $rider->id]);

        $response1 = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/user-addresses/' . $superAdminAddress->id);
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/user-addresses/' . $adminAddress->id);
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/user-addresses/' . $userAddress->id);
        $response4 = $this->actingAs($rider)->getJson('/api/v1/rider/user-addresses/' . $riderAddress->id);

        $response1->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'city'],
            ]);

        $response2->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'city'],
            ]);

        $response3->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'city'],
            ]);

        $response4->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'city'],
            ]);
    }

    public function test_only_superadmin_and_admin_via_their_respective_routes_can_update_every_other_users_user_addresses()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $user_address = UserAddress::factory()->create();

        $city = fake()->city();

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/user-addresses/' . $user_address->id, [
            'city' => $city,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/admin/user-addresses/' . $user_address->id, [
            'city' => $city,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/admin/user-addresses/' . $user_address->id, [
            'city' => $city,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'city'],
            ])
            ->assertJsonPath('data.city', $city);

        $this->assertDatabaseHas('user_addresses', [
            'city' => $city,
        ]);

        $response2->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'city'],
            ])
            ->assertJsonPath('data.city', $city);

        $this->assertDatabaseHas('user_addresses', [
            'city' => $city,
        ]);

        $response3->assertStatus(403);
    }

    public function test_all_user_roles_can_update_user_address_belonging_to_them()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $superAdminAddress = UserAddress::factory()->create([
            'user_id' => $superAdmin->id,
        ]);
        $adminAddress = UserAddress::factory()->create([
            'user_id' => $admin->id,
        ]);
        $userAddress = UserAddress::factory()->create([
            'user_id' => $user->id,
        ]);
        $riderAddress = UserAddress::factory()->create([
            'user_id' => $rider->id,
        ]);

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/user-addresses/' . $superAdminAddress->id, [
            'city' => $superAdminAddress->city,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/admin/user-addresses/' . $adminAddress->id, [
            'city' => $adminAddress->city,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/user/user-addresses/' . $userAddress->id, [
            'city' => $userAddress->city,
        ]);

        $response4 = $this->actingAs($rider)->putJson('/api/v1/rider/user-addresses/' . $riderAddress->id, [
            'city' => $riderAddress->city,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'city'],
            ])
            ->assertJsonPath('data.user_id', $superAdmin->id);

        $this->assertDatabaseHas('user_addresses', [
            'city' => $superAdminAddress->city,
        ]);

        $response2->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'city'],
            ])
            ->assertJsonPath('data.user_id', $admin->id);

        $this->assertDatabaseHas('user_addresses', [
            'city' => $adminAddress->city,
        ]);

        $response3->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'city'],
            ])
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('user_addresses', [
            'city' => $userAddress->city,
        ]);

        $response4->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'city'],
            ])
            ->assertJsonPath('data.user_id', $rider->id);

        $this->assertDatabaseHas('user_addresses', [
            'city' => $riderAddress->city,
        ]);
    }

    public function test_only_superadmin_and_admin_via_their_respective_routes_can_delete_every_other_users_user_addresses()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $user_address = UserAddress::factory()->create();
        $user_address2 = UserAddress::factory()->create();
        $user_address3 = UserAddress::factory()->create();

        $response = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/user-addresses/' . $user_address->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/user-addresses/' . $user_address2->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/admin/user-addresses/' . $user_address3->id);

        $response->assertNoContent();
        $response2->assertNoContent();

        $this->assertDatabaseHas('user_addresses', [
            'id' => $user_address->id,
            'deleted_at' => $user_address->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('user_addresses', 13);

        $response3->assertStatus(403);
    }

    public function test_all_user_roles_can_delete_user_addresses_belonging_to_them()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $superAdminAddress = UserAddress::factory()->create([
            'user_id' => $superAdmin->id,
        ]);
        $adminAddress = UserAddress::factory()->create([
            'user_id' => $admin->id,
        ]);
        $userAddress = UserAddress::factory()->create([
            'user_id' => $user->id,
        ]);
        $riderAddress = UserAddress::factory()->create([
            'user_id' => $rider->id,
        ]);

        $response1 = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/user-addresses/' . $superAdminAddress->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/user-addresses/' . $adminAddress->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/user/user-addresses/' . $userAddress->id);
        $response4 = $this->actingAs($rider)->deleteJson('/api/v1/rider/user-addresses/' . $riderAddress->id);

        $response1->assertNoContent();
        $response2->assertNoContent();
        $response3->assertNoContent();
        $response4->assertNoContent();

        $this->assertDatabaseHas('user_addresses', [
            'id' => $superAdminAddress->id,
            'deleted_at' => $superAdminAddress->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('user_addresses', 14);

        $this->assertDatabaseHas('user_addresses', [
            'id' => $adminAddress->id,
            'deleted_at' => $adminAddress->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('user_addresses', 14);

        $this->assertDatabaseHas('user_addresses', [
            'id' => $userAddress->id,
            'deleted_at' => $userAddress->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('user_addresses', 14);

        $this->assertDatabaseHas('user_addresses', [
            'id' => $riderAddress->id,
            'deleted_at' => $riderAddress->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('user_addresses', 14);
    }
}
