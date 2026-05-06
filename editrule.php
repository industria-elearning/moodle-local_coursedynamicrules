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

$ruleid = optional_param('id', 0, PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_course::instance($courseid);

require_login($course);
require_capability('local/coursedynamicrules:updaterule', $context);

$url = new moodle_url('/local/coursedynamicrules/editrule.php', ['courseid' => $courseid, 'id' => $ruleid]);
$rulesurl = new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $courseid]);

if ($ruleid) {
    $pagetitle = get_string('editrule', 'local_coursedynamicrules');
    $rule = $DB->get_record('local_coursedynamicrules_rule', ['id' => $ruleid, 'courseid' => $courseid], '*', MUST_EXIST);
} else {
    $pagetitle = get_string('createrule', 'local_coursedynamicrules');
    $rule = new stdClass();
    $rule->id = 0;
    $rule->name = '';
    $rule->description = '';
    $rule->active = 0;
}

$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);
$PAGE->set_course($course);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');

$ruleform = new local_coursedynamicrules\form\rule_form($url, ['rule' => $rule, 'courseid' => $courseid]);

if ($ruleform->is_cancelled()) {
    redirect($rulesurl);
} else if ($data = $ruleform->get_data()) {
    $data->timemodified = time();
    $data->active = $data->active ?? 0;
    if (empty($data->id)) {
        $data->timecreated = time();
        $DB->insert_record('local_coursedynamicrules_rule', $data);
        redirect(
            $rulesurl,
            get_string('ruleaddedsuccessfully', 'local_coursedynamicrules'),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
    } else {
        $DB->update_record('local_coursedynamicrules_rule', $data);
        redirect(
            $rulesurl,
            get_string('ruleupdatedsuccessfully', 'local_coursedynamicrules'),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
    }
}

echo $OUTPUT->header();
$headingkey = $ruleid ? 'editrule' : 'createrule';
$headerrow = new \local_coursedynamicrules\output\header_with_brand($headingkey, 'local_coursedynamicrules', false);
echo $OUTPUT->render($headerrow);
$ruleform->display();
echo $OUTPUT->footer();
