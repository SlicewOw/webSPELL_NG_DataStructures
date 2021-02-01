<?php

namespace webspell_ng;

use webspell_ng\NewsContent;
use webspell_ng\NewsRubric;
use webspell_ng\NewsSource;
use webspell_ng\User;


class News {

    /**
     * @var ?int $news_id
     */
    private $news_id;

    /**
     * @var ?NewsRubric $rubric
     */
    private $rubric;

    /**
     * @var User $writer
     */
    private $writer;

    /**
     * @var array<NewsContent> $content
     */
    private $content = array();

    /**
     * @var array<NewsSource> $sources
     */
    private $sources = array();

    /**
     * @var bool $published
     */
    private $published = false;

    /**
     * @var bool $internal
     */
    private $internal = false;

    /**
     * @var ?\DateTime $date
     */
    private $date;

    public function setNewsId(int $news_id): void
    {
        $this->news_id = $news_id;
    }

    public function getNewsId(): ?int
    {
        return $this->news_id;
    }

    public function setRubric(NewsRubric $rubric): void
    {
        $this->rubric = $rubric;
    }

    public function getRubric(): ?NewsRubric
    {
        return $this->rubric;
    }

    public function setWriter(User $writer): void
    {
        $this->writer = $writer;
    }

    public function getWriter(): User
    {
        return $this->writer;
    }

    /**
     * @param array<NewsContent> $content
     */
    public function setContent(array $content): void
    {
        $this->content = $content;
    }

    public function addContent(NewsContent $content): void
    {
        array_push(
            $this->content,
            $content
        );
    }

    /**
     * @return array<NewsContent>
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param array<NewsSource> $sources
     */
    public function setSources(array $sources): void
    {
        $this->sources = $sources;
    }

    public function addSource(NewsSource $source): void
    {
        array_push(
            $this->sources,
            $source
        );
    }

    /**
     * @return array<NewsSource>
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    public function setIsPublished(bool $published): void
    {
        $this->published = $published;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setIsInternal(bool $internal): void
    {
        $this->internal = $internal;
    }

    public function isInternal(): bool
    {
        return $this->internal;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getDate(): \DateTime
    {
        if (is_null($this->date)) {
            return new \DateTime("now");
        }
        return $this->date;
    }

}
