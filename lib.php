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
 * Callback implementations for Course dynamic rules
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

/**
 * Extends the navigation tree with the course dynamic rules menu item.
 *
 * @param navigation_node $navigation the navigation tree
 * @param stdClass $course the course
 * @param stdClass $context the context
 */
function local_coursedynamicrules_extend_navigation_course($navigation, $course, $context) {
    if (has_capability('local/coursedynamicrules:managerule', $context)) {
        $url = new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $course->id]);
        $name = get_string('pluginname', 'local_coursedynamicrules');
        $navigation->add($name, $url, navigation_node::TYPE_SETTING, null, null, new pix_icon('i/settings', ''));
    }
}

