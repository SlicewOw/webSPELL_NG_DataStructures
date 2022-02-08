<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\Game;
use webspell_ng\SquadMemberPosition;
use webspell_ng\WebSpellDatabaseConnection;


class SquadMemberPositionHandler {

    private const DB_TABLE_NAME_SQUADS_MEMBER_POSITION = "squads_members_position";

    public static function getMemberPositionById(int $position_id): SquadMemberPosition
    {

        if (!Validator::numericVal()->min(1)->validate($position_id)) {
            throw new \InvalidArgumentException("position_id_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SQUADS_MEMBER_POSITION)
            ->where('positionID = ?')
            ->setParameter(0, $position_id);

        $position_query = $queryBuilder->executeQuery();
        $position_result = $position_query->fetchAssociative();

        return self::getMemberPositionByQueryResult($position_result);

    }

    public static function getMemberPositionByParameters(string $tag, ?Game $game = null): SquadMemberPosition
    {

        if (empty($tag)) {
            throw new \InvalidArgumentException("tag_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SQUADS_MEMBER_POSITION)
            ->where('tag = ?')
            ->setParameter(0, $tag);

        if (!is_null($game)) {
            $queryBuilder
                ->andWhere('gameID = ?')
                ->setParameter(1, $game->getGameId());
        } else {
            $queryBuilder
                ->andWhere('gameID IS NULL');
        }

        $position_query = $queryBuilder->executeQuery();
        $position_result = $position_query->fetchAssociative();

        return self::getMemberPositionByQueryResult($position_result);

    }

    /**
     * @return array<SquadMemberPosition>
     */
    public static function getAllMemberPositions(): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SQUADS_MEMBER_POSITION)
            ->orderBy('sort', 'ASC');

        $position_query = $queryBuilder->executeQuery();
        $position_results = $position_query->fetchAllAssociative();

        $all_member_positions = array();
        foreach ($position_results as $position_result) {

            array_push(
                $all_member_positions,
                self::getMemberPositionByQueryResult($position_result)
            );

        }

        return $all_member_positions;

    }

    /**
     * @param array<string,mixed>|false $query_result
     */
    private static function getMemberPositionByQueryResult($query_result): SquadMemberPosition
    {

        if (empty($query_result)) {
            throw new \UnexpectedValueException('unknown_member_position');
        }

        $member_position = new SquadMemberPosition();
        $member_position->setPositionId($query_result['positionID']);
        $member_position->setName($query_result['name']);
        $member_position->setTag($query_result['tag']);
        $member_position->setSort($query_result['sort']);
        if (!is_null($query_result['gameID'])) {
            $member_position->setGame(
                GameHandler::getGameByGameId($query_result['gameID'])
            );
        }

        return $member_position;

    }

    public static function saveMemberPosition(SquadMemberPosition $position): SquadMemberPosition
    {

        if (is_null($position->getPositionId())) {
            $position = self::insertMemberPosition($position);
        } else {
            self::updateMemberPosition($position);
        }

        return $position;

    }

    private static function insertMemberPosition(SquadMemberPosition $position): SquadMemberPosition
    {

        $game_id = null;
        if (!is_null($position->getGame())) {
            $game_id = $position->getGame()->getGameId();
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SQUADS_MEMBER_POSITION)
            ->values(
                array(
                    'name' => '?',
                    'tag' => '?',
                    'sort' => '?',
                    'gameID' => '?'
                )
            )
            ->setParameter(0, $position->getName())
            ->setParameter(1, $position->getTag())
            ->setParameter(2, $position->getSort())
            ->setParameter(3, $game_id);

        $queryBuilder->executeQuery();

        $position_id = (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId();

        return self::getMemberPositionById($position_id);

    }

    private static function updateMemberPosition(SquadMemberPosition $position): void
    {

        $game_id = null;
        if (!is_null($position->getGame())) {
            $game_id = $position->getGame()->getGameId();
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SQUADS_MEMBER_POSITION)
            ->set('name', '?')
            ->set('tag', '?')
            ->set('sort', '?')
            ->set('gameID', '?')
            ->where('positionID = ?')
            ->setParameter(0, $position->getName())
            ->setParameter(1, $position->getTag())
            ->setParameter(2, $position->getSort())
            ->setParameter(3, $game_id)
            ->setParameter(4, $position->getPositionId());

        $queryBuilder->executeQuery();

    }

}
