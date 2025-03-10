<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_coursedynamicrules
 * @category    admin
 * @copyright   2024 Industria Elearning <info@industriaelearning.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $pluginname = 'local_coursedynamicrules';

    // Add plugin settings category link.
    $plugincategory = new admin_category($pluginname, get_string('pluginname', $pluginname));

    // Add plugin settings category link to the local plugins category.
    $ADMIN->add('localplugins', $plugincategory);

    // Add plugin settings page.
    $settings = new admin_settingpage("{$pluginname}_settings", get_string('generalsettings', $pluginname));
    $ADMIN->add($pluginname, $settings);

    $licensekey = 'licencekey';
    $licensekeystring = get_string('licensekey', $pluginname);
    $licensekeydescstring = get_string('licensekey_desc', $pluginname);

    // Check if this moodle instance is an iomad.
    if ($DB->record_exists('config_plugins', ['plugin' => 'local_iomad', 'name' => 'version'])) {
        $companyid = iomad::get_my_companyid(context_system::instance());
        $company = new company($companyid);
        $companyname = $company->get_name();

        $licensekey = "licensekey_{$companyid}";
        $licensekeystring = get_string('licensekeycompany', $pluginname, $companyname);
        $licensekeydescstring = get_string('licensekeycompany_desc', $pluginname, $companyname);
    }

    // Add license key setting.
    $settings->add(new admin_setting_configtext(
        "local_coursedynamicrules/{$licensekey}",
        $licensekeystring,
        $licensekeydescstring,
        '',
    ));

    $url = new moodle_url('/local/coursedynamicrules/checklicensekey.php', []);
    $ADMIN->add(
        $pluginname,
        new admin_externalpage(
            "{$pluginname}_checklicensekey",
            get_string('checklicensekey', $pluginname),
            $url,
        )
    );

}
