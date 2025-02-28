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

    /** @var int indicate time in hours to interval window */
    const CRON_INTERVAL_HOURS = 6;

    /**
     * @var string interval is a custom set of comma-separated values
     * for interval evaluations. For example, "7,14,30" means the condition will be
     * evaluated at 7 days, 14 days, and 30 days.
     */
    const INTERVAL_CUSTOM = 'custom';

    /** @var string interval is recurring based on the specified date type, e.g., every 7 days */
    const INTERVAL_RECURRING = 'recurring';


    /** @var string type of condition */
    protected $type = "course_inactivity";

    /**
     * @var int $currenttime The current timestamp, used for more consistence in time calculations
     */
    protected $currenttime;

    /**
     * course_inactivity_condition constructor.
     * @param object $record Record of the condition stored in DB
     * @param int $courseid the course id where the action is applied.
     * @param int $currenttime Only for testing purposes, to set the current time
     */
    public function __construct($record, $courseid = null, $currenttime = null) {
        parent::__construct($record, $courseid);
        $this->currenttime = $currenttime ?? time();
    }

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
     * @return bool True if the condition is met, false otherwise
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

        $basedate = $this->get_basedate($courseid, $userid);

        if ($this->params->intervaltype == self::INTERVAL_CUSTOM) {
            return $this->check_inactivity_intervals($lastaccess, $basedate->timestart);
        } else if ($this->params->intervaltype == self::INTERVAL_RECURRING) {
            return $this->check_recurring_inactivity($lastaccess, $basedate->timestart);
        }

        return false;

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
     * Check if user is inactive during the intervals
     *
     * @param int $lastaccess User last access in timestamp
     * @param int $basetime Base timestamp to calculate the intervals, e.g., enrollment date, course start date, etc.
     * @return bool True if user is inactive during the interval, false otherwise
     */
    private function check_inactivity_intervals($lastaccess, $basetime) {
        $timeintervals = explode(',', $this->params->timeintervals);
        $intervalunit = $this->params->intervalunit;

        $prevtimeinterval = 0;

        foreach ($timeintervals as $timeinterval) {
            $startinterval = $this->add_time_interval($basetime, $prevtimeinterval, $intervalunit);
            $endinterval = $this->add_time_interval($basetime, $timeinterval, $intervalunit);
            $timewindow = $this->add_time_interval($endinterval, self::CRON_INTERVAL_HOURS, 'hours');

            if ($this->is_within_interval_window($this->currenttime, $endinterval, $timewindow)
                && $this->is_user_inactive($lastaccess, $startinterval)) {
                return true;
            }

            $prevtimeinterval = $timeinterval;
        }

        return false;
    }

    /**
     * Check if user is inactive during the recurring interval
     *
     * @param int $lastaccess User last access in timestamp
     * @param int $basetime Base date to calculate the intervals, e.g., enrollment date, course start date, etc.
     * @return bool True if user is inactive during the interval, false otherwise
     */
    private function check_recurring_inactivity($lastaccess, $basetime) {
        $interval = $this->params->timeintervals;
        $intervalunit = $this->params->intervalunit;

        // Calculate expected execution time of the first interval.
        $firstintervaltime = $this->add_time_interval($basetime, $interval, $intervalunit);
        $intervalspassed = $this->count_completed_intervals($basetime, $this->currenttime, $firstintervaltime);

        $currentinterval = $intervalspassed * $interval;
        $prevtimeinterval = $currentinterval - $interval;

        $startinterval = $this->add_time_interval($basetime, $prevtimeinterval, $intervalunit);
        $endinterval = $this->add_time_interval($basetime, $currentinterval, $intervalunit);

        $timewindow = $this->add_time_interval($endinterval, self::CRON_INTERVAL_HOURS, 'hours');

        return $this->is_within_interval_window($this->currenttime, $endinterval, $timewindow)
            && $this->is_user_inactive($lastaccess, $startinterval);

    }

    /**
     * Calculates the next execution time based on the base timestamp,
     * the current time, and a given interval in a specified time unit.
     *
     * @param int $basetime Base timestamp example: enrollment date, course start date, etc.
     * @param int $currenttime Current timestamp
     * @param int $interval Interval value (e.g., 7 for 7 days)
     * @param string $unit Time unit ('days', 'weeks', 'months', etc.)
     * @return int Timestamp of the last valid execution time
     */
    private function get_next_execution_time($basetime, $currenttime, $interval, $unit) {

        // Calculate expected execution time of the first interval.
        $firstintervaltime = $this->add_time_interval($basetime, $interval, $unit);
        $intervalspassed = $this->count_completed_intervals($basetime, $currenttime, $firstintervaltime);
        return calculate_last_execution_time($basetime, $intervalspassed, $interval, $unit);
    }

    /**
     * Determines how many full intervals have passed since the basetime
     *
     * @param int $basetime Base timestamp example: enrollment date, course start date, etc.
     * @param int $currenttime Current timestamp
     * @param int $firstintervaltime Timestamp of the expected execution time of the first interval
     * @return int Number of completed intervals
     */
    private function count_completed_intervals($basetime, $currenttime, $firstintervaltime) {
        $intervalduration = $firstintervaltime - $basetime; // Duration of one interval.
        $elapsedtime = $currenttime - $basetime; // Time elapsed since basetime.
        return floor($elapsedtime / $intervalduration); // Number of completed intervals.
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

        $basedatetype = $this->params->basedatetype;

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
                $basedate->timestart = $this->currenttime;
                break;
            default:
                throw new \moodle_exception('invalidbasedate', 'local_coursedynamicrules', '', $basedatetype);

        }

        return $basedate;
    }

    /**
     * Calculates a future timestamp by adding a specific time interval to a base time.
     *
     * This function takes a base timestamp and adds a specified amount of time
     * (e.g., days, weeks, months) to it, returning the resulting timestamp.
     *
     * @param int $basetime The starting timestamp from which the additional time will be added.
     * @param int $additionaltime The amount of time to be added (e.g., 7 for 7 days).
     * @param string $unit The unit of time (e.g., 'days', 'weeks', 'months').
     * @return int The resulting timestamp after adding the interval.
     */
    private function add_time_interval($basetime, $additionaltime, $unit) {
        return strtotime("+$additionaltime $unit", $basetime);
    }

    /**
     * Check if the current time is within the interval window
     * This is used to avoid evaluating the condition as true every time it is run
     *
     * @param int $currenttime Current time
     * @param int $endinterval Time at wich is expected to evaluate the condition for the interval
     * @param int $timewindow Adittional time to avoid exacly match the end of interval
     * @return bool True if the current time is within the interval window, false otherwise
     */
    private function is_within_interval_window($currenttime, $endinterval, $timewindow) {
        return $currenttime >= $endinterval && $currenttime <= $timewindow;
    }

    /**
     * Check if user is inactive during specific interval
     *
     * @param int|null $lastaccess User last access in timestamp or null if user has never accessed the course
     * @param int $startinterval Timestamp from which the interval starts
     * @return bool True if user is inactive during the interval, false otherwise
     */
    private function is_user_inactive($lastaccess, $startinterval) {
        // If user has never accessed the course.
        if (!$lastaccess) {
            return true;
        }
        return $lastaccess < $startinterval;
    }

    /**
     * Saves the condition after it has been edited (or created)
     * @param object $formdata
     */
    public function save_condition($formdata) {
        global $DB;

        $timeintervals = $formdata->intervaltype == self::INTERVAL_CUSTOM ?
            $formdata->customintervals : $formdata->recurringinterval;

        $params = [
            'intervaltype' => $formdata->intervaltype,
            'timeintervals' => $timeintervals,
            'intervalunit' => $formdata->intervalunit,
            'basedatetype' => $formdata->basedatetype,
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
        return 'Course inactivity condition';
    }
}
