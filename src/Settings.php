<?php

namespace webspell_ng;

class Settings
{

    /**
     * @var string $homepage_title
     */
    private $homepage_title;

    /**
     * @var string $clanname
     */
    private $clanname;

    /**
     * @var string $clantag
     */
    private $clantag;

    /**
     * @var string $default_date_format
     */
    private $default_date_format;

    /**
     * @var string $default_time_format
     */
    private $default_time_format;

    public function setHomepageTitle(string $homepage_title): void
    {
        $this->homepage_title = $homepage_title;
    }

    public function getHomepageTitle(): string
    {
        return $this->homepage_title;
    }

    public function setClanname(string $clanname): void
    {
        $this->clanname = $clanname;
    }

    public function getClanname(): string
    {
        return $this->clanname;
    }

    public function setClantag(string $clantag): void
    {
        $this->clantag = $clantag;
    }

    public function getClantag(): string
    {
        return $this->clantag;
    }

    public function setDefaultDateFormat(string $default_date_format): void
    {
        $this->default_date_format = $default_date_format;
    }

    public function getDefaultDateFormat(): string
    {
        return $this->default_date_format;
    }

    public function setDefaultTimeFormat(string $default_time_format): void
    {
        $this->default_time_format = $default_time_format;
    }

    public function getDefaultTimeFormat(): string
    {
        return $this->default_time_format;
    }

}
