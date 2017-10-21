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

/**
 * The private files modified event.
 *
 * @package    core
 * @copyright  2017 Alexander Melihov <amelihovv@ya.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The private files modified event.
 *
 * @property-read array $other {
 *      Extra information about event.
 *
 *      - array files_before: user private files before modification.
 *      - array files_after: user private files after modification.
 * }
 *
 * @package    core
 * @since      Moodle 3.4
 * @copyright  2017 Alexander Melihov <amelihovv@ya.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class files_private_files_modified extends \core\event\base {

    /**
     * Initialise required event data properties.
     */
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Returns localised general event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventprivatefilesmodified', 'core');
    }

    /**
     * Returns non-localised event description with id's for admin use only.
     *
     * @return string
     */
    public function get_description() {
        $desc = "The user with id '$this->userid' modified his private files.";

        $filesbefore = $this->other['files_before'];
        $filesafter = $this->other['files_after'];

        $deletedfiles = array_diff($filesbefore, $filesafter);
        if ($deletedfiles) {
            $desc .= ' Deleted files: ' . implode(', ', $deletedfiles) . '.';
        }

        $addedfiles = array_diff($filesafter, $filesbefore);
        if ($addedfiles) {
            $desc .= ' Added files: ' . implode(', ', $addedfiles) . '.';
        }

        return $desc;
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception when validation does not pass.
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();

        if (!isset($this->other['files_before'])) {
            throw new \coding_exception('The \'files_before\' must be set.');
        }

        if (!isset($this->other['files_after'])) {
            throw new \coding_exception('The \'files_after\' must be set.');
        }
    }
}
