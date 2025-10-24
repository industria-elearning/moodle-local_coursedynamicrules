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

namespace local_coursedynamicrules\output;

use renderable;
use templatable;
use renderer_base;

/**
 * Renders a header (with help) and Datacurso branding aligned on the same row.
 *
 * The constructor accepts language string keys; the class prepares the full
 * context required by the mustache template so pages stay simple.
 *
 * @package    local_coursedynamicrules
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class header_with_brand implements renderable, templatable {
    /** @var string */
    private $headingkey;
    /** @var string */
    private $component;
    /** @var bool */
    private $withhelp;

    /**
     * Constructor.
     *
     * @param string $headingkey Language string key for the heading and (optionally) its help identifier
     * @param string $component Language component, defaults to local_coursedynamicrules
     * @param bool $withhelp Whether to use heading_with_help() (true) or simple heading() (false)
     */
    public function __construct(string $headingkey, string $component = 'local_coursedynamicrules', bool $withhelp = true) {
        $this->headingkey = $headingkey;
        $this->component = $component;
        $this->withhelp = $withhelp;
    }

    /**
     * Export context for the mustache template.
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        if ($this->withhelp) {
            $heading = $output->heading_with_help(
                get_string($this->headingkey, $this->component),
                $this->headingkey,
                $this->component
            );
        } else {
            $heading = $output->heading(get_string($this->headingkey, $this->component));
        }

        return [
            'heading' => $heading,
            'logourl' => $output->image_url('logodatacurso', 'local_coursedynamicrules')->out(false),
        ];
    }
}
