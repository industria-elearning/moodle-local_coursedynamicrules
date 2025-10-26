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
 * Selectors for the coursedynamicrules subsystem
 *
 * @module      local_coursedynamicrules/local/selectors
 */

const SELECTORS = {
    regions: {
        conditionsContainer: '[data-region="local_coursedynamicrules/conditions"]',
        conditionCard: '[data-region="local_coursedynamicrules/condition-card"]',
        conditionHeading: '[data-region="local_coursedynamicrules/condition-heading"]',
        conditionForm: '[data-region="local_coursedynamicrules/condition-form"]',
        conditionFormContainer: '[data-region="local_coursedynamicrules/condition-form-container"]',
        conditionDescription: '[data-region="local_coursedynamicrules/condition-description"]',
        conditionEmptyMessage: '[data-region="local_coursedynamicrules/no-instances-message"]',
        sidebarItem: '[data-region="local_coursedynamicrules/sidebar-item"]'
    },
    actions: {
        ruleCreate: '[data-action="local_coursedynamicrules/rule-create"]',
        ruleEdit: '[data-action="local_coursedynamicrules/rule-edit"]',
        conditionAdd: '[data-action="local_coursedynamicrules/addcondition"]',
        conditionEdit: '[data-action="local_coursedynamicrules/edit-condition"]',
        conditionDelete: '[data-action="local_coursedynamicrules/delete-condition"]'
    },
};

export default SELECTORS;
