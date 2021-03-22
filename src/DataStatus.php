<?php

namespace webspell_ng;


class DataStatus {

    /**
     * @var bool $is_active
     */
    private $is_active = false;

    /**
     * @var bool $is_deleted
     */
    private $is_deleted = false;

    /**
     * @var int $hits
     */
    private $hits = 0;

    /**
     * @var int $sort
     */
    private $sort = 1;

    public function setIsActive(bool $active): void
    {
        $this->is_active = $active;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function setIsDeleted(bool $deleted): void
    {
        $this->is_deleted = $deleted;
    }

    public function isDeleted(): bool
    {
        return $this->is_deleted;
    }

    public function setHits(int $hits): void
    {
        $this->hits = $hits;
    }

    public function getHits(): int
    {
        return $this->hits;
    }

    public function setSort(int $sort): void
    {
        if ($sort > 0) {
            $this->sort = $sort;
        }
    }

    public function getSort(): int
    {
        return $this->sort;
    }

}
