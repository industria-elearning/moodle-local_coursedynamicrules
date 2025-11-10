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

declare(strict_types=1);

namespace local_coursedynamicrules\output\dynamictabs;

use core\output\dynamic_tabs\base;
use local_coursedynamicrules\output\actions_page;
use context_course;
use renderer_base;

/**
 * Actions dynamic tab for rule editor
 *
 * @package     local_coursedynamicrules
 * @copyright   2025 Wilber Narvaez
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class actions extends base {
    /**
     * Export this for use in a mustache template context
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        $ruleid = (int)($this->data['ruleid'] ?? 0);
        $courseid = (int)($this->data['courseid'] ?? 0);

        $page = new actions_page($courseid, $ruleid);
        $data = $page->export_for_template($output);

        $data['tabheading'] = get_string('actions', 'local_coursedynamicrules');
        return $data;
    }

    /**
     * The label to be displayed on the tab
     *
     * @return string
     */
    public function get_tab_label(): string {
        return get_string('actions', 'local_coursedynamicrules');
    }

    /**
     * Check permission of the current user to access this tab
     *
     * @return bool
     */
    public function is_available(): bool {
        $courseid = (int)($this->data['courseid'] ?? 0);
        if (!$courseid) {
            return false;
        }
        $context = context_course::instance($courseid);
        return has_capability('local/coursedynamicrules:managerule', $context);
    }

    /**
     * Template to use to display tab contents
     *
     * @return string
     */
    public function get_template(): string {
        return 'local_coursedynamicrules/actions_page';
    }
}
