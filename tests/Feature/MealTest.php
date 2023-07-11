<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Category;
use App\Models\Meal;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_and_admin_and_generic_user_can_retrieve_meals_from_their_respective_routes()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/meals');
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/meals');
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/meals');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
        $response2->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
        $response3->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
    }

    public function test_only_superadmin_can_create_meal()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $category_id = Category::value('id');

        $response = $this->actingAs($superAdmin)->postJson('/api/v1/super-admin/meals', [
            'category_id' => $category_id,
            'title' => 'Cookies',
            'description' => 'This is the Cookies recipe!',
            'price' => 20.00,
            'active' => true,
        ]);
        $response2 = $this->actingAs($admin)->postJson('/api/v1/super-admin/meals', [
            'category_id' => $category_id,
            'title' => 'Cookies',
            'description' => 'This is the Cookies recipe!',
            'price' => 20.00,
            'active' => true,
        ]);
        $response3 = $this->actingAs($user)->postJson('/api/v1/super-admin/meals', [
            'category_id' => $category_id,
            'title' => 'Cookies',
            'description' => 'This is the Cookies recipe!',
            'price' => 20.00,
            'active' => true,
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'title'],
            ])
            ->assertJsonPath('data.title', 'Cookies');

        $this->assertDatabaseHas('meals', [
            'title' => 'Cookies',
        ]);

        $response2->assertStatus(403);
        $response3->assertStatus(403);
    }

    public function test_superadmin_and_admin_and_user_can_view_meal_via_their_respective_routes()
    {
        $meal_id = Meal::value('id');

        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/meals/' . $meal_id);
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/meals/' . $meal_id);
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/meals/' . $meal_id);

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'title'],
            ]);

        $response2->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'title'],
            ]);

        $response3->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'title'],
            ]);
    }

    public function test_only_superadmin_can_update_meal()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $meal = Meal::factory()->create();

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/meals/' . $meal->id, [
            'title' => 'Meal Updated',
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/super-admin/meals/' . $meal->id, [
            'title' => 'Meal Updated II',
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/super-admin/meals/' . $meal->id, [
            'title' => 'Meal Updated III',
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'title'],
            ])
            ->assertJsonPath('data.title', 'Meal Updated');

        $this->assertDatabaseHas('meals', [
            'title' => 'Meal Updated',
        ]);

        $response2->assertStatus(403);
        $response3->assertStatus(403);
    }

    public function test_only_superadmin_can_delete_meal()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $meal = Meal::factory()->create();
        $meal2 = Meal::factory()->create();
        $meal3 = Meal::factory()->create();

        $response = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/meals/' . $meal->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/super-admin/meals/' . $meal2->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/super-admin/meals/' . $meal3->id);

        $response->assertNoContent();

        $this->assertDatabaseHas('meals', [
            'id' => $meal->id,
            'deleted_at' => $meal->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('meals', 13);

        $response2->assertStatus(403);
        $response3->assertStatus(403);
    }
}
