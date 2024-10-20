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

namespace local_coursedynamicrules\condition\passgrade;

use local_coursedynamicrules\condition\condition_base;
use stdClass;

/**
 * Class passgrade_condition
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class passgrade_condition extends condition_base {
    /** @var string type of condition */
    protected $type = "passgrade";

    /**
     * Creates and returns an instance of the form for editing the item
     *
     * @param stdClass $condition object that represents the condition on the database
     */
    public function build_editform($condition) {
        global $DB, $CFG;
        $customdata = [];
        // $editconditionurl = new moodle_url('/local/coursedynamicrules/editcondition.php', []);

        $this->conditionform = new passgrade_form('editcondition.php', $customdata);

    }

        /**
         * Saves the condition after it has been edited (or created)
         */
    public function save_condition() {
    }
}
