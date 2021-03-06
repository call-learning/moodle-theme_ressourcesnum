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

    }

}