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
use context_course;
use renderer_base;
use local_coursedynamicrules\external\rule_condition_cards_exporter;
use local_coursedynamicrules\helper\rule_component_loader;
use moodle_url;

/**
 * Conditions dynamic tab for rule editor
 *
 * @package     local_coursedynamicrules
 * @copyright   2025 Wilber Narvaez
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class conditions extends base {
    /**
     * Export this for use in a mustache template context
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        global $DB;

        $ruleid = (int)($this->data['ruleid'] ?? 0);
        $courseid = (int)($this->data['courseid'] ?? 0);
        $contextid = $courseid ? \context_course::instance($courseid)->id : 0;

        // Build sidebar menu cards for available conditions.
        $menucardsexporter = new rule_condition_cards_exporter([
            'courseid' => $courseid,
            'ruleid' => $ruleid,
        ]);
        $menucards = (array)$menucardsexporter->export($output);

        // Collect current condition instances for this rule (shape aligned with audience form context).
        $conditionsfortemplate = [];
        if ($ruleid) {
            $conditions = $DB->get_records('cdr_condition', ['ruleid' => $ruleid]);
            $index = 0;
            foreach ($conditions as $condition) {
                $conditioninstance = rule_component_loader::create_condition_instance($condition, $courseid);
                $header = $conditioninstance->get_header();
                $description = $conditioninstance->get_description();
                if (!empty($header) && !empty($description)) {
                    $conditionsfortemplate[] = [
                        'instanceid' => (int)$condition->id,
                        'heading' => $header,
                        'headingeditable' => $header,
                        'description' => $description,
                        'canedit' => true,
                        'candelete' => true,
                        'showormessage' => ($index++ > 0),
                    ];
                }
            }
        }

        return [
            'tabheading' => get_string('conditions', 'local_coursedynamicrules'),
            'courseid' => $courseid,
            'ruleid' => $ruleid,
            'contextid' => $contextid,
            'sidebarmenucards' => $menucards,
            'instances' => $conditionsfortemplate,
            'hasinstances' => !empty($conditionsfortemplate),
        ];
    }

    /**
     * The label to be displayed on the tab
     *
     * @return string
     */
    public function get_tab_label(): string {
        return get_string('conditions', 'local_coursedynamicrules');
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
        return 'local_coursedynamicrules/local/dynamictabs/conditions';
    }
}
