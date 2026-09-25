<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Fonctions du thème Asaka.
 *
 * L'ordre de compilation est : pre (Boost puis Asaka) → preset Boost → extra (Boost puis Asaka).
 * Les tokens sont générés par design/build-tokens.mjs dans scss/_tokens.scss et scss/_components.scss.
 *
 * @package    theme_asaka
 * @copyright  2026 Asaka Academy
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * SCSS principal : le preset par défaut de Boost (Bootstrap 5 + Moodle).
 *
 * @param theme_config $theme
 * @return string
 */
function theme_asaka_get_main_scss_content($theme) {
    global $CFG;
    return file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
}

/**
 * Variables injectées avant Bootstrap : tokens Asaka mappés sur les variables Bootstrap/Moodle.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_asaka_get_pre_scss($theme) {
    return file_get_contents(__DIR__ . '/scss/_tokens.scss') . "\n" .
        file_get_contents(__DIR__ . '/scss/pre.scss');
}

/**
 * Styles ajoutés après Bootstrap : composants partagés puis surcharges Moodle.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_asaka_get_extra_scss($theme) {
    return file_get_contents(__DIR__ . '/scss/_tokens.scss') . "\n" .
        file_get_contents(__DIR__ . '/scss/_components.scss') . "\n" .
        file_get_contents(__DIR__ . '/scss/post.scss');
}
