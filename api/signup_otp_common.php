<?php

function signup_otp_json_response($status, array $response) {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, private');
    header('X-Content-Type-Options: nosniff');
    echo json_encode($response);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, private');
header('X-Content-Type-Options: nosniff');
ob_start();

try {
    require_once(__DIR__ . '/../config.php');
} catch (\Throwable $exception) {
    error_log('Signup OTP API could not initialize Moodle.');
    signup_otp_json_response(500, [
        'type' => 'ERROR',
        'message' => 'The verification service is temporarily unavailable.'
    ]);
}

function signup_otp_read_request() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        signup_otp_json_response(405, [
            'type' => 'ERROR',
            'message' => 'POST is required.'
        ]);
    }

    $raw = file_get_contents('php://input');
    if ($raw === false || strlen($raw) > 4096) {
        signup_otp_json_response(400, [
            'type' => 'ERROR',
            'message' => 'Invalid request.'
        ]);
    }

    $request = json_decode($raw);
    if (!is_object($request) || json_last_error() !== JSON_ERROR_NONE) {
        signup_otp_json_response(400, [
            'type' => 'ERROR',
            'message' => 'A valid JSON request is required.'
        ]);
    }

    if (!isset($request->sesskey) || !is_string($request->sesskey) ||
            $request->sesskey === '' || !confirm_sesskey($request->sesskey)) {
        signup_otp_json_response(403, [
            'type' => 'ERROR',
            'message' => 'The session has expired. Reload the signup page and try again.'
        ]);
    }

    return $request;
}

function signup_otp_email($request) {
    if (!isset($request->email) || !is_string($request->email)) {
        signup_otp_json_response(400, [
            'type' => 'ERROR',
            'message' => 'A valid email address is required.'
        ]);
    }

    $email = core_text::strtolower(trim($request->email));
    if (!validate_email($email)) {
        signup_otp_json_response(400, [
            'type' => 'ERROR',
            'message' => 'A valid email address is required.'
        ]);
    }

    if (email_is_not_allowed($email)) {
        signup_otp_json_response(400, [
            'type' => 'ERROR',
            'message' => 'This email address cannot be used for signup.'
        ]);
    }

    return $email;
}

function signup_otp_require_signup_enabled() {
    global $CFG;

    require_once($CFG->libdir . '/authlib.php');
    if (!signup_is_enabled()) {
        signup_otp_json_response(403, [
            'type' => 'ERROR',
            'message' => 'Signup is currently unavailable.'
        ]);
    }
}

function signup_otp_email_key($email) {
    return hash('sha256', core_text::strtolower(trim($email)));
}
