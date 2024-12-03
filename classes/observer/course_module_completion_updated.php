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

use local_coursedynamicrules\task\rule_task;

/**
 * Class course_module_completion_updated
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_module_completion_updated {
    /** @var array $conditions list of conditions to include in the executions for this event observer */
    private static $conditiontypes = [
        'passgrade',
        'no_complete_activity',
    ];
    /**
     * The definition of the event.
     *
     */
    public static function observe(\core\event\course_module_completion_updated $event) {
        global $DB;
        $eventdata = $event->get_data();

        $courseid = $eventdata["courseid"];

        // User that completed the module.
        $userid = $eventdata["relateduserid"];

        $task = rule_task::instance((object)[
            'courseid' => $courseid,
            'userid' => $userid,
            'conditiontypes' => self::$conditiontypes,
        ]);

        \core\task\manager::queue_adhoc_task($task);
    }
}
