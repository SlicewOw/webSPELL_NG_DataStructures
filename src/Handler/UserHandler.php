<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\User;
use webspell_ng\UserLog;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Handler\CountryHandler;
use webspell_ng\Handler\UserLogHandler;
use webspell_ng\Utils\StringFormatterUtils;
use webspell_ng\Utils\ValidationUtils;


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

        $user_query = $queryBuilder->executeQuery();
        $user_result = $user_query->fetchAssociative();

        if (empty($user_result)) {
            throw new \InvalidArgumentException('unknown_user');
        }

        $user = new User();
        $user->setUserId((int) $user_result['userID']);
        $user->setUsername($user_result['username']);
        $user->setFirstname($user_result['firstname']);
        $user->setLastname($user_result['lastname']);
        $user->setEmail($user_result['email']);
        $user->setPassword($user_result['password']);
        $user->setTown($user_result['town']);
        $user->setActivationKey($user_result['activated']);
        $user->setCountry(
            CountryHandler::getCountryByCountryShortcut($user_result['country'])
        );
        $user->setBirthday(
            new \DateTime($user_result['birthday'])
        );
        $user->setRegistrationDate(
            new \DateTime($user_result['registerdate'])
        );
        if (!is_null($user_result['firstlogin'])) {
            $user->setFirstLoginDate(
                new \DateTime($user_result['firstlogin'])
            );
        }
        if (!is_null($user_result['lastlogin'])) {
            $user->setLastLoginDate(
                new \DateTime($user_result['lastlogin'])
            );
        }

        return $user;

    }

    public static function getDataOfUserByUserId(int $user_id, string $column_name): ?string
    {

        if (!Validator::numericVal()->min(1)->validate($user_id)) {
            return null;
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_USER)
            ->where('userID = ?')
            ->setParameter(0, $user_id);

        $user_query = $queryBuilder->executeQuery();
        $user_result = $user_query->fetchAssociative();

        if (empty($user_result)) {
            throw new \InvalidArgumentException('unknown_user');
        }

        return (isset($user_result[$column_name])) ? $user_result[$column_name] : null;

    }

    public static function saveUser(User $user): User
    {

        if (is_null($user->getEmail()) || !ValidationUtils::validateEmail($user->getEmail())) {
            throw new \InvalidArgumentException('email_value_is_invalid');
        }

        if (is_null($user->getUserId())) {
            $user = self::insertUser($user);
        } else {
            self::updateUser($user);
        }

        if (is_null($user->getUserId())) {
            throw new \UnexpectedValueException("user_id_is_not_set");
        }

        return self::getUserByUserId($user->getUserId());

    }

    public static function loginUser(User $user): User
    {

        if (is_null($user->getFirstLoginDate())) {
            $user->setFirstLoginDate(
                new \DateTime("now")
            );
        }
        $user->setLastLoginDate(
            new \DateTime("now")
        );

        return self::saveUser($user);

    }

    private static function insertUser(User $user): User
    {

        if (is_null($user->getPassword())) {
            throw new \InvalidArgumentException("password_of_user_is_not_set");
        }

        $first_login_date = !is_null($user->getFirstLoginDate()) ? $user->getFirstLoginDate()->format("Y-m-d H:i:s") : null;
        $last_login_date = !is_null($user->getLastLoginDate()) ? $user->getLastLoginDate()->format("Y-m-d H:i:s") : null;

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_USER)
            ->values(
                array(
                    'username' => '?',
                    'email' => '?',
                    'password' => '?',
                    'firstname' => '?',
                    'lastname' => '?',
                    'sex' => '?',
                    'birthday' => '?',
                    'country' => '?',
                    'town' => '?',
                    'registerdate' => '?',
                    'firstlogin' => '?',
                    'lastlogin' => '?',
                    'activated' => '?'
                )
            )
            ->setParameter(0, $user->getUsername())
            ->setParameter(1, $user->getEmail())
            ->setParameter(2, $user->getPassword())
            ->setParameter(3, $user->getFirstname())
            ->setParameter(4, $user->getLastname())
            ->setParameter(5, $user->getSex())
            ->setParameter(6, $user->getBirthday()->format("Y-m-d H:i:s"))
            ->setParameter(7, $user->getCountry()->getShortcut())
            ->setParameter(8, $user->getTown())
            ->setParameter(9, $user->getRegistrationDate()->format("Y-m-d H:i:s"))
            ->setParameter(10, $first_login_date)
            ->setParameter(11, $last_login_date)
            ->setParameter(12, StringFormatterUtils::getRandomString(20));

        $queryBuilder->executeQuery();

        $user->setUserId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        self::saveUserLogNewUser($user);

        return $user;

    }

    private static function updateUser(User $user): void
    {

        if (is_null($user->getPassword())) {
            throw new \InvalidArgumentException("password_of_user_is_not_set");
        }

        $first_login_date = !is_null($user->getFirstLoginDate()) ? $user->getFirstLoginDate()->format("Y-m-d H:i:s") : null;
        $last_login_date = !is_null($user->getLastLoginDate()) ? $user->getLastLoginDate()->format("Y-m-d H:i:s") : null;

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_USER)
            ->set("username", "?")
            ->set("email", "?")
            ->set("password", "?")
            ->set("firstname", "?")
            ->set("lastname", "?")
            ->set("sex", "?")
            ->set("birthday", "?")
            ->set("country", "?")
            ->set("town", "?")
            ->set("firstlogin", "?")
            ->set("lastlogin", "?")
            ->set("activated", "?")
            ->where("userID = ?")
            ->setParameter(0, $user->getUsername())
            ->setParameter(1, $user->getEmail())
            ->setParameter(2, $user->getPassword())
            ->setParameter(3, $user->getFirstname())
            ->setParameter(4, $user->getLastname())
            ->setParameter(5, $user->getSex())
            ->setParameter(6, $user->getBirthday()->format("Y-m-d H:i:s"))
            ->setParameter(7, $user->getCountry()->getShortcut())
            ->setParameter(8, $user->getTown())
            ->setParameter(9, $first_login_date)
            ->setParameter(10, $last_login_date)
            ->setParameter(11, $user->getActivationKey())
            ->setParameter(12, $user->getUserId());

        $queryBuilder->executeQuery();

    }

    public static function activateUser(string $activation_key): bool
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_USER)
            ->where('activated = ?')
            ->setParameter(0, $activation_key);

        $user_query = $queryBuilder->executeQuery();
        $user_result = $user_query->fetchAssociative();

        if (empty($user_result)) {
            return false;
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_USER)
            ->set("activated", "?")
            ->where("activated = ?")
            ->setParameter(0, '1')
            ->setParameter(1, $activation_key);

        $queryBuilder->executeQuery();

        return true;

    }

    private static function saveUserLogNewUser(User $user): void
    {

        if (!is_null($user->getUserId())) {

            $log = new UserLog();
            $log->setInfo("user_registered");
            $log->setParentId($user->getUserId());

            UserLogHandler::saveUserLog(
                $user,
                $log
            );

        }

    }

}
