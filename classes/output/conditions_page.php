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
use moodle_url;
use local_coursedynamicrules\helper\rule_component_loader;

/**
 * Page renderable for the Conditions management view.
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Wilber Narvaez <https://datacurso.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class conditions_page implements renderable, templatable {
    /** @var int */
    private $courseid;
    /** @var int */
    private $ruleid;
    /** @var string|null */
    private $formhtml;

    /**
     * Constructor.
     *
     * @param int $courseid
     * @param int $ruleid
     * @param string|null $formhtml Optional HTML for the right column (form output)
     */
    public function __construct(int $courseid, int $ruleid, ?string $formhtml = null) {
        $this->courseid = $courseid;
        $this->ruleid = $ruleid;
        $this->formhtml = $formhtml;
    }

    /**
     * Export data for mustache template.
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        global $DB;

        $backlinkurl = (new moodle_url('/local/coursedynamicrules/rules.php', [
            'courseid' => $this->courseid,
        ]))->out(false);

        // Build conditions list (always displayed).
        $conditionsfortemplate = [];
        $conditions = $DB->get_records('cdr_condition', ['ruleid' => $this->ruleid]);
        foreach ($conditions as $condition) {
            $conditioninstance = rule_component_loader::create_condition_instance($condition, $this->courseid);
            $header = $conditioninstance->get_header();
            $description = $conditioninstance->get_description();
            if (!empty($header) && !empty($description)) {
                $deleteurl = (new moodle_url('/local/coursedynamicrules/deletecondition.php', [
                    'id' => $condition->id,
                    'ruleid' => $this->ruleid,
                    'courseid' => $this->courseid,
                ]))->out(false);
                $conditionsfortemplate[] = [
                    'id' => $condition->id,
                    'header' => $header,
                    'description' => $description,
                    'deleteurl' => $deleteurl,
                ];
            }
        }

        // Build menu options.
        $options = [];
        $conditiontypes = get_list_of_plugins('local/coursedynamicrules/classes/condition');
        foreach ($conditiontypes as $conditiontype) {
            $conditionclass = "\\local_coursedynamicrules\\condition\\{$conditiontype}\\{$conditiontype}_condition";
            if (class_exists($conditionclass)) {
                $url = (new moodle_url('/local/coursedynamicrules/conditions.php', [
                    'courseid' => $this->courseid,
                    'type' => $conditiontype,
                    'ruleid' => $this->ruleid,
                ]))->out(false);
                $options[] = [
                    'type' => $conditiontype,
                    'visualname' => get_string($conditiontype, 'local_coursedynamicrules'),
                    'action' => $url,
                ];
            }
        }
        asort($options);

        $data = [
            'backlinkurl' => $backlinkurl,
            'backlinktext' => get_string('backtolistrules', 'local_coursedynamicrules'),
            'options' => $options,
            'conditions' => $conditionsfortemplate,
        ];

        if (!empty($this->formhtml)) {
            $data['formhtml'] = $this->formhtml;
        }

        return $data;
    }
}
