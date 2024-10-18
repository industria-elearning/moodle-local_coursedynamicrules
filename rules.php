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

$courseid = required_param('id', PARAM_INT);

$url = new moodle_url('/local/coursedynamicrules/rules.php', ['id' => $courseid]);
$PAGE->set_url($url);

if (! $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST)) {
    exit;
}

require_login($course);

$PAGE->set_course($course);
$PAGE->set_title($course->shortname);
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('admin');

// Mock de datos.
$mockdata = [
    ['Rule 1', 'Completed module 1', 'Send notification', '2024-10-15'],
    ['Rule 2', 'Completed module 2', 'Send reminder', '2024-10-12'],
    ['Rule 3', 'Completed module 3', 'Unlock certificate', '2024-10-14'],
    ['Rule 4', 'Completed module 4', 'Send notification', '2024-10-15'],
];

$table = new html_table();
$table->head = ['Name', 'Conditions', 'Actions', 'Creation Date'];
$table->data = $mockdata;
$addrulebutton = new single_button(new moodle_url('/local/coursedynamicrules/editrule.php', ['id' => $courseid]), 'Add rule', 'get', true);

echo $OUTPUT->header();

echo html_writer::div($OUTPUT->render($addrulebutton), 'my-3');
echo html_writer::table($table);
echo $OUTPUT->footer();
