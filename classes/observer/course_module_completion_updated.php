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

namespace local_coursedynamicrules\observer;

use local_coursedynamicrules\helper\rule_component_loader;

/**
 * Class course_module_completion_updated
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_module_completion_updated {
    /**
     * The definition of the event.
     *
     */
    public static function observe(\core\event\course_module_completion_updated $event) {
        global $DB;
        $eventdata = $event->get_data();
        $otherdata = $eventdata['other'];

        $courseid = $eventdata["courseid"];
        $cmid = $eventdata["contextinstanceid"];
        $completionstate = $otherdata['completionstate'];
        // User that completed the module.
        $userid = $eventdata["relateduserid"];

        $rulecontext = (object) [
            'courseid' => $courseid,
            'cmid' => $cmid,
            'userid' => $userid,
            'completionstate' => $completionstate,
        ];

        // Get active rules for the course.
        $rules = $DB->get_records('cdr_rule', ['courseid' => $courseid, 'active' => 1]);

        foreach ($rules as $rule) {
            // Get conditions to the rule.
            $conditions = $DB->get_records('cdr_condition', ['ruleid' => $rule->id]);

            $conditionsmet = false;

            // Validate each condition associated to the rule.
            foreach ($conditions as $condition) {
                $conditioninstance = rule_component_loader::create_condition_instance($condition, $courseid);

                $conditionsmet = $conditioninstance->evaluate($rulecontext);
                // Verify if the condition is met.
                if (!$conditionsmet) {
                    // If any condition is not met, the rule is not executed.
                    break;
                }
            }

            // If all conditions are met, execute the actions.
            if ($conditionsmet) {
                self::execute_actions($rule, $rulecontext);
            }
        }
    }

    /**
     * Executes the actions associated to the rule.
     * @param object $rule
     * @param object $rulecontext
     */
    private static function execute_actions($rule, $rulecontext) {
        global $DB;

        // Get actions associated to the rule.
        $actions = $DB->get_records('cdr_action', ['ruleid' => $rule->id]);

        foreach ($actions as $action) {
            $actioninstance = rule_component_loader::create_action_instance($action, $rulecontext->courseid);
            $actioninstance->execute($rulecontext);
        }
    }
}
