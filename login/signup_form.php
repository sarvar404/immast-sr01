<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/user/profile/lib.php');
require_once($CFG->dirroot . '/user/editlib.php');

class login_signup_form extends moodleform implements renderable, templatable {

    function definition() {
        global $CFG, $PAGE;

        $mform = $this->_form;

        /* -------------------------------------------------------------
                               CREATE USERNAME + PASSWORD
        --------------------------------------------------------------*/
        $mform->addElement('header', 'createuserandpass', get_string('createuserandpass'));

        $mform->addElement('text', 'username', get_string('username'), 'maxlength="100" size="20"');
        $mform->setType('username', PARAM_RAW);
        $mform->addRule('username', get_string('missingusername'), 'required', null, 'client');

        if (!empty($CFG->passwordpolicy)) {
            $mform->addElement('static', 'passwordpolicyinfo', '', print_password_policy());
        }

        $mform->addElement('password', 'password', get_string('password'), 'maxlength="32" size="20"');
        $mform->setType('password', core_user::get_property_type('password'));
        $mform->addRule('password', get_string('missingpassword'), 'required', null, 'client');

        /* -------------------------------------------------------------
                                     EMAIL
        --------------------------------------------------------------*/
        $mform->addElement('header', 'supplyinfo', get_string('supplyinfo'));

        $mform->addElement('text', 'email', get_string('email'), 'maxlength="100" size="30" id="id_email"');
        $mform->setType('email', PARAM_RAW);
        $mform->addRule('email', get_string('missingemail'), 'required', null, 'client');
        $mform->setForceLtr('email');

        $mform->addElement('text', 'email2', get_string('emailagain'), 'maxlength="100" size="30"');
        $mform->setType('email2', PARAM_RAW);
        $mform->addRule('email2', get_string('missingemail'), 'required', null, 'client');
        $mform->setForceLtr('email2');

        /* -------------------------------------------------------------
                                  NAME FIELDS
        --------------------------------------------------------------*/
        $namefields = useredit_get_required_name_fields();
        foreach ($namefields as $field) {
            $mform->addElement('text', $field, get_string($field), 'maxlength="100" size="30"');
            $mform->setType($field, PARAM_TEXT);
            $mform->addRule($field, get_string('required'), 'required', null, 'client');
        }

        /* -------------------------------------------------------------
                                 PROFILE FIELDS
        --------------------------------------------------------------*/
        profile_signup_fields($mform);

        /* -------------------------------------------------------------
                                OTP SECTION
        --------------------------------------------------------------*/
        $mform->addElement('button', 'sendotp', 'Send OTP', [
            'id' => 'id_sendotp',
            'onclick' => 'sendOTP(); return false;'
        ]);

        $mform->addElement('text', 'otp', 'Enter OTP', 'maxlength="6" size="10" id="id_otp"');
        $mform->setType('otp', PARAM_RAW);

        $mform->addElement('button', 'verifyotp', 'Verify OTP', [
            'id' => 'id_verifyotp',
            'onclick' => 'verifyOTP(); return false;'
        ]);

        /* -------------------------------------------------------------
                                      CAPTCHA
        --------------------------------------------------------------*/
        if (signup_captcha_enabled()) {
            $mform->addElement('recaptcha', 'recaptcha_element', get_string('security_question', 'auth'));
            $mform->addHelpButton('recaptcha_element', 'recaptcha', 'auth');
        }

        /* -------------------------------------------------------------
                                      POLICY
        --------------------------------------------------------------*/
        core_login_extend_signup_form($mform);

        $policy = new \core_privacy\local\sitepolicy\manager();
        $policy->signup_form($mform);

        /* -------------------------------------------------------------
                                SUBMIT BUTTON
        --------------------------------------------------------------*/
        $this->add_action_buttons(true, get_string('createaccount'));

        /* -------------------------------------------------------------
                                JAVASCRIPT LOGIC
        --------------------------------------------------------------*/
        $PAGE->requires->js_init_code("

document.addEventListener('DOMContentLoaded', function () {

    let submitBtn = document.getElementById('id_submitbutton');
    if (submitBtn) submitBtn.disabled = true;

    let otpInput  = document.getElementById('id_otp');
    let otpLabel  = document.querySelector('label[for=\"id_otp\"]');
    let verifyBtn = document.getElementById('id_verifyotp');

    // Hide using !important
    if (otpInput) otpInput.style.setProperty('display', 'none', 'important');
    if (otpLabel) otpLabel.style.setProperty('display', 'none', 'important');
    if (verifyBtn) verifyBtn.style.setProperty('display', 'none', 'important');
});


window.sendOTP = function () {
    let email = document.getElementById('id_email').value;
    let sendBtn = document.getElementById('id_sendotp');
    if (!email) { alert('Please enter your email'); return; }
    sendBtn.disabled = true;
    sendBtn.textContent = 'Sending...';
    let baseUrl = M.cfg.wwwroot.replace(/\\/$/, '');
    fetch(baseUrl + '/api/send_otp.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify({
            email: email,
            sesskey: M.cfg.sesskey
        })
    })
    .then(function (response) {
        return response.json().then(function (data) {
            if (!response.ok || !data || data.type !== 'OK') {
                throw new Error(data && data.message ? data.message : 'Failed to send OTP');
            }
            return data;
        });
    })
    .then(function (data) {
        alert(data.message);
        let otpInput  = document.getElementById('id_otp');
        let otpLabel  = document.querySelector('label[for=\"id_otp\"]');
        let verifyBtn = document.getElementById('id_verifyotp');
        otpInput.style.setProperty('display', 'block', 'important');
        otpLabel.style.setProperty('display', 'block', 'important');
        verifyBtn.style.setProperty('display', 'inline-block', 'important');
        otpInput.focus();
        sendBtn.textContent = 'Resend OTP';
        sendBtn.disabled = false;
    })
    .catch(function (error) {
        alert(error.message || 'Unable to send OTP. Please try again later.');
        sendBtn.disabled = false;
        sendBtn.textContent = 'Send OTP';
    });
};


window.verifyOTP = function () {

    let email = document.getElementById('id_email').value;
    let otp   = document.getElementById('id_otp').value;
    if (!otp) { alert('Enter OTP'); return; }

    let verifyBtn = document.getElementById('id_verifyotp');
    verifyBtn.disabled = true;
    let baseUrl = M.cfg.wwwroot.replace(/\\/$/, '');
    fetch(baseUrl + '/api/verify_otp.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify({
            email: email,
            otp: otp,
            sesskey: M.cfg.sesskey
        })
    })
    .then(function (response) {
        return response.json().then(function (data) {
            if (!response.ok || !data || data.type !== 'OK') {
                throw new Error(data && data.message ? data.message : 'OTP verification failed');
            }
            return data;
        });
    })
    .then(function (data) {
        alert(data.message);
        let submitBtn = document.getElementById('id_submitbutton');
        if (submitBtn) submitBtn.disabled = false;
        document.getElementById('id_otp').style.setProperty('display', 'none', 'important');
        document.querySelector('label[for=\"id_otp\"]').style.setProperty('display', 'none', 'important');
        document.getElementById('id_verifyotp').style.setProperty('display', 'none', 'important');
        document.getElementById('id_sendotp').style.setProperty('display', 'none', 'important');
    })
    .catch(function (error) {
        alert(error.message || 'Unable to verify OTP. Please try again.');
        verifyBtn.disabled = false;
    });
};

");

    }


    /* -------------------------------------------------------------
                           SERVER-SIDE VALIDATION
    --------------------------------------------------------------*/
    function validation($data, $files) {

        $errors = parent::validation($data, $files);

        global $SESSION;
        $email = isset($data['email']) && is_string($data['email']) ?
                core_text::strtolower(trim($data['email'])) : '';
        $emailkey = hash('sha256', $email);
        $verifieduntil = isset($SESSION->signupotpverified) && is_array($SESSION->signupotpverified) &&
                isset($SESSION->signupotpverified[$emailkey]) ? $SESSION->signupotpverified[$emailkey] : 0;
        if ($verifieduntil <= time()) {
            if (isset($SESSION->signupotpverified) && is_array($SESSION->signupotpverified)) {
                unset($SESSION->signupotpverified[$emailkey]);
            }
            $errors['otp'] = 'Please verify your email address with the OTP before creating your account.';
        }

        $errors += signup_validate_data($data, $files);
        return $errors;
    }

    public function export_for_template(renderer_base $output) {
        ob_start();
        $this->display();
        $html = ob_get_clean();
        return ['formhtml' => $html];
    }
}
