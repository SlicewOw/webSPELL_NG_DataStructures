<?php

namespace webspell_ng;


class NewsLanguage {

    /**
     * @var ?int $language_id
     */
    private $language_id;

    /**
     * @var string $language
     */
    private $language;

    /**
     * @var string $shortcut
     */
    private $shortcut;

    public function setLanguageId(int $language_id): void
    {
        $this->language_id = $language_id;
    }

    public function getLanguageId(): ?int
    {
        return $this->language_id;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setShortcut(string $shortcut): void
    {
        $this->shortcut = $shortcut;
    }

    public function getShortcut(): string
    {
        return $this->shortcut;
    }

}
