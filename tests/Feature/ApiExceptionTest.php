<?php

use Tests\TestCase;

class ApiExceptionTest extends TestCase
{
    public function test_not_found_exception()
    {
        $response = $this->getJson('/api/invalid-endpoint');
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Resource not found',
            ]);
    }
}
