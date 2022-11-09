<?php

namespace Controllers;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Controllers\MainController
 */
class MainControllerTest extends TestCase
{
    const PAGING_BLOCK = '!&lt; Пред &gt;</span>&nbsp&nbsp<b>1 </b>&nbsp<a href="/2">&lt; След &gt</a>!';
    protected $main ;
    protected $params ;

    /**
     * @coversNothing
     */
    protected function setUp(): void
    {
        $this->main = new MainController();
        $this->params[ USER ] = null;
    }

    /**
     * @coversNothing
     */
    protected function tearDown(): void
    {
        $this->main = NULL;
        unset($this->params);
    }

    /**
     * @covers \Controllers\MainController::main
     */
    public function testMain()
    {
/*
        // Необязательно: протестируйте здесь что-нибудь, если хотите.
        $this->assertTrue(true, 'This should already work.');

        // Остановиться тут и отметить, что тест неполный.
        $this->markTestIncomplete(
            'Этот тест ещё не реализован.'
        );
*/
        // если всё ОК - на гл. странице д.б. блок пагинации
        $this->expectOutputRegex(self::PAGING_BLOCK);
        $this->params[ MATCHES ] = array( '/1', '1');
        $this->main->main($this->params);

    }

    /**
     * @covers \Controllers\MainController::page
     * @dataProvider providerPage
     */
    public function testPage($matches)
    {
        // если всё ОК - на странице д.б. блок пагинации
        $this->expectOutputRegex(self::PAGING_BLOCK);
        $this->params[ MATCHES ] = $matches;
        $this->main->main($this->params);

    }

    public function providerPage ()
    {
        return [
            'first page'  => ['/1', '1'],
            'secend page' => ['/2', '2']
        ];
    }
}