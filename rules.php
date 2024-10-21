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
 * TODO describe file rules
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

require_login();

$courseid = required_param('courseid', PARAM_INT);

$url = new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $courseid]);
$editruleurl = new moodle_url('/local/coursedynamicrules/editrule.php', ['courseid' => $courseid]);

$PAGE->set_url($url);

if (! $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST)) {
    exit;
}

require_login($course);

$PAGE->set_course($course);
$PAGE->set_title($course->shortname);
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('admin');

$rules = $DB->get_records('cdr_rule', ['courseid' => $courseid]);

$table = new html_table();
$table->head[] = get_string('rule:name', 'local_coursedynamicrules');
$table->head[] = get_string('rule:conditions', 'local_coursedynamicrules');
$table->head[] = get_string('rule:actions', 'local_coursedynamicrules');

foreach ($rules as $rule) {
    $conditions = $DB->get_records('cdr_condition', ['ruleid' => $rule->id]);
    $actions = $DB->get_records('cdr_action', ['ruleid' => $rule->id]);
    $conditionstext = '';
    $actionstext = '';

    $conditionsurl = new moodle_url(
        '/local/coursedynamicrules/conditions.php',
        ['courseid' => $courseid, 'ruleid' => $rule->id]
    );


    if (empty($conditions)) {
        $conditionstext = html_writer::link(
            $conditionsurl,
            get_string('condition:add', 'local_coursedynamicrules')
        );
    } else {
        $conditionstext = '';
        foreach ($conditions as $condition) {
            $conditionstext .= '<p>' . $condition->name . '</p>';
        }
        $editlink = html_writer::link(
            $conditionsurl,
            $OUTPUT->pix_icon('t/edit', get_string('condition:edit', 'local_coursedynamicrules'))
        );

        $conditionstext = html_writer::div($conditionstext . $editlink, 'd-flex', ['style' => 'gap: .8rem']);
    }

    $table->data[] = [
        $rule->name,
        $conditionstext,
        $actionstext,
    ];

}

$addrulebutton = new single_button(
    $editruleurl,
    get_string('rule:add', 'local_coursedynamicrules'),
    'get',
    true
);

echo $OUTPUT->header();

echo html_writer::div($OUTPUT->render($addrulebutton), 'my-3');
echo html_writer::table($table);
echo $OUTPUT->footer();
