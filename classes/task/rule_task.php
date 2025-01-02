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
 * Class rule_task
 * This task is used to execute rules en foreground to avoid block main executions when rule data is
 * get from observer
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule_task extends \core\task\adhoc_task {

    /**
     * Return a instance of rule_task with custom data added
     * @param object $customadata
     */
    public static function instance($customdata): self {
        $task = new self();
        $task->set_custom_data($customdata);

        return $task;
    }

    /**
     * This function is execute when cron jobs are executed
     */
    public function execute() {
        global $DB;

        $licensestatus = rule::validate_licence_status();
        if (!$licensestatus->success) {
            throw new \moodle_exception('pluginnotavailable', 'local_coursedynamicrules');
        }

        $customdata = $this->get_custom_data();

        $courseid = $customdata->courseid;
        $userid = $customdata->userid;
        $conditiontypes = $customdata->conditiontypes;

        $user = $DB->get_record('user', ['id' => $userid]);

        // Make array to pass to rule class in second param.
        $users = [$user];

        // Get active rules for the course.
        $rules = $DB->get_records('cdr_rule', ['courseid' => $courseid, 'active' => 1]);

        foreach ($rules as $rule) {
            $ruleinstance = new rule($rule, $users, $conditiontypes);
            $ruleinstance->execute();
        }
    }
}
