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

use local_coursedynamicrules\condition\course_inactivity\course_inactivity_condition;

/**
 * Class course_inactivity_form
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_inactivity_form extends condition_form {

    /** @var string base date for evaluating the intervals is start date of user enrolment*/
    const DATE_FROM_ENROLLMENT = course_inactivity_condition::DATE_FROM_ENROLLMENT;

    /** @var string base date for evaluating the intervals is start date of course */
    const DATE_FROM_COURSE_START = course_inactivity_condition::DATE_FROM_COURSE_START;

    /** @var string base date for evaluating the intervals is current date */
    const DATE_FROM_NOW = course_inactivity_condition::DATE_FROM_NOW;

    /** @var string type of condition */
    protected $type = "course_inactivity";

    /**
     * Form definition
     *
     * @return void
     */
    public function definition() {
        global $PAGE, $OUTPUT;
        $pluginname = 'local_coursedynamicrules';

        $mform = $this->_form;
        $customdata = $this->_customdata;
        $this->courseid = $customdata['courseid'];
        $this->ruleid = $customdata['ruleid'];

        $notification = $OUTPUT->notification(
            get_string('course_inactivity_info', $pluginname),
            \core\output\notification::NOTIFY_INFO
        );
        $mform->addElement('html', $notification);

        $periodgroup = [];
        $periodgroup[] = $mform->createElement('text', 'timeintervals');
        $periodgroup[] = $mform->createElement('select', 'intervalunit', '', [
            'minutes' => get_string('minutes', $pluginname),
            'hours' => get_string('hours', $pluginname),
            'days' => get_string('days', $pluginname),
            'weeks' => get_string('weeks', $pluginname),
        ]);

        $mform->addGroup($periodgroup, 'timeintervals_group', get_string('timeintervals', $pluginname), '', false);
        $mform->addHelpButton('timeintervals_group', 'timeintervals', $pluginname);

        // Base date for evaluating the intervals.
        $basedateoptions = [
            self::DATE_FROM_ENROLLMENT => get_string('date_from_enrollment', $pluginname),
            self::DATE_FROM_COURSE_START => get_string('date_from_course_start', $pluginname),
            self::DATE_FROM_NOW => get_string('date_from_now', $pluginname),
        ];
        $mform->addElement('select', 'basedate', get_string('basedate', $pluginname), $basedateoptions);
        $mform->addHelpButton('basedate', 'basedate', $pluginname);

        parent::definition();
    }
}
