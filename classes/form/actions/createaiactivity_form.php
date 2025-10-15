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
 * Class createaiactivity_form
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class createaiactivity_form extends action_form {
    /** @var string type of action */
    protected $type = 'createaiactivity';

    /**
     * Form definition
     *
     * @return void
     */
    public function definition() {
        global $OUTPUT, $CFG;

        require_once($CFG->dirroot . '/course/lib.php');

        $mform = $this->_form;
        $customdata = $this->_customdata;

        $ruleid = $customdata['ruleid'];
        $courseid = $customdata['courseid'];

        $notification = $OUTPUT->notification(
            get_string('createaiactivity_action_info', 'local_coursedynamicrules'),
            \core\output\notification::NOTIFY_INFO
        );
        $mform->addElement('html', $notification);

        // Check if the availability_user plugins is installed.
        if (!get_config('availability_user', 'version')) {

            $plugininfo = $OUTPUT->notification(
                get_string('missing_availability_user', 'local_coursedynamicrules'),
                \core\output\notification::NOTIFY_ERROR,
                false
            );
            $mform->addElement('html', $plugininfo);
            return;
        } else if (get_config('availability_user', 'disabled')) {
            $managerestrictionsurl = new moodle_url('/admin/tool/availabilityconditions/');
            // Check if the availability_user plugins is enabled.
            $plugininfo = $OUTPUT->notification(
                get_string('disabled_availability_user', 'local_coursedynamicrules', $managerestrictionsurl->out()),
                \core\output\notification::NOTIFY_ERROR,
                false
            );
            $mform->addElement('html', $plugininfo);
            return;
        }

        $mform->addElement('hidden', 'type', $this->type);
        $mform->addElement('hidden', 'ruleid', $ruleid);
        $mform->addElement('hidden', 'courseid', $courseid);
        $mform->setType('type', PARAM_TEXT);
        $mform->setType('ruleid', PARAM_INT);
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement(
            'textarea',
            'message',
            get_string('createaiactivity_prompt', 'local_coursedynamicrules'),
            ['rows' => 5]
        );
        $mform->setType('message', PARAM_RAW_TRIMMED);
        $mform->addRule('message', null, 'required', null, 'client');
        $mform->addHelpButton('message', 'createaiactivity_prompt', 'local_coursedynamicrules');

        $placeholdersinfo = get_string('createaiactivity_placeholders_info', 'local_coursedynamicrules');
        $mform->addElement('static', 'message_placeholders', '', $placeholdersinfo);

        $mform->addElement(
            'advcheckbox',
            'generateimages',
            get_string('createaiactivity_generateimages', 'local_coursedynamicrules'),
            get_string('createaiactivity_generateimages_label', 'local_coursedynamicrules')
        );
        $mform->setType('generateimages', PARAM_BOOL);

        $course = get_course($courseid);
        $sectionoptions = [];
        $sectioninfos = get_fast_modinfo($courseid)->get_section_info_all();
        foreach ($sectioninfos as $sectioninfo) {
            $sectionoptions[$sectioninfo->section] = get_section_name($course, $sectioninfo->section);
        }
        $mform->addElement(
            'select',
            'sectionnum',
            get_string('createaiactivity_section', 'local_coursedynamicrules'),
            $sectionoptions
        );
        $mform->setType('sectionnum', PARAM_INT);
        if (array_key_exists(0, $sectionoptions)) {
            $mform->setDefault('sectionnum', 0);
        }

        $beforeoptions = [0 => get_string('createaiactivity_beforemod_none', 'local_coursedynamicrules')];
        $cms = get_fast_modinfo($courseid)->get_cms();
        foreach ($cms as $cm) {
            if ($cm->deletioninprogress) {
                continue;
            }
            $beforeoptions[$cm->id] = ucfirst($cm->modname) . ' - ' . $cm->name;
        }

        $mform->addElement(
            'select',
            'beforemod',
            get_string('createaiactivity_beforemod', 'local_coursedynamicrules'),
            $beforeoptions
        );
        $mform->setType('beforemod', PARAM_INT);
        $mform->setDefault('beforemod', 0);
        $mform->addHelpButton('beforemod', 'createaiactivity_beforemod', 'local_coursedynamicrules');

        parent::definition();
    }
}
