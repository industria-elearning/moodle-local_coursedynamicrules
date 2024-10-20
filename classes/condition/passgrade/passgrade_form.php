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

use local_coursedynamicrules\condition\condition_form;

/**
 * TODO describe file passgrade_form
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class passgrade_form extends condition_form {

    /** @var string type of condition */
    protected $type = "passgrade";
    /**
     * Form definition
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'passgrade', 'Pass grade', []);

        parent::definition();
        // $this->set_data([]);
    }
}
