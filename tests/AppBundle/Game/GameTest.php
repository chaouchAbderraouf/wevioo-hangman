<?php

use AppBundle\Game\Game;

class GameTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideTestTryWordForTestWord
     */
    public function testTryWordForTestWord($word, $expectedResult)
    {
        $game = new Game('test');
        $this->assertSame($expectedResult, $game->tryWord($word));
    }

    public function provideTestTryWordForTestWord()
    {
        return [
            ['test', true],
            ['not test', false]
        ];

    }
}
