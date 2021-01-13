<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\News;
use webspell_ng\NewsContent;
use webspell_ng\NewsLanguage;
use webspell_ng\NewsRubric;
use webspell_ng\User;
use webspell_ng\Handler\NewsHandler;
use webspell_ng\Handler\NewsLanguageHandler;
use webspell_ng\Handler\NewsRubricHandler;
use webspell_ng\Handler\UserHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class NewsHandlerTest extends TestCase
{

    /**
     * @var NewsRubric $first_rubric
     */
    private static $first_rubric;

    /**
     * @var NewsRubric $second_rubric
     */
    private static $second_rubric;

    /**
     * @var NewsLanguage $language
     */
    private static $language;

    /**
     * @var User $user
     */
    private static $user;

    public static function setUpBeforeClass(): void
    {

        $new_rubric_01 = new NewsRubric();
        $new_rubric_01->setName("Test rubric " . StringFormatterUtils::getRandomString(10));
        $new_rubric_01->setImage(StringFormatterUtils::getRandomString(10) . ".jpg");

        self::$first_rubric = NewsRubricHandler::saveRubric($new_rubric_01);

        $new_rubric_02 = new NewsRubric();
        $new_rubric_02->setName("Test rubric " . StringFormatterUtils::getRandomString(10));
        $new_rubric_02->setImage(StringFormatterUtils::getRandomString(10) . ".jpg");

        self::$second_rubric = NewsRubricHandler::saveRubric($new_rubric_02);

        self::$language = NewsLanguageHandler::getNewsLanguageByShortcut("de");

        self::$user = UserHandler::getUserByUserId(1);

    }

    public function testIfNewsCanBeSavedAndUpdated(): void
    {

        $headline = "Test headline " . StringFormatterUtils::getRandomString(10);
        $news_text = "Lorem ipsum " . StringFormatterUtils::getRandomString(10);

        $new_content = new NewsContent();
        $new_content->setHeadline($headline);
        $new_content->setContent($news_text);
        $new_content->setLanguage(self::$language);

        $new_news = new News();
        $new_news->setWriter(self::$user);
        $new_news->setRubric(self::$first_rubric);
        $new_news->addContent($new_content);

        $saved_news = NewsHandler::saveNews($new_news);

        $this->assertGreaterThan(0, $saved_news->getNewsId(), "News ID is set.");
        $this->assertEquals(self::$first_rubric->getName(), $saved_news->getRubric()->getName(), "Rubric is set.");
        $this->assertEquals(self::$user->getUserId(), $saved_news->getWriter()->getUserId(), "Writer is set.");
        $this->assertFalse($saved_news->isPublished(), "News is not published yet.");
        $this->assertFalse($saved_news->isInternal(), "News is not an internal news.");

        $news_contents = $saved_news->getContent();

        $this->assertEquals(1, count($news_contents), "News content is saved.");

        $news_content = $news_contents[0];

        $this->assertEquals($headline, $news_content->getHeadline(), "Headline is set.");
        $this->assertEquals($news_text, $news_content->getContent(), "Content is set.");
        $this->assertEquals(self::$language->getLanguage(), $news_content->getLanguage()->getLanguage(), "Content language is set.");

        $new_news->setRubric(self::$second_rubric);

        $update_news = NewsHandler::saveNews($new_news);

        $this->assertEquals($saved_news->getNewsId(), $update_news->getNewsId(), "News ID is set.");
        $this->assertEquals(self::$second_rubric->getName(), $update_news->getRubric()->getName(), "Rubric is set.");
        $this->assertEquals(self::$user->getUserId(), $update_news->getWriter()->getUserId(), "Writer is set.");
        $this->assertFalse($update_news->isPublished(), "News is not published yet.");
        $this->assertFalse($update_news->isInternal(), "News is not an internal news.");

    }

    public function testIfNewsCanBePublishedAndUnpublished(): void
    {

        $new_content = new NewsContent();
        $new_content->setHeadline("Test headline " . StringFormatterUtils::getRandomString(10));
        $new_content->setContent("Lorem ipsum " . StringFormatterUtils::getRandomString(10));
        $new_content->setLanguage(self::$language);

        $new_news = new News();
        $new_news->setWriter(self::$user);
        $new_news->setRubric(self::$first_rubric);
        $new_news->addContent($new_content);

        $news = NewsHandler::saveNews($new_news);

        $this->assertGreaterThan(0, $news->getNewsId(), "News ID is set.");
        $this->assertFalse($news->isPublished(), "News is not published yet.");

        NewsHandler::publishNews($news);

        $published_news = NewsHandler::getNewsByNewsId($news->getNewsId());

        $this->assertTrue($published_news->isPublished(), "News is published.");

        NewsHandler::unpublishNews($published_news);

        $unpublished_news = NewsHandler::getNewsByNewsId($news->getNewsId());

        $this->assertFalse($unpublished_news->isPublished(), "News is not published yet.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfNewsIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        NewsHandler::getNewsByNewsId(-1);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfNewsDoesNotExist(): void
    {

        $this->expectException(UnexpectedValueException::class);

        NewsHandler::getNewsByNewsId(99999999);

    }

}
