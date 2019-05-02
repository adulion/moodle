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
 * Run the code checker from the web.
 *
 * @package    local_coursecompletionstats
 * @copyright  2019 Chris McCabe
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('coursecompletionstats', '', null, '', array('pagelayout' => 'report'));

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('lb_main_header', 'local_coursecompletionstatus'));

$table = new html_table();

$table->head = array(get_string('lb_first_name', 'local_coursecompletionstatus'),
        get_string('lb_last_name', 'local_coursecompletionstatus'),
        '',
);

foreach (get_users() as $user) {

    $row = array();

    $row[] = $user->firstname;
    $row[] = $user->lastname;
    $row[] = html_writer::link(
            new moodle_url($CFG->wwwroot . '/local/coursecompletionstatus/details.php?user_id=' . $user->id), 'Visit'
    );

    $table->data[] = $row;
}

echo html_writer::table($table);

echo $OUTPUT->footer();