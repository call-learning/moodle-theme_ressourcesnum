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

namespace theme_ressourcesnum\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;
use theme_ressourcesnum\local\utils;

/**
 * Carousel renderable
 *
 * @package   theme_ressourcesnum
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class carousel_renderable implements renderable, templatable {

    /**
     * @var string $themename
     */
    protected $themename;

    /**
     * Constructor
     *
     * @param string $themename
     */
    public function __construct($themename) {
        $this->themename = $themename;
    }

    /**
     * Export for template
     *
     * @param renderer_base $output
     * @return array
     * @throws \dml_exception
     */
    public function export_for_template(renderer_base $output) {
        $carousel = [];
        $slidersimageurl = utils::get_slider_images_url($this->themename);
        $currentnumslide = get_config('theme_ressourcesnum', 'slidernumslides');
        foreach (range(1, $currentnumslide ?? 1) as $slidenum) {
            $item = new stdClass();
            $item->imageurl = $slidersimageurl[$slidenum];
            $item->text = get_config('theme_ressourcesnum', 'slidertext' . $slidenum);
            $item->overlaycolor = get_config('theme_ressourcesnum', 'sliderimageoverlaycolor' . $slidenum);
            $item->slidercontentright = get_config('theme_ressourcesnum', 'slidercontentright' . $slidenum);
            $color = get_config('theme_ressourcesnum', 'sliderimageoverlaycolor' . $slidenum);
            if (empty($color)) {
                $item->destcolor = 'rgba(100, 100, 100, 0.5)';
            } else {
                $hex = str_replace("#", "", $color);
                if (strlen($hex) == 3) {
                    $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                    $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                    $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
                } else {
                    $r = hexdec(substr($hex, 0, 2));
                    $g = hexdec(substr($hex, 2, 2));
                    $b = hexdec(substr($hex, 4, 2));
                }
                $item->destcolor = "rgba($r, $g, $b , 0.5)";
            }
            $item->index = $slidenum - 1;
            $carousel[] = $item;
        }
        return $carousel;
    }
}
