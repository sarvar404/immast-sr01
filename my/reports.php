<?php 

require_once(__DIR__ . '/../config.php');
require_once($CFG->dirroot . '/my/lib.php');

redirect_if_major_upgrade_required();

// TODO Add sesskey check to edit
$edit   = optional_param('edit', null, PARAM_BOOL);    // Turn editing on and off
$reset  = optional_param('reset', null, PARAM_BOOL);

$ec_id  = $_GET['id'];
require_login();

$hassiteconfig = has_capability('moodle/site:config', context_system::instance());
if ($hassiteconfig && moodle_needs_upgrading()) {
    redirect(new moodle_url('/admin/index.php'));
}

$strmymoodle = get_string('myhome');

if (isguestuser()) {  // Force them to see system default, no editing allowed
    // If guests are not allowed my moodle, send them to front page.
    if (empty($CFG->allowguestmymoodle)) {
        redirect(new moodle_url('/', array('redirect' => 0)));
    }

    $userid = null;
    $USER->editing = $edit = 0;  // Just in case
    $context = context_system::instance();
    $PAGE->set_blocks_editing_capability('moodle/my:configsyspages');  // unlikely :)
    $strguest = get_string('guest');
    $header = "$SITE->shortname: $strmymoodle ($strguest)";
    $pagetitle = $header;

} else {        // We are trying to view or edit our own My Moodle page
    $userid = $USER->id;  // Owner of the page
    $context = context_user::instance($USER->id);
    $PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
    $header = "$SITE->shortname: $strmymoodle";
    $pagetitle = $strmymoodle;
}



// Get the My Moodle page info.  Should always return something unless the database is broken.
if (!$currentpage = my_get_page($userid, MY_PAGE_PRIVATE)) {
    print_error('mymoodlesetup');
}

// Start setting up the page
$params = array();
$PAGE->set_context($context);
$PAGE->set_url('/my/index.php', $params);
$PAGE->set_pagelayout('mydashboard');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$PAGE->set_subpage($currentpage->id);
$PAGE->set_title($pagetitle);
$PAGE->set_heading($header);

if (!isguestuser()) {   // Skip default home page for guests
    if (get_home_page() != HOMEPAGE_MY) {
        if (optional_param('setdefaulthome', false, PARAM_BOOL)) {
            set_user_preference('user_home_page_preference', HOMEPAGE_MY);
        } else if (!empty($CFG->defaulthomepage) && $CFG->defaulthomepage == HOMEPAGE_USER) {
            $frontpagenode = $PAGE->settingsnav->add(get_string('frontpagesettings'), null, navigation_node::TYPE_SETTING, null);
            $frontpagenode->force_open();
            $frontpagenode->add(get_string('makethismyhome'), new moodle_url('/my/', array('setdefaulthome' => true)),
                    navigation_node::TYPE_SETTING);
        }
    }
    // echo get_reports_form();

}




//echo $OUTPUT->header();
//echo get_reports_form();




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startdate          = htmlspecialchars($_POST['startdate']);
    $enddata            = htmlspecialchars($_POST['enddata']);
    $file_format        = htmlspecialchars($_POST['file_format']);
    $registration_type  = htmlspecialchars($_POST['registration-type']);
    $idnumber           = htmlspecialchars($_POST['idnumber']);
    $mobile_num         = htmlspecialchars($_POST['mobile_num']);


    // echo $registration_type;
    // echo $enddata;
    //echo  $startdate;
    $uri_part = "";
    $header = '';
    $file_name = "";

    if ($file_format == "excel") {
        $file_name = "registration_reports.xlsx";
        $header = 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $uri_part = "download_registration_report";
    }
    elseif ($file_format == "pdf") {
        $header = 'Content-Type: application/pdf';
        $file_name = "registration_reports.pdf";
        $uri_part = "download_registration_report_pdf";
    }


    $url = rtrim($CFG->wwwroot, '/') . "/api/$uri_part?registration_type=$registration_type&start_date=$startdate&end_date=$enddata&mobile_num=$mobile_num&idnumber=$idnumber";
    // $url =    "http://10.100.103.3:10991/download_registration_report?registration_type=all&start_date=2024-02-13&end_date=2024-05-13";
    $api_token = "tk16Q80LnJE4MOUsTzrldKb9ZFojicYpGgANS2Pva7DChVI5x";
    $ch = curl_init($url);

    $dir = './';

    $save_file_loc = $dir . $file_name;
    $fp = fopen($save_file_loc, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    // curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('api_token: tk16Q80LnJE4MOUsTzrldKb9ZFojicYpGgANS2Pva7DChVI5x',));
    curl_exec($ch);
    curl_close($ch);

    fclose($fp);

    $fp = fopen($save_file_loc, 'rb');
    header($header);
    header("Content-Disposition: attachment; filename=$save_file_loc");
    echo fread($fp, filesize($save_file_loc));
    fclose($fp);
    unlink($save_file_loc);
}else{
	echo $OUTPUT->header();
	echo get_reports_form();
}
// echo $OUTPUT->footer();


