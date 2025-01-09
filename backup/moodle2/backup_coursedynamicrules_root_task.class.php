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
 * The task that provides all the steps to perform a complete backup is defined here.
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// More information about the backup process: {@link https://docs.moodle.org/dev/Backup_API}.
// More information about the restore process: {@link https://docs.moodle.org/dev/Restore_API}.

require_once($CFG->dirroot . '/local/coursedynamicrules/backup/moodle2/backup_coursedynamicrules_settingslib.php');

/**
 * Provides all the settings and steps to perform a complete backup of local_coursedynamicrules.
 */
class backup_coursedynamicrules_root_task extends backup_root_task {

    /**
     * Defines particular settings for the plugin.
     */
    protected function define_settings() {
        parent::define_settings();
        // Define course dynamic rules inclusion setting if it are used.
        $coursedynamicrules = new backup_coursedynamicrules_setting('coursedynamicrules', base_setting::IS_BOOLEAN, true);
        $coursedynamicrules->set_ui(
            new backup_setting_ui_checkbox($coursedynamicrules, get_string('includecoursedynamicrules', 'local_coursedynamicrules'))
        );
        $this->add_setting($coursedynamicrules);
    }
}
