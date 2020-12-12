<?php

namespace webspell_ng;


class ContactMailReceiver {

    /**
     * @var int $receiver_id
     */
    private $receiver_id = null;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var int $sort
     */
    private $sort = 1;

    public function setReceiverId(int $receiver_id): void
    {
        $this->receiver_id = $receiver_id;
    }

    public function getReceiverId(): ?int
    {
        return $this->receiver_id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

}
