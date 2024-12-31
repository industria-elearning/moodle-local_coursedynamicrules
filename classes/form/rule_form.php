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

namespace local_coursedynamicrules\form;

/**
 * Class rule_form
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule_form extends \moodleform {
    /**
     * Defines the form.
     */
    public function definition() {
        $mform = $this->_form;
        $customdata = $this->_customdata;

        $courseid = $customdata['courseid'];
        $rule = $customdata['rule'];

        $mform->addElement('text', 'name', get_string('name', 'local_coursedynamicrules'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->setDefault('name', $rule->name);

        $mform->addElement('textarea', 'description', get_string('description', 'local_coursedynamicrules'));
        $mform->setType('description', PARAM_RAW);
        $mform->setDefault('description', $rule->description);

        $mform->addElement('checkbox', 'active', get_string('ruleactive', 'local_coursedynamicrules'));
        $mform->setDefault('active', $rule->active ?? 0);
        $mform->addHelpButton('active', 'ruleactive', 'local_coursedynamicrules');

        $mform->addElement('checkbox', 'hasfrequencylimit', get_string('hasfrequencylimit', 'local_coursedynamicrules'));

        $frequencygroup = [];
        $frequencygroup[] = $mform->createElement('static', 'freqtextbefore', '', get_string('freqtextbefore', 'local_coursedynamicrules'));
        $frequencygroup[] = $mform->createElement('text', 'frequencylimit', '', ['size' => 5]);
        $frequencygroup[] = $mform->createElement('static', 'freqtextafter', '', ' times ');
        $frequencygroup[] = $mform->createElement('select', 'frequencytype', '', [
            'total' => get_string('total', 'local_coursedynamicrules'),
            'per' => get_string('per', 'local_coursedynamicrules'),
        ]);

        $mform->addGroup($frequencygroup, 'frequency_group', '', ' ', false);

        $periodgroup = [];
        $periodgroup[] = $mform->createElement('text', 'periodvalue', '', ['size' => 5]);
        $periodgroup[] = $mform->createElement('select', 'periodunit', '', [
            'minutes' => 'minutes',
            'hours' => 'hours',
            'days' => 'days',
            'weeks' => 'weeks',
        ]);

        $mform->addGroup($periodgroup, 'period_group', '', ' ', false);

        $mform->addHelpButton('hasfrequencylimit', 'hasfrequencylimit', 'local_coursedynamicrules');

        $mform->setType('frequencylimit', PARAM_INT);
        $mform->setType('periodvalue', PARAM_INT);

        $mform->hideIf('frequency_group', 'hasfrequencylimit', 'notchecked');
        $mform->hideIf('period_group', 'frequencytype', 'eq', 'total');
        $mform->hideIf('period_group', 'hasfrequencylimit', 'notchecked');

        $mform->addElement('hidden', 'courseid', $courseid);
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'id', $rule->id);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}
