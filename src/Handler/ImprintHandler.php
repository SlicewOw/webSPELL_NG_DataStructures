<?php

namespace webspell_ng\Handler;

use webspell_ng\Imprint;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Enums\PageEnums;
use webspell_ng\Utils\DateUtils;


class ImprintHandler {

    private const DB_TABLE_NAME_IMPRINT = "imprint";

    public static function getImprintByPage(string $page): Imprint
    {

        if (empty($page)) {
            throw new \InvalidArgumentException('page_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_IMPRINT)
            ->where('page = ?')
            ->setParameter(0, $page);

        $imprint_query = $queryBuilder->execute();
        $imprint_result = $imprint_query->fetch();

        if (empty($imprint_result)) {
            return self::getImprintByPage(PageEnums::POLICY_DEFAULT);
        }

        $policy = new Imprint();
        $policy->setPage($imprint_result['page']);
        $policy->setInfo($imprint_result['description']);
        $policy->setDate(
            DateUtils::getDateTimeByMktimeValue((int) $imprint_result['date'])
        );

        return $policy;

    }

    /**
     * @return array<Imprint>
     */
    public static function getAllImprints(): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('page')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_IMPRINT)
            ->orderBy("date", "ASC");

        $imprint_query = $queryBuilder->execute();

        $imprints = array();

        while ($imprint_result = $imprint_query->fetch())
        {
            array_push(
                $imprints,
                self::getImprintByPage($imprint_result['page'])
            );
        }

        return $imprints;

    }

    public static function saveImprint(Imprint $imprint): void
    {

        if (!self::isExistingImprint($imprint)) {
            self::insertImprint($imprint);
        } else {
            self::updateImprint($imprint);
        }

    }

    public static function insertImprint(Imprint $imprint): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_IMPRINT)
            ->values(
                    [
                        'page' => '?',
                        'description' => '?',
                        'date' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $imprint->getPage(),
                        1 => $imprint->getInfo(),
                        2 => time()
                    ]
                );

        $queryBuilder->execute();

    }

    public static function updateImprint(Imprint $imprint): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_IMPRINT)
            ->set("description", "?")
            ->set("date", "?")
            ->where("page = ?")
            ->setParameter(0, $imprint->getInfo())
            ->setParameter(1, time())
            ->setParameter(2, $imprint->getPage());

        $queryBuilder->execute();

    }

    private static function isExistingImprint(Imprint $imprint): bool
    {

        if (empty($imprint->getPage())) {
            throw new \InvalidArgumentException("page_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_IMPRINT)
            ->where('page = ?')
            ->setParameter(0, $imprint->getPage());

        $policy_query = $queryBuilder->execute();
        $policy_result = $policy_query->fetch();

        return !(empty($policy_result));

    }

}
