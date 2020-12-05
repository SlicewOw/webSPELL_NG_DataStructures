<?php

namespace webspell_ng;


class Clan {

	/**
	 * @var int $clan_id
	 */
	private $clan_id = null;

	/**
	 * @var string $clan_name
	 */
	private $clan_name = null;

	/**
	 * @var string $clan_tag
	 */
	private $clan_tag = null;

	/**
	 * @var string $clan_homepage
	 */
	private $clan_homepage = null;

	/**
	 * @var string $clan_logo
	 */
	private $clan_logo = null;

	public function setClanId(int $clan_id): void
	{
		$this->clan_id = $clan_id;
	}

	public function getClanId(): ?int
	{
		return $this->clan_id;
	}

	public function setClanName(string $clan_name): void
	{
		$this->clan_name = $clan_name;
	}

	public function getClanName(): ?string
	{
		return $this->clan_name;
	}

	public function setClanTag(string $clan_tag): void
	{
		$this->clan_tag = $clan_tag;
	}

	public function getClanTag(): ?string
	{
		return $this->clan_tag;
	}

	public function setHomepage(string $homepage): void
	{
		$this->clan_homepage = $homepage;
	}

	public function getHomepage(): ?string
	{
		return $this->clan_homepage;
	}

	public function setClanLogotype(string $clan_logotype): void
	{
		$this->clan_logo = $clan_logotype;
	}

	public function getClanLogotype(): ?string
	{
		return $this->clan_logo;
	}

}
