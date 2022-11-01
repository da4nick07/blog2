<?php

namespace Classes;

use PHPUnit\Framework\TestCase;
//use Classes\MyClass1;
use Exceptions\InvalidArgumentException;

// автозагрузку делает окружение...
//require_once './src/Classes/MyClass1.php';

class MyClass1Test extends TestCase {
    protected $myClass1;

    protected function setUp(): void
    {
        $this->myClass1 = new MyClass1 ();
    }

    protected function tearDown(): void
    {
        $this->myClass1 = NULL;
    }

    /**
     * @return void
     * @covers MyClass1::multiply
     * @dataProvider providerMultiply
     */
    public function testMultiply($a, $b, $c)
    {
//        $my = new MyClass1();
//        $this->assertEquals($c, $my->multiply($a, $b));
        $this->assertEquals($c, $this->myClass1->multiply($a, $b));
    }

    public function providerMultiply ()
    {
        return [
            'first'  => [2, 2, 4],
            'secend' => [2, 3, 6],
            'third' => [3, 5, 15]
        ];
    }

    /**
     * @return void
     * @covers MyClass1::multiply
     */
    // аннотации про исключения устарели
    public function testHasExeption1 ()
    {
        $this->expectException('InvalidArgumentException');
//        $this->myClass1->multiply("2", 3);
        $this->myClass1->multiply(2, '3');
    }
/*
    // не работает эта конструкция...
    public function testHasExeption2 ()
    {
        try {
            $this->myClass1->multiply(2, '3');
        } catch (InvalidArgumentException $e) {
            return;
        }
        $this->fail ('Not raise an exception InvalidArgumentException');
    }
*/
}
