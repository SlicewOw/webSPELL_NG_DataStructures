<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\Event;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Handler\LeagueCategoryHandler;
use webspell_ng\Handler\SquadHandler;
use webspell_ng\Utils\DateUtils;


class EventHandler {

    private const DB_TABLE_NAME_EVENTS = "events";

    public static function isExistingEvent(int $event_id): bool
    {

        if (!Validator::numericVal()->min(1)->validate($event_id)) {
            return false;
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_EVENTS)
            ->where('eventID = ?')
            ->setParameter(0, $event_id);

        $event_query = $queryBuilder->execute();
        $event_result = $event_query->fetch();

        return !empty($event_result);

    }

    public static function getEventById(int $event_id): Event
    {

        if (!Validator::numericVal()->min(1)->validate($event_id)) {
            throw new \InvalidArgumentException("event_id_value_is_invalid");
        }

        if (!EventHandler::isExistingEvent($event_id)) {
            throw new \InvalidArgumentException("event_id_is_not_existing");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_EVENTS)
            ->where('eventID = ?')
            ->setParameter(0, $event_id);

        $event_query = $queryBuilder->execute();
        $event_result = $event_query->fetch();

        $event = new Event();
        $event->setEventId($event_id);
        $event->setName($event_result['name']);
        $event->setHomepage($event_result['homepage']);
        $event->setSquad(
            SquadHandler::getSquadBySquadId((int) $event_result['squadID'])
        );
        $event->setDate(
            DateUtils::getDateTimeByMktimeValue($event_result['date'])
        );

        return $event;

    }

    /**
     * @return array<Event>
     */
    public static function getEventsOfSquad(int $squad_id): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('eventID')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_EVENTS)
            ->where(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('squadID', $squad_id),
                    $queryBuilder->expr()->eq('active', 1)
                )
            );

        $event_query = $queryBuilder->execute();

        $events = array();
        while ($event_result = $event_query->fetch())
        {
            array_push($events, self::getEventById($event_result['eventID']));
        }

        return $events;

    }

    public static function saveEvent(Event $event): Event
    {

        if (is_null($event->getSquad())) {
            throw new \InvalidArgumentException("squad_of_event_is_not_set");
        }

        if (is_null($event->getEventId())) {
            $event = self::insertEvent($event);
        } else {
            self::updateEvent($event);
        }

        return LeagueCategoryHandler::setEventLeagueCategory($event);

    }

    private static function insertEvent(Event $event): Event
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_EVENTS)
            ->values(
                    [
                        'date' => '?',
                        'name' => '?',
                        'squadID' => '?',
                        'homepage' => '?',
                        'offline' => '?',
                        'active' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $event->getDate()->getTimestamp(),
                        1 => $event->getName(),
                        2 => $event->getSquadId(),
                        3 => $event->getHomepage(),
                        4 => ($event->getIsOffline()) ? 1 : 0,
                        5 => 1
                    ]
                );

        $queryBuilder->execute();

        $event->setEventId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $event;

    }

    private static function updateEvent(Event $event): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_EVENTS)
            ->set('date', '?')
            ->set('name', '?')
            ->set('squadID', '?')
            ->set('homepage', '?')
            ->set('offline', '?')
            ->where('eventID = ?')
            ->setParameter(0, $event->getDate()->getTimestamp())
            ->setParameter(1, $event->getName())
            ->setParameter(2, $event->getSquadId())
            ->setParameter(3, $event->getHomepage())
            ->setParameter(4, $event->getIsOffline() ? 1 : 0)
            ->setParameter(5, $event->getEventId());

        $queryBuilder->execute();

    }

}
