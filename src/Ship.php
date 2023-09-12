<?php

namespace Femi\Battle;

class Ship {
    private $type;
    private $size;
    private $hits;

    public function __construct($type, $size)
    {
        $this->type = $type;
        $this->size = $size;
        $this->hits = 0;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function isSunk()
    {
        return $this->hits >= $this->size;
    }
}