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
 * @copyright  2025 Wilber Narvaez <https://datacurso.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import {loadFragment} from "core/fragment";
import Notification from "core/notification";
import Templates from "core/templates";

export const init = (contextId, courseId) => {
  const form = document.querySelector(
    '[data-region="local_coursedynamicrules/grade_in_activity_form"]'
  );
  const select = document.querySelector(
    '[data-action="local_coursedynamicrules/change_coursemodule"]'
  );
  if (!select || !form) {
    return;
  }

  form.addEventListener("submit", () => {
    const inputs = form.querySelectorAll(
      '[data-name="local_coursedynamicrules/grede_in_activity_input"]'
    );

    const gradeItemsObject = {};
    inputs.forEach((input) => {
      const condition = input.dataset.condition; // 'gradegte' | 'gradelt'
      const gradeItem = input.dataset.gradeitem; // Grade item id
      const value = input.value;
      const disabled = input.disabled === true;

      if (!gradeItemsObject[gradeItem]) {
        gradeItemsObject[gradeItem] = {};
      }
      gradeItemsObject[gradeItem][condition] = {
        value: value,
        disabled: disabled,
      };
    });

    const gradeitemsField = form.querySelector('input[name="gradeitems"]');
    if (gradeitemsField) {
      gradeitemsField.value = JSON.stringify(gradeItemsObject);
    }
  });

  select.addEventListener("change", () =>
    loadGradeItemsForm(contextId, courseId, select)
  );
  loadGradeItemsForm(contextId, courseId, select);
};

const loadGradeItemsForm = (contextId, courseId, select) => {
  const container = document.querySelector(
    '[data-region="local_coursedynamicrules/gradeitems_form"]'
  );
  const value = select.value;
  loadFragment(
    "local_coursedynamicrules",
    "grade_in_activity_form",
    contextId,
    {coursemodule: value, courseid: courseId}
  )
    .then((html, js) => {
      return Templates.replaceNodeContents(container, html, js);
    })
    .fail(Notification.exception);
};
