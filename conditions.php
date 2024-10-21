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

use local_coursedynamicrules\rule\rule_class_loader;

require('../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$ruleid = required_param('ruleid', PARAM_INT);
$type = optional_param('type', '', PARAM_ALPHA);

$url = new moodle_url('/local/coursedynamicrules/conditions.php', ['courseid' => $courseid]);
$rulesurl = new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $courseid]);

$PAGE->set_url($url);

if (! $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST)) {
    exit;
}

require_login($course);

$PAGE->set_course($course);
$PAGE->set_title($course->shortname);
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('admin');

$conditions = $DB->get_records('cdr_condition', ['ruleid' => $ruleid]);

$conditionsfortemplate = [];
foreach ($conditions as $condition) {
    $conditionsfortemplate[] = [
        'id' => $condition->id,
        'name' => $condition->name,
    ];
}

echo $OUTPUT->header();
echo html_writer::start_div('d-flex');
echo $OUTPUT->render_from_template('local_coursedynamicrules/conditions_menu', ['conditions' => $conditionsfortemplate]);
echo html_writer::start_div('col-8');
echo $OUTPUT->render_from_template('local_coursedynamicrules/conditions', ['conditions' => $conditionsfortemplate]);
if (!empty($type)) {
    $conditionclass = rule_class_loader::get_condition_class($type);

    /** @var \local_coursedynamicrules\condition\condition_base $conditioninstance */
    $conditioninstance = new $conditionclass();

    $conditioninstance->build_editform(null, null, 'post', '', ['class' => 'card p-4']);
    // $itemobj->get_data();
    $conditioninstance->show_editform();
}
echo html_writer::end_div();
echo html_writer::end_div();
echo $OUTPUT->footer();
