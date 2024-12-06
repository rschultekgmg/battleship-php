<?php

namespace Battleship;

use InvalidArgumentException;

class Letter
{

    public static $letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');
    public static $numbers = ['1', '2', '3', '4', '5', '6', '7', '8'];

    public static function value($index)
    {
        return self::$letters[$index];
    }

    public static function validate($letter) : string
    {
        if(!in_array($letter, self::$letters))
        {
            throw new InvalidArgumentException("Invalid column - should be from A to H");
        }

        return $letter;
    }

    public static function validateNumber($number) : string
    {
        if(!in_array($number, self::$numbers))
        {
            throw new InvalidArgumentException("Invalid row - should be from 1 to 8");
        }

        return $number;
    }
}
