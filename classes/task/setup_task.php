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

namespace theme_ressourcesnum\task;

use theme_ressourcesnum\setup;

defined('MOODLE_INTERNAL') || die();
/**
 * Class setup task
 *
 * Utility setup class, ran after install.
 *
 * @package   theme_ressourcesnum
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class setup_task extends \core\task\adhoc_task {
    /**
     * Execute install process.
     */
    public function execute() {
        setup::install_update();
    }
}
