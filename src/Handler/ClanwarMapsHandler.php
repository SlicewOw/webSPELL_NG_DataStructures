<?php

namespace webspell_ng\Handler;

use \webspell_ng\Clanwar;
use \webspell_ng\ClanwarMap;
use \webspell_ng\WebSpellDatabaseConnection;
use \webspell_ng\Utils\ValidationUtils;


class ClanwarMapsHandler {

    private const DB_TABLE_NAME_CLANWARS_MAPS_MAPPING = "clanwars_maps_mapping";

    private const DB_TABLE_COLUMN_NAME_CLANWAR_ID = "cw_id";

    /**
     * @return array<ClanwarMap>
     */
    public static function getMapsOfClanwar(Clanwar $clanwar): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS_MAPPING)
            ->where(self::DB_TABLE_COLUMN_NAME_CLANWAR_ID . ' = ?')
            ->setParameter(0, $clanwar->getClanwarId());

        $clanwar_maps = array();

        $clanwar_map_query = $queryBuilder->executeQuery();
        while ($clanwar_map_result = $clanwar_map_query->fetchAssociative()) {

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

        $clanwar_maps = $clanwar->getMaps();

        if (empty($clanwar_maps)) {
            return;
        }

        self::removeExistingMapMappingsOfClanwar($clanwar);

        $map_index = 1;
        foreach ($clanwar_maps as $clanwar_map) {

            if (is_null($clanwar_map->getMap())) {
                throw new \UnexpectedValueException("map_of_clanwar_map_is_not_set_yet");
            }

            $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
            $queryBuilder
                ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS_MAPPING)
                ->values(
                        [
                            self::DB_TABLE_COLUMN_NAME_CLANWAR_ID => '?',
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
                            1 => $clanwar_map->getMap()->getMapId(),
                            2 => $clanwar_map->getMap()->getName(),
                            3 => $clanwar_map->getScoreHome(),
                            4 => $clanwar_map->getScoreOpponent(),
                            5 => $map_index++
                        ]
                    );

            $queryBuilder->executeQuery();

        }

    }

    public static function isAnyMapSavedForClanwar(int $clanwar_id): bool
    {

        if (!ValidationUtils::validateInteger($clanwar_id, true)) {
            throw new \InvalidArgumentException("clanwar_id_value_is_not_valid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS_MAPPING)
            ->where(self::DB_TABLE_COLUMN_NAME_CLANWAR_ID . ' = ?')
            ->setParameter(0, $clanwar_id);

        $clanwar_map_query = $queryBuilder->executeQuery();
        $clanwar_map_result = $clanwar_map_query->fetchAssociative();

        return !empty($clanwar_map_result);

    }

    private static function removeExistingMapMappingsOfClanwar(Clanwar $clanwar): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->delete(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS_MAPPING)
            ->where(self::DB_TABLE_COLUMN_NAME_CLANWAR_ID . ' = ?')
            ->setParameter(0, $clanwar->getClanwarId());

    }

}
