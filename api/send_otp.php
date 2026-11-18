<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/var/www/yancy_container/vendor/autoload.php';

header('Content-Type: application/json');

// ✅ Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

// ✅ Get name safely
$name = isset($_POST['name']) && !empty(trim($_POST['name']))
    ? trim($_POST['name'])
    : 'Guest';

// ✅ Generate secure OTP
// $otp = random_int(100000, 999999);

// $mail = new PHPMailer(true);

// try {
//     // ✅ Enable SMTP
//     $mail->isSMTP();

//     // ✅ Amazon SES SMTP
//     $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
//     $mail->SMTPAuth = true;

//     // ✅ SMTP Credentials (ROTATE THESE NOW)
//     $mail->Username = 'AKIAWJQ6BVXAPLWIZK3Y';
//     $mail->Password = 'BBd1PLa/hIdwy7BfGRG8RXlkjptDR4OhcOIGc4fB7k75';

//     // ✅ Encryption & Port
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
//     $mail->Port = 465;

//     // ✅ Sender & Receiver
//     $mail->setFrom('noreply@courses.immast.org', 'OTP Verification');
//     $mail->addAddress('ranjeet.yadav@intileo.com'); // change as needed

//     // ✅ Email Content
//     $mail->isHTML(true);
//     $mail->Subject = 'Your OTP Verification Code';
//     $mail->Body = "
//         <h3>Hello $name</h3>
//         <p>Your OTP is:</p>
//         <h2 style='color:#2e86de;'>$otp</h2>
//         <p>This OTP is valid for 5 minutes.</p>
//     ";

//     // ✅ Send Email
//     $mail->send();

//     // ✅ JSON Success Response
//     echo json_encode([
//         'status'  => 'success',
//         'message' => 'OTP sent successfully!',
//         'name'    => $name
//         // ❌ Do NOT return OTP in production
//     ]);
//     exit;

// } catch (Exception $e) {
//     // ✅ JSON Error Response
//     echo json_encode([
//         'status'  => 'error',
//         'message' => 'Mail sending failed',
//         'error'   => $mail->ErrorInfo
//     ]);
//     exit;
// }
my $otp = int(rand(900000)) + 100000;

my $status = Immastback::send_email(
    to      => 'ranjeet.yadav@intileo.com',
    subject => 'Your OTP Verification Code',
    body    => "Your OTP is: $otp\nValid for 5 minutes."
);

if ($status) {
    $c->render(json => { status => "success", message => "OTP sent" });
} else {
    $c->render(json => { status => "error", message => "OTP failed" });
}
