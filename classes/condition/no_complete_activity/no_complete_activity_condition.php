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

namespace local_coursedynamicrules\condition\no_complete_activity;

use completion_info;
use local_coursedynamicrules\core\condition;
use local_coursedynamicrules\core\rule;
use local_coursedynamicrules\form\conditions\no_complete_activity_form;
use stdClass;

/**
 * Class no_complete_activity_condition
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class no_complete_activity_condition extends condition {
    /** @var string type of condition */
    protected $type = "no_complete_activity";

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
        $this->conditionform = new no_complete_activity_form(
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

        $licensestatus = rule::validate_licence_status();
        if (!$licensestatus->success) {
            return false;
        }

        $courseid = $context->courseid;
        $userid = $context->userid;
        $cmid = $this->params->cmid;
        $expectedcompletiondate = $this->params->expectedcompletiondate;

        $now = time();

        if ($now < $expectedcompletiondate) {
            return false;
        }

        $modinfo = get_fast_modinfo($courseid, $userid);
        // Get in this form because the $modinfo->get_cm($cmid) throws an error if the activity module is not found.
        $cminfo = $modinfo->cms[$cmid];
        if (!$cminfo || $cminfo->deletioninprogress) {
            return false;
        }

        $completion = new completion_info($modinfo->get_course());

        $completiondata = $completion->get_data(
            (object)['id' => $cmid], false, $userid
        );

        // Return false if the user has completed the activity module because is not necessary execute the actions of the rule.
        if ($cminfo->completion == COMPLETION_TRACKING_MANUAL && $completiondata->completionstate == COMPLETION_COMPLETE) {
            return false;
        }

        // Return false if the user has completed the activity module with a passing grade
        // because is not necessary execute the actions of the rule.
        if ($cminfo->completion == COMPLETION_TRACKING_AUTOMATIC && $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
            return false;
        }

        // Return true if the user has not completed the activity module in the expected date.
        return true;
    }

    /**
     * Saves the condition after it has been edited (or created)
     * @param object $formdata
     */
    public function save_condition($formdata) {
        global $DB;
        $params = [
            'cmid' => $formdata->coursemodule,
            'expectedcompletiondate' => $formdata->expectedcompletiondate,
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
        return get_string('no_complete_activity', 'local_coursedynamicrules');
    }

    /**
     * Returns the description of the condition to visualization
     *
     * @return string
     */
    public function get_description() {
        $courseid = $this->courseid;
        $cmid = $this->params->cmid;
        $modinfo = get_fast_modinfo($courseid);
        $cms = $modinfo->get_cms();
        $cminfo = $cms[$cmid];

        if (!$cminfo) {
            return '';
        }
        $options = [
            'moddescription' => ucfirst($cminfo->modname) . " - " . $cminfo->name,
            'expectedcompletiondate' => userdate($this->params->expectedcompletiondate),
        ];
        $description = get_string('no_complete_activity_description', 'local_coursedynamicrules', $options);

        return $description;
    }
}
