<?php

namespace webspell_ng\Handler;

use webspell_ng\PrivacyPolicy;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Enums\PolicyEnums;
use webspell_ng\Utils\DateUtils;


class PrivacyPolicyHandler {

    private const DB_TABLE_NAME_PRIVACY_POLICY = "privacy_policy";

    public static function getPrivacyPolicyByPage(string $page): PrivacyPolicy
    {

        if (empty($page)) {
            throw new \InvalidArgumentException('page_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_PRIVACY_POLICY)
            ->where('page = ?')
            ->setParameter(0, $page);

        $policy_query = $queryBuilder->execute();
        $policy_result = $policy_query->fetch();

        if (empty($policy_result)) {
            return self::getPrivacyPolicyByPage(PolicyEnums::POLICY_DEFAULT);
        }

        $policy = new PrivacyPolicy();
        $policy->setPage($policy_result['page']);
        $policy->setInfo($policy_result['description']);
        $policy->setDate(
            DateUtils::getDateTimeByMktimeValue((int) $policy_result['date'])
        );

        return $policy;

    }

    public static function savePolicy(PrivacyPolicy $policy): void
    {

        if (!self::isExistingPolicy($policy)) {
            self::insertPolicy($policy);
        } else {
            self::updatePolicy($policy);
        }

    }

    public static function insertPolicy(PrivacyPolicy $policy): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_PRIVACY_POLICY)
            ->values(
                    [
                        'page' => '?',
                        'description' => '?',
                        'date' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $policy->getPage(),
                        1 => $policy->getInfo(),
                        2 => time()
                    ]
                );

        $queryBuilder->execute();

    }

    public static function updatePolicy(PrivacyPolicy $policy): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_PRIVACY_POLICY)
            ->set("description", "?")
            ->set("date", "?")
            ->where("page = ?")
            ->setParameter(0, $policy->getInfo())
            ->setParameter(1, time())
            ->setParameter(2, $policy->getPage());

        $queryBuilder->execute();

    }

    private static function isExistingPolicy(PrivacyPolicy $policy): bool
    {

        if (empty($policy->getPage())) {
            throw new \InvalidArgumentException("page_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_PRIVACY_POLICY)
            ->where('page = ?')
            ->setParameter(0, $policy->getPage());

        $policy_query = $queryBuilder->execute();
        $policy_result = $policy_query->fetch();

        return !(empty($policy_result));

    }

}
