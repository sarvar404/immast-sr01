<?php
// Local IMMAST overview page based on the existing course calendar content.

require_once(__DIR__ . '/../../config.php');

$PAGE->set_url(new moodle_url('/local/immast/about.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('About IMMAST');
$PAGE->set_heading('About IMMAST');
$PAGE->navbar->add('About IMMAST');

echo $OUTPUT->header();
?>
<div class="container py-4">
    <h2>About IMMAST</h2>
    <p>IMMAST provides structured learning through hands-on practice sessions and supports healthcare professionals with ongoing advice from experienced faculty.</p>
    <p>Its course design combines virtual-reality simulation, teaching, live-procedure observation, practical training and group discussion. Courses are designed for physicians, surgeons, nurses, paramedical professionals and support staff.</p>
    <p>The local Moodle catalogue contains the courses currently available in this copy of the site.</p>
    <p><a class="btn btn-primary" href="<?php echo (new moodle_url('/course/index.php'))->out(); ?>">Browse courses</a></p>
</div>
<?php
echo $OUTPUT->footer();
