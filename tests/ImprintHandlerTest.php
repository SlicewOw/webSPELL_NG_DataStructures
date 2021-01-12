<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Imprint;
use webspell_ng\Enums\PageEnums;
use webspell_ng\Handler\ImprintHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class ImprintHandlerTest extends TestCase
{

    public function testIfDefaultPolicyIsExisting(): void
    {

        $policy = ImprintHandler::getImprintByPage(PageEnums::POLICY_DEFAULT);

        $this->assertEquals(PageEnums::POLICY_DEFAULT, $policy->getPage(), "Page is set.");
        $this->assertGreaterThan(0, $policy->getDate()->getTimestamp(), "Date is set");
        $this->assertNotEmpty($policy->getInfo(), "Info is set.");

    }

    public function testIfPolicyCanBeSavedAndUpdated(): void
    {

        $page = "Test Page " . StringFormatterUtils::getRandomString(10);
        $info = StringFormatterUtils::getRandomString(10);
        $old_date = new \DateTime("1 minute ago");

        $policy = new Imprint();
        $policy->setPage($page);
        $policy->setInfo($info);

        ImprintHandler::saveImprint($policy);

        $saved_policy = ImprintHandler::getImprintByPage($page);

        $this->assertEquals($page, $saved_policy->getPage(), "Page is set.");
        $this->assertGreaterThan($old_date, $saved_policy->getDate(), "Date is set automatically");
        $this->assertEquals($info, $saved_policy->getInfo(), "Info is set.");

        $changed_info = StringFormatterUtils::getRandomString(10);

        $saved_policy->setInfo($changed_info);

        ImprintHandler::saveImprint($saved_policy);

        $saved_policy = ImprintHandler::getImprintByPage($page);

        $this->assertEquals($page, $saved_policy->getPage(), "Page is set.");
        $this->assertGreaterThan($old_date, $saved_policy->getDate(), "Date is set automatically");
        $this->assertEquals($changed_info, $saved_policy->getInfo(), "Info is set.");

    }

    public function testIfDefaultIsReturnedIfPageIsNotSavedYet(): void
    {

        $policy = ImprintHandler::getImprintByPage(
            StringFormatterUtils::getRandomString(10)
        );

        $this->assertEquals(PageEnums::POLICY_DEFAULT, $policy->getPage(), "Page is set.");
        $this->assertGreaterThan(0, $policy->getDate()->getTimestamp(), "Date is set");
        $this->assertNotEmpty($policy->getInfo(), "Info is set.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfPageValueIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        ImprintHandler::getImprintByPage("");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfPageIsNotSetYet(): void
    {

        $this->expectException(InvalidArgumentException::class);

        $imprint = new Imprint();
        $imprint->setPage("");

        ImprintHandler::saveImprint($imprint);

    }

}
