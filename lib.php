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
 * Theme plugin version definition.
 *
 * @package   theme_ressourcesnum
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_ressourcesnum\local\utils;

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 * @throws coding_exception
 */
function theme_ressourcesnum_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    // Specific use case here: we need to have an itemid for the settings and Moodle is usually
    // using this as a revision number, so we have to come up with a specific way to send the file here.
    if ($filearea == utils::SLIDER_FILEAREA) {
        $themename = 'ressourcesnum';
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }

        $syscontext = context_system::instance();
        $component = 'theme_' . $themename;
        $lifetime = 60 * 60 * 24 * 60;
        $fs = get_file_storage();
        $itemid = array_shift($args);
        $relativepath = implode('/', $args);
        $fullpath = "/{$syscontext->id}/{$component}/{$filearea}/$itemid/{$relativepath}";
        $fullpath = rtrim($fullpath, '/');
        if ($file = $fs->get_file_by_hash(sha1($fullpath))) {
            send_stored_file($file, $lifetime, 0, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
    } else {
        return theme_clboost\local\utils::generic_pluginfile('ressourcesnum', $course, $cm, $context, $filearea, $args,
            $forcedownload, $options);
    }

}
