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
 * All constant in one place
 *
 * @package   theme_ressourcesnum
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_ressourcesnum\local;

defined('MOODLE_INTERNAL') || die;

/**
 * Theme constants. In one place.
 *
 * @package   theme_ressourcesnum
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class utils {

    /**
     * Converts the addresses config string into an array of information that can be
     * then added to the footer via the "footer_address" mustache template.
     * Structure:
     *     addresslabel|address|tel
     *
     * Example structure:
     *     Campus agronomique|89 Avenue de l’Europe, 63370 Lempdes|04 73 98 13 13
     *
     * Converted into: an object with title, address, tel fields.
     *
     * @return array
     * @throws \dml_exception
     */
    public static function convert_addresses_config() {
        $configtext = get_config('theme_ressourcesnum', 'addresses');

        $lineparser = function ($setting, $index, &$currentobject) {
            if (!empty($setting[$index])) {
                $val = trim($setting[$index]);
                switch ($index) {
                    case 0:
                        $currentobject->title = $val;
                        break;
                    case 1:
                        $currentobject->address = $val;
                        break;
                    case 2:
                        $currentobject->tel = $val;
                        break;
                }
            }
        };
        return \theme_clboost\local\utils::convert_from_config($configtext, $lineparser);
    }
}