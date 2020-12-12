<?php declare(strict_types=1);

use phpDocumentor\Reflection\DocBlock\Tags\InvalidTag;
use PHPUnit\Framework\TestCase;

use webspell_ng\ContactMailReceiver;
use webspell_ng\Handler\ContactMailReceiverHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class ContactMailReceiverHandlerTest extends TestCase
{

    public function testIfContactReceiverCanBeSavedAndUpdated(): void
    {

        $receiver_name = StringFormatterUtils::getRandomString(10, 2);
        $receiver_email = StringFormatterUtils::getRandomString(10, 2) . "@webspell-ng.de";
        $sort = rand(1, 999999);

        $new_receiver = new ContactMailReceiver();
        $new_receiver->setName($receiver_name);
        $new_receiver->setEmail($receiver_email);
        $new_receiver->setSort($sort);

        $receiver = ContactMailReceiverHandler::saveContactReceiver($new_receiver);

        $this->assertGreaterThan(0, $receiver->getReceiverId(), "Receiver ID is set.");
        $this->assertEquals($receiver_name, $receiver->getName(), "Receiver name is set.");
        $this->assertEquals($receiver_email, $receiver->getEmail(), "Receiver email is set.");
        $this->assertEquals($sort, $receiver->getSort(), "Receiver sort is set.");

        $receiver_from_database = ContactMailReceiverHandler::getContactReceiverById($receiver->getReceiverId());

        $this->assertEquals($receiver->getReceiverId(), $receiver_from_database->getReceiverId(), "Receiver name is set.");
        $this->assertEquals($receiver->getName(), $receiver_from_database->getName(), "Receiver name is set.");
        $this->assertEquals($receiver->getEmail(), $receiver_from_database->getEmail(), "Receiver email is set.");
        $this->assertEquals($receiver->getSort(), $receiver_from_database->getSort(), "Receiver sort is set.");

        $changed_receiver_name = StringFormatterUtils::getRandomString(10, 2);
        $changed_sort = rand(1, 999999);

        $receiver_from_database->setName($changed_receiver_name);
        $receiver_from_database->setSort($changed_sort);

        ContactMailReceiverHandler::saveContactReceiver($receiver_from_database);

        $updated_receiver = ContactMailReceiverHandler::getContactReceiverById($receiver->getReceiverId());

        $this->assertEquals($receiver->getReceiverId(), $updated_receiver->getReceiverId(), "Receiver name is set.");
        $this->assertEquals($changed_receiver_name, $updated_receiver->getName(), "Receiver name is set.");
        $this->assertEquals($receiver->getEmail(), $updated_receiver->getEmail(), "Receiver email is set.");
        $this->assertEquals($changed_sort, $updated_receiver->getSort(), "Receiver sort is set.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfReceiverIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        ContactMailReceiverHandler::getContactReceiverById(-1);

    }

    public function testIfUnexpectedValueExceptionIsThrownIfReceiverDoesNotExist(): void
    {

        $this->expectException(UnexpectedValueException::class);

        ContactMailReceiverHandler::getContactReceiverById(99999999);

    }

}
