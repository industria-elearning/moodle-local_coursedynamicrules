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

use stdClass;

/**
 * Class condition_base
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class condition_base {

    /** @var string type of the condition, should be overridden by each condition type */
    protected $type;

    /** @var condition_form|null */
    protected $conditionform;

    /** @var  \stdClass|null condition data that represents the condition record on the database */
    protected $condition;

    /** @var  \stdClass|null condition parameters stored in the database */
    protected $params;

    /**
     * constructor
     * @param stdClass|null $condition condition data that represents the condition record on the database
     */
    public function __construct($condition = null) {
        $this->condition = $condition;
        if ($condition && $condition->params) {
            $this->params = json_decode($condition->params, true);
        }
    }

    /**
     * Displays the form for editing an condition
     *
     * this function only can used after the call of build_editform()
     */
    public function show_editform() {
        $this->conditionform->display();
    }

    /**
     * Checks if the editing form was cancelled
     *
     * @return bool
     */
    public function is_cancelled() {
        return $this->conditionform->is_cancelled();
    }

    /**
     * Gets submitted data from the edit form and saves it in $this->condition
     *
     * @return bool
     */
    public function get_data() {
        if ($this->condition !== null) {
            return true;
        }
        if ($this->condition = $this->conditionform->get_data()) {
            return true;
        }
        return false;
    }

    /**
     * Set the condition data (to be used by data generators).
     *
     * @param stdClass $conditiondata the condition data to set
     */
    public function set_data($conditiondata) {
        $this->condition = $conditiondata;
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
    abstract public function build_editform(
        $action=null, $customdata=null, $method='post', $target='', $attributes=null, $editable=true, $ajaxformdata=null);

    /**
     * Saves the condition after it has been edited (or created)
     */
    abstract public function save_condition();

    /**
     * Returns the formatted name of the condition for the complete form or response view
     *
     * @param stdClass $condition
     * @param bool $withpostfix
     * @return string
     */
    public function get_display_name($condition, $withpostfix = true) {
        return format_text($condition->name, FORMAT_HTML, ['noclean' => true, 'para' => false]) .
                ($withpostfix ? $this->get_display_name_postfix($condition) : '');
    }

    /**
     * Returns the postfix to be appended to the display name that is based on other settings
     *
     * @param stdClass $condition
     * @return string
     */
    public function get_display_name_postfix($condition) {
        return '';
    }

    /**
     * Validate if the event is instanceof the event type for this condition
     *
     * @param \core\event\base $event information about the event obtained from the event handler
     * @return bool
     */
    abstract protected function is_instace_of_event($event);

    /**
     * Validates the condition
     * Use this function to validate each one of the parameters of the condition that must be fulfilled to return true
     *
     * @param \core\event\base $event information about the event obtained from the event handler
     *
     * @return bool
     */
    abstract public function validate($event);

}
