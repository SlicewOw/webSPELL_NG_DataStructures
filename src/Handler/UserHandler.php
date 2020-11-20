<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\User;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Utils\DateUtils;


class UserHandler {

    public static function getUserByUserId(int $user_id): User
    {

        if (!Validator::numericVal()->min(1)->validate($user_id)) {
            throw new \InvalidArgumentException('user_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . 'user')
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
        $user->setCountry($user_result['country']);
        $user->setTown($user_result['town']);
        $user->setBirthday(
            DateUtils::getDateTimeByMktimeValue(
                strtotime($user_result['birthday'])
            )
        );

        return $user;

    }

}
