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

namespace local_coursedynamicrules\action;

use stdClass;

/**
 * Class action_base
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class action_base {
    /** @var string type of the action, should be overridden by each action type */
    protected $type;
    /** @var action_form|null */
    protected $actionform;
    /** @var  \stdClass|null action data that represents the action record on the database */
    protected $action;
    /** @var  \stdClass|null action parameters stored in the database */
    protected $params;

    /**
     * constructor
     * @param stdClass|null $action action data that represents the action record on the database
     */
    public function __construct($action = null) {
        $this->action = $action;
        if ($action && $action->params) {
            $this->params = json_decode($action->params, true);
        }
    }

    /**
     * Displays the form for editing an action
     *
     * this function only can used after the call of build_editform()
     */
    public function show_editform() {
        $this->actionform->display();
    }

    /**
     * Executes the action
     *
     * @return mixed
     */
    abstract public function execute();

    /**
     * Creates and returns an instance of the form for editing the action
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
     * Checks if the editing form was cancelled
     *
     * @return bool
     */
    abstract public function is_cancelled();

    /**
     * Gets submitted data from the edit form
     *
     * @return mixed
     */
    abstract public function get_data();

    /**
     * Returns the header of the action to visualization
     *
     * @return string
     */
    abstract public function get_header();

    /**
     * Returns the description of the action to visualization
     *
     * @return string
     */
    abstract public function get_description();

    /**
     * Saves the action after it has been edited (or created)
     * @param object $formdata
     */
    abstract public function save_action($formdata);

    /**
     * Sets extra data for the action
     * @param array $data
     */
    public function set_extra_data($data) {
    }
}


