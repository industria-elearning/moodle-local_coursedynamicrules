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
 * Callback implementations for Smart Rules AI
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Extends the navigation tree with the Smart Rules AI menu item.
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

/**
 * Output fragment for a condition form card
 *
 * @param array $params
 * @return string
 */
function local_coursedynamicrules_output_fragment_condition_form(array $params): string {
    global $PAGE;

    $classname = $params['classname'];
    $ruleid = (int)$params['ruleid'];
    $courseid = (int)($params['courseid'] ?? 0);

    // Derive condition type from classname namespace: \component\condition\{type}\{type}_condition.
    $parts = explode('\\', $classname);
    $type = count($parts) >= 2 ? $parts[count($parts) - 2] : '';

    // Prepare dynamic form for condition (mirror core_reportbuilder audience_form).
    $conditionform = new \local_coursedynamicrules\form\conditions\condition_form(
        null,
        null,
        'post',
        '',
        [],
        true,
        [
            'ruleid' => $ruleid,
            'classname' => $classname,
            'courseid' => $courseid,
        ]
    );
    $conditionform->set_data_for_dynamic_submission();

    $resolvedtitle = $params['title'] ?? ($type ? get_string($type, 'local_coursedynamicrules') : '');

    $context = [
        'instanceid' => 0,
        'heading' => $resolvedtitle,
        'headingeditable' => $resolvedtitle,
        'form' => $conditionform->render(),
        'canedit' => true,
        'candelete' => true,
        'showormessage' => !empty($params['showormessage']),
        'description' => '',
    ];

    $renderer = $PAGE->get_renderer('local_coursedynamicrules');
    return $renderer->render_from_template('local_coursedynamicrules/local/condition/form', $context);
}
