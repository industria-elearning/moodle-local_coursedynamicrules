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

use core\output\dynamic_tabs;
use local_coursedynamicrules\output\dynamictabs\conditions;
use local_coursedynamicrules\output\dynamictabs\actions;

require_once(__DIR__ . '/../../config.php');

$ruleid = required_param('id', PARAM_INT);

$rule = $DB->get_record('cdr_rule', ['id' => $ruleid], '*', MUST_EXIST);
$course = $DB->get_record('course', ['id' => $rule->courseid], '*', MUST_EXIST);
$context = context_course::instance($rule->courseid);

require_login($course);
require_capability('local/coursedynamicrules:managerule', $context);

$PAGE->set_context($context);
$PAGE->set_pagelayout('popup');
$PAGE->set_url(new moodle_url('/local/coursedynamicrules/ruleedit.php', ['id' => $ruleid]));
$PAGE->set_title(get_string('editrule', 'local_coursedynamicrules'));

// Header.
echo $OUTPUT->header();

// Build top navbar similar to core_reportbuilder editor header.
$closebutton = html_writer::link(
    new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $course->id]),
    get_string('close', 'core_reportbuilder'),
    [
        'class' => 'btn btn-secondary',
        'title' => get_string('closeeditor', 'core_reportbuilder', get_string('editrule', 'local_coursedynamicrules')),
        'role' => 'button',
    ]
);
$navcontext = [
    'title' => get_string('editrule', 'local_coursedynamicrules'),
    'buttons' => $closebutton,
];
echo $OUTPUT->render_from_template('core_reportbuilder/editor_navbar', $navcontext);

// Dynamic tabs.
$tabdata = ['ruleid' => $ruleid, 'courseid' => $course->id];
$tabs = [
    new conditions($tabdata),
    new actions($tabdata),
];

echo $OUTPUT->render_from_template('core/dynamic_tabs',
    (new dynamic_tabs($tabs))->export_for_template($OUTPUT));

echo $OUTPUT->footer();
