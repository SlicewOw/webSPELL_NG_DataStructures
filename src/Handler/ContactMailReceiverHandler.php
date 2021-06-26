<?php

namespace webspell_ng\Handler;

use webspell_ng\ContactMailReceiver;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Utils\ValidationUtils;

class ContactMailReceiverHandler {

    private const DB_TABLE_NAME_CONTACT_RECEIVER = "contact";

    public static function getContactReceiverById(int $receiver_id): ContactMailReceiver
    {

        if (!ValidationUtils::validateInteger($receiver_id, true)) {
            throw new \InvalidArgumentException("receiver_id_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CONTACT_RECEIVER)
            ->where('contactID = ?')
            ->setParameter(0, $receiver_id);

        $receiver_query = $queryBuilder->executeQuery();
        $receiver_result = $receiver_query->fetchAssociative();

        if (empty($receiver_result)) {
            throw new \UnexpectedValueException("unknown_receiver");
        }

        $receiver = new ContactMailReceiver();
        $receiver->setReceiverId($receiver_result['contactID']);
        $receiver->setName($receiver_result['name']);
        $receiver->setEmail($receiver_result['email']);
        $receiver->setSort($receiver_result['sort']);

        return $receiver;

    }

    public static function saveContactReceiver(ContactMailReceiver $receiver): ContactMailReceiver
    {

        if (is_null($receiver->getReceiverId())) {
            $receiver = self::insertContactReceiver($receiver);
        } else {
            self::updateContactReceiver($receiver);
        }

        return $receiver;

    }

    private static function insertContactReceiver(ContactMailReceiver $receiver): ContactMailReceiver
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CONTACT_RECEIVER)
            ->values(
                    [
                        'name' => '?',
                        'email' => '?',
                        'sort' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $receiver->getName(),
                        1 => $receiver->getEmail(),
                        2 => $receiver->getSort()
                    ]
                );

        $queryBuilder->executeQuery();

        $receiver->setReceiverId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $receiver;

    }

    private static function updateContactReceiver(ContactMailReceiver $receiver): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_CONTACT_RECEIVER)
            ->set('name', '?')
            ->set('email', '?')
            ->set('sort', '?')
            ->where('contactID = ?')
            ->setParameter(0, $receiver->getName())
            ->setParameter(1, $receiver->getEmail())
            ->setParameter(2, $receiver->getSort())
            ->setParameter(3, $receiver->getReceiverId());

        $queryBuilder->executeQuery();

    }

}
