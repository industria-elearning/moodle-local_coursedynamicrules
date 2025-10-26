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

use context_course;
use core_form\dynamic_form;
use local_coursedynamicrules\helper\rule_component_loader;
use moodle_url;
use stdClass;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Class condition_form
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class condition_form extends dynamic_form {
    /** @var string type of condition */
    protected $type;

    /** @var int $courseid*/
    protected $courseid;

    /** @var int $ruleid */
    protected $ruleid;

    /**
     * Add elements to form.
     */
    public function definition() {
        $mform = $this->_form;
        // Hidden fields used by dynamic submission (mirror audience form).
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'ruleid');
        $mform->setType('ruleid', PARAM_INT);

        $mform->addElement('hidden', 'classname');
        $mform->setType('classname', PARAM_RAW_TRIMMED);

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        // Embed form defined in condition class.
        $condition = $this->get_condition();
        $condition->get_config_form($mform);

        $this->add_action_buttons();
    }

    /**
     * Context for dynamic submission.
     */
    protected function get_context_for_dynamic_submission(): \context {
        global $DB;
        $id = $this->optional_param('id', 0, PARAM_INT);
        if ($id) {
            $ruleid = (int)$DB->get_field('cdr_condition', 'ruleid', ['id' => $id], MUST_EXIST);
            $courseid = (int)$DB->get_field('cdr_rule', 'courseid', ['id' => $ruleid], MUST_EXIST);
            return context_course::instance($courseid);
        }
        $courseid = $this->optional_param('courseid', 0, PARAM_INT);
        return $courseid ? context_course::instance($courseid) : \context_system::instance();
    }

    /**
     * Access check for dynamic submission.
     */
    protected function check_access_for_dynamic_submission(): void {
        $context = $this->get_context_for_dynamic_submission();
        require_capability('local/coursedynamicrules:managecondition', $context);
    }

    /**
     * Process AJAX submission and return heading/description for the card.
     *
     * @return array
     */
    public function process_dynamic_submission() {
        $formdata = $this->get_data();

        // Normalise fields expected by conditions.
        if (isset($formdata->coursemodule)) {
            $formdata->coursemodule = (int)$formdata->coursemodule;
        }
        if (isset($formdata->ruleid)) {
            $formdata->ruleid = (int)$formdata->ruleid;
        }
        if (isset($formdata->id)) {
            $formdata->id = (int)$formdata->id;
        }

        // Persist using condition implementation (insert/update), like audiences do.
        $condition = $this->get_condition();
        $condition->save_condition($formdata);

        return [
            'instanceid' => (int)$condition->get_id(),
            'heading' => $condition->get_header(),
            'description' => $condition->get_description(),
        ];
    }

    /**
     * Load in existing data as form defaults.
     */
    public function set_data_for_dynamic_submission(): void {
        $id = $this->optional_param('id', 0, PARAM_INT);
        $ruleid = $this->optional_param('ruleid', 0, PARAM_INT);
        $classname = $this->optional_param('classname', '', PARAM_RAW_TRIMMED);
        $courseid = $this->optional_param('courseid', 0, PARAM_INT);

        if ($id) {
            $condition = $this->get_condition();
            $formdata = [
                'id' => $id,
                'ruleid' => $condition->get_ruleid() ?? $ruleid,
                'classname' => $classname,
                'courseid' => $courseid,
            ];
            $formdata = array_merge($formdata, $condition->get_configdata());
            $this->set_data($formdata);
            return;
        }

        $this->set_data([
            'ruleid' => $ruleid,
            'classname' => $classname,
            'courseid' => $courseid,
        ]);
    }

    /**
     * Page url for dynamic submission.
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {
        $ruleid = $this->optional_param('ruleid', 0, PARAM_INT);
        return new moodle_url('/local/coursedynamicrules/ruleedit.php', ['id' => $ruleid]);
    }

    /**
     * Helper: create condition instance from classname param.
     *
     * @return \local_coursedynamicrules\core\condition
     */
    protected function get_condition() {
        global $DB;

        $id = $this->optional_param('id', 0, PARAM_INT);
        $courseid = $this->optional_param('courseid', 0, PARAM_INT);

        // Update existing: load record and ensure course context.
        if ($id) {
            $record = $DB->get_record('cdr_condition', ['id' => $id], '*', MUST_EXIST);
            $courseid = (int)$DB->get_field('cdr_rule', 'courseid', ['id' => $record->ruleid], MUST_EXIST);
            return rule_component_loader::create_condition_instance($record, $courseid);
        }

        // Create new: build minimal record from inputs.
        $ruleid = $this->optional_param('ruleid', 0, PARAM_INT);
        $classname = $this->optional_param('classname', '', PARAM_RAW_TRIMMED);
        $record = (object) [
            'id' => 0,
            'ruleid' => $ruleid,
            'conditiontype' => $this->derive_type_from_classname($classname),
            'params' => json_encode(new stdClass()),
        ];
        return rule_component_loader::create_condition_instance($record, $courseid);
    }

    /**
     * Derive condition type from classname namespace.
     *
     * @param string|null $classname
     * @return string
     */
    protected function derive_type_from_classname(?string $classname = null): string {
        $classname = $classname ?? $this->optional_param('classname', '', PARAM_RAW_TRIMMED);
        if (!$classname) {
            return '';
        }
        $parts = explode('\\', $classname);
        return count($parts) >= 2 ? $parts[count($parts) - 2] : '';
    }
}
