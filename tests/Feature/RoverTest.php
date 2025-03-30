<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoverTest extends TestCase
{
    public function test_rover_moves_correctly()
    {
        $response = $this->postJson('/api/rover/move', [
            'x' => 0,
            'y' => 0,
            'direction' => 'N',
            'commands' => 'FFRFF',
            'obstacles' => []
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'position' => [2, 2],
                     'direction' => 'E'
                 ]);
    }

    public function test_rover_stops_at_obstacle()
    {
        $response = $this->postJson('/api/rover/move', [
            'x' => 0,
            'y' => 0,
            'direction' => 'N',
            'commands' => 'FFFF',
            'obstacles' => [[0, 3]]
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'obstacle_detected',
                     'position' => [0, 2],
                     'direction' => 'N'
                 ]);
    }
}
