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
 * TODO describe module grade_in_activity_form
 *
 * @module     local_coursedynamicrules/grade_in_activity_form
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import DynamicForm from 'core_form/dynamicform';
import Notification from 'core/notification';
import Pending from 'core/pending';
import {get_string as getString} from "core/str";


/**
 * Initializes the dynamic form handling.
 */
export function init() {
    const formContainer = document.querySelector('[data-region=dynamicform]');
    const dynamicForm = createDynamicForm(formContainer);
    handleLoadForm(dynamicForm);
}

/**
 * Creates a new dynamic form instance.
 *
 * @param {HTMLElement} container The container element for the form.
 * @return {DynamicForm} The dynamic form instance.
 */
function createDynamicForm(container) {
    const dynamicForm = new DynamicForm(
        container,
        'local_coursedynamicrules\\form\\conditions\\dynamic_grade_in_activity_form'
    );

    return dynamicForm;
}

/**
 * Handles the form loading process.
 *
 * @param {DynamicForm} dynamicForm The dynamic form instance.
 */
function handleLoadForm(dynamicForm) {
    const loadPromise = new Pending(' local_coursedynamicrules/grade_in_activity_form:load');
    const courseId = document.querySelector('[name=courseid]').value;
    dynamicForm.load({courseid: courseId})
        .then(() => {
            attachCourseModuleChangeListener(dynamicForm);
            resetGradeItems();
            updateGradeItems();
            handleSubmitForm();
            return loadPromise.resolve();
        })
        .catch(Notification.exception);
}

/**
 * Handles form submission.
 */
function handleSubmitForm() {
    const gradeInActivityForm = document.getElementById('grade_in_activity_form');
    const dynamicGradeInActivityForm = document.getElementById('dynamic_grade_in_activity_form');
    gradeInActivityForm.addEventListener('submit', (e) => {
        // Check if the submit button was clicked.
        // this is to prevent the form from submitting when the user clicks the cancel button.
        if (e.submitter.name === 'submitbutton') {
            e.preventDefault();
            const formIsValid = formValidation();
            if (e.submitter.name === 'submitbutton' && dynamicGradeInActivityForm.checkValidity() && formIsValid) {
                gradeInActivityForm.submit();
            }
        }
    });
}

/**
 * Validates the form by checking if the input values are within the specified grade range.
 *
 * This function retrieves the course module ID from the form, selects all inputs associated with that ID,
 * and checks if their values fall within the specified minimum and maximum grade range. If an input value
 * is out of range, it marks the input as invalid and displays an error message.
 *
 * @returns {boolean} - Returns true if the form is valid, otherwise false.
 */
function formValidation() {
    const cmId = document.querySelector('[name=coursemodule]').value;
    const cmConditionInputs = document.querySelectorAll(`[data-cmid='${cmId}']`);

    let formIsValid = true;
    cmConditionInputs.forEach((input) => {
        const gradeMin = parseFloat(input.dataset.grademin);
        const gradeMax = parseFloat(input.dataset.grademax);
        const inputValue = parseFloat(input.value);
        let invalidFeedback = input.nextElementSibling;

        if (input.disabled) {
            return;
        }
        if (!invalidFeedback || !invalidFeedback.classList.contains('invalid-feedback')) {
            invalidFeedback = document.createElement('span');
            invalidFeedback.className = 'invalid-feedback';
            input.after(invalidFeedback);
        }

        if (inputValue > gradeMax || inputValue < gradeMin) {
            input.classList.add('is-invalid');
            formIsValid = false;
            getString('errorgradeoutofrange', 'local_coursedynamicrules', {
                min: gradeMin,
                max: gradeMax,
            }).then(function(content) {
                invalidFeedback.textContent = content;
                return;
            }).catch(function() {
                Notification.exception(new Error('Failed to load string: errorgradeoutofrange'));
            });
        } else {
            input.classList.remove('is-invalid');
            invalidFeedback.textContent = '';
        }
    });

    return formIsValid;
}

/**
 * Attaches a change event listener to the course module select element.
 *
 * @param {DynamicForm} dynamicForm The dynamic form instance.
 */
function attachCourseModuleChangeListener(dynamicForm) {
    const courseModuleSelect = document.querySelector('[name=coursemodule]');

    if (!courseModuleSelect) {
        Notification.addNotification({
            message: 'Course module select element not found.',
            type: 'warning',
        });
        return;
    }

    courseModuleSelect.addEventListener('change', (e) => {
        handleCourseModuleChange(dynamicForm, e.target.value);
    });
}

/**
 * Handles the course module change event.
 *
 * @param {DynamicForm} dynamicForm The dynamic form instance.
 * @param {string} courseModuleValue The selected course module value.
 */
function handleCourseModuleChange(dynamicForm, courseModuleValue) {
    const updatePromise = new Pending('local_coursedynamicrules/grade_in_activity_form:update');

    const courseId = document.querySelector('[name=courseid]').value;
    dynamicForm.load({coursemodule: courseModuleValue, courseid: courseId})
        .then(() => {
            attachCourseModuleChangeListener(dynamicForm);
            resetGradeItems();
            updateGradeItems(dynamicForm);
            handleSubmitForm();
            return updatePromise.resolve();
        })
        .catch(Notification.exception);
}

/**
 * Resets the grade items field.
 */
function resetGradeItems() {
    document.querySelector('[name=gradeitems]').value = JSON.stringify({});
}

/**
 * Updates the grade items based on the current form state.
 *
 */
function updateGradeItems() {
    const cmId = document.querySelector('[name=coursemodule]').value;
    document.querySelector('[name=cmid]').value = cmId;
    const cmConditionInputs = document.querySelectorAll(`[data-cmid='${cmId}']`);

    cmConditionInputs.forEach((input) => {
        updateGradeItem(input);

        input.addEventListener('change', (e) => {
            updateGradeItem(e.target);
        });
    });
}

/**
 * Updates a single grade item based on the input element.
 *
 * @param {HTMLElement} input The input element.
 */
function updateGradeItem(input) {
    const gradeItems = document.querySelector('[name=gradeitems]').value;
    const gradeItemsObject = JSON.parse(gradeItems);

    const condition = input.dataset.condition;
    const gradeItem = input.dataset.gradeitem;
    const value = input.value;
    const disabled = input.getAttribute('disabled') === 'disabled';
    const gradeItemKey = `${condition}_${gradeItem}`;

    gradeItemsObject[gradeItemKey] = {
        gradeitem: gradeItem,
        condition: condition,
        value: value,
        disabled: disabled,
    };

    document.querySelector('[name=gradeitems]').value = JSON.stringify(gradeItemsObject);
}

