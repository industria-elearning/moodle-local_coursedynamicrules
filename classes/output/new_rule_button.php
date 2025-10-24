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

namespace local_coursedynamicrules\output;

use renderable;
use templatable;
use renderer_base;

/**
 * Renderable for the New Rule button.
 *
 * @package    local_coursedynamicrules
 */
class new_rule_button implements renderable, templatable {
    /** @var int */
    protected $courseid;

    /**
     * @param int $courseid
     */
    public function __construct(int $courseid) {
        $this->courseid = $courseid;
    }

    public function export_for_template(renderer_base $output): array {
        return [
            'courseid' => $this->courseid,
            'label' => get_string('ruleadd', 'local_coursedynamicrules'),
        ];
    }
}
