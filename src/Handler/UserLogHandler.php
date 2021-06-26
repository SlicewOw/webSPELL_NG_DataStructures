<?php

namespace webspell_ng\Handler;

use webspell_ng\User;
use webspell_ng\UserLog;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Utils\DateUtils;


class UserLogHandler {

    private const DB_TABLE_NAME_USER_LOG = "user_log";

    /**
     * @return array<UserLog>
     */
    public static function getLogsOfUser(User $user): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_USER_LOG)
            ->where("userID = ?")
            ->setParameter(0, $user->getUserId())
            ->orderBy("date", "ASC");

        $log_query = $queryBuilder->executeQuery();

        $logs = array();
        while ($log_result = $log_query->fetchAssociative())
        {

            $log = new UserLog();
            $log->setUsername($log_result['username']);
            $log->setInfo($log_result['action']);
            $log->setParentId((int) $log_result['parent_id']);
            $log->setDate(
                DateUtils::getDateTimeByMktimeValue($log_result['date'])
            );

            array_push($logs, $log);

        }

        return $logs;

    }

    public static function saveUserLog(User $user, UserLog $log): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_USER_LOG)
            ->values(
                array(
                    'userID' => '?',
                    'username' => '?',
                    'date' => '?',
                    'parent_id' => '?',
                    'action' => '?'
                )
            )
            ->setParameter(0, $user->getUserId())
            ->setParameter(1, $user->getUsername())
            ->setParameter(2, $log->getDate()->getTimestamp())
            ->setParameter(3, $log->getParentId())
            ->setParameter(4, $log->getInfo());

        $queryBuilder->executeQuery();

    }

}
