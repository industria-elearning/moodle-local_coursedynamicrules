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

namespace local_coursedynamicrules\form\actions;

use moodle_url;

/**
 * Class sendnotification_form
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sendnotification_form extends action_form {
    /** @var string type of action */
    protected $type = "sendnotification";

    /**
     * Form definition
     *
     * @return void
     */
    public function definition() {
        global $OUTPUT, $DB;
        $mform = $this->_form;
        $customdata = $this->_customdata;
        $ruleid = $customdata['ruleid'];

        $notification = $OUTPUT->notification(
            get_string('notification_action_info', 'local_coursedynamicrules'),
            \core\output\notification::NOTIFY_INFO
        );
        $mform->addElement('html', $notification);

        // Check if the messaging plugins are installed.
        if (!$DB->record_exists('config_plugins', ['plugin' => 'local_datacurso_msghub', 'name' => 'version']) ||
            !$DB->record_exists('config_plugins', ['plugin' => 'message_datacurso_msghub', 'name' => 'version'])) {
            $plugininfo = $OUTPUT->notification(
                get_string('missing_plugins_warning', 'local_coursedynamicrules'),
                \core\output\notification::NOTIFY_WARNING
            );
            $mform->addElement('html', $plugininfo);
        } else {
            $enabledproviders = get_config(
                'message',
                'message_provider_local_coursedynamicrules_coursedynamicrules_notification_enabled'
            );

            // Validate if enabledproviders includes datacurso_msghub.
            $enabledproviderslist = explode(',', $enabledproviders);
            if (!in_array('datacurso_msghub', $enabledproviderslist)) {
                $notificationsettingssurl = new moodle_url('/admin/message.php');
                $plugininfo = $OUTPUT->notification(
                    get_string('provider_not_enabled_warning', 'local_coursedynamicrules', $notificationsettingssurl->out()),
                    \core\output\notification::NOTIFY_WARNING
                );
                $mform->addElement('html', $plugininfo);
            }
        }

        $mform->addElement('text', 'messagesubject', get_string('messagesubject', 'local_coursedynamicrules'));
        $mform->setType('messagesubject', PARAM_TEXT);
        $mform->addRule('messagesubject', null, 'required', null, 'client');

        $editoroptions = [
            'subdirs' => 0,
            'maxbytes' => 0,
            'maxfiles' => 0,
            'changeformat' => 0,
            'context' => null,
            'noclean' => 0,
            'trusttext' => 0,
            'enable_filemanagement' => true,
        ];
        $mform->addElement(
            'editor',
            'messagebody',
            get_string('messagebody', 'local_coursedynamicrules'),
            null,
            $editoroptions
        );
        $mform->setType('messagebody', PARAM_RAW);
        $mform->addRule('messagebody', null, 'required', null, 'client');
        $mform->addHelpButton('messagebody', 'messagebody', 'local_coursedynamicrules');

        $placeholderstext = $OUTPUT->render_from_template('local_coursedynamicrules/notification_placeholders', []);

        $mform->addElement('static', 'messagebody_static', '', $placeholderstext);

        $mform->addElement('hidden', 'type', $this->type);
        $mform->addElement('hidden', 'ruleid', $ruleid);
        $mform->setType('type', PARAM_TEXT);
        $mform->setType('ruleid', PARAM_INT);

        parent::definition();
    }
}
