<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\SocialNetworkType;
use webspell_ng\WebSpellDatabaseConnection;


class SocialNetworkTypeHandler {

    private const DB_TABLE_NAME_SOCIAL_NETWORKS = "user_socials_types";

    public static function getSocialNetworkById(int $social_network_id): SocialNetworkType
    {

        if (!Validator::numericVal()->min(1)->validate($social_network_id)) {
            throw new \InvalidArgumentException('social_network_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SOCIAL_NETWORKS)
            ->where('typeID = ?')
            ->setParameter(0, $social_network_id);

        $social_network_query = $queryBuilder->execute();
        $social_network_result = $social_network_query->fetch();

        if (empty($social_network_result)) {
            throw new \InvalidArgumentException('unknown_social_network');
        }

        $social_network = new SocialNetworkType();
        $social_network->setSocialNetworkId((int) $social_network_result["typeID"]);
        $social_network->setName($social_network_result["name"]);
        $social_network->setIconPrefix($social_network_result["icon_prefix"]);
        $social_network->setSort((int) $social_network_result["sort"]);
        $social_network->setIsHomepage(
            ($social_network_result["is_url"] == 1)
        );
        if (!is_null($social_network_result["placeholder"])) {
            $social_network->setPlaceholderPlayer($social_network_result["placeholder"]);
        }
        if (!is_null($social_network_result["placeholder_team"])) {
            $social_network->setPlaceholderPlayer($social_network_result["placeholder_team"]);
        }

        return $social_network;

    }

    public static function saveSocialNetworkType(SocialNetworkType $social_network_type): SocialNetworkType
    {

        if (is_null($social_network_type->getSocialNetworkId())) {
            $social_network_type = self::insertSocialNetworkType($social_network_type);
        } else {
            self::updateSocialNetworkType($social_network_type);
        }

        return self::getSocialNetworkById($social_network_type->getSocialNetworkId());

    }

    private static function insertSocialNetworkType(SocialNetworkType $social_network_type): SocialNetworkType
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SOCIAL_NETWORKS)
            ->values(
                    [
                        'name' => '?',
                        'icon_prefix' => '?',
                        'placeholder' => '?',
                        'placeholder_team' => '?',
                        'is_url' => '?',
                        'sort' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $social_network_type->getName(),
                        1 => $social_network_type->getIconPrefix(),
                        2 => $social_network_type->getPlaceholderPlayer(),
                        3 => $social_network_type->getPlaceholderTeam(),
                        4 => $social_network_type->isHomepage() ? 1 : 0,
                        5 => $social_network_type->getSort()
                    ]
                );

        $queryBuilder->execute();

        $social_network_type->setSocialNetworkId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $social_network_type;

    }

    private static function updateSocialNetworkType(SocialNetworkType $social_network_type): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SOCIAL_NETWORKS)
            ->set('name', '?')
            ->set('icon_prefix', '?')
            ->set('placeholder', '?')
            ->set('placeholder_team', '?')
            ->set('is_url', '?')
            ->set('sort', '?')
            ->where('typeID = ?')
            ->setParameter(0, $social_network_type->getName())
            ->setParameter(1, $social_network_type->getIconPrefix())
            ->setParameter(2, $social_network_type->getPlaceholderPlayer())
            ->setParameter(3, $social_network_type->getPlaceholderTeam())
            ->setParameter(4, $social_network_type->isHomepage() ? 1 : 0)
            ->setParameter(5, $social_network_type->getSort())
            ->setParameter(6, $social_network_type->getSocialNetworkId());

        $queryBuilder->execute();

    }

}
