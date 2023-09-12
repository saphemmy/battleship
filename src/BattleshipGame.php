<?php

namespace Femi\Battle;

class BattleshipGame {
    private $grid;
    private $ships;

    public function __construct(GameGrid $grid, array $ships)
    {
        $this->grid = $grid;
        $this->ships = $ships;
//        $this->placeShips();
        $this->placeShipsRandomly();

    }

    public function start($target) {
        echo "Welcome to Battleships!\n";
        echo "Try to sink all the ships on the grid.\n";

        while (!$this->isGameOver()) {
            $this->displayGrid();

            echo "Enter target (e.g., A5): ";
            $input = strtoupper(trim(fgets(STDIN))); // Read input from the console

            list($x, $y) = $this->parseCoordinate($input);

            if (!$this->isValidCoordinate($x, $y)) {
                echo "Invalid input. Please enter a valid target (e.g., A5).\n";
                continue;
            }

            if ($this->grid->isCoordinateHit($x, $y)) {
                echo "You already targeted this coordinate. Try again.\n";
                continue;
            }

            $result = $this->grid->hit($x, $y);

            if ($result === 'Miss') {
                echo "Miss!\n";
            } elseif ($result === 'Hit') {
                echo "Hit!\n";
            } elseif ($result === 'Sunk') {
                echo "You sunk a ship!\n";
            }
        }

        $this->displayGrid();
        echo "Congratulations! You sunk all the ships.\n";
    }

    private function displayGrid()
    {
        echo "  A B C D E F G H I J\n";
        $grid = $this->grid->getGrid();
        for ($x = 0; $x < 10; $x++) {
            echo "$x ";
            for ($y = 0; $y < 10; $y++) {
                $cell = $grid[$x][$y];

                if ($cell === ' ') {
                    echo ' ';
                } elseif ($cell === 'X') {
                    echo 'X ';
                } elseif ($cell === 'M') {
                    echo 'M ';
                } elseif ($cell instanceof Ship) {
                    echo 'S ';
                }
            }
            echo "\n";
        }
    }

    private function isGameOver()
    {
//        foreach ($this->ships as $ship) {
//            if (!$ship->isSunk()) {
//                return false;
//            }
//        }
//        return true;
        return $this->grid->isAllShipsSunk();
    }

    private function parseCoordinate($input)
    {
        if (strlen($input) !== 2 || !ctype_alpha($input[0]) || !ctype_digit($input[1])) {
            return [-1, -1]; // Invalid input
        }

        $x = ord($input[0]) - ord('A');
        $y = (int)$input[1];

        return [$x, $y];
    }

    private function isValidCoordinate($x, $y)
    {
        return $this->grid->isCoordinateValid($x, $y);
    }

    private function placeShips()
    {
        foreach ($this->ships as $ship) {
            $placed = false;
            while (!$placed) {
                $x = rand(0, 9);
                $y = rand(0, 9);
                $orientation = rand(0, 1) === 0 ? 'horizontal' : 'vertical';

                if ($this->grid->placeShip($x, $y, $ship, $orientation)) {
                    $placed = true;
                }
            }
        }
    }

    private function placeShipsRandomly()
    {
        foreach ($this->ships as $ship) {
            $this->grid->placeRandomly($ship);
        }
    }

    public function restartGame()
    {
        $this->grid->resetGrid();
        $this->placeShips();
    }

    public function displayPlayerGrid()
    {
        $this->grid->displayHiddenGrid();
    }

    public function getCellStatus($x, $y)
    {
        return $this->grid->getCellStatus($x, $y);
    }

    public function countRemainingShips()
    {
        return $this->grid->countShips();
    }

}