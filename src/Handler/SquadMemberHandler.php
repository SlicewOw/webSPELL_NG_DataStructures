<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\Squad;
use webspell_ng\SquadMember;
use webspell_ng\UserLog;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Handler\UserLogHandler;
use webspell_ng\Utils\DateUtils;


class SquadMemberHandler {

    private const DB_TABLE_NAME_SQUADS_MEMBERS = "squads_members";

    /**
     * @return array<SquadMember>
     */
    public static function getMembersOfSquad(int $squad_id): array
    {

        $members = array();

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('sqmID')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SQUADS_MEMBERS)
            ->where(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('squadID', $squad_id),
                    $queryBuilder->expr()->eq('active', 1)
                )
            )
            ->setParameter(0, $squad_id);

        $member_query = $queryBuilder->execute();
        while ($member_result = $member_query->fetch())
        {
            array_push($members, self::getSquadMemberById($member_result['sqmID']));
        }

        return $members;

    }

    public static function getSquadMemberById(int $member_id): SquadMember
    {

        if (!Validator::numericVal()->min(1)->validate($member_id)) {
            throw new \InvalidArgumentException("member_id_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SQUADS_MEMBERS)
            ->where('sqmID = ?')
            ->setParameter(0, $member_id);

        $member_query = $queryBuilder->execute();
        $member_result = $member_query->fetch();

        if (empty($member_result)) {
            throw new \UnexpectedValueException('unknown_member');
        }

        $member = new SquadMember();
        $member->setMemberId($member_result['sqmID']);
        $member->setIsActive($member_result['active']);
        $member->setSort($member_result['sort']);
        $member->setUser(
            UserHandler::getUserByUserId($member_result['userID'])
        );
        $member->setMemberPosition(
            SquadMemberPositionHandler::getMemberPositionById($member_result['positionID'])
        );
        $member->setJoinDate(
            DateUtils::getDateTimeByMktimeValue($member_result['join_date'])
        );

        if (!is_null($member_result['left_date'])) {
            $member->setLeftDate(
                DateUtils::getDateTimeByMktimeValue($member_result['left_date'])
            );
        }

        return $member;

    }

    public static function saveSquadMember(Squad $squad, SquadMember $member): SquadMember
    {

        if (is_null($member->getMemberId())) {
            $member = self::insertSquadMember($squad, $member);
        } else {
            self::updateSquadMember($squad, $member);
        }

        return $member;

    }

    private static function insertSquadMember(Squad $squad, SquadMember $member): SquadMember
    {

        $is_active = $member->isActive() ? 1 : 0;

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SQUADS_MEMBERS)
            ->values(
                array(
                    'userID' => '?',
                    'squadID' => '?',
                    'positionID' => '?',
                    'join_date' => '?',
                    'active' => '?',
                    'sort' => '?'
                )
            )
            ->setParameter(0, $member->getUser()->getUserId())
            ->setParameter(1, $squad->getSquadId())
            ->setParameter(2, $member->getMemberPosition()->getPositionId())
            ->setParameter(3, $member->getJoinDate()->getTimestamp())
            ->setParameter(4, $is_active)
            ->setParameter(5, $member->getSort());

        $queryBuilder->execute();

        $member->setMemberId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        self::saveUserLogNewSquadMember($squad, $member);

        return self::getSquadMemberById($member->getMemberId());

    }

    private static function updateSquadMember(Squad $squad, SquadMember $member): void
    {

        $is_active = $member->isActive() ? 1 : 0;
        $left_date = (!is_null($member->getLeftDate())) ? $member->getLeftDate()->getTimestamp() : null;

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SQUADS_MEMBERS)
            ->set('userID', '?')
            ->set('squadID', '?')
            ->set('positionID', '?')
            ->set('active', '?')
            ->set('left_date', '?')
            ->set('sort', '?')
            ->where('sqmID = ?')
            ->setParameter(0, $member->getUser()->getUserId())
            ->setParameter(1, $squad->getSquadId())
            ->setParameter(2, $member->getMemberPosition()->getPositionId())
            ->setParameter(3, $is_active)
            ->setParameter(4, $left_date)
            ->setParameter(5, $member->getSort())
            ->setParameter(6, $member->getMemberId());

        $queryBuilder->execute();

    }

    public static function kickSquadMember(Squad $squad, SquadMember $member): void
    {

        $member->setIsActive(false);
        $member->setLeftDate(
            new \DateTime("now")
        );

        self::saveSquadMember($squad, $member);

        self::saveUserLogLeftSquadMember($squad, $member);

    }

    private static function saveUserLogNewSquadMember(Squad $squad, SquadMember $member): void
    {
        UserLogHandler::saveUserLog(
            $member->getUser(),
            self::getSquadMemberUserLog($squad, "squad_joined")
        );
    }

    private static function saveUserLogLeftSquadMember(Squad $squad, SquadMember $member): void
    {
        UserLogHandler::saveUserLog(
            $member->getUser(),
            self::getSquadMemberUserLog($squad, "squad_left")
        );
    }

    private static function getSquadMemberUserLog(Squad $squad, string $info): UserLog
    {
        $log = new UserLog();
        $log->setInfo($info);
        $log->setParentId($squad->getSquadId());
        return $log;
    }

}
