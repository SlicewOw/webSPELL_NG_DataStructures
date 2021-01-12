<?php

namespace webspell_ng\Handler;

use webspell_ng\PageData;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Enums\PageEnums;
use webspell_ng\Utils\DateUtils;


class PageDataHandler {

    public static function getPageDataByTableAndPage(string $table, string $page): PageData
    {

        if (empty($page)) {
            throw new \InvalidArgumentException('page_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . $table)
            ->where('page = ?')
            ->setParameter(0, $page);

        $page_data_query = $queryBuilder->execute();
        $page_data_result = $page_data_query->fetch();

        if (empty($page_data_result)) {
            return self::getPageDataByTableAndPage($table, PageEnums::POLICY_DEFAULT);
        }

        $page_data = new PageData();
        $page_data->setPage($page_data_result['page']);
        $page_data->setInfo($page_data_result['description']);
        $page_data->setDate(
            DateUtils::getDateTimeByMktimeValue((int) $page_data_result['date'])
        );

        return $page_data;

    }

    /**
     * @return array<PageData>
     */
    public static function getAllPageData(string $table): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('page')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . $table)
            ->orderBy("date", "ASC");

        $page_data_query = $queryBuilder->execute();

        $page_data_array = array();

        while ($page_data_result = $page_data_query->fetch())
        {
            array_push(
                $page_data_array,
                self::getPageDataByTableAndPage($table, $page_data_result['page'])
            );
        }

        return $page_data_array;

    }

    public static function savePageData(string $table, PageData $page_data): void
    {

        if (!self::isExistingPageData($table, $page_data)) {
            self::insertPageData($table, $page_data);
        } else {
            self::updatePageData($table, $page_data);
        }

    }

    public static function insertPageData(string $table, PageData $page_data): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . $table)
            ->values(
                    [
                        'page' => '?',
                        'description' => '?',
                        'date' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $page_data->getPage(),
                        1 => $page_data->getInfo(),
                        2 => time()
                    ]
                );

        $queryBuilder->execute();

    }

    public static function updatePageData(string $table, PageData $page_data): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . $table)
            ->set("description", "?")
            ->set("date", "?")
            ->where("page = ?")
            ->setParameter(0, $page_data->getInfo())
            ->setParameter(1, time())
            ->setParameter(2, $page_data->getPage());

        $queryBuilder->execute();

    }

    private static function isExistingPageData(string $table, PageData $page_data): bool
    {

        if (empty($page_data->getPage())) {
            throw new \InvalidArgumentException("page_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . $table)
            ->where('page = ?')
            ->setParameter(0, $page_data->getPage());

        $page_data_query = $queryBuilder->execute();
        $page_data_result = $page_data_query->fetch();

        return !(empty($page_data_result));

    }

}
