<?php

require_once( 'config.php');

defined('MOODLE_INTERNAL') || die("ERROR");

require_once($CFG->libdir . '/behat/lib.php');
header("Access-Control-Allow-Origin: https://www.immast.org");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Max-Age: 86400");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");


if (isloggedin()) {
	$user->name = $USER->firstname . " " . $USER->lastname  ;
	$user->userid = $USER->id ;
	$user->loggedin = 1 ;
	$myJSON = json_encode($user);
	echo $myJSON;
} else {
	$user->name = "";
	$user->userid = 0 ;
	$user->loggedin = 0 ;
	$myJSON = json_encode($user);
	echo $myJSON;
}
?>

