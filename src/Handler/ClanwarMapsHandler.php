<?php

namespace webspell_ng\Handler;

use \webspell_ng\Clanwar;
use \webspell_ng\ClanwarMap;
use \webspell_ng\WebSpellDatabaseConnection;
use \webspell_ng\Utils\ValidationUtils;


class ClanwarMapsHandler
{

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
            $clanwar_map->setScoreHome((int) $clanwar_map_result['score_home']);
            $clanwar_map->setScoreOpponent((int) $clanwar_map_result['score_opponent']);
            $clanwar_map->setSort((int) $clanwar_map_result['sort']);
            $clanwar_map->setMap(
                MapHandler::getMapByMapId((int) $clanwar_map_result['map_id'])
            );
            $clanwar_map->setIsDefaultWin(
                (int) $clanwar_map_result['def_win'] == 1
            );
            $clanwar_map->setIsDefaultLoss(
                (int) $clanwar_map_result['def_loss'] == 1
            );

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

        $map_index = 1;
        foreach ($clanwar_maps as $clanwar_map) {

            if (is_null($clanwar_map->getMap())) {
                throw new \UnexpectedValueException("map_of_clanwar_map_is_not_set_yet");
            }

            if (is_null($clanwar_map->getMappingId())) {
                self::insertClanwarMapping((int) $clanwar->getClanwarId(), $clanwar_map, $map_index);
            } else {
                self::updateClanwarMapping((int) $clanwar->getClanwarId(), $clanwar_map, $map_index);
            }

            $map_index++;
        }
    }

    private static function insertClanwarMapping(int $clanwar_id, ClanwarMap $clanwar_map, int $map_index): void
    {

        if (is_null($clanwar_map->getMap())) {
            throw new \UnexpectedValueException("map_of_clanwar_mapping_is_not_set");
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
                    'def_win' => '?',
                    'def_loss' => '?',
                    'sort' => '?'
                ]
            )
            ->setParameters(
                [
                    0 => $clanwar_id,
                    1 => $clanwar_map->getMap()->getMapId(),
                    2 => $clanwar_map->getMap()->getName(),
                    3 => $clanwar_map->getScoreHome(),
                    4 => $clanwar_map->getScoreOpponent(),
                    5 => $clanwar_map->isDefaultWin() ? 1 : 0,
                    6 => $clanwar_map->isDefaultLoss() ? 1 : 0,
                    7 => $map_index++
                ]
            );

        $queryBuilder->executeQuery();
    }

    private static function updateClanwarMapping(int $clanwar_id, ClanwarMap $clanwar_map, int $map_index): void
    {

        if (is_null($clanwar_map->getMap())) {
            throw new \UnexpectedValueException("map_of_clanwar_mapping_is_not_set");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS_MAPS_MAPPING)
            ->set(self::DB_TABLE_COLUMN_NAME_CLANWAR_ID, '?')
            ->set('map_id', '?')
            ->set('map_name', '?')
            ->set('score_home', '?')
            ->set('score_opponent', '?')
            ->set('def_win', '?')
            ->set('def_loss', '?')
            ->set('sort', '?')
            ->where('mappingID = ?')
            ->setParameter(0, $clanwar_id)
            ->setParameter(1, $clanwar_map->getMap()->getMapId())
            ->setParameter(2, $clanwar_map->getMap()->getName())
            ->setParameter(3, $clanwar_map->getScoreHome())
            ->setParameter(4, $clanwar_map->getScoreOpponent())
            ->setParameter(5, $clanwar_map->isDefaultWin() ? 1 : 0)
            ->setParameter(6, $clanwar_map->isDefaultLoss() ? 1 : 0)
            ->setParameter(7, $map_index)
            ->setParameter(8, $clanwar_map->getMappingId());

        $queryBuilder->executeQuery();
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
}
