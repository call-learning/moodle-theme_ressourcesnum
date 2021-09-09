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
 * Setup routine for theme
 *
 * @package   setup.php
 * @copyright 2021 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_ressourcesnum;

use context_block;
use context_system;
use dml_exception;
use file_exception;
use local_mcms\page;
use local_mcms\page_role;
use local_mcms\page_utils;
use moodle_page;
use stored_file_creation_exception;
use theme_ressourcesnum\local\utils;

defined('MOODLE_INTERNAL') || die();

/**
 * Class setup
 *
 * Utility setup class.
 *
 * @copyright   2021 Laurent David - CALL Learning <laurent@call-learning.fr>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class setup {

    const IDENTIFIER_COMPETENCES = [
        'blockname' => 'html',
        'showinsubcontexts' => '0',
        'defaultregion' => 'content',
        'defaultweight' => '4',
        'configdata' => [
            'text' => '<p dir="ltr" style="text-align: left;">Il vous suffit pour cela de répondre à des questions en réfléchissant à vos expériences récentes, dans un cadre professionnel, personnel ou informel. Vous serez en mesure ainsi d’évaluer votre niveau actuel d’acquisition. Les questionnaires mis à disposition par HESAM Université vous accompagnent tout au long de votre parcours, selon votre niveau et à votre rythme. Ces questionnaires prennent environ 10 minutes.<br></p><h4 style="text-align: left;">Bac + 1</h4><p>Destinées aux élèves des cursus Bac+ 1, ces ressources ont pour but d’accompagner la transition vers l’enseignement supérieur.&nbsp;<br></p><p class="text-center"><a href="/">Test sur les compétences transverses</a></p><p class="text-center"><a href="/">Test sur les compétences informationnelles</a></p><p class="text-center"></p><h4>Bachelor / Bac + 3</h4><p>Conçues spécifiquement à destination des élèves des cursus Bac +3 (Bachelor), ces ressources accompagnent la professionnalisation.<br></p><p></p><p class="text-center"><a href="http://soka-hesam.local/">Test sur les compétences transverses</a></p><p class="text-center"><a href="http://soka-hesam.local/">Test sur les compétences informationnelles</a></p><h4>Ingénieurs et élèves ingénieurs</h4><p>Pour ceux et celles qui se préparent au métier d’ingénieur, ces ressources spécifiques complémentent votre formation technique.<br></p><p></p><p class="text-center"><a href="http://soka-hesam.local/">Test sur les compétences transverses</a></p><p class="text-center"><a href="http://soka-hesam.local/">Test sur les compétences informationnelles</a></p><br><br><p></p><br><p></p>',
            'title' => 'Comment identifier les compétences transverses ?',
            'classes' => 'ressourcesnum-text-container',
            'format' => '1',
        ]
    ];
    const FOUR_COMPS_BLOCK_DEF = [
        'blockname' => 'html',
        'showinsubcontexts' => '0',
        'defaultregion' => 'content',
        'defaultweight' => '2',
        'configdata' => [
            'text' => '<p dir="ltr" style="text-align: left;">Elles concernent la mise en place de conditions nécessaires pour le bon déroulement d’un projet du point de vue des relations sociales et des méthodes de travail.<br></p><p dir="ltr" style="text-align: left;"><br>
</p><table>
<caption></caption>
<thead>
<tr>
<th scope="col"><img src="/theme/ressourcesnum/pix/pages/sideimage1.png" alt="" width="288" height="235" role="presentation" class="img-responsive atto_image_button_left"></th>
<th scope="col">Compétences sociales<br><span style="font-weight: normal;">Quelle est votre capacité à travailler avec d’autres personnes dans un contexte professionnel où l’interaction est importante ?</span><br></th>
</tr>
</thead>
<tbody>
<tr>
<td><img src="/theme/ressourcesnum/pix/pages/sideimage2.png" alt="" width="288" height="235" role="presentation" class="img-responsive atto_image_button_left"></td>
<td><span><strong>Compétences personnelles</strong><br><span>Quelles aptitudes personnelles utiles dans un contexte professionnel avez-vous développées ?</span><br></span></td>
</tr>
<tr>
<td><img src="/theme/ressourcesnum/pix/pages/sideimage3.png" alt="" width="288" height="235" role="presentation" class="img-responsive atto_image_button_text-bottom"></td>
<td><span><strong>Compétences méthodologiques</strong><br><span>Par quelles démarches réussissez-vous à organiser vos activités professionnelles ?</span><br></span></td>
</tr>
<tr>
<td><img src="/theme/ressourcesnum/pix/pages/sideimage4.png" alt="" width="288" height="242" role="presentation" class="img-responsive atto_image_button_text-bottom"></td>
<td><span><strong>Compétences en conduite de projets</strong><br><span>Par quelles démarches réussissez-vous à organiser vos activités professionnelles ?</span><br></span></td>
</tr>
</tbody>
</table>
<br><br><p></p>',
            'title' => 'Competences manageriales et de conduite de projet',
            'classes' => 'ressourcesnum-text-container',
            'format' => '1',
        ]
    ];
    const MCMS_PAGES = [
        [
            'title' => 'Connaissez-vous vos forces et vos faiblesses? ',
            'shortname' => 'Soft Skills',
            'description' => '<p dir="ltr" style="text-align:left;">Les connaissances acquises pendant vos études ne constituent pas votre seul atout. Lors d’un recrutement, par exemple, vous pouvez vous distinguer face à d’autres candidats en mettant en valeur vos compétences transverses.<br></p><p dir="ltr" style="text-align:left;">',
            'descriptionformat' => '1',
            'idnumber' => 'vos-forces-et-faiblesses',
            'parent' => '0',
            'ctalink' => '',
            'parentmenu' => 'top',
            'menusortorder' => '2',
            'style' => 'hesam_blue',
            'roles' => [
                'guest',
                'user',
                'frontpage'
            ],
            'image' => '/theme/ressourcesnum/pix/pages/iceberg.jpg',
            'blocks' => [
                [
                    'blockname' => 'html',
                    'showinsubcontexts' => '0',
                    'defaultregion' => 'content',
                    'defaultweight' => '1',
                    'configdata' => [
                        'text' => '<p dir="ltr" style="text-align: left;">Cette dénomination permet de mettre en lumière les compétences complémentaires aux compétences considérées comme « techniques », objectives et mesurables, propres à un métier. Souvent, l’ensemble de compétences transverses contemplent les dimensions interpersonnelles et sociales nécessaires à l’activité professionnelle. Elles s’acquièrent dans des contextes professionnels mais aussi personnels et informels. Ces compétences prennent leur sens dans le cadre d’un métier et en référence à des situations professionnelles concrètes.<br></p>',
                        'title' => 'Les compétences transverses, c’est quoi ?',
                        'classes' => '',
                        'format' => '1',
                    ]
                ],
                self::FOUR_COMPS_BLOCK_DEF,
                [
                    'blockname' => 'html',
                    'showinsubcontexts' => '0',
                    'defaultregion' => 'content',
                    'defaultweight' => '3',
                    'configdata' => [
                        'title' => 'Competences informationnelles',
                        'classes' => 'ressourcesnum-text-container',
                        'format' => '1',
                        'text' => '<p dir="ltr" style="text-align: left;">Elles concernent l’identification des besoins, la recherche, l’analyse critique et l’organisation des informations trouvées.&nbsp;<br></p><p dir="ltr" style="text-align: left;"><br>
</p><table>
<caption></caption>
<thead>
<tr>
<th scope="col"><img src="/theme/ressourcesnum/pix/pages/sideimage5.png" alt="" width="288" height="230" role="presentation" class="img-responsive atto_image_button_text-bottom"></th>
<th scope="col"><span style="font-weight: normal;">Par quelles démarches réussissez-vous à organiser traiter de l’information ?</span></th>
</tr>
</thead>
<tbody>
</tbody>
</table>
<br><br><p></p>',
                    ]
                ],
                self::IDENTIFIER_COMPETENCES,
                [
                    'blockname' => 'html',
                    'showinsubcontexts' => '0',
                    'defaultregion' => 'content',
                    'defaultweight' => '5',
                    'configdata' => [
                        'text' => '<p dir="ltr" style="text-align: left;">Une fois le questionnaire fini, des propositions de ressources s’affichent. Celles-ci vous permettront de savoir plus sur les éléments à approfondir.<br></p>',
                        'title' => 'Comment les ameliorer ?',
                        'classes' => 'ressourcesnum-text-container',
                        'format' => '1',
                    ]
                ]
            ]
        ],
        [
            'title' => 'Testez vous',
            'shortname' => 'Testez vous',
            'description' => '<p dir="ltr" style="text-align:left;"></p><h3>Un atout&nbsp; pour son avenir</h3><br>',
            'descriptionformat' => '1',
            'idnumber' => 'testez-vous',
            'parent' => '0',
            'ctalink' => '',
            'style' => 'hesam',
            'parentmenu' => 'top',
            'menusortorder' => '3',
            'roles' => [
                'guest',
                'user'
            ],
            'image' => '/theme/ressourcesnum/pix/pages/generic.jpg',
            'blocks' => [
                self::IDENTIFIER_COMPETENCES
            ]
        ],
        [
            'title' => 'BAC+1 - Competences Informationnelles',
            'shortname' => 'BAC+1',
            'description' => '<p dir="ltr" style="text-align:left;"></p><h3>Un atout&nbsp; pour son avenir</h3><br>',
            'descriptionformat' => '1',
            'idnumber' => 'test-bac-1',
            'parent' => 'testez-vous',
            'ctalink' => '',
            'style' => 'hesam',
            'parentmenu' => '',
            'menusortorder' => '1',
            'roles' => [
                'guest',
                'user'
            ],
            'image' => '/theme/ressourcesnum/pix/pages/testez-vous.jpg',
            'blocks' => [
                  [
                     'blockname' => 'html',
                     'showinsubcontexts' => '0',
                     'defaultregion' => 'content',
                     'defaultweight' => '4',
                     'configdata' => [
                         'text' => '<p dir="ltr" style="text-align: left;">Il vous suffit pour cela de répondre à des questions en réfléchissant à vos expériences récentes, dans un cadre professionnel, personnel ou informel. Vous serez en mesure ainsi d’évaluer votre niveau actuel d’acquisition. Les questionnaires mis à disposition par HESAM Université vous accompagnent tout au long de votre parcours, selon votre niveau et à votre rythme. Ces questionnaires prennent environ 10 minutes.<br></p><h4 style="text-align: left;">Bac + 1</h4><p>Destinées aux élèves des cursus Bac+ 1, ces ressources ont pour but d’accompagner la transition vers l’enseignement supérieur.&nbsp;<br></p><p class="text-center"><a href="/">Test sur les compétences transverses</a></p><p class="text-center"><a href="/">Test sur les compétences informationnelles</a></p>',
                         'title' => 'Comment identifier les compétences transverses ?',
                         'classes' => 'ressourcesnum-text-container',
                         'format' => '1',
                     ]
                 ]
            ]
        ],
        [
            'title' => 'Bachelor (Bac + 3) - Competences Informationnelles',
            'shortname' => 'BACHELOR',
            'description' => '<p dir="ltr" style="text-align:left;"></p><h3>Un atout&nbsp; pour son avenir</h3><br>',
            'descriptionformat' => '1',
            'idnumber' => 'test-bachelor',
            'parent' => 'testez-vous',
            'ctalink' => '',
            'style' => 'hesam',
            'parentmenu' => '',
            'menusortorder' => '2',
            'roles' => [
                'guest',
                'user'
            ],
            'image' => '/theme/ressourcesnum/pix/pages/testez-vous.jpg',
            'blocks' => [
                [
                    'blockname' => 'html',
                    'showinsubcontexts' => '0',
                    'defaultregion' => 'content',
                    'defaultweight' => '4',
                    'configdata' => [
                        'text' => '<p dir="ltr" style="text-align: left;">Il vous suffit pour cela de répondre à des questions en réfléchissant à vos expériences récentes, dans un cadre professionnel, personnel ou informel. Vous serez en mesure ainsi d’évaluer votre niveau actuel d’acquisition. Les questionnaires mis à disposition par HESAM Université vous accompagnent tout au long de votre parcours, selon votre niveau et à votre rythme. Ces questionnaires prennent environ 10 minutes.<br></p><p class="text-center"></p><h4>Bachelor / Bac + 3</h4><p>Conçues spécifiquement à destination des élèves des cursus Bac +3 (Bachelor), ces ressources accompagnent la professionnalisation.<br></p><p></p><p class="text-center"><a href="http://soka-hesam.local/">Test sur les compétences transverses</a></p><p class="text-center"><a href="http://soka-hesam.local/">Test sur les compétences informationnelles</a></p>',
                        'title' => 'Comment identifier les compétences transverses ?',
                        'classes' => 'ressourcesnum-text-container',
                        'format' => '1',
                    ]
                ]
            ]
        ],
        [
            'title' => 'Elèves Ingénieurs - Competences Informationnelles',
            'shortname' => 'Eleves Ingénieurs',
            'description' => '<p dir="ltr" style="text-align:left;"></p><h3>Un atout&nbsp; pour son avenir</h3><br>',
            'descriptionformat' => '1',
            'idnumber' => 'test-eleve-ingenieurs',
            'parent' => 'testez-vous',
            'ctalink' => '',
            'style' => 'hesam',
            'parentmenu' => '',
            'menusortorder' => '3',
            'roles' => [
                'guest',
                'user'
            ],
            'image' => '/theme/ressourcesnum/pix/pages/testez-vous.jpg',
            'blocks' => [
                [
                    'blockname' => 'html',
                    'showinsubcontexts' => '0',
                    'defaultregion' => 'content',
                    'defaultweight' => '4',
                    'configdata' => [
                        'text' => '<p dir="ltr" style="text-align: left;">Il vous suffit pour cela de répondre à des questions en réfléchissant à vos expériences récentes, dans un cadre professionnel, personnel ou informel. Vous serez en mesure ainsi d’évaluer votre niveau actuel d’acquisition. Les questionnaires mis à disposition par HESAM Université vous accompagnent tout au long de votre parcours, selon votre niveau et à votre rythme. Ces questionnaires prennent environ 10 minutes.<br></p><h4>Ingénieurs et élèves ingénieurs</h4><p>Pour ceux et celles qui se préparent au métier d’ingénieur, ces ressources spécifiques complémentent votre formation technique.<br></p><p></p><p class="text-center"><a href="http://soka-hesam.local/">Test sur les compétences transverses</a></p><p class="text-center"><a href="http://soka-hesam.local/">Test sur les compétences informationnelles</a></p><br><br><p></p><br><p></p>',
                        'title' => 'Comment identifier les compétences transverses ?',
                        'classes' => 'ressourcesnum-text-container',
                        'format' => '1',
                    ]
                ]
            ]
        ]
    ];

    /**
     * The defaults settings
     */
    const DEFAULT_SETTINGS = [
        'moodle' => [
            'country' => 'FR',
            'timezone' => 'Europe/Paris',
            'block_html_allowcssclasses' => true,
            'defaulthomepage' => HOMEPAGE_MY,
            'summary' => '<h3>Pourquoi des ressources en ligne ?</h3>
<p>HESAM Université vous accompagne tout au long de votre cursus en vous proposant des ressources pédagogiques accessibles à tout moment. Ces ressources vous permettront de compléter votre formation à votre rythme et selon vos besoins.&nbsp;</p>
<hr>
<h3>L’autoformation, c’est quoi ?</h3>
<h4>Une compétence essentielle</h4>
<p>Même si nous n’en sommes pas toujours conscients, nous apprenons par nous-mêmes en permanence et en dehors des espaces de formation formels : essayer une nouvelle recette de cuisine, consulter un tutoriel pour résoudre un dysfonctionnement de notre aspirateur,
    manipuler des applications informatiques pour communiquer avec nos amis… L’apprentissage est une activité essentielle de notre vie quotidienne.&nbsp;<br></p>
<h4>Un atout&nbsp; pour son avenir</h4>
<p>HESAM Université souhaite vous aider à renforcer vos compétences d’autoformation en créant des ponts entre vos compétences d’autoformation acquises dans le cadre de votre vie quotidienne et votre cursus de formation. L’articulation de ces deux dynamiques,
    formation formelle et autoformation vous aidera à devenir plus efficace dans l’organisation de vos apprentissages, ce qui constitue un atout pour votre avenir.&nbsp;<br></p>
<h3>Les ressources proposées et à venir</h3>
<p>Actuellement, la plateforme Ressources Numériques propose des questionnaires portant notamment sur les compétences transverses, appelées habituellement « soft » ou « molles », pour les distinguer des compétences considérées comme techniques ou cœur de
    métier. Les compétences dites « soft » ne sont pas pour autant moins nécessaires à l’exercice d’un métier !&nbsp;<br></p>
<p>Nous développons notre offre pour qu’elle s’adapte au mieux à vos besoins de formation.<br></p>
<p style="text-align: center;"><a class="highlight" href="/">En savoir plus sur les compétences transversales</a></p>
<p>Du Bac +1, en passant par le Bac + 3 jusqu’au niveau ingénieur, les ressources d’HESAM Université vous permettront de vous former selon votre niveau.<br></p>
<h4>Bac + 1</h4>
<p>Destinées aux élèves des cursus Bac+ 1, ces ressources ont pour but d’accompagner la transition vers l’enseignement supérieur.&nbsp;<br></p>
<p style="text-align: center;"><a href="/">Test sur les compétences transversales</a></p>
<p style="text-align: center;"><a href="/">Test sur les compétences&nbsp;informationnelles</a><br></p>
<p></p>
<h4>Bachelor / Bac + 3</h4>
<p>Destinées aux élèves des cursus Bac+ 1, ces ressources ont pour but d’accompagner la transition vers l’enseignement supérieur.&nbsp;<br></p>
<p style="text-align: center;"><a href="http://soka-hesam.local/">Test sur les compétences transversales</a></p>
<p style="text-align: center;"><a href="http://soka-hesam.local/">Test sur les compétences&nbsp;informationnelles</a></p>
<h4>Ingénieurs et élèves ingénieurs</h4>
<p>Destinées aux élèves des cursus Bac+ 1, ces ressources ont pour but d’accompagner la transition vers l’enseignement supérieur.&nbsp;<br></p>
<p style="text-align: center;"><a href="http://soka-hesam.local/">Test sur les compétences transversales</a></p>
<p style="text-align: center;"><a href="http://soka-hesam.local/">Test sur les compétences&nbsp;informationnelles</a><br></p>
<p><br></p><br>
<p></p>'
        ]
    ];

    /**
     * Frontpage slider
     */
    const FRONT_PAGE_SLIDER = array(
        array(
            'text' => '<h3 style="text-align: left;">Resources numériques</h3>
<p dir="ltr" style="text-align: left;">La plateforme Ressources Numériques de HESAM Université met à votre disposition des questionnaires vous permettant d’explorer vos compétences, de compléter votre formation par des ressources pédagogiques et de faire reconnaitre vos acquis par le biais
    de badges numériques.<br></p>
<p dir="ltr" style="text-align: left;"><a href="/">En savoir plus</a></p>',
            'overlaycolor' => '#fff',
            'image' => '/theme/ressourcesnum/pix/slider/slider1.jpg',
        ),
        array(
            'text' => '<h3 style="text-align: left;">Ingénieurs : vous connaissez-vous vraiment ?</h3>
<p dir="ltr" style="text-align: left;">Découvrez votre face cachée grâce au test Soft Skills proposé par HESAM Université.<br></p>
<a href="/">Découvrez-vous !</a><br>
<a href="/">En savoir plus</a><br>',
            'overlaycolor' => '#000',
            'slidercontentright' => true,
            'image' => '/theme/ressourcesnum/pix/slider/slider2.jpg',
        ),
        array(
            'text' => '<h3 style="text-align: left;">Bac + 1</h3>
<p dir="ltr" style="text-align: left;">Identifiez vos points forts et vos points faibles grâce à nos tests dédiés aux étudiants Bac+1.<br></p>
<a href="/">Testez vos compétences transverses</a><br>
<a href="/">Testez vos compétences informationelles</a><br>',
            'overlaycolor' => '#fff',
            'image' => '/theme/ressourcesnum/pix/slider/slider3.jpg',
        ),
        array(
            'text' => '<h3 style="text-align: left;">Bachelor</h3>
<p dir="ltr" style="text-align: left;">Identifiez vos points forts et vos points faibles grâce à nos tests dédiés aux étudiants Bac+3.<br></p>
<a href="/">Testez vos compétences transverses</a><br>
<a href="/">Testez vos compétences informationelles</a><br>',
            'overlaycolor' => '#000223',
            'slidercontentright' => true,
            'image' => '/theme/ressourcesnum/pix/slider/slider4.jpg',
        ),
    );

    /**
     * Install updates
     */
    public static function install_update() {

        static::setup_config_values();

        static::setup_slider();

        static::setup_page_mcms();
    }


    // @codingStandardsIgnoreStart
    // phpcs:disable

    /**
     * Setup config values
     */
    public static function setup_config_values() {
        foreach (self::DEFAULT_SETTINGS as $pluginname => $plugindefs) {
            $plugin = $pluginname;
            if ($pluginname === 'moodle') {
                $plugin = null;
            }
            foreach ($plugindefs as $key => $value) {
                $configvalue = get_config($plugin, $key);
                if ($configvalue != $value) {
                    set_config($key, $value, $plugin);
                }
            }
        }
    }

    /**
     * Create a MCMS page through menus
     */
    public static function setup_page_mcms() {
        global $DB, $PAGE;
        $oldpage = $PAGE;
        $context = context_system::instance();
        foreach (self::MCMS_PAGES as $mcmspage) {
            $blocks = $mcmspage['blocks'] ?? [];
            if (isset($mcmspage['blocks'])) {
                unset($mcmspage['blocks']);
            }
            $roles = $mcmspage['roles'] ?? [];
            if (isset($mcmspage['roles'])) {
                unset($mcmspage['roles']);
            }
            $image = $mcmspage['image'] ?? null;
            if (!empty($mcmspage['image'])) {
                unset($mcmspage['image']);
            }

            if (!is_int($mcmspage['parent'])) {
                global $DB;
                $parentpage = page::get_record(array('idnumber' => $mcmspage['parent']));
                $mcmspage['parent'] = empty($parentpage) ? 0 : $parentpage->get('id');
            }
            $existingpage = page::get_record(array('idnumber' => $mcmspage['idnumber']));
            $existingpageid = empty($existingpage) ? 0 : $existingpage->get('id');

            if ($existingpageid) {
                $page = new page($existingpageid);
                $page->from_record((object) $mcmspage);
                $page->save();
            } else {
                $page = new page(0, (object) $mcmspage);
                $page->create();
                $existingpageid = $page->get('id');
            }
            // Roles.

            // Remove previous existing roles.
            $page->delete_associated_roles();
            foreach ($roles as $rolename) {
                $roleid = $DB->get_field('role', 'id', array('shortname' => $rolename));
                if ($roleid) {
                    $pagerole = new page_role(0, (object) ['pageid' => $existingpageid, 'roleid' => $roleid]);
                    $pagerole->create();
                }
            }
            // Now the file.
            $filename = basename($image);
            $filespec = [
                'filepath' => $image,
                'filearea' => page_utils::PLUGIN_FILE_AREA_IMAGE,
                'itemid' => $existingpageid
            ];
            static::upload_file($filename, $filespec, $context, page_utils::PLUGIN_FILE_COMPONENT, $existingpageid);
            // Finally the blocks.

            // Setup Home page.
            $page = new moodle_page();
            $page->set_pagelayout("standard");
            $page->set_pagetype('mcmspage');
            $page->blocks->add_region('content');
            $page->set_subpage($existingpageid);
            $page->set_context(context_system::instance());
            $PAGE = $page;
            static::setup_page_blocks($page, $blocks);
        }
        $PAGE = $oldpage;
    }

    /**
     * Setup the frontpage slider
     */
    public static function setup_slider() {
        set_config('slidernumslides', count(self::FRONT_PAGE_SLIDER), 'theme_ressourcesnum');
        $frontpagecontext = \context_course::instance(SITEID);
        foreach (self::FRONT_PAGE_SLIDER as $index => $slider) {
            $realindex = $index + 1;
            set_config('slidertext' . $realindex, $slider['text'], 'theme_ressourcesnum');
            set_config('slidercontentright' . $realindex, $slider['slidercontentright'] ?? false, 'theme_ressourcesnum');
            set_config('sliderimageoverlaycolor' . $realindex, $slider['overlaycolor'], 'theme_ressourcesnum');
            $filename = basename($slider['image']);
            $filespec = [
                'filepath' => $slider['image'],
                'filearea' => utils::SLIDER_FILEAREA,
                'itemid' => $index
            ];
            static::upload_file($filename, $filespec, $frontpagecontext, 'theme_ressourcesnum', $index);
        }

    }

    /**
     * Setup dashboard  - to be completed
     *
     * @param moodle_page $page
     * @param array $blockdeflist
     * @param string $regionname
     * @return bool
     * @throws \dml_transaction_exception
     * @throws dml_exception
     * @throws file_exception
     * @throws stored_file_creation_exception
     */
    public static function setup_page_blocks($page, $blockdeflist, $regionname = 'content') {
        global $DB;
        $transaction = $DB->start_delegated_transaction(); // Do not commit transactions until the end.
        $blocks = $page->blocks;
        $blocks->add_regions(array($regionname), false);
        $blocks->set_default_region($regionname);
        $blocks->load_blocks();

        // Delete unceessary blocks.
        $centralblocks = $blocks->get_blocks_for_region($regionname);
        foreach ($centralblocks as $cb) {
            blocks_delete_instance($cb->instance);
        }
        // Add the blocks.
        foreach ($blockdeflist as $blockdef) {
            global $DB;
            $blockinstance = (object) $blockdef;
            $blockinstance->parentcontextid = $page->context->id;
            $blockinstance->pagetypepattern = $page->pagetype;
            $subpage = $page->subpage;
            if (!empty($subpage)) {
                $blockinstance->subpagepattern = $subpage;
            }
            if (!empty($blockinstance->configdata)) {
                $blockinstance->configdata = base64_encode(serialize((object) $blockinstance->configdata));

            } else {
                $blockinstance->configdata = '';
            }
            $blockinstance->timecreated = time();
            $blockinstance->timemodified = $blockinstance->timecreated;
            $blockinstance->showinsubcontexts = $blockdef['showinsubcontexts']  ?? 0;
            $contextdefs = [];
            if (!empty($blockinstance->capabilities)) {
                $contextdefs = $blockinstance->capabilities;
                unset($blockinstance->capabilities);
            }

            $blockinstance->id = $DB->insert_record('block_instances', $blockinstance);
            if (!empty($blockdef['files'])) {
                static::upload_files_in_block($blockinstance, $blockdef['files']);
            }
            // Ensure the block context is created.
            context_block::instance($blockinstance->id);

            // If the new instance was created, allow it to do additional setup.
            if ($block = block_instance($blockinstance->blockname, $blockinstance)) {
                $block->instance_create();
            }
            foreach ($contextdefs as $capability => $roles) {
                foreach ($roles as $rolename => $permission) {
                    $roleid = $DB->get_field('role', 'id', array('shortname' => $rolename));
                    if ($roleid) {
                        role_change_permission($roleid, $block->context, $capability, $permission);
                    }
                }
            }
        }
        $DB->commit_delegated_transaction($transaction);// Ok, we can commit.
        return true;
    }

    // @codingStandardsIgnoreEnd
    // phpcs:enable

    /**
     * Upload files in blocks
     *
     * @param object $blockinstance
     * @param array $files
     * @throws file_exception
     * @throws stored_file_creation_exception
     */
    protected static function upload_files_in_block($blockinstance, $files) {
        global $DB;
        $configdata = unserialize(base64_decode($blockinstance->configdata));
        $context = context_block::instance($blockinstance->id);
        foreach ($files as $filename => $filespec) {
            $configdata =
                self::upload_file($context, 'block_' . $blockinstance->blockname, $blockinstance->id, $filename, $configdata);
        }
        $DB->update_record('block_instances',
            [
                'id' => $blockinstance->id,
                'configdata' => base64_encode(serialize($configdata)),
                'timemodified' => time()
            ]);
    }

    /**
     * Upload a file
     *
     * @param $filename
     * @param $filespec
     * @param $context
     * @param $component
     * @param $defaultitemid
     * @param $filename
     * @param $textfieldstructure
     * @throws file_exception
     * @throws stored_file_creation_exception
     */
    protected static function upload_file($filename, $filespec, $context, $component, $defaultitemid, $textfieldstructure = null) {
        $filerecord = array(
            'contextid' => $context->id,
            'component' => $component,
            'filearea' => empty($filespec['filearea']) ? "files" : $filespec['filearea'],
            'itemid' => isset($filespec['itemid']) ? $filespec['itemid'] : $defaultitemid,
            'filepath' => dirname($filename) == '.' ? '/' : dirname($filename),
            'filename' => basename($filename),
        );
        // Create an area to upload the file.
        $fs = get_file_storage();
        // Create a file from the string that we made earlier.
        if (!($file = $fs->get_file($filerecord['contextid'],
            $filerecord['component'],
            $filerecord['filearea'],
            $filerecord['itemid'],
            $filerecord['filepath'],
            $filerecord['filename']))) {
            global $CFG;
            $originalpath = $CFG->dirroot;
            $originalpath .= empty($filespec['filepath']) ?
                "/theme/imtpn/data/files/{$filerecord['filename']}" : $filespec['filepath'];

            $file = $fs->create_file_from_pathname($filerecord,
                $originalpath);
        }
        if (!empty($filespec['textfields']) && !is_null($textfieldstructure)) {
            foreach ($filespec['textfields'] as $textfield) {
                $textfieldstructure->{$textfield} =
                    file_rewrite_pluginfile_urls($textfieldstructure->{$textfield},
                        'pluginfile.php',
                        $context->id,
                        'block',
                        $filerecord['filearea'],
                        $filerecord['itemid']
                    );
            }
        }
        return $textfieldstructure;
    }
}
