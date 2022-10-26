<?php

namespace Classes;

use PHPUnit\Framework\TestCase;
//use Classes\MyClass1;

require_once './src/Classes/MyClass1.php';

class MyClass1Test extends TestCase {
    public function testMultiply()
    {
        $my = new MyClass1();
        $this->assertEquals(6, $my->multiply(2, 3));
    }
}
