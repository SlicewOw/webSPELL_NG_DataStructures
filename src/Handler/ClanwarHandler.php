<?php

namespace webspell_ng\Handler;

use \webspell_ng\Clanwar;
use webspell_ng\ClanwarMap;
use \webspell_ng\WebSpellDatabaseConnection;
use \webspell_ng\Handler\ClanwarMapsHandler;
use \webspell_ng\Utils\ValidationUtils;


class ClanwarHandler {

    private const DB_TABLE_NAME_CLANWARS = "clanwars";

    public static function saveMatch(Clanwar $clanwar): Clanwar
    {

        if (is_null($clanwar->getClanwarId())) {
            $clanwar = self::insertClanwar($clanwar);
        } else {
            self::updateClanwar($clanwar);
        }

        ClanwarMapsHandler::saveMapsOfClanwar($clanwar);

        return $clanwar;

    }

    private static function insertClanwar(Clanwar $clanwar): Clanwar
    {

        $home_string = serialize($clanwar->getHometeam());

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS)
            ->values(
                    [
                        'date' => '?',
                        'squadID' => '?',
                        'gameID' => '?',
                        'eventID' => '?',
                        'opponentID' => '?',
                        'homepage'=>'?',
                        'hometeam' => '?',
                        'report' => '?',
                        'report_uk' => '?',
                        'def_win' => '?',
                        'def_loss' => '?',
                        'active' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $clanwar->getDate()->getTimestamp(),
                        1 => $clanwar->getSquadId(),
                        2 => $clanwar->getGame()->getGameId(),
                        3 => $clanwar->getEventId(),
                        4 => $clanwar->getOpponent()->getClanId(),
                        5 => $clanwar->getMatchHomepage(),
                        6 => $home_string,
                        7 => $clanwar->getReportInGerman(),
                        8 => $clanwar->getReportInEnglish(),
                        9 => $clanwar->getIsDefaultWin() ? 1 : 0,
                        10 => $clanwar->getIsDefaultLoss() ? 1 : 0,
                        11 => 1
                    ]
                );

        $queryBuilder->execute();

        $clanwar->setClanwarId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $clanwar;

    }

    private static function updateClanwar(Clanwar $clanwar): void
    {

        $home_string = serialize($clanwar->getHometeam());

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS)
            ->set('date', '?')
            ->set('squadID', '?')
            ->set('gameID', '?')
            ->set('eventID', '?')
            ->set('opponentID', '?')
            ->set('homepage', '?')
            ->set('hometeam', '?')
            ->set('report', '?')
            ->set('report_uk', '?')
            ->set('def_win', '?')
            ->set('def_loss', '?')
            ->set('active', '?')
            ->where('cwID = ?')
            ->setParameter(0, $clanwar->getDate()->getTimestamp())
            ->setParameter(1, $clanwar->getSquadId())
            ->setParameter(2, $clanwar->getGame()->getGameId())
            ->setParameter(3, $clanwar->getEventId())
            ->setParameter(4, $clanwar->getOpponent()->getClanId())
            ->setParameter(5, $clanwar->getMatchHomepage())
            ->setParameter(6, $home_string)
            ->setParameter(7, $clanwar->getReportInGerman())
            ->setParameter(8, $clanwar->getReportInEnglish())
            ->setParameter(9, $clanwar->getIsDefaultWin() ? 1 : 0)
            ->setParameter(10, $clanwar->getIsDefaultLoss() ? 1 : 0)
            ->setParameter(11, 1)
            ->setParameter(12, $clanwar->getClanwarId());

        $queryBuilder->execute();

    }


    public static function addMapToClanwar(Clanwar $clanwar, int $map_id, int $score_home, int $score_opponent): Clanwar
    {

        $clanwar_map = new ClanwarMap();
        $clanwar_map->setMap(
            MapHandler::getMapByMapId($map_id)
        );
        $clanwar_map->setScoreHome($score_home);
        $clanwar_map->setScoreOpponent($score_opponent);

        $existing_maps = $clanwar->getMaps();
        $existing_maps[] = $clanwar_map;

        $clanwar->setMap($existing_maps);

        return $clanwar;

    }

    public static function isAnyMapSavedForClanwar(int $clanwar_id): bool
    {

        if (!ValidationUtils::validateInteger($clanwar_id, true)) {
            throw new \InvalidArgumentException("clanwar_id_value_is_not_valid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS)
            ->where('cwID = ?')
            ->setParameter(0, $clanwar_id);

        $clanwar_map_query = $queryBuilder->execute();
        $clanwar_map_result = $clanwar_map_query->fetch();

        return !empty($clanwar_map_result);

    }

}
