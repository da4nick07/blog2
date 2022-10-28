<?php

namespace Env;

use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    public function testEnv()
    {
//        echo PHP_EOL . \ROOT_DIR . PHP_EOL;
        // ROOT_DIR определяется в tests/loadHTML_ENV
        // т.е. проверяем, что отработал загрузчик окружения
        $this->assertTrue( defined( 'ROOT_DIR'));
        // DIR_SEPARATOR задаётся в boot/const
        // т.е. проверяем, что отработал require_once ROOT_DIR . 'boot/init_html.php';
        // он и так выругается, но всё-же...
        $this->assertTrue( defined( 'DIR_SEPARATOR'));
    }
}
