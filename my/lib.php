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
 * My Moodle -- a user's personal dashboard
 *
 * This file contains common functions for the dashboard and profile pages.
 *
 * @package    moodlecore
 * @subpackage my
 * @copyright  2010 Remote-Learner.net
 * @author     Hubert Chathi <hubert@remote-learner.net>
 * @author     Olav Jordan <olav.jordan@remote-learner.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('MY_PAGE_PUBLIC', 0);
define('MY_PAGE_PRIVATE', 1);

require_once("$CFG->libdir/blocklib.php");

/*
 * For a given user, this returns the $page information for their My Moodle page
 *
 */
function my_get_page($userid, $private=MY_PAGE_PRIVATE) {
    global $DB, $CFG;

    if (empty($CFG->forcedefaultmymoodle) && $userid) {  // Ignore custom My Moodle pages if admin has forced them
        // Does the user have their own page defined?  If so, return it.
        if ($customised = $DB->get_record('my_pages', array('userid' => $userid, 'private' => $private))) {
            return $customised;
        }
    }

    // Otherwise return the system default page
    return $DB->get_record('my_pages', array('userid' => null, 'name' => '__default', 'private' => $private));
}


/*
 * This copies a system default page to the current user
 *
 */
function my_copy_page($userid, $private=MY_PAGE_PRIVATE, $pagetype='my-index') {
    global $DB;

    if ($customised = $DB->get_record('my_pages', array('userid' => $userid, 'private' => $private))) {
        return $customised;  // We're done!
    }

    // Get the system default page
    if (!$systempage = $DB->get_record('my_pages', array('userid' => null, 'private' => $private))) {
        return false;  // error
    }

    // Clone the basic system page record
    $page = clone($systempage);
    unset($page->id);
    $page->userid = $userid;
    $page->id = $DB->insert_record('my_pages', $page);

    // Clone ALL the associated blocks as well
    $systemcontext = context_system::instance();
    $usercontext = context_user::instance($userid);

    $blockinstances = $DB->get_records('block_instances', array('parentcontextid' => $systemcontext->id,
                                                                'pagetypepattern' => $pagetype,
                                                                'subpagepattern' => $systempage->id));
    $newblockinstanceids = [];
    foreach ($blockinstances as $instance) {
        $originalid = $instance->id;
        unset($instance->id);
        $instance->parentcontextid = $usercontext->id;
        $instance->subpagepattern = $page->id;
        $instance->timecreated = time();
        $instance->timemodified = $instance->timecreated;
        $instance->id = $DB->insert_record('block_instances', $instance);
        $newblockinstanceids[$originalid] = $instance->id;
        $blockcontext = context_block::instance($instance->id);  // Just creates the context record
        $block = block_instance($instance->blockname, $instance);
        if (!$block->instance_copy($originalid)) {
            debugging("Unable to copy block-specific data for original block instance: $originalid
                to new block instance: $instance->id", DEBUG_DEVELOPER);
        }
    }

    // Clone block position overrides.
    if ($blockpositions = $DB->get_records('block_positions',
            ['subpage' => $systempage->id, 'pagetype' => $pagetype, 'contextid' => $systemcontext->id])) {
        foreach ($blockpositions as &$positions) {
            $positions->subpage = $page->id;
            $positions->contextid = $usercontext->id;
            if (array_key_exists($positions->blockinstanceid, $newblockinstanceids)) {
                // For block instances that were defined on the default dashboard and copied to the user dashboard
                // use the new blockinstanceid.
                $positions->blockinstanceid = $newblockinstanceids[$positions->blockinstanceid];
            }
            unset($positions->id);
        }
        $DB->insert_records('block_positions', $blockpositions);
    }

    return $page;
}

/*
 * For a given user, this deletes their My Moodle page and returns them to the system default.
 *
 * @param int $userid the id of the user whose page should be reset
 * @param int $private either MY_PAGE_PRIVATE or MY_PAGE_PUBLIC
 * @param string $pagetype either my-index or user-profile
 * @return mixed system page, or false on error
 */





function get_reports_form (){
	
	global $USER,$DB,$CFG;
if (isloggedin()) {
        $templatecontext['isloggedin'] = 1;
        $templatecontext['username'] = $USER->username ;
        $systemcontext = context_system::instance();
        if (has_capability('moodle/role:manage', $systemcontext)) {


        $form_html = "


    <div class='container bootdey my-5'>
        <form action='/my/reports.php' method='post' onsubmit= 'javascript: return validate_form() ;' id='download_report_form' >
            <h2>Download Registration reports</h2>
            <hr>

            <div class='row'>
                <div class='col my-2'>
                    <label for='exampleInputStartDate1' class='form-label'>Select Report Format: </label>
                    <select class='form-select' name='file_format' required>
                        <option value='excel' selected> Excel Format</option>
                        <option value='pdf'>PDF Format</option>
                    </select>
                </div>
                <div class='col my-2'>
                    <label for='exampleInputStartDate1' class='form-label'>Select Registration Type: </label>
                    <select class='form-select' name='registration-type' required>
                        <option value='all' selected> All</option>
                        <option value='registration-only'>Registration only</option>
                        <option value='waitlist'>WaitList</option>
                        <option value='apply-pg-enrollment'>Apply / PG Enrollment</option>
                        <option value='signup-of-newsletter'>Signup of Newsletter</option>
                    </select>
                </div>
            </div>

            <div class='row'>
                <div class='col my-2'>
                    <label for='exampleInputStartDate1' class='form-label'>Registration start date: </label>
                    <input type='date' class='form-control' id='exampleInputStartDate1' placeholder='Start date'
                        name='startdate'>
                </div>
                <div class='col my-2'>
                    <label for='exampleInputEndDate1' class='form-label'>Registration end date: </label>
                    <input type='date' class='form-control' id='exampleInputEndDate1' placeholder='End date'
                        name='enddata'>
                </div>
            </div>

        <div class='row'>
            <div class='col my-2'>
                <label for='exampleInputStartDate1' class='form-label'>Enter ID Number: </label>
                <input type='text' class='form-control' id='exampleInputStartDate1' placeholder='Enter ID Number' name='idnumber' >
            </div>
            <div class='col my-2'>
                <label for='exampleInputEndDate1' class='form-label'>Enter Mobile number: </label>
                <input type='text' class='form-control' id='exampleInputEndDate1' placeholder='Mobile Number' name='mobile_num' >
            </div>
        </div>

            <button type='submit' class='btn btn-primary'>Submit</button>
        </form>

    </div>





<script>
    function validate_form(){
        var body_validated  = true;        
        var start_date           = $('#download_report_form input[name=startdate]'); 
        var end_date          = $('#download_report_form input[name=enddata]'); 
        var idnumber          = $('#download_report_form input[name=idnumber]'); 
        var mobile_num          = $('#download_report_form input[name=mobile_num]'); 
        console.log('mobile_num: ' + mobile_num.val())
        console.log('idnumber: ' + idnumber.val())
        console.log('end_date: ' + end_date.val())
        console.log('start_date: ' + start_date.val())
        if (mobile_num.val() == '' && idnumber.val() == '' && end_date.val() == '' && start_date.val() == '' ){
            body_validated = false;
            alert('Please fill atleast registration start-date/end-date ,  ID-Number, and Mobile-number');
        } 

        return body_validated
    }
</script>










";

        return $form_html;
                $templatecontext['role_manage'] = 1 ;



        }
}

}










function get_all_invoices (){
	global $DB, $USER, $CFG;
	$logged_user_id = $USER->id ;
	// echo "<h2>USER: " . $USER->id . "</h1>" ;
	//$sql = " select  ue.id as ue_id, ec.id as ec_id, ue.userid, usr.firstname || ' ' || usr.lastname as user_fullname , usr.username as username , usr.email as user_email, ue.enrolid, parent_txn_id as order_id, payment_status, payment_type , memo as notes , ec.courseid  ,course.fullname as course_fullname, course.shortname as course_shortname   from mdl_user_enrolments ue   join mdl_user usr on (usr.id=ue.userid)  join mdl_enrol enrol on (enrol.id=ue.enrolid) join mdl_enrol_ccavenue ec on (ec.courseid=enrol.courseid and ec.userid=ue.userid) join mdl_course course on (course.id=ec.courseid)   where ue.userid=$logged_user_id  and enrol.name ilike 'ccavenue';";
	
	$sql = "select  ue.id as ue_id, ec.id as ec_id, ue.userid, usr.firstname || ' ' || usr.lastname as user_fullname , usr.username as username , usr.email as user_email, ue.enrolid, parent_txn_id as order_id, payment_status, payment_type , memo as notes , ec.courseid  ,course.fullname as course_fullname, course.shortname as course_shortname , to_timestamp(ec.timeupdated) as ec_date  from mdl_user_enrolments ue   join mdl_user usr on (usr.id=ue.userid)  join mdl_enrol enrol on (enrol.id=ue.enrolid) join mdl_enrol_ccavenue ec on (ec.courseid=enrol.courseid and ec.userid=ue.userid) join mdl_course course on (course.id=ec.courseid)   where ue.userid=$logged_user_id  and enrol.name ilike 'ccavenue';";
	 $invoices_rs = $DB->get_recordset_sql($sql);
	

	if ( sizeof($invoices_rs) >  0 ){


	
	$invoice_info_html = '';
	foreach ($invoices_rs as $inv ){
		$invoice_info_html = sprintf('<tr><td> %s (%s) </td><td><b>Order-ID:</b> %s  <br><b>Order-Date:</b> %s  <br></td><td><b>Customer Name:</b> %s (%s) <br><b>Customer-Email:</b> %s <br></td><td> <a href="/my/invoices.php?id=%s"> View Invoice</a> </td></tr>', $inv->course_fullname, $inv->course_shortname, $inv->order_id, $inv->ec_date , $inv->user_fullname , $inv->username , $inv->user_email, $inv->ec_id) ;

	
	}

	
	$html = sprintf ('
	<div class="container">
	 <h1> Invoices  </h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <!-- <th scope="col">#</th> -->
                    <th scope="col">Course name </th>
                    <th scope="col">Order Info </th>
                    <th scope="col">Customer info</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody> %s </tbody>
        </table>
	</div>' , $invoice_info_html); 
	
	echo $html ;
	}

}



function get_invoice_info( $ec_id) {
	global $DB,$USER, $CFG ;
	
	$logged_in_userid = $USER->id;
	$sql = "select  ec.txn_amount, uid.data as custom_address , ec.txn_id as rxn_id,  ue.id as ue_id, ec.id as ec_id, ue.userid, usr.firstname || ' ' || usr.lastname as user_fullname , usr.username as username , usr.email as user_email, ue.enrolid, parent_txn_id as order_id, payment_status, payment_type , memo as notes , ec.courseid  ,course.fullname as course_fullname, course.shortname as course_shortname , ec.timeupdated as ec_date, cost  from mdl_user_enrolments ue   join mdl_user usr on (usr.id=ue.userid)  join mdl_enrol enrol on (enrol.id=ue.enrolid) join mdl_enrol_ccavenue ec on (ec.courseid=enrol.courseid and ec.userid=ue.userid) join mdl_course course on (course.id=ec.courseid)  join mdl_user_info_data uid on (uid.userid=usr.id) join mdl_user_info_field uif on (uif.id=uid.fieldid)  where ue.userid= $logged_in_userid  and enrol.name ilike 'ccavenue' and ec.id =  $ec_id  and uif.shortname='address';";
	//$sql = "select ec.txn_amount as txn_amount ,  ec.txn_id as txn_id,  usr.address as address , usr.city as city, usr.country as country, ue.id as ue_id, ec.id as ec_id, ue.userid, usr.firstname || ' ' || usr.lastname as user_fullname , usr.username as username , usr.email as user_email, ue.enrolid, parent_txn_id as order_id, payment_status, payment_type , memo as notes , ec.courseid  ,course.fullname as course_fullname, course.shortname as course_shortname , to_timestamp( ec.timeupdated) as ec_date, cost  from mdl_user_enrolments ue   join mdl_user usr on (usr.id=ue.userid)  join mdl_enrol enrol on (enrol.id=ue.enrolid) join mdl_enrol_ccavenue ec on (ec.courseid=enrol.courseid and ec.userid=ue.userid) join mdl_course course on (course.id=ec.courseid)   where ue.userid=$logged_in_userid  and enrol.name ilike 'ccavenue' and ec.id = $ec_id ; ";

	$invoices_rs = $DB->get_recordset_sql($sql);
	
	if (sizeof( $invoices_rs  ) > 0){
	foreach ($invoices_rs as $data){
 


    $address = $data->custom_address ;
    if ($data->address){
        $address = $data->address;
    }

$amount = $data->cost;

if ( $data->txn_amount  )
{
   $amount = $data->txn_amount;
}
	$html = "

    <style type='text/css'>
        li {
            list-style: none;
        }
    </style>
    <div class='container bootdey'>
        <h2> Invoice: $data->txn_id </h2> <!-- <button type='button' onclick='javascript: print();'> Print Invoice</button> -->
        <br><br>
        <div class='row invoice row-printable'>
            <div class='col-md-10'>

                <div class='panel panel-default plain' id='dash_0'>

                    <div class='panel-body p30'>
                        <div class='row'>
                            <div class='row'>

                                <div class='col-lg-6'>
                                    <div class='well'>
                                        <h2>Sold By: </h2>
                                        <ul class='list-unstyled mb0'>
                                            <li><strong>IMMAST.org </strong></li>
                                            <li> Ground Floor, Poonam Chambers, <br> B-Wing, Opp. Atria Mall, Dr. Annie
                                                Besant Road, <br>Shiv Sagar Estate, Worli, Mumbai - 400018,<br> India
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class='col-lg-6'>
                                    <div class='well'>
                                        <h2>Billing Address: </h2>
                                        <ul class='list-unstyled mb0'>
                                            <li><strong>  $data->user_fullname </strong></li>
                                            <li> 
                                                $address
                                            </li>
                                            <li> 
                                                $data->city
                                            </li>
                                            <li> 
                                                $data->country
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class='col-lg-12'>
                                <div class='invoice-details mt25'>
                                    <div class='well'>
                                        <ul class='list-unstyled mb0'>
                                            <li><strong>Order ID: </strong> $data->order_id</li>
                                            <li><strong>Order Date:</strong> $data->ec_date</li>
                                            <li><strong>Order Status:</strong> <span class='label label-danger'>$data->payment_status</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- <div class='invoice-to mt25'>
                                    <ul class='list-unstyled'>
                                        <li><strong>Reciept To</strong></li>
                                        <li> $data->user_fullname ( $USER->email ) </li>
                                    </ul>
                                </div> -->
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
                                                    <td> $data->course_fullname</td>
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
                                    <p class='text-center'>Generated on $data->ec_date
                                        <!-- <a href='#'
                                                class='btn btn-default ml15'><i class='fa fa-print mr5'>  </i> Print</a> -->
                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

";
 echo $html ;

}

    }

}


function my_reset_page($userid, $private=MY_PAGE_PRIVATE, $pagetype='my-index') {
    global $DB, $CFG;

    $page = my_get_page($userid, $private);
    if ($page->userid == $userid) {
        $context = context_user::instance($userid);
        if ($blocks = $DB->get_records('block_instances', array('parentcontextid' => $context->id,
                'pagetypepattern' => $pagetype))) {
            foreach ($blocks as $block) {
                if (is_null($block->subpagepattern) || $block->subpagepattern == $page->id) {
                    blocks_delete_instance($block);
                }
            }
        }
        $DB->delete_records('block_positions', ['subpage' => $page->id, 'pagetype' => $pagetype, 'contextid' => $context->id]);
        $DB->delete_records('my_pages', array('id' => $page->id));
    }

    // Get the system default page
    if (!$systempage = $DB->get_record('my_pages', array('userid' => null, 'private' => $private))) {
        return false; // error
    }

    // Trigger dashboard has been reset event.
    $eventparams = array(
        'context' => context_user::instance($userid),
        'other' => array(
            'private' => $private,
            'pagetype' => $pagetype,
        ),
    );
    $event = \core\event\dashboard_reset::create($eventparams);
    $event->trigger();
    return $systempage;
}

/**
 * Resets the page customisations for all users.
 *
 * @param int $private Either MY_PAGE_PRIVATE or MY_PAGE_PUBLIC.
 * @param string $pagetype Either my-index or user-profile.
 * @param progress_bar $progressbar A progress bar to update.
 * @return void
 */
function my_reset_page_for_all_users($private = MY_PAGE_PRIVATE, $pagetype = 'my-index', $progressbar = null) {
    global $DB;

    // This may take a while. Raise the execution time limit.
    core_php_time_limit::raise();

    $users = $DB->get_fieldset_select(
        'my_pages',
        'DISTINCT(userid)',
        'userid IS NOT NULL AND private = :private',
        ['private' => $private]
    );
    $chunks = array_chunk($users, 20);

    if (!empty($progressbar) && count($chunks) > 0) {
        $count = count($chunks);
        $message = get_string('inprogress');
        $progressbar->update(0, $count, $message);
    }

    foreach ($chunks as $key => $userchunk) {
        list($infragment, $inparams) = $DB->get_in_or_equal($userchunk,  SQL_PARAMS_NAMED);
        // Find all the user pages and all block instances in them.
        $sql = "SELECT bi.id
                  FROM {my_pages} p
                  JOIN {context} ctx ON ctx.instanceid = p.userid AND ctx.contextlevel = :usercontextlevel
                  JOIN {block_instances} bi ON bi.parentcontextid = ctx.id
                   AND bi.pagetypepattern = :pagetypepattern
                   AND (bi.subpagepattern IS NULL OR bi.subpagepattern = " . $DB->sql_concat("''", 'p.id') . ")
                 WHERE p.private = :private
                   AND p.userid $infragment";

        $params = array_merge([
            'private' => $private,
            'usercontextlevel' => CONTEXT_USER,
            'pagetypepattern' => $pagetype
        ], $inparams);
        $blockids = $DB->get_fieldset_sql($sql, $params);

        // Wrap the SQL queries in a transaction.
        $transaction = $DB->start_delegated_transaction();

        // Delete the block instances.
        if (!empty($blockids)) {
            blocks_delete_instances($blockids);
        }

        // Finally delete the pages.
        $DB->delete_records_select(
            'my_pages',
            "userid $infragment AND private = :private",
            array_merge(['private' => $private], $inparams)
        );

        // We should be good to go now.
        $transaction->allow_commit();

        if (!empty($progressbar)) {
            $progressbar->update(((int) $key + 1), $count, $message);
        }
    }

    // Trigger dashboard has been reset event.
    $eventparams = array(
        'context' => context_system::instance(),
        'other' => array(
            'private' => $private,
            'pagetype' => $pagetype,
        ),
    );
    $event = \core\event\dashboards_reset::create($eventparams);
    $event->trigger();

    if (!empty($progressbar)) {
        $progressbar->update(1, 1, get_string('completed'));
    }
}

class my_syspage_block_manager extends block_manager {
    // HACK WARNING!
    // TODO: figure out a better way to do this
    /**
     * Load blocks using the system context, rather than the user's context.
     *
     * This is needed because the My Moodle pages set the page context to the
     * user's context for access control, etc.  But the blocks for the system
     * pages are stored in the system context.
     */
    public function load_blocks($includeinvisible = null) {
        $origcontext = $this->page->context;
        $this->page->context = context_system::instance();
        parent::load_blocks($includeinvisible);
        $this->page->context = $origcontext;
    }
}
