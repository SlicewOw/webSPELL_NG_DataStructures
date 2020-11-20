<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\WebSpellDatabaseConnection;

use webspell_ng\Sponsor;

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

        return $sponsor;

    }

}