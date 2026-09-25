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
 * Renderer principal : logo Asaka par défaut tant qu'aucun logo n'est téléversé dans l'administration.
 *
 * @package    theme_asaka
 * @copyright  2026 Asaka Academy
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_asaka\output;

/**
 * Surcharge du renderer de Boost.
 *
 * @package    theme_asaka
 */
class core_renderer extends \theme_boost\output\core_renderer {
    /**
     * Logo compact (navbar sombre) : version blanche du logotype.
     *
     * @param int $maxwidth
     * @param int $maxheight
     * @return \moodle_url|false
     */
    public function get_compact_logo_url($maxwidth = 300, $maxheight = 300) {
        return parent::get_compact_logo_url($maxwidth, $maxheight) ?: $this->image_url('logo-inverse', 'theme');
    }

    /**
     * Logo principal (page de connexion, fond clair).
     *
     * @param int|null $maxwidth
     * @param int $maxheight
     * @return \moodle_url|false
     */
    public function get_logo_url($maxwidth = null, $maxheight = 200) {
        return parent::get_logo_url($maxwidth, $maxheight) ?: $this->image_url('logo', 'theme');
    }
}
