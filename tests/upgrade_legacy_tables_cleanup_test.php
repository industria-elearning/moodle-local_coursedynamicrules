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

namespace local_coursedynamicrules;

/**
 * Tests cleanup of legacy CDR tables during plugin upgrade.
 *
 * @package    local_coursedynamicrules
 * @covers     ::local_coursedynamicrules_upgrade_cleanup_legacy_tables
 * @copyright  2026 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class upgrade_legacy_tables_cleanup_test extends \advanced_testcase {
    /**
     * Test legacy cdr_* tables are dropped when they still exist.
     */
    public function test_upgrade_cleanup_drops_legacy_tables(): void {
        global $DB, $CFG;

        $this->resetAfterTest(true);
        require_once($CFG->dirroot . '/local/coursedynamicrules/db/upgrade.php');

        $dbman = $DB->get_manager();

        $legacytables = ['cdr_rule', 'cdr_condition', 'cdr_action'];
        foreach ($legacytables as $tablename) {
            $table = new \xmldb_table($tablename);
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

            if (!$dbman->table_exists($table)) {
                $dbman->create_table($table);
            }
        }

        \local_coursedynamicrules_upgrade_cleanup_legacy_tables();

        foreach ($legacytables as $tablename) {
            $table = new \xmldb_table($tablename);
            $this->assertFalse($dbman->table_exists($table));
        }
    }
}
