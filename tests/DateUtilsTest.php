<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Utils\DateUtils;

final class DateUtilsTest extends TestCase
{

    public function testIfDateTimeConverterIsWorking(): void
    {

        $expected_datetime = new \DateTime("2018-04-14 13:37:10");
        $mktime_value = mktime(13, 37, 10, 4, 14, 2018);

        $this->assertEquals($expected_datetime, DateUtils::getDateTimeByMktimeValue($mktime_value));

    }

    public function testIfDaysSinceTimestampIsWorking(): void
    {

        $date = new \DateTime("5 days ago");

        $this->assertEquals(5, DateUtils::daysSinceTimestamp($date->getTimestamp()), "Days since timestamp is working.");

    }

    public function testIfDaysSinceTimestampReturnsInvalidInteger(): void
    {

        $this->assertEquals(-1, DateUtils::daysSinceTimestamp(0), "Invalid date is found.");

    }

    public function testIfDefaultDateFormatIsUsed(): void
    {

        $date_time = new \DateTime("2021-02-28 12:34:56");

        $formatted_date = DateUtils::getFormattedDate($date_time);

        $this->assertEquals("2021-02-28", $formatted_date, "Default date is used!");

    }

    public function testIfDefaultTimeFormatIsUsed(): void
    {

        $date_time = new \DateTime("2021-02-28 12:34:56");

        $formatted_time = DateUtils::getFormattedTime($date_time);

        $this->assertEquals("12:34:56", $formatted_time, "Default time is used!");

    }

    public function getFormattedDateTime(): void
    {

        $date_time = new \DateTime("2021-02-28 12:34:56");

        $formatted_datetime = DateUtils::getFormattedDateTime($date_time);

        $this->assertEquals("2021-02-28 12:34:56", $formatted_datetime, "Default datetime is used!");

    }

}
