<?php

namespace webspell_ng;


class NewsRubric {

    /**
     * @var ?int $rubric_id
     */
    private $rubric_id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $image
     */
    private $image;

    /**
     * @var bool $active
     */
    private $active = true;

    public function setRubricId(int $rubric_id): void
    {
        $this->rubric_id = $rubric_id;
    }

    public function getRubricId(): ?int
    {
        return $this->rubric_id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setIsActive(bool $active): void
    {
        $this->active = $active;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

}
