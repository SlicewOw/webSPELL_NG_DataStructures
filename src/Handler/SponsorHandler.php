<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\Sponsor;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Handler\SponsorSocialNetworkHandler;
use webspell_ng\Utils\DateUtils;


class SponsorHandler {

    private const DB_TABLE_NAME_SPONSORS = "sponsors";

    public static function getSponsorBySponsorId(int $sponsor_id): Sponsor
    {

        if (!Validator::numericVal()->min(1)->validate($sponsor_id)) {
            throw new \InvalidArgumentException('sponsor_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SPONSORS)
            ->where('sponsorID = ?')
            ->setParameter(0, $sponsor_id);

        $sponsor_query = $queryBuilder->executeQuery();
        $sponsor_result = $sponsor_query->fetchAssociative();

        if (empty($sponsor_result)) {
            throw new \InvalidArgumentException('unknown_sponsor');
        }

        $sponsor = new Sponsor();
        $sponsor->setSponsorId((int) $sponsor_result['sponsorID']);
        $sponsor->setName($sponsor_result['name']);
        $sponsor->setHomepage($sponsor_result['homepage']);
        $sponsor->setInfo($sponsor_result['info']);
        $sponsor->setBanner($sponsor_result['banner']);
        $sponsor->setBannerSmall($sponsor_result['banner_small']);
        $sponsor->setHits((int) $sponsor_result['hits']);
        $sponsor->setSort((int) $sponsor_result['sort']);
        $sponsor->setIsActive(
            ($sponsor_result['displayed'] == 1)
        );
        $sponsor->setIsMainsponsor(
            ($sponsor_result['mainsponsor'] == 1)
        );
        $sponsor->setShowOnSubPagesOnly(
            ($sponsor_result['subpage_only'] == 1)
        );
        $sponsor->setShowOnFrontPageOnly(
            ($sponsor_result['frontpage_only'] == 1)
        );
        $sponsor->setDate(
            DateUtils::getDateTimeByMktimeValue($sponsor_result['date'])
        );

        $sponsor->setSocialNetworks(
            SponsorSocialNetworkHandler::getSocialNetworksOfSponsor($sponsor)
        );

        return $sponsor;

    }

    /**
     * @return array<Sponsor>
     */
    public static function getAllActiveSponsors(): array
    {
        return self::getSponsorsByParameters(true);
    }

    /**
     * @return array<Sponsor>
     */
    public static function getAllSponsors(): array
    {
        return self::getSponsorsByParameters(false);
    }

    /**
     * @return array<Sponsor>
     */
    private static function getSponsorsByParameters(bool $displayed_sponsors_only = true): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();

        $queryBuilder
            ->select('sponsorID')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SPONSORS)
            ->orderBy("sort", "ASC")
            ->addOrderBy("mainsponsor", "DESC");

        if ($displayed_sponsors_only) {
            $queryBuilder->where(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('displayed', '1')
                )
            );
        }

        $sponsor_query = $queryBuilder->executeQuery();

        $sponsors = array();
        while ($sponsor_result = $sponsor_query->fetchAssociative())
        {
            array_push(
                $sponsors,
                self::getSponsorBySponsorId((int) $sponsor_result['sponsorID'])
            );
        }

        return $sponsors;

    }

    public static function saveSponsor(Sponsor $sponsor): Sponsor
    {

        if (is_null($sponsor->getSponsorId())) {
            $sponsor = self::insertSponsor($sponsor);
        } else {
            self::updateSponsor($sponsor);
        }

        SponsorSocialNetworkHandler::saveSocialNetworksOfSponsor($sponsor);

        if (is_null($sponsor->getSponsorId())) {
            throw new \UnexpectedValueException("sponsor_id_is_not_set");
        }

        return self::getSponsorBySponsorId($sponsor->getSponsorId());

    }

    public static function insertSponsor(Sponsor $sponsor): Sponsor
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SPONSORS)
            ->values(
                    [
                        'name' => '?',
                        'homepage' => '?',
                        'info' => '?',
                        'banner' => '?',
                        'banner_small' => '?',
                        'displayed' => '?',
                        'mainsponsor' => '?',
                        'date' => '?',
                        'sort' => '?',
                        'subpage_only' => '?',
                        'frontpage_only' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $sponsor->getName(),
                        1 => $sponsor->getHomepage(),
                        2 => $sponsor->getInfo(),
                        3 => $sponsor->getBanner(),
                        4 => $sponsor->getBannerSmall(),
                        5 => $sponsor->isActive() ? 1 : 0,
                        6 => $sponsor->isMainsponsor() ? 1 : 0,
                        7 => $sponsor->getDate()->getTimestamp(),
                        8 => $sponsor->getSort(),
                        9 => $sponsor->showOnSubPagesOnly() ? 1 : 0,
                        10 => $sponsor->showOnFrontPageOnly() ? 1 : 0
                    ]
                );

        $queryBuilder->executeQuery();

        $sponsor->setSponsorId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $sponsor;

    }

    public static function updateSponsor(Sponsor $sponsor): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SPONSORS)
            ->set('name', '?')
            ->set('homepage', '?')
            ->set('banner', '?')
            ->set('banner_small', '?')
            ->set('date', '?')
            ->set('displayed', '?')
            ->set('mainsponsor', '?')
            ->set('sort', '?')
            ->set('subpage_only', '?')
            ->set('frontpage_only', '?')
            ->where('sponsorID = ?')
            ->setParameter(0, $sponsor->getName())
            ->setParameter(1, $sponsor->getHomepage())
            ->setParameter(2, $sponsor->getBanner())
            ->setParameter(3, $sponsor->getBannerSmall())
            ->setParameter(4, $sponsor->getDate()->getTimestamp())
            ->setParameter(5, $sponsor->isActive() ? 1 : 0)
            ->setParameter(6, $sponsor->isMainsponsor() ? 1 : 0)
            ->setParameter(7, $sponsor->getSort())
            ->setParameter(8, $sponsor->showOnSubPagesOnly() ? 1 : 0)
            ->setParameter(9, $sponsor->showOnFrontPageOnly() ? 1 : 0)
            ->setParameter(10, $sponsor->getSponsorId());

        $queryBuilder->executeQuery();

    }

}