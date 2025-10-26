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
 * Smart Rules AI - Conditions
 *
 * Mirrors core_reportbuilder/audience.js structure for adding/editing/removing condition cards
 * via fragments and DynamicForm, adapted to condition-* selectors and local component.
 *
 * @module      local_coursedynamicrules/conditions
 */

import 'core/inplace_editable';
import Templates from 'core/templates';
import Notification from 'core/notification';
import Pending from 'core/pending';
import {prefetchStrings} from 'core/prefetch';
import {getString} from 'core/str';
import DynamicForm from 'core_form/dynamicform';
import {add as addToast} from 'core/toast';
import {loadFragment} from 'core/fragment';
import {markFormAsDirty} from 'core_form/changechecker';
import SELECTORS from 'local_coursedynamicrules/local/selectors';

let courseId = 0;
let ruleId = 0;
let contextId = 0;


/**
 * Add condition card via fragment
 * @param {String} className Fully-qualified condition class name
 * @param {String} title Display title
 */
const addConditionCard = (className, title) => {
    const pending = new Pending('local_coursedynamicrules/conditions:add');

    const container = document.querySelector(SELECTORS.regions.conditionsContainer);
    const cardsCount = container.querySelectorAll(SELECTORS.regions.conditionCard).length;

    const params = {
        classname: className,
        ruleid: ruleId,
        courseid: courseId,
        showormessage: (cardsCount > 0),
        title: title,
    };

    // Load condition card fragment, render and then initialise the form within.
    loadFragment('local_coursedynamicrules', 'condition_form', contextId, params)
        .then((html, js) => {
            const conditionCard = Templates.appendNodeContents(container, html, js)[0];
            const emptyMessage = container.querySelector(SELECTORS.regions.conditionEmptyMessage);

            const conditionForm = initConditionCardForm(conditionCard);
            // Mark as dirty new condition form created to prevent users leaving the page without saving it.
            markFormAsDirty(conditionForm.getFormNode());
            if (emptyMessage) {
                emptyMessage.classList.add('hidden');
            }

            return getString('conditionadded', 'local_coursedynamicrules', title);
        })
        .then(addToast)
        .then(() => pending.resolve())
        .catch(Notification.exception);
};


/**
 * Edit condition card
 *
 * @param {Element} conditionCard
 */
const editConditionCard = conditionCard => {
    const pending = new Pending('local_coursedynamicrules/conditions:edit');

    // Load condition form with data for editing, then toggle visible controls in the card.
    const conditionForm = initConditionCardForm(conditionCard);
    conditionForm.load({id: conditionCard.dataset.conditionId})
        .then(() => {
            const formContainer = conditionCard.querySelector(SELECTORS.regions.conditionFormContainer);
            const description = conditionCard.querySelector(SELECTORS.regions.conditionDescription);
            const editBtn = conditionCard.querySelector(SELECTORS.actions.conditionEdit);

            formContainer.classList.remove('hidden');
            if (description) {
                description.classList.add('hidden');
            }
            if (editBtn) {
                editBtn.disabled = true;
            }

            return pending.resolve();
        })
        .catch(Notification.exception);
};


/**
 * Initialise dynamic form within given condition card
 *
 * @param {Element} conditionCard
 * @return {DynamicForm}
 */
const initConditionCardForm = conditionCard => {
    const formContainer = conditionCard.querySelector(SELECTORS.regions.conditionFormContainer);
    const form = new DynamicForm(formContainer, '\\local_coursedynamicrules\\form\\conditions\\condition_form');

    // After submitting the form, update the card heading and description.
    form.addEventListener(form.events.FORM_SUBMITTED, data => {
        const heading = conditionCard.querySelector(SELECTORS.regions.conditionHeading);
        const description = conditionCard.querySelector(SELECTORS.regions.conditionDescription);

        // Ensure subsequent edits use the newly created/updated instance id.
        if (data.detail.instanceid) {
            conditionCard.dataset.conditionId = data.detail.instanceid;
        }

        if (data.detail.heading) {
            heading.innerHTML = data.detail.heading;
        }
        if (data.detail.description) {
            description.innerHTML = data.detail.description;
        }

        closeConditionCardForm(conditionCard);

        return getString('changessaved', 'moodle')
            .then(addToast);
    });

    // If cancelling the form, close the card or remove it if it was never created.
    form.addEventListener(form.events.FORM_CANCELLED, () => {
        if (conditionCard.dataset.conditionId > 0) {
            closeConditionCardForm(conditionCard);
        } else {
            removeConditionCard(conditionCard);
        }
    });

    return form;
};

/**
 * Close condition card form
 *
 * @param {Element} conditionCard
 */
const closeConditionCardForm = conditionCard => {
    // Remove the [data-region="condition-form-container"] and create it again to drop listeners.
    const form = conditionCard.querySelector(SELECTORS.regions.conditionFormContainer);
    const newForm = form.cloneNode(false);
    conditionCard.querySelector(SELECTORS.regions.conditionForm).replaceChild(newForm, form);
    // Show description and enable action buttons.
    const description = conditionCard.querySelector(SELECTORS.regions.conditionDescription);
    const editBtn = conditionCard.querySelector(SELECTORS.actions.conditionEdit);
    const deleteBtn = conditionCard.querySelector(SELECTORS.actions.conditionDelete);
    if (description) {
        description.classList.remove('hidden');
    }
    if (editBtn) {
        editBtn.disabled = false;
    }
    if (deleteBtn) {
        deleteBtn.disabled = false;
    }
};

/**
 * Remove condition card
 *
 * @param {Element} conditionCard
 */
const removeConditionCard = conditionCard => {
    conditionCard.remove();

    const container = document.querySelector(SELECTORS.regions.conditionsContainer);
    const cards = container.querySelectorAll(SELECTORS.regions.conditionCard);

    if (cards.length === 0) {
        const emptyMessage = document.querySelector(SELECTORS.regions.conditionEmptyMessage);
        if (emptyMessage) {
            emptyMessage.classList.remove('hidden');
        }
    }
};

let initialized = false;

/**
 * Initialise conditions tab
 *
 * @param {Object} args
 * @param {Number} args.ruleid Rule id
 * @param {Number} args.courseid Course id
 * @param {Number} args.contextid Course context id
 */
export const init = (args) => {
    prefetchStrings('local_coursedynamicrules', [
        'conditionadded',
        'deletecondition',
        'deleteconditioncheck',
        'deletedcondition'
    ]);

    prefetchStrings('core', [
        'delete'
    ]);

    ruleId = args.ruleid;
    courseId = args.courseid;
    contextId = args.contextid;

    if (initialized) {
        return;
    }

    document.addEventListener('click', event => {

        // Add instance.
        const conditionAdd = event.target.closest(SELECTORS.actions.conditionAdd);
        if (conditionAdd) {
            event.preventDefault();
            addConditionCard(conditionAdd.dataset.uniqueIdentifier, conditionAdd.dataset.name);
        }

        // Edit instance.
        const conditionEdit = event.target.closest(SELECTORS.actions.conditionEdit);
        if (conditionEdit) {
            const conditionCard = conditionEdit.closest(SELECTORS.regions.conditionCard);

            event.preventDefault();
            editConditionCard(conditionCard);
        }
    });

    initialized = true;
};
