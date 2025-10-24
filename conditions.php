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
 * TODO describe file conditions
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_coursedynamicrules\helper\rule_component_loader;

require('../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$ruleid = required_param('ruleid', PARAM_INT);
$type = optional_param('type', '', PARAM_TEXT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_course::instance($courseid);

require_login($course);
require_capability('local/coursedynamicrules:managecondition', $context);

$url = new moodle_url('/local/coursedynamicrules/conditions.php', ['courseid' => $courseid, 'ruleid' => $ruleid]);
$rulesurl = new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $courseid]);

$PAGE->set_title($course->shortname);
$PAGE->set_heading($course->fullname);
$PAGE->set_course($course);

// The page renderable will build the conditions list and menu by default.
// If a specific condition type is selected, we will build its form and
// inject the rendered HTML into the right column.
$formhtml = null;
if (!empty($type)) {
    $conditionrecord = (object) [
        'conditiontype' => $type,
        'params' => json_encode([]),
    ];
    $conditioninstance = rule_component_loader::create_condition_instance($conditionrecord);

    $customdata = [
        'courseid' => $courseid,
        'ruleid' => $ruleid,
    ];
    $conditioninstance->build_editform($url, $customdata, 'post', '', ['class' => 'card p-4']);

    if ($conditioninstance->is_cancelled()) {
        redirect($url);
    } else if ($data = $conditioninstance->get_data()) {
        $conditioninstance->save_condition($data);
        redirect($url);
    } else {
        $formhtml = $conditioninstance->show_editform();
    }
}
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
echo $OUTPUT->header();

if (!$DB->get_record('cdr_rule', ['id' => $ruleid])) {
    throw new moodle_exception('invalidruleid', 'local_coursedynamicrules');
}

// Render heading and branding using reusable renderable.
$headerrow = new \local_coursedynamicrules\output\header_with_brand('conditions');
echo $OUTPUT->render($headerrow);

// Render the page content.
$conditionspage = new \local_coursedynamicrules\output\conditions_page($courseid, $ruleid, $formhtml);
echo $OUTPUT->render($conditionspage);

echo $OUTPUT->footer();
