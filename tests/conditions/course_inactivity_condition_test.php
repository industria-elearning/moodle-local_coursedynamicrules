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

namespace local_coursedynamicrules\conditions;

use local_coursedynamicrules\condition\course_inactivity\course_inactivity_condition;
use ReflectionClass;
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

    /**
     * Test setup.
     */
    public function setUp(): void {
        $this->resetAfterTest();
    }

    /**
     * Create a test condition instance.
     *
     * @param array $params Custom parameters (optional)
     * @return course_inactivity_condition
     */
    private function create_test_condition($params = []) {
        // Default parameters.
        $defaults = [
            'timeintervals' => 7,
            'intervalunit' => 'days',
            'basedate' => course_inactivity_condition::DATE_FROM_ENROLLMENT,
        ];

        $params = array_merge($defaults, $params);

        $conditionrecord = (object)[
            'ruleid' => 1,
            'conditiontype' => 'course_inactivity',
            'params' => json_encode($params),
        ];

        return new course_inactivity_condition($conditionrecord);
    }

    /**
     * Test save_condition method.
     */
    public function test_save_condition() {
        global $DB;

        // Create a mock form data object.
        $formdata = new stdClass();
        $formdata->ruleid = 2;
        $formdata->timeintervals = 14;
        $formdata->intervalunit = 'days';
        $formdata->basedate = course_inactivity_condition::DATE_FROM_NOW;

        // Create the condition.
        $condition = $this->create_test_condition();

        // Save the condition.
        $condition->save_condition($formdata);

        // Verify the record was saved correctly.
        $records = $DB->get_records('cdr_condition', ['ruleid' => 2]);
        $this->assertCount(1, $records);

        $record = reset($records);
        $this->assertEquals($this->type, $record->conditiontype);

        $params = json_decode($record->params);
        $this->assertEquals(14, $params->timeintervals);
        $this->assertEquals('days', $params->intervalunit);
        $this->assertEquals(course_inactivity_condition::DATE_FROM_NOW, $params->basedate);
    }

    /**
     * Test get_user_enrollment method by using reflection.
     */
    public function test_get_user_enrollment() {
        // Create test course.
        $course = $this->getDataGenerator()->create_course(['startdate' => time() - 86400 * 30]); // 30 days ago

        // Create test user.
        $user = $this->getDataGenerator()->create_user();

        // Enroll user in course.
        $enrolltime = strtotime('-15 days'); // 15 days ago
        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student', 'manual', $enrolltime);

        // Create condition.
        $condition = $this->create_test_condition();

        // Use reflection to access private method.
        $reflection = new ReflectionClass($condition);
        $method = $reflection->getMethod('get_user_enrollment');
        $method->setAccessible(true);

        // Call the method.
        $enrollment = $method->invoke($condition, $user->id, $course->id);

        // Verify result.
        $this->assertEquals($enrolltime, $enrollment->timestart);
    }

    /**
     * Test get_basedate method using reflection for all base date types.
     */
    public function test_get_basedate() {
        // Create test data with readable dates.
        $currenttime = time();
        $coursestartdate = strtotime('-30 days');
        $enrolltime = strtotime('-15 days');

        // Create course.
        $course = $this->getDataGenerator()->create_course(['startdate' => $coursestartdate]);

        // Create user and enroll.
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student', 'manual', $enrolltime);

        // Test DATE_FROM_ENROLLMENT.
        $condition = $this->create_test_condition([
            'basedate' => course_inactivity_condition::DATE_FROM_ENROLLMENT,
        ]);

        $reflection = new ReflectionClass($condition);
        $method = $reflection->getMethod('get_basedate');
        $method->setAccessible(true);

        $basedate = $method->invoke($condition, $course->id, $user->id);
        $this->assertEquals($enrolltime, $basedate->timestart, 'Failed to get basedate from enrollment.');

        // Test DATE_FROM_COURSE_START.
        $condition = $this->create_test_condition([
            'basedate' => course_inactivity_condition::DATE_FROM_COURSE_START,
        ]);

        $reflection = new ReflectionClass($condition);
        $method = $reflection->getMethod('get_basedate');
        $method->setAccessible(true);

        $basedate = $method->invoke($condition, $course->id, $user->id);
        $this->assertEquals($coursestartdate, $basedate->timestart, 'Failed to get basedate from course start.');

        // Test DATE_FROM_NOW with mocked time.
        $condition = $this->create_test_condition([
            'basedate' => course_inactivity_condition::DATE_FROM_NOW,
        ]);

        // Create a mock for the current class to override get_current_time.
        $mock = $this->getMockBuilder(course_inactivity_condition::class)
            ->setConstructorArgs([(object)[
                'ruleid' => 1,
                'conditiontype' => 'course_inactivity',
                'params' => json_encode([
                    'timeintervals' => 7,
                    'intervalunit' => 'days',
                    'basedate' => course_inactivity_condition::DATE_FROM_NOW,
                ]),
            ]])
            ->setMethods(['get_current_time'])
            ->getMock();

        $mock->method('get_current_time')
            ->willReturn($currenttime);

        $reflection = new ReflectionClass($mock);
        $method = $reflection->getMethod('get_basedate');
        $method->setAccessible(true);

        $basedate = $method->invoke($mock, $course->id, $user->id);
        $this->assertEquals($currenttime, $basedate->timestart, 'Failed to get basedate from now.');

        // Test invalid base date.
        $condition = $this->create_test_condition([
            'basedate' => 'invalid_date',
        ]);

        $reflection = new ReflectionClass($condition);
        $method = $reflection->getMethod('get_basedate');
        $method->setAccessible(true);

        $this->expectException(\moodle_exception::class);
        $method->invoke($condition, $course->id, $user->id);
    }

}
