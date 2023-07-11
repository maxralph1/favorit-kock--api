<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Meal;
use App\Models\MealImage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_and_admin_and_generic_user_via_their_respective_routes_can_retrieve_meal_images()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/meal-images');
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/meal-images');
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/meal-images');

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

    public function test_only_super_admin_and_admin_via_their_respective_routes_can_create_meal_images()
    {
        $meal_id = Meal::value('id');

        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->postJson('/api/v1/super-admin/meal-images', [
            'meal_id' => $meal_id,
            'default' => false,
            'image_url' => fake()->imageUrl(rand(50, 500), rand(50, 500), 'meals', true, 'Faker'),
        ]);
        $response2 = $this->actingAs($admin)->postJson('/api/v1/admin/meal-images', [
            'meal_id' => $meal_id,
            'default' => false,
            'image_url' => fake()->imageUrl(rand(50, 500), rand(50, 500), 'meals', true, 'Faker'),
        ]);
        $response3 = $this->actingAs($user)->postJson('/api/v1/admin/meal-images', [
            'meal_id' => $meal_id,
            'default' => false,
            'image_url' => fake()->imageUrl(rand(50, 500), rand(50, 500), 'meals', true, 'Faker'),
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.default', false);

        $this->assertDatabaseHas('meal_images', [
            'default' => false,
        ]);

        $response2->assertStatus(201)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'default'],
            ])
            ->assertJsonPath('data.default', false);

        $this->assertDatabaseHas('meal_images', [
            'default' => false,
        ]);

        $response3->assertStatus(403);
    }

    public function test_superadmin_and_admin_and_generic_user_via_their_respective_routes_can_view_meal_image()
    {
        $meal_image_id = MealImage::value('id');

        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/meal-images/' . $meal_image_id);
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/meal-images/' . $meal_image_id);
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/meal-images/' . $meal_image_id);

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ]);

        $response2->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ]);

        $response3->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ]);
    }

    public function test_only_superadmin_and_admin_via_their_respective_routes_can_update_meal_images()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $meal_image = MealImage::factory()->create();

        $meal_image_url = fake()->imageUrl(rand(50, 500), rand(50, 500), 'meals', true, 'Faker');

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/meal-images/' . $meal_image->id, [
            'image_url' => $meal_image_url,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/admin/meal-images/' . $meal_image->id, [
            'image_url' => $meal_image_url,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/admin/meal-images/' . $meal_image->id, [
            'image_url' => $meal_image_url,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.image_url', $meal_image_url);

        $this->assertDatabaseHas('meal_images', [
            'image_url' => $meal_image_url,
        ]);

        $response2->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.image_url', $meal_image_url);

        $this->assertDatabaseHas('meal_images', [
            'image_url' => $meal_image_url,
        ]);

        $response3->assertStatus(403);
    }

    public function test_only_superadmin_and_admin_via_their_respective_routes_can_delete_meal_images()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $meal_image = MealImage::factory()->create();
        $meal_image2 = MealImage::factory()->create();
        $meal_image3 = MealImage::factory()->create();

        $response = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/meal-images/' . $meal_image->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/meal-images/' . $meal_image2->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/admin/meal-images/' . $meal_image3->id);

        $response->assertNoContent();
        $response2->assertNoContent();

        $this->assertDatabaseHas('meal_images', [
            'id' => $meal_image->id,
            'deleted_at' => $meal_image->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('meal_images', 13);

        $response3->assertStatus(403);
    }
}
