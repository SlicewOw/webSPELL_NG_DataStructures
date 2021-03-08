<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\History;
use webspell_ng\Handler\HistoryHandler;

final class HistoryHandlerTest extends TestCase
{

    public function testIfHistoryCanBeSavedAndUpdated(): void
    {

        $history_array = HistoryHandler::getHistory(true);

        if (!empty($history_array)) {
            $last_history = $history_array[count($history_array) - 1];
            $history_year = $last_history->getYear() + 1;
        } else {
            $history_year = 1971;
        }

        $new_history = new History();
        $new_history->setYear($history_year);
        $new_history->setText("Test Text 123");
        $new_history->setIsPublished(true);

        HistoryHandler::saveHistory($new_history);

        $history_array = HistoryHandler::getHistory(true);

        $saved_history = $history_array[count($history_array) - 1];

        $this->assertEquals($history_year, $saved_history->getYear(), "Year of history is set.");
        $this->assertEquals("Test Text 123", $saved_history->getText(), "Text of history is set.");
        $this->assertGreaterThan(new \DateTime("1 minute ago"), $saved_history->getDate(), "Date of history is set.");
        $this->assertTrue($saved_history->isPublished(), "History is not published yet.");

        $history_date = new \DateTime("5 minutes ago");

        $saved_history->setDate($history_date);
        $saved_history->setText("Test Text 321");
        $saved_history->setIsPublished(false);

        HistoryHandler::saveHistory($saved_history);

        $history_array = HistoryHandler::getHistory(true);

        $updated_history = $history_array[count($history_array) - 1];

        $this->assertEquals($history_year, $updated_history->getYear(), "Year of history is set.");
        $this->assertEquals("Test Text 321", $updated_history->getText(), "Text of history is set.");
        $this->assertEquals($history_date->getTimestamp(), $updated_history->getDate()->getTimestamp(), "Date of history is set.");
        $this->assertFalse($updated_history->isPublished(), "History is not published yet.");

    }

}
