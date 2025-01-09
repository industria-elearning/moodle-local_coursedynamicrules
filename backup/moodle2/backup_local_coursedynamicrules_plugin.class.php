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
 * TODO describe file backup_local_coursedynamicrules_plugin.class
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * backup_local_coursedynamicrules_plugin class
 */
class backup_local_coursedynamicrules_plugin extends backup_local_plugin {
    /**
     * Define structure for backup
     */
    protected function define_course_plugin_structure() {
        // Create plugin configuration.
        $plugin = $this->get_plugin_element();

        // Check if we want to include dynamic rules.
        $coursedynamicrules = $this->get_setting_value('coursedynamicrules');

        if ($coursedynamicrules) {
            // Create rules element.
            $rules = new backup_nested_element($this->get_recommended_name());
            $rule = new backup_nested_element('rule', ['id'], [
                'courseid',
                'name',
                'description',
                'active',
            ]);

            // Create conditions element.
            $conditions = new backup_nested_element('conditions');
            $condition = new backup_nested_element('condition', ['id'], [
                'ruleid',
                'conditiontype',
                'params',
            ]);

            // Create actions element.
            $actions = new backup_nested_element('actions');
            $action = new backup_nested_element('action', ['id'], [
                'ruleid',
                'actiontype',
                'params',
            ]);

            // Build the tree.
            $plugin->add_child($rules);
            $rules->add_child($rule);
            $rule->add_child($conditions);
            $conditions->add_child($condition);
            $rule->add_child($actions);
            $actions->add_child($action);

            // Define sources.
            $rule->set_source_table('cdr_rule', ['courseid' => backup::VAR_COURSEID]);
            $condition->set_source_table('cdr_condition', ['ruleid' => backup::VAR_PARENTID]);
            $action->set_source_table('cdr_action', ['ruleid' => backup::VAR_PARENTID]);
        }

        return $plugin;
    }
}
