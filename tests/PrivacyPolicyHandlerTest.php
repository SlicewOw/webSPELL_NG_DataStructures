<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\PrivacyPolicy;
use webspell_ng\Enums\PolicyEnums;
use webspell_ng\Handler\PrivacyPolicyHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class PrivacyPolicyHandlerTest extends TestCase
{

    public function testIfDefaultPolicyIsExisting(): void
    {

        $policy = PrivacyPolicyHandler::getPrivacyPolicyByPage(PolicyEnums::POLICY_DEFAULT);

        $this->assertEquals(PolicyEnums::POLICY_DEFAULT, $policy->getPage(), "Page is set.");
        $this->assertGreaterThan(0, $policy->getDate()->getTimestamp(), "Date is set");
        $this->assertNotEmpty($policy->getInfo(), "Info is set.");

    }

    public function testIfPolicyCanBeSavedAndUpdated(): void
    {

        $page = "Test Page " . StringFormatterUtils::getRandomString(10);
        $info = StringFormatterUtils::getRandomString(10);
        $old_date = new \DateTime("1 minute ago");

        $policy = new PrivacyPolicy();
        $policy->setPage($page);
        $policy->setInfo($info);

        PrivacyPolicyHandler::savePolicy($policy);

        $saved_policy = PrivacyPolicyHandler::getPrivacyPolicyByPage($page);

        $this->assertEquals($page, $saved_policy->getPage(), "Page is set.");
        $this->assertGreaterThan($old_date, $saved_policy->getDate(), "Date is set automatically");
        $this->assertEquals($info, $saved_policy->getInfo(), "Info is set.");

        $changed_info = StringFormatterUtils::getRandomString(10);

        $saved_policy->setInfo($changed_info);

        PrivacyPolicyHandler::savePolicy($saved_policy);

        $saved_policy = PrivacyPolicyHandler::getPrivacyPolicyByPage($page);

        $this->assertEquals($page, $saved_policy->getPage(), "Page is set.");
        $this->assertGreaterThan($old_date, $saved_policy->getDate(), "Date is set automatically");
        $this->assertEquals($changed_info, $saved_policy->getInfo(), "Info is set.");

    }

    public function testIfDefaultIsReturnedIfPageIsNotSavedYet(): void
    {

        $policy = PrivacyPolicyHandler::getPrivacyPolicyByPage(
            StringFormatterUtils::getRandomString(10)
        );

        $this->assertEquals(PolicyEnums::POLICY_DEFAULT, $policy->getPage(), "Page is set.");
        $this->assertGreaterThan(0, $policy->getDate()->getTimestamp(), "Date is set");
        $this->assertNotEmpty($policy->getInfo(), "Info is set.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfPageValueIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        PrivacyPolicyHandler::getPrivacyPolicyByPage("");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfPageIsNotSetYet(): void
    {

        $this->expectException(InvalidArgumentException::class);

        $policy = new PrivacyPolicy();
        $policy->setPage("");

        PrivacyPolicyHandler::savePolicy($policy);

    }

}
