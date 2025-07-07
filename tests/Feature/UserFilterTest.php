<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserFilterTest extends TestCase
{ 
    public function test_gender_filter()
    {
        $response = $this->get('/users?gender=male');
        $response->assertStatus(200);
        $response->assertSee('Male');
    }
}
