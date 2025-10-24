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

$courseid = required_param('courseid', PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_course::instance($courseid);

require_login($course);

require_capability('local/coursedynamicrules:managerule', $context);

$url = new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $courseid]);

$PAGE->set_title($course->shortname);
$PAGE->set_heading($course->fullname);
$PAGE->set_course($course);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');

echo $OUTPUT->header();

// Require JS to handle rule create modal via AMD.
$PAGE->requires->js_call_amd('local_coursedynamicrules/rules_list', 'init');

// Render heading and "Add rule" button in a single row (same pattern as core_reportbuilder/index.php).
echo html_writer::start_div('d-flex justify-content-between mb-2');
echo $OUTPUT->heading(get_string('rules', 'local_coursedynamicrules'));
/** @var \local_coursedynamicrules\output\renderer $renderer */
$renderer = $PAGE->get_renderer('local_coursedynamicrules');
$newrulebutton = new \local_coursedynamicrules\output\new_rule_button($courseid);
echo $renderer->render($newrulebutton);
echo html_writer::end_div();

// Output rules list using a system report (mirroring core_reportbuilder pattern).
/** @var \core_reportbuilder\system_report $report */
$report = \core_reportbuilder\system_report_factory::create(
    \local_coursedynamicrules\local\systemreports\rules_list::class,
    $context,
    '',
    '',
    0,
    ['courseid' => $courseid]
);
echo $report->output();
echo $OUTPUT->footer();
