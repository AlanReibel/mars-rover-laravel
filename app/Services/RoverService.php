<?php

namespace App\Services;

class RoverService
{
    private int $x;
    private int $y;
    private string $direction;
    private array $obstacles;

    public function __construct(int $x, int $y, string $direction, array $obstacles = [])
    {
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
        $this->obstacles = $obstacles;
    }

    public function executeCommands(string $commands): array
    {
        // Procesar cada comando
        foreach (str_split($commands) as $command) {
            if (!$this->move($command)) {
                return [
                    'status' => 'obstacle_detected',
                    'position' => [$this->x, $this->y],
                    'direction' => $this->direction
                ];
            }
        }

        return [
            'status' => 'success',
            'position' => [$this->x, $this->y],
            'direction' => $this->direction
        ];
    }

    private function move(string $command): bool
    {
        // Lógica para mover el rover según el comando
        switch ($command) {
            case 'F':
                if (!$this->moveForward()) return false; // Si hay obstáculo, retorna false
                break;
            case 'L':
                $this->turnLeft();
                break;
            case 'R':
                $this->turnRight();
                break;
        }

        return true; // Si el movimiento fue exitoso, continúa con el siguiente comando
    }

    private function moveForward(): bool
    {
        // Verificar si hay un obstáculo en la posición actual antes de mover
        if ($this->isObstacle($this->x, $this->y + 1)) {  // Aquí verificamos si hay un obstáculo en la siguiente posición (para el movimiento hacia el norte)
            return false;
        }
    
        // Mover el rover
        switch ($this->direction) {
            case 'N':
                $this->y++;
                break;
            case 'S':
                $this->y--;
                break;
            case 'E':
                $this->x++;
                break;
            case 'W':
                $this->x--;
                break;
        }
    
        return true;
    }

    private function turnLeft()
    {
        // Girar a la izquierda
        $turns = ['N' => 'W', 'W' => 'S', 'S' => 'E', 'E' => 'N'];
        $this->direction = $turns[$this->direction];
    }

    private function turnRight()
    {
        // Girar a la derecha
        $turns = ['N' => 'E', 'E' => 'S', 'S' => 'W', 'W' => 'N'];
        $this->direction = $turns[$this->direction];
    }

    private function isObstacle(int $x, int $y): bool
    {
        // Verificar si hay un obstáculo en la posición (x, y)
        return in_array([$x, $y], $this->obstacles);
    }
}
