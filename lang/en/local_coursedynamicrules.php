<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     local_coursedynamicrules
 * @category    string
 * @copyright   2024 Industria Elearning <info@industriaelearning.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Course dynamic rules';
$string['coursedynamicrules:notification'] = 'Send notification';
$string['messageprovider:coursedynamicrules_notification'] = 'Course dynamic rules notification';
$string['coursedynamicrules:manage'] = 'Manage course dynamic rules';
$string['typemissing'] = 'Missing value "type"';
$string['rule:name'] = 'Name';
$string['rule:description'] = 'Description';
$string['rule:conditions'] = 'Conditions';
$string['rule:actions'] = 'Actions';
$string['rule:active'] = 'Active';
$string['rule:active_help'] = 'Enable or disable the rule';
$string['rule:add'] = 'Add rule';
$string['rule:addedsuccessfully'] = 'Rule added successfully';
$string['rule:edit'] = 'Edit rule';
$string['rule:delete'] = 'Delete rule';
$string['condition:add'] = 'Add conditions';
$string['condition:edit'] = 'Edit conditions';
$string['condition:passgrade'] = 'Activity completion with passing grade';
$string['allcourseactivitymodules'] = 'All course activity modules';
$string['searchcourseactivitymodules'] = 'Search course activity modules';
$string['condition:passgrade:description'] = 'Users who have completed the course activity module \'{$a}\' with a passing grade';
$string['invalidruleid'] = 'Invalid rule id';
$string['deletecondition'] = 'Delete condition';
$string['messagesubject'] = 'Subject';
$string['messagebody'] = 'Body';
$string['messagebody_help'] = 'Message body may be added as plain text or Moodle-auto format, including HTML tags and multi-lang tags.

The following placeholders may be included in the message:

* Course name {$a->coursename}
* User fullname {$a->fullname}
* User firstname {$a->firstname}
* User lastname {$a->lastname}
* Course activity module name {$a->modulename}
* Course activity module instance name {$a->moduleinstancename}';
$string['sendnotification'] = 'Send notification';
$string['sendnotification_description'] = 'Send notification \'{$a}\' to users';
$string['addactions'] = 'Add actions';
$string['editactions'] = 'Edit actions';
$string['backtolistrules'] = 'Back to list rules';
$string['availableplaceholders'] = 'Available placeholders';
$string['coursename'] = 'Course name';
$string['fullname'] = 'User fullname';
$string['firstname'] = 'User firstname';
$string['lastname'] = 'User lastname';
$string['modulename'] = 'Course activity module name';
$string['moduleinstancename'] = 'Course activity module instance name';
$string['deletingrule'] = 'Deleting rule \'{$a}\'';
$string['deleterulecheck'] = 'Are you absolutely sure you want to completely delete this rule?';
$string['deletedrule'] = 'rule \'{$a}\' has been completely deleted';
$string['rule:updatedsuccessfully'] = 'Rule updated successfully';
$string['createrule'] = 'Create Rule';
$string['editrule'] = 'Edit Rule';
$string['local_coursedynamicrules:editrules'] = 'Edit Course Dynamic Rules';


