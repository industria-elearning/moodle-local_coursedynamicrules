<?php

use local_coursedynamicrules\core\rule;
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
 * Data generator class
 *
 * @package    local_coursedynamicrules
 * @category   test
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_coursedynamicrules_generator extends component_generator_base {

    /**
     * Create a user last access in a course.
     *
     * @param int $userid User ID
     * @param int $courseid Course ID
     * @param int $lastaccess Last access timestamp
     */
    public function create_user_lastaccess($userid, $courseid, $lastaccess) {
        global $DB;

        $accessrecord = new stdClass();
        $accessrecord->userid = $userid;
        $accessrecord->courseid = $courseid;
        $accessrecord->timeaccess = $lastaccess;

        $DB->insert_record('user_lastaccess', $accessrecord);

    }

    /**
     * Create a rule.
     *
     * @param int $courseid Course ID
     * @param stdClass[] $users Array of users
     * @param string[] $conditiontypes Array of condition types
     * @return rule Rule instance
     */
    public function create_rule($courseid, $users, $conditiontypes = []) {
        global $DB;

        $record = new stdClass();
        $record->courseid = $courseid;
        $record->name = 'Test rule';
        $record->description = 'Test rule description';
        $record->active = 1;
        $record->timecreated = time();
        $record->timemodified = time();

        $ruleid = $DB->insert_record('cdr_rule', $record);
        $record->id = $ruleid;

        return new rule($record, $users, $conditiontypes);

    }
}
