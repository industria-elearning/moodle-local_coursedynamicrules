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

namespace local_coursedynamicrules\condition\passgrade;

use local_coursedynamicrules\condition\condition_base;
use stdClass;

/**
 * Class passgrade_condition
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class passgrade_condition extends condition_base {
    /** @var string type of condition */
    protected $type = "passgrade";

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
        global $DB, $CFG;
        $customdata = [];
        // $editconditionurl = new moodle_url('/local/coursedynamicrules/editcondition.php', []);

        $this->conditionform = new passgrade_form($action, $customdata, $method, $target, $attributes, $editable, $ajaxformdata);

    }

    /**
     * Validate if the event is instanceof the event type for this condition
     *
     * @param \core\event\course_module_completion_updated $event information about the event obtained from the event handler
     *
     * @return bool
     */
    protected function is_instace_of_event($event) {
        return $event instanceof \core\event\course_module_completion_updated;
    }

    /**
     * Validates the condition
     *
     * @param \core\event\course_module_completion_updated $event information about the event obtained from the event handler
     *
     * @return bool
     */
    public function validate($event) {
        global $DB;
        if (!$this->is_instace_of_event($event) || $this->condition->conditiontype !== $this->type) {
            return false;
        }
        $eventdata = $event->get_data();
        $otherdata = $eventdata['other'];
        $courseid = $eventdata["courseid"];
        $cmid = $eventdata["contextinstanceid"];
        $relateduserid = $eventdata["relateduserid"];
        $completionstate = $otherdata['completionstate'];

        $modinfo = get_fast_modinfo($courseid, $relateduserid);
        $cminfo = $modinfo->get_cm($cmid);

        if ($cminfo->completion != COMPLETION_TRACKING_AUTOMATIC) {
            return false;
        }

        return $completionstate == COMPLETION_COMPLETE_PASS;
    }

    /**
     * Saves the condition after it has been edited (or created)
     */
    public function save_condition() {
    }
}
