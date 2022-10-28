<?php

namespace Controllers;

use PHPUnit\Framework\TestCase;

class MainControllerTest extends TestCase
{
    public function testMain()
    {
        // Необязательно: протестируйте здесь что-нибудь, если хотите.
        $this->assertTrue(true, 'This should already work.');

        // Остановиться тут и отметить, что тест неполный.
        $this->markTestIncomplete(
            'Этот тест ещё не реализован.'
        );
    }
}