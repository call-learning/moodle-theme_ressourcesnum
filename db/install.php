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
 * This file is executed right after the install.xml
 *
 *
 * @package   theme_ressourcesnum
 * @copyright 2021 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use theme_ressourcesnum\task\setup_task;

/**
 * Theme install
 */
function xmldb_theme_ressourcesnum_install() {
    // The theme installation is ran before local install.
    // So table "local_mcms_page" does not exist before we setup.
    $setuptask = new setup_task();
    // Queue it.
    \core\task\manager::queue_adhoc_task($setuptask);
}
