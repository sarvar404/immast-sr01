<?php
// Local front end for the installed Moodle Contact Form plugin.

require_once(__DIR__ . '/../../config.php');

$PAGE->set_url(new moodle_url('/local/immast/contact.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Contact IMMAST');
$PAGE->set_heading('Contact IMMAST');
$PAGE->navbar->add('Contact IMMAST');

echo $OUTPUT->header();
?>
<div class="container py-4">
    <h2>Contact IMMAST</h2>
    <p>Use this form for general questions, collaboration enquiries, careers and course information.</p>
    <form action="<?php echo (new moodle_url('/local/contact/index.php'))->out(); ?>" method="post">
        <input type="hidden" name="sesskey" value="<?php echo sesskey(); ?>">
        <div class="mb-3">
            <label class="form-label" for="contact-name">Your name</label>
            <input class="form-control" id="contact-name" name="name" type="text" maxlength="45" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="contact-email">Email address</label>
            <input class="form-control" id="contact-email" name="email" type="email" maxlength="60" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="contact-subject">Subject</label>
            <input class="form-control" id="contact-subject" name="subject" type="text" maxlength="80" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="contact-message">Message</label>
            <textarea class="form-control" id="contact-message" name="message" rows="6" required></textarea>
        </div>
        <button class="btn btn-primary" type="submit" name="submit" value="Send">Send message</button>
    </form>
</div>
<?php
echo $OUTPUT->footer();
