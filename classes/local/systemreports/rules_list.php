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

namespace local_coursedynamicrules\local\systemreports;

use lang_string;
use moodle_url;
use pix_icon;
use core_reportbuilder\system_report;
use core_reportbuilder\local\report\action;

/**
 * Rules list (system report) filtered by course
 *
 * @package     local_coursedynamicrules
 * @copyright   2025 Wilber Narvaez <https://datacurso.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rules_list extends system_report {
    /**
     * Initialise the report
     */
    protected function initialise(): void {
        // Create entity first and get a stable table alias from it.
        $entity = new \local_coursedynamicrules\local\entities\rule();
        $entityalias = $entity->get_table_alias('cdr_rule');

        // Use the entity alias as the main table alias to avoid duplicate aliases in SQL.
        $this->set_main_table('cdr_rule', $entityalias);

        // Filter by course using the same alias.
        $courseid = $this->get_parameter('courseid', 0, PARAM_INT);
        $this->add_base_condition_simple("{$entityalias}.courseid", $courseid);

        // Add entity to the report.
        $this->add_entity($entity);

        // Base fields required by actions, etc.
        $this->add_base_fields("{$entityalias}.id");

        // Now add columns/filters/actions.
        $this->add_columns();
        $this->add_filters();
        $this->add_actions();

        $this->set_downloadable(false);
    }

    /**
     * Ensure we can view the report
     *
     * @return bool
     */
    protected function can_view(): bool {
        $context = $this->get_context();
        return has_capability('local/coursedynamicrules:managerule', $context);
    }

    /**
     * Add columns to report
     */
    protected function add_columns(): void {
        $this->add_columns_from_entities([
            'rule:name',
            'rule:active',
            'rule:timecreated',
            'rule:timemodified',
        ]);

        // Initial sorting.
        $this->set_initial_sort_column('rule:timecreated', SORT_DESC);
    }

    /**
     * Add filters to report
     */
    protected function add_filters(): void {
        $this->add_filters_from_entities([
            'rule:name',
            'rule:active',
            'rule:timecreated',
            'rule:timemodified',
        ]);
    }

    /**
     * Add actions to report
     */
    protected function add_actions(): void {
        // Edit content action (full-page editor), like core reportbuilder 'edit.php'.
        $this->add_action((new action(
            new moodle_url('/local/coursedynamicrules/ruleedit.php', ['id' => ':id']),
            new pix_icon('t/right', ''),
            [],
            false,
            new lang_string('editrulecontent', 'local_coursedynamicrules')
        ))
            ->add_callback(function(\stdClass $row): bool {
                return has_capability('local/coursedynamicrules:managerule', $this->get_context());
            })
        );

        // Edit details action (open modal), like core reportbuilder settings modal.
        $this->add_action((new action(
            new moodle_url('#'),
            new pix_icon('i/settings', ''),
            [
                'data-action' => 'local_coursedynamicrules/rule-edit',
                'data-rule-id' => ':id',
                'data-course-id' => $this->get_parameter('courseid', 0, PARAM_INT),
            ],
            false,
            new lang_string('editruledetails', 'local_coursedynamicrules')
        ))
            ->add_callback(function(\stdClass $row): bool {
                return has_capability('local/coursedynamicrules:managerule', $this->get_context());
            })
        );

        // Delete rule.
        $this->add_action((new action(
            new moodle_url('/local/coursedynamicrules/deleterule.php', ['id' => ':id', 'courseid' => $this->get_parameter('courseid', 0, PARAM_INT)]),
            new pix_icon('t/delete', ''),
            ['class' => 'text-danger'],
            false,
            new lang_string('deleterule', 'local_coursedynamicrules')
        )));
    }
}
