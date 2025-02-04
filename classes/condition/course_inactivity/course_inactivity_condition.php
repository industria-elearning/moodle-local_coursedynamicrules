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

namespace local_coursedynamicrules\condition\course_inactivity;

use local_coursedynamicrules\core\condition;
use local_coursedynamicrules\core\rule;
use local_coursedynamicrules\form\conditions\course_inactivity_form;
use stdClass;

/**
 * Class course_inactivity_condition
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_inactivity_condition extends condition {

    /** @var string base date for evaluating the intervals is start date of user enrolment*/
    const DATE_FROM_ENROLLMENT = 'enrollment';

    /** @var string base date for evaluating the intervals is start date of course */
    const DATE_FROM_COURSE_START = 'coursestart';

    /** @var string base date for evaluating the intervals is current date */
    const DATE_FROM_NOW = 'now';

    /** @var string type of condition */
    protected $type = "course_inactivity";

    /**
     * Creates and returns an instance of the form for editing the item
     *
     * @param mixed $action the action attribute for the form. If empty defaults to auto detect the
     *              current url. If a moodle_url object then outputs params as hidden variables.
     * @param mixed $customdata if your form defintion method needs access to data such as $course
     *              $cm, etc. to construct the form definition then pass it in this array. You can
     *              use globals for somethings.
     * @param string $method if you set this to anything other than 'post' then _GET and _POST will
     *               be merged and used as incoming data to the form.
     * @param string $target target frame for form submission. You will rarely use this. Don't use
     *               it if you don't need to as the target attribute is deprecated in xhtml strict.
     * @param mixed $attributes you can pass a string of html attributes here or an array.
     *               Special attribute 'data-random-ids' will randomise generated elements ids. This
     *               is necessary when there are several forms on the same page.
     *               Special attribute 'data-double-submit-protection' set to 'off' will turn off
     *               double-submit protection JavaScript - this may be necessary if your form sends
     *               downloadable files in response to a submit button, and can't call
     *               \core_form\util::form_download_complete();
     * @param bool $editable
     * @param array $ajaxformdata Forms submitted via ajax, must pass their data here, instead of relying on _GET and _POST.
     */
    public function build_editform($action=null, $customdata=null, $method='post', $target='', $attributes=null, $editable=true,
        $ajaxformdata=null) {
        $this->conditionform = new course_inactivity_form(
            $action,
            $customdata,
            $method,
            $target,
            $attributes,
            $editable,
            $ajaxformdata
        );
    }

    /**
     * Evaluate the condition and return true if the condition is met
     *
     * @param object $context Context of the rule
     * @return bool
     */
    public function evaluate($context) {
        global $DB;

        $licensestatus = rule::validate_licence_status();
        if (!$licensestatus->success) {
            return false;
        }

        $courseid = $context->courseid;
        $userid = $context->userid;

        $lastaccess = $this->get_user_last_access($courseid, $userid);

        // If user has never accessed the course.
        if (!$lastaccess) {
            return true;
        }

        $basedate = $this->get_basedate($courseid, $userid);

        return $this->check_inactivity_intervals($lastaccess, $basedate);
    }

    /**
     * Get user's last access to the course
     * @param int $courseid Course ID
     * @param int $userid User ID
     * @return int
     */
    private function get_user_last_access($courseid, $userid) {
        global $DB;

        return $DB->get_field('user_lastaccess', 'timeaccess', [
            'courseid' => $courseid,
            'userid' => $userid,
        ]);
    }

    /**
     * Get user's enrollment details
     * @param int $userid User ID
     * @param int $courseid Course ID
     * @return stdClass
     */
    private function get_user_enrollment($userid, $courseid) {
        global $DB;

        return $DB->get_record_sql(
            "SELECT timestart
             FROM {user_enrolments} ue
             JOIN {enrol} e ON e.id = ue.enrolid
             WHERE ue.userid = :userid AND e.courseid = :courseid",
            ['userid' => $userid, 'courseid' => $courseid],
            MUST_EXIST
        );
    }

    /**
     * Check if user is inactive during specific interval based on the last access and enrollment start
     *
     * @param int $lastaccess User last access in timestamp
     * @param stdClass $basedate Enrollment start in timestamp
     * @return bool True if user is inactive during the interval, false otherwise
     */
    private function check_inactivity_intervals($lastaccess, $basedate) {
        $timeintervals = explode(',', $this->params->timeintervals);
        $intervalunit = $this->params->intervalunit;

        $now = time();
        $prevtimeinterval = 0;

        foreach ($timeintervals as $timeinterval) {
            $startinterval = $this->calculate_interval($basedate->timestart, $prevtimeinterval, $intervalunit);
            $endinterval = $this->calculate_interval($basedate->timestart, $timeinterval, $intervalunit);

            if (!$this->is_within_interval($now, $endinterval)) {
                return false;
            }

            if ($this->is_user_inactive($lastaccess, $startinterval)) {
                return true;
            }

            $prevtimeinterval = $timeinterval;
        }

        return false;
    }

    /**
     * Get the base date to calculate the intervals
     *
     * @param int $courseid Course ID
     * @param int $userid User ID
     * @return stdClass
     */
    private function get_basedate($courseid, $userid) {
        $basedate = new stdClass();
        $basedate->timestart = 0;

        $basedatetype = $this->params->basedate;

        switch ($basedatetype) {
            case self::DATE_FROM_ENROLLMENT:
                $enrollment = $this->get_user_enrollment($userid, $courseid);
                $basedate->timestart = $enrollment->timestart;
                break;
            case self::DATE_FROM_COURSE_START:
                $course = get_course($courseid);
                $basedate->timestart = $course->startdate;
                break;
            case self::DATE_FROM_NOW:
                $basedate->timestart = time();
                break;
            default:
                throw new \moodle_exception('invalidbasedate', 'local_coursedynamicrules', '', $basedatetype);

        }

        return $basedate;
    }

    /**
     * Calculate the interval based on the start time, interval and unit
     * This function adds the interval to the base time
     *
     * @param int $basetime Base time
     * @param int $interval Interval
     * @param string $unit Unit e.g. days, weeks, months
     * @return int
     */
    private function calculate_interval($basetime, $interval, $unit) {
        return strtotime("+$interval $unit", $basetime);
    }

    /**
     * Check if the current time is within the interval.
     *
     * @param int $now The current time.
     * @param int $endinterval Time at which the interval ends
     * @return bool True if within the interval, false otherwise.
     */
    private function is_within_interval($now, $endinterval) {
        return $now >= $endinterval;
    }

    /**
     * Check if user is inactive during specific interval
     *
     * @param int $lastaccess User last access in timestamp
     * @param int $startinterval Timestamp from which the interval starts
     * @return bool True if user is inactive during the interval, false otherwise
     */
    private function is_user_inactive($lastaccess, $startinterval) {
        return $lastaccess < $startinterval;
    }

    /**
     * Saves the condition after it has been edited (or created)
     * @param object $formdata
     */
    public function save_condition($formdata) {
        global $DB;

        $params = [
            'timeintervals' => $formdata->timeintervals,
            'intervalunit' => $formdata->intervalunit,
            'basedate' => $formdata->basedate,
        ];

        $condition = new stdClass();
        $condition->ruleid = $formdata->ruleid;
        $condition->conditiontype = $this->type;
        $condition->params = json_encode($params);

        $this->set_data($condition);

        $DB->insert_record('cdr_condition', $condition);
    }

    /**
     * Returns the header of the condition to visualization
     *
     * @return string
     */
    public function get_header() {
        return get_string('course_inactivity', 'local_coursedynamicrules');
    }

    /**
     * Returns the description of the condition to visualization
     *
     * @return string
     */
    public function get_description() {
        $periodvalue = $this->params->periodvalue;
        $periodunit = $this->params->periodunit;

        $periodunitstr = get_string($periodunit, 'local_coursedynamicrules');
        $options = [
            'periodvalue' => $periodvalue,
            'periodunit' => strtolower($periodunitstr),
        ];
        $description = get_string('no_course_access_description', 'local_coursedynamicrules', $options);

        return $description;
    }
}
