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
 * Backup plugin for local_coursedynamicrules.
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Wilber Narvaez <https://datacurso.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Defines course-level backup structure for the coursedynamicrules local plugin.
 *
 * @package   local_coursedynamicrules
 */
class backup_local_coursedynamicrules_plugin extends backup_local_plugin {
    /**
     * Define plugin structure
     *
     * @return backup_plugin_element
     */
    protected function define_course_plugin_structure() {
        $plugin = $this->get_plugin_element(null);

        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        $rules = new backup_nested_element('rules');
        $plugin->add_child($pluginwrapper);
        $pluginwrapper->add_child($rules);

        $rule = new backup_nested_element('rule', ['id'], [
            'courseid',
            'name',
            'description',
            'active',
            'lastexecutiontime',
            'timecreated',
            'timemodified',
        ]);
        $rules->add_child($rule);

        $conditions = new backup_nested_element('conditions');
        $rule->add_child($conditions);
        $condition = new backup_nested_element('condition', ['id'], [
            'ruleid',
            'name',
            'conditiontype',
            'eventname',
            'params',
            'lastexecutiontime',
        ]);
        $conditions->add_child($condition);

        $actions = new backup_nested_element('actions');
        $rule->add_child($actions);
        $action = new backup_nested_element('action', ['id'], [
            'ruleid',
            'name',
            'actiontype',
            'params',
            'lastexecutiontime',
        ]);
        $actions->add_child($action);

        // Sources.
        $rule->set_source_table('local_coursedynamicrules_rule', ['courseid' => backup::VAR_COURSEID]);
        $condition->set_source_table('local_coursedynamicrules_condition', ['ruleid' => backup::VAR_PARENTID]);
        $action->set_source_table('local_coursedynamicrules_action', ['ruleid' => backup::VAR_PARENTID]);

        return $plugin;
    }
}
