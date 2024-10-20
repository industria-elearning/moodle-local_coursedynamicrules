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

namespace local_coursedynamicrules;

use local_coursedynamicrules\condition\condition_base;
use stdClass;

/**
 * Class observer
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * Dynamic handler for events
     * @param \core\event\base $event
     */
    public static function handle_event($event) {
        global $DB;
        $eventdata = $event->get_data();
        $courseid = $eventdata["courseid"];

        if (!$courseid) {
            return;
        }

        // Get active rules for the course.
        $rules = $DB->get_records('cdr_rule', ['courseid' => $courseid, 'active' => 1]);

        foreach ($rules as $rule) {
            // Get conditions to the rule.
            $conditions = $DB->get_records('cdr_condition', ['ruleid' => $rule->id]);

            $conditionsmet = false;

            // Validate each condition associated to the rule.
            foreach ($conditions as $condition) {
                $conditionclass = rule_loader::get_condition_class($condition->conditiontype);

                /** @var condition_base $conditioninstance */
                $conditioninstance = new $conditionclass($condition);

                $conditionsmet = $conditioninstance->validate($event);
                // Verify if the condition is met.
                if (!$conditionsmet) {
                    // If any condition is not met, the rule is not executed.
                    break;
                }
            }

            // If all conditions are met, execute the actions.
            if ($conditionsmet) {
                self::execute_actions($rule);
            }
        }
    }

    /**
     * Executes the actions associated to the rule.
     * @param stdClass $rule
     */
    private static function execute_actions($rule) {
        global $DB;

        // Get actions associated to the rule.
        $actions = $DB->get_records('cdr_action', ['ruleid' => $rule->id]);

        foreach ($actions as $action) {
            $actionclass = rule_loader::get_action_class($action->actiontype);
            $actioninstance = new $actionclass($action, true);
            $actioninstance->execute();
        }
    }

}
