<?php
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

namespace local_coursedynamicrules;

defined('MOODLE_INTERNAL') || die();

/**
 * API client for coursedynamicrules plugin
 * @package local_coursedynamicrules
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class api_client {
    /** @var string Base URL for API requests */
    private $baseurl = 'http://laravel/api/plugins/coursedynamicrules';

    /** @var array Default payload for all requests */
    private $defaultpayload;

    /**
     * Constructor
     */
    public function __construct() {
        global $CFG;
        $this->defaultpayload = [
            'licenseKey' => get_config('local_coursedynamicrules', 'licencekey'),
            'domain' => $CFG->wwwroot,
            'dirPath' => $CFG->dirroot . '/local/coursedynamicrules',
        ];
    }

    /**
     * Make a POST request to the API
     *
     * @param string $endpoint The API endpoint
     * @param array $payload Additional payload to merge with default payload
     * @return object|null Response data or null on failure
     */
    public function post($endpoint, array $payload = []) {
        $payload = array_merge($this->defaultpayload, $payload);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseurl . '/' . ltrim($endpoint, '/'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return null;
        }

        return json_decode($response, true);
    }
}
