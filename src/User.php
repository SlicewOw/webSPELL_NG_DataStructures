<?php

namespace webspell_ng;

class User {

    private int $user_id;
    private string $user_name;
    private ?string $firstname;
    private ?string $lastname;

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUsername(string $user_name): void
    {
        $this->user_name = $user_name;
    }

    public function getUsername(): ?string
    {
        return $this->user_name;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

}
