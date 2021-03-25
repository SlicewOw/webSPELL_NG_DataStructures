<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Game;
use webspell_ng\Map;
use webspell_ng\Handler\GameHandler;
use webspell_ng\Handler\MapHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class MapHandlerTest extends TestCase
{

    public function testIfClanwarMapCanBeSavedAndUpdated(): void
    {

        $map_name = "Test Map " . StringFormatterUtils::getRandomString(10);
        $map_icon = StringFormatterUtils::getRandomString(10) . ".png";
        $game_id = 1;

        $new_map = new Map();
        $new_map->setName($map_name);
        $new_map->setGame(
            GameHandler::getGameByGameId($game_id)
        );
        $new_map->setIcon($map_icon);

        $saved_map = MapHandler::saveMap($new_map);

        $this->assertInstanceOf(Map::class, $saved_map, "Instance is of class 'Map'");
        $this->assertGreaterThan(0, $saved_map->getMapId(), "Map ID is set.");
        $this->assertEquals($map_name, $saved_map->getName(), "Map name is set.");
        $this->assertEquals($map_icon, $saved_map->getIcon(), "Map icon is set.");
        $this->assertInstanceOf(Game::class, $saved_map->getGame(), "Instance is of class 'Game'");
        $this->assertEquals($game_id, $saved_map->getGame()->getGameId(), "Game ID is set.");

        $changed_map_name = "Test Map " . StringFormatterUtils::getRandomString(10);

        $changed_map = $saved_map;
        $changed_map->setName($changed_map_name);

        $updated_map = MapHandler::saveMap($changed_map);

        $this->assertInstanceOf(Map::class, $updated_map, "Instance is of class 'Map'");
        $this->assertGreaterThan(0, $updated_map->getMapId(), "Map ID is set.");
        $this->assertEquals($changed_map_name, $updated_map->getName(), "Map name is set.");
        $this->assertEquals($map_icon, $updated_map->getIcon(), "Map icon is set.");
        $this->assertInstanceOf(Game::class, $updated_map->getGame(), "Instance is of class 'Game'");
        $this->assertEquals($game_id, $updated_map->getGame()->getGameId(), "Game ID is set.");

    }

    public function testIfAllMapsAreReturned(): void
    {

        $all_maps = MapHandler::getAllMaps();

        $this->assertGreaterThan(0, count($all_maps), "Maps are returned.");

        $map = $all_maps[0];

        $this->assertInstanceOf(Map::class, $map, "Instance is of class 'Map'");
        $this->assertEquals(1, $map->getMapId(), "Map ID is set.");
        $this->assertNotEmpty($map->getName(), "Map name is not empty.");
        $this->assertNotNull($map->getIcon(), "Map icon is not empty.");
        $this->assertInstanceOf(Game::class, $map->getGame(), "Instance is of class 'Game'");
        $this->assertNotEmpty($map->getGame()->getName(), "Game name is not empty.");

        $maps_of_game = MapHandler::getMapsByGame($map->getGame());

        $this->assertGreaterThan(0, count($maps_of_game), "Maps are returned.");

        $only_the_own_game_is_present = true;

        foreach ($maps_of_game as $map_of_game) {

            if ($map_of_game->getGame()->getGameId() != $map->getGame()->getGameId()) {
                $only_the_own_game_is_present = false;
            }

        }

        $this->assertTrue($only_the_own_game_is_present, "The own game is returned only.");

    }

    public function testIfMapCanBeDeleted(): void
    {

        $new_map = new Map();
        $new_map->setName("Test Map " . StringFormatterUtils::getRandomString(10));
        $new_map->setIcon(StringFormatterUtils::getRandomString(10) . ".png");
        $new_map->setGame(
            GameHandler::getGameByGameId(1)
        );

        $saved_map = MapHandler::saveMap($new_map);

        $this->assertGreaterThan(0, $saved_map->getMapId(), "Map ID is set.");

        MapHandler::deleteMap($saved_map);

        $all_maps = MapHandler::getAllMaps();

        $map_is_deleted_successfully = true;

        foreach ($all_maps as $map) {

            if ($map->getName() == $new_map->getName()) {
                $map_is_deleted_successfully = false;
            }

        }

        $this->assertTrue($map_is_deleted_successfully, "Map is deleted successfully.");

        $deleted_map = MapHandler::getMapByMapId($saved_map->getMapId());

        $this->assertEquals($new_map->getName(), $deleted_map->getName(), "Map name is set.");
        $this->assertTrue($deleted_map->isDeleted(), "Map is deleted.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfMapIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        MapHandler::getMapByMapId(-1);

    }

    public function testIfUnexpectedValueExceptionIsThrownIfMapDoesNotExist(): void
    {

        $this->expectException(UnexpectedValueException::class);

        MapHandler::getMapByMapId(99999999);

    }

    public function testIfUnexpectedValueExceptionIsThrownIfGameOfMapIsNotSet(): void
    {

        $this->expectException(UnexpectedValueException::class);

        MapHandler::saveMap(new Map());

    }

}
