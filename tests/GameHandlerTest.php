<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Game;
use webspell_ng\Handler\GameHandler;

final class GameHandlerTest extends TestCase
{

    public function testIfGameInstanceCanBeCreated(): void
    {

        $game = GameHandler::getGameByGameId(1);

        $this->assertEquals(1, $game->getGameId(), "Game ID is set.");
        $this->assertEquals("cs", $game->getTag(), "Game tag is set.");
        $this->assertEquals("CS1.6", $game->getShortcut(), "Game shortcut is set.");
        $this->assertEquals("Counter-Strike", $game->getName(), "Game name is set.");

    }

}