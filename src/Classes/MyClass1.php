<?php

namespace Classes;

use InvalidArgumentException;

class MyClass1
{
    public function multiply($x, $y)
    {
        if ( !is_int($y)) {
            throw new InvalidArgumentException('Secend parameter is\'not integer!');
        }

        return $x *$y;
    }
}