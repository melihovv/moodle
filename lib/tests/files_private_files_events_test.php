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
 * Private files events tests.
 *
 * @package    core
 * @category   test
 * @copyright  2017 Alexander Melihov
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Unit tests for private files events.
 *
 * @package    core
 * @category   test
 * @copyright  2017 Alexander Melihov
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class files_private_files_events_testcase extends advanced_testcase {

    public function test_private_files_viewed_event() {
        $this->resetAfterTest();

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        $params = ['context' => context_user::instance($user->id)];
        $event = \core\event\files_private_files_viewed::create($params);

        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $realevent = reset($events);

        $this->assertInstanceOf('\core\event\files_private_files_viewed', $realevent);
        $this->assertEquals(context_user::instance($user->id), $realevent->get_context());
    }

    public function test_private_files_modified_event() {
        $this->resetAfterTest();

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        $params = [
            'context' => context_user::instance($user->id),
            'other' => [
                'files_before' => [],
                'files_after' => [],
            ],
        ];
        $event = \core\event\files_private_files_modified::create($params);

        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $realevent = reset($events);

        $this->assertInstanceOf('\core\event\files_private_files_modified', $realevent);
        $this->assertEquals(context_user::instance($user->id), $realevent->get_context());
    }
}
