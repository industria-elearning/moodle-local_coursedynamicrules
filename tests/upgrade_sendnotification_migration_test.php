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

defined('MOODLE_INTERNAL') || die();

/**
 * Tests sendnotification roles migration in plugin upgrade.
 *
 * @package    local_coursedynamicrules
 * @copyright  2026 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class upgrade_sendnotification_migration_test extends \advanced_testcase {
    /**
     * Test legacy role params are migrated to primary/copy keys.
     */
    public function test_upgrade_migrates_sendnotification_role_params(): void {
        global $DB, $CFG;

        $this->resetAfterTest(true);
        require_once($CFG->dirroot . '/local/coursedynamicrules/db/upgrade.php');

        $course = $this->getDataGenerator()->create_course();
        $ruleid = $DB->insert_record('local_coursedynamicrules_rule', (object) [
            'courseid' => $course->id,
            'name' => 'Migration test rule',
            'active' => 1,
            'timecreated' => time(),
            'timemodified' => time(),
        ]);

        $studentroleid = (int)$DB->get_field('role', 'id', ['shortname' => 'student'], MUST_EXIST);
        $teacherroleid = (int)$DB->get_field('role', 'id', ['shortname' => 'editingteacher'], MUST_EXIST);
        $managerroleid = (int)$DB->get_field('role', 'id', ['shortname' => 'manager'], MUST_EXIST);

        $singlelegacyactionid = $this->create_sendnotification_action($ruleid, [
            'messagesubject' => 'Subject',
            'messagebody' => 'Body',
            'roleids' => [$studentroleid],
        ]);
        $multilegacywithstudentactionid = $this->create_sendnotification_action($ruleid, [
            'messagesubject' => 'Subject',
            'messagebody' => 'Body',
            'roleids' => [$teacherroleid, $studentroleid, $managerroleid],
        ]);
        $multilegacynostudentactionid = $this->create_sendnotification_action($ruleid, [
            'messagesubject' => 'Subject',
            'messagebody' => 'Body',
            'roleids' => [$teacherroleid, $managerroleid],
        ]);

        \local_coursedynamicrules_upgrade_migrate_sendnotification_roles();

        $singlelegacyparams = json_decode(
            $DB->get_field('local_coursedynamicrules_action', 'params', ['id' => $singlelegacyactionid]),
            true
        );
        $multilegacywithstudentparams = json_decode(
            $DB->get_field('local_coursedynamicrules_action', 'params', ['id' => $multilegacywithstudentactionid]),
            true
        );
        $multilegacynostudentparams = json_decode(
            $DB->get_field('local_coursedynamicrules_action', 'params', ['id' => $multilegacynostudentactionid]),
            true
        );

        $this->assertSame([$studentroleid], $singlelegacyparams['primaryroleids']);
        $this->assertSame([], $singlelegacyparams['copyroleids']);
        $this->assertArrayNotHasKey('roleids', $singlelegacyparams);

        $this->assertSame([$studentroleid], $multilegacywithstudentparams['primaryroleids']);
        $this->assertEqualsCanonicalizing([$teacherroleid, $managerroleid], $multilegacywithstudentparams['copyroleids']);
        $this->assertArrayNotHasKey('roleids', $multilegacywithstudentparams);

        $this->assertSame([$teacherroleid], $multilegacynostudentparams['primaryroleids']);
        $this->assertSame([$managerroleid], $multilegacynostudentparams['copyroleids']);
        $this->assertArrayNotHasKey('roleids', $multilegacynostudentparams);
    }

    /**
     * Create sendnotification action record for tests.
     *
     * @param int   $ruleid Rule ID.
     * @param array $params Action params.
     * @return int Inserted action ID.
     */
    private function create_sendnotification_action(int $ruleid, array $params): int {
        global $DB;

        return (int)$DB->insert_record('local_coursedynamicrules_action', (object) [
            'ruleid' => $ruleid,
            'actiontype' => 'sendnotification',
            'params' => json_encode($params),
        ]);
    }
}
