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

    /** @var condition[] List of conditions instances */
    private $conditions = [];

    /** @var action[] List of actions instances */
    private $actions = [];

    /** @var object|null Store information that is needed to evaluate the rule. This is generated on the events or scheduled tasks */
    private $rulecontext;

    /**
     * Rule constructor.
     * @param int $id ID of the rule
     * @param object|null $rulecontext Store information that is needed to evaluate the rule
     */
    public function __construct($id, $rulecontext = null) {
        global $DB;
        $this->id = $id;
        $this->rulecontext = $rulecontext;

        // Load conditions and actions from the DB.
        $conditions = $DB->get_records('cdr_condition', ['ruleid' => $this->id]);
        $actions = $DB->get_records('cdr_action', ['ruleid' => $this->id]);

        foreach ($conditions as $conditionrecord) {
            $this->conditions[] = rule_component_loader::create_condition_instance($conditionrecord);
        }

        foreach ($actions as $actionrecord) {
            $this->actions[] = rule_component_loader::create_action_instance($actionrecord);
        }

    }

    /**
     * Validate if all conditions of the rule are true
     * @return bool
     */
    public function evaluate() {
        foreach ($this->conditions as $condition) {
            if (!$condition->evaluate($this->rulecontext)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Execute all actions of the rule if the conditions are true
     */
    public function execute() {
        // Validate if all conditions of the rule are true.
        if ($this->evaluate()) {
            // Execute all actions of the rule.
            foreach ($this->actions as $action) {
                $action->execute($this->rulecontext);
            }
        }
    }

}
