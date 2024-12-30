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

namespace local_coursedynamicrules\core;
use local_coursedynamicrules\helper\rule_component_loader;
use stdClass;

/**
 * Class rule
 *
 * @package    local_coursedynamicrules
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule {
    /** @var int ID of the rule on the DB */
    private $id;

    /** @var int 1 indicates that the rule is active, 0 indicates that the rule is inactive */
    private $active;

    /** @var int ID of the course */
    private $courseid;

    /** @var condition[] List of conditions instances */
    private $conditions = [];

    /** @var action[] List of actions instances */
    private $actions = [];

    /** @var array List of users to validate this rule */
    private $users;

    /**
     * Rule constructor.
     * @param object $rule
     * @param array $users List of users to validate this rule
     * @param array $conditiontypes list of conditions to include in the executions
     * of rules if not pass all conditions for each rule of the course are added
     */
    public function __construct($rule, $users, $conditiontypes=[]) {
        global $DB;
        $this->id = $rule->id;
        $this->courseid = $rule->courseid;
        $this->users = $users;
        $this->active = $rule->active;

        // Load conditions and actions from the DB.
        $conditions = $DB->get_records('cdr_condition', ['ruleid' => $this->id]);
        $actions = $DB->get_records('cdr_action', ['ruleid' => $this->id]);

        foreach ($conditions as $conditionrecord) {
            if (!empty($conditiontypes) && !in_array($conditionrecord->conditiontype, $conditiontypes)) {
                // Skip condition.
                continue;
            }
            $this->conditions[] = rule_component_loader::create_condition_instance($conditionrecord, $this->courseid);
        }

        foreach ($actions as $actionrecord) {
            $this->actions[] = rule_component_loader::create_action_instance($actionrecord, $this->courseid);
        }

    }

    /**
     * Validate status of licence
     *
     * @return stdClass
     *
     * - success {boolean} - Determine when validations is correct
     * - message {string} - Message to show when validation is not correct
     */
    public static function validate_licence_status() {
        $config = get_config('local_coursedynamicrules');

        $licencekey = $config->licencekey;

        // First time when localkey is not set in config yet.
        $localkey = $config->localkey ? $config->localkey : '';

        $licensestatus = new stdClass();
        $licensestatus->success = false;
        $licensestatus->message = get_string('pluginnotavailable', 'local_coursedynamicrules');

        try {
            if (!empty($licencekey)) {
                $licencedata = self::validate_licence($licencekey, $localkey);

                // When check is remote licencedata contain localkey.
                $localkey = $licencedata["remotecheck"] ? $licencedata["localkey"] : $localkey;

                if ($licencedata['status'] == 'Active') {
                    set_config('localkey', $localkey, 'local_coursedynamicrules');
                    $licensestatus->success = true;
                    $licensestatus->message = '';
                }
            }

        } catch (\Exception $e) {
            debugging(
                'Exception while trying verfy licence ' . $e->getMessage(),
                DEBUG_DEVELOPER
            );
        }

        return $licensestatus;

    }

    /**
     * Get conditions for this rule
     * @return condition[]
     */
    public function get_conditions() {
        return $this->conditions;
    }

    /**
     * Validate if all conditions of the rule are true
     * @param object $rulecontext Necesary information to evaluate the conditions of the rule
     * @return bool
     */
    public function evaluate_conditions($rulecontext) {
        if (empty($this->conditions)) {
            return false;
        }
        foreach ($this->conditions as $condition) {
            if (!$condition->evaluate($rulecontext)) {
                return false;
            }
        }
        return true;
    }

    /**
     *
     * Validate the license key.
     * First validate if the local key is correct so as not to make any request to the server,
     * if the validation of the local key fails, a remote validation is done.
     *
     * @param string $licensekey licence key
     * @param string $localkey local key for avoid remote validation
     *
     * @return array asociative array with properties that contain the result of the validation.
     *
     */
    public static function validate_licence($licensekey, $localkey='') {
        // Configuration Values.
        // Enter the url to your WHMCS installation here.
        $whmcsurl = 'https://shop.datacurso.com/';

        // Must match what is specified in the MD5 Hash Verification field.
        // of the licensing product that will be used with this check.
        $licensingsecretkey = 'Ya9x!&9CsBb6EYeT';
        // The number of days to wait between performing remote license checks.
        $localkeydays = 1;
        // The number of days to allow failover for after local key expiry.
        $allowcheckfaildays = 0;

        // -----------------------------------
        // Do not edit below this line.

        $checktoken = time() . md5(mt_rand(100000000, mt_getrandmax()) . $licensekey);
        $checkdate = date("Ymd");
        $domain = $_SERVER['SERVER_NAME'];
        $usersip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
        $dirpath = dirname(__FILE__);
        $verifyfilepath = 'modules/servers/licensing/verify.php';
        $localkeyvalid = false;

        if (!empty($localkey)) {
            $localkey = str_replace("\n", '', $localkey); // Remove the line breaks.
            $localdata = substr($localkey, 0, strlen($localkey) - 32); // Extract License Data.
            $md5hash = substr($localkey, strlen($localkey) - 32); // Extract MD5 Hash.
            if ($md5hash == md5($localdata . $licensingsecretkey)) {
                $localdata = strrev($localdata); // Reverse the string.
                $md5hash = substr($localdata, 0, 32); // Extract MD5 Hash.
                $localdata = substr($localdata, 32); // Extract License Data.
                $localdata = base64_decode($localdata);
                $localkeyresults = json_decode($localdata, true);
                $originalcheckdate = $localkeyresults['checkdate'];
                if ($md5hash == md5($originalcheckdate . $licensingsecretkey)) {
                    $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y")));
                    if ($originalcheckdate > $localexpiry) {
                        $localkeyvalid = true;
                        $results = $localkeyresults;
                        $validdomains = explode(',', $results['validdomain']);
                        if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
                            $localkeyvalid = false;
                            $localkeyresults['status'] = "Invalid";
                            $results = [];
                        }
                        $validips = explode(',', $results['validip']);
                        if (!in_array($usersip, $validips)) {
                            $localkeyvalid = false;
                            $localkeyresults['status'] = "Invalid";
                            $results = [];
                        }
                        $validdirs = explode(',', $results['validdirectory']);
                        if (!in_array($dirpath, $validdirs)) {
                            $localkeyvalid = false;
                            $localkeyresults['status'] = "Invalid";
                            $results = [];
                        }
                    }
                }
            }
        }
        if (!$localkeyvalid) {
            $responsecode = 0;
            $postfields = [
            'licensekey' => $licensekey,
            'domain' => $domain,
            'ip' => $usersip,
            'dir' => $dirpath,
            ];
            if ($checktoken) {
                $postfields['check_token'] = $checktoken;
            }
            $querystring = '';
            foreach ($postfields as $k => $v) {
                $querystring .= $k.'='.urlencode($v).'&';
            }
            if (function_exists('curl_exec')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $whmcsurl . $verifyfilepath);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $querystring);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $data = curl_exec($ch);
                $responsecode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
            } else {
                $responsecodepattern = '/^HTTP\/\d+\.\d+\s+(\d+)/';
                $fp = @fsockopen($whmcsurl, 80, $errno, $errstr, 5);
                if ($fp) {
                    $newlinefeed = "\r\n";
                    $header = "POST ".$whmcsurl . $verifyfilepath . " HTTP/1.0" . $newlinefeed;
                    $header .= "Host: ".$whmcsurl . $newlinefeed;
                    $header .= "Content-type: application/x-www-form-urlencoded" . $newlinefeed;
                    $header .= "Content-length: ".@strlen($querystring) . $newlinefeed;
                    $header .= "Connection: close" . $newlinefeed . $newlinefeed;
                    $header .= $querystring;
                    $data = $line = '';
                    @stream_set_timeout($fp, 20);
                    @fputs($fp, $header);
                    $status = @socket_get_status($fp);
                    while (!@feof($fp)&&$status) {
                        $line = @fgets($fp, 1024);
                        $patternmatches = [];
                        if (!$responsecode
                        && preg_match($responsecodepattern, trim($line), $patternmatches)
                        ) {
                            $responsecode = (empty($patternmatches[1])) ? 0 : $patternmatches[1];
                        }
                        $data .= $line;
                        $status = @socket_get_status($fp);
                    }
                    @fclose ($fp);
                }
            }
            if ($responsecode != 200) {
                $localexpiry = date(
                    "Ymd",
                    mktime(0, 0, 0, date("m"), date("d") - ($localkeydays + $allowcheckfaildays), date("Y"))
                );
                if ($originalcheckdate > $localexpiry) {
                    $results = $localkeyresults;
                } else {
                    $results = [];
                    $results['status'] = "Invalid";
                    $results['description'] = "Remote Check Failed";
                    return $results;
                }
            } else {
                preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
                $results = [];
                foreach ($matches[1] as $k => $v) {
                    $results[$v] = $matches[2][$k];
                }
            }
            if (!is_array($results)) {
                die("Invalid License Server Response");
            }
            if ($results['md5hash']) {
                if ($results['md5hash'] != md5($licensingsecretkey . $checktoken)) {
                    $results['status'] = "Invalid";
                    $results['description'] = "MD5 Checksum Verification Failed";
                    return $results;
                }
            }
            if ($results['status'] == "Active") {
                $results['checkdate'] = $checkdate;
                $dataencoded = json_encode($results);
                $dataencoded = base64_encode($dataencoded);
                $dataencoded = md5($checkdate . $licensingsecretkey) . $dataencoded;
                $dataencoded = strrev($dataencoded);
                $dataencoded = $dataencoded . md5($dataencoded . $licensingsecretkey);
                $dataencoded = wordwrap($dataencoded, 80, "\n", true);
                $results['localkey'] = $dataencoded;
            }
            $results['remotecheck'] = true;
        }
        unset(
            $postfields,
            $data,
            $matches,
            $whmcsurl,
            $licensingsecretkey,
            $checkdate,
            $usersip,
            $localkeydays,
            $allowcheckfaildays,
            $md5hash
        );
        return $results;

    }

    /**
     * Execute all actions of the rule if the conditions are true
     */
    public function execute() {

        $licensestatus = self::validate_licence_status();
        if (!$licensestatus->success) {
            return;
        }

        foreach ($this->users as $user) {
            $rulecontext = (object)[
                'courseid' => $this->courseid,
                'userid' => $user->id,
            ];

            // Validate if all conditions of the rule are true for the user.
            if ($this->evaluate_conditions($rulecontext)) {
                // Execute all actions of the rule.
                $this->execute_actions($rulecontext);
            }
        }
    }

    /**
     * Execute all actions of the rule
     * @param object $rulecontext Necesary information to execute the actions of the rule
     */
    private function execute_actions($rulecontext) {
        foreach ($this->actions as $action) {
            $action->execute($rulecontext);
        }
    }

    /**
     * Set the active status of the rule
     * @param bool $active 1 indicates that the rule is active, 0 indicates that the rule is inactive
     */
    public function set_active($active) {
        global $DB;
        $this->active = $active ? 1 : 0;
        $DB->set_field('cdr_rule', 'active', $this->active, ['id' => $this->id]);

    }

    /**
     * Retrieves the ID of the rule.
     *
     * @return int The ID of the rule.
     */
    public function get_id() {
        return $this->id;
    }
    /**
     * Deletes a rule record from the 'cdr_rule' table. and related conditions and actions with it.
     *
     * @return bool True on success, false on failure.
     * @throws \dml_exception A DML specific exception is thrown for any errors.
     */
    public function delete() {
        global $DB;

        foreach ($this->conditions as $condition) {
            $condition->delete();
        }

        foreach ($this->actions as $action) {
            $action->delete();
        }

        return $DB->delete_records('cdr_rule', ['id' => $this->id]);
    }

}
