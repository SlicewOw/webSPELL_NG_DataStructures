<?php

namespace webspell_ng\Exception;

class AccessDeniedException extends \Exception
{

    /**
     * @codeCoverageIgnore
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}
