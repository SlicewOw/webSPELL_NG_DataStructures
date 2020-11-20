<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\Sponsor;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Utils\DateUtils;


class SponsorHandler {

    public static function getSponsorBySponsorId(int $sponsor_id): Sponsor
    {

        if (!Validator::numericVal()->min(1)->validate($sponsor_id)) {
            throw new \InvalidArgumentException('sponsor_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . 'sponsors')
            ->where('sponsorID = ?')
            ->setParameter(0, $sponsor_id);

        $sponsor_query = $queryBuilder->execute();
        $sponsor_result = $sponsor_query->fetch();

        if (empty($sponsor_result)) {
            throw new \InvalidArgumentException('unknown_sponsor');
        }

        $sponsor = new Sponsor();
        $sponsor->setSponsorId($sponsor_result['sponsorID']);
        $sponsor->setName($sponsor_result['name']);
        $sponsor->setHomepage($sponsor_result['url']);
        $sponsor->setInfo($sponsor_result['info']);
        $sponsor->setBanner($sponsor_result['banner']);
        $sponsor->setBannerSmall($sponsor_result['banner_small']);
        $sponsor->setIsDisplayed($sponsor_result['displayed']);
        $sponsor->setIsMainsponsor($sponsor_result['mainsponsor']);
        $sponsor->setDate(
            DateUtils::getDateTimeByMktimeValue($sponsor_result['date'])
        );
        $sponsor->setSort($sponsor_result['sort']);

        return $sponsor;

    }

    public static function saveSponsor(Sponsor $sponsor): Sponsor
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . 'sponsors')
            ->values(
                    [
                        'name' => '?',
                        'url' => '?',
                        'info' => '?',
                        'banner' => '?',
                        'banner_small' => '?',
                        'displayed' => '?',
                        'mainsponsor' => '?',
                        'date' => '?',
                        'sort' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $sponsor->getName(),
                        1 => $sponsor->getHomepage(),
                        2 => $sponsor->getInfo(),
                        3 => $sponsor->getBanner(),
                        4 => $sponsor->getBannerSmall(),
                        5 => $sponsor->isDisplayed() ? 1 : 0,
                        6 => $sponsor->isMainsponsor() ? 1 : 0,
                        7 => $sponsor->getDate()->getTimestamp(),
                        8 => $sponsor->getSort()
                    ]
                );

        $queryBuilder->execute();

        $sponsor->setSponsorId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $sponsor;

    }

}