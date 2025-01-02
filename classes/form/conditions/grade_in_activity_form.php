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

namespace local_coursedynamicrules\form\conditions;

use html_writer;

/**
 * Class grade_in_activity
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class grade_in_activity_form extends condition_form {
    /** @var string type of condition */
    protected $type = "grade_in_activity";

    /**
     * Form definition
     *
     * @return void
     */
    public function definition() {
        global $PAGE;
        $mform = $this->_form;
        $customdata = $this->_customdata;
        $this->courseid = $customdata['courseid'];
        $this->ruleid = $customdata['ruleid'];

        $attributes = $mform->getAttributes();
        $attributes['id'] = 'grade_in_activity_form';
        $attributes['novalidate'] = true;

        $mform->setAttributes($attributes);

        // Create container for dynamic form.
        $mform->addElement('html', html_writer::div('', '', ['data-region' => 'dynamicform']));

        parent::definition();

        $PAGE->requires->js_call_amd('local_coursedynamicrules/grade_in_activity_form', 'init', []);
    }
}
