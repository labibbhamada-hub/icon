<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertSuccessful();
    }

    public function test_certificate_verification_page_is_accessible(): void
    {
        $response = $this->get('/certificate/verify');

        $response->assertSuccessful();
    }
}
