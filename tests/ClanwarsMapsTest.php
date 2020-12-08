<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \webspell_ng\ClanwarMap;
use \webspell_ng\Game;
use \webspell_ng\Handler\ClanwarMapsHandler;


final class ClanwarsMapsTest extends TestCase
{

    public function testIfClanwarMapInstanceIsCreated(): void
    {

        $clanwar_map = ClanwarMapsHandler::getMapByMapId(1);

        $this->assertInstanceOf(ClanwarMap::class, $clanwar_map, "Instance is of class 'ClanwarMap'");
        $this->assertEquals(1, $clanwar_map->getMapId(), "Map ID is set.");
        $this->assertNotEmpty($clanwar_map->getName(), "Map name is not empty.");
        $this->assertNotEmpty($clanwar_map->getIcon(), "Map icon is not empty.");
        $this->assertInstanceOf(Game::class, $clanwar_map->getGame(), "Instance is of class 'Game'");
        $this->assertNotEmpty($clanwar_map->getGame()->getName(), "Game name is not empty.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfMapIdIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ClanwarMapsHandler::getMapByMapId(-1);

    }

}
