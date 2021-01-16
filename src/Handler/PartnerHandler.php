<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\Partner;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Utils\DateUtils;


class PartnerHandler {

    private const DB_TABLE_NAME_PARTNERS = "partners";

    public static function getPartnerByPartnerId(int $partner_id): Partner
    {

        if (!Validator::numericVal()->min(1)->validate($partner_id)) {
            throw new \InvalidArgumentException('partner_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_PARTNERS)
            ->where('partnerID = ?')
            ->setParameter(0, $partner_id);

        $partner_query = $queryBuilder->execute();
        $partner_result = $partner_query->fetch();

        if (empty($partner_result)) {
            throw new \InvalidArgumentException('unknown_partner');
        }

        $partner = new Partner();
        $partner->setPartnerId((int) $partner_result['partnerID']);
        $partner->setName($partner_result['name']);
        $partner->setHomepage($partner_result['homepage']);
        $partner->setBanner($partner_result['banner']);
        $partner->setSort((int) $partner_result['sort']);
        $partner->setHits((int) $partner_result['hits']);
        $partner->setIsDisplayed(
            ($partner_result['displayed'] == 1)
        );
        $partner->setDate(
            DateUtils::getDateTimeByMktimeValue((int) $partner_result['date'])
        );

        return $partner;

    }

    /**
     * @return array<Partner>
     */
    public static function getAllPartners(): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('partnerID')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_PARTNERS)
            ->where('displayed = 1')
            ->orderBy("sort", "ASC");

        $partner_query = $queryBuilder->execute();

        $partners = array();
        while ($partner_result = $partner_query->fetch())
        {
            array_push(
                $partners,
                self::getPartnerByPartnerId((int) $partner_result['partnerID'])
            );
        }

        return $partners;

    }

    public static function savePartner(Partner $partner): Partner
    {

        if (is_null($partner->getPartnerId())) {
            $partner = self::insertPartner($partner);
        } else {
            self::updatePartner($partner);
        }

        return self::getPartnerByPartnerId($partner->getPartnerId());

    }

    private static function insertPartner(Partner $partner): Partner
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_PARTNERS)
            ->values(
                    [
                        'name' => '?',
                        'homepage' => '?',
                        'banner' => '?',
                        'date' => '?',
                        'displayed' => '?',
                        'sort' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $partner->getName(),
                        1 => $partner->getHomepage(),
                        2 => $partner->getBanner(),
                        3 => $partner->getDate()->getTimestamp(),
                        4 => $partner->isDisplayed() ? 1 : 0,
                        5 => $partner->getSort()
                    ]
                );

        $queryBuilder->execute();

        $partner->setPartnerId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $partner;

    }

    private static function updatePartner(Partner $partner): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_PARTNERS)
            ->set('name', '?')
            ->set('homepage', '?')
            ->set('banner', '?')
            ->set('date', '?')
            ->set('displayed', '?')
            ->set('sort', '?')
            ->where('partnerID = ?')
            ->setParameter(0, $partner->getName())
            ->setParameter(1, $partner->getHomepage())
            ->setParameter(2, $partner->getBanner())
            ->setParameter(3, $partner->getDate()->getTimestamp())
            ->setParameter(4, $partner->isDisplayed() ? 1 : 0)
            ->setParameter(5, $partner->getSort())
            ->setParameter(6, $partner->getPartnerId());

        $queryBuilder->execute();

    }

}
