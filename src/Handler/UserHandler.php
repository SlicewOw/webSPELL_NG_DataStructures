<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\WebSpellDatabaseConnection;

use webspell_ng\User;

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

        $user = new User();
        $user->setUserId($user_result['userID']);
        $user->setUsername($user_result['username']);
        $user->setFirstname($user_result['firstname']);
        $user->setLastname($user_result['lastname']);

        return $user;

    }

}
