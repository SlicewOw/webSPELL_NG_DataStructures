<?php

namespace webspell_ng\Handler;

use \webspell_ng\Settings;


class SettingsHandler
{

    public static function getSettings(): Settings
    {

        $settings = new Settings();

        // TODO: Implement missing functionality
        $settings->setHomepageTitle("Test Homepage Title");

        return $settings;

    }

}