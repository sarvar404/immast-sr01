<?php

global $DB;

// Standard config file and local library.
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/course_calendar_func.php');


// Setting up the page.
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout("simple");
$PAGE->set_url(new moodle_url('/local/immast/course_calendar.php'));

// Ouput the page header.
echo $OUTPUT->header();

$res  =  course_calendar_data ();

$course_calendar = $res[0];
$course_by_month = $res[1];
$categories      = $res[2];


// format the course calender as a JS object.
	
?>

<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- content area -->



<div class="container-fluid inner-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 pb-5">

<p>Committed healthcare professionals always ask themselves this question, “How can I improve patient care?” Over 14000 professionals have found their answer at IMMAST.</p>
        <strong>IMMAST</strong> since inception with outstanding feedback from all of the participants: <strong>IMMAST</strong> received the participants from more than 30 countries.</p>

<p><strong>IMMAST</strong> offers the promise of not just providing structured learning through unique hands on practice sessions, but also supports its International and National alumni with ongoing advice from faculty which includes the who's who of medicine and surgery.</p>

<p>The Course Design & Delivery at <strong>IMMAST</strong> ensure a scientific & comprehensive curriculum, an optimal mix of virtual reality simulation, didactics, live procedure observation, hands-on practice and group discussions, creating an unmatched learning experience. <strong>IMMAST</strong> is one of the handful of centres in the world to focus on procedure, and not product-based training. At <strong>IMMAST</strong>, courses are designed to teach the complete healthcare team the physician, the surgeon, the nurses, paramedical and support staff. The aim is to create safe healthcare settings - clinical and surgical, with confident teams that are well versed in standard procedures.</p>
      </div>
      
      
<!-- Left Area Start -->
      
      <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-5 col-sm-12">
      	
        <div class="coures-filter">
      	<div class="coures_title">Type</div>
        
        <div>
          <input class="course_filter" value="" type="radio" name="course_filter" >
          <label class="form-check-label" for="course_filter1">
            Courses
          </label>
        </div>
        <div>
          <input class="course_filter" value="hl" type="radio" name="course_filter" >
          <label class="form-check-label" for="course_filter1">
            Hybrid Learning <span class="coures_tag red">hl</span>
          </label>
        </div>
        <div>
          <input class="course_filter" value="os"  type="radio" name="course_filter" >
          <label class="form-check-label" for="course_filter1">
            On-Site Courses <span class="coures_tag green">os</span>
          </label>
        </div>
        <div>
          <input class="course_filter" value="fl" type="radio" name="course_filter" >
          <label class="form-check-label" for="course_filter1">
            Fellowship & Observer-ship <span class="coures_tag blue">fl</span>
          </label>
        </div>
        
        <div class="coures_title mt-4">Choose a Specialty</div>
        <ul class="coures-list">
<?php
	foreach ( $categories as $cat) {
	  $li_class = $cat->category_type ;
	  $classes =  explode (',' , $li_class ) ;
	  $all_classes = join ( " " , array_map ( function ($x) { return "category_$x" ; } , $classes) ) ;
          echo sprintf ('<li class="course_categories %s" data-category-id="%s" ><a data-category-id="%s" class="category_link" href=""><img src="/local/static/icons/img-%d.png" width="20" height="20"> %s ' , $all_classes, $cat->id,  $cat->id,  $cat->id, $cat->name );
	  foreach ( $classes as $class) {
		  echo sprintf ('<span class="coures_tag tag_%s">%s</span> '  , strtolower($class) , strtolower($class) );
	  }
          echo sprintf ('</a></li>');
	}
?>
        </ul>
      </div>
      
      </div>
      
<!-- Left Area End -->

<!-- Right Area Start -->
      
      <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-7 col-sm-12">
      
<!-- Content Area Start --> 

     
        
        <p><strong><span style="font-size: xx-large;">Courses calendar</span></strong></p>

	<div class="calendar_container">
		<?php echo create_calendars([ "year" => "2022" , "course_by_month" => $course_by_month  ] )  ?>
	</div>

      </div>
<!-- Right Area End -->      
    </div>
  </div>
</div>
<!-- content area --> 
<script>

var month  = 1;
var course_calendar;
var course_id2color={};

$(function() {
		// initialize course_calendar 
		course_calendar = <?php echo json_encode( $course_calendar,  JSON_PRETTY_PRINT ); ?>;
		course_id2color = course2color (course_calendar);
		var category_id = '';
		clearAllHighlight();
		highlightCourses(category_id , course_calendar, course_id2color);

});


$('.course_filter').on('click' , function() {
	var value = $("input[name='course_filter']:checked").val()
	console.log('hiding all elements with class .course_categories');

	$('.course_categories').hide();
	if ( value === "") {
		clearAllHighlight();
		$('.course_categories').show();
		highlightCourses('', course_calendar, course_id2color);
	} else {
		clearAllHighlight();
		$('.category_' + value ).each( function() {
			$(this).show();
			var category_id = $(this).attr('data-category-id');
			highlightCourses(category_id, course_calendar, course_id2color);
		});
	}
});

$('.category_link').on ('click' , function(event) {
	event.preventDefault();
	var category_id = $(this).attr('data-category-id');
	clearAllHighlight();
	highlightCourses(category_id, course_calendar, course_id2color);

});


</script>


<?php
// Output the page footer.
echo $OUTPUT->footer();
?>
