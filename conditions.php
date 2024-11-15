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

// TODO Refactor this file.

use local_coursedynamicrules\helper\rule_component_loader;

require('../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$ruleid = required_param('ruleid', PARAM_INT);
$type = optional_param('type', '', PARAM_ALPHA);

$url = new moodle_url('/local/coursedynamicrules/conditions.php', ['courseid' => $courseid, 'ruleid' => $ruleid]);
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

if (!$DB->get_record('cdr_rule', ['id' => $ruleid])) {
    throw new moodle_exception('invalidruleid', 'local_coursedynamicrules');
}

$conditions = $DB->get_records('cdr_condition', ['ruleid' => $ruleid]);


$conditionsfortemplate = [];
foreach ($conditions as $condition) {
    $conditioninstance = rule_component_loader::create_condition_instance($condition, $courseid);

    $header = $conditioninstance->get_header();
    $description = $conditioninstance->get_description();

    if (!empty($header) && !empty($description)) {
        $conditionsfortemplate[] = [
            'id' => $condition->id,
            'header' => $header,
            'description' => $description,
        ];
    }
}

echo $OUTPUT->header();

$conditionoptions = load_condition_options();

echo html_writer::link($rulesurl, get_string('backtolistrules', 'local_coursedynamicrules'), ['class' => 'mb-3 d-block']);
echo html_writer::start_div('d-flex h-100');
echo $OUTPUT->render_from_template('local_coursedynamicrules/conditions_menu', ['options' => $conditionoptions]);
echo html_writer::start_div('col-8 h-100');
echo $OUTPUT->render_from_template('local_coursedynamicrules/conditions', ['conditions' => $conditionsfortemplate]);
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
        $conditioninstance->show_editform();
    }
}

echo html_writer::end_div();
echo html_writer::end_div();
echo $OUTPUT->footer();


/**
 * load the available item plugins from given subdirectory of $CFG->dirroot
 * the default is "mod/feedback/item"
 *
 * @param string $dir the subdir
 * @return array list of condition types
 */
function load_conditions($dir = 'local/coursedynamicrules/classes/condition') {
    global $CFG;
    $conditiontypes = get_list_of_plugins($dir);
    $filtered = [];

    foreach ($conditiontypes as $conditiontype) {
        $conditionclass = "\\local_coursedynamicrules\\condition\\{$conditiontype}\\{$conditiontype}_condition";
        if (class_exists($conditionclass)) {
            $filtered[] = $conditiontype;
        }
    }
    return $filtered;
}

/**
 * load the available condtion options to use in conditions menu option
 *
 * @return array pluginnames as string
 */
function load_condition_options() {
    global $CFG;
    $courseid = required_param('courseid', PARAM_INT);
    $ruleid = required_param('ruleid', PARAM_INT);
    $conditionoptions = [];

    if (!$conditiontypes = load_conditions('local/coursedynamicrules/classes/condition')) {
        return [];
    }

    foreach ($conditiontypes as $conditiontype) {
        $url = new moodle_url(
            '/local/coursedynamicrules/conditions.php',
            ['courseid' => $courseid, 'type' => $conditiontype, 'ruleid' => $ruleid]
        );
        $conditionoptions[] = [
            'type' => $conditiontype,
            'visualname' => get_string('condition:' .$conditiontype, 'local_coursedynamicrules'),
            'action' => $url->out(false),
        ];
    }
    asort($conditionoptions);
    return $conditionoptions;
}
