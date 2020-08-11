<?php

namespace webspell_ng;

class Settings
{

    /** @var string $homepage_title */
    private $homepage_title;

    public function setHomepageTitle(string $homepage_title): void
    {
        $this->homepage_title = $homepage_title;
    }

    public function getHomepageTitle(): string
    {
        return $this->homepage_title;
    }

}
