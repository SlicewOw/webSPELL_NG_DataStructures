<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

namespace webspell_ng;

use webspell_ng\Language;

class Template
{

    /**
     * @var string $moduleName
     */
    private $moduleName;

    /**
     * @var bool $moduleName
     */
    private $isAdminLanguage;

    /**
     * @var string $defaultFolder
     */
    private $defaultFolder = __DIR__ . '/../templates';

    /**
     * @var string $rootFolder
     */
    private $rootFolder;

    /**
     * @var Language $language
     */
    private $language;

    /**
    * @param string $rootFolder base folder where the template files are located
    */
    public function __construct(string $module_name, bool $is_admin_language=false, string $language="en", $rootFolder = "templates")
    {

        $this->moduleName = $module_name;
        $this->isAdminLanguage = $is_admin_language;
        $this->rootFolder = $rootFolder;

        $this->language = new Language();
        $this->language->setLanguage($language);
        $this->language->readModule($this->moduleName, false, $this->isAdminLanguage);

    }

    /**
    * returns the content of a template file
    *
    * @param string $template name of the template
    *
    * @return string content of the template
    */
    private function loadFile($template): string
    {

        $file = $this->rootFolder . "/" . $template . ".html";
        if (!file_exists($file)) {
            $file = $this->defaultFolder . "/error.html";
        }

        return file_get_contents($file);

    }

    /**
    * Replace all keys of data with its values in the string
    * Longer keys are replaced first (users before user)
    *
    * @param string $template
    * @param array  $data
    *
    * @return string
    */
    private function replace($template, $data = array()): string
    {
        return strtr($template, $data);
    }

    /**
    * Replace a single template with one set of data and translate all language keys
    *
    * @param string $template name of a template
    * @param array  $data data which gets replaced
    *
    * @return string
    */
    public function replaceTemplate($template, $data = array()): string
    {
        $templateString = $this->loadFile($template);
        $templateTranslated = $this->replaceLanguage($templateString);
        return $this->replace($templateTranslated, $data);
    }

    /**
    * Replaces all language variables which are available
    *
    * @param string $template content of a template
    *
    * @return string
    */
    private function replaceLanguage(string $template): string
    {
        return $this->replace(
            $template,
            $this->language->getTranslationTable()
        );
    }

}
