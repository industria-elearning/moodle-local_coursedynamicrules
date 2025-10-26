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

namespace local_coursedynamicrules\external;

use renderer_base;
use moodle_url;
use core_reportbuilder\external\custom_report_menu_cards_exporter;
use core_collator;

/**
 * Rule condition cards exporter class.
 *
 * Builds a sidebar menu grouped in cards for all available condition types.
 *
 * @package     local_coursedynamicrules
 * @copyright   2025
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule_condition_cards_exporter extends custom_report_menu_cards_exporter {

    /** @var int */
    protected $courseid;
    /** @var int */
    protected $ruleid;

    /**
     * Constructor.
     *
     * @param array $data
     * @param array $related
     */
    public function __construct($data, $related = []) {
        parent::__construct($data, $related);
        $this->courseid = (int)($data['courseid'] ?? 0);
        $this->ruleid = (int)($data['ruleid'] ?? 0);
    }

    /**
     * Define exporter properties.
     *
     * @return array
     */
    protected static function define_properties() {
        return [
            'courseid' => ['type' => PARAM_INT],
            'ruleid' => ['type' => PARAM_INT],
        ];
    }

    /**
     * Build menu cards for sidebar.
     *
     * @param renderer_base $output
     * @return array
     */
    protected function get_other_values(renderer_base $output): array {
        $menucards = [];

        $cardkey = 'index1';
        $cardname = get_string('conditions', 'local_coursedynamicrules');
        $menucards[$cardname] = [
            'name' => $cardname,
            'key' => $cardkey,
            'items' => [],
        ];

        // Discover condition classes for this component under the 'condition' namespace.
        $classes = \core_component::get_component_classes_in_namespace('local_coursedynamicrules', 'condition');
        foreach ($classes as $class => $path) {
            // Only include concrete condition types.
            if (!is_subclass_of($class, \local_coursedynamicrules\core\condition::class)) {
                continue;
            }

            // Expect class name like \local_coursedynamicrules\condition\TYPE\TYPE_condition.
            $parts = explode('\\', $class);
            if (count($parts) < 2) {
                continue;
            }
            $type = $parts[count($parts) - 2];
            $name = get_string($type, 'local_coursedynamicrules');

            $menucards[$cardname]['items'][] = [
                'name' => $name,
                'identifier' => $class,
                'title' => $name,
                'action' => 'local_coursedynamicrules/addcondition',
                'disabled' => false,
            ];
        }

        // Order items alphabetically.
        array_walk($menucards, static function(array &$menucard): void {
            core_collator::asort_array_of_arrays_by_key($menucard['items'], 'name');
            $menucard['items'] = array_values($menucard['items']);
        });

        return [
            'menucards' => array_values($menucards),
        ];
    }
}
