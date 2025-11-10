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
import { loadFragment } from "core/fragment";
import Notification from "core/notification";
import Templates from "core/templates";

export const init = (contextId, courseId) => {
  const form = document.querySelector(
    '[data-region="local_coursedynamicrules/grade_in_activity_form"]'
  );
  const select = form.querySelector(
    '[data-action="local_coursedynamicrules/change_coursemodule"]'
  );
  if (!select || !form) {
    return;
  }

  select.addEventListener("change", (e) => {
    const formParent = form.closest('[data-region="local_coursedynamicrules/condition_form_container"]');
    const ruleId = form.querySelector('[name="ruleid"]').value;

    const params = {
      classname:
        "\\local_coursedynamicrules\\condition\\grade_in_activity\\grade_in_activity_condition",
      ruleid: parseInt(ruleId),
      courseid: courseId,
      showormessage: true,
      coursemodule: e.target.value,
    };
    // Load condition card fragment, render and then initialise the form within.
    loadFragment(
      "local_coursedynamicrules",
      "condition_form",
      contextId,
      params
    )
      .then((html, js) => {
        return Templates.replaceNodeContents(formParent, html, js);
      })
      .catch(Notification.exception);
  });
};
