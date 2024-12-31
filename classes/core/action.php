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

namespace local_coursedynamicrules\core;

use local_coursedynamicrules\form\actions\action_form;

/**
 * Class action
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class action {

    /** @var action_form|null */
    protected $actionform;

    /** @var string Type of the action example: sendnotification */
    protected $type;

    /** @var object Parameters of the action stored in DB */
    protected $params;

    /**
     * Action constructor.
     * @param object $record Record of the action stored in DB
     */
    public function __construct($record) {
        $this->type = $record->actiontype;
        $this->params = json_decode($record->params);
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
     * Execute the action
     * @param object $context Context of the rule
     */
    abstract public function execute($context);

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
}
