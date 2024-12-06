<?php

namespace Battleship;

class Position
{
    /**
     * @var string
     */
    private $column;
    private $row;
    private $isHit;

    /**
     * Position constructor.
     * @param string $letter
     * @param string $number
     */
    public function __construct($letter, $number)
    {
        $this->column = Letter::validate(strtoupper($letter));
        $this->row = Letter::validateNumber($number);
        $this->isHit = false;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function getRow()
    {
        return $this->row;
    }

    public function setHit()
    {
        $this->isHit = true;
    }

    public function isHit()
    {
        return $this->isHit;
    }

    public function __toString()
    {
        return sprintf("%s%s", $this->column, $this->row);
    }
}
