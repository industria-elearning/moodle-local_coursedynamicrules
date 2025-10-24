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

/**
 * Module to handle modal form requests for coursedynamicrules
 *
 * @module      local_coursedynamicrules/local/repository/modals
 */

import ModalForm from 'core_form/modalform';
import {getString} from 'core/str';

/**
 * Return modal instance
 *
 * @param {EventTarget} triggerElement
 * @param {Promise} modalTitle
 * @param {String} formClass
 * @param {Object} formArgs
 * @return {ModalForm}
 */
const createModalForm = (triggerElement, modalTitle, formClass, formArgs) => {
    return new ModalForm({
        modalConfig: {
            title: modalTitle,
        },
        formClass: formClass,
        args: formArgs,
        saveButtonText: getString('save', 'moodle'),
        returnFocus: triggerElement,
    });
};

/**
 * Return rule modal instance
 *
 * @param {EventTarget} triggerElement
 * @param {Promise} modalTitle
 * @param {Number} courseId
 * @param {Number} ruleId
 * @return {ModalForm}
 */
export const createRuleModal = (triggerElement, modalTitle, courseId, ruleId = 0) => {
    return createModalForm(triggerElement, modalTitle, 'local_coursedynamicrules\\form\\rule_form', {
        id: ruleId,
        courseid: courseId,
    });
};
