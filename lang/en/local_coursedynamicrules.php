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
$string['coursedynamicrules:managerule'] = 'Manage rules';
$string['coursedynamicrules:createrule'] = 'Create rules';
$string['coursedynamicrules:updaterule'] = 'Update rules';
$string['coursedynamicrules:viewrule'] = 'View rules';
$string['coursedynamicrules:deleterule'] = 'Delete rules';
$string['coursedynamicrules:manageaction'] = 'Manage actions';
$string['coursedynamicrules:createaction'] = 'Create actions';
$string['coursedynamicrules:updateaction'] = 'Update actions';
$string['coursedynamicrules:viewaction'] = 'View actions';
$string['coursedynamicrules:deleteaction'] = 'Delete actions';
$string['coursedynamicrules:managecondition'] = 'Manage conditions';
$string['coursedynamicrules:createcondition'] = 'Create conditions';
$string['coursedynamicrules:updatecondition'] = 'Update conditions';
$string['coursedynamicrules:viewcondition'] = 'View conditions';
$string['coursedynamicrules:deletecondition'] = 'Delete conditions';
$string['typemissing'] = 'Missing value "type"';
$string['name'] = 'Name';
$string['description'] = 'Description';
$string['conditions'] = 'Conditions';
$string['actions'] = 'Actions';
$string['ruleactive'] = 'Active';
$string['ruleactive_help'] = 'Enable or disable the rule';
$string['ruleadd'] = 'Add rule';
$string['ruleaddedsuccessfully'] = 'Rule added successfully';
$string['editrule'] = 'Edit rule';
$string['deleterule'] = 'Delete rule';
$string['addconditions'] = 'Add conditions';
$string['editconditions'] = 'Edit conditions';
$string['passgrade'] = 'Activity completion with passing grade';
$string['allcourseactivitymodules'] = 'All course activity modules';
$string['searchcourseactivitymodules'] = 'Search course activity modules';
$string['passgrade_description'] = 'Users who have completed the course activity module \'{$a}\' with a passing grade';
$string['no_complete_activity_description'] = 'Users who have not completed the course activity module \'{$a->moddescription}\' after {$a->expectedcompletiondate}';
$string['invalidruleid'] = 'Invalid rule id';
$string['deletecondition'] = 'Delete condition';
$string['messagesubject'] = 'Subject';
$string['messagebody'] = 'Body';
$string['messagebody_help'] = 'The following placeholders may be included in the message:

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
$string['courselink'] = 'Course link';
$string['fullname'] = 'User fullname';
$string['firstname'] = 'User firstname';
$string['lastname'] = 'User lastname';
$string['modulename'] = 'Course activity module name';
$string['moduleinstancename'] = 'Course activity module instance name';
$string['deletingrule'] = 'Deleting rule \'{$a}\'';
$string['deletingcondition'] = 'Deleting condition \'{$a}\'';
$string['deleterulecheck'] = 'Are you absolutely sure you want to completely delete this rule?';
$string['deleteconditioncheck'] = 'Are you absolutely sure you want to completely delete this condition?';
$string['deleteactioncheck'] = 'Are you absolutely sure you want to completely delete this action?';
$string['deletedrule'] = 'Deleted rule <b>{$a}</b>';
$string['deletedcondition'] = 'Deleted condition <b>{$a}</b>';
$string['deletedaction'] = 'Deleted action <b>{$a}</b>';
$string['ruleupdatedsuccessfully'] = 'Rule updated successfully';
$string['createrule'] = 'Create Rule';
$string['completiondate'] = 'Completion date';
$string['before'] = 'Before';
$string['after'] = 'After';
$string['no_complete_activity'] = 'Activity not completed';
$string['no_complete_activity_task'] = 'No complete activity task';
$string['expectedcompletiondate'] = 'Expected completion date';
$string['grade_in_activity'] = 'Grade in activity';
$string['grade_in_activity_description'] = 'Users who have receive a grade for course activity module \'{$a}\'';

$string['grade'] = 'Grade';
$string['enablegradegreaterthanorequal_help'] = 'Enable grade greater than or equal to';
$string['gradegreaterthanorequal'] = 'must be &#x2265;';
$string['gradegreaterthanorequal_help'] = 'Minimum grade (inclusive)';
$string['gradelessthan'] = 'must be <';
$string['gradelessthan_help'] = 'Maximum grade (exclusive)';
$string['enablegradelessthan'] = 'Enable grade less than';
$string['errornegativegrade'] = 'The grade must be 0 or greater.';
$string['errormaxgradeexceeded'] = 'The grade cannot exceed the maximum grade for the activity.';

// $string['option_min'] = 'must be &#x2265;';
// $string['option_max'] = 'must be <';
