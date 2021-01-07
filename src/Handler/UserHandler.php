<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\User;
use webspell_ng\UserLog;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Handler\CountryHandler;
use webspell_ng\Handler\UserLogHandler;
use webspell_ng\Utils\DateUtils;


class UserHandler {

    private const DB_TABLE_USER = "user";

    public static function getUserByUserId(int $user_id): User
    {

        if (!Validator::numericVal()->min(1)->validate($user_id)) {
            throw new \InvalidArgumentException('user_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_USER)
            ->where('userID = ?')
            ->setParameter(0, $user_id);

        $user_query = $queryBuilder->execute();
        $user_result = $user_query->fetch();

        if (empty($user_result)) {
            throw new \InvalidArgumentException('unknown_user');
        }

        $user = new User();
        $user->setUserId($user_result['userID']);
        $user->setUsername($user_result['username']);
        $user->setFirstname($user_result['firstname']);
        $user->setLastname($user_result['lastname']);
        $user->setEmail($user_result['email']);
        $user->setTown($user_result['town']);
        $user->setCountry(
            CountryHandler::getCountryByCountryShortcut($user_result['country'])
        );
        $user->setBirthday(
            DateUtils::getDateTimeByMktimeValue(
                strtotime($user_result['birthday'])
            )
        );

        return $user;

    }

    public static function saveUser(User $user): User
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_USER)
            ->values(
                array(
                    'username' => '?',
                    'email' => '?',
                    'firstname' => '?',
                    'lastname' => '?',
                    'sex' => '?',
                    'birthday' => '?',
                    'country' => '?',
                    'town' => '?',
                    'registerdate' => '?'
                )
            )
            ->setParameter(0, $user->getUsername())
            ->setParameter(1, $user->getEmail())
            ->setParameter(2, $user->getFirstname())
            ->setParameter(3, $user->getLastname())
            ->setParameter(4, $user->getSex())
            ->setParameter(5, $user->getBirthday()->format("Y-m-d H:i:s"))
            ->setParameter(6, $user->getCountry()->getShortcut())
            ->setParameter(7, $user->getTown())
            ->setParameter(8, time());

        $queryBuilder->execute();

        $user->setUserId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        self::saveUserLogNewUser($user);

        return $user;

    }

    private static function saveUserLogNewUser(User $user): void
    {

        $log = new UserLog();
        $log->setInfo("user_registered");
        $log->setParentId($user->getUserId());

        UserLogHandler::saveUserLog(
            $user,
            $log
        );

    }

}
