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
 * CCAvenue India (payment) enrolment plugin .
 *
 * @package    enrol_ccavenue
 * @copyright  2010 EddyTools(TM) Anwesha Software Pvt. Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/*





[14-May-2022 00:38:03 Asia/Kolkata] *****************************
[14-May-2022 00:38:03 Asia/Kolkata] stdClass Object
(
    [encResp] => 8965e04f41e77327baa83b9fcedd3613b4516b60454b841de0999700dcaa5dfe0b4660585201dc03efebac235cb044dd8047be9603ad2b5540602ef4b83ebb47060c317d1d3999664a81f5258af1cec026ed0a035e3448bb8acd245c5077fa33229e881d2cdc29ee0b82b119b28f9cdf16a65619e1d943ed407a6cf199bd2dae833012271bc9d030078bd1225534279f72fece7ab9980e8e7eee00e1a0664281fe98a6172019ce352d4661292792382a0a877424caa1bcf2d4f801db1d3c486ef3e11aa6af57d5e8e7cbd705205f261fd2b87b9fbc711c0732f6bebb3d2bc149ca4ae2450880fee1fd4a13d1f45a4a9929902a85ce8bac04cbbd6289eaf2388f1efe06ace34c9a605436de0cf8d54c5297f09e0a78ecb37c923a167fb5c16358a73fbbd68bad33a6b086c2ef6662791690d087b86022ab194ffc9a8a0c2d3dbff6f5c94edd75833b69f2349a033b0f08f7a3d1b692f89eb30292db741c78b06d9ea8c08f68607129272954bec5f58422c9f94f377c1f8ff645f3bd09310213eec22efb64e8a82bf00ee17b637b7f6f4323f324a818151d9934c4e94fc428464b4da0a4e7997629748cc0e9592bbc9dc8cdaffed23383b6efa70aa1f6aec7bb58efed9f0bd44bfcac77acf174c025c7dbc1b3a2ac64ca5fe0fcf4292b8c37854537297cc115a48630ebc0fd7989878d771862852e207d423a40312a34b9b3807dd19cfe92ded15d5e0f68faa2e047ea1567ac2ffea2b203849dc133df95b7e9b655aa4b521bf16bf89c6d6087bf509393fe886be81b091f5a37ffb57660de66ceac83b1d2f072a6c2f61902fa0b9f066abd62eeb9d00d4ddf2cc7a9beb2c38147a0b60f79729033344514ae14fa6d786fe8dbd15648f5a0e2573dba695745f1fc189660bd00b044aef780a5d9fc6cd69b9c93cfa2ce5ba41bcc9f09fd53c81c5ebe534be47814079530b81389990f84bac358336bacd9f229bfaf1f442bd32d46821722a162127927927963dc87e5fc13
    [orderNo] => 7-8-202-1652468830-20
)



*/


require("../../config.php");
require_once("lib.php");
require_once($CFG->libdir.'/enrollib.php');
require_once($CFG->libdir . '/filelib.php');

require_once($CFG->dirroot.'/enrol/ccavenue/api/libfuncs.php3');

$PAGE->set_url('/enrol/ccavenue/ipn.php', array('uid'=> $_GET["uid"]  ) );
$PAGE->set_pagelayout('standard');


$postdata = new stdClass();
foreach ($_POST as $key => $value) {
    $postdata->$key = $value;
}
error_log("*****************************");
error_log(print_r( $postdata , true ));
error_log("*****************************");

$encResp = $postdata->encResp ;
$orderNo = $postdata->orderNo;

$custom = explode('-', $orderNo);
$userid           = (int)$custom[0];
$courseid         = (int)$custom[1];
$instanceid       = (int)$custom[2];

if (! $plugin_instance = $DB->get_record("enrol", array("id"=>$instanceid, "status"=>0))) {
    message_ccavenue_error_to_admin("Not a valid instance id", $instanceid );
    die;
}

$workingKey = $plugin_instance -> customchar2;

error_log("working key is: $workingKey");

$response = decrypt ( $encResp , $workingKey );

error_log("decrypted response is: $response");

$responseData = array();
parse_str($response, $responseData);

error_log(print_r( $responseData , true ));

/*

(
    [order_id] => 7-8-202-1652468830-20
    [tracking_id] => 111503497656
    [bank_ref_no] => 50521810383
    [order_status] => Success
    [failure_message] =>
    [payment_mode] => Unified Payments
    [card_name] => UPI
    [status_code] =>
    [status_message] => SUCCESS-NA-00
    [currency] => INR
    [amount] => 10.00
    [vault] => N
    [mer_amount] => 10.00
    [eci_value] =>
    [retry] => N
    [response_code] =>
    [billing_notes] =>
    [trans_date] => 14/05/2022 12:37:57
    [bin_country] =>
)

immast_mdl_db=> \d public.mdl_enrol_ccavenue
                                          Table "public.mdl_enrol_ccavenue"
       Column        |          Type          | Collation | Nullable |                    Default
---------------------+------------------------+-----------+----------+------------------------------------------------
 id                  | bigint                 |           | not null | nextval('mdl_enrol_ccavenue_id_seq'::regclass)
 business            | character varying(255) |           | not null | ''::character varying
 receiver_email      | character varying(255) |           | not null | ''::character varying
 receiver_id         | character varying(255) |           | not null | ''::character varying
 item_name           | character varying(255) |           | not null | ''::character varying
 courseid            | bigint                 |           | not null | 0
 userid              | bigint                 |           | not null | 0
 instanceid          | bigint                 |           | not null | 0
 memo                | character varying(255) |           | not null | ''::character varying
 tax                 | character varying(255) |           | not null | ''::character varying
 option_name1        | character varying(255) |           | not null | ''::character varying
 option_selection1_x | character varying(255) |           | not null | ''::character varying
 option_name2        | character varying(255) |           | not null | ''::character varying
 option_selection2_x | character varying(255) |           | not null | ''::character varying
 payment_status      | character varying(255) |           | not null | ''::character varying
 pending_reason      | character varying(255) |           | not null | ''::character varying
 reason_code         | character varying(30)  |           | not null | ''::character varying
 txn_id              | character varying(255) |           | not null | ''::character varying
 parent_txn_id       | character varying(255) |           | not null | ''::character varying
 payment_type        | character varying(30)  |           | not null | ''::character varying
 timeupdated         | bigint                 |           | not null | 0
Indexes:

*/

$data = new stdClass();

$data->timeupdated      = time();

$data->userid           = $userid;
$data->courseid         = $courseid;
$data->instanceid       = $instanceid ;

$data->txn_id           = $responseData["bank_ref_no"] ;
$data->parent_txn_id    = $responseData["order_id"] ;
$data->payment_type     = $responseData["payment_mode"] ;
$data->payment_status   = $responseData["order_status"] ;

$currency = $responseData["currency"];
$amount = $responseData["amount"];
$trackingId = $responseData["tracking_id"];
$data->memo    = "payment amount: $amount, tracking_id: $trackingId"  ;

$data->timeupdated      = time();
/***************** Test Data *******^^^^^*************/


/// get the user and course records

if (! $user = $DB->get_record("user", array("id"=>$data->userid))) {
    message_ccavenue_error_to_admin("Not a valid user id", $data);
    die;
}

if (! $course = $DB->get_record("course", array("id"=>$data->courseid))) {
    message_ccavenue_error_to_admin("Not a valid course id", $data);
    die;
}

if (! $context = context_course::instance($course->id, IGNORE_MISSING)) {
    message_ccavenue_error_to_admin("Not a valid context id", $data);
    die;
}

$PAGE->set_context($context);

$coursecontext = context_course::instance($course->id, IGNORE_MISSING);

// Check that amount paid is the correct amount

if ( (float) $plugin_instance->cost <= 0 ) {
    $cost = (float) $plugin->get_config('cost');
} else {
    $cost = (float) $plugin_instance->cost;
}

error_log("currency is: $currency");
if ( $currency == "USD") {
    $cost = (float) $plugin_instance->customtext2;
    error_log("USD cost is: $cost");
    error_log("amount from: $cost");
}

/* handling of discounted cost */
$Order_Id = $responseData["order_id"];
if ( $_SESSION["pg"][$Order_Id]["discounted_cost"] > 0  ) {
	$cost = $_SESSION["pg"][$Order_Id]["discounted_cost"] ;
	$_SESSION["pg"][$Order_Id]["discounted_cost"] = 0 ;
}

// Use the same rounding of floats as on the enrol form.
$cost = format_float($cost, 2, false);
/*
if ($amount < $cost) {
    message_ccavenue_error_to_admin("Amount paid is not enough ($amount < $cost))", $data);
    die;

}
 */

// PARAM ALL CLEAR !


if($data->payment_status == "Success" )
{
    $data->txn_amount = $amount;
    $plugin = enrol_get_plugin('ccavenue');
    $DB->insert_record("enrol_ccavenue", $data);

    if ($plugin_instance->enrolperiod) {
        $timestart = time();
        $timeend   = $timestart + $plugin_instance->enrolperiod;
    } else {
        $timestart = 0;
        $timeend   = 0;
    }

    
    $plugin->enrol_user($plugin_instance, $user->id, $plugin_instance->roleid, $timestart, $timeend);
    // print_r($plugin);
    // Pass $view=true to filter hidden caps if the user cannot see them
    
    if ($users = get_users_by_capability($context, 'moodle/course:update', 'u.*', 'u.id ASC','', '', '', '', false, true)) {
	    $coursecontext = context_course::instance($course->id, IGNORE_MISSING);

        $users = sort_by_roleassignment_authority($users, $context);
        $teacher = array_shift($users);
    } else {
        $teacher = false;
    }

    
    
    $mailstudents = $plugin->get_config('mailstudents');
    $mailteachers = $plugin->get_config('mailteachers');
    $mailadmins   = $plugin->get_config('mailadmins');
    $shortname = format_string($course->shortname, true, array('context' => $context));


    if (!empty($mailstudents)) {
        $a = new stdClass();
        $a->coursename = format_string($course->fullname, true, array('context' => $coursecontext));
        $a->profileurl = "$CFG->wwwroot/user/view.php?id=$user->id";

        $eventdata =  new \core\message\message(); 
        $eventdata->modulename        = 'moodle';
        $eventdata->component         = 'enrol_ccavenue';
        $eventdata->name              = 'ccavenue_enrolment';
        $eventdata->userfrom          = $teacher;
        $eventdata->userto            = $user;
        $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
        $eventdata->fullmessage       = get_string('welcometocoursetext', '', $a);
        $eventdata->fullmessageformat = FORMAT_PLAIN;
        $eventdata->fullmessagehtml   = '';
        $eventdata->smallmessage      = '';
        message_send($eventdata);

    }

    if (!empty($mailteachers)) {
        $a->course = format_string($course->fullname, true, array('context' => $coursecontext));
        $a->user = fullname($user);

        $eventdata = new \core\message\message(); 
        $eventdata->modulename        = 'moodle';
        $eventdata->component         = 'enrol_ccavenue';
        $eventdata->name              = 'ccavenue_enrolment';
        $eventdata->userfrom          = $user;
        $eventdata->userto            = $teacher;
        $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
        $eventdata->fullmessage       = get_string('enrolmentnewuser', 'enrol', $a);
        $eventdata->fullmessageformat = FORMAT_PLAIN;
        $eventdata->fullmessagehtml   = '';
        $eventdata->smallmessage      = '';
        message_send($eventdata);
    }

    if (!empty($mailadmins)) {
        $a->course = format_string($course->fullname, true, array('context' => $coursecontext));
        $a->user = fullname($user);
        $admins = get_admins();
        foreach ($admins as $admin) {
	    $eventdata = new \core\message\message(); 
            $eventdata->modulename        = 'moodle';
            $eventdata->component         = 'enrol_ccavenue';
            $eventdata->name              = 'ccavenue_enrolment';
            $eventdata->userfrom          = $user;
            $eventdata->userto            = $admin;
            $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
            $eventdata->fullmessage       = get_string('enrolmentnewuser', 'enrol', $a);
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml   = '';
            $eventdata->smallmessage      = '';
            message_send($eventdata);
        }
         
    }
    $course_fullname = $course->fullname ;
    // notice("<br><br><br>Thank for your payment , Course: '$course_fullname' is added to your account<br> <br><h3 style='color:green;'>Your OrderID is <b> $Order_Id </b> and Amount is <b>$cost</b></h3><br>   " , $CFG->wwwroot);


    


    //$USER->email = "waoflix@gmail.com";
    // error_log(print_r($USER,true));


    $current_date = date("Y/m/d");
    $user_name = $USER->firstname . " " . $USER->lastname; 
    $reciept_html = "
       
<style type='text/css'>
        li {
            list-style: none;
        }

    </style>
<div class='container bootdey'>
<h2> Payment Receipt </h2><br><br>         
<div class='row invoice row-printable'>
            <div class='col-md-10'>

                <div class='panel panel-default plain' id='dash_0'>

                    <div class='panel-body p30'>
                        <div class='row'>

                            <div class='col-lg-6'>
                               <!--
                                <div class='invoice-logo'><img width='100'
                                        src='https://courses.immast.org/pluginfile.php/957/user/icon/' alt='Invoice logo'>
                                </div> -->
                            </div>

                            <div class='col-lg-6'>

                            <!--    <div class='invoice-from'>
                                    <ul class='list-unstyled text-right'>
                                        <li>Dash LLC</li>
                                        <li>2500 Ridgepoint Dr, Suite 105-C</li>
                                        <li>Austin TX 78754</li>
                                        <li>VAT Number EU826113958</li>
                                    </ul>
                                </div> -->
                            </div>

                            <div class='col-lg-12'>

                                <div class='invoice-details mt25'>
                                    <div class='well'>
                                        <ul class='list-unstyled mb0'>
                                            <li><strong>Order ID: </strong> $Order_Id</li>
                                            <li><strong>Order Date:</strong> $current_date</li>
                                            <li><strong>Status:</strong> <span class='label label-danger'>$data->payment_status</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class='invoice-to mt25'>
                                    <ul class='list-unstyled'>
                                        <li><strong>Reciept To</strong></li>
                                        <li> $user_name ( $USER->email  )     </li>
                                    </ul>
                                </div>
                                <div class='invoice-items'>
                                    <div class='table-responsive' style='overflow: hidden; outline: none;' tabindex='0'>
                                        <table class='table table-bordered'>
                                            <thead>
                                                <tr>
                                                    <th class='per70 text-center'>Course name</th>
                                                    <th class='per5 text-center'>Qty</th>
                                                    <th class='per25 text-center'>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> $course_fullname</td>
                                                    <td class='text-center'>1</td>
                                                    <td class='text-center'>$amount</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan='2' class='text-right'>Total:</th>
                                                    <th class='text-center'>$amount</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class='invoice-footer mt25'>
                                    <p class='text-center'>Generated on $current_date <!-- <a href='#'
                                            class='btn btn-default ml15'><i class='fa fa-print mr5'>  </i> Print</a> --> </p>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

";


/*
    if (!empty($mailstudents)) {
        $a = new stdClass();
        $a->coursename = format_string($course->fullname, true, array('context' => $coursecontext));
        $a->profileurl = "$CFG->wwwroot/user/view.php?id=$user->id";

        $eventdata =  new \core\message\message();
        $eventdata->modulename        = 'moodle';
        $eventdata->component         = 'enrol_ccavenue';
        $eventdata->name              = 'ccavenue_enrolment';
        $eventdata->userfrom          = $teacher;
        $eventdata->userto            = $user;
        $eventdata->subject           = "Payment receipt for Order Id - $Order_Id - $shortname" ;
        $eventdata->fullmessage       = $reciept_html;
        $eventdata->fullmessageformat = FORMAT_PLAIN;
        $eventdata->fullmessagehtml   = $reciept_html;
        $eventdata->smallmessage      = '';
        message_send($eventdata);

    }
 */

   if (!empty($mailadmins)) {
        $a->course = format_string($course->fullname, true, array('context' => $coursecontext));
        $a->user = fullname($user);
        $admins = get_admins();
        foreach ($admins as $admin) {
            $eventdata = new \core\message\message();
            $eventdata->modulename        = 'moodle';
            $eventdata->component         = 'enrol_ccavenue';
            $eventdata->name              = 'ccavenue_enrolment';
            $eventdata->userfrom          = $user;
            $eventdata->userto            = $admin;
            $eventdata->subject           = "Payment receipt for Order Id - $Order_Id - $shortname" ;
            $eventdata->fullmessage       =  $reciept_html ;
            $eventdata->fullmessageformat = FORMAT_HTML;
            $eventdata->fullmessagehtml   =  $reciept_html;
            $eventdata->smallmessage      = '';
            message_send($eventdata);
        }

    }



    notice("<br><br><br>Thank for your payment , Course: '$course_fullname' is added to your account<br> <br><h3 style='color:green;'>Your OrderID is <b> $Order_Id </b> and Amount is <b>$amount</b></h3><br> <hr> <br>$reciept_html <br> <br><br><br> <strong>  Note:</strong>  This order receipt is subject to realisation of the payment. Final receipt shall be mailed after completion of the training course." , $CFG->wwwroot);











} else if($data->Checksum=="true" && $data->AuthDesc=="B") { // ERROR
    $DB->insert_record("enrol_ccavenue", $data, false);
    message_ccavenue_error_to_admin("Received an payment status is pending!!", $data);
    notice("<br><br><br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail<br><br><br>",$CFG->webroot);

    //echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
    //Here you need to put in the routines/e-mail for a  "Batch Processing" order
    //This is only if payment for this transaction has been made by an American Express Card
    //since American Express authorisation status is available only after 5-6 hours by mail from ccavenue and at the "View Pending Orders"
} else if($data->Checksum=="true" && $data->AuthDesc=="N"){
    notice("<br><br><br>CCAvenue : Sorry !!! The transaction has been <strong>declined</strong>.<br><br><br>",$CFG->webroot);		
    //Here you need to put in the routines for a failed
    //transaction such as sending an email to customer
    //setting database status etc etc

}else{
    notice("<br><br><br>CCAvenue : INVALID TRANSATION<br><br><br>",$CFG->webroot);

}


exit;


//--- HELPER FUNCTIONS --------------------------------------------------------------------------------------


function message_ccavenue_error_to_admin($subject, $data) {
    echo $subject;
    $admin = get_admin();
    $site = get_site();

    $message = "$site->fullname:  Transaction failed.\n\n$subject\n\n";

    foreach ($data as $key => $value) {
        $message .= "$key => $value\n";
    }

    $eventdata =  new \core\message\message(); 
    $eventdata->modulename        = 'moodle';
    $eventdata->component         = 'enrol_ccavenue';
    $eventdata->name              = 'ccavenue_enrolment';
    $eventdata->userfrom          = $admin;
    $eventdata->userto            = $admin;
    $eventdata->subject           = "ccavenue ERROR: ".$subject;
    $eventdata->fullmessage       = $message;
    $eventdata->fullmessageformat = FORMAT_PLAIN;
    $eventdata->fullmessagehtml   = '';
    $eventdata->smallmessage      = '';
    
    message_send($eventdata);
}
?>
