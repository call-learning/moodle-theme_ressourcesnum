<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings
 *
 * @package   theme_ressourcesnum
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_ressourcesnum\local;

use admin_setting_configcolourpicker;
use admin_setting_configstoredfile;
use admin_setting_configtext;
use admin_setting_scsscode;
use admin_settingpage;
use theme_boost_admin_settingspage_tabs;

defined('MOODLE_INTERNAL') || die;

/**
 * Theme settings. In one place.
 *
 * @package   theme_ressourcesnum
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class settings extends \theme_clboost\local\settings {

    /**
     * Additional settings
     *
     * This is intended to be overriden in the subtheme to add new pages for example.
     *
     * @param admin_settingpage $settings
     */
    protected static function additional_settings(admin_settingpage &$settings) {
        global $CFG;
        // Advanced settings.
        $page = new admin_settingpage('additionalinfo', static::get_string('additionalinfo',
            'theme_ressourcesnum'));

        $setting = new \admin_setting_configtextarea('theme_ressourcesnum/addresses',
            static::get_string('addresses', 'theme_ressourcesnum'),
            static::get_string('addresses_desc', 'theme_ressourcesnum'),
            "HESAM Université|15, rue Soufflot<br>75005 Pars|01 87 39 20 20\n",
            PARAM_RAW);
        $page->add($setting);

        $legallinks = [];
        $legallinks[] = 'mentionlegales|'
            .$CFG->wwwroot . '/admin/tool/policy/view.php?policyid=1';
        $legallinks[] = 'cookiesrgpd|'
            .$CFG->wwwroot . '/admin/tool/policy/view.php?policyid=1';
        $legallinks[] = 'copyright';

        $setting = new \admin_setting_configtextarea('theme_ressourcesnum/legallinks',
            static::get_string('legallinks', 'theme_ressourcesnum'),
            static::get_string('legallinks_desc', 'theme_ressourcesnum'),
            join("\n", $legallinks),
            PARAM_RAW);
        $page->add($setting);
        $settings->add($page);
        // Advanced settings.
        $page = new admin_settingpage('slider', static::get_string('slider',
            'theme_ressourcesnum'));


        $choices = array_combine(range(1, self::MAX_SLIDER_SLIDES),
            range(1, self::MAX_SLIDER_SLIDES));
        $setting = new \admin_setting_configselect(
            'theme_ressourcesnum/slidernumslides',
            static::get_string('slidernumslides', 'theme_ressourcesnum'),
            static::get_string('slidernumslides_desc', 'theme_ressourcesnum'),
            1,
            $choices
        );
        $page->add($setting);
        $currentnumslide = get_config('theme_ressourcesnum', 'slidernumslides');
        foreach(range(1,$currentnumslide ?? 1) as $slidenum) {
            $setting = new \admin_setting_confightmleditor('theme_ressourcesnum/slidertext'.$slidenum,
                static::get_string('slidertext', 'theme_ressourcesnum', $slidenum),
                static::get_string('slidertext_desc', 'theme_ressourcesnum', $slidenum),
                '',
                PARAM_RAW);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
            $setting = new admin_setting_configstoredfile('theme_ressourcesnum/sliderimage'.$slidenum,
                static::get_string('sliderimage', 'theme_ressourcesnum', $slidenum),
                static::get_string('sliderimage_desc', 'theme_ressourcesnum', $slidenum),
                utils::SLIDER_FILEAREA,
                $slidenum
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
            $setting = new \admin_setting_configcheckbox('theme_ressourcesnum/slidercontentright'.$slidenum,
                static::get_string('slidercontentright', 'theme_ressourcesnum', $slidenum),
                static::get_string('slidercontentright_desc', 'theme_ressourcesnum', $slidenum),
                false
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
            $setting = new admin_setting_configcolourpicker('theme_ressourcesnum/overlaycolor'.$slidenum,
                static::get_string('sliderimageoverlaycolor', 'theme_ressourcesnum', $slidenum),
                static::get_string('sliderimageoverlaycolor_desc', 'theme_ressourcesnum', $slidenum),
                "#fff");
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
        }
        $settings->add($page);
    }

    const MAX_SLIDER_SLIDES = 10;
}