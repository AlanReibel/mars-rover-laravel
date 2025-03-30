<?php

namespace App\Services;

class RoverService
{
    private int $x;
    private int $y;
    private string $direction;
    private array $obstacles;
    private string $status;

    public function __construct(int $x, int $y, string $direction, array $obstacles = [])
    {
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
        $this->obstacles = $obstacles;
        $this->status = 'success';
    }

    public function executeCommands(string $commands): array
    {
        // Procesar cada comando
        foreach (str_split($commands) as $command) {
            $moveResult = $this->move($command);
            
            if ($moveResult === false) {
                return [
                    'status' => $this->status,
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
        // Verificar límites del grid y obstáculos antes de mover
        $newX = $this->x;
        $newY = $this->y;

        switch ($this->direction) {
            case 'N':
                $newY++;
                break;
            case 'S':
                $newY--;
                break;
            case 'E':
                $newX++;
                break;
            case 'W':
                $newX--;
                break;
        }

        // Comprobar límites del grid (0-199)
        if ($this->isBoundaryReached($newX, $newY)) {
            $this->status = 'boundary_reached';
            return false;
        }

        // Comprobar obstáculos
        if ($this->isObstacle($newX, $newY)) {
            $this->status = 'obstacle_detected';
            return false;
        }

        // Actualizar posición si es válido
        $this->x = $newX;
        $this->y = $newY;

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

    private function isBoundaryReached(int $newX, int $newY): bool
    {
        return $newX < 0 || $newX > 199 || $newY < 0 || $newY > 199;
    }
}
