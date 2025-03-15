<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user password is hashed correctly.
     */
    public function testUserPasswordIsHashedCorrectly(): void
    {
        $user = User::factory()->create([
            'password' => 'plainpassword'
        ]);

        $this->assertTrue(Hash::check('plainpassword', $user->password));
    }

    /**
     * Test user authentication with correct credentials.
     */
    public function testUserAuthenticationWithCorrectCredentials(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $credentials = [
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ];

        $this->assertTrue(auth()->attempt($credentials));
    }

    /**
     * Test user authentication fails with incorrect credentials.
     */
    public function testUserAuthenticationFailsWithIncorrectCredentials(): void
    {
        User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $credentials = [
            'email' => 'testuser@example.com',
            'password' => 'wrongpassword'
        ];

        $this->assertFalse(auth()->attempt($credentials));
    }
}
