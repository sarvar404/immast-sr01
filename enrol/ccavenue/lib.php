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

defined('MOODLE_INTERNAL') || die();

function RetrieveUserIP()
{
	if(!empty($_SERVER['HTTP_CLIENT_IP']))
	{
		$address=$_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$address=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$address=$_SERVER['REMOTE_ADDR'];
	}
	return $address;
}

class enrol_ccavenue_plugin extends enrol_plugin {

    public function get_currencies() {
        $codes = array(
            'INR', 'USD');
        $currencies = array();
        foreach ($codes as $c) {
            $currencies[$c] = new lang_string($c, 'core_currencies');
        }

        return $currencies;
    }

   public function get_info_icons(array $instances) {
        return array(new pix_icon('icon', get_string('pluginname', 'enrol_ccavenue'), 'enrol_ccavenue'));
    }

    public function roles_protected() {
        // users with role assign cap may tweak the roles later
        return false;
    }

    public function allow_unenrol(stdClass $instance) {
        // users with unenrol cap may unenrol other users manually - requires enrol/ccavenue:unenrol
        return true;
    }

    public function allow_manage(stdClass $instance) {
        // users with manage cap may tweak period and status - requires enrol/ccavenue:manage
        return true;
    }

    public function show_enrolme_link(stdClass $instance) {
        return ($instance->status == ENROL_INSTANCE_ENABLED);
    }

    public function add_course_navigation($instancesnode, stdClass $instance) {
        if ($instance->enrol !== 'ccavenue') {
             throw new coding_exception('Invalid enrol instance type!');
        }

        $context = context_course::instance($instance->courseid);
        if (has_capability('enrol/ccavenue:config', $context)) {
            $managelink = new moodle_url('/enrol/ccavenue/edit.php', array('courseid'=>$instance->courseid, 'id'=>$instance->id));
            $instancesnode->add($this->get_instance_name($instance), $managelink, navigation_node::TYPE_SETTING);
        }
    }

    public function get_action_icons(stdClass $instance) {
        global $OUTPUT;

        if ($instance->enrol !== 'ccavenue') {
            throw new coding_exception('invalid enrol instance!');
        }
        $context = context_course::instance($instance->courseid);

        $icons = array();

        if (has_capability('enrol/ccavenue:config', $context)) {
            $editlink = new moodle_url("/enrol/ccavenue/edit.php", array('courseid'=>$instance->courseid, 'id'=>$instance->id));
            $icons[] = $OUTPUT->action_icon($editlink, new pix_icon('t/edit', get_string('edit'), 'core',
                    array('class' => 'iconsmall')));
        }

        return $icons;
    }

    public function get_newinstance_link($courseid) {
        $context = context_course::instance($courseid, MUST_EXIST);

        if (!has_capability('moodle/course:enrolconfig', $context) or !has_capability('enrol/ccavenue:config', $context)) {
            return NULL;
        }

        // multiple instances supported - different cost for different roles
        return new moodle_url('/enrol/ccavenue/edit.php', array('courseid'=>$courseid));
    }

    function enrol_page_hook(stdClass $instance) {
        global $CFG, $USER, $OUTPUT, $PAGE, $DB;
	require_once( $CFG->dirroot. '/user/profile/lib.php' );
        ob_start();

        if ($DB->record_exists('user_enrolments', array('userid'=>$USER->id, 'enrolid'=>$instance->id))) {
            return ob_get_clean();
        }

        if ($instance->enrolstartdate != 0 && $instance->enrolstartdate > time()) {
            return ob_get_clean();
        }

        if ($instance->enrolenddate != 0 && $instance->enrolenddate < time()) {
            return ob_get_clean();
        }

        $course = $DB->get_record('course', array('id'=>$instance->courseid));
        $context = context_course::instance($course->id);

        $shortname = format_string($course->shortname, true, array('context' => $context));
        $strloginto = get_string("loginto", "", $shortname);
        $strcourses = get_string("courses");

        // Pass $view=true to filter hidden caps if the user cannot see them
        if ($users = get_users_by_capability($context, 'moodle/course:update', 'u.*', 'u.id ASC',
                                             '', '', '', '', false, true)) {
            $users = sort_by_roleassignment_authority($users, $context);
            $teacher = array_shift($users);
        } else {
            $teacher = false;
        }

        if ( (float) $instance->cost <= 0 ) {
            $cost = (float) $this->get_config('cost');
        } else {
            $cost = (float) $instance->cost;
        }

        if (abs($cost) < 0.01) { // no cost, other enrolment methods (instances) should be used
            echo '<p>'.get_string('nocost', 'enrol_ccavenue').'</p>';
        } else {

	    $overseas = 1;


	    $ip = RetrieveUserIP() ;
	    // $ip = '172.105.57.97'; // US IP FOR TESTING.
	    $country_code = function_exists('geoip_country_code_by_name') ? geoip_country_code_by_name($ip) : 'IN';

	    echo "<span><font color='#D0D0D0' size=-1>IP: $ip, Country code:$country_code</font></span>";

	    if ( $country_code == "IN") {
	    	$overseas = 0;
	    } else {
	    	$overseas = 1;
	    }


	    $tax_rate = 0.18;

	    if ( $overseas) {
	    	$cost = $instance->customtext2;
		$currency = $instance->customtext1;
		$tax_rate = 0;
	    } else {
		$currency = $instance->currency;
	    }


            $localisedcost = format_float($cost, 2, true);
            $cost = format_float($cost, 2, false);

            if (isguestuser()) { // force login only for guest user, not real users with guest role
                if (empty($CFG->loginhttps)) {
                    $wwwroot = $CFG->wwwroot;
                } else {
                    $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
                }
                echo '<div class="mdl-align"><p>'.get_string('paymentrequired').'</p>';
                # echo '<p><b>'.get_string('cost').": $instance->currency $localisedcost".'</b></p>';

		$currency = 'INR';
		$discount = 0;
		$discountedPrice = $localisedcost - $discount;
		$tax = $tax_rate * $discountedPrice;
		$payable = $tax + $discountedPrice ;

		# echo '<div align="right">';
		# echo "<p><b>Price: $currency $localisedcost</b></p>" ;
		# echo "<p><b>Discount: $currency $discount</b></p>" ;
		# echo "<p><b>Discounted Price: $currency $discountedPrice</b></p>" ;
		# echo "<p><b>Tax (GST 18%) : $currency $tax</b></p>" ;
		# echo "<p><b><h6>Payable Amount: <b>$currency $payable</b></h6></b></p>" ;
		# echo '</div>';

                echo '<p><a href="'.$wwwroot.'/login/">'.get_string('loginsite').'</a></p>';
                echo '</div>';
            } else {
                $coursefullname  = format_string($course->fullname, true, array('context'=>$context));
                $courseshortname = $shortname;
                $userfullname    = fullname($USER);
                $userfirstname   = $USER->firstname;
                $userlastname    = $USER->lastname;
                $useraddress     = $USER->address;
                $usercity        = $USER->city;
                $instancename    = $this->get_instance_name($instance);

                
                require_once($CFG->dirroot.'/enrol/ccavenue/api/libfuncs.php3');
                // $ccavenueurl = 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
               $ccavenueurl = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
                $Merchant_Id = $instance->customchar1; 
                $Redirect_Url = "$CFG->wwwroot/enrol/ccavenue/ipn.php?uid=".$USER->id ;//your redirect URL where your customer will be redirected after authorisation from CCAvenue
                $WorkingKey = $instance->customchar2; 
                $AccessCode = $instance->customchar3; 

                $Actual_Amount = $cost ;//your script should substitute the amount in the quotes provided here
                $Amount = $cost ;//your script should substitute the amount in the quotes provided here
                $course_id=$course->id;
                $category="ONLINE";

                $Order_Id = $USER->id."-".$course->id."-".$instance->id."-".time()."-".rand(100000,999999) ;//your script should substitute the order description in the quotes provided here


		/* HANDLE DISCOUNT COUPON HERE */

		$applicant = core_user::get_user($USER->id);
		profile_load_custom_fields($applicant);
		$extrauserfields = $applicant->profile;

		$coupon_code = $extrauserfields["coupon_code"];

		$discount=0;
		$coupon_applied = 0;


		if ( $coupon_code) {

			$url = "https://courses.immast.org/api/check_coupon";
			//Make 
			$requestDataObject = [ "coupon" => $coupon_code, "userid" => $USER->userid  ];
			//Serialize object to json.
			$data = json_encode($requestDataObject);
			//Initialize curl.
			$curl = curl_init($url);
			//Setting method type(POST, GET, ...).
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");      
			//If PSOT type you can set the body data like this:                                                               
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);     
			//If request is successful return the response instead of "true".                                                       
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   
			//Add headers.                                                                                                                              
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data))                                                                       
			);
			//Make the request.
			$response = curl_exec($curl);
			//In this case the response will be a json string so we'll use json_decode() to deserialize it.
			$jsonResponse = json_decode($response, true); 
			// echo print_r($jsonResponse , 1);

			$disc_value = $jsonResponse["dis_value"] ;
			$disc_mode  = $jsonResponse["dis_mode"]  ;

			# $currency = 'INR';

			$discount_descr = "";
			if ( $disc_mode === 'PCT' && $disc_value > 0 && $disc_value <= 100.0 ) {
				$discount = ( $disc_value /100.0 ) * $Actual_Amount;
				$discount = format_float( $discount, 2, true)  ;
				$discount_descr = "Discount of $disc_value%% on $Actual_Amount: $currency $discount";
			} else if ( $disc_mode === 'ABS'  && $disc_value > 0 && $disc_value <= $Actual_Amount ) {
				$discount = format_float( $disc_value, 2, true)  ;
				$discount_descr = "Discount of $disc_value on $Actual_Amount = $currency $discount";
			}

			if ( $discount > 0 ) {
				$Amount   = $Amount - $discount;
				$localisedAmount = format_float($Amount , 2, true);
				$localisedDiscount = format_float($discount , 2, true);
				$tax = format_float ( $tax_rate * $localisedAmount, 2, true)  ;
				$localisedAmount_includingTax = format_float( $tax + $localisedAmount, 2, true) ;

				$Amount = $localisedAmount_includingTax;


				$coupon_applied = 1;
				$discount_message = sprintf (
					"Discount coupon <b>$coupon_code</b> applied successfully<br/>"
					. "Discount Detail: $discount_descr <br/>"
					. "Actual Price: $currency $localisedcost, Discounted Price: $currency $localisedAmount <br/>"
					. "GST (%s%%) : $currency $tax<br/>"
					. "<b>Total Payable:</b> $currency $localisedAmount_includingTax (includes tax)<br/>",
					$tax_rate * 100
				) ;
				echo '<div class="alert alert-info">'. $discount_message .'</div>';
				$_SESSION["pg"][$Order_Id]["discounted_cost"] = $localisedAmount ;
			}
			$localisedcost = format_float($Amount , 2, true);
			$cost = format_float($Amount , 2, false);
		// echo "Discounted amount is: " . $Amount ;
		/* END OF DISCOUND COUPON HANDLING */

		} else {
			$localisedAmount = format_float($Amount , 2, true);
			$tax = format_float ( $tax_rate * $localisedAmount, 2, true)  ;
			$localisedAmount_includingTax = format_float( $tax + $localisedAmount, 2, true) ;
			$Amount = $localisedAmount_includingTax;

			$localisedcost = format_float($Amount , 2, true);
			$cost = format_float($Amount , 2, false);
		}

                $Checksum = getCheckSum($Merchant_Id,$Amount,$Order_Id ,$Redirect_Url,$WorkingKey);
                $Merchant_Param=$USER->id."-".$course->id."-".$instance->id;
		if ( ! $coupon_applied && $USER->id > 0 ) {
			include($CFG->dirroot.'/enrol/ccavenue/enrol.html');
		}
            }

        }

        return $OUTPUT->box(ob_get_clean());
    }

    public function restore_instance(restore_enrolments_structure_step $step, stdClass $data, $course, $oldid) {
        global $DB;
        if ($step->get_task()->get_target() == backup::TARGET_NEW_COURSE) {
            $merge = false;
        } else {
            $merge = array(
                'courseid'   => $data->courseid,
                'enrol'      => $this->get_name(),
                'roleid'     => $data->roleid,
                'cost'       => $data->cost,
                'currency'   => $data->currency,
            );
        }
        if ($merge and $instances = $DB->get_records('enrol', $merge, 'id')) {
            $instance = reset($instances);
            $instanceid = $instance->id;
        } else {
            $instanceid = $this->add_instance($course, (array)$data);
        }
        $step->set_mapping('enrol', $oldid, $instanceid);
    }

    public function restore_user_enrolment(restore_enrolments_structure_step $step, $data, $instance, $userid, $oldinstancestatus) {
         $this->enrol_user($instance, $userid, null, $data->timestart, $data->timeend, $data->status);
    }
   
    public function get_user_enrolment_actions(course_enrolment_manager $manager, $ue) {
        $actions = array();
        $context = $manager->get_context();
        $instance = $ue->enrolmentinstance;
        $params = $manager->get_moodlepage()->url->params();
        $params['ue'] = $ue->id;
        if ($this->allow_unenrol($instance) && has_capability("enrol/ccavenue:unenrol", $context)) {
            $url = new moodle_url('/enrol/unenroluser.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/delete', ''), get_string('unenrol', 'enrol'), $url, array('class'=>'unenrollink', 'rel'=>$ue->id));
        }
        if ($this->allow_manage($instance) && has_capability("enrol/ccavenue:manage", $context)) {
            $url = new moodle_url('/enrol/editenrolment.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/edit', ''), get_string('edit'), $url, array('class'=>'editenrollink', 'rel'=>$ue->id));
        }
        return $actions;
    }

    /**
     *      * Is it possible to delete enrol instance via standard UI?
     *           *
     *                * @param stdClass $instance
     *                     * @return bool
     *                          */
    public function can_delete_instance($instance) {
	    $context = context_course::instance($instance->courseid);
	    return has_capability('enrol/ccavenue:config', $context);
    }

    /**
     *      * Is it possible to hide/show enrol instance via standard UI?
     *           *
     *                * @param stdClass $instance
     *                     * @return bool
     *                          */
    public function can_hide_show_instance($instance) {
	    $context = context_course::instance($instance->courseid);
	    return has_capability('enrol/ccavenue:config', $context);
    }

}
