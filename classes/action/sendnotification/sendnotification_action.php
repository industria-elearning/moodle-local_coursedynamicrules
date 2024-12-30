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
use local_coursedynamicrules\core\rule;
use local_coursedynamicrules\form\actions\sendnotification_form;
use moodle_url;
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

        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
        $courseurl = new moodle_url('/course/view.php', ['id' => $courseid]);
        $courselink = '<a href="'.$courseurl->out(false).'">'.$courseurl->out(false).'</a>';

        $key = ['{$a-&gt;coursename}', '{$a-&gt;courselink}', '{$a-&gt;fullname}', '{$a-&gt;firstname}', '{$a-&gt;lastname}'];
        $value = [$course->fullname, $courselink, fullname($user), $user->firstname, $user->lastname];
        $messagebody = str_replace($key, $value, $messagebody);

        $smallmessagehtml = html_entity_decode($messagebody, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 );
        $smallmessagetext = $this->sanitize_html_message_twilio($smallmessagehtml);

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
        $message->smallmessage = $smallmessagetext;
        $message->notification = 1;

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
            $this->actionform = new sendnotification_form(
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
     * Saves the action after it has been edited (or created)
     * @param object $formdata
     */
    public function save_action($formdata) {
        global $DB;

        $licensestatus = rule::validate_licence_status();
        if (!$licensestatus->success) {
            return;
        }

        $params = [
            'messagesubject' => $formdata->messagesubject,
            'messagebody' => format_text($formdata->messagebody['text'], FORMAT_HTML),
        ];

        $action = new stdClass();
        $action->ruleid = $formdata->ruleid;
        $action->actiontype = $this->type;
        $action->params = json_encode($params);

        $this->set_data($action);

        $DB->insert_record('cdr_action', $action);
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

    /**
     * Sanitizes an HTML message for Twilio.
     *
     * This function takes an HTML message as input and returns a sanitized string
     * that is safe to be sent via Twilio.
     *
     * @param string $html The HTML message to be sanitized.
     * @return string The sanitized message.
     */
    protected function sanitize_html_message_twilio($html): string {
        // Check if the HTML message is empty or invalid.
        if (empty($html) || !is_string($html)) {
            return '';
        }

        // Convert all <a> tags to plain text.
        $html = preg_replace_callback('/<a\s+[^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/i', function ($matches) {
            // We return only the URL, we don't need the text.
            return $matches[1] . ' ';
        }, $html);

        // Remove all HTML tags.
        $text = strip_tags($html);

        // Decode HTML entities.
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5);

        return $text;
    }
}
