<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\Squad;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Handler\GameHandler;
use webspell_ng\Utils\DateUtils;

class SquadHandler {

    private const DB_TABLE_SQUADS = "squads";

    public static function getSquadBySquadId(int $squad_id): Squad
    {

        if (!Validator::numericVal()->min(1)->validate($squad_id)) {
            throw new \InvalidArgumentException("squad_id_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_SQUADS)
            ->where('squadID = ?')
            ->setParameter(0, $squad_id);

        $squad_query = $queryBuilder->executeQuery();
        $squad_result = $squad_query->fetchAssociative();

        if (empty($squad_result)) {
            throw new \InvalidArgumentException('unknown_squad');
        }

        $is_gaming_squad = ($squad_result['gamesquad'] == 1);

        $squad = new Squad();
        $squad->setSquadId($squad_id);
        $squad->setIsGameSquad($is_gaming_squad);
        $squad->setIsConsoleSquad(
            ($squad_result['console'] == 1)
        );
        $squad->setName($squad_result['name']);
        $squad->setRubric($squad_result['rubric']);
        $squad->setIcon($squad_result['icon']);
        $squad->setIconSmall($squad_result['icon_small']);
        $squad->setIsActive(
            ($squad_result['active'] == 1)
        );
        $squad->setSort($squad_result['sort']);
        $squad->setHits($squad_result['hits']);
        $squad->setIsDeleted(
            ($squad_result['deleted'] == 1)
        );
        $squad->setDate(
            DateUtils::getDateTimeByMktimeValue($squad_result['date'])
        );
        $squad->setMembers(
            SquadMemberHandler::getMembersOfSquad($squad_id)
        );

        if (!is_null($squad_result['date_deleted'])) {
            $squad->setDateOfDeletion(
                DateUtils::getDateTimeByMktimeValue((int) $squad_result['date_deleted'])
            );
        }

        if (!is_null($squad_result['info'])) {
            $squad->setInfo($squad_result['info']);
        }

        if ($is_gaming_squad) {
            $squad->setGame(
                GameHandler::getGameByGameId((int) $squad_result['gameID'])
            );
        }

        return $squad;

    }

    public static function saveSquad(Squad $squad): Squad
    {

        if (is_null($squad->getSquadId())) {
            $squad = self::insertSquad($squad);
        } else {
            self::updateSquad($squad);
        }

        return self::getSquadBySquadId($squad->getSquadId());

    }

    private static function insertSquad(Squad $squad): Squad
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_SQUADS)
            ->values(
                array(
                    'date' => '?',
                    'gamesquad' => '?',
                    'console' => '?',
                    'gameID' => '?',
                    'name' => '?',
                    'icon' => '?',
                    'icon_small' => '?',
                    'info' => '?',
                    'sort' => '?',
                    'rubric' => '?',
                    'active' => '?'
                )
            )
            ->setParameter(0, $squad->getDate()->getTimestamp())
            ->setParameter(1, $squad->getIsGameSquad() ? 1 : 0)
            ->setParameter(2, $squad->getIsConsoleSquad() ? 1 : 0)
            ->setParameter(3, $squad->getGame()->getGameId())
            ->setParameter(4, $squad->getName())
            ->setParameter(5, $squad->getIcon())
            ->setParameter(6, $squad->getIconSmall())
            ->setParameter(7, $squad->getInfo())
            ->setParameter(8, $squad->getSort())
            ->setParameter(9, $squad->getRubric())
            ->setParameter(10, $squad->isActive() ? 1 : 0);

        $queryBuilder->executeQuery();

        $squad_id = (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId();

        return self::getSquadBySquadId($squad_id);

    }

    private static function updateSquad(Squad $squad): void
    {

        $deletion_date = !is_null($squad->getDateOfDeletion()) ? $squad->getDateOfDeletion()->getTimestamp() : null;

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_SQUADS)
            ->set('gamesquad', '?')
            ->set('console', '?')
            ->set('gameID', '?')
            ->set('name', '?')
            ->set('icon', '?')
            ->set('icon_small', '?')
            ->set('info', '?')
            ->set('sort', '?')
            ->set('rubric', '?')
            ->set('active', '?')
            ->set('date_deleted', '?')
            ->where('squadID = ?')
            ->setParameter(0, $squad->getIsGameSquad() ? 1 : 0)
            ->setParameter(1, $squad->getIsConsoleSquad() ? 1 : 0)
            ->setParameter(2, $squad->getGame()->getGameId())
            ->setParameter(3, $squad->getName())
            ->setParameter(4, $squad->getIcon())
            ->setParameter(5, $squad->getIconSmall())
            ->setParameter(6, $squad->getInfo())
            ->setParameter(7, $squad->getSort())
            ->setParameter(8, $squad->getRubric())
            ->setParameter(9, $squad->isActive() ? 1 : 0)
            ->setParameter(10, $deletion_date)
            ->setParameter(11, $squad->getSquadId());

        $queryBuilder->executeQuery();

    }

    public static function deleteSquad(Squad $squad): void
    {

        $squad->setIsActive(false);
        $squad->setIsDeleted(true);
        $squad->setDateOfDeletion(
            new \DateTime("now")
        );

        self::updateSquad($squad);

    }

}
