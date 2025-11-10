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

use local_coursedynamicrules\form\conditions\dynamic_grade_in_activity_form;
use local_coursedynamicrules\helper\rule_component_loader;

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
    global $PAGE, $DB;

    $classname = $params['classname'];
    $ruleid = (int)$params['ruleid'];
    $courseid = (int)($params['courseid'] ?? 0);
    $conditionid = (int)($params['id'] ?? 0);

    // Derive condition type from classname namespace: \component\condition\{type}\{type}_condition.
    $parts = explode('\\', $classname);
    $type = count($parts) >= 2 ? $parts[count($parts) - 2] : '';

    if ($conditionid) {
        $conditionrecord = $DB->get_record('cdr_condition', ['id' => $conditionid], '*', MUST_EXIST);
        if (!$courseid) {
            $courseid = (int)$DB->get_field('cdr_rule', 'courseid', ['id' => $conditionrecord->ruleid], MUST_EXIST);
        }
    } else {
        $conditionrecord = (object)[
            'id' => 0,
            'ruleid' => $ruleid,
            'conditiontype' => $type,
            'params' => json_encode(new stdClass()),
        ];
    }

    $conditioninstance = rule_component_loader::create_condition_instance($conditionrecord, $courseid);
    $conditioninstance->build_editform(
        null,
        null,
        'post',
        '',
        [],
        true,
        $params,
    );
    $conditionform = $conditioninstance->get_editform_instance();
    if (!$conditionform) {
        throw new \coding_exception('Condition form could not be initialised.');
    }
    if (method_exists($conditionform, 'set_data_for_dynamic_submission')) {
        $conditionform->set_data_for_dynamic_submission();
    }

    $resolvedtitle = '';
    if (!empty($params['title'])) {
        $resolvedtitle = $params['title'];
    } else if (!empty($type)) {
        $resolvedtitle = get_string($type, 'local_coursedynamicrules');
    }

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

/**
 * Output fragment for a grade in activity form
 *
 * @param array $params
 * @return string
 */
function local_coursedynamicrules_output_fragment_grade_in_activity_form(array $params): string {
    global $PAGE;

    $coursemodule = $params['coursemodule'];
    $courseid = $params['courseid'];

    $gradeinactivityform = new dynamic_grade_in_activity_form(
        null,
        null,
        'post',
        '',
        [],
        true,
        [
            'coursemodule' => $coursemodule,
            'courseid' => $courseid,
        ]
    );
    $gradeinactivityform->set_data_for_dynamic_submission();

    return $gradeinactivityform->render();
}
