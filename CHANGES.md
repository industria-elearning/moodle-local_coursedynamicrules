## 1.6.3

**Released on:** 2026-04-29

**Compatibility note:** This version is compatible with **Moodle 4.5**.

## Fixed
- **Database schema check reports legacy CDR tables**
  Added an upgrade cleanup step that removes obsolete `cdr_rule`, `cdr_condition`, and `cdr_action` tables when they remain in existing sites.

## Added
- **Automated coverage for legacy table cleanup**
  Added PHPUnit coverage to validate that the upgrade helper drops legacy CDR tables safely.

## 1.6.2

**Released on:** 2026-04-24

**Compatibility note:** This version is compatible with **Moodle 4.5**.

## Changed
- **Notification audience model clarified**
  The send notification action now distinguishes between **primary recipients** and **copy recipients** so the target user and observer users receive the correct message format.
- **Configuration UI improved**
  Notification targeting now uses explicit recipient groups with clearer help text and student selected by default as a primary recipient.

## Fixed
- **Wrong user placeholders in notifications**
  Fixed cases where notification placeholders could be rendered with incorrect user data when different role combinations were selected.
- **No-course-access delivery semantics**
  Notifications are now sent only when the matched user belongs to primary recipient roles, while copy recipients receive an observation message.

## Added
- **Upgrade migration for legacy role params**
  Added upgrade logic to migrate legacy `roleids` (and interim keys) into `primaryroleids` and `copyroleids` without data loss.
- **Automated coverage for migration and behavior**
  Added PHPUnit migration tests and Behat end-to-end scenarios covering no-course-access plus send-notification combinations with exact user-visible assertions.
