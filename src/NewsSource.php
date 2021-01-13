<?php

namespace webspell_ng;


class NewsSource {

    /**
     * @var ?int $source_id
     */
    private $source_id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $homepage
     */
    private $homepage;

    public function setSourceId(int $source_id): void
    {
        $this->source_id = $source_id;
    }

    public function getSourceId(): ?int
    {
        return $this->source_id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setHomepage(string $homepage): void
    {
        $this->homepage = $homepage;
    }

    public function getHomepage(): string
    {
        return $this->homepage;
    }

}
