<?php

namespace webspell_ng;

use webspell_ng\Country;
use webspell_ng\Enums\UserEnums;

class User {

    /**
     * @var int $user_id
     */
    private $user_id;

    /**
     * @var string $user_name
     */
    private $user_name;

    /**
     * @var ?string $firstname
     */
    private $firstname;

    /**
     * @var ?string $lastname
     */
    private $lastname = null;

    /**
     * @var ?string $email
     */
    private $email = null;

    /**
     * @var string $sex
     */
    private $sex = UserEnums::SEXUALITY_MAN;

    /**
     * @var Country $country
     */
    private $country;

    /**
     * @var string $town
     */
    private $town = null;

    /**
     * @var \DateTime $birthday
     */
    private $birthday = null;

    /**
     * @var \DateTime $registration_date
     */
    private $registration_date = null;

    /**
     * @var ?\DateTime $first_login_date
     */
    private $first_login_date = null;

    /**
     * @var ?\DateTime $last_login_date
     */
    private $last_login_date = null;

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

    public function getLastname(): string
    {
        if (is_null($this->lastname)) {
            return "";
        }
        return $this->lastname;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setSex(string $sex): void
    {
        $allowed_options = array(
            UserEnums::SEXUALITY_MAN,
            UserEnums::SEXUALITY_WOMAN,
            UserEnums::SEXUALITY_DIVERS
        );
        if (in_array($sex, $allowed_options)) {
            $this->sex = $sex;
        }
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setTown(string $town): void
    {
        $this->town = $town;
    }

    public function getTown(): string
    {
        return $this->town;
    }

    public function setBirthday(\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    public function setRegistrationDate(\DateTime $registration_date): void
    {
        $this->registration_date = $registration_date;
    }

    public function getRegistrationDate(): \DateTime
    {
        if (is_null($this->registration_date)) {
            return new \DateTime("now");
        }
        return $this->registration_date;
    }

    public function setFirstLoginDate(\DateTime $first_login_date): void
    {
        $this->first_login_date = $first_login_date;
    }

    public function getFirstLoginDate(): ?\DateTime
    {
        return $this->first_login_date;
    }

    public function setLastLoginDate(\DateTime $last_login_date): void
    {
        $this->last_login_date = $last_login_date;
    }

    public function getLastLoginDate(): ?\DateTime
    {
        return $this->last_login_date;
    }

}
