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

/**
 * Class sendnotification_action
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sendnotification_action extends \local_coursedynamicrules\action\action_base {
    /** @var string type of the action, should be overridden by each condition type */
    protected $type = 'sendnotification';

    /**
     * Executes the action
     *
     * @return mixed
     */
    public function execute() {
        $userto = $this->params['userto'];
        $messagesubject = $this->params['messagesubject'];
        $messagebody = $this->params['messagebody'];
        $messagesmallmessage = $this->params['messagesmallmessage'];

        $message = new \core\message\message();
        $message->component = 'local_coursedynamicrules'; // Your plugin's name
        $message->name = 'coursedynamicrules_notification'; // Your notification name from message.php
        $message->userfrom = $message->userfrom = \core_user::get_support_user();
        $message->userto = $userto;
        $message->subject = $messagesubject;
        $message->fullmessage = html_to_text($messagebody);
        $message->fullmessageformat = FORMAT_HTML;
        $message->fullmessagehtml = $messagebody;
        $message->smallmessage = $messagesmallmessage;
        $messageid = message_send($message);
        return $messageid;
    }
}
