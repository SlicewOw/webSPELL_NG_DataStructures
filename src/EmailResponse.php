<?php

namespace webspell_ng;

use webspell_ng\Enums\EmailEnums;

class EmailResponse {

    /**
     * @var string $result
     */
    private $result = EmailEnums::MAIL_RESULT_FAIL;

    /**
     * @var ?string $error
     */
    private $error = null;

    /**
     * @var ?string $debug
     */
    private $debug = null;

    public function setResult(string $result): void
    {
        $this->result = $result;
    }

    public function isSuccess(): bool
    {
        return $this->result == EmailEnums::MAIL_RESULT_DONE;
    }

    public function isFailed(): bool
    {
        return $this->result == EmailEnums::MAIL_RESULT_FAIL;
    }

    public function setError(string $error): void
    {
        $this->error = $error;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setDebugMessage(string $debug): void
    {
        $this->debug = $debug;
    }

    public function getDebugMessage(): ?string
    {
        return $this->debug;
    }

}
