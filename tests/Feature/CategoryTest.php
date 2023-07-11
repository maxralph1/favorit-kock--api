<?php

namespace Tests\Feature;

// use Database\Seeders\RoleSeeder;
// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_and_admin_and_generic_user_can_retrieve_categories_from_their_respective_routes()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/categories');
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/categories');
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/categories');

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

    public function test_generic_user_cannot_retrieve_categories_from_superadmin_nor_admin_routes()
    {
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($user)->getJson('/api/v1/super-admin/categories');
        $response2 = $this->actingAs($user)->getJson('/api/v1/admin/categories');

        $response->assertStatus(403);
        $response2->assertStatus(403);
    }

    public function test_only_superadmin_can_create_category()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->postJson('/api/v1/super-admin/categories', [
            'title' => 'Cookies',
            'description' => 'This is the Cookies recipe!',
        ]);
        $response2 = $this->actingAs($admin)->postJson('/api/v1/super-admin/categories', [
            'title' => 'Cookies',
            'description' => 'This is the Cookies recipe!',
        ]);
        $response3 = $this->actingAs($user)->postJson('/api/v1/super-admin/categories', [
            'title' => 'Cookies',
            'description' => 'This is the Cookies recipe!',
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'title'],
            ])
            ->assertJsonPath('data.title', 'Cookies');

        $this->assertDatabaseHas('categories', [
            'title' => 'Cookies',
        ]);

        $response2->assertStatus(403);
        $response3->assertStatus(403);
    }

    public function test_superadmin_and_admin_and_user_can_view_category_via_their_respective_routes()
    {
        $category_id = Category::value('id');

        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/categories/' . $category_id);
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/categories/' . $category_id);
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/categories/' . $category_id);

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'title'],
            ]);

        $response2->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'title'],
            ]);

        $response3->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'title'],
            ]);
    }

    public function test_only_superadmin_can_update_category()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $category = Category::factory()->create();

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/categories/' . $category->id, [
            'title' => 'Category Updated',
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/super-admin/categories/' . $category->id, [
            'title' => 'Category Updated II',
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/super-admin/categories/' . $category->id, [
            'title' => 'Category Updated III',
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'title'],
            ])
            ->assertJsonPath('data.title', 'Category Updated');

        $this->assertDatabaseHas('categories', [
            'title' => 'Category Updated',
        ]);

        $response2->assertStatus(403);
        $response3->assertStatus(403);
    }

    public function test_only_superadmin_can_delete_category()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $category = Category::factory()->create();
        $category2 = Category::factory()->create();
        $category3 = Category::factory()->create();

        $response = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/categories/' . $category->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/super-admin/categories/' . $category2->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/super-admin/categories/' . $category3->id);

        $response->assertNoContent();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'deleted_at' => $category->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('categories', 13);

        $response2->assertStatus(403);
        $response3->assertStatus(403);
    }
}
