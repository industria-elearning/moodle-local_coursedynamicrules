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
 * TODO describe file editrule
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

require_login();

$courseid = required_param('id', PARAM_INT);

$url = new moodle_url('/local/coursedynamicrules/editrule.php', ['id' => $courseid]);
$PAGE->set_url($url);

if (! $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST)) {
    exit;
}

require_login($course);

$PAGE->set_course($course);
$PAGE->set_title($course->shortname);
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('admin');

echo $OUTPUT->header();

$editconditionurl = new moodle_url('/local/coursedynamicrules/editcondition.php');
$options = [
    [
        'value' => '',
        'name' => 'Select condition...',
        'selected' => true,
        'optgroup' => false,
        'ignore' => 'data-ignore',
    ],
    [
        'value' => 'passgrade',
        'name' => 'Activity completion with passing grade',
        'selected' => false,
        'optgroup' => false,
    ],
];
// $singleselect = new single_select($editconditionurl, 'type', $options);
// $singleselect->export_for_template();
$singleselectcontext = [
    'name' => 'type',
    'label' => 'Select condition',
    'action' => $editconditionurl->out(false),
    'method' => 'get',
    'formid' => html_writer::random_id('single_select_f'),
    'id' => html_writer::random_id('single_select'),
    'classes' => 'singleselect',
    'params' => [
        [
            'name' => 'courseid',
            'value' => $courseid,
        ],
        [
            'name' => 'sesskey',
            'value' => sesskey(),
        ],
    ],
    'options' => $options,
];

echo $OUTPUT->render_from_template('core/single_select', $singleselectcontext);

echo $OUTPUT->footer();
