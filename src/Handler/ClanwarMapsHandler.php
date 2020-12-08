<?php

namespace webspell_ng\Handler;

use \webspell_ng\Clanwar;
use \webspell_ng\ClanwarMap;
use \webspell_ng\WebSpellDatabaseConnection;


class ClanwarMapsHandler {

    private const DB_TABLE_NAME_CLANWARS_MAPS_MAPPING = "clanwars_maps_mapping";

    /**
     * @return array<ClanwarMap>
     */
    public static function getMapsOfClanwar(Clanwar $clanwar): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS_MAPPING)
            ->where('cwID = ?')
            ->setParameter(0, $clanwar->getClanwarId());

        $clanwar_maps = array();

        $clanwar_map_query = $queryBuilder->execute();
        while ($clanwar_map_result = $clanwar_map_query->fetch()) {

            $clanwar_map = new ClanwarMap();
            $clanwar_map->setMappingId((int) $clanwar_map_result['mappingID']);
            $clanwar_map->setMap(
                MapHandler::getMapByMapId((int) $clanwar_map_result['map_id'])
            );
            $clanwar_map->setScoreHome((int) $clanwar_map_result['score_home']);
            $clanwar_map->setScoreOpponent((int) $clanwar_map_result['score_opponent']);

            array_push($clanwar_maps, $clanwar_map);

        }

        return $clanwar_maps;

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
                            1 => $map->getMap()->getMapId(),
                            2 => $map->getMap()->getName(),
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
