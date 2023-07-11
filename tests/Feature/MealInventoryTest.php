<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Meal;
use App\Models\MealInventory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_super_admin_and_admin_via_their_respective_routes_can_retrieve_meal_inventories()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/meal-inventories');
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/meal-inventories');
        $response3 = $this->actingAs($user)->getJson('/api/v1/admin/meal-inventories');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
        $response2->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
        $response3->assertStatus(403);
    }

    public function test_only_super_admin_and_admin_via_their_respective_routes_can_create_meal_inventories()
    {
        $meal_id = Meal::value('id');

        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->postJson('/api/v1/super-admin/meal-inventories', [
            'meal_id' => $meal_id,
            // 'meal_id' => Meal::all()->random()->id,
            'plates_prepared' => rand(50, 100),
            'available' => fake()->boolean(),
        ]);
        $response2 = $this->actingAs($admin)->postJson('/api/v1/admin/meal-inventories', [
            'meal_id' => $meal_id,
            'plates_prepared' => rand(50, 100),
            'available' => fake()->boolean(),
        ]);
        $response3 = $this->actingAs($user)->postJson('/api/v1/admin/meal-inventories', [
            'meal_id' => $meal_id,
            'plates_prepared' => rand(50, 100),
            'available' => fake()->boolean(),
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'plates_prepared'],
            ])
            ->assertJsonPath('data.meal_id', $meal_id);

        $this->assertDatabaseHas('meal_inventories', [
            'meal_id' => $meal_id,
        ]);

        $response2->assertStatus(201)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'plates_prepared'],
            ])
            ->assertJsonPath('data.meal_id', $meal_id);

        $this->assertDatabaseHas('meal_inventories', [
            'meal_id' => $meal_id,
        ]);

        $response3->assertStatus(403);
    }

    public function test_only_superadmin_and_admin_and_via_their_respective_routes_can_view_meal_inventory()
    {
        $meal_inventory_id = MealInventory::value('id');

        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/meal-inventories/' . $meal_inventory_id);
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/meal-inventories/' . $meal_inventory_id);
        $response3 = $this->actingAs($user)->getJson('/api/v1/admin/meal-inventories/' . $meal_inventory_id);

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'plates_prepared'],
            ]);

        $response2->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'plates_prepared'],
            ]);

        $response3->assertStatus(403);
    }

    public function test_only_superadmin_and_admin_via_their_respective_routes_can_update_meal_inventories()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $meal_inventory = MealInventory::factory()->create();

        $plates_prepared = rand(50, 100);

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/meal-inventories/' . $meal_inventory->id, [
            'plates_prepared' => $plates_prepared,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/admin/meal-inventories/' . $meal_inventory->id, [
            'plates_prepared' => $plates_prepared,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/admin/meal-inventories/' . $meal_inventory->id, [
            'plates_prepared' => $plates_prepared,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'plates_prepared'],
            ])
            ->assertJsonPath('data.plates_prepared', $plates_prepared);

        $this->assertDatabaseHas('meal_inventories', [
            'plates_prepared' => $plates_prepared,
        ]);

        $response2->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'plates_prepared'],
            ])
            ->assertJsonPath('data.plates_prepared', $plates_prepared);

        $this->assertDatabaseHas('meal_inventories', [
            'plates_prepared' => $plates_prepared,
        ]);

        $response3->assertStatus(403);
    }

    public function test_only_superadmin_and_admin_via_their_respective_routes_can_delete_meal_inventories()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $meal_inventory = MealInventory::factory()->create();
        $meal_inventory2 = MealInventory::factory()->create();
        $meal_inventory3 = MealInventory::factory()->create();

        $response = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/meal-inventories/' . $meal_inventory->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/meal-inventories/' . $meal_inventory2->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/admin/meal-inventories/' . $meal_inventory3->id);

        $response->assertNoContent();
        $response2->assertNoContent();

        $this->assertDatabaseHas('meal_inventories', [
            'id' => $meal_inventory->id,
            'deleted_at' => $meal_inventory->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('meal_inventories', 13);

        $response3->assertStatus(403);
    }
}
