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

namespace local_coursedynamicrules\form\conditions;

/**
 * Class no_course_access_form
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class no_course_access_form extends condition_form {
    /** @var string type of condition */
    protected $type = "no_course_access";

    /**
     * Form definition
     *
     * @return void
     */
    public function definition() {
        global $PAGE, $OUTPUT;
        $mform = $this->_form;
        $customdata = $this->_customdata;
        $this->courseid = $customdata['courseid'];
        $this->ruleid = $customdata['ruleid'];

        $notification = $OUTPUT->notification(
            get_string('no_course_access_condition_info', 'local_coursedynamicrules'),
            \core\output\notification::NOTIFY_INFO
        );
        $mform->addElement('html', $notification);

        $periodgroup = [];
        $periodgroup[] = $mform->createElement('text', 'periodvalue', '', ['size' => 5]);
        $periodgroup[] = $mform->createElement('select', 'periodunit', '', [
            'minutes' => get_string('minutes'),
            'hours' => get_string('hours'),
            'days' => get_string('days'),
            'weeks' => get_string('weeks'),
        ]);

        $mform->addGroup($periodgroup, 'period_group', get_string('period', 'local_coursedynamicrules'), '', false);

        $mform->addHelpButton('period_group', 'period', 'local_coursedynamicrules');

        parent::definition();
    }
}
