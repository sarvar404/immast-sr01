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
 * This page shows all course enrolment options for current user.
 *
 * @package    core_enrol
 * @copyright  2010 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../config.php');
require_once("$CFG->libdir/formslib.php");

$id = required_param('id', PARAM_INT);
$returnurl = optional_param('returnurl', 0, PARAM_LOCALURL);

/*
if (!isloggedin()) {
    $referer = get_local_referer();
    if (empty($referer)) {
        // A user that is not logged in has arrived directly on this page,
        // they should be redirected to the course page they are trying to enrol on after logging in.
        $SESSION->wantsurl = "$CFG->wwwroot/course/view.php?id=$id";
    }
    // do not use require_login here because we are usually coming from it,
    // it would also mess up the SESSION->wantsurl
    redirect(get_login_url());
}
 */

$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

// Everybody is enrolled on the frontpage
if ($course->id == SITEID) {
    redirect("$CFG->wwwroot/");
}

if (!$course->visible && !has_capability('moodle/course:viewhiddencourses', context_course::instance($course->id))) {
    print_error('coursehidden');
}

$PAGE->set_course($course);
$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/enrol/index.php', array('id'=>$course->id));

// do not allow enrols when in login-as session
if (\core\session\manager::is_loggedinas() and $USER->loginascontext->contextlevel == CONTEXT_COURSE) {
    print_error('loginasnoenrol', '', $CFG->wwwroot.'/course/view.php?id='.$USER->loginascontext->instanceid);
}

// Check if user has access to the category where the course is located.
if (!core_course_category::can_view_course_info($course) && !is_enrolled($context, $USER, '', true)) {
    print_error('coursehidden', '', $CFG->wwwroot . '/');
}

// get all enrol forms available in this course
$enrols = enrol_get_plugins(true);
$enrolinstances = enrol_get_instances($course->id, true);
$forms = array();
$maxEnrolReached = false;
$showPG  = false;
global $DB;
foreach($enrolinstances as $instance) {
    $showForm = false;
    if (!isset($enrols[$instance->enrol])) {
        continue;
    }

    $form = $enrols[$instance->enrol]->enrol_page_hook($instance);
    if ($form) {
        if ( $instance->customint3 > 0 && $instance->enrol != 'ccavenue' ) {
            $count = $DB->count_records('user_enrolments', array('enrolid' => $instance->id));
            if ($count < $instance->customint3 && $maxEnrolReached == false ) {
                $maxEnrolReached = true;
                $showForm = true;
                if ( preg_match('/waitlist/i' , $instance->name ) != 1 ){
                    $showPG = true ;
                }
            }
        }
        
        if ( $instance->enrol == 'ccavenue' && $showPG  ){
            $showForm = true ;
        }

        if ( $showForm == true ){
            $forms[$instance->id] = $form;
        }
    }
}

// Check if user already enrolled
if (is_enrolled($context, $USER, '', true)) {
    if (!empty($SESSION->wantsurl)) {
        $destination = $SESSION->wantsurl;
        unset($SESSION->wantsurl);
    } else {
        $destination = "$CFG->wwwroot/course/view.php?id=$course->id";
    }
    redirect($destination);   // Bye!
}

$PAGE->set_title($course->shortname);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add(get_string('enrolmentoptions','enrol'));





echo $OUTPUT->header();


echo '
<div style="
    background: #f6f6f6;
    border: 2px solid #c02424;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    font-family: Arial, sans-serif;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
">
    <h3 style="margin-top: 0; color: #c02424;">
        📞 Connect with Us
    </h3>
    <p style="font-size: 16px; line-height: 1.6; color: #333;">
        <span style="font-weight: bold;">Call us at:</span><br>
        ☎️ 96652 15299 / 86556 59847 / 86556 59846 / 86556 59845 / 9711008644 / 022 6101 2000
    </p>

    <p style="font-size: 16px; line-height: 1.6; color: #333;">
        <span style="font-weight: bold;">Email:</span><br>
        📧 <a href="mailto:contact@immast.org" style="color: #c02424; text-decoration: none;">contact@immast.org</a>
    </p>
</div>';


echo $OUTPUT->heading(get_string('enrolmentoptions','enrol'));



// echo '<div style="background: #f0f8ff; border-left: 5px solid #0073aa; padding: 15px; margin-bottom: 20px;">
//         <strong>Need Help?</strong><br>
//         <strong>Call:</strong> 96652 15299 / 86556 59847 / 86556 59846 / 86556 59845 / 9711008644 / 022 6101 2000<br>
//         <strong>Mail us at:</strong> <a href="mailto:contact@immast.org">contact@immast.org</a>
//       </div>';




$courserenderer = $PAGE->get_renderer('core', 'course');
// echo $courserenderer->course_info_box($course);

//TODO: find if future enrolments present and display some info

foreach ($forms as $form) {
	if ( $USER->id > 0) {
		echo $form;
	}
}


if (!$forms) {
    if (isguestuser()) {
        notice(get_string('noguestaccess', 'enrol'), get_login_url());
    } else if ($returnurl) {
        notice('', $returnurl);
        // notice(get_string('notenrollable', 'enrol'), $returnurl);
    } else {
        $url = get_local_referer(false);
        if (empty($url)) {
            $url = new moodle_url('/index.php');
        }
        notice('', $url);
        // notice(get_string('notenrollable', 'enrol'), $url);
    }
}

echo $OUTPUT->footer();
