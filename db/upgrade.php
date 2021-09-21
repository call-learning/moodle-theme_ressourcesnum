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
 * Theme upgrade - Upgrade plugin tasks
 *
 * @package   theme_ressourcesnum
 * @copyright 2021 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_ressourcesnum\setup;

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade steps for this plugin
 *
 * @param int $oldversion the version we are upgrading from
 * @return void
 */
function xmldb_theme_ressourcesnum_upgrade($oldversion) {
    if ($oldversion < 2021091401) {
        setup::install_update();
        upgrade_plugin_savepoint(true, 2021091401, 'theme', 'ressourcesnum');
    }
    if ($oldversion < 2021091402) {
        setup::install_update();
        upgrade_plugin_savepoint(true, 2021091402, 'theme', 'ressourcesnum');
    }
    return true;
}
