<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\SocialNetwork;
use webspell_ng\Sponsor;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Handler\SocialNetworkTypeHandler;


class SponsorSocialNetworkHandler {

    private const DB_TABLE_NAME_SPONSORS_SOCIAL_NETWORK = "sponsors_social_network";

    /**
     * @return array<SocialNetwork>
     */
    public static function getSocialNetworksOfSponsor(Sponsor $sponsor): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SPONSORS_SOCIAL_NETWORK)
            ->where('sponsorID = ?')
            ->setParameter(0, $sponsor->getSponsorId());

        $social_network_query = $queryBuilder->executeQuery();

        $social_networks = array();
        while ($social_network_result = $social_network_query->fetchAssociative())
        {

            $social_network = new SocialNetwork();
            $social_network->setValue($social_network_result['value']);
            $social_network->setSocialNetworkType(
                SocialNetworkTypeHandler::getSocialNetworkById((int) $social_network_result['social_network_id'])
            );

            array_push(
                $social_networks,
                $social_network
            );

        }

        return $social_networks;

    }

    public static function saveSocialNetworksOfSponsor(Sponsor $sponsor): void
    {

        if (is_null($sponsor->getSponsorId())) {
            throw new \UnexpectedValueException("sponsor_id_of_social_network_is_not_set");
        }

        $all_social_networks = $sponsor->getSocialNetworks();
        foreach ($all_social_networks as $social_network) {

            if (!self::isSavedSocialNetwork($sponsor, $social_network)) {
                self::insertSocialNetworkOfSponsor($sponsor, $social_network);
            } else {
                self::updateSocialNetworkOfSponsor($sponsor, $social_network);
            }

        }

    }

    private static function isSavedSocialNetwork(Sponsor $sponsor, SocialNetwork $social_network): bool
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SPONSORS_SOCIAL_NETWORK)
            ->where('sponsorID = ?', 'social_network_id = ?')
            ->setParameter(0, $sponsor->getSponsorId())
            ->setParameter(1, $social_network->getSocialNetworkType()->getSocialNetworkId());

        $social_network_query = $queryBuilder->executeQuery();
        $social_network_result = $social_network_query->fetchAssociative();

        return !empty($social_network_result);

    }

    private static function insertSocialNetworkOfSponsor(Sponsor $sponsor, SocialNetwork $social_network): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SPONSORS_SOCIAL_NETWORK)
            ->values(
                    [
                        'sponsorID' => '?',
                        'social_network_id' => '?',
                        'value' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $sponsor->getSponsorId(),
                        1 => $social_network->getSocialNetworkType()->getSocialNetworkId(),
                        2 => $social_network->getValue()
                    ]
                );

        $queryBuilder->executeQuery();

    }

    private static function updateSocialNetworkOfSponsor(Sponsor $sponsor, SocialNetwork $social_network): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SPONSORS_SOCIAL_NETWORK)
            ->set('value', '?')
            ->where('sponsorID = ?', 'social_network_id = ?')
            ->setParameter(0, $social_network->getValue())
            ->setParameter(1, $sponsor->getSponsorId())
            ->setParameter(2, $social_network->getSocialNetworkType()->getSocialNetworkId());

        $queryBuilder->executeQuery();

    }

}
