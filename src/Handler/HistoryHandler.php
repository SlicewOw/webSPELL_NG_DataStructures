<?php

namespace webspell_ng\Handler;

use webspell_ng\History;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Utils\DateUtils;


class HistoryHandler {

    private const DB_TABLE_NAME_HISTORY = "history";

    /**
     * @return array<History>
     */
    public static function getHistory(bool $sort_by_ascending_years = true): array
    {

        $sort_order = ($sort_by_ascending_years) ? 'ASC' : 'DESC';

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_HISTORY)
            ->orderBy('year', $sort_order);

        $history_query = $queryBuilder->execute();

        $history_array = array();

        while ($history_result = $history_query->fetch())
        {

            $history = new History();
            $history->setYear((int) $history_result["year"]);
            $history->setText($history_result["history"]);
            $history->setDate(
                DateUtils::getDateTimeByMktimeValue((int) $history_result["date"])
            );
            $history->setIsPublished(
                ((int) $history_result["public"] == 1)
            );

            array_push(
                $history_array,
                $history
            );

        }

        return $history_array;

    }

    public static function isExistingHistory(History $history): bool
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_HISTORY)
            ->where('year = ?')
            ->setParameter(0, $history->getYear());

        $history_query = $queryBuilder->execute();
        $history_result = $history_query->fetch();

        return !empty($history_result);

    }

    public static function saveHistory(History $history): void
    {

        if (is_null($history->getYear()) || ($history->getYear() < 1970)) {
            throw new \InvalidArgumentException("year_of_history_is_invalid");
        }

        if (!self::isExistingHistory($history)) {
            self::insertHistory($history);
        } else {
            self::updateHistory($history);
        }

    }

    private static function insertHistory(History $history): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_HISTORY)
            ->values(
                    [
                        'year' => '?',
                        'history' => '?',
                        'date' => '?',
                        'public' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $history->getYear(),
                        1 => $history->getText(),
                        2 => $history->getDate()->getTimestamp(),
                        3 => ($history->isPublished()) ? 1 : 0
                    ]
                );

        $queryBuilder->execute();

    }

    private static function updateHistory(History $history): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_HISTORY)
            ->set('history', '?')
            ->set('date', '?')
            ->set('public', '?')
            ->where('year = ?')
            ->setParameter(0, $history->getText())
            ->setParameter(1, $history->getDate()->getTimestamp())
            ->setParameter(2, ($history->isPublished()) ? 1 : 0)
            ->setParameter(3, $history->getYear());

        $queryBuilder->execute();

    }

}
