<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MultiplicationControllerTest extends TestCase
{
    /**
     * Testing index without any size parameter.
     */
    public function testIndex(): void
    {
        $response = $this->get('/');

        $response->assertViewIs('welcome');
    }
    /**
     * Testing index with size parameter.
     */
    public function testIndexWithGetSize()
    {
        $response = $this->get('/?size=1');

        $response->assertViewIs('welcome');
        $response->assertViewHas('res', '{"1":{"1":1}}');
        $response->assertViewHas('size', 1);
    }
}
