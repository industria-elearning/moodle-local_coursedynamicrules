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

/**
 * TODO describe file restore_local_coursedynamicrules_plugin.class
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * restore_local_coursedynamicrules_plugin class
 * Handles the course dynamic rules restore logic
 */
class restore_local_recompletion_plugin extends restore_local_plugin {
    /**
     * Returns the paths to be handled by the plugin at course level.
     */
    protected function define_course_plugin_structure() {
        $paths = [];

        $elepath = $this->get_pathfor('/');

        $paths[] = new restore_path_element('rule', $elepath . '/rule');
        $paths[] = new restore_path_element('condition', $elepath . '/rule/conditions/condition');
        $paths[] = new restore_path_element('action', $elepath . '/rule/actions/action');

        return $paths;
    }


    /**
     * Process rule restore
     */
    public function process_rule($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->courseid = $this->task->get_courseid();

        $newid = $DB->insert_record('cdr_rule', $data);
        $this->set_mapping('rule', $oldid, $newid);
    }

    /**
     * Process condition restore
     */
    public function process_condition($data) {
        global $DB;

        $data = (object)$data;
        $data->ruleid = $this->get_new_parentid('rule');

        $DB->insert_record('cdr_condition', $data);
    }

    /**
     * Process action restore
     */
    public function process_action($data) {
        global $DB;

        $data = (object)$data;
        $data->ruleid = $this->get_new_parentid('rule');

        $DB->insert_record('cdr_action', $data);
    }
}
