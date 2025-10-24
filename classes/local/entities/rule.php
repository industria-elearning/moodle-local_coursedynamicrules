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

namespace local_coursedynamicrules\local\entities;

use lang_string;
use stdClass;
use core_reportbuilder\local\entities\base;
use core_reportbuilder\local\report\column;
use core_reportbuilder\local\report\filter;
use core_reportbuilder\local\filters\text;
use core_reportbuilder\local\filters\select;
use core_reportbuilder\local\filters\date;
use core_reportbuilder\local\helpers\format;
use html_writer;

/**
 * Entity for course dynamic rules
 *
 * @package     local_coursedynamicrules
 * @copyright   2025 Wilber Narvaez <https://datacurso.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule extends base {
    /**
     * Tables used by this entity
     *
     * @return string[]
     */
    protected function get_default_tables(): array {
        return ['cdr_rule'];
    }

    /**
     * Default entity title
     *
     * @return lang_string
     */
    protected function get_default_entity_title(): lang_string {
        return new lang_string('name', 'local_coursedynamicrules');
    }

    /**
     * Define columns and filters provided by this entity
     */
    public function initialise(): self {
        // Add all columns.
        foreach ($this->get_all_columns() as $column) {
            $this->add_column($column);
        }

        // Add all filters also as conditions.
        foreach ($this->get_all_filters() as $filter) {
            $this->add_filter($filter)
                ->add_condition($filter);
        }

        return $this;
    }

    /**
     * Returns list of all available columns
     *
     * @return column[]
     */
    protected function get_all_columns(): array {
        $columns = [];
        $tablealias = $this->get_table_alias('cdr_rule');

        // Name.
        $columns[] = (new column(
            'name',
            new lang_string('name', 'local_coursedynamicrules'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TEXT)
            ->add_field("{$tablealias}.name")
            ->set_is_sortable(true);

        // Active (badge rendered by callback).
        $columns[] = (new column(
            'active',
            new lang_string('ruleactive', 'local_coursedynamicrules'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_INTEGER)
            ->add_field("{$tablealias}.active", 'active')
            ->add_callback(function(int $active): string {
                $key = ($active === 1) ? 'ruleactive' : 'ruleinactive';
                $cls = ($active === 1) ? 'badge badge-success' : 'badge badge-secondary';
                return html_writer::span(get_string($key, 'local_coursedynamicrules'), $cls);
            });

        // Time created.
        $columns[] = (new column(
            'timecreated',
            new lang_string('timecreated', 'core_reportbuilder'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TIMESTAMP)
            ->add_field("{$tablealias}.timecreated")
            ->set_is_sortable(true)
            ->add_callback([format::class, 'userdate']);

        // Time modified.
        $columns[] = (new column(
            'timemodified',
            new lang_string('timemodified', 'core_reportbuilder'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TIMESTAMP)
            ->add_field("{$tablealias}.timemodified")
            ->set_is_sortable(true)
            ->add_callback([format::class, 'userdate']);

        return $columns;
    }

    /**
     * Return list of all available filters
     *
     * @return filter[]
     */
    protected function get_all_filters(): array {
        $filters = [];
        $tablealias = $this->get_table_alias('cdr_rule');

        // Name filter.
        $filters[] = (new filter(
            text::class,
            'name',
            new lang_string('name', 'local_coursedynamicrules'),
            $this->get_entity_name(),
            "{$tablealias}.name"
        ))->add_joins($this->get_joins());

        // Active filter.
        $filters[] = (new filter(
            select::class,
            'active',
            new lang_string('ruleactive', 'local_coursedynamicrules'),
            $this->get_entity_name(),
            "{$tablealias}.active"
        ))
            ->add_joins($this->get_joins())
            ->set_options([
                1 => get_string('yes'),
                0 => get_string('no'),
            ]);

        // Date filters.
        $filters[] = (new filter(
            date::class,
            'timecreated',
            new lang_string('timecreated', 'core_reportbuilder'),
            $this->get_entity_name(),
            "{$tablealias}.timecreated"
        ))->add_joins($this->get_joins());

        $filters[] = (new filter(
            date::class,
            'timemodified',
            new lang_string('timemodified', 'core_reportbuilder'),
            $this->get_entity_name(),
            "{$tablealias}.timemodified"
        ))->add_joins($this->get_joins());

        return $filters;
    }
}
