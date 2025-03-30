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

    public function test_rover_stops_at_grid_boundary()
    {
        $response = $this->postJson('/api/rover/move', [
            'x' => 199,
            'y' => 199,
            'direction' => 'N',
            'commands' => 'F',
            'obstacles' => []
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'boundary_reached',
                     'position' => [199, 199],
                     'direction' => 'N'
                 ]);
    }

    public function test_rover_handles_multiple_obstacles()
    {
        $response = $this->postJson('/api/rover/move', [
            'x' => 0,
            'y' => 0,
            'direction' => 'N',
            'commands' => 'FRFLF',
            'obstacles' => [[0, 3], [1, 2]]
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'obstacle_detected',
                     'position' => [1, 1],
                     'direction' => 'N'
                 ]);
    }

    public function test_invalid_input_validation()
    {
        $response = $this->postJson('/api/rover/move', [
            'x' => 200,  // Out of bounds
            'y' => 0,
            'direction' => 'X',  // Invalid direction
            'commands' => 'ABCD',  // Invalid commands
            'obstacles' => [[200, 0]]  // Out of bounds obstacle
        ]);

        $response->assertStatus(422);  // Unprocessable Entity
    }
}
