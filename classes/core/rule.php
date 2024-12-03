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

namespace local_coursedynamicrules\core;
use local_coursedynamicrules\helper\rule_component_loader;
use stdClass;

/**
 * Class rule
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule {
    /** @var int ID of the rule on the DB */
    private $id;

    /** @var int 1 indicates that the rule is active, 0 indicates that the rule is inactive */
    private $active;

    /** @var int ID of the course */
    private $courseid;

    /** @var condition[] List of conditions instances */
    private $conditions = [];

    /** @var action[] List of actions instances */
    private $actions = [];

    /** @var array List of users to validate this rule */
    private $users;

    /**
     * Rule constructor.
     * @param object $rule
     * @param array $users List of users to validate this rule
     * @param array $conditiontypes list of conditions to include in the executions
     * of rules if not pass all conditions for each rule of the course are added
     */
    public function __construct($rule, $users, $conditiontypes=[]) {
        global $DB;
        $this->id = $rule->id;
        $this->courseid = $rule->courseid;
        $this->users = $users;
        $this->active = $rule->active;

        // Load conditions and actions from the DB.
        $conditions = $DB->get_records('cdr_condition', ['ruleid' => $this->id]);
        $actions = $DB->get_records('cdr_action', ['ruleid' => $this->id]);

        foreach ($conditions as $conditionrecord) {
            if (!empty($conditiontypes) && !in_array($conditionrecord->conditiontype, $conditiontypes)) {
                // Skip condition.
                continue;
            }
            $this->conditions[] = rule_component_loader::create_condition_instance($conditionrecord, $this->courseid);
        }

        foreach ($actions as $actionrecord) {
            $this->actions[] = rule_component_loader::create_action_instance($actionrecord, $this->courseid);
        }

    }

    /**
     * Get conditions for this rule
     * @return condition[]
     */
    public function get_conditions() {
        return $this->conditions;
    }

    /**
     * Validate if all conditions of the rule are true
     * @param object $rulecontext Necesary information to evaluate the conditions of the rule
     * @return bool
     */
    public function evaluate_conditions($rulecontext) {
        if (empty($this->conditions)) {
            return false;
        }
        foreach ($this->conditions as $condition) {
            if (!$condition->evaluate($rulecontext)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Execute all actions of the rule if the conditions are true
     */
    public function execute() {

        foreach ($this->users as $user) {
            $rulecontext = (object)[
                'courseid' => $this->courseid,
                'userid' => $user->id,
            ];

            // Validate if all conditions of the rule are true for the user.
            if ($this->evaluate_conditions($rulecontext)) {
                // Execute all actions of the rule.
                $this->execute_actions($rulecontext);
            }
        }
    }

    /**
     * Execute all actions of the rule
     * @param object $rulecontext Necesary information to execute the actions of the rule
     */
    private function execute_actions($rulecontext) {
        foreach ($this->actions as $action) {
            $action->execute($rulecontext);
        }
    }

    /**
     * Set the active status of the rule
     * @param bool $active 1 indicates that the rule is active, 0 indicates that the rule is inactive
     */
    public function set_active($active) {
        global $DB;
        $this->active = $active ? 1 : 0;
        $DB->set_field('cdr_rule', 'active', $this->active, ['id' => $this->id]);

    }

}
