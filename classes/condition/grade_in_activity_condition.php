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

namespace local_coursedynamicrules\condition;

use cm_info;
use core_grades\component_gradeitems;
use grade_grade;
use grade_item;
use local_coursedynamicrules\core\condition;
use local_coursedynamicrules\form\conditions\grade_in_activity_form;
use stdClass;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/gradelib.php');

/**
 * Class grade_in_activity_condition
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class grade_in_activity_condition extends condition {
    /** @var string type of condition */
    protected $type = "grade_in_activity";

    /**
     * Adds condition-specific form elements
     *
     * @param \MoodleQuickForm $mform The form to add elements to
     * @return void
     */
    public function get_config_form(\MoodleQuickForm $mform): void {
        global $OUTPUT, $PAGE;
        $courseid = $this->courseid;
        $coursemodule = $mform->optional_param('coursemodule', 0, PARAM_INT);

        $attributes = $mform->getAttributes();
        $attributes['data-region'] = 'local_coursedynamicrules/grade_in_activity_form';

        $mform->setAttributes($attributes);

        $notification = $OUTPUT->notification(
            get_string('grade_in_activity_condition_info', 'local_coursedynamicrules'),
            \core\output\notification::NOTIFY_INFO
        );
        $mform->addElement('html', $notification);

        $modinfo = get_fast_modinfo($courseid);
        $cms = $modinfo->get_cms();
        $options = [];
        foreach ($cms as $cm) {
            // Indicate when require grade is enable.
            // See get_moduleinfo_data funtion.
            $completionusegrade = !is_null($cm->completiongradeitemnumber);

            if ($cm->completion == COMPLETION_TRACKING_AUTOMATIC && $completionusegrade && !$cm->deletioninprogress) {
                $options[$cm->id] = ucfirst($cm->modname) . " - " . $cm->name;
            }
        }

        $attributes = [
            'multiple' => false,
            'noselectionstring' => get_string('allcourseactivitymodules', 'local_coursedynamicrules'),
            'data-action' => 'local_coursedynamicrules/change_coursemodule',
        ];
        $mform->addElement(
            'autocomplete',
            'coursemodule',
            get_string('searchcourseactivitymodules', 'local_coursedynamicrules'),
            $options,
            $attributes
        );

        $modinfo = get_fast_modinfo($courseid);
        $cms = $modinfo->get_cms();
        $filteredcms = [];
        $options = [];
        foreach ($cms as $cm) {
            // Indicate when require grade is enable.
            // See get_moduleinfo_data funtion.
            $completionusegrade = !is_null($cm->completiongradeitemnumber);

            if ($cm->completion == COMPLETION_TRACKING_AUTOMATIC && $completionusegrade && !$cm->deletioninprogress) {
                $options[$cm->id] = ucfirst($cm->modname) . " - " . $cm->name;
                $filteredcms[$cm->id] = $cm;
            }
        }

        $cm = $coursemodule ? $filteredcms[$coursemodule] : reset($filteredcms);

        if ($cm) {
            $component = 'mod_' . $cm->modname;

            // Get the itemnames mapping for the component. This is used to display the grade item names in the form.
            $itemnames = component_gradeitems::get_itemname_mapping_for_component($component);

            if (count($itemnames) === 1) {
                $options = [
                    'groupstring' => get_string('gradenoun'),
                ];
                $this->add_grade_elements($mform, $cm, 0, $options);
            } else if (count($itemnames) > 1) {
                foreach ($itemnames as $itemnumber => $itemname) {
                    $optiongroup = [
                        'groupstring' => get_string("grade_{$itemname}_name", $component),
                    ];
                    $this->add_grade_elements($mform, $cm, $itemnumber, $optiongroup);
                }
            }
        }

        $mform->addElement('hidden', 'cmid');
        $mform->setType('cmid', PARAM_INT);

        $jsargs = [
            \context_course::instance($courseid)->id,
            $courseid,
        ];
        $PAGE->requires->js_call_amd(
            'local_coursedynamicrules/grade_in_activity_form',
            'init',
            $jsargs
        );
    }

    /**
     * Adds grade elements to the form.
     *
     * This function adds two groups of elements to the form: one for grades greater than or equal to a specified value,
     * and another for grades less than a specified value. Each group consists of a checkbox to enable the condition and
     * a text input to specify the grade value.
     *
     * @param \MoodleQuickForm $mform The form to which the elements will be added.
     * @param cm_info $cm The course module object
     * @param int $itemnumber The item number for the grade item.
     * @param array $optiongroup An array containing the group string for the option group.
     */
    private function add_grade_elements($mform, $cm, $itemnumber, $optiongroup) {
        // Fetch the grade item based on the course module and item number.
        $gradeitem = grade_item::fetch(
            [
                'iteminstance' => $cm->instance,
                'itemmodule' => $cm->modname,
                'itemtype' => 'mod',
                'itemnumber' => $itemnumber,
            ]
        );

        $decimals = $gradeitem->get_decimals();
        $grademin = format_float($gradeitem->grademin, $decimals);
        $grademax = format_float($gradeitem->grademax, $decimals);

        // Load the scale for the grade item.
        $scale = $gradeitem->load_scale();

        $attributes = [
            'data-cmid' => $cm->id,
            'data-gradeitem' => $gradeitem->id,
            'data-grademin' => $grademin,
            'data-grademax' => $grademax,
            'data-name' => 'local_coursedynamicrules/grede_in_activity_input',
        ];

        $namegradegte = 'grade[' . $gradeitem->id . '][gte]';
        $namegradelt = 'grade[' . $gradeitem->id . '][lt]';
        $nameenablegte = 'enable[' . $gradeitem->id . '][gte]';
        $nameenablelt = 'enable[' . $gradeitem->id . '][lt]';

        if ($scale) {
            $options = [];
            foreach ($scale->scale_items as $i => $name) {
                $options[$i + 1] = $name;
            }

            $attributes['data-condition'] = 'gradegte';
            $elementgradegte = $mform->createElement(
                'select',
                $namegradegte,
                '',
                $options,
                $attributes
            );

            $attributes['data-condition'] = 'gradelt';
            $elementgradelt = $mform->createElement(
                'select',
                $namegradelt,
                '',
                $options,
                $attributes
            );
        } else {
            $attributes['data-condition'] = 'gradegte';
            $elementgradegte = $mform->createElement(
                'text',
                $namegradegte,
                '',
                $attributes
            );

            $attributes['data-condition'] = 'gradelt';
            $elementgradelt = $mform->createElement(
                'text',
                $namegradelt,
                '',
                $attributes
            );
        }

        // Create elements for "grade greater than or equal" condition.
        $gradegreatergroup = [];
        $gradegreatergroup[] = $mform->createElement(
            'advcheckbox',
            $nameenablegte,
            '',
            get_string('gradegreaterthanorequal', 'local_coursedynamicrules'),
        );
        $gradegreatergroup[] = $elementgradegte;
        $groupstring = $optiongroup['groupstring'];
        $groupstring .= ' (' . $grademin . ' - ' . $grademax . ')';
        $mform->addGroup(
            $gradegreatergroup,
            'gradegtegroup_' . $gradeitem->id,
            $groupstring,
            ' ',
            false
        );
        $mform->addHelpButton('gradegtegroup_' . $gradeitem->id, 'gradegreaterthanorequal', 'local_coursedynamicrules');
        $mform->disabledIf($namegradegte, $nameenablegte, 'notchecked');
        $mform->setType($namegradegte, PARAM_FLOAT);

        // Create elements for "grade less than" condition.
        $gradelessgroup = [];
        $gradelessgroup[] = $mform->createElement(
            'advcheckbox',
            $nameenablelt,
            '',
            get_string('gradelessthan', 'local_coursedynamicrules'),
        );
        $gradelessgroup[] = $elementgradelt;
        $mform->addGroup($gradelessgroup, 'gradeltgroup_' . $gradeitem->id, '', ' ', false);
        $mform->addHelpButton('gradeltgroup_' . $gradeitem->id, 'gradelessthan', 'local_coursedynamicrules');
        $mform->disabledIf($namegradelt, $nameenablelt, 'notchecked');
        $mform->setType($namegradelt, PARAM_FLOAT);
    }

    /**
     * Evaluate the condition and return true if the condition is met
     * @param object $context Context of the rule
     * @return bool
     */
    public function evaluate($context) {

        $courseid = $context->courseid;
        $userid = $context->userid;
        $params = (array)$this->params;
        $cmid = $params['cmid'];
        $gradeitemsconditions = $params['gradeitemsconditions'];

        // This is for evaluate the condition only for the course module obtained from event observer related data.
        if (isset($context->cmid) && $context->cmid != $cmid) {
            return false;
        }

        $cminfo = get_coursemodule_from_id(null, $cmid, $courseid);
        if (!$cminfo || $cminfo->deletioninprogress) {
            return false;
        }

        if ($cminfo->completion != COMPLETION_TRACKING_AUTOMATIC) {
            return false;
        }

        $hasgraderequire = false;
        $allitemconditionsmet = true;

        /** @var grade_item[]  $gradeitems */
        $gradeitems = grade_item::fetch_all(['iteminstance' => $cminfo->instance, 'itemmodule' => $cminfo->modname]);

        foreach ($gradeitems as $gradeitem) {
            $gradeitemid = $gradeitem->id;

            $grade = grade_grade::fetch(['itemid' => $gradeitem->id, 'userid' => $userid]);
            $finalgrade = $grade->finalgrade;

            $conditions = [];
            if (isset($gradeitemsconditions[$gradeitemid])) {
                $conditions = (array)$gradeitemsconditions[$gradeitemid];
            }

            $gte = $conditions['gradegte'] ?? null;
            if ($gte && !empty($gte['value'])) {
                $gtebounded = $gradeitem->bounded_grade($gte['value']);
                if ($finalgrade >= $gtebounded) {
                    $hasgraderequire = true;
                } else {
                    $allitemconditionsmet = false;
                }
            }

            $lt = $conditions['gradelt'] ?? null;
            if ($lt && !empty($lt['value'])) {
                $ltbounded = $gradeitem->bounded_grade($lt['value']);
                if ($finalgrade < $ltbounded) {
                    $hasgraderequire = true;
                } else {
                    $allitemconditionsmet = false;
                }
            }

            if (!$allitemconditionsmet) {
                break;
            }
        }

        return $hasgraderequire && $allitemconditionsmet;
    }

    /**
     * Returns the header of the condition to visualization
     *
     * @return string
     */
    public function get_header() {
        return get_string('grade_in_activity', 'local_coursedynamicrules');
    }

    /**
     * Returns the description of the condition to visualization
     *
     * @return string
     */
    public function get_description() {
        $courseid = $this->courseid;
        $cmid = $this->params->cmid;
        $modinfo = get_fast_modinfo($courseid);
        $cms = $modinfo->get_cms();
        $cminfo = $cms[$cmid];

        if (!$cminfo) {
            return '';
        }

        $component = 'mod_' . $cminfo->modname;

        // Get the itemnames mapping for the component. This is used to display the grade item names in the form.
        $itemnames = component_gradeitems::get_itemname_mapping_for_component($component);

        $gradeitemsconditions = $this->params->gradeitemsconditions;

        $gradestrings = [];
        if (count($itemnames) === 1) {
            $itemnamestring = get_string('gradenoun');
            $gradestrings[] = $this->get_grades_string($cminfo, 0, $itemnamestring, $gradeitemsconditions);
        } else if (count($itemnames) > 1) {
            foreach ($itemnames as $itemnumber => $itemname) {
                $itemnamestring = get_string("grade_{$itemname}_name", $component);
                $gradestrings[] = $this->get_grades_string($cminfo, $itemnumber, $itemnamestring, $gradeitemsconditions);
            }
        }

        $gradestring = implode(', ', $gradestrings);

        return get_string(
            'grade_in_activity_description',
            'local_coursedynamicrules',
            [
                'moddescription' => ucfirst($cminfo->modname) . " - " . $cminfo->name,
                'gradestring' => $gradestring,
            ]
        );
    }

    /**
     * Generates a string representation of grade conditions for a given activity.
     *
     * @param cm_info $cminfo Information about the course module instance.
     * @param int $itemnumber The item number of the grade item.
     * @param string $itemnamestring The name of the grade item.
     * @param object $gradeitemsconditions Conditions related to grade items.
     * @return string A string representing the grade conditions for the specified activity.
     */
    private function get_grades_string($cminfo, $itemnumber, $itemnamestring, $gradeitemsconditions) {
        $gradeitem = grade_item::fetch(
            [
                'iteminstance' => $cminfo->instance,
                'itemmodule' => $cminfo->modname,
                'itemtype' => 'mod',
                'itemnumber' => $itemnumber,
            ]
        );

        $gradeitemid = $gradeitem->id;

        $gradestrings = [];

        $itemconds = null;
        if (is_object($gradeitemsconditions) && isset($gradeitemsconditions->{$gradeitemid})) {
            $itemconds = $gradeitemsconditions->{$gradeitemid};
        } else if (is_array($gradeitemsconditions) && isset($gradeitemsconditions[$gradeitemid])) {
            $itemconds = (object) $gradeitemsconditions[$gradeitemid];
        }

        if (
            $itemconds && isset($itemconds->gradegte) && isset($itemconds->gradegte->value)
            && empty($itemconds->gradegte->disabled) && $itemconds->gradegte->value !== ''
        ) {
            $gradestrings[] = get_string('gradegreaterthanorequalvalue', 'local_coursedynamicrules', $itemconds->gradegte->value);
        }

        if (
            $itemconds && isset($itemconds->gradelt) && isset($itemconds->gradelt->value)
            && empty($itemconds->gradelt->disabled) && $itemconds->gradelt->value !== ''
        ) {
            $gradestrings[] = get_string('gradelessthanvalue', 'local_coursedynamicrules', $itemconds->gradelt->value);
        }

        if (empty($gradestrings)) {
            return '';
        }

        return "\"{$itemnamestring}\" " . implode(' & ', $gradestrings);
    }

    /**
     * Creates and returns an instance of the form for editing the item
     *
     * @param mixed $action the action attribute for the form. If empty defaults to auto detect the
     *              current url. If a moodle_url object then outputs params as hidden variables.
     * @param mixed $customdata if your form defintion method needs access to data such as $course
     *              $cm, etc. to construct the form definition then pass it in this array. You can
     *              use globals for somethings.
     * @param string $method if you set this to anything other than 'post' then _GET and _POST will
     *               be merged and used as incoming data to the form.
     * @param string $target target frame for form submission. You will rarely use this. Don't use
     *               it if you don't need to as the target attribute is deprecated in xhtml strict.
     * @param mixed $attributes you can pass a string of html attributes here or an array.
     *               Special attribute 'data-random-ids' will randomise generated elements ids. This
     *               is necessary when there are several forms on the same page.
     *               Special attribute 'data-double-submit-protection' set to 'off' will turn off
     *               double-submit protection JavaScript - this may be necessary if your form sends
     *               downloadable files in response to a submit button, and can't call
     *               \core_form\util::form_download_complete();
     * @param bool $editable
     * @param array $ajaxformdata Forms submitted via ajax, must pass their data here, instead of relying on _GET and _POST.
     */
    public function build_editform(
        $action = null,
        $customdata = null,
        $method = 'post',
        $target = '',
        $attributes = null,
        $editable = true,
        $ajaxformdata = null
    ) {
        $this->conditionform = new grade_in_activity_form(
            $action,
            $customdata,
            $method,
            $target,
            $attributes,
            $editable,
            $ajaxformdata
        );
    }

    /**
     * Saves the condition after it has been edited (or created)
     * @param object $formdata
     */
    public function save_condition($formdata) {
        global $DB;

        $gradeitems = json_decode($formdata->gradeitems, true);

        $gradeitemsconditions = $this->get_grade_items_conditions($gradeitems);

        $params = [
            'cmid' => clean_param($formdata->coursemodule, PARAM_INT),
            'gradeitemsconditions' => $gradeitemsconditions,
        ];

        $condition = new stdClass();
        $condition->ruleid = $formdata->ruleid;
        $condition->conditiontype = $this->type;
        $condition->params = json_encode($params);

        $this->set_data($condition, $this->courseid);

        $DB->insert_record('cdr_condition', $condition);
    }

    /**
     * Gets the grade items conditions
     *
     * @param array $gradeitems The grade items
     * @return array The grade items conditions
     */
    private function get_grade_items_conditions($gradeitems) {
        $gradeitemsconditions = [];

        foreach ($gradeitems as $gradeitemid => $conditions) {
            $cleanid = clean_param($gradeitemid, PARAM_INT);

            $gradeitemsconditions[$cleanid] = $this->clean_grade_item_conditions($conditions, ['gradegte', 'gradelt']);
        }

        return $gradeitemsconditions;
    }

    /**
     * Cleans the grade item conditions
     *
     * @param array $conditions The conditions to clean
     * @param array $conditionkeys The keys of the conditions to clean
     * @return array The cleaned conditions
     */
    private function clean_grade_item_conditions($conditions, $conditionkeys) {
        $cleanconditions = [];
        foreach ($conditionkeys as $key) {
            if (!isset($conditions[$key])) {
                continue;
            }

            $rawvalue = $conditions[$key]['value'] ?? '';
            $rawdisabled = !empty($conditions[$key]['disabled']);
            $value = clean_param($rawvalue, PARAM_FLOAT);

            if (empty($value) || $rawdisabled) {
                continue;
            }
            $cleanconditions[$key] = [
                'value' => $value,
            ];
        }
        return $cleanconditions;
    }

    /**
     * Returns config data to prefill the edit form.
     *
     * @return array
     */
    public function get_configdata(): array {
        $configdata = [];
        if (isset($this->params->cmid)) {
            $configdata['coursemodule'] = (int)$this->params->cmid;
        }
        return $configdata;
    }
}

