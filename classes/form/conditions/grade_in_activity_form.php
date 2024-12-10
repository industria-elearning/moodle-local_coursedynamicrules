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
 * Class grade_in_activity
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class grade_in_activity_form extends condition_form {
    /** @var string type of condition */
    protected $type = "no_complete_activity";

    /**
     * Form definition
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;
        $customdata = $this->_customdata;
        $this->courseid = $customdata['courseid'];
        $this->ruleid = $customdata['ruleid'];

        $modinfo = get_fast_modinfo($this->courseid);
        $cms = $modinfo->get_cms();
        $options = [];
        foreach ($cms as $cm) {
            if ($cm->completion == COMPLETION_TRACKING_AUTOMATIC) {
                $options[$cm->id] = ucfirst($cm->modname) . " - " . $cm->name;
            }
        }

        $attributes = [
            'multiple' => false,
            'noselectionstring' => get_string('allcourseactivitymodules', 'local_coursedynamicrules'),
        ];
        $mform->addElement(
            'autocomplete',
            'coursemodule',
            get_string('searchcourseactivitymodules',
            'local_coursedynamicrules'),
            $options,
            $attributes
        );

        $gradegreatergroup = [];
        $gradegreatergroup[] = $mform->createElement(
            'advcheckbox',
            'enablegradegreaterthanorequal',
            '',
            get_string('gradegreaterthanorequal', 'local_coursedynamicrules')
        );
        $gradegreatergroup[] = $mform->createElement('text', 'gradegreaterthanorequal', '');
        $mform->addGroup($gradegreatergroup, 'gradegreatergroup', get_string('grade', 'local_coursedynamicrules'), ' ', false);
        $mform->addHelpButton('gradegreatergroup', 'gradegreaterthanorequal', 'local_coursedynamicrules');
        $mform->disabledIf('gradegreaterthanorequal', 'enablegradegreaterthanorequal', 'notchecked');
        $mform->setType('gradegreaterthanorequal', PARAM_FLOAT);

        $gradelessgroup = [];
        $gradelessgroup[] = $mform->createElement(
            'advcheckbox',
            'enablegradelessthan',
            '',
            get_string('gradelessthan', 'local_coursedynamicrules')
        );
        $gradelessgroup[] = $mform->createElement('text', 'gradelessthan', '');
        $mform->addGroup($gradelessgroup, 'gradelessgroup', '', ' ', false);
        $mform->addHelpButton('gradelessgroup', 'gradelessthan', 'local_coursedynamicrules');
        $mform->disabledIf('gradelessthan', 'enablegradelessthan', 'notchecked');
        $mform->setType('gradelessthan', PARAM_FLOAT);
    }
}
