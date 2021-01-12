<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Game;
use webspell_ng\Map;
use webspell_ng\Handler\MapHandler;


final class MapHandlerTest extends TestCase
{

    public function testIfClanwarMapInstanceIsCreated(): void
    {

        $map = MapHandler::getMapByMapId(1);

        $this->assertInstanceOf(Map::class, $map, "Instance is of class 'ClanwarMap'");
        $this->assertEquals(1, $map->getMapId(), "Map ID is set.");
        $this->assertNotEmpty($map->getName(), "Map name is not empty.");
        $this->assertNotEmpty($map->getIcon(), "Map icon is not empty.");
        $this->assertInstanceOf(Game::class, $map->getGame(), "Instance is of class 'Game'");
        $this->assertNotEmpty($map->getGame()->getName(), "Game name is not empty.");

        $maps = MapHandler::getMapsByGame($map->getGame());

        $this->assertGreaterThan(0, count($maps), "Maps are returned.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfMapIdIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        MapHandler::getMapByMapId(-1);

    }

}
