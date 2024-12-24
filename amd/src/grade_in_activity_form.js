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

            // Handle gradeitems dynamics conditions.
            document.querySelector('[name=gradeitems]').value = JSON.stringify({});

            const cmId = document.querySelector('[name=coursemodule]').value;

            document.querySelector('[name=cmid]').value = cmId;
            const cmConditionInputs = document.querySelectorAll(`[data-cmid='${cmId}']`);

            cmConditionInputs.forEach((input) => {
                const gradeItems = document.querySelector('[name=gradeitems]').value;
                const gradeItemsObject = JSON.parse(gradeItems);

                const condition = input.dataset.condition;
                const gradeItem = input.dataset.gradeitem;
                const value = input.value;
                const gradeItemKey = `${condition}_${gradeItem}`;
                gradeItemsObject[gradeItemKey] = {
                    gradeitem: gradeItem,
                    condition: condition,
                    value: value,
                };

                document.querySelector('[name=gradeitems]').value = JSON.stringify(gradeItemsObject);

                input.addEventListener('change', (e) => {
                    const gradeItems = document.querySelector('[name=gradeitems]').value;
                    const gradeItemsObject = JSON.parse(gradeItems);

                    const condition = e.target.dataset.condition;
                    const gradeItem = e.target.dataset.gradeitem;
                    const value = e.target.value;
                    const gradeItemKey = `${condition}_${gradeItem}`;

                    gradeItemsObject[gradeItemKey] = {
                        gradeitem: gradeItem,
                        condition: condition,
                        value: value,
                    };

                    document.querySelector('[name=gradeitems]').value = JSON.stringify(gradeItemsObject);
                });
            });

            // Handle inputs validation and submission.
            const gradeInActivityForm = document.getElementById('grade_in_activity_form');
            const dynamicGradeInActivityForm = document.getElementById('dynamic_grade_in_activity_form');
            gradeInActivityForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const cmId = document.querySelector('[name=coursemodule]').value;
                const cmConditionInputs = document.querySelectorAll(`[data-cmid='${cmId}']`);

                cmConditionInputs.forEach((input) => {
                    // eslint-disable-next-line no-console
                    console.log(input.value);

                    if (input.value > 4) {
                        const id = input.id;
                        const invalidFeedback = document.querySelector(`.invalid-feedback#${id}`);
                        invalidFeedback.textContent = 'The grade must be between 0 and 4.';
                        input.setCustomValidity('The grade must be between 0 and 4.');
                    }
                });

                dynamicGradeInActivityForm.classList.add('was-validated');

                if (dynamicGradeInActivityForm.checkValidity()) {
                    gradeInActivityForm.submit();
                    return;
                }
            });

            return loadPromise.resolve();
        })
        .catch(Notification.exception);
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

        document.querySelector('[name=cmid]').value = courseModuleValue;

        document.querySelector('[name=gradeitems]').value = JSON.stringify({});

        const cmId = document.querySelector('[name=coursemodule]').value;
        const cmConditionInputs = document.querySelectorAll(`[data-cmid='${cmId}']`);

        cmConditionInputs.forEach((input) => {
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

            input.addEventListener('change', (e) => {
                const gradeItems = document.querySelector('[name=gradeitems]').value;
                const gradeItemsObject = JSON.parse(gradeItems);

                const condition = e.target.dataset.condition;
                const gradeItem = e.target.dataset.gradeitem;
                const value = e.target.value;
                const gradeItemKey = `${condition}_${gradeItem}`;

                const disabled = e.target.getAttribute('disabled') === 'disabled';

                gradeItemsObject[gradeItemKey] = {
                    gradeitem: gradeItem,
                    condition: condition,
                    value: value,
                    disabled: disabled,
                };

                document.querySelector('[name=gradeitems]').value = JSON.stringify(gradeItemsObject);
            });
        });
        return updatePromise.resolve();
    })
    .catch(Notification.exception);
}

