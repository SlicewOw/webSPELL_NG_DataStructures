<?php

namespace webspell_ng\Handler;

use webspell_ng\Game;
use webspell_ng\Map;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Handler\GameHandler;
use webspell_ng\Utils\ValidationUtils;


class MapHandler {

    private const DB_TABLE_NAME_CLANWARS_MAPS = "clanwars_maps";

    public static function getMapByMapId(int $map_id): Map
    {

        if (!ValidationUtils::validateInteger($map_id, true)) {
            throw new \InvalidArgumentException('map_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS)
            ->where('mapID = ?')
            ->setParameter(0, $map_id);

        $clanwar_map_query = $queryBuilder->execute();
        $clanwar_map_result = $clanwar_map_query->fetch();

        if (empty($clanwar_map_result)) {
            throw new \UnexpectedValueException("unknown_clanwar_map");
        }

        $clanwar_map = new Map();
        $clanwar_map->setMapId($map_id);
        $clanwar_map->setName($clanwar_map_result['name']);
        $clanwar_map->setIcon($clanwar_map_result['pic']);
        $clanwar_map->setGame(
            GameHandler::getGameByGameId((int) $clanwar_map_result['gameID'])
        );

        return $clanwar_map;

    }

    /**
     * @return array<Map>
     */
    public static function getMapsByGame(Game $game): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('mapID')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS)
            ->where('gameID = ?')
            ->setParameter(0, $game->getGameId())
            ->orderBy("name", "ASC");

        $map_query = $queryBuilder->execute();

        $maps = array();
        while ($map_result = $map_query->fetch())
        {
            array_push(
                $maps,
                self::getMapByMapId((int) $map_result['mapID'])
            );
        }

        return $maps;

    }

    /**
     * @return array<Map>
     */
    public static function getAllMaps(): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('mapID')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS)
            ->orderBy("gameID", "ASC");

        $map_query = $queryBuilder->execute();

        $maps = array();
        while ($map_result = $map_query->fetch())
        {
            array_push(
                $maps,
                self::getMapByMapId((int) $map_result['mapID'])
            );
        }

        return $maps;

    }

    public static function saveMap(Map $map): Map
    {

        if (is_null($map->getGame())) {
            throw new \UnexpectedValueException("game_of_map_is_not_set_yet");
        }

        if (is_null($map->getMapId())) {
            $map = self::insertMap($map);
        } else {
            self::updateMap($map);
        }

        return self::getMapByMapId($map->getMapId());

    }

    private static function insertMap(Map $map): Map
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS)
            ->values(
                array(
                    'name' => '?',
                    'gameID' => '?',
                    'pic' => '?'
                )
            )
            ->setParameter(0, $map->getName())
            ->setParameter(1, $map->getGame()->getGameId())
            ->setParameter(2, $map->getIcon());

        $queryBuilder->execute();

        $map->setMapId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $map;

    }

    private static function updateMap(Map $map): void
    {


        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS)
            ->set('name', '?')
            ->set('gameID', '?')
            ->set('pic', '?')
            ->where('mapID = ?')
            ->setParameter(0, $map->getName())
            ->setParameter(1, $map->getGame()->getGameId())
            ->setParameter(2, $map->getIcon())
            ->setParameter(3, $map->getMapId());

        $queryBuilder->execute();

    }

}