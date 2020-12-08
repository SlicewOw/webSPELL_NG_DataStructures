<?php

namespace webspell_ng\Handler;

use \webspell_ng\Clanwar;
use \webspell_ng\ClanwarMap;
use \webspell_ng\WebSpellDatabaseConnection;
use \webspell_ng\Handler\GameHandler;
use \webspell_ng\Utils\ValidationUtils;


class ClanwarMapsHandler {

    private const DB_TABLE_NAME_CLANWARS_MAPS = "clanwars_maps";
    private const DB_TABLE_NAME_CLANWARS_MAPS_MAPPING = "clanwars_maps_mapping";

    public static function getMapByMapId(int $map_id): ClanwarMap
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

        $clanwar_map = new ClanwarMap();
        $clanwar_map->setMapId($map_id);
        $clanwar_map->setName($clanwar_map_result['name']);
        $clanwar_map->setIcon($clanwar_map_result['pic']);
        $clanwar_map->setGame(
            GameHandler::getGameByGameId((int) $clanwar_map_result['gameID'])
        );

        return $clanwar_map;

    }

    public static function saveMapsOfClanwar(Clanwar $clanwar): void
    {

        if (is_null($clanwar->getClanwarId())) {
            throw new \UnexpectedValueException('unknown_clanwar');
        }

        $mapsArray = $clanwar->getMaps();

        $anzMaps = count($mapsArray);
        if ($anzMaps < 1) {
            return;
        }

        self::removeExistingMapMappingsOfClanwar($clanwar);

        $map_index = 1;
        foreach ($mapsArray as $map) {

            $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
            $queryBuilder
                ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS_MAPPING)
                ->values(
                        [
                            'cw_id' => '?',
                            'map_id' => '?',
                            'map_name' => '?',
                            'score_home' => '?',
                            'score_opponent' => '?',
                            'sort' => '?'
                        ]
                    )
                ->setParameters(
                        [
                            0 => $clanwar->getClanwarId(),
                            1 => $map->getMapId(),
                            2 => $map->getName(),
                            3 => $map->getScoreHome(),
                            4 => $map->getScoreOpponent(),
                            5 => $map_index++
                        ]
                    );

            $queryBuilder->execute();

        }

    }

    private static function removeExistingMapMappingsOfClanwar(Clanwar $clanwar): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->delete(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS_MAPPING)
            ->where('cw_id = ?')
            ->setParameter(0, $clanwar->getClanwarId());

    }

}
