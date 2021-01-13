<?php

namespace webspell_ng;

use webspell_ng\NewsLanguage;


class NewsContent {

    /**
     * @var NewsLanguage $language
     */
    private $language;

    /**
     * @var string $headline
     */
    private $headline;

    /**
     * @var string $content
     */
    private $content;

    public function setLanguage(NewsLanguage $language): void
    {
        $this->language = $language;
    }

    public function getLanguage(): NewsLanguage
    {
        return $this->language;
    }

    public function setHeadline(string $headline): void
    {
        $this->headline = $headline;
    }

    public function getHeadline(): string
    {
        return $this->headline;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

}
