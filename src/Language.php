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

use Noodlehaus\Config;

class Language
{

    /**
     * @var string $language
     */
    public $language = 'en';

    /**
     * @var string $language_path
     */
    private $language_path = 'languages/';

    /**
     * @var string $language_path
     */
    private $default_language_path = __DIR__ . '/../languages/';

    /**
     * @var array<string> $module
     */
    public $module = array();

    /**
     * @var array<string> $module_files
     */
    public $module_files = array();

    /**
     * @var array<string> $module
     */
    private $module_array = array();

    public function setLanguage(string $to, bool $admin=false): bool
    {

        if ($admin) {
            $this->language_path = '../'.$this->language_path;
        }

        if (in_array($to, $this->getLanguages())) {
            $this->language = $to;
            $this->language_path = 'languages/';
            return true;
        } else {
            return false;
        }

    }

    /**
     * @return array<string>
     */
    private function getLanguages(): array
    {
        $languages = array();
        foreach (new \DirectoryIterator($this->default_language_path) as $fileInfo) {
            if ($fileInfo->isDot() === false && $fileInfo->isDir() === true) {
                $languages[] = $fileInfo->getFilename();
            }
        }
        return $languages;
    }

    public function getRootPath(): string
    {
        return $this->language_path;
    }

    public function readModule(string $module, bool $add=false, bool $admin=false, bool $pluginpath=false, bool $installpath=false): bool
    {

        $module = str_replace(array('\\', '/', '.'), '', $module);

        if ($admin && !$pluginpath) {
            $langFolder = '../' . $this->language_path;
            $folderPath = '%s%s/admin/%s.json';
        } else if ($admin && $pluginpath) {
            $langFolder = '../' . $pluginpath . $this->language_path;
            $folderPath = '%s%s/admin/%s.json';
        } else if ($pluginpath) {
            $langFolder = $pluginpath . $this->language_path;
            $folderPath = '%s%s/%s.json';
        } else if ($installpath) {
            $langFolder = '../install/' . $this->language_path;
            $folderPath = '%s%s/%s.json';
        } else if (!$admin && is_dir('../languages/')) {
            $langFolder = '../' . $this->language_path;
            $folderPath = '%s%s/%s.json';
        } else {
            $langFolder = $this->default_language_path;
            $folderPath = '%s%s/%s.json';
        }

        $module_file = $this->getModuleFile($folderPath, $langFolder, $module);

        if (is_null($module_file)) {
            return false;
        }

        $this->module_files[] = $module;

        $language_array = Config::load(
            $module_file
        );

        if (!$add) {
            $this->module = array();
        }

        foreach ($language_array as $key => $val) {
            $this->module[ $key ] = $val;
        }

        $formvalidation = 'formvalidation';
        if (!in_array($formvalidation, $this->module_array) && ($module != $formvalidation)) {
            $this->readModule($formvalidation, true, false, false);
        }

        return true;
    }

    private function getModuleFile(string $folderPath, string $langFolder, string $module): ?string
    {

        $languageFallbackTable = array();
        if (!empty($this->language)) {
            $languageFallbackTable[] = $this->language;
        }

        if (!in_array('en', $languageFallbackTable)) {
            $languageFallbackTable[] = 'en';
        }

        foreach ($languageFallbackTable as $folder) {

            if (empty($folder)) {
                continue;
            }

            $path = sprintf($folderPath, $langFolder, $folder, $module);
            if (file_exists($path)) {
                return $path;
            }

        }

        return null;

    }

    public function replace(string $template): string
    {
        foreach ($this->module as $key => $val) {
            $template = str_replace('%' . $key . '%', $val, $template);
        }
        return $template;
    }

    /**
     * @return array<string>
     */
    public function getTranslationTable(): array
    {
        $map = array();
        foreach ($this->module as $key => $val) {
            $newKey = '%' . $key . '%';
            $map[ $newKey ] = $val;
        }
        return $map;
    }

}
