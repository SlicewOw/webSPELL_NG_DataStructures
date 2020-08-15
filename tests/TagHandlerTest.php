<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Handler\TagHandler;

final class TagHandlerTest extends TestCase
{

    public function testIfTagsCanBeSavedAndReadFromDatabase(): void
    {

        $related_type = "ne";
        $related_id = rand(10000, 99999);
        $tags = array(
            "tag1-" . rand(1, 100),
            "tag2-" . rand(1, 100),
            "tag3-" . rand(1, 100)
        );

        $this->assertTrue(TagHandler::setTags($related_type, $related_id, $tags), "Tags can be saved");

        $saved_tags = TagHandler::getTags($related_type, $related_id);

        $this->assertEquals(3, count($saved_tags), "Count of tags is expected.");
        foreach ($tags as $tag) {
            $this->assertTrue(in_array($tag, $saved_tags), "Tag is returned by database query.");
        }

        TagHandler::removeTags($related_type, $related_id);

        $deleted_tags = TagHandler::getTags($related_type, $related_id);

        $this->assertEquals(0, count($deleted_tags), "There is no tag left after deletion.");

    }

}
