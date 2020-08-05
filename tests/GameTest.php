<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Game;

final class GameTest extends TestCase
{

    public function testIfGameInstanceCanBeCreated(): void
    {

        $game = new Game();
        $game->setGameId(666);
        $game->setTag("TT");
        $game->setName("Test Team");

        $this->assertEquals(666, $game->getGameId(), "Game ID is set.");
        $this->assertEquals("TT", $game->getTag(), "Game tag is set.");
        $this->assertEquals("Test Team", $game->getName(), "Game name is set.");

    }

}