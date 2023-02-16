<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use \webspell_ng\Clan;
use \webspell_ng\WebSpellDatabaseConnection;
use \webspell_ng\Utils\StringFormatterUtils;


class ClanHandler
{

    private const DB_TABLE_NAME_TEAMS = "clans";

    public static function getClanByClanId(int $clan_id): Clan
    {

        if (!Validator::numericVal()->min(1)->validate($clan_id)) {
            throw new \InvalidArgumentException('clan_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_TEAMS)
            ->where('clanID = ?')
            ->setParameter(0, $clan_id);

        $clan_query = $queryBuilder->executeQuery();
        $clan_result = $clan_query->fetchAssociative();

        if (empty($clan_result)) {
            throw new \UnexpectedValueException('unknown_clan');
        }

        $clan = new Clan();
        $clan->setClanId($clan_result['clanID']);
        $clan->setClanName($clan_result['name']);
        $clan->setClanTag($clan_result['tag']);
        $clan->setHomepage($clan_result['homepage']);
        $clan->setClanLogotype($clan_result['logotype']);

        return $clan;
    }

    public static function isExistingClan(string $clan_name): bool
    {

        if (empty($clan_name)) {
            throw new \InvalidArgumentException("clan_name_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_TEAMS)
            ->where('name = ?')
            ->setParameter(0, StringFormatterUtils::getTextFormattedForDatabase($clan_name));

        $clan_query = $queryBuilder->executeQuery();
        $clan_result = $clan_query->fetchAssociative();

        return !empty($clan_result);
    }

    public static function saveClan(Clan $clan): Clan
    {

        if (is_null($clan->getClanId())) {
            $clan = self::insertClan($clan);
        } else {
            self::updateClan($clan);
        }

        return $clan;
    }

    private static function insertClan(Clan $clan): Clan
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_TEAMS)
            ->values(
                [
                    'name' => '?',
                    'tag' => '?',
                    'homepage' => '?',
                    'logotype' => '?'
                ]
            )
            ->setParameters(
                [
                    0 => $clan->getClanName(),
                    1 => $clan->getClanTag(),
                    2 => $clan->getHomepage(),
                    3 => $clan->getClanLogotype()
                ]
            );

        $queryBuilder->executeQuery();

        $clan->setClanId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $clan;
    }

    private static function updateClan(Clan $clan): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_TEAMS)
            ->set('name', '?')
            ->set('tag', '?')
            ->set('homepage', '?')
            ->set('logotype', '?')
            ->where('clanID = ?')
            ->setParameter(0, $clan->getClanName())
            ->setParameter(1, $clan->getClanTag())
            ->setParameter(2, $clan->getHomepage())
            ->setParameter(3, $clan->getClanLogotype())
            ->setParameter(4, $clan->getClanId());

        $queryBuilder->executeQuery();
    }
}
