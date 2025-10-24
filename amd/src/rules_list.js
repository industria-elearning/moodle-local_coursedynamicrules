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
import * as selectors from 'local_coursedynamicrules/local/selectors';
import {createRuleModal} from 'local_coursedynamicrules/local/repository/modals';

/**
 * Initialise module
 */
export const init = () => {
    prefetchStrings('local_coursedynamicrules', [
        'createrule',
        'ruleadd',
    ]);

    document.addEventListener('click', event => {
        const trigger = event.target.closest(selectors.actions.ruleCreate);
        if (!trigger) {
            return;
        }
        event.preventDefault();

        const courseId = parseInt(trigger.getAttribute('data-course-id'), 10) || 0;

        const modal = createRuleModal(trigger, getString('createrule', 'local_coursedynamicrules'), courseId);

        modal.addEventListener(modal.events.FORM_SUBMITTED, e => {
            // Server returns URL to redirect after creation/update.
            if (e && e.detail) {
                window.location.href = e.detail;
            }
        });

        modal.show();
    });
};
