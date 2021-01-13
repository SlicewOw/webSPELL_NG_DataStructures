<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Prophecy\Exception\Call\UnexpectedCallException;
use webspell_ng\NewsRubric;
use webspell_ng\Handler\NewsRubricHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class NewsRubricHandlerTest extends TestCase
{

    public function testIfNewsRubricCanBeSavedAndUpdated(): void
    {

        $rubric_name = "Test rubric " . StringFormatterUtils::getRandomString(10);
        $rubric_image = StringFormatterUtils::getRandomString(10) . ".jpg";

        $new_rubric = new NewsRubric();
        $new_rubric->setName($rubric_name);
        $new_rubric->setImage($rubric_image);

        $saved_rubric = NewsRubricHandler::saveRubric($new_rubric);

        $this->assertGreaterThan(0, $saved_rubric->getRubricId(), "Rubric ID is set.");
        $this->assertEquals($rubric_name, $saved_rubric->getName(), "Rubric name is set.");
        $this->assertEquals($rubric_image, $saved_rubric->getImage(), "Rubric image is set.");
        $this->assertTrue($saved_rubric->isActive(), "Rubric is active.");

        $changed_rubric_name = "Test rubric " . StringFormatterUtils::getRandomString(10);

        $saved_rubric->setName($changed_rubric_name);

        $updated_rubric = NewsRubricHandler::saveRubric($saved_rubric);

        $this->assertEquals($saved_rubric->getRubricId(), $updated_rubric->getRubricId(), "Rubric ID is set.");
        $this->assertEquals($changed_rubric_name, $updated_rubric->getName(), "Rubric name is set.");
        $this->assertEquals($rubric_image, $updated_rubric->getImage(), "Rubric image is set.");
        $this->assertTrue($updated_rubric->isActive(), "Rubric is active.");

    }

    public function testIfNewsRubricCanBeDeleted(): void
    {

        $new_rubric = new NewsRubric();
        $new_rubric->setName("Test rubric " . StringFormatterUtils::getRandomString(10));
        $new_rubric->setImage(StringFormatterUtils::getRandomString(10) . ".jpg");

        $rubric = NewsRubricHandler::saveRubric($new_rubric);

        $this->assertGreaterThan(0, $rubric->getRubricId(), "Rubric ID is set.");

        NewsRubricHandler::deleteRubric($rubric);

        $deleted_rubric = NewsRubricHandler::getRubricByRubricId($rubric->getRubricId());

        $this->assertFalse($deleted_rubric->isActive(), "Rubric is inactive.");

    }

    public function testIfAllRubricsReturnsAsAnArray(): void
    {

        $rubrics = NewsRubricHandler::getAllRubrics();

        $this->assertGreaterThan(0, count($rubrics), "Array is not empty.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfNewsRubricIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        NewsRubricHandler::getRubricByRubricId(-1);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfNewsRubricDoesNotExist(): void
    {

        $this->expectException(UnexpectedValueException::class);

        NewsRubricHandler::getRubricByRubricId(999999999);

    }

}
