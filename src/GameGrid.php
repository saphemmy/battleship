<?php

namespace Femi\Battle;

class GameGrid
{
    const EMPTY_CELL = ' ';
    const MISS = 'M';
    const SUNK = 'X';

    private $grid;

    public function __construct()
    {
        $this->initializeGrid();
    }

    public function getGrid()
    {
        return $this->grid;
    }

    public function placeShip($x, $y, Ship $ship, $direction)
    {
        $size = $ship->getSize();

        if (!$this->canPlaceShip($x, $y, $size, $direction)) {
            return false;
        }

        for ($i = 0; $i < $size; $i++) {
            $this->setCell($x, $y, $ship);
            $x += ($direction === 'horizontal') ? 1 : 0;
            $y += ($direction === 'vertical') ? 1 : 0;
        }

        return true;
    }

    public function placeRandomly(Ship $ship)
    {
        $direction = rand(0, 1) === 0 ? 'horizontal' : 'vertical';

        do {
            $x = rand(0, 9);
            $y = rand(0, 9);
        } while (!$this->placeShip($x, $y, $ship, $direction));
    }

    public function hit($x, $y) {
        if ($this->isEmpty($x, $y)) {
            $this->setCell($x, $y, self::MISS);
            return 'Miss';
        } elseif ($this->isShip($x, $y)) {
            $this->setCell($x, $y, self::SUNK);
            return 'Hit';
        }

        return 'Invalid';
    }

    public function isCoordinateHit($x, $y)
    {
        return in_array($this->getCell($x, $y), [self::MISS, self::SUNK], true);
    }

    public function isAllShipsSunk()
    {
        foreach ($this->grid as $row) {
            foreach ($row as $cell) {
                if ($cell === self::SUNK) {
                    return false;
                }
            }
        }
        return true;
    }

    public function resetGrid()
    {
        $this->initializeGrid();
    }

    public function displayHiddenGrid()
    {
        echo "  A B C D E F G H I J\n";
        for ($x = 0; $x < 10; $x++) {
            echo "$x ";
            for ($y = 0; $y < 10; $y++) {
                $cell = $this->getCell($x, $y);
                if ($this->isCoordinateHit($x, $y) || ($cell instanceof Ship && $cell->isSunk())) {
                    echo $cell . ' ';
                } else {
                    echo self::EMPTY_CELL . ' ';
                }
            }
            echo "\n";
        }
    }

    public function getCellStatus($x, $y)
    {
        return $this->getCell($x, $y);
    }

    public function countShips()
    {
        $count = 0;
        foreach ($this->grid as $row) {
            foreach ($row as $cell) {
                if ($cell instanceof Ship) {
                    $count++;
                }
            }
        }
        return $count;
    }

    public function isCoordinateValid($x, $y)
    {
        return $x >= 0 && $x < 10 && $y >= 0 && $y < 10;
    }

    private function initializeGrid()
    {
        $this->grid = array_fill(0, 10, array_fill(0, 10, self::EMPTY_CELL));
    }

    private function canPlaceShip($x, $y, $size, $direction)
    {
        if ($direction === 'horizontal' && $x + $size > 10) {
            return false;
        }
        if ($direction === 'vertical' && $y + $size > 10) {
            return false;
        }

        for ($i = 0; $i < $size; $i++) {
            if (!$this->isEmpty($x, $y)) {
                return false;
            }
            $x += ($direction === 'horizontal') ? 1 : 0;
            $y += ($direction === 'vertical') ? 1 : 0;
        }

        return true;
    }

    private function isEmpty($x, $y)
    {
        return $this->getCell($x, $y) === self::EMPTY_CELL;
    }

    private function isShip($x, $y)
    {
        return $this->getCell($x, $y) instanceof Ship;
    }

    private function getCell($x, $y)
    {
        return $this->grid[$x][$y];
    }

    private function setCell($x, $y, $value)
    {
        $this->grid[$x][$y] = $value;
    }
}