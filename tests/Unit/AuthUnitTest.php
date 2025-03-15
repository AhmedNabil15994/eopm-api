<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthUnitTest extends TestCase
{
    use DatabaseTransactions;

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
        $user = User::factory()->count(1)->create([
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
        User::factory()->count(1)->create([
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
