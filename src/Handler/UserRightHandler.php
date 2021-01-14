<?php

namespace webspell_ng\Handler;

use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Utils\ValidationUtils;


class UserRightHandler {

    private const DB_TABLE_NAME_USER_GROUPS = "user_groups";

    public static function isSuperAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'super = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isCupAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'cup = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isTvAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'tv = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isFileAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'files = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isNewsAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'news = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isForumAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'forum = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isClanwarAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'clanwars = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isGalleryAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'gallery = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isDevAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'dev = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isUserAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'user = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isAnyAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;

        $rightsArray = array();
        $rightsArray[] = 'page = 1';
        $rightsArray[] = 'forum = 1';
        $rightsArray[] = 'user = 1';
        $rightsArray[] = 'news = 1';
        $rightsArray[] = 'clanwars = 1';
        $rightsArray[] = 'feedback = 1';
        $rightsArray[] = 'super = 1';
        $rightsArray[] = 'gallery = 1';
        $rightsArray[] = 'cash = 1';
        $rightsArray[] = 'files = 1';
        $rightsArray[] = 'cup = 1';
        $rightsArray[] = 'tv = 1';
        $rightsArray[] = 'dev = 1';

        $whereClauseArray[] = '(' . implode(' OR ', $rightsArray) . ')';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    public static function isPageAdmin(int $user_id): bool
    {

        if (!ValidationUtils::validateInteger($user_id, true)) {
            return false;
        }

        if (self::isSuperAdmin($user_id)) {
            return true;
        }

        $whereClauseArray = array();
        $whereClauseArray[] = 'userID =' . $user_id;
        $whereClauseArray[] = 'page = 1';

        return self::getFlagIfUserHasAccess($whereClauseArray);

    }

    /**
     * @param array<string> $where_clause_array
     */
    private static function getFlagIfUserHasAccess(array $where_clause_array): bool
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_USER_GROUPS);

        foreach ($where_clause_array as $where_clause) {
            $queryBuilder->andWhere($where_clause);
        }

        $event_query = $queryBuilder->execute();

        return !empty($event_query->fetch());

    }

}
