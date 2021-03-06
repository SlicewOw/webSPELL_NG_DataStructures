<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

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
        $this->assertTrue($game->isActive(), "Game is active.");

    }

    public function testIfGameIsReturnedByGameTag(): void
    {

        $game = GameHandler::getGameByTag("csg");

        $this->assertGreaterThan(0, $game->getGameId(), "Game ID is set.");
        $this->assertEquals("csg", $game->getTag(), "Game tag is set.");
        $this->assertEquals("CS:GO", $game->getShortcut(), "Game shortcut is set.");
        $this->assertEquals("Counter-Strike: Global Offensive", $game->getName(), "Game name is set.");
        $this->assertTrue($game->isActive(), "Game is active.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfGameIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        GameHandler::getGameByGameId(-1);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfGameDoesNotExistByGameId(): void
    {

        $this->expectException(InvalidArgumentException::class);

        GameHandler::getGameByGameId(99999999);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfGameDoesNotExistByTag(): void
    {

        $this->expectException(InvalidArgumentException::class);

        GameHandler::getGameByTag("aaa");

    }

}