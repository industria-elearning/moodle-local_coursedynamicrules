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
 * TODO describe file ckecklicense
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_coursedynamicrules\core\rule;

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$pluginname = 'local_coursedynamicrules';

// External page setup.
admin_externalpage_setup("{$pluginname}_checklicensekey");

// Check permissions.
is_siteadmin() || die('You must be an administrator to view this page.');

$url = new moodle_url('/local/coursedynamicrules/checklicensekey.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

$PAGE->set_title(get_string('checklicensekey', $pluginname));
$PAGE->set_heading(get_string('checklicensekey', $pluginname));

echo $OUTPUT->header();

$licensestatus = rule::validate_licence_status();
if ($licensestatus->success) {
    echo $OUTPUT->notification(get_string('licensekeyvalid', $pluginname), 'success', false);
} else {
    echo $OUTPUT->notification(get_string('licensekeyinvalid', $pluginname), 'error', false);
}

echo $OUTPUT->footer();
