<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Modules\User\Entities\User;

class AuthFeatureTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test user can register successfully.
     */
    public function testUserCanRegister(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'mobile' => '1234567890',
            'calling_code' => '1'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'code',
                'data' => ['access_token', 'user']
            ]);
    }

    /**
     * Test user registration fails due to validation errors.
     */
    public function testUserRegistrationFailsDueToValidationErrors(): void
    {
        $response = $this->postJson('/api/auth/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password', 'name']);
    }

    /**
     * Test user can log in successfully.
     */
    public function testUserCanLogin(): void
    {
        $user = User::factory()->count(1)->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'code',
                'data' => ['access_token', 'user']
            ]);
    }

    /**
     * Test login fails with incorrect credentials.
     */
    public function testUserLoginFailsWithIncorrectCredentials(): void
    {
        User::factory()->count(1)->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Invalid data.']);
    }

    /**
     * Test logout fails for unauthenticated users.
     */
    public function testUserLogoutFailsForUnauthenticatedUser(): void
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
