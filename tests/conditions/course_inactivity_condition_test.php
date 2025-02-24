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

    /**
     * Test calculate_interval method using reflection.
     */
    public function test_calculate_interval() {
        // Create condition.
        $condition = $this->create_test_condition();

        // Use reflection to access private method.
        $reflection = new ReflectionClass($condition);
        $method = $reflection->getMethod('calculate_interval');
        $method->setAccessible(true);

        // Create a base date that's easy to read and verify.
        $basedate = strtotime('2025-01-01 00:00:00');

        // Test days.
        $result = $method->invoke($condition, $basedate, 7, 'days');
        $expected = strtotime('2025-01-08 00:00:00');
        $this->assertEquals($expected, $result);
        $this->assertEquals(date('Y-m-d', $expected), date('Y-m-d', $result));

        // Test weeks.
        $result = $method->invoke($condition, $basedate, 2, 'weeks');
        $expected = strtotime('2025-01-15 00:00:00');
        $this->assertEquals($expected, $result);
        $this->assertEquals(date('Y-m-d', $expected), date('Y-m-d', $result));

        // Test months.
        $result = $method->invoke($condition, $basedate, 3, 'months');
        $expected = strtotime('2025-04-01 00:00:00');
        $this->assertEquals($expected, $result);
        $this->assertEquals(date('Y-m-d', $expected), date('Y-m-d', $result));
    }

    /**
     * Test get_current_time method.
     */
    public function test_get_current_time() {
        $condition = $this->create_test_condition();

        $before = time();
        $result = $condition->get_current_time();
        $after = time();

        // The result should be between before and after.
        $this->assertGreaterThanOrEqual($before, $result);
        $this->assertLessThanOrEqual($after, $result);
    }

    /**
     * Test for get_user_last_access method using reflection.
     */
    public function test_get_user_last_access() {
        // Create test course
        $course = $this->getDataGenerator()->create_course();

        // Create test user
        $user = $this->getDataGenerator()->create_user();

        // Enroll user in course.
        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student');

        // Simulate a user access to the course.
        $lastaccess = strtotime('2025-03-15 10:30:00');
        $accessrecord = new stdClass();
        $accessrecord->userid = $user->id;
        $accessrecord->courseid = $course->id;
        $accessrecord->timeaccess = $lastaccess;

        global $DB;
        $DB->insert_record('user_lastaccess', $accessrecord);

        // Create condition.
        $condition = $this->create_test_condition();

        // Use reflection to access private method.
        $reflection = new ReflectionClass($condition);
        $method = $reflection->getMethod('get_user_last_access');
        $method->setAccessible(true);

        // Call the method.
        $result = $method->invoke($condition, $course->id, $user->id);

        // Verify result.
        $this->assertEquals($lastaccess, $result);

        // Test with a user who has never accessed the course.
        $newuser = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($newuser->id, $course->id, 'student');

        $result = $method->invoke($condition, $course->id, $newuser->id);
        $this->assertFalse($result); // Should return false when no access record exists.
    }

    /**
     * Test is_within_interval method using reflection.
     */
    public function test_is_within_interval() {
        // Create condition.
        $condition = $this->create_test_condition();

        // Use reflection to access private method.
        $reflection = new ReflectionClass($condition);
        $method = $reflection->getMethod('is_within_interval');
        $method->setAccessible(true);

        // Test cases.
        $now = strtotime('2025-01-15 12:00:00');

        // Case 1: now is after end interval.
        $endinterval = strtotime('2025-01-10 12:00:00');
        $result = $method->invoke($condition, $now, $endinterval);
        $this->assertTrue($result);

        // Case 2: now is equal to end interval.
        $endinterval = strtotime('2025-01-15 12:00:00');
        $result = $method->invoke($condition, $now, $endinterval);
        $this->assertTrue($result);

        // Case 3: now is before end interval.
        $endinterval = strtotime('2025-01-20 12:00:00');
        $result = $method->invoke($condition, $now, $endinterval);
        $this->assertFalse($result);
    }

    /**
     * Test is_user_inactive method using reflection.
     */
    public function test_is_user_inactive() {
        // Create condition.
        $condition = $this->create_test_condition();

        // Use reflection to access private method.
        $reflection = new ReflectionClass($condition);
        $method = $reflection->getMethod('is_user_inactive');
        $method->setAccessible(true);

        // Test cases.
        $startinterval = strtotime('2025-01-15 12:00:00');

        // Case 1: User accessed before the start interval (is inactive).
        $lastaccess = strtotime('2025-01-10 12:00:00');
        $result = $method->invoke($condition, $lastaccess, $startinterval);
        $this->assertTrue($result);

        // Case 2: User accessed at the start interval (is active).
        $lastaccess = strtotime('2025-01-15 12:00:00');
        $result = $method->invoke($condition, $lastaccess, $startinterval);
        $this->assertFalse($result);

        // Case 3: User accessed after the start interval (is active).
        $lastaccess = strtotime('2025-01-20 12:00:00');
        $result = $method->invoke($condition, $lastaccess, $startinterval);
        $this->assertFalse($result);

        // Case 4: No last access (is inactive).
        $lastaccess = false;
        $result = $method->invoke($condition, $lastaccess, $startinterval);
        $this->assertTrue($result);
    }

    /**
     * Test check_inactivity_intervals method using reflection.
     */
    public function test_check_inactivity_intervals() {
        // Create a test condition with different parameters.
        $conditiondata = [
            'timeintervals' => '7,14,30', // Check inactivity at 7, 14, and 30 days.
            'intervalunit' => 'days',
            'basedate' => course_inactivity_condition::DATE_FROM_ENROLLMENT,
        ];

        // Create the condition record.
        $conditionrecord = new stdClass();
        $conditionrecord->ruleid = 1;
        $conditionrecord->conditiontype = 'course_inactivity';
        $conditionrecord->params = json_encode($conditiondata);

        // Create a mock for the current class to override get_current_time.
        $currenttime = strtotime('2025-01-15 12:00:00');
        $mock = $this->getMockBuilder(course_inactivity_condition::class)
            ->setConstructorArgs([$conditionrecord])
            ->setMethods(['get_current_time'])
            ->getMock();

        $mock->method('get_current_time')
            ->willReturn($currenttime);

        // Use reflection to access private method.
        $reflection = new ReflectionClass($mock);
        $method = $reflection->getMethod('check_inactivity_intervals');
        $method->setAccessible(true);

        // Create base date (enrollment date).
        $basedate = new stdClass();
        $basedate->timestart = strtotime('2025-01-01 12:00:00'); // Enrolled 14 days ago.

        // Test case 1: User accessed very recently (not inactive).
        $lastaccess = strtotime('2025-01-14 12:00:00'); // Accessed 1 day ago.
        $result = $method->invoke($mock, $lastaccess, $basedate);
        $this->assertFalse($result);

        // Test case 2: User never accessed (inactive).
        $lastaccess = false;
        $result = $method->invoke($mock, $lastaccess, $basedate);
        $this->assertTrue($result);

        // Test case 3: User accessed before the first interval (inactive).
        $lastaccess = strtotime('2025-01-03 12:00:00'); // Accessed 12 days ago (before 7 day interval).
        $result = $method->invoke($mock, $lastaccess, $basedate);
        $this->assertTrue($result);

        // Test case 4: User accessed after the relevant interval.
        // Set current time to be within the 7-day interval but not yet at 14 days.
        $currenttime = strtotime('2025-01-10 12:00:00'); // 9 days after enrollment

        $mock = $this->getMockBuilder(course_inactivity_condition::class)
            ->setConstructorArgs([$conditionrecord])
            ->setMethods(['get_current_time'])
            ->getMock();

        $mock->method('get_current_time')
            ->willReturn($currenttime);

        $reflection = new ReflectionClass($mock);
        $method = $reflection->getMethod('check_inactivity_intervals');
        $method->setAccessible(true);

        // User accessed 5 days ago (after the first 7-day interval started).
        $lastaccess = strtotime('2025-01-05 12:00:00');
        $result = $method->invoke($mock, $lastaccess, $basedate);
        $this->assertFalse($result);

        // Test case 5: Current time is before any intervals have elapsed.
        $currenttime = strtotime('2025-01-03 12:00:00'); // Only 2 days after enrollment.

        $mock = $this->getMockBuilder(course_inactivity_condition::class)
            ->setConstructorArgs([$conditionrecord])
            ->setMethods(['get_current_time'])
            ->getMock();

        $mock->method('get_current_time')
            ->willReturn($currenttime);

        $reflection = new ReflectionClass($mock);
        $method = $reflection->getMethod('check_inactivity_intervals');
        $method->setAccessible(true);

        $lastaccess = strtotime('2025-01-02 12:00:00');
        $result = $method->invoke($mock, $lastaccess, $basedate);
        $this->assertFalse($result); // No intervals have been reached yet.
    }

}
