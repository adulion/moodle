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

$userid = optional_param('user_id', 0, PARAM_INT);

admin_externalpage_setup('coursecompletionstats', '', null, '', array('pagelayout' => 'report'));

echo $OUTPUT->header();

$userdetails = core_user::get_user($userid);

if($userdetails) {

    $usercourses = $DB->get_records_sql('SELECT ue.id, ue.userid, e.courseid, c.fullname, cc.id completion_id, cc.timecompleted FROM
    {user_enrolments} ue JOIN {enrol} e ON ( ue.enrolid = e.id) JOIN {course} c ON e.courseid = c.id AND userid = :id LEFT JOIN
    {course_completions} cc ON cc.userid = ue.userid AND cc.course = e.courseid ORDER BY c.fullname ASC', ['id' => $userid]);

    echo $OUTPUT->heading(get_string('lb_details_header', 'local_coursecompletionstatus') . ': ' . $userdetails->firstname . ' - ' .
            $userdetails->lastname);

    $table = new html_table();

    $table->head = array(get_string('lb_course_name', 'local_coursecompletionstatus'),
            get_string('lb_completion_status', 'local_coursecompletionstatus'),
            get_string('lb_completed_on', 'local_coursecompletionstatus'));

    foreach ($usercourses as $course) {
        $row = array();

        $row[] = html_writer::link(new moodle_url('/course/view.php', array('id' => $course->courseid)), $course->fullname);
        $row[] = is_null($course->completion_id) ? 'Incomplete' : 'Completed';
        $row[] = is_null($course->timecompleted) ? 'Incomplete' :
                userdate($course->timecompleted, get_string('strftimedatetime', 'langconfig'));

        $table->data[] = $row;
    }
    echo html_writer::table($table);

}
else {

}

echo $OUTPUT->footer();

