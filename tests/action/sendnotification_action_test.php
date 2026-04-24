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

namespace local_coursedynamicrules\action\sendnotification;

/**
 * Tests for send notification action.
 *
 * @package    local_coursedynamicrules
 * @covers     \local_coursedynamicrules\action\sendnotification\sendnotification_action
 * @copyright  2026 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class sendnotification_action_test extends \advanced_testcase {
    /**
     * Test action is skipped when matched user is not in observed roles.
     */
    public function test_execute_does_not_send_when_user_role_is_not_observed(): void {
        global $DB;

        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();
        $sink = $this->redirectMessages();

        $course = $generator->create_course(['fullname' => 'Course placeholder test']);
        $teacher = $generator->create_user([
            'firstname' => 'TeacherFirst',
            'lastname' => 'TeacherLast',
        ]);
        $student = $generator->create_user([
            'firstname' => 'StudentFirst',
            'lastname' => 'StudentLast',
        ]);

        $teacherroleid = $DB->get_field('role', 'id', ['shortname' => 'editingteacher'], MUST_EXIST);
        $studentroleid = $DB->get_field('role', 'id', ['shortname' => 'student'], MUST_EXIST);

        $generator->enrol_user($teacher->id, $course->id, $teacherroleid);
        $generator->enrol_user($student->id, $course->id, $studentroleid);

        $record = (object) [
            'id' => 1,
            'ruleid' => 1,
            'actiontype' => 'sendnotification',
            'params' => json_encode([
                'messagesubject' => 'Nactivity notification',
                'messagebody' => '{$a-&gt;fullname} - {$a-&gt;firstname} - {$a-&gt;lastname}',
                'primaryroleids' => [$teacherroleid],
                'copyroleids' => [$studentroleid],
            ]),
        ];

        $action = new sendnotification_action($record, $course->id);
        $rulecontext = (object) [
            'courseid' => $course->id,
            'userid' => $student->id,
        ];

        $result = $action->execute($rulecontext);

        $messages = $sink->get_messages_by_component('local_coursedynamicrules');

        $this->assertFalse($result);
        $this->assertCount(0, $messages);
    }

    /**
     * Test observer recipients receive an observation-formatted notification.
     */
    public function test_execute_sends_observation_message_to_other_role(): void {
        global $DB;

        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();
        $sink = $this->redirectMessages();

        $course = $generator->create_course(['fullname' => 'Course placeholder test']);
        $teacher = $generator->create_user([
            'firstname' => 'TeacherFirst',
            'lastname' => 'TeacherLast',
        ]);
        $student = $generator->create_user([
            'firstname' => 'StudentFirst',
            'lastname' => 'StudentLast',
        ]);

        $teacherroleid = $DB->get_field('role', 'id', ['shortname' => 'editingteacher'], MUST_EXIST);
        $studentroleid = $DB->get_field('role', 'id', ['shortname' => 'student'], MUST_EXIST);

        $generator->enrol_user($teacher->id, $course->id, $teacherroleid);
        $generator->enrol_user($student->id, $course->id, $studentroleid);

        $record = (object) [
            'id' => 1,
            'ruleid' => 1,
            'actiontype' => 'sendnotification',
            'params' => json_encode([
                'messagesubject' => 'Nactivity notification',
                'messagebody' => '{$a-&gt;fullname} - {$a-&gt;firstname} - {$a-&gt;lastname}',
                'primaryroleids' => [$studentroleid],
                'copyroleids' => [$teacherroleid],
            ]),
        ];

        $action = new sendnotification_action($record, $course->id);
        $rulecontext = (object) [
            'courseid' => $course->id,
            'userid' => $student->id,
        ];

        $action->execute($rulecontext);

        $messages = $sink->get_messages_by_component('local_coursedynamicrules');

        $this->assertCount(2, $messages);

        $messagesbyrecipient = [];
        foreach ($messages as $message) {
            $messagesbyrecipient[$message->useridto] = $message;
        }

        $message = $messagesbyrecipient[$teacher->id];
        $expectedobserversubject = get_string('observer_notification_subject', 'local_coursedynamicrules', (object) [
            'fullname' => fullname($student),
            'subject' => 'Nactivity notification',
        ]);
        $expectedobserverintro = get_string('observer_notification_intro', 'local_coursedynamicrules', fullname($student));

        $this->assertEquals($teacher->id, $message->useridto);
        $this->assertEquals($expectedobserversubject, $message->subject);
        $this->assertStringContainsString($expectedobserverintro, $message->fullmessagehtml);
        $this->assertStringContainsString(fullname($student), $message->fullmessagehtml);
        $this->assertStringNotContainsString(fullname($teacher), $message->fullmessagehtml);
    }

    /**
     * Test matched user and observer recipients get different message formats.
     */
    public function test_execute_sends_different_messages_for_target_and_observer_roles(): void {
        global $DB;

        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();
        $sink = $this->redirectMessages();

        $course = $generator->create_course(['fullname' => 'Course placeholder test']);
        $teacher = $generator->create_user([
            'firstname' => 'TeacherFirst',
            'lastname' => 'TeacherLast',
        ]);
        $student = $generator->create_user([
            'firstname' => 'StudentFirst',
            'lastname' => 'StudentLast',
        ]);

        $teacherroleid = $DB->get_field('role', 'id', ['shortname' => 'editingteacher'], MUST_EXIST);
        $studentroleid = $DB->get_field('role', 'id', ['shortname' => 'student'], MUST_EXIST);

        $generator->enrol_user($teacher->id, $course->id, $teacherroleid);
        $generator->enrol_user($student->id, $course->id, $studentroleid);

        $record = (object) [
            'id' => 1,
            'ruleid' => 1,
            'actiontype' => 'sendnotification',
            'params' => json_encode([
                'messagesubject' => 'Nactivity notification',
                'messagebody' => '{$a-&gt;fullname} - {$a-&gt;firstname} - {$a-&gt;lastname}',
                'primaryroleids' => [$studentroleid],
                'copyroleids' => [$teacherroleid],
            ]),
        ];

        $action = new sendnotification_action($record, $course->id);
        $rulecontext = (object) [
            'courseid' => $course->id,
            'userid' => $student->id,
        ];

        $action->execute($rulecontext);

        $messages = $sink->get_messages_by_component('local_coursedynamicrules');

        $this->assertCount(2, $messages);

        $messagesbyrecipient = [];
        foreach ($messages as $message) {
            $messagesbyrecipient[$message->useridto] = $message;
        }

        $this->assertArrayHasKey($student->id, $messagesbyrecipient);
        $this->assertArrayHasKey($teacher->id, $messagesbyrecipient);

        $expectedobserversubject = get_string('observer_notification_subject', 'local_coursedynamicrules', (object) [
            'fullname' => fullname($student),
            'subject' => 'Nactivity notification',
        ]);
        $expectedobserverintro = get_string('observer_notification_intro', 'local_coursedynamicrules', fullname($student));

        $studentmessage = $messagesbyrecipient[$student->id];
        $teachermessage = $messagesbyrecipient[$teacher->id];

        $this->assertEquals('Nactivity notification', $studentmessage->subject);
        $this->assertStringNotContainsString($expectedobserverintro, $studentmessage->fullmessagehtml);
        $this->assertStringContainsString(fullname($student), $studentmessage->fullmessagehtml);
        $this->assertStringNotContainsString(fullname($teacher), $studentmessage->fullmessagehtml);

        $this->assertEquals($expectedobserversubject, $teachermessage->subject);
        $this->assertStringContainsString($expectedobserverintro, $teachermessage->fullmessagehtml);
        $this->assertStringContainsString(fullname($student), $teachermessage->fullmessagehtml);
        $this->assertStringNotContainsString(fullname($teacher), $teachermessage->fullmessagehtml);
    }
}
