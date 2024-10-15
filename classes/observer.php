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

namespace local_coursedynamicrules;

/**
 * Class observer
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * Trigger when course module completion updated
     * @param \core\event\course_module_completion_updated
     */
    public static function course_module_completion_updated(\core\event\course_module_completion_updated $event) {
        global $DB;
        $eventdata = $event->get_data();
        $otherdata = $eventdata['other'];
        $courseid = $eventdata["courseid"];
        $cmid = $eventdata["contextinstanceid"];
        $userid = $eventdata["userid"];
        // User that receive the grade.
        $relateduserid = $eventdata["relateduserid"];
        $cmcompletionid = $eventdata["objectid"];

        $completionstate = $otherdata['completionstate'];
        $course = $DB->get_record('course', ['id' => $courseid]);

        $modinfo = get_fast_modinfo($courseid, $relateduserid);
        $cminfo = $modinfo->get_cm($cmid);
        $completioninfo = new \completion_info($course);
        // $completionstate = $completioninfo->get_core_completion_state($cminfo, $relateduserid);

        if ($completionstate == COMPLETION_COMPLETE_PASS) {
            $message = new \core\message\message();
            $message->component = 'local_coursedynamicrules'; // Your plugin's name
            $message->name = 'coursedynamicrules_notification'; // Your notification name from message.php
            $message->userfrom = $message->userfrom = \core_user::get_support_user(); // TODO: Validate when use $user variable, example when grade assign
            $message->userto = $relateduserid;
            $message->subject = 'message subject 2';
            $message->fullmessage = 'message body';
            $message->fullmessageformat = FORMAT_HTML;
            $message->fullmessagehtml = '<p>message body</p>';
            $message->smallmessage = 'small message';
            $messageid = message_send($message);

        }
    }
}
