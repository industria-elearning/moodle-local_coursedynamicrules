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

namespace local_coursedynamicrules\condition;

use local_coursedynamicrules\condition\course_inactivity\course_inactivity_condition;
use stdClass;

/**
 * Tests for Course dynamic rules
 *
 * @package    local_coursedynamicrules
 * @category   test
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class course_inactivity_condition_test extends \advanced_testcase {

    /**  @var string  $type Type of the condition */
    private $type = 'course_inactivity';

    /** @var int $currenttime Current time for testing */
    private $currenttime;

    /** @var int $coursestarttime Course start time for testing */
    private $coursestarttime;

    /** @var int $courseendtime Course end time for testing */
    private $courseendtime;

    /** @var int $enrolltime User enrollment time for testing */
    private $enrolltime;

    /** @var int $ruleid Rule ID for testing */
    private $ruleid;

    /**
     * Test setup.
     */
    public function setUp(): void {
        $this->resetAfterTest(true);

        $this->currenttime = strtotime('2025-01-17 12:00:00');
        $this->coursestarttime = strtotime('2025-01-01 12:00:00');
        $this->courseendtime = strtotime('2025-02-10 12:00:00');
        $this->enrolltime = strtotime('2025-01-10 8:00:00');

        $course = $this->getDataGenerator()->create_course(['startdate' => $this->coursestarttime]);
        $user = $this->getDataGenerator()->create_user();

        /** @var \local_coursedynamicrules_generator  $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('local_coursedynamicrules');

        $rule = $generator->create_rule($course->id, [$user]);
        $this->ruleid = $rule->get_id();

    }

    /**
     * Create a test condition instance.
     *
     * @param array $params Custom parameters (optional)
     * @return course_inactivity_condition
     */
    private function create_test_condition($params = [], $currentime = null) {
        // Default parameters.
        $defaultparams = [
            'intervaltype' => course_inactivity_condition::INTERVAL_CUSTOM,
            'timeintervals' => '7,14,21',
            'intervalunit' => 'days',
            'basedatetype' => course_inactivity_condition::DATE_FROM_ENROLLMENT,
        ];

        $params = array_merge($defaultparams, $params);

        $conditionrecord = (object)[
            'ruleid' => 1,
            'conditiontype' => 'course_inactivity',
            'params' => json_encode($params),
        ];

        $currentime = $currentime ?? $this->currenttime;

        return new course_inactivity_condition($conditionrecord, null, $currentime);
    }


    /**
     * Test save_condition method.
     */
    public function test_save_condition() {
        global $DB;

        $intervaltype = course_inactivity_condition::INTERVAL_CUSTOM;
        $customintervals = '7,14,21';
        $intervalunit = 'days';
        $basedatetype = course_inactivity_condition::DATE_FROM_NOW;

        // Create a mock condition data object.
        $conditiondata = new stdClass();
        $conditiondata->intervaltype = $intervaltype;
        $conditiondata->customintervals = $customintervals;
        $conditiondata->ruleid = $this->ruleid;
        $conditiondata->intervalunit = $intervalunit;
        $conditiondata->basedatetype = $basedatetype;

        // Create the condition.
        $condition = $this->create_test_condition();

        // Save the condition.
        $condition->save_condition($conditiondata);

        $records = $DB->get_records('cdr_condition', ['ruleid' => $this->ruleid]);

        $record = reset($records);
        $this->assertEquals($this->type, $record->conditiontype);
        $this->assertEquals($this->ruleid, $record->ruleid);

        $params = json_decode($record->params);
        $this->assertEquals($intervaltype, $params->intervaltype);
        $this->assertEquals($customintervals, $params->timeintervals);
        $this->assertEquals($intervalunit, $params->intervalunit);
        $this->assertEquals(course_inactivity_condition::DATE_FROM_NOW, $params->basedatetype);
    }

    /**
     * Provider for test_evaluate.
     */
    public static function evaluate_provider(): array {
        // First interval: 7 days ends on 2025-01-17 12:00:00.
        // Second interval: 14 days ends on 2025-01-24 12:00:00.
        // Third interval: 21 days ends on 2025-01-31 12:00:00.
        return [
            'before course start' => [
                strtotime('2024-12-31 12:00:00'), // Current time, one day before the course start.
                0, // Last access time.
                false, // Expected result.
            ],
            'after course end' => [
                strtotime('2025-02-11 12:00:00'), // Current time, one day after the course end.
                strtotime('2025-01-30 08:00:00'), // Last access time.
                false, // Expected result.
            ],
            'before intervals start' => [
                strtotime('2025-01-09 12:00:00'), // Current time, one day before intervals start.
                strtotime('2025-01-11 08:10:00'), // Last access time.
                false, // Expected result.
            ],
            'before first interval' => [
                strtotime('2025-01-16 12:00:00'), // Current time, one day before the end of the first interval.
                strtotime('2025-01-11 08:20:00'), // Last access time.
                false, // Expected result.
            ],
            'before second interval' => [
                strtotime('2025-01-21 12:00:00'), // Current time, one day before the end of the second interval.
                strtotime('2025-01-11 08:30:00'), // Last access time.
                false, // Expected result.
            ],
            'after last interval' => [
                strtotime('2025-02-01 12:00:00'), // One day after the end of the third interval.
                strtotime('2025-01-11 08:40:00'), // Last access time.
                false, // Expected result.
            ],
            'access in first interval' => [
                strtotime('2025-01-17 12:00:00'), // Current time, 7 days after the first interval starts.
                strtotime('2025-01-11 08:00:00'), // Last access time, one day after the first interval starts.
                false, // Expected result.
            ],
            'access in second interval' => [
                strtotime('2025-01-24 12:00:00'), // Current time, 14 days after the first interval starts.
                strtotime('2025-01-18 08:00:00'), // Last access time, one day after the second interval starts.
                false, // Expected result.
            ],
            'hour 00:00' => [
                strtotime('2025-01-17 00:00:00'), // Current time, 7 days after the first interval starts at 00:00.
                strtotime('2025-01-11 08:00:00'), // Last access time, one day after the first interval starts.
                false, // Expected result.
            ],
            'hour 06:00' => [
                strtotime('2025-01-17 06:00:00'), // Current time, 7 days after the first interval starts at 06:00.
                strtotime('2025-01-11 08:00:00'), // Last access time, one day after the first interval starts.
                false, // Expected result.
            ],
            'hour 18:00' => [
                strtotime('2025-01-17 18:00:00'), // Current time, 7 days after the first interval starts at 18:00.
                strtotime('2025-01-11 08:00:00'), // Last access time, one day after the first interval starts.
                false, // Expected result.
            ],
            'without access' => [
                strtotime('2025-01-17 12:00:00'), // Current time, 7 days after the first interval starts.
                0, // Last access time.
                true, // Expected result.
            ],
            'access in first but not in second interval' => [
                strtotime('2025-01-24 12:00:00'), // Current time, 14 days after the first interval starts.
                strtotime('2025-01-11 08:00:00'), // Last access time, one day after the first interval starts.
                true, // Expected result.
            ],
        ];
    }

    /**
     * Test for evaluate method with custom intervals.
     *
     * @dataProvider evaluate_provider
     */
    public function test_evaluate_for_custom_intervals($currentime, $lastaccess, $expected) {
        // Create the condition.
        $params = [
            'intervaltype' => course_inactivity_condition::INTERVAL_CUSTOM,
        ];
        $condition = $this->create_test_condition($params, $currentime);
        $course = $this->getDataGenerator()->create_course(
            ['startdate' => $this->coursestarttime, 'enddate' => $this->courseendtime]
        );
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student', 'manual', $this->enrolltime);

        $generator = $this->getDataGenerator()->get_plugin_generator('local_coursedynamicrules');
        $generator->create_user_lastaccess($user->id, $course->id, $lastaccess);

        // Evaluate the condition.
        $result = $condition->evaluate((object) ['courseid' => $course->id, 'userid' => $user->id]);

        // Verify the result.
        $this->assertEquals($expected, $result);
    }

    /**
     * Test for evaluate method with custom intervals.
     *
     * @dataProvider evaluate_provider
     */
    public function test_evaluate_for_recurring_interval($currentime, $lastaccess, $expected) {
        // Create the condition.
        $params = [
            'intervaltype' => course_inactivity_condition::INTERVAL_RECURRING,
            'timeintervals' => '7',
        ];
        $condition = $this->create_test_condition($params, $currentime);
        $course = $this->getDataGenerator()->create_course(
            ['startdate' => $this->coursestarttime, 'enddate' => $this->courseendtime]
        );
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student', 'manual', $this->enrolltime);

        $generator = $this->getDataGenerator()->get_plugin_generator('local_coursedynamicrules');
        $generator->create_user_lastaccess($user->id, $course->id, $lastaccess);

        // Evaluate the condition.
        $result = $condition->evaluate((object) ['courseid' => $course->id, 'userid' => $user->id]);

        // Verify the result.
        $this->assertEquals($expected, $result);
    }


}
