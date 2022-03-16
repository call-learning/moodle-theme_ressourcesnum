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
 * Core renderer
 *
 * @package   theme_ressourcesnum
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_ressourcesnum\output;

use theme_ressourcesnum\local\utils;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 *
 * @package   theme_ressourcesnum
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_clboost\output\core_renderer {
    /**
     * Return false (no compact logo)
     *
     * @param int $maxwidth The maximum width, or null when the maximum width does not matter.
     * @param int $maxheight The maximum height, or null when the maximum height does not matter.
     * @return \moodle_url|false
     */
    public function get_compact_logo_url($maxwidth = 300, $maxheight = 300) {
        return $this->get_logo_url($maxwidth, $maxheight); // No compact logo here.
    }

    /**
     * Get template additional informaiton
     *
     * @return \stdClass
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function get_template_additional_information() {
        global $SITE, $CFG;
        $additionalinfo = parent::get_template_additional_information();
        $sitenameval = explode(' ', trim($SITE->fullname));
        $additionalinfo->sitenamestyled = \html_writer::tag('strong', $sitenameval[0]) . ' ' .
            \html_writer::span(implode(' ', array_slice($sitenameval, 1)));
        $additionalinfo->addresses = utils::convert_addresses_config();
        $additionalinfo->legallinks = utils::convert_legallinks_config();
        return $additionalinfo;
    }

    /**
     * Menu
     *
     * @return mixed
     */
    public function mcms_menu() {
        $renderer = $this->page->get_renderer('local_mcms', 'menu');
        return $renderer->mcms_menu();
    }

    /**
     * We want to show the custom menus as a list of links in the footer on small screens.
     * Just return the menu object exported so we can render it differently.
     */
    public function mcms_menu_menu_flat() {
        $renderer = $this->page->get_renderer('local_mcms', 'menu');
        return $renderer->mcms_menu_menu_flat();
    }

    // Standard footer should be hidden in non development mode.

    /**
     * The standard tags (typically performance information and validation links,
     * if we are in developer debug mode) that should be output in the footer area
     * of the page. Designed to be called in theme layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_footer_html() {
        if (debugging()) {
            return parent::standard_footer_html();
        }
        return '';
    }

}
