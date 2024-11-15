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

namespace local_coursedynamicrules\helper;

use local_coursedynamicrules\core\action;
use local_coursedynamicrules\core\condition;
use moodle_exception;


/**
 * Class rule_component_loader
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule_component_loader {
    /**
     * Create instance of condition
     *
     * @param object $conditionrecord record of condition stored in DB
     * @param int $courseid course id
     * @return condition instance of condition. Example: insyace of local_coursedynamicrules\condition\passgrade\passgrade_condition
     * @throws moodle_exception For invalid type
     */
    public static function create_condition_instance($conditionrecord, $courseid = null) {
        global $CFG;

        // Type of condition, example: passgrade.
        $type = clean_param($conditionrecord->conditiontype, PARAM_ALPHA);

        // Example: local_coursedynamicrules\condition\passgrade\passgrade_condition.
        $conditionclass = "\\local_coursedynamicrules\\condition\\{$type}\\{$type}_condition";
        $conditionclasspath = "{$CFG->dirroot}/local/coursedynamicrules/classes/condition/{$type}/{$type}_condition.php";

        // Get the instance of item-class.
        if (!class_exists($conditionclass) && file_exists($conditionclasspath)) {
            require_once($conditionclasspath);
        }

        if (!class_exists($conditionclass)) {
            throw new moodle_exception('typemissing', 'local_coursedynamicrules');
        }

        return new $conditionclass($conditionrecord, $courseid);
    }

    /**
     * Create instance of action
     *
     * @param object $actionrecord record of action stored in DB
     * @param int $courseid course id
     * @return action instance of action. Example: instace of local_coursedynamicrules\condition\sendnotification\sendnotification_action.
     * @throws moodle_exception For invalid type
     */
    public static function create_action_instance($actionrecord, $courseid = null) {
        global $CFG;

        // Type of condition, example: sendnotification.
        $type = clean_param($actionrecord->actiontype, PARAM_ALPHA);

        // Example: local_coursedynamicrules\condition\sendnotification\sendnotification_action.
        $conditionclass = "\\local_coursedynamicrules\\action\\{$type}\\{$type}_action";
        $conditionclasspath = "{$CFG->dirroot}/local/coursedynamicrules/classes/action/{$type}/{$type}_action.php";

        if (!class_exists($conditionclass) && file_exists($conditionclasspath)) {
            require_once($conditionclasspath);
        }

        if (!class_exists($conditionclass)) {
            throw new moodle_exception('typemissing', 'local_coursedynamicrules');
        }

        return new $conditionclass($actionrecord, $courseid);
    }
}
