<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\UserSession;
use webspell_ng\UserSettings;

final class UserSessionTest extends TestCase
{

    public function testIfUserSessionCannotBeSetWithInvalidInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        UserSession::setUserSession(-1);
    }

    public function testIfUserSessionIsSetCorrectly(): void
    {
        UserSession::setUserSession(1);
        $this->assertEquals(1, UserSession::getUserId());
    }

    public function testIfUserSessionReturnsNegativeOneIfUnset(): void
    {
        unset($_SESSION['user_id']);
        $this->assertEquals(-1, UserSession::getUserId());
    }

    public function testIfFalseIsReturnedIfUserSessionIdIsNotSet(): void
    {

        UserSession::clearUserSession();

        $this->assertFalse(UserSession::isAnyAdmin());

    }

    public function testIfFalseIsReturnedIfUserIsNotAnAdmin(): void
    {

        UserSession::setUserSession(100000);

        $this->assertEquals(100000, UserSession::getUserId());

        $this->assertFalse(UserSession::isAnyAdmin());
        $this->assertFalse(UserSession::isPageAdmin());
        $this->assertFalse(UserSession::isClanwarAdmin());
        $this->assertFalse(UserSession::isCupAdmin());
        $this->assertFalse(UserSession::isUserAdmin());
        $this->assertFalse(UserSession::isDevAdmin());
        $this->assertFalse(UserSession::isFileAdmin());
        $this->assertFalse(UserSession::isForumAdmin());
        $this->assertFalse(UserSession::isNewsAdmin());
        $this->assertFalse(UserSession::isTvAdmin());
        $this->assertFalse(UserSession::isGalleryAdmin());
        $this->assertFalse(UserSession::isSuperAdmin());

    }

    public function testIfTrueIsReturnedIfUserIsAnAdmin(): void
    {

        UserSession::setUserSession(1);

        $this->assertEquals(1, UserSession::getUserId());

        $this->assertTrue(UserSession::isAnyAdmin());
        $this->assertTrue(UserSession::isPageAdmin());
        $this->assertTrue(UserSession::isClanwarAdmin());
        $this->assertTrue(UserSession::isCupAdmin());
        $this->assertTrue(UserSession::isUserAdmin());
        $this->assertTrue(UserSession::isDevAdmin());
        $this->assertTrue(UserSession::isFileAdmin());
        $this->assertTrue(UserSession::isForumAdmin());
        $this->assertTrue(UserSession::isNewsAdmin());
        $this->assertTrue(UserSession::isTvAdmin());
        $this->assertTrue(UserSession::isGalleryAdmin());
        $this->assertTrue(UserSession::isSuperAdmin());

        $this->assertNotEmpty(UserSettings::getDateFormat(), "Date format is returned.");
        $this->assertNotEmpty(UserSettings::getTimeFormat(), "Time format is returned.");

    }

}