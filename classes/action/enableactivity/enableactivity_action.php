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

namespace local_coursedynamicrules\action\enableactivity;

use core_availability\tree;
use local_coursedynamicrules\core\action;
use local_coursedynamicrules\form\actions\enableactivity_form;
use stdClass;

/**
 * Class enableactivity_action
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enableactivity_action extends action {
    /** @var string type of the action */
    protected $type = 'enableactivity';

    /**
     * Execute the action
     * @param object $context Context of the rule
     */
    public function execute($context) {
        global $DB;
        $userid = $context->userid;
        $cmids = $this->params->coursemodules;

        foreach ($cmids as $cmid) {
            $cm = $DB->get_record('course_modules', ['id' => $cmid]);
            $availability = json_decode($cm->availability);
            $availability->c[0]->userids[] = $userid;

            $DB->set_field(
                'course_modules',
                'availability',
                json_encode($availability),
                [
                    'id' => $cmid,
                ]
            );

        }

        rebuild_course_cache($this->courseid, true);
    }

    /**
     * Creates and returns an instance of the form for editing the action
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
     *               \core_form\util::form_download_complete(){
     * }
     * @param bool $editable
     * @param array $ajaxformdata Forms submitted via ajax, must pass their data here, instead of relying on _GET and _POST.
     */
    public function build_editform(
        $action=null,
        $customdata=null,
        $method='post',
        $target='',
        $attributes=null,
        $editable=true,
        $ajaxformdata=null
    ) {
        $this->actionform = new enableactivity_form(
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
     * Saves the action after it has been edited (or created)
     * @param object $formdata
     */
    public function save_action($formdata) {
        global $DB;

        $coursemodules = $formdata->coursemodules;

        $params = [
            'coursemodules' => $coursemodules,
        ];

        $action = new stdClass();
        $action->ruleid = $formdata->ruleid;
        $action->actiontype = $this->type;
        $action->params = json_encode($params);

        $this->set_data($action);

        $DB->insert_record('cdr_action', $action);

        foreach ($coursemodules as $cmid) {
            $availabilityoptions = (object)[
                'type' => 'user',
                'userids' => [],
            ];
            $availability = tree::get_root_json(
                [$availabilityoptions],
                tree::OP_AND,
                false
            );

            $availability = json_encode($availability);
            $DB->set_field(
                'course_modules',
                'availability',
                $availability,
                [
                    'id' => $cmid,
                ]
            );
        }

        rebuild_course_cache($formdata->courseid, true);
    }

    /**
     * Returns the description of the action to visualization
     *
     * @return string
     */
    public function get_description() {
        $cmids = $this->params->coursemodules;
        $descriptionarray = [];
        $modinfo = get_fast_modinfo($this->courseid);

        foreach ($cmids as $cmid) {
            $cms = $modinfo->get_cms();
            $cminfo = $cms[$cmid];
            $descriptionarray[] = ucfirst($cminfo->modname) . " - " . $cminfo->name;
        }
        return get_string(
            'enableactivity_description',
            'local_coursedynamicrules',
            implode(', ', $descriptionarray)
        );
    }
}
