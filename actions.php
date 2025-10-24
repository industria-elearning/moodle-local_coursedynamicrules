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
 * TODO describe file actions
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// TODO Refactor this file.

use local_coursedynamicrules\core\rule;
use local_coursedynamicrules\helper\rule_component_loader;

require('../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$ruleid = required_param('ruleid', PARAM_INT);
$type = optional_param('type', '', PARAM_ALPHA);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_course::instance($courseid);

require_login($course);
require_capability('local/coursedynamicrules:manageaction', $context);

$url = new moodle_url('/local/coursedynamicrules/actions.php', ['courseid' => $courseid, 'ruleid' => $ruleid]);
$rulesurl = new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $courseid]);

$PAGE->set_title($course->shortname);
$PAGE->set_heading($course->fullname);
$PAGE->set_course($course);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');

echo $OUTPUT->header();

if (!$DB->get_record('cdr_rule', ['id' => $ruleid])) {
    throw new moodle_exception('invalidruleid', 'local_coursedynamicrules');
}

$headerrow = new \local_coursedynamicrules\output\header_with_brand('actions');
echo $OUTPUT->render($headerrow);

if (!empty($type)) {
    $actionrecord = (object) [
        'actiontype' => $type,
        'params' => json_encode([]),
    ];
    $actioninstance = rule_component_loader::create_action_instance($actionrecord);
    $customdata = [
        'courseid' => $courseid,
        'ruleid' => $ruleid,
    ];
    $actioninstance->build_editform($url, $customdata, 'post', '', ['class' => 'card p-4']);

    if ($actioninstance->is_cancelled()) {
        redirect($url);
    } else if ($data = $actioninstance->get_data()) {
        $actioninstance->save_action($data);
        redirect($url);
    } else {
        $formhtml = $actioninstance->show_editform();
    }
}

// Render the page content (back link, menu, list, and optional form) via template.
$actionspage = new \local_coursedynamicrules\output\actions_page($courseid, $ruleid, $formhtml ?? null);
echo $OUTPUT->render($actionspage);

echo $OUTPUT->footer();
