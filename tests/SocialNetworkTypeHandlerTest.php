<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\SocialNetworkType;
use webspell_ng\Handler\SocialNetworkTypeHandler;
use webspell_ng\Utils\StringFormatterUtils;


final class SocialNetworkTypeHandlerTest extends TestCase
{

    public function testIfSocialNetworkTypeCanBeSavedAndUpdated(): void
    {

        $social_network_name = "Test Network " . StringFormatterUtils::getRandomString(10);
        $social_network_icon_prefix = strtolower($social_network_name);

        $new_social_network = new SocialNetworkType();
        $new_social_network->setName($social_network_name);
        $new_social_network->setIconPrefix($social_network_icon_prefix);
        $new_social_network->setIsHomepage(true);

        $saved_social_network = SocialNetworkTypeHandler::saveSocialNetworkType($new_social_network);

        $this->assertGreaterThan(0, $saved_social_network->getSocialNetworkId(), "Type ID is set.");
        $this->assertEquals($social_network_name, $saved_social_network->getName(), "Social Network name is set.");
        $this->assertEquals($social_network_icon_prefix, $saved_social_network->getIconPrefix(), "Social Network icon prefix is set.");
        $this->assertTrue($saved_social_network->isHomepage(), "Social Network is using a homepage.");
        $this->assertNull($saved_social_network->getPlaceholderPlayer(), "No placeholder for 'player'.");
        $this->assertNull($saved_social_network->getPlaceholderTeam(), "No placeholder for 'team'.");

        $changed_social_network_name = "Test Network " . StringFormatterUtils::getRandomString(10);

        $changed_social_network = $saved_social_network;
        $changed_social_network->setName($changed_social_network_name);
        $changed_social_network->setIsHomepage(false);
        $changed_social_network->setPlaceholderPlayer("https://random.org/player/");
        $changed_social_network->setPlaceholderTeam("https://random.org/team/");

        $updated_social_network = SocialNetworkTypeHandler::saveSocialNetworkType($changed_social_network);

        $this->assertEquals($saved_social_network->getSocialNetworkId(), $updated_social_network->getSocialNetworkId(), "Type ID is set.");
        $this->assertEquals($changed_social_network_name, $updated_social_network->getName(), "Social Network name is set.");
        $this->assertEquals($social_network_icon_prefix, $updated_social_network->getIconPrefix(), "Social Network icon prefix is set.");
        $this->assertFalse($updated_social_network->isHomepage(), "Social Network is using a homepage.");
        $this->assertEquals("https://random.org/player/", $updated_social_network->getPlaceholderPlayer(), "Placeholder for 'player' is set.");
        $this->assertEquals("https://random.org/team/", $updated_social_network->getPlaceholderTeam(), "Placeholder for 'team' is set.");

    }

    public function testIfSocialNetworkIsReturnedByName(): void
    {

        $social_network = SocialNetworkTypeHandler::getSocialNetworkTypeByName("Twitter");

        $this->assertGreaterThan(0, $social_network->getSocialNetworkId(), "Type ID is set.");
        $this->assertEquals("Twitter", $social_network->getName(), "Social Network name is set.");
        $this->assertEquals("twitter", $social_network->getIconPrefix(), "Social Network icon prefix is set.");
        $this->assertTrue($social_network->isHomepage(), "Social Network is using a homepage.");
        $this->assertNotNull($social_network->getPlaceholderPlayer(), "No placeholder for 'player'.");
        $this->assertNull($social_network->getPlaceholderTeam(), "No placeholder for 'team'.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfSocialNetworkIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        SocialNetworkTypeHandler::getSocialNetworkById(-1);

    }

    public function testIfUnexpectedValueExceptionIsThrownIfSocialNetworkDoesNotExistById(): void
    {

        $this->expectException(UnexpectedValueException::class);

        SocialNetworkTypeHandler::getSocialNetworkById(99999999);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfSocialNetworkNameIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        SocialNetworkTypeHandler::getSocialNetworkTypeByName("");

    }

    public function testIfUnexpectedValueExceptionIsThrownIfSocialNetworkDoesNotExistByName(): void
    {

        $this->expectException(UnexpectedValueException::class);

        SocialNetworkTypeHandler::getSocialNetworkTypeByName("random123");

    }

}
