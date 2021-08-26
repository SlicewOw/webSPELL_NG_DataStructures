<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Award;


final class AwardTest extends TestCase
{

    public function testIfUnexpectedValueExceptionIsThrownIfNameValueIsInvalid(): void
    {

        $this->expectException(UnexpectedValueException::class);

        $award = new Award();
        $award->setName("");

    }

    public function testIfUnexpectedValueExceptionIsThrownIfHomepageValueIsInvalid(): void
    {

        $this->expectException(UnexpectedValueException::class);

        $award = new Award();
        $award->setHomepage("this_is_not_an_link");

    }

    public function testIfUnexpectedValueExceptionIsThrownIfRankValueIsInvalid(): void
    {

        $this->expectException(UnexpectedValueException::class);

        $award = new Award();
        $award->setRank(-1);

    }

    public function testIfUnexpectedValueExceptionIsThrownIfLeagueCategoryValueIsInvalid(): void
    {

        $this->expectException(UnexpectedValueException::class);

        $award = new Award();
        $award->setLeagueCategory("");

    }

    public function testIfEmptyInfoIsSetIfStringIsEmpty(): void
    {

        $award = new Award();
        $award->setDescription("");

        $this->assertEmpty($award->getDescription(), "Description is empty.");

    }

    public function testIfHitsValueIsSet(): void
    {

        $award = new Award();
        $award->setHits(1337);

        $this->assertEquals(1337, $award->getHits(), "Hits are set.");

    }

}
