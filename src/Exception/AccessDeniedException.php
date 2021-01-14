<?php

namespace webspell_ng\Exception;

use webspell_ng\Language;


class AccessDeniedException extends \Exception
{

    /**
     * @var Language $language
     */
    private $language;

    public function __construct($message = '')
    {

        $this->language = new Language();
        $this->language->readModule("AccessDeniedException");

        parent::__construct($message);

    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->language->module['access_denied']} {$this->message} in {$this->file}({$this->line})\n" . "{$this->getTraceAsString()}\n";
    }

}
