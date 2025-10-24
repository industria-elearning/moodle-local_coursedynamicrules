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

namespace local_coursedynamicrules\form;

use context_course;
use core_form\dynamic_form;
use moodle_url;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

/**
 * Dynamic form to create/update a Course Dynamic Rule via modal.
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Wilber Narvaez <https://datacurso.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule_dynamic extends dynamic_form {
    /**
     * Form definition.
     */
    public function definition() {
        $mform = $this->_form;
        $courseid = (int)$this->optional_param('courseid', 0, PARAM_INT);

        $mform->addElement('text', 'name', get_string('name', 'local_coursedynamicrules'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('textarea', 'description', get_string('description', 'local_coursedynamicrules'));
        $mform->setType('description', PARAM_RAW);

        $mform->addElement('checkbox', 'active', get_string('ruleactive', 'local_coursedynamicrules'));
        $mform->setDefault('active', 1);

        $mform->addElement('hidden', 'courseid', $courseid);
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);
    }

    /**
     * Check user has access to submit this form.
     */
    protected function check_access_for_dynamic_submission(): void {
        $courseid = (int)$this->optional_param('courseid', 0, PARAM_INT);
        $context = context_course::instance($courseid);
        require_capability('local/coursedynamicrules:updaterule', $context);
    }

    /**
     * Get context for persistent validation.
     */
    public function get_context_for_dynamic_submission(): \context {
        $courseid = (int)$this->optional_param('courseid', 0, PARAM_INT);
        return context_course::instance($courseid);
    }

    /**
     * Return page URL.
     */
    public function get_page_url_for_dynamic_submission(): moodle_url {
        $courseid = (int)$this->optional_param('courseid', 0, PARAM_INT);
        return new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $courseid]);
    }

    /**
     * Set default data for form when editing.
     * Loads data when id is provided.
     */
    public function set_data_for_dynamic_submission(): void {
        global $DB;
        $id = (int)$this->optional_param('id', 0, PARAM_INT);
        if ($id) {
            $record = $DB->get_record('cdr_rule', ['id' => $id], '*', MUST_EXIST);
            $this->set_data($record);
        }
    }

    /**
     * Process submission: insert or update rule. Return redirect URL string.
     *
     * @return string
     */
    public function process_dynamic_submission() {
        global $DB;
        $data = $this->get_data();
        $now = time();
        $data->timemodified = $now;
        $data->active = $data->active ?? 0;

        if (empty($data->id)) {
            $data->timecreated = $now;
            $DB->insert_record('cdr_rule', $data);
        } else {
            $DB->update_record('cdr_rule', $data);
        }

        $url = new moodle_url('/local/coursedynamicrules/rules.php', ['courseid' => $data->courseid]);
        return $url->out(false);
    }
}
