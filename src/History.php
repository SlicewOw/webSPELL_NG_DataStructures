<?php

namespace webspell_ng;


class History {

    /**
     * @var int $year
     */
    private $year;

    /**
     * @var string $text
     */
    private $text;

    /**
     * @var ?\DateTime $updated_on
     */
    private $updated_on;

    /**
     * @var bool $is_published
     */
    private $is_published = false;

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setDate(\DateTime $updated_on): void
    {
        $this->updated_on = $updated_on;
    }

    public function getDate(): \DateTime
    {
        if (is_null($this->updated_on)) {
            return new \DateTime("now");
        }
        return $this->updated_on;
    }

    public function setIsPublished(bool $is_published): void
    {
        $this->is_published = $is_published;
    }

    public function isPublished(): bool
    {
        return $this->is_published;
    }

}
