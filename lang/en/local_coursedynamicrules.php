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
$string['conditions_help'] = 'Conditions are used to define the conditions that must be met for executing the rule actions';
$string['actions'] = 'Actions';
$string['actions_help'] = 'Actions are used to define the actions that will be executed when the rule conditions are met';
$string['ruleactive'] = 'Active';
$string['ruleactive_help'] = 'Enable or disable the rule';
$string['ruleinactive'] = 'Inactive';
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
$string['createaiactivity'] = 'Create AI reinforcement activity';
$string['createaiactivity_description'] = 'Generate an AI reinforcement activity in section "{$a->section}" using prompt "{$a->prompt}"';
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
$string['grade_in_activity_description'] = 'For "{$a->moddescription}", the following grades must be obtained: {$a->gradestring}';
$string['grade'] = 'Grade';
$string['enablegradegreaterthanorequal_help'] = 'Enable grade greater than or equal to';
$string['gradegreaterthanorequalvalue'] = '&#x2265; {$a}';
$string['gradelessthanvalue'] = '< {$a}';
$string['enablegradelessthan'] = 'Enable grade less than';
$string['errornegativegrade'] = 'The grade must be 0 or greater.';
$string['errormaxgradeexceeded'] = 'The grade cannot exceed the maximum grade for the activity.';
$string['enableactivity'] = 'Enable activity';
$string['enableactivity_description'] = 'Enable activities \'{$a}\'';
$string['createaiactivity_action_info'] = 'This action will request the AI service to generate a personalised reinforcement activity for users who meet the rule conditions.';
$string['errorgradeoutofrange'] = 'The value must be between {$a->min} and {$a->max}.';
$string['notification_action_info'] = 'This action will send a notification to users who meet the rule conditions criteria.';
$string['missing_plugins_warning'] = '🔔 Enhance your notifications! Our <strong>Datacurso Message Hub</strong> plugins let you send notifications via WhatsApp and SMS using providers like Twilio.
<br>
<a href="https://shop.datacurso.com/clientarea.php" target="_blank">Click here to purchase and enable them now!</a>';
$string['provider_not_enabled_warning'] = 'Enable notifications with <strong>Datacurso Message Hub</strong> to this action to send notifications via WhatsApp and SMS using providers like Twilio.
You can enable it from <a href="{$a}" target="_blank">Notification settings</a> and searching <strong>Course dynamic rules notification</strong>.
<br>
<a href="https://docs.datacurso.com/index.php?title=Message_Hub" target="_blank">See documentation for more information.</a>';
$string['rules'] = 'Rules';
$string['rules_help'] = 'Rules are used to define set of conditions and actions that will be executed';
$string['missing_availability_user'] = 'This action requires the plugin <strong>Restriction by user</strong> to be installed and enabled. Please download it from <a href="https://moodle.org/plugins/availability_user/versions" target="_blank">https://moodle.org/plugins/availability_user/versions</a> and install it.';
$string['disabled_availability_user'] = 'This action requires the plugin <strong>Restriction by user</strong> to be enabled. Please access to the <a href="{$a}" target="_blank">Manage restrictions</a> page, search <strong>Restriction by user</strong> and enable it.';
$string['enableactivity_action_info'] = 'This action will enable selected activities modules for users who meet the rule conditions criteria.';
$string['createaiactivity_prompt'] = 'AI prompt';
$string['createaiactivity_prompt_help'] = 'Write the instruction that will be sent to the AI service. You can include placeholders that will be replaced before sending the prompt.';
$string['createaiactivity_generateimages'] = 'Generate images with AI';
$string['createaiactivity_generateimages_label'] = 'Allow the AI to include generated images when supported.';
$string['createaiactivity_section'] = 'Course section';
$string['createaiactivity_beforemod'] = 'Place before activity';
$string['createaiactivity_beforemod_help'] = 'Select the activity that the new resource should precede, or keep the default option to add it at the end of the section.';
$string['createaiactivity_beforemod_none'] = 'Do not position before another activity';
$string['createaiactivity_placeholders_info'] = 'Available placeholders: <code>{$a->coursename}</code>, <code>{$a->courseurl}</code>, <code>{$a->fullname}</code>, <code>{$a->firstname}</code>, <code>{$a->lastname}</code>.';
$string['grade_in_activity_condition_info'] = 'This condition will check which user has obtained the specified grade in the selected activity module.';
$string['no_complete_activity_condition_info'] = 'This condition will check which user has not completed the selected activity module after the specified date.';
$string['passgrade_condition_info'] = 'This condition will check which user has completed the selected activity module with a passing grade.';
$string['generalsettings'] = 'General settings';
$string['gradegreaterthanorequal'] = 'must be &#x2265;';
$string['gradegreaterthanorequal_help'] = 'The condition is met if the user\'s grade is greater than or equal to the specified value.';
$string['gradelessthan'] = 'must be <';
$string['gradelessthan_help'] = 'The condition is met if the user\'s grade is less than the specified value.';
$string['no_course_access_condition_info'] = 'This condition will check which users have not accessed this course within the specified time period.';
$string['no_course_access'] = 'No course access';
$string['no_course_access_description'] = 'Users who take more than {$a->periodvalue} {$a->periodunit} without accessing this course.';
$string['period'] = 'Period';
$string['period_help'] = 'The minimum amount of time a user must go without accessing the course.';
$string['no_course_access_task'] = 'No course access task';
$string['minutes'] = 'Minutes';
$string['hours'] = 'Hours';
$string['days'] = 'Days';
$string['weeks'] = 'Weeks';
$string['months'] = 'Months';
$string['course_inactivity_info'] = 'This condition will check which users have had no activity in the course within the specified time intervals.';
$string['course_inactivity'] = 'Course inactivity in time intervals';
$string['basedate'] = 'Base date';
$string['basedate_help'] = 'Choose the reference date for evaluating inactivity:

* **From enrolment date**: Calculates from when the user enrolled.
* **From course start date**: Calculates from the course start date.
* **From now**: Calculates from the current date.';
$string['invalidbasedate'] = 'Invalid base date type {$a}';
$string['course_inactivity_task'] = 'Course inactivity task';
$string['intervaltype'] = 'Interval type';
$string['intervaltype_help'] = 'Select how the interval will be evaluated:

* **Custom intervals**: To add comma-separated values (e.g., 7,14,30) to evaluate inactivity at specific time points.
* **Recurring interval**: To evaluate inactivity at recurring intervals (e.g., every 7 days).';
$string['customintervals'] = 'Custom intervals';
$string['customintervals_help'] = 'Enter comma-separated numbers representing inactivity periods (e.g., "7,14,30").';
$string['recurringinterval'] = 'Recurring interval';
$string['recurringinterval_help'] = 'Enter an numeric value representing a recurring inactivity interval (e.g., "7" for every 7 days of inactivity).';
$string['intervalunit'] = 'Time unit';
$string['intervalunit_help'] = 'Select the unit of time for the intervals.';
$string['date_from_enrollment'] = 'From enrolment date';
$string['date_from_course_start'] = 'From course start date';
$string['date_from_now'] = 'From now';
$string['course_inactivity_custom_description'] = 'Users without activity in the course for intervals of {$a->intervals} {$a->unit} from {$a->basedate}';
$string['course_inactivity_recurring_description'] = 'Users without activity in the course at recurring intervals of {$a->intervals} {$a->unit} from {$a->basedate}';
$string['enrollmentdate'] = 'Enrollment date';
$string['coursestartdate'] = 'Course start date';
$string['now'] = 'Now';
$string['complete_activity_description'] = 'Users who have completed the course activity module \'{$a->moddescription}\'';
$string['complete_activity_condition_info'] = 'This condition will check which user has completed the selected activity module.';
$string['complete_activity'] = 'Activity completed';
$string['copytoclipboard'] = 'Copy to clipboard';
$string['copiedtoclipboard'] = 'Copied to clipboard';
$string['rolestonotify'] = 'Roles to notify';
$string['rolestonotify_help'] = 'Select the roles the user must have to receive the notification. You must select at least one.';
$string['mustselectonerole'] = 'You must select at least one role.';

// License.
$string['pluginnotavailable'] = 'This plugin is not available, because the product license has expired or is invalid. Please go to <a href="https://shop.datacurso.com/clientarea.php" target="_blank">Shop Datacurso</a> to renew or purchase a new license.';
$string['licensekey'] = 'License Key';
$string['licensekeycompany'] = 'License Key for: {$a}';
$string['licensekeycompany_desc'] = 'License key required to use this plugin for company: {$a}';
$string['licensekey_desc'] = 'License key required to use this plugin';
$string['licensekeyinvalid'] = 'License key has expired or is invalid. Please go to <a href="https://shop.datacurso.com/clientarea.php" target="_blank">Shop Datacurso</a> to renew or purchase a new license.';
$string['checklicensekey'] = 'Check licence key';
$string['licensekeyvalid'] = 'License key is valid';

