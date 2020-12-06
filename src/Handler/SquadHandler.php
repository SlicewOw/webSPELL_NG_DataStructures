<?php

namespace webspell_ng\Handler;

use \Respect\Validation\Validator;

use \webspell_ng\Squad;
use \webspell_ng\WebSpellDatabaseConnection;
use \webspell_ng\Handler\GameHandler;
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

        $squad_query = $queryBuilder->execute();
        $squad_result = $squad_query->fetch();

        if (empty($squad_result)) {
            throw new \InvalidArgumentException('unknown_squad');
        }

        $is_active = ($squad_result['active'] == 1);
        $is_gaming_squad = ($squad_result['gamesquad'] == 1);

        $squad = new Squad();
        $squad->setSquadId($squad_id);
        $squad->setIsGameSquad($is_gaming_squad);
        $squad->setName($squad_result['name']);
        $squad->setRubric($squad_result['rubric']);
        $squad->setIcon($squad_result['icon']);
        $squad->setIconSmall($squad_result['icon_small']);
        $squad->setIsActive($is_active);
        $squad->setSort($squad_result['sort']);
        $squad->setHits($squad_result['hits']);
        $squad->setIsDeleted($squad_result['deleted']);
        $squad->setDate(
            DateUtils::getDateTimeByMktimeValue($squad_result['date'])
        );
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

        return $squad;

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
                    'gameID' => '?',
                    'name' => '?',
                    'icon' => '?',
                    'icon_small' => '?',
                    'info' => '?',
                    'sort' => '?',
                    'rubric' => '?'
                )
            )
            ->setParameter(0, $squad->getDate()->getTimestamp())
            ->setParameter(1, $squad->getIsGameSquad() ? 1 : 0)
            ->setParameter(2, $squad->getGame()->getGameId())
            ->setParameter(3, $squad->getName())
            ->setParameter(4, $squad->getIcon())
            ->setParameter(5, $squad->getIconSmall())
            ->setParameter(6, $squad->getInfo())
            ->setParameter(7, $squad->getSort())
            ->setParameter(8, $squad->getRubric());

        $queryBuilder->execute();

        $squad_id = (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId();

        return self::getSquadBySquadId($squad_id );

    }

    private static function updateSquad(Squad $squad): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_SQUADS)
            ->set('gamesquad', '?')
            ->set('gameID', '?')
            ->set('name', '?')
            ->set('icon', '?')
            ->set('icon_small', '?')
            ->set('info', '?')
            ->set('sort', '?')
            ->set('rubric', '?')
            ->where('squadID = ?')
            ->setParameter(0, $squad->getIsGameSquad() ? 1 : 0)
            ->setParameter(1, $squad->getGame()->getGameId())
            ->setParameter(2, $squad->getName())
            ->setParameter(3, $squad->getIcon())
            ->setParameter(4, $squad->getIconSmall())
            ->setParameter(5, $squad->getInfo())
            ->setParameter(6, $squad->getSort())
            ->setParameter(7, $squad->getRubric())
            ->setParameter(8, $squad->getSquadId());

        $queryBuilder->execute();

    }

}
