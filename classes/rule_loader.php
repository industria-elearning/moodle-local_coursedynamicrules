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

use moodle_exception;

/**
 * Class rule_loader
 * This class is used to load the rule condition and act class
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule_loader {
    /**
     * load the condition type class
     *
     * @package    local_coursedynamicrules
     * @copyright  2024 Industria Elearning <info@industriaelearning.com>
     * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     *
     * @param string $type example: passgrade
     * @return string class defintion of condition. Example: local_coursedynamicrules\condition\passgrade\passgrade_condition
     * @throws moodle_exception For invalid type
     */
    public static function get_condition_class($type) {
        global $CFG;

        // Get the class of item-type.
        $type = clean_param($type, PARAM_ALPHA);

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

        return $conditionclass;
    }
}
