<?php

namespace webspell_ng;

use Respect\Validation\Validator;

use webspell_ng\Handler\UserRightHandler;


class UserSession {

    private const SESSION_KEY_USER_ID = "user_id";
    private const SESSION_KEY_LOGGEDIN = "loggedin";

    public static function getUserId(): int
    {
        if (isset($_SESSION[self::SESSION_KEY_USER_ID])) {
            return (int)$_SESSION[self::SESSION_KEY_USER_ID];
        } else {
            return -1;
        }
    }

    public static function setUserSession(int $user_id): void
    {
        UserSession::setUserId($user_id);
        $_SESSION[self::SESSION_KEY_LOGGEDIN] = true;
    }

    private static function setUserId(int $user_id): void
    {
        if (!Validator::numericVal()->min(1)->validate($user_id)) {
            throw new \InvalidArgumentException('session_user_id_must_be_greater_than_zero');
        }
        $_SESSION[self::SESSION_KEY_USER_ID] = $user_id;
    }

    public static function isAnyAdmin(): bool
    {
        return UserRightHandler::isAnyAdmin(
            UserSession::getUserId()
        );
    }

    public static function isPageAdmin(): bool
    {
        return UserRightHandler::isPageAdmin(
            UserSession::getUserId()
        );
    }

    public static function isClanwarAdmin(): bool
    {
        return UserRightHandler::isClanwarAdmin(
            UserSession::getUserId()
        );
    }

    public static function isNewsAdmin(): bool
    {
        return UserRightHandler::isNewsAdmin(
            UserSession::getUserId()
        );
    }

    public static function isForumAdmin(): bool
    {
        return UserRightHandler::isForumAdmin(
            UserSession::getUserId()
        );
    }

    public static function isUserAdmin(): bool
    {
        return UserRightHandler::isUserAdmin(
            UserSession::getUserId()
        );
    }

    public static function isCupAdmin(): bool
    {
        return UserRightHandler::isCupAdmin(
            UserSession::getUserId()
        );
    }

    public static function isTvAdmin(): bool
    {
        return UserRightHandler::isTvAdmin(
            UserSession::getUserId()
        );
    }

    public static function isFileAdmin(): bool
    {
        return UserRightHandler::isFileAdmin(
            UserSession::getUserId()
        );
    }

    public static function isGalleryAdmin(): bool
    {
        return UserRightHandler::isGalleryAdmin(
            UserSession::getUserId()
        );
    }

    public static function isDevAdmin(): bool
    {
        return UserRightHandler::isDevAdmin(
            UserSession::getUserId()
        );
    }

    public static function isSuperAdmin(): bool
    {
        return UserRightHandler::isSuperAdmin(
            UserSession::getUserId()
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public static function clearUserSession(): void
    {

        $sessionKeyArray = array(
            self::SESSION_KEY_LOGGEDIN,
            self::SESSION_KEY_USER_ID
        );

        foreach ($sessionKeyArray as $session_key) {

            if (!isset($_SESSION[$session_key])) {
                continue;
            }

            unset($_SESSION[$session_key]);

        }

    }

}


