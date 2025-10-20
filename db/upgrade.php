<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin upgrade steps are defined here.
 *
 * @package     local_coursedynamicrules
 * @category    upgrade
 * @copyright   2024 Industria Elearning <info@industriaelearning.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute local_coursedynamicrules upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_coursedynamicrules_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // For further information please read {@link https://docs.moodle.org/dev/Upgrade_API}.
    //
    // You will also have to create the db/install.xml file by using the XMLDB Editor.
    // Documentation for the XMLDB Editor can be found at {@link https://docs.moodle.org/dev/XMLDB_editor}.
    if ($oldversion < 2024102000) {
        // Define table cdr_rule to be created.
        $table = new xmldb_table('cdr_rule');

        // Adding fields to table cdr_rule.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('active', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '16', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '16', null, null, null, null);

        // Adding keys to table cdr_rule.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('courseid', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);

        // Conditionally launch create table for cdr_rule.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table cdr_condition to be created.
        $table = new xmldb_table('cdr_condition');

        // Adding fields to table cdr_condition.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('ruleid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('conditiontype', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('eventname', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('params', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table cdr_condition.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('ruleid', XMLDB_KEY_FOREIGN, ['ruleid'], 'cdr_rule', ['id']);

        // Conditionally launch create table for cdr_condition.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table cdr_action to be created.
        $table = new xmldb_table('cdr_action');

        // Adding fields to table cdr_action.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('ruleid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('actiontype', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('params', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table cdr_action.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('ruleid', XMLDB_KEY_FOREIGN, ['ruleid'], 'cdr_rule', ['id']);

        // Conditionally launch create table for cdr_action.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Coursedynamicrules savepoint reached.
        upgrade_plugin_savepoint(true, 2024102000, 'local', 'coursedynamicrules');
    }

    if ($oldversion < 2025010600) {
        // Define field lastexecutiontime to be added to cdr_rule.
        $table = new xmldb_table('cdr_rule');
        $field = new xmldb_field('lastexecutiontime', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'active');

        // Conditionally launch add field lastexecutiontime.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Changing precision of field timecreated on table cdr_rule to (10).
        $table = new xmldb_table('cdr_rule');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'lastexecutiontime');

        // Launch change of precision for field timecreated.
        $dbman->change_field_precision($table, $field);

        // Changing precision of field timemodified on table cdr_rule to (10).
        $table = new xmldb_table('cdr_rule');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timecreated');

        // Launch change of precision for field timemodified.
        $dbman->change_field_precision($table, $field);

        // Coursedynamicrules savepoint reached.
        upgrade_plugin_savepoint(true, 2025010600, 'local', 'coursedynamicrules');
    }

    if ($oldversion < 2025022601) {
        // Define field lastexecutiontime to be added to cdr_condition.
        $table = new xmldb_table('cdr_condition');
        $field = new xmldb_field('lastexecutiontime', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'params');

        // Conditionally launch add field lastexecutiontime.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field lastexecutiontime to be added to cdr_action.
        $table = new xmldb_table('cdr_action');
        $field = new xmldb_field('lastexecutiontime', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'params');

        // Conditionally launch add field lastexecutiontime.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Coursedynamicrules savepoint reached.
        upgrade_plugin_savepoint(true, 2025022601, 'local', 'coursedynamicrules');
    }

    return true;
}
