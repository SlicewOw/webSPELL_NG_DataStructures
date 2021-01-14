<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Handler\UserRightHandler;


final class UserRightHandlerTest extends TestCase
{

    public function testIfFalseIsReturnedIfUserIdIsInvalid_SuperAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isSuperAdmin(-1)
        );

    }

    public function testIfFalseIsReturnedIfUserIdIsInvalid_CupAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isCupAdmin(-1)
        );

    }

    public function testIfFalseIsReturnedIfUserIdIsInvalid_ClanwarAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isClanwarAdmin(-1)
        );

    }

    public function testIfFalseIsReturnedIfUserIdIsInvalid_TvAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isTvAdmin(-1)
        );

    }

    public function testIfFalseIsReturnedIfUserIdIsInvalid_FileAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isFileAdmin(-1)
        );

    }

    public function testIfFalseIsReturnedIfUserIdIsInvalid_NewsAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isNewsAdmin(-1)
        );

    }

    public function testIfFalseIsReturnedIfUserIdIsInvalid_ForumAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isForumAdmin(-1)
        );

    }

    public function testIfFalseIsReturnedIfUserIdIsInvalid_GalleryAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isGalleryAdmin(-1)
        );

    }

    public function testIfFalseIsReturnedIfUserIdIsInvalid_DevAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isDevAdmin(-1)
        );

    }

    public function testIfFalseIsReturnedIfUserIdIsInvalid_UserAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isUserAdmin(-1)
        );

    }

    public function testIfFalseIsReturnedIfUserIdIsInvalid_PageAdmin(): void
    {

        $this->assertFalse(
            UserRightHandler::isPageAdmin(-1)
        );

    }

}
