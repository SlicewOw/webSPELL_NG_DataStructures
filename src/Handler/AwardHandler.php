<?php

namespace webspell_ng\Handler;

use \webspell_ng\Award;
use \webspell_ng\WebSpellDatabaseConnection;
use \webspell_ng\Handler\EventHandler;
use \webspell_ng\Handler\LeagueCategoryHandler;
use \webspell_ng\Handler\SquadHandler;
use \webspell_ng\Utils\DateUtils;
use \webspell_ng\Utils\ValidationUtils;


class AwardHandler {

    private const DB_TABLE_NAME_AWARDS = "awards";

    public static function getAwardById(int $award_id): Award
    {

        if (!ValidationUtils::validateInteger($award_id)) {
            throw new \InvalidArgumentException('award_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_AWARDS)
            ->where('awardID = ?')
            ->setParameter(0, $award_id);

        $award_query = $queryBuilder->execute();
        $award_result = $award_query->fetch();

        if (empty($award_result)) {
            throw new \UnexpectedValueException("unknown_award");
        }

        $award = new Award();
        $award->setAwardId($award_id);
        $award->setName($award_result['name']);
        $award->setHomepage($award_result['homepage']);
        $award->setOffline($award_result['offline']);
        $award->setRank($award_result['rang']);
        $award->setHits($award_result['hits']);
        $award->setSquad(
            SquadHandler::getSquadBySquadId((int) $award_result['squadID'])
        );
        $award->setDate(
            DateUtils::getDateTimeByMktimeValue($award_result['date'])
        );
        if (!is_null($award_result['category'])) {
            $award->setLeagueCategory($award_result['category']);
        }
        if (!is_null($award_result['info'])) {
            $award->setDescription($award_result['info']);
        }
        if (!is_null($award_result['eventID'])) {
            $award->setEvent(
                EventHandler::getEventById((int) $award_result['eventID'])
            );
        }

        return $award;

    }
    /**
     * @return array<Award>
     */
    public static function getAwardsOfSquad(int $squad_id): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('awardID')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_AWARDS)
            ->where(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('squadID', $squad_id),
                    $queryBuilder->expr()->eq('active', 1)
                )
            );

        $award_query = $queryBuilder->execute();

        $awards = array();
        while ($award_result = $award_query->fetch())
        {
            array_push($awards, self::getAwardById($award_result['awardID']));
        }

        return $awards;

    }

    /**
     * @codeCoverageIgnore
     */
    private static function checkAwardVariables(Award $award): bool
    {

        if (is_null($award->getSquad()) || !ValidationUtils::validateInteger($award->getSquadId(), true)) {
            throw new \UnexpectedValueException('enter_squad');
        }

        if (is_null($award->getName()) || empty($award->getName())) {
            throw new \UnexpectedValueException('enter_title');
        }

        if (is_null($award->getHomepage()) || empty($award->getHomepage())) {
            throw new \UnexpectedValueException('enter_url');
        }

        if (!ValidationUtils::validateInteger($award->getRank(), true)) {
            throw new \UnexpectedValueException('enter_rank_type');
        }

        return True;

    }

    public static function saveAward(Award $award): Award
    {

        self::checkAwardVariables($award);

        if (is_null($award->getAwardId())) {
            $award = self::insertAward($award);
        } else {
            self::updateAward($award);
        }

        return LeagueCategoryHandler::setAwardLeagueCategory($award);

    }

    private static function insertAward(Award $award): Award
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_AWARDS)
            ->values(
                    [
                        'date' => '?',
                        'name' => '?',
                        'squadID' => '?',
                        'eventID' => '?',
                        'homepage' => '?',
                        'rang' => '?',
                        'offline' => '?',
                        'info' => '?',
                        'active' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $award->getDate()->getTimestamp(),
                        1 => $award->getName(),
                        2 => $award->getSquadId(),
                        3 => $award->getEventId(),
                        4 => $award->getHomepage(),
                        5 => $award->getRank(),
                        6 => ($award->getOffline()) ? 1 : 0,
                        7 => $award->getDescription(),
                        8 => 1
                    ]
                );

        $queryBuilder->execute();

        $award->setAwardId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $award;

    }

    private static function updateAward(Award $award): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_AWARDS)
            ->set('date', '?')
            ->set('name', '?')
            ->set('squadID', '?')
            ->set('eventID', '?')
            ->set('homepage', '?')
            ->set('rang', '?')
            ->set('offline', '?')
            ->where('awardID = ?')
            ->setParameter(0, $award->getDate()->getTimestamp())
            ->setParameter(1, $award->getName())
            ->setParameter(2, $award->getSquadId())
            ->setParameter(3, $award->getEventId())
            ->setParameter(4, $award->getHomepage())
            ->setParameter(5, $award->getRank())
            ->setParameter(6, $award->getOffline() ? 1 : 0)
            ->setParameter(7, $award->getAwardId());

        $queryBuilder->execute();

    }

}
