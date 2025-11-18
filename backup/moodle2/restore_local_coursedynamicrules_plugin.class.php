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
 * Restore plugin for local_coursedynamicrules.
 *
 * @package    local_coursedynamicrules
 * @copyright  2025 Wilber Narvaez <https://datacurso.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Defines course-level restore structure and processing for coursedynamicrules local plugin.
 *
 * @package   local_coursedynamicrules
 */
class restore_local_coursedynamicrules_plugin extends restore_local_plugin {

    /**
     * Define plugin structure
     *
     * @return restore_path_element[]
     */
    protected function define_course_plugin_structure() {
        return [
            new restore_path_element(
                'local_coursedynamicrules_rule',
                $this->get_pathfor('/rules/rule')
            ),
            new restore_path_element(
                'local_coursedynamicrules_condition',
                $this->get_pathfor('/rules/rule/conditions/condition')
            ),
            new restore_path_element(
                'local_coursedynamicrules_action',
                $this->get_pathfor('/rules/rule/actions/action')
            ),
        ];
    }

    /**
     * Process rule element
     *
     * @param array $data
     *
     * @return void
     */
    public function process_local_coursedynamicrules_rule($data) {
        global $DB;

        $data = (object)$data;

        $record = new \stdClass();
        $record->courseid = $this->task->get_courseid();
        $record->name = $data->name ?? null;
        $record->description = $data->description ?? null;
        $record->active = $data->active ?? 0;
        $record->lastexecutiontime = $data->lastexecutiontime ?? null;
        $record->timecreated = $data->timecreated ?? time();
        $record->timemodified = $data->timemodified ?? time();

        $newruleid = $DB->insert_record('local_coursedynamicrules_rule', $record);
        $this->set_mapping('local_coursedynamicrules_rule', $data->id, $newruleid, false);
    }

    /**
     * Process condition element
     *
     * @param array $data
     *
     * @return void
     */
    public function process_local_coursedynamicrules_condition($data) {
        global $DB;

        $data = (object)$data;

        $record = new \stdClass();
        $record->ruleid = $this->get_mappingid('local_coursedynamicrules_rule', $data->ruleid);
        $record->name = $data->name ?? null;
        $record->conditiontype = $data->conditiontype ?? null;
        $record->eventname = $data->eventname ?? null;
        $record->params = $this->remap_condition_params($data->params ?? '');
        $record->lastexecutiontime = $data->lastexecutiontime ?? null;

        $newconditionid = $DB->insert_record('local_coursedynamicrules_condition', $record);
        $this->set_mapping('local_coursedynamicrules_condition', $data->id, $newconditionid, false);
    }

    /**
     * Process action element
     *
     * @param array $data
     *
     * @return void
     */
    public function process_local_coursedynamicrules_action($data) {
        global $DB;

        $data = (object)$data;

        $record = new \stdClass();
        $record->ruleid = $this->get_mappingid('local_coursedynamicrules_rule', $data->ruleid);
        $record->name = $data->name ?? null;
        $record->actiontype = $data->actiontype ?? null;
        $record->params = $this->remap_action_params($data->params ?? '');
        $record->lastexecutiontime = $data->lastexecutiontime ?? null;

        $newactionid = $DB->insert_record('local_coursedynamicrules_action', $record);
        $this->set_mapping('local_coursedynamicrules_action', $data->id, $newactionid, false);
    }

    /**
     * Remap params for conditions.
     *
     * @param string $paramsjson
     * @return string
     */
    protected function remap_condition_params(string $paramsjson): string {
        $params = json_decode($paramsjson);
        if (!$params) {
            return $paramsjson;
        }

        if (isset($params->cmid)) {
            $mappedcmid = $this->get_mapped_cmid($params->cmid);
            // Keep original id if mapping is not available to avoid losing the condition definition.
            $params->cmid = $mappedcmid ?? $params->cmid;
        }

        if (!empty($params->gradeitemsconditions)) {
            $params->gradeitemsconditions = $this->remap_gradeitemsconditions($params->gradeitemsconditions);
        }

        return json_encode($params);
    }

    /**
     * Remap params for actions.
     *
     * @param string $paramsjson
     * @return string
     */
    protected function remap_action_params(string $paramsjson): string {
        $params = json_decode($paramsjson);
        if (!$params) {
            return $paramsjson;
        }

        if (!empty($params->coursemodules)) {
            $params->coursemodules = $this->remap_coursemodules($params->coursemodules);
        }

        if (!empty($params->beforemod)) {
            $mappedbeforemod = $this->get_mapped_cmid($params->beforemod);
            $params->beforemod = $mappedbeforemod ?? $params->beforemod;
        }

        if (isset($params->cmid)) {
            $mappedcmid = $this->get_mapped_cmid($params->cmid);
            $params->cmid = $mappedcmid ?? $params->cmid;
        }

        return json_encode($params);
    }

    /**
     * Remap grade item conditions to the new course ids.
     *
     * @param array|object $gradeitemsconditions
     * @return array
     */
    protected function remap_gradeitemsconditions($gradeitemsconditions): array {
        $remapped = [];
        foreach ((array)$gradeitemsconditions as $key => $gradeitemcondition) {
            $condition = (object)$gradeitemcondition;
            $oldgradeitemid = $condition->gradeitem ?? null;
            $newgradeitemid = $oldgradeitemid ? $this->get_mappingid('grade_item', $oldgradeitemid) : null;

            if ($newgradeitemid) {
                $condition->gradeitem = $newgradeitemid;
                $key = preg_replace('/_(\d+)$/', '_' . $newgradeitemid, (string)$key);
            }

            $remapped[$key] = $condition;
        }

        return $remapped;
    }

    /**
     * Remap course module references stored in params.
     *
     * @param array|object $coursemodules
     * @return array
     */
    protected function remap_coursemodules($coursemodules): array {
        $remapped = [];
        foreach ((array)$coursemodules as $coursemodule) {
            $coursemodule = (object)$coursemodule;
            $oldcmid = $coursemodule->id ?? null;
            $newcmid = $this->get_mapped_cmid($oldcmid);

            // If there is no mapping, keep the old id so the record is not lost.
            $coursemodule->id = $newcmid ?? $oldcmid;
            $remapped[] = $coursemodule;
        }

        return $remapped;
    }

    /**
     * Returns the new course module mapping.
     *
     * @param int|null $cmid
     * @return int|null
     */
    protected function get_mapped_cmid($cmid): ?int {
        if (empty($cmid)) {
            return null;
        }

        $newcmid = $this->get_mappingid('course_module', $cmid);

        return $newcmid ? (int)$newcmid : null;
    }

    /**
     * Final pass to remap any lingering ids once all mappings are available.
     *
     * @return void
     */
    public function after_restore_course() {
        $this->remap_persisted_params();
    }

    /**
     * Re-run param remapping for restored rules so cmids/grade items update after activities are created.
     *
     * @return void
     */
    protected function remap_persisted_params() {
        global $DB;

        $records = $DB->get_records(
            'backup_ids_temp',
            [
                'backupid' => $this->get_restoreid(),
                'itemname' => 'local_coursedynamicrules_rule',
            ],
            '',
            'itemid, newitemid'
        );

        if (empty($records)) {
            return;
        }

        foreach ($records as $record) {
            $newruleid = $record->newitemid;

            $this->remap_condition_records($newruleid);
            $this->remap_action_records($newruleid);
        }
    }

    /**
     * Remap and persist params for conditions of a rule.
     *
     * @param int $ruleid
     * @return void
     */
    protected function remap_condition_records(int $ruleid) {
        global $DB;

        $conditions = $DB->get_records('local_coursedynamicrules_condition', ['ruleid' => $ruleid]);
        foreach ($conditions as $condition) {
            $paramsjson = $condition->params ?? '';
            $remappedjson = $this->remap_condition_params($paramsjson);

            if ($remappedjson !== $paramsjson) {
                $DB->set_field('local_coursedynamicrules_condition', 'params', $remappedjson, ['id' => $condition->id]);
            }
        }
    }

    /**
     * Remap and persist params for actions of a rule.
     *
     * @param int $ruleid
     * @return void
     */
    protected function remap_action_records(int $ruleid) {
        global $DB;

        $actions = $DB->get_records('local_coursedynamicrules_action', ['ruleid' => $ruleid]);
        foreach ($actions as $action) {
            $paramsjson = $action->params ?? '';
            $remappedjson = $this->remap_action_params($paramsjson);

            if ($remappedjson !== $paramsjson) {
                $DB->set_field('local_coursedynamicrules_action', 'params', $remappedjson, ['id' => $action->id]);
            }
        }
    }
}
