<?php

require_once(__DIR__ . '/signup_otp_common.php');

try {
    global $SESSION;

    $request = signup_otp_read_request();
    signup_otp_require_signup_enabled();
    $email = signup_otp_email($request);
    $emailkey = signup_otp_email_key($email);
    $otp = isset($request->otp) && is_string($request->otp) ? trim($request->otp) : '';

    $entries = isset($SESSION->signupotp) && is_array($SESSION->signupotp) ? $SESSION->signupotp : [];
    if (!isset($entries[$emailkey]) || !is_array($entries[$emailkey])) {
        signup_otp_json_response(400, [
            'type' => 'ERROR',
            'message' => 'Request a verification code before entering it.'
        ]);
    }

    $entry = $entries[$emailkey];
    if (empty($entry['expires']) || (int)$entry['expires'] <= time()) {
        unset($entries[$emailkey]);
        $SESSION->signupotp = $entries;
        signup_otp_json_response(400, [
            'type' => 'ERROR',
            'message' => 'The verification code expired. Request a new code.'
        ]);
    }

    $attempts = isset($entry['attempts']) ? (int)$entry['attempts'] : 0;
    if ($attempts >= 5) {
        unset($entries[$emailkey]);
        $SESSION->signupotp = $entries;
        signup_otp_json_response(429, [
            'type' => 'ERROR',
            'message' => 'Too many incorrect codes. Request a new code.'
        ]);
    }

    $entry['attempts'] = $attempts + 1;
    if (!preg_match('/^\d{6}$/D', $otp) || !password_verify($otp, $entry['hash'])) {
        if ($entry['attempts'] >= 5) {
            unset($entries[$emailkey]);
        } else {
            $entries[$emailkey] = $entry;
        }
        $SESSION->signupotp = $entries;
        signup_otp_json_response(400, [
            'type' => 'ERROR',
            'message' => 'The verification code is incorrect.'
        ]);
    }

    unset($entries[$emailkey]);
    $SESSION->signupotp = $entries;
    if (!isset($SESSION->signupotpverified) || !is_array($SESSION->signupotpverified)) {
        $SESSION->signupotpverified = [];
    }
    $SESSION->signupotpverified[$emailkey] = time() + 600;

    signup_otp_json_response(200, [
        'type' => 'OK',
        'message' => 'Email address verified.'
    ]);
} catch (\Throwable $exception) {
    error_log('Signup OTP verification failed.');
    signup_otp_json_response(500, [
        'type' => 'ERROR',
        'message' => 'The verification service is temporarily unavailable.'
    ]);
}
