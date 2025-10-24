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
 * Rules list management
 *
 * @module      local_coursedynamicrules/rules_list
 * @copyright   2024 Industria Elearning
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

"use strict";

import {prefetchStrings} from 'core/prefetch';
import {getString} from 'core/str';
import {dispatchEvent} from 'core/event_dispatcher';
import Notification from 'core/notification';
import {add as addToast} from 'core/toast';
import * as reportEvents from 'core_reportbuilder/local/events';
import * as reportSelectors from 'core_reportbuilder/local/selectors';
import * as selectors from 'local_coursedynamicrules/local/selectors';
import {createRuleModal} from 'local_coursedynamicrules/local/repository/modals';

/**
 * Initialise module
 */
export const init = () => {
    prefetchStrings('local_coursedynamicrules', [
        'createrule',
        'ruleadd',
        'ruleupdatedsuccessfully',
    ]);

    document.addEventListener('click', event => {
        // Create rule.
        const ruleCreate = event.target.closest(selectors.actions.ruleCreate);
        if (ruleCreate) {
            event.preventDefault();

            const courseId = parseInt(ruleCreate.getAttribute('data-course-id'), 10) || 0;
            const modal = createRuleModal(ruleCreate, getString('createrule', 'local_coursedynamicrules'), courseId);

            modal.addEventListener(modal.events.FORM_SUBMITTED, e => {
                // Server returns URL to redirect after creation/update.
                if (e && e.detail) {
                    window.location.href = e.detail;
                }
            });

            modal.show();
            return;
        }

        // Edit rule (pattern aligned with core reportbuilder).
        const ruleEdit = event.target.closest(selectors.actions.ruleEdit);
        if (ruleEdit) {
            event.preventDefault();

            const courseId = parseInt(ruleEdit.getAttribute('data-course-id'), 10) || 0;
            const ruleId = parseInt(ruleEdit.getAttribute('data-rule-id'), 10) || 0;

            // Use triggerElement as the action menu toggle to preserve focus after modal closes.
            const triggerElement = ruleEdit.closest('.dropdown')?.querySelector('.dropdown-toggle') || ruleEdit;
            const modal = createRuleModal(triggerElement, getString('editrule', 'local_coursedynamicrules'), courseId, ruleId);

            modal.addEventListener(modal.events.FORM_SUBMITTED, () => {
                const reportElement = event.target.closest(reportSelectors.regions.report);
                getString('ruleupdatedsuccessfully', 'local_coursedynamicrules')
                    .then(addToast)
                    .then(() => {
                        dispatchEvent(reportEvents.tableReload, {preservePagination: true}, reportElement);
                        return;
                    })
                    .catch(Notification.exception);
            });

            modal.show();
            return;
        }
    });
};
