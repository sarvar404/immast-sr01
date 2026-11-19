<?php

require_once(__DIR__ . '/signup_otp_common.php');

try {
    global $CFG, $SESSION, $SITE;

    $request = signup_otp_read_request();
    signup_otp_require_signup_enabled();
    $email = signup_otp_email($request);
    $emailkey = signup_otp_email_key($email);
    $now = time();

    $entries = isset($SESSION->signupotp) && is_array($SESSION->signupotp) ? $SESSION->signupotp : [];
    $previous = isset($entries[$emailkey]) && is_array($entries[$emailkey]) ? $entries[$emailkey] : [];
    $windowstart = isset($previous['windowstart']) ? (int)$previous['windowstart'] : $now;
    $sendcount = isset($previous['sendcount']) ? (int)$previous['sendcount'] : 0;

    if ($now - $windowstart >= 3600) {
        $windowstart = $now;
        $sendcount = 0;
    }

    if (!empty($previous['sentat']) && $now - (int)$previous['sentat'] < 60) {
        signup_otp_json_response(429, [
            'type' => 'ERROR',
            'message' => 'Please wait before requesting another code.'
        ]);
    }
    if ($sendcount >= 5) {
        signup_otp_json_response(429, [
            'type' => 'ERROR',
            'message' => 'Too many codes requested. Try again later.'
        ]);
    }

    $otp = (string)random_int(100000, 999999);
    $otpHash = password_hash($otp, PASSWORD_DEFAULT);
    if ($otpHash === false) {
        error_log('Signup OTP could not be hashed.');
        signup_otp_json_response(500, [
            'type' => 'ERROR',
            'message' => 'The verification service is temporarily unavailable.'
        ]);
    }

    // Keep signup email delivery independent of user creation.
    $recipient = (object)[
        'id' => -1,
        'email' => $email,
        'firstname' => '',
        'lastname' => '',
        'username' => 'signup',
        'auth' => 'manual',
        'suspended' => 0,
        'deleted' => 0,
        'mnethostid' => $CFG->mnet_localhost_id,
        'maildisplay' => core_user::MAILDISPLAY_EVERYONE,
        'mailformat' => FORMAT_PLAIN
    ];
    $body = "Your signup verification code is: {$otp}\n\nThis code expires in 5 minutes.";
    if (!email_to_user($recipient, core_user::get_noreply_user(), 'Signup email verification code', $body)) {
        signup_otp_json_response(503, [
            'type' => 'ERROR',
            'message' => 'The verification service could not send email right now.'
        ]);
    }

    $entries[$emailkey] = [
        'hash' => $otpHash,
        'expires' => $now + 300,
        'sentat' => $now,
        'windowstart' => $windowstart,
        'sendcount' => $sendcount + 1,
        'attempts' => 0
    ];
    $SESSION->signupotp = $entries;
    if (isset($SESSION->signupotpverified) && is_array($SESSION->signupotpverified)) {
        unset($SESSION->signupotpverified[$emailkey]);
    }

    signup_otp_json_response(200, [
        'type' => 'OK',
        'message' => 'Verification code sent to your email address.'
    ]);
} catch (\Throwable $exception) {
    error_log('Signup OTP email request failed.');
    signup_otp_json_response(500, [
        'type' => 'ERROR',
        'message' => 'The verification service is temporarily unavailable.'
    ]);
}
