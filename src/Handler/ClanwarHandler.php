<?php

namespace webspell_ng\Handler;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Respect\Validation\Validator;

use webspell_ng\Clanwar;
use webspell_ng\ClanwarMap;
use webspell_ng\Squad;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Enums\ClanwarEnums;
use webspell_ng\Handler\ClanwarMapsHandler;
use webspell_ng\Utils\DateUtils;
use webspell_ng\Utils\ValidationUtils;


class ClanwarHandler
{

    private const DB_TABLE_NAME_CLANWARS = "clanwars";

    public static function getClanwarByClanwarId(int $clanwar_id): Clanwar
    {

        if (!ValidationUtils::validateInteger($clanwar_id, true)) {
            throw new \InvalidArgumentException("clanwar_id_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS)
            ->where('cwID = ?')
            ->setParameter(0, $clanwar_id);

        $clanwar_query = $queryBuilder->executeQuery();
        $clanwar_result = $clanwar_query->fetchAssociative();

        if (empty($clanwar_result)) {
            throw new \UnexpectedValueException("unknown_clanwar");
        }

        $hometeam = array();
        if (!empty($clanwar_result["hometeam"])) {
            $hometeam = unserialize($clanwar_result["hometeam"]);
        }

        $clanwar_status = ClanwarEnums::CLANWAR_STATUS_NORMAL;
        if ($clanwar_result["def_win"]) {
            $clanwar_status = ClanwarEnums::CLANWAR_STATUS_DEFAULT_WIN;
        } else if ($clanwar_result["def_loss"]) {
            $clanwar_status = ClanwarEnums::CLANWAR_STATUS_DEFAULT_LOSS;
        }

        $clanwar = new Clanwar();
        $clanwar->setClanwarId((int) $clanwar_result["cwID"]);
        $clanwar->setStatus($clanwar_status);
        $clanwar->setSquad(
            SquadHandler::getSquadBySquadId((int) $clanwar_result["squadID"]),
            $hometeam
        );
        $clanwar->setOpponent(
            ClanHandler::getClanByClanId((int) $clanwar_result["opponentID"])
        );
        $clanwar->setLeague(
            EventHandler::getEventById((int) $clanwar_result["eventID"])
        );
        $clanwar->setDate(
            DateUtils::getDateTimeByMktimeValue((int) $clanwar_result["date"])
        );
        $clanwar->setReports(
            $clanwar_result["report"],
            $clanwar_result["report_uk"]
        );

        if (!is_null($clanwar_result["homepage"]) && !empty($clanwar_result["homepage"])) {
            $clanwar->setMatchURL($clanwar_result["homepage"]);
        }

        $clanwar->setMap(
            ClanwarMapsHandler::getMapsOfClanwar($clanwar)
        );

        return $clanwar;
    }

    /**
     * @return array<Clanwar>
     */
    public static function getAllMatchesOfSquad(Squad $squad): array
    {
        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        return self::getMatchesByFilter(
            $queryBuilder->expr()->and(
                $queryBuilder->expr()->eq('squadID', $squad->getSquadId()),
                $queryBuilder->expr()->eq('active', 1)
            )
        );
    }

    /**
     * @return array<Clanwar>
     */
    public static function getUpcomingMatchesOfSquad(Squad $squad, int $limit = -1, int $start_value = -1): array
    {
        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        return self::getMatchesByFilter(
            $queryBuilder->expr()->and(
                $queryBuilder->expr()->eq('squadID', $squad->getSquadId()),
                $queryBuilder->expr()->eq('active', 1),
                $queryBuilder->expr()->gt('date', time())
            ),
            $limit,
            $start_value
        );
    }

    /**
     * @return array<Clanwar>
     */
    public static function getRecentMatches(int $max_clanwars = 20): array
    {
        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        return self::getMatchesByFilter(
            $queryBuilder->expr()->and(
                $queryBuilder->expr()->eq('active', 1),
                $queryBuilder->expr()->lte('date', time())
            ),
            $max_clanwars,
            0
        );
    }

    /**
     * @return array<Clanwar>
     */
    public static function getUpcomingMatches(int $max_clanwars = 1): array
    {
        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        return self::getMatchesByFilter(
            $queryBuilder->expr()->and(
                $queryBuilder->expr()->eq('active', 1),
                $queryBuilder->expr()->gt('date', time())
            ),
            $max_clanwars,
            0
        );
    }

    /**
     * @return array<Clanwar>
     */
    public static function getRecentMatchesOfSquad(Squad $squad, int $limit = -1, int $start_value = -1): array
    {
        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        return self::getMatchesByFilter(
            $queryBuilder->expr()->and(
                $queryBuilder->expr()->eq('squadID', $squad->getSquadId()),
                $queryBuilder->expr()->eq('active', 1),
                $queryBuilder->expr()->lte('date', time())
            ),
            $limit,
            $start_value
        );
    }

    /**
     * @return array<Clanwar>
     */
    private static function getMatchesByFilter(CompositeExpression $expression, int $limit = -1, int $start_value = -1): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('cwID')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS)
            ->where($expression)
            ->orderBy("date", "ASC");

        if ($limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        if ($start_value > 0) {
            $queryBuilder->setFirstResult($start_value);
        }

        $clanwar_query = $queryBuilder->executeQuery();

        $matches = array();

        while ($clanwar_result = $clanwar_query->fetchAssociative()) {
            array_push(
                $matches,
                self::getClanwarByClanwarId((int) $clanwar_result['cwID'])
            );
        }

        return $matches;
    }

    public static function saveMatch(Clanwar $clanwar): Clanwar
    {

        if (is_null($clanwar->getClanwarId())) {
            $clanwar = self::insertClanwar($clanwar);
        } else {
            self::updateClanwar($clanwar);
        }

        ClanwarMapsHandler::saveMapsOfClanwar($clanwar);

        if (is_null($clanwar->getClanwarId())) {
            throw new \UnexpectedValueException("clanwar_id_is_invalid");
        }

        return self::getClanwarByClanwarId($clanwar->getClanwarId());
    }

    private static function insertClanwar(Clanwar $clanwar): Clanwar
    {

        if (is_null($clanwar->getGame())) {
            throw new \UnexpectedValueException("game_of_clanwar_is_not_set");
        } else if (is_null($clanwar->getEvent())) {
            throw new \UnexpectedValueException("event_of_clanwar_is_not_set");
        } else if (is_null($clanwar->getOpponent())) {
            throw new \UnexpectedValueException("opponent_of_clanwar_is_not_set");
        }

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
                    'homepage' => '?',
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
                    3 => $clanwar->getEvent()->getEventId(),
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

        $queryBuilder->executeQuery();

        $clanwar->setClanwarId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $clanwar;
    }

    private static function updateClanwar(Clanwar $clanwar): void
    {

        if (is_null($clanwar->getGame())) {
            throw new \UnexpectedValueException("game_of_clanwar_is_not_set");
        } else if (is_null($clanwar->getEvent())) {
            throw new \UnexpectedValueException("event_of_clanwar_is_not_set");
        } else if (is_null($clanwar->getOpponent())) {
            throw new \UnexpectedValueException("opponent_of_clanwar_is_not_set");
        }

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
            ->setParameter(3, $clanwar->getEvent()->getEventId())
            ->setParameter(4, $clanwar->getOpponent()->getClanId())
            ->setParameter(5, $clanwar->getMatchHomepage())
            ->setParameter(6, $home_string)
            ->setParameter(7, $clanwar->getReportInGerman())
            ->setParameter(8, $clanwar->getReportInEnglish())
            ->setParameter(9, $clanwar->getIsDefaultWin() ? 1 : 0)
            ->setParameter(10, $clanwar->getIsDefaultLoss() ? 1 : 0)
            ->setParameter(11, 1)
            ->setParameter(12, $clanwar->getClanwarId());

        $queryBuilder->executeQuery();
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

    public static function getCountOfPlayedMatches(int $squad_id): int
    {

        if (!Validator::numericVal()->min(1)->validate($squad_id)) {
            throw new \InvalidArgumentException("squad_id_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('COUNT(*) as `clanwars_played`')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CLANWARS)
            ->where(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('squadID', $squad_id),
                    $queryBuilder->expr()->lt('date', time())
                )
            );

        $stats_query = $queryBuilder->executeQuery();
        $stats_result = $stats_query->fetchAssociative();

        return isset($stats_result['clanwars_played']) ? (int) $stats_result['clanwars_played'] : 0;
    }
}
