<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\PrivacyPolicy;

final class PrivacyPolicyTest extends TestCase
{

    public function testIfDefaultDateIsReturned(): void
    {

        $policy = new PrivacyPolicy();

        $this->assertGreaterThan(new \DateTime("1 minute ago"), $policy->getDate());

    }

}
