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
        $mform = $this->_form;
        $customdata = $this->_customdata;
        $ruleid = $customdata['ruleid'];

        $mform->addElement('text', 'messagesubject', get_string('messagesubject', 'local_coursedynamicrules'));
        $mform->setType('messagesubject', PARAM_TEXT);
        $mform->addRule('messagesubject', null, 'required', null, 'client');

        $mform->addElement('textarea', 'messagebody', get_string('messagebody', 'local_coursedynamicrules'),  'rows="10"');
        $mform->setType('messagebody', PARAM_TEXT);
        $mform->addRule('messagebody', null, 'required', null, 'client');
        $mform->addHelpButton('messagebody', 'messagebody', 'local_coursedynamicrules');

        $placeholderstext = '
        <div>
            <strong>' . get_string('availableplaceholders', 'local_coursedynamicrules') . ':</strong>
            <ul>
                <li>{$a->coursename} - ' . get_string('coursename', 'local_coursedynamicrules') . '</li>
                <li>{$a->fullname} - ' . get_string('fullname', 'local_coursedynamicrules') . '</li>
                <li>{$a->firstname} - ' . get_string('firstname', 'local_coursedynamicrules') . '</li>
                <li>{$a->lastname} - ' . get_string('lastname', 'local_coursedynamicrules') . '</li>
            </ul>
        </div>';

        $mform->addElement('static', 'messagebody_static', '', $placeholderstext);

        $mform->addElement('hidden', 'type', $this->type);
        $mform->addElement('hidden', 'ruleid', $ruleid);
        $mform->setType('type', PARAM_ALPHA);
        $mform->setType('ruleid', PARAM_INT);

        parent::definition();
    }
}
