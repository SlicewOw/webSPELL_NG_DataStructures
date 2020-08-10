<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Template;

final class TemplateTest extends TestCase
{

    public function testIfDefaultErrorTemplateCanBeLoaded(): void
    {

        $template = new Template("test_module", false, "templates");
        $template_content = $template->replaceTemplate(
            "not_existing_template",
            array()
        );

        $this->assertTrue(!empty($template_content), "Default template is not empty!");

    }

}
