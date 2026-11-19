<?php
// Shows locally stored Moodle courses by their existing delivery-type tag.

require_once(__DIR__ . '/../../config.php');

$type = optional_param('type', 'all', PARAM_ALPHA);
$page = max(0, optional_param('page', 0, PARAM_INT));
$types = array(
    'hl' => 'Hybrid Learning',
    'os' => 'On-Site Courses',
    'fl' => 'Fellowships',
);
if ($type !== 'all' && !array_key_exists($type, $types)) {
    $type = 'all';
}

$heading = ($type === 'all') ? 'Courses and Workshops' : $types[$type];
$PAGE->set_url(new moodle_url('/local/immast/courses.php', array('type' => $type)));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title($heading);
$PAGE->set_heading($heading);
$PAGE->navbar->add($heading);

echo $OUTPUT->header();

$params = array('siteid' => SITEID);
$typecondition = '';
if ($type !== 'all') {
    $typecondition = ' AND tag.name = :tagname';
    $params['tagname'] = $type;
}

$fromsql = " FROM {course} course
          JOIN {course_categories} category ON category.id = course.category
          LEFT JOIN {tag_instance} taginstance
                 ON taginstance.itemid = course.id
                AND taginstance.itemtype = 'course'
                AND taginstance.component = 'core'
          LEFT JOIN {tag} tag ON tag.id = taginstance.tagid
         WHERE course.id <> :siteid
           AND course.visible = 1
           AND category.visible = 1" . $typecondition;
$totalcourses = $DB->count_records_sql('SELECT COUNT(DISTINCT course.id)' . $fromsql, $params);
$pagesize = 50;
$sql = "SELECT DISTINCT course.id, course.fullname, course.shortname, course.summary,
               course.category, category.name AS categoryname, category.sortorder
          " . $fromsql . "
      ORDER BY category.sortorder, course.fullname";
$courses = $DB->get_records_sql($sql, $params, $page * $pagesize, $pagesize);

echo html_writer::start_div('container py-4');
    echo html_writer::tag('p', 'Browse courses currently available in this local Moodle copy.');

if (!$courses) {
    echo $OUTPUT->notification('No courses with this delivery type are currently present in the local catalogue.', 'info');
    echo html_writer::link(new moodle_url('/course/index.php'), 'Browse all courses', array('class' => 'btn btn-primary'));
} else {
    echo html_writer::start_tag('div', array('class' => 'list-group'));
    foreach ($courses as $course) {
        $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
        echo html_writer::start_tag('a', array('href' => $courseurl, 'class' => 'list-group-item list-group-item-action'));
        echo html_writer::tag('h3', format_string($course->fullname), array('class' => 'h5 mb-1'));
        echo html_writer::tag('small', format_string($course->categoryname), array('class' => 'text-muted'));
        echo html_writer::end_tag('a');
    }
    echo html_writer::end_tag('div');
    echo html_writer::tag('p', 'Showing ' . min($totalcourses, $page * $pagesize + 1) . ' to ' . min($totalcourses, ($page + 1) * $pagesize) . ' of ' . $totalcourses . ' courses.', array('class' => 'text-muted mt-3')); 
    if ($page > 0) {
        echo html_writer::link(new moodle_url('/local/immast/courses.php', array('type' => $type, 'page' => $page - 1)), 'Previous', array('class' => 'btn btn-secondary me-2'));
    }
    if (($page + 1) * $pagesize < $totalcourses) {
        echo html_writer::link(new moodle_url('/local/immast/courses.php', array('type' => $type, 'page' => $page + 1)), 'Next', array('class' => 'btn btn-primary'));
    }
}

echo html_writer::end_div();
echo $OUTPUT->footer();
