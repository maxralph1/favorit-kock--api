<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Role;
use App\Models\User;
use App\Models\UserImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_and_admin_via_their_respective_routes_can_retrieve_user_images()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);

        $response = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/user-images');
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/user-images');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
        $response2->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(10, 'data');
    }

    public function test_all_user_roles_via_their_respective_routes_can_create_user_images()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $response = $this->actingAs($superAdmin)->postJson('/api/v1/super-admin/user-images', [
            'user_id' => $superAdmin->id,
            'image_url' => fake()->imageUrl(rand(50, 500), rand(50, 500), 'users', true, 'Faker'),
        ]);
        $response2 = $this->actingAs($admin)->postJson('/api/v1/admin/user-images', [
            'user_id' => $admin->id,
            'image_url' => fake()->imageUrl(rand(50, 500), rand(50, 500), 'users', true, 'Faker'),
        ]);
        $response3 = $this->actingAs($user)->postJson('/api/v1/user/user-images', [
            'user_id' => $user->id,
            'image_url' => fake()->imageUrl(rand(50, 500), rand(50, 500), 'users', true, 'Faker'),
        ]);
        $response4 = $this->actingAs($rider)->postJson('/api/v1/rider/user-images', [
            'user_id' => $rider->id,
            'image_url' => fake()->imageUrl(rand(50, 500), rand(50, 500), 'users', true, 'Faker'),
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.user_id', $superAdmin->id);

        $this->assertDatabaseHas('user_images', [
            'user_id' => $superAdmin->id,
        ]);

        $response2->assertStatus(201)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.user_id', $admin->id);

        $this->assertDatabaseHas('user_images', [
            'user_id' => $admin->id,
        ]);

        $response3->assertStatus(201)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('user_images', [
            'user_id' => $user->id,
        ]);

        $response4->assertStatus(201)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.user_id', $rider->id);

        $this->assertDatabaseHas('user_images', [
            'user_id' => $rider->id,
        ]);
    }

    public function test_all_user_roles_via_their_respective_routes_can_view_user_image()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $superAdminImage = UserImage::factory()->create(['user_id' => $superAdmin->id]);
        $adminImage = UserImage::factory()->create(['user_id' => $admin->id]);
        $userImage = UserImage::factory()->create(['user_id' => $user->id]);
        $riderImage = UserImage::factory()->create(['user_id' => $rider->id]);

        $response1 = $this->actingAs($superAdmin)->getJson('/api/v1/super-admin/user-images/' . $superAdminImage->id);
        $response2 = $this->actingAs($admin)->getJson('/api/v1/admin/user-images/' . $adminImage->id);
        $response3 = $this->actingAs($user)->getJson('/api/v1/user/user-images/' . $userImage->id);
        $response4 = $this->actingAs($rider)->getJson('/api/v1/rider/user-images/' . $riderImage->id);

        $response1->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ]);

        $response2->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ]);

        $response3->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ]);

        $response4->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ]);
    }

    public function test_only_superadmin_and_admin_via_their_respective_routes_can_update_every_other_users_user_images()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $user_image = UserImage::factory()->create();

        $user_image_url = fake()->imageUrl(rand(50, 500), rand(50, 500), 'users', true, 'Faker');

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/user-images/' . $user_image->id, [
            'image_url' => $user_image_url,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/admin/user-images/' . $user_image->id, [
            'image_url' => $user_image_url,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/admin/user-images/' . $user_image->id, [
            'image_url' => $user_image_url,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.image_url', $user_image_url);

        $this->assertDatabaseHas('user_images', [
            'image_url' => $user_image_url,
        ]);

        $response2->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.image_url', $user_image_url);

        $this->assertDatabaseHas('user_images', [
            'image_url' => $user_image_url,
        ]);

        $response3->assertStatus(403);
    }

    public function test_all_user_roles_can_update_user_image_belonging_to_them()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $superAdminImage = UserImage::factory()->create([
            'user_id' => $superAdmin->id,
        ]);
        $adminImage = UserImage::factory()->create([
            'user_id' => $admin->id,
        ]);
        $userImage = UserImage::factory()->create([
            'user_id' => $user->id,
        ]);
        $riderImage = UserImage::factory()->create([
            'user_id' => $rider->id,
        ]);

        $response = $this->actingAs($superAdmin)->putJson('/api/v1/super-admin/user-images/' . $superAdminImage->id, [
            'image_url' => $superAdminImage->image_url,
        ]);

        $response2 = $this->actingAs($admin)->putJson('/api/v1/admin/user-images/' . $adminImage->id, [
            'image_url' => $adminImage->image_url,
        ]);

        $response3 = $this->actingAs($user)->putJson('/api/v1/user/user-images/' . $userImage->id, [
            'image_url' => $userImage->image_url,
        ]);

        $response4 = $this->actingAs($rider)->putJson('/api/v1/rider/user-images/' . $riderImage->id, [
            'image_url' => $riderImage->image_url,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.user_id', $superAdmin->id);

        $this->assertDatabaseHas('user_images', [
            'image_url' => $superAdminImage->image_url,
        ]);

        $response2->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.user_id', $admin->id);

        $this->assertDatabaseHas('user_images', [
            'image_url' => $adminImage->image_url,
        ]);

        $response3->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('user_images', [
            'image_url' => $userImage->image_url,
        ]);

        $response4->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'image_url'],
            ])
            ->assertJsonPath('data.user_id', $rider->id);

        $this->assertDatabaseHas('user_images', [
            'image_url' => $riderImage->image_url,
        ]);
    }

    public function test_only_superadmin_and_admin_via_their_respective_routes_can_delete_every_other_users_user_images()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);

        $user_image = UserImage::factory()->create();
        $user_image2 = UserImage::factory()->create();
        $user_image3 = UserImage::factory()->create();

        $response = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/user-images/' . $user_image->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/user-images/' . $user_image2->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/admin/user-images/' . $user_image3->id);

        $response->assertNoContent();
        $response2->assertNoContent();

        $this->assertDatabaseHas('user_images', [
            'id' => $user_image->id,
            'deleted_at' => $user_image->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('user_images', 13);

        $response3->assertStatus(403);
    }

    public function test_all_user_roles_can_delete_user_images_belonging_to_them()
    {
        $superAdmin = User::factory()->create(['role_id' => Role::SUPERADMIN_ROLE]);
        $admin = User::factory()->create(['role_id' => Role::ADMIN_ROLE]);
        $user = User::factory()->create(['role_id' => Role::USER_ROLE]);
        $rider = User::factory()->create(['role_id' => Role::RIDER_ROLE]);

        $superAdminImage = UserImage::factory()->create([
            'user_id' => $superAdmin->id,
        ]);
        $adminImage = UserImage::factory()->create([
            'user_id' => $admin->id,
        ]);
        $userImage = UserImage::factory()->create([
            'user_id' => $user->id,
        ]);
        $riderImage = UserImage::factory()->create([
            'user_id' => $rider->id,
        ]);

        $response1 = $this->actingAs($superAdmin)->deleteJson('/api/v1/super-admin/user-images/' . $superAdminImage->id);
        $response2 = $this->actingAs($admin)->deleteJson('/api/v1/admin/user-images/' . $adminImage->id);
        $response3 = $this->actingAs($user)->deleteJson('/api/v1/user/user-images/' . $userImage->id);
        $response4 = $this->actingAs($rider)->deleteJson('/api/v1/rider/user-images/' . $riderImage->id);

        $response1->assertNoContent();
        $response2->assertNoContent();
        $response3->assertNoContent();
        $response4->assertNoContent();

        $this->assertDatabaseHas('user_images', [
            'id' => $superAdminImage->id,
            'deleted_at' => $superAdminImage->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('user_images', 14);

        $this->assertDatabaseHas('user_images', [
            'id' => $adminImage->id,
            'deleted_at' => $adminImage->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('user_images', 14);

        $this->assertDatabaseHas('user_images', [
            'id' => $userImage->id,
            'deleted_at' => $userImage->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('user_images', 14);

        $this->assertDatabaseHas('user_images', [
            'id' => $riderImage->id,
            'deleted_at' => $riderImage->updated_at,       // consider ignoring this line as the 'deleted_at' may differ from the 'updated_at' field by 1 second, thereby causing the test to fail; but will pass if ran again immediately after a failure.
        ])->assertDatabaseCount('user_images', 14);
    }
}
