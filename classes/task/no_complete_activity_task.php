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

namespace local_coursedynamicrules\task;

use local_coursedynamicrules\core\rule;

/**
 * Class no_complete_activity_task
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class no_complete_activity_task extends \core\task\scheduled_task {
    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('no_complete_activity_task', 'local_coursedynamicrules');
    }

    /**
     * Execute the task.
     *
     */
    public function execute() {
        global $DB;

        $rules = $DB->get_records_sql(
            "SELECT DISTINCT r.*
            FROM
                {cdr_rule} r
                JOIN {cdr_condition} c ON c.ruleid = r.id
            WHERE
                c.conditiontype = 'no_complete_activity'
                AND r.active = 1"
        );

        foreach ($rules as $rule) {
            $ruleinstance = new rule($rule);
            $conditions = $ruleinstance->get_conditions();

            if ($this->is_time_to_execute_rule($ruleinstance) && !empty($conditions)) {
                $ruleinstance->execute();
                $ruleinstance->set_active(false);
            }

        }
    }

    /**
     * Validate if rule could be executed.
     * @param rule $rule
     */
    private function is_time_to_execute_rule($rule) {
        $conditions = $rule->get_conditions();

        foreach ($conditions as $condition) {
            $now = time();
            $params = $condition->get_params();
            if ($condition->get_type() == 'no_complete_activity' && $now < $params->expectedcompletiondate) {
                return false;
            }
        }

        return true;

    }
}
