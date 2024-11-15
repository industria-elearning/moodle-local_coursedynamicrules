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
 * TODO describe file deleterule
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

require_login();


$id = required_param('id', PARAM_INT); // Rule ID.
$delete = optional_param('delete', '', PARAM_ALPHANUM); // Confirmation hash.
$courseid = required_param('courseid', PARAM_INT);

require_login();

$url = new moodle_url('/local/coursedynamicrules/deleterule.php', ['delete' => $delete, 'courseid' => $courseid]);
$rulesurl = new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $courseid]);

$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

echo $OUTPUT->header();


$rule = $DB->get_record('cdr_rule', ['id' => $id]);

if (!$rule) {
    exit;
}
$config = get_config('local_coursedynamicrules');

if ($delete === md5($config->confirmdelete)) {
    require_sesskey();
    $strdeletinglevel = get_string("deletingrule", "local_coursedynamicrules", $rule->name);
    echo $OUTPUT->heading($strdeletinglevel);

    // Delete rule.
    $DB->delete_records('cdr_rule', ['id' => $id]);
    $DB->delete_records('cdr_condition', ['ruleid' => $id]);
    $DB->delete_records('cdr_action', ['ruleid' => $id]);

    echo $OUTPUT->heading(get_string("deletedrule", "local_coursedynamicrules", $rule->name));
    echo $OUTPUT->continue_button($rulesurl);
    exit; // We must exit here!!!
}

$strdeletecoursecheck = get_string("deleterulecheck", "local_coursedynamicrules");
$message = "{$strdeletecoursecheck}<br /><br />{$rule->name}";

// Generate ramdom token for validation delete action.
$confirmdelete = time() . md5(mt_rand(100000000, mt_getrandmax()));
set_config('confirmdelete', $confirmdelete, 'local_coursedynamicrules');


$continueurl = new moodle_url(
    '/local/coursedynamicrules/deleterule.php',
    ['id' => $rule->id, 'delete' => md5($confirmdelete), 'courseid' => $courseid]
);


$continuebutton = new single_button(
    $continueurl,
    get_string('delete'), 'post', false, ['data-action' => 'delete']
);
echo $OUTPUT->confirm($message, $continuebutton, $rulesurl);

// In the following script, we need to use setTimeout as disabling the
// button in the event listener script prevent the click to be taken into account.
$jsscript = <<<EOF
const button = document.querySelector('button[data-action="delete"]');
if (button) {
button.addEventListener('click', () => {
    setTimeout(() => {
        button.disabled = true;
    }, 0);
});
}
EOF;
$PAGE->requires->js_amd_inline($jsscript);

echo $OUTPUT->footer();
