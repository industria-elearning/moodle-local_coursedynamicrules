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

namespace local_coursedynamicrules\action\sendnotification;

use local_coursedynamicrules\core\action;
use local_coursedynamicrules\form\actions\sendnotification_form;
use stdClass;

/**
 * Class sendnotification_action
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sendnotification_action extends action {
    /** @var string type of the action, should be overridden by each action type */
    protected $type = 'sendnotification';

    /** @var string related user id to the event */

    /**
     * Executes the action
     * @param object $rulecontext Context of the rule
     *
     * @return mixed
     */
    public function execute($rulecontext) {
        global $DB;

        $userid = $rulecontext->userid;
        $courseid = $rulecontext->courseid;

        $messagesubject = $this->params->messagesubject;
        $messagebody = $this->params->messagebody;
        $messagesmallmessage = $this->params->messagesmallmessage;

        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

        $key = ['{$a->coursename}', '{$a->fullname}', '{$a->firstname}', '{$a->lastname}'];
        $value = [$course->fullname, fullname($user), $user->firstname, $user->lastname];
        $messagebody = str_replace($key, $value, $messagebody);

        $message = new \core\message\message();
        $message->component = 'local_coursedynamicrules';
        // Notification name from message.php.
        $message->name = 'coursedynamicrules_notification';
        $message->userfrom = $message->userfrom = \core_user::get_support_user();
        $message->userto = $userid;
        $message->subject = $messagesubject;
        $message->fullmessage = html_to_text($messagebody);
        $message->fullmessageformat = FORMAT_HTML;
        $message->fullmessagehtml = $messagebody;
        $message->smallmessage = $messagesmallmessage;
        $messageid = message_send($message);
        return $messageid;
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
    public function build_editform(
        $action=null, $customdata=null, $method='post', $target='', $attributes=null, $editable=true, $ajaxformdata=null) {
            $this->actionform = new sendnotification_form($action, $customdata, $method, $target, $attributes, $editable, $ajaxformdata);
    }

    /**
     * Saves the action after it has been edited (or created)
     * @param object $formdata
     */
    public function save_action($formdata) {
        global $DB;
        $params = [
            'messagesubject' => $formdata->messagesubject,
            'messagebody' => $formdata->messagebody,
        ];

        $action = new stdClass();
        $action->ruleid = $formdata->ruleid;
        $action->actiontype = $this->type;
        $action->params = json_encode($params);

        $this->set_data($action);

        $DB->insert_record('cdr_action', $action);
    }

    /**
     * Checks if the editing form was cancelled
     *
     * @return bool
     */
    public function is_cancelled() {
        return $this->actionform->is_cancelled();
    }

    /**
     * Gets submitted data from the edit form
     *
     * @return mixed
     */
    public function get_data() {
        return $this->actionform->get_data();
    }

    /**
     * Set the action data
     *
     * @param stdClass $actiondata the action data to set
     */
    public function set_data($action) {
        if ($action && $action->params) {
            $this->params = json_decode($action->params, true);
        }
    }

    /**
     * Returns the header of the action to visualization
     *
     * @return string
     */
    public function get_header() {
        return get_string('sendnotification', 'local_coursedynamicrules');
    }

    /**
     * Returns the description of the action to visualization
     *
     * @return string
     */
    public function get_description() {
        $messagesubject = $this->params->messagesubject;
        return get_string('sendnotification_description', 'local_coursedynamicrules', $messagesubject);
    }
}
