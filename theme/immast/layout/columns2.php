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
 * columns2.php
 *
 * @package   theme_immast
 * @copyright 2015 Lmsace Dev Team,lmsace.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {
    $navdraweropen = false;
}
$navdraweropen = false;
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
// Header content.
$logourl = get_logo_url();
$surl = new moodle_url('/course/search.php');
if (!$PAGE->url->compare($surl, URL_MATCH_BASE)) {
    $compare = 1;
} else {
    $compare = 0;
}
$surl = new moodle_url('/course/search.php');
$ssearchcourses = get_string('searchcourses');
$shome = get_string('home', 'theme_immast');

$custom = $OUTPUT->custom_menu();

if ($custom == '') {
    $class = "navbar-toggler hidden-lg-up nocontent-navbar";
} else {
    $class = "navbar-toggler hidden-lg-up";
}

// Footer Content.
$logourlfooter = get_logo_url('footer');
$footlogo = theme_immast_get_setting('footerlogo');
$footnote = theme_immast_get_setting('footnote', 'format_html');
$fburl    = theme_immast_get_setting('fburl');
$pinurl   = theme_immast_get_setting('pinurl');
$twurl    = theme_immast_get_setting('twurl');
$gpurl    = theme_immast_get_setting('gpurl');
$address  = theme_immast_get_setting('address');
$emailid  = theme_immast_get_setting('emailid');
$phoneno  = theme_immast_get_setting('phoneno');
$copyrightfooter = theme_immast_get_setting('copyright_footer');
$infolink = theme_immast_get_setting('infolink');
$infolink = theme_immast_infolink();

$sinfo = get_string('info', 'theme_immast');
$scontactus = get_string('contact_us', 'theme_immast');
$sphone = get_string('phone', 'theme_immast');
$semail = get_string('email', 'theme_immast');
$sgetsocial = get_string('get_social', 'theme_immast');

$url = ($fburl != '' || $pinurl != '' || $twurl != '' || $gpurl != '') ? 1 : 0;
$contact = ($emailid != '' || $address != '' || $phoneno != '') ? 1 : 0;

if ($footlogo != '' || $footnote != '' || $infolink != '' || $url != 0 || $contact != 0 || $copyrightfooter != '') {
    $footerall = 1;
} else {
    $footerall = 0;
}

$block1 = ($footlogo != '' || $footnote != '') ? 1 : 0;
$infoslink = ($infolink != '') ? 1 : 0;
$blockarrange = $block1 + $infoslink + $contact + $url;

switch ($blockarrange) {
    case 4:
        $colclass = 'col-md-3';
        break;
    case 3:
        $colclass = 'col-md-4';
        break;
    case 2:
        $colclass = 'col-md-6';
        break;
    case 1:
        $colclass = 'col-md-12';
        break;
    case 0:
        $colclass = '';
        break;
    default:
        $colclass = 'col-md-3';
    break;
}

$sesskey = sesskey();
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    "surl" => $surl,
    "s_searchcourses" => $ssearchcourses,
    "s_home" => $shome,
    "logourl" => $logourl,
    "compare" => $compare,
    "logourl_footer" => $logourlfooter,
    "footnote" => $footnote,
    "fburl" => $fburl,
    "pinurl" => $pinurl,
    "twurl" => $twurl,
    "gpurl" => $gpurl,
    "address" => $address,
    "emailid" => $emailid,
    "phoneno" => $phoneno,
    "copyright_footer" => $copyrightfooter,
    "infolink" => $infolink,
    "s_info" => $sinfo,
    "s_contact_us" => $scontactus,
    "s_phone" => $sphone,
    "s_email" => $semail,
    "s_get_social" => $sgetsocial,
    "url" => $url,
    "contact" => $contact,
    "footerall" => $footerall,
    "customclass" => $class,
    "block1" => $block1,
    "colclass" => $colclass,
    "sesskey" => $sesskey,
];

$templatecontext['flatnavigation'] = $PAGE->flatnav;
global $USER;
if (isloggedin()) {
	$templatecontext['isloggedin'] = 1;
	$templatecontext['username'] = $USER->username ;
	$systemcontext = context_system::instance();
	if (has_capability('moodle/role:manage', $systemcontext)) {
		$templatecontext['role_manage'] = 1 ;
	}
}

if (isloggedin() and ! isguestuser()) {
	$templatecontext['proper_loggedin'] = 1;
} else {
	$templatecontext['proper_loggedin'] = 0;
}

if($PAGE->url->get_path() == '/enrol/index.php'){

	global $DB, $CFG;

	$id = required_param('id', PARAM_INT);
	$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);
	$context = context_course::instance($course->id, MUST_EXIST);
	$templatecontext['course_name'] = $course->fullname;
	$templatecontext['course_summary'] = $course->summary;
	// $templatecontext['short_descr'] = $course->short_descr;
	$templatecontext['startdate'] = date('dS F Y (D)'  , $course->startdate) ;
	$templatecontext['enddate'] = date('dS F Y (D)' , $course->enddate) ;

	$course_element = new core_course_list_element($course);

	$short_descr_obj = $DB->get_record_sql("select  b.value  from  public.mdl_customfield_field a join  public.mdl_customfield_data b on (a.id=b.fieldid) and b.instanceid=$id and a.shortname='short_descr' " );
	$short_descr = $short_descr_obj->value ;

	$templatecontext['short_descr'] = $short_descr;

	// error_log ("course custom fields  is:"  . print_r ( $short_descr , true)  );
	// error_log ("short_descr is : "  . $short_descr );

	$imgcnt = 0;
	$course_images;
	foreach ($course_element->get_course_overviewfiles() as $file) {
		$url = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php",
				'/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
				$file->get_filearea() . $file->get_filepath() . $file->get_filename(), 0);

		// error_log ("url is:"  . $url );

		$course_images[$imgcnt] = (object) array('url' => $url );
		if ( $imgcnt == 0) {
			$templatecontext['course_image'] = $url ;
		}
		$imgcnt++;
	}
	$templatecontext['course_images'] = $course_images ;
	$templatecontext['category'] = $course->category ;

	$category_id = $course->category;


	
	/*
	error_log(sprintf ("getting list of courses in this category: $category_id" )  );
	$other_courses = $DB->get_records('course', array('category'=>$category_id), '*', MUST_EXIST);
	foreach ($other_courses as $this_course) {



	}
	 */

	// error_log(sprintf ("getting list of courses in this category: $category_id" )  );
	// $category_courses = $DB->get_records( 'course', array('category' => $category_id), '', 'id, fullname,startdate');

	$sql = "select id,fullname,startdate from mdl_course where category=$category_id and to_timestamp(startdate) >= now()" ;
	$category_courses = $DB->get_records_sql($sql);

	$other_courses = array();
	foreach ($category_courses as $rec) {
		if ( $rec->id != $course->id) {
			$epoch = $rec->startdate;
			$dt = new DateTime("@$epoch");
			// $startdate =  $dt->format('Y-m-d H:i:s');
			$startdate =  $dt->format('d/m/Y');
			array_push ( $other_courses , (object) array( 'id' => $rec->id , 'fullname' => $rec->fullname , 'startdate' => $startdate)) ;
		}
	}
	// print(count($other_courses));
	// Now get other courses based on tag.
	//
	$sql = "select itemid,fullname,startdate  from public.mdl_tag_instance mtio join public.mdl_course mc on (mtio.itemid=mc.id)  where itemtype='course' and  tagid in (select tagid  from public.mdl_tag_instance mti join public.mdl_tag mt on (mti.tagid=mt.id)  where itemtype='course' and itemid='$id' and mt.name ilike 'rel%' ) and itemid <> '$id'"  ;

	// error_log ("getting records for sql: $sql");
        $records = $DB->get_records_sql($sql);
	foreach ($records as $rec) {

		 $epoch = $rec->startdate;
		 $dt = new DateTime("@$epoch");
		 $startdate =  $dt->format('d/m/Y');
		 array_push ( $other_courses , (object) array( 'id' => $rec->itemid , 'fullname' => $rec->fullname , 'startdate' => $startdate)) ;
	}
	// End of records based on tag.

	$templatecontext['other_courses'] = $other_courses ;
	// get testimonials.
	$records = $DB->get_records_sql("select  user_id , testimonial,ratings, firstname  || ' ' || lastname as fullname   from  general.testimonials t join mdl_user  u1 on (t.user_id=u1.id)    where course_id=$id" );
	$testimonials = array();
	foreach ($records as $rec) {

		$rater_id =  $rec->user_id;
		$userpicture = new user_picture(core_user::get_user( $rater_id ));
		$profile_pic = $userpicture->get_url($PAGE)->out(false);
		
		array_push ( $testimonials , (object) array( 
			'testimonial' => $rec->testimonial ,
			'rating' => $rec->ratings , 
			'fullname' => $rec->fullname ,  
			'profile_pic' => $profile_pic ,
		)) ;
			// error_log ("pushing testtimonial --> " . $rec["testimonial"]  );
	}
	if( sizeof($testimonials) > 0   ){
		$templatecontext['testimonials_exists'] = true ;
	}
	$templatecontext['testimonials'] = $testimonials ;
	// end of testimonials
	//

	// get the instructor
	//
	//

	$sql = "select cfd.value as value from mdl_course course join mdl_customfield_data cfd on(cfd.instanceid = course.id) join mdl_customfield_field cff  on(cff.id = cfd.fieldid  )  where course.id=$id and cff.shortname='course_video_url' and cfd.value ~ 'YouTube video player' ";
	$records = $DB->get_records_sql($sql);
	$urls = array();
	foreach ($records as $rec) {
		array_push ($urls , $rec->value );
	}
	error_log(print_r($urls,true));
	$templatecontext['video_url'] = html_entity_decode ($urls[0])  ;

	$inst_sql = "
		SELECT DISTINCT ue.userid
		FROM mdl_user_enrolments ue
		JOIN mdl_enrol enr
			ON ue.enrolid = enr.id
		JOIN mdl_role_assignments mra
			ON ue.userid = mra.userid
			AND mra.roleid = 3
		WHERE enr.courseid = :courseid
	";

	$records = $DB->get_records_sql($inst_sql, ['courseid' => $id]);

	$professionals = [];
		
	if ($records) {
		foreach ($records as $rec) {
			$professional = core_user::get_user($rec->userid);

			if (!$professional) continue;

			$userpicture = new user_picture($professional);
			$userpicture->size = 1;
			$profile_pic = $userpicture->get_url($PAGE)->out(false);

			$professionals[] = (object)[
				'bio' => $professional->description,
				'fullname' => fullname($professional),
				'profile_pic' => $profile_pic,
			];
		}
	}
	$templatecontext['professionals'] = $professionals;

	// $inst_sql = "select ue.userid  from mdl_user_enrolments ue  join mdl_enrol enr on (ue.enrolid=enr.id)  join mdl_role_assignments mra on ( ue.userid = mra.userid AND mra.roleid=3) where courseid=$id" ;
	// $record = $DB->get_record_sql($inst_sql );

	// if ( $record ) {
	// 	$userid = $record->userid ;
	// 	$professional = core_user::get_user( $userid );
	// 	$bio = $professional->description ;
	// 	$userpicture = new user_picture($professional );
	// 	$userpicture->size = 1;
	// 	$profile_pic = $userpicture->get_url($PAGE)->out(false);
	// 	$profession_obj = (object) array( 
	// 		'bio' => $bio , 
	// 		'fullname' => $professional->firstname . " " .  $professional->lastname   ,  
	// 		'profile_pic' => $profile_pic ,
	// 	) ;
	// }
	
	$templatecontext['phpsession'] = session_id();
	// end of get the instructor
	
	// error_log(sprintf ("value is: %s" , print_r ( $course_images , true) )  );
	echo $OUTPUT->render_from_template('theme_immast/enroll', $templatecontext);
	// echo $OUTPUT->render_from_template('theme_immast/columns2', $templatecontext);
}else{
	echo $OUTPUT->render_from_template('theme_immast/columns2', $templatecontext);
}
