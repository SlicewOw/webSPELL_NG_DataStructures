<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Sponsor;
use webspell_ng\Handler\SponsorSocialNetworkHandler;

final class SponsorSocialNetworkHandlerTest extends TestCase
{

    public function testIfUnexpectedValueExceptionIsThrownIfSponsorIdIsNotSet(): void
    {

        $this->expectException(UnexpectedValueException::class);

        SponsorSocialNetworkHandler::saveSocialNetworksOfSponsor(new Sponsor());

    }

}
