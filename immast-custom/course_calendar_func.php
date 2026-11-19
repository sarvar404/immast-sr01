
<?php


function course_calendar_data() {

	global $DB;

	// $sql = "select cat.id,cat.name , string_agg(distinct mt.name,',') as category_type from mdl_tag_instance mti left join mdl_tag mt on (mti.tagid=mt.id) join  mdl_course c on(c.id=mti.itemid) join mdl_course_categories cat on ( c.category=cat.id)  group by cat.id,cat.name order by cat.name; ";
	$sql = "select cat.id,cat.name , string_agg(distinct mt.name,',') as category_type from mdl_tag_instance mti left join mdl_tag mt on (mti.tagid=mt.id) left join  mdl_course c on(c.id=mti.itemid and c.visible=1 ) left join mdl_course_categories cat on ( c.category=cat.id) where cat.visible=1  and length (mt.name) = 2  group by cat.id,cat.name,cat.sortorder order by cat.sortorder ; ";
	$categories = $DB->get_recordset_sql($sql);


	// $all_categories = $DB->get_recordset_sql("select id,name from mdl_course_categories where id not in ( select distinct category from mdl_course ) and visible=1 order by sortorder ;");


	$all_categories = $DB->get_recordset_sql("select id,name from mdl_course_categories where id not in ( select distinct category from mdl_course where visible=1 ) and visible=1 order by sortorder ;");
	// get the course calender from the DB
	
	$sql = "select cat.id as category_id , date(to_timestamp(startdate)) as startdate, date(to_timestamp(enddate)) as enddate, c.id as course_id, shortname,fullname  from mdl_tag_instance mti left join mdl_tag mt on (mti.tagid=mt.id) join  mdl_course c on(c.id=mti.itemid and c.visible=1) join mdl_course_categories cat on ( c.category=cat.id)  order by startdate; ";

	$ccd = $DB->get_recordset_sql($sql);


	$i = 0;
	foreach ( $ccd as $entry) {
		$category_id = $entry->	category_id ;

		$date_parts = explode("-", $entry->startdate );

		// error_log ( sprintf ("year: %s, month: %s, day: %s" , $date_parts[0] , $date_parts[1] , $date_parts[2])) ;

		$year_month = $date_parts[0] . '_'  . $date_parts[1] ;

		$course_by_month[$year_month] .= sprintf ( "%s|%s|%s," , $entry->course_id, $entry->shortname, $entry->category_id);

		$course_calendar[$i] =  $entry ;
		$i++;
	}

	return array ($course_calendar, $course_by_month, $categories, $all_categories) ;


}







function create_calendars($params)
{
    $year = $params["year"];
    // $months = $params["course_by_month"];
    $course_by_month = $params["course_by_month"];
    $months = $params["months"];

    if ($months == 'current') {
        $start = 12;
        $end = 12;
    }

    if (!isset($year)) {
        $year = date('Y');
    }

    $all_calendars = '';
    $month = strtotime(date('Y-m-d'));
    $curr_month = date("m");
    for ($i = 0; $i <= 2; $i++) {
        $current_month = $curr_month + $i ;
        $cur_mon  = $current_month > 12 ? $current_month -  12 : $current_month;
        $j = $cur_mon -1 ;
        $all_calendars .= '<div class="col-sm-6 col-md-6 col-lg-4 col-xl-4 calendar">' . "\n";
        // date_default_timezone_set('GMT');
        $month_name = date('F-Y', $month);
        // echo "Date: " . date('Y', $month) . "\n";
        // echo $j;
        // echo "Date: " . $month . "\n";
        //echo "J: " . $j . "\n";
        $all_calendars .= '<div class="month_year">' . "$month_name" . '</div>' . "\n";
        $all_calendars .= '<div class="calendar_table">' . "\n";
        $all_calendars .= build_html_calendar(date('Y', $month), $j ) . "\n";
        $all_calendars .= '</div>' . "\n";
        $year_month = date('Y_m', $month);
        $courses = explode(',', $course_by_month[$year_month]);
        $all_calendars .= '</div>';
        $month = strtotime("+1 month", $month);

    }
    return $all_calendars;
}








/*

function create_calendars ($params) {
	$year = $params["year"];
	// $months = $params["course_by_month"];
	$course_by_month = $params["course_by_month"];
	$months = $params["months"] ;

	if ( $months == 'current') {
		$start = 12;
		$end = 12;
	}

	if ( ! isset( $year)) {
		$year = date('Y');
	}

	$all_calendars = '';
	$month = strtotime(date('Y-m-d') );
  $curr_month = date("m");
  for ($i = 0 ;$i <= 2 ; $i++) {
   	 $current_month = $curr_month + $i;
	  $j = $current_month -1;
		$all_calendars .=  '<div class="col-sm-6 col-md-6 col-lg-4 col-xl-4 calendar">' . "\n"  ;
    
    $month_name = date('F-Y', $month) ;
        
		$all_calendars .=  '<div class="month_year">' . "$month_name" . '</div>'  . "\n" ;
    
		$all_calendars .=  '<div class="calendar_table">'  . "\n" ;

    
		$all_calendars .=  build_html_calendar( date('Y', $month) , $j ) . "\n" ;
		$all_calendars .=  '</div>'  . "\n" ;
    
    $year_month =  date('Y_m', $month) ;
    
    
		$courses = explode ( ',' , $course_by_month[ $year_month ] )  ;
		$all_calendars .=  '</div>' ;

	         $month = strtotime("+1 month", $month);
	}

	return $all_calendars;

}

*/
 
/**
 * Returns the calendar's html for the given year and month.
 *
 * @param $year (Integer) The year, e.g. 2015.
 * @param $month (Integer) The month, e.g. 7.
 * @param $events (Array) An array of events where the key is the day's date
 * in the format "Y-m-d", the value is an array with 'text' and 'link'.
 * @return (String) The calendar's html.
 */
function build_html_calendar($year, $month, $events = null) {

  // CSS classes
  $css_cal = 'calendar';
  $css_cal_row = 'calendar-row';
  $css_cal_day_head = 'calendar-day-head';
  $css_cal_day = 'calendar-day';
  $css_cal_day_number = 'day-number';
  $css_cal_day_blank = 'calendar-day-np';
  $css_cal_day_event = 'calendar-day-event';
  $css_cal_event = 'calendar-event';

  // error_log(sprintf("::::::::::::::>>>>>>>>>>>>>>>>> %s",strtotime(date("Y",$month))));


  // Table headings
  $headings = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];

  // Start: draw table
  $calendar =
    "<table cellpadding='0' cellspacing='0' >" .
    "<tr class='{$css_cal_row}'>" .
    "<td class='{$css_cal_day_head}'>" .
    implode("</td><td class='{$css_cal_day_head}'>", $headings) .
    "</td>" .
    "</tr>";

    
    // Days and weeks
  $running_day = date('N', mktime(0, 0, 0, $month+1, 1, $year));
  $days_in_month = date('t', mktime(0, 0, 0, $month +1, 1, $year));
  //error_log(sprintf(">>>>>>>>>>>>>>>>>>>>>>>>>>>> %s ", $month));

  // Row for week one
  $calendar .= "<tr class='{$css_cal_row}'>";
  
  // Print "blank" days until the first of the current week
  for ($x = 1; $x < $running_day; $x++) {
    $calendar .= "<td class='{$css_cal_day_blank}'> </td>";
  }
  
  // Keep going with days...
  for ($day = 1; $day <= $days_in_month; $day++) {
    
    // Check if there is an event today
    $cur_date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
    $draw_event = false;
    if (isset($events) && isset($events[$cur_date])) {
      $draw_event = true;
    }

    $date_str = sprintf ( "%4d_%02d_%02d" , $year, $month + 1, $day) ;

    // Day cell
    $calendar .= $draw_event ?
      "<td class='{$css_cal_day} {$css_cal_day_event} date_$date_str'>" :
      "<td class='{$css_cal_day} date_$date_str'>";

    // Add the day number

    $calendar .= "<div class='{$css_cal_day_number} '>" . $day . "</div>";

    // Insert an event for this day
    if ($draw_event) {
      $calendar .=
        "<div class='{$css_cal_event}'>" .
        "<a href='{$events[$cur_date]['href']}'>" .
        $events[$cur_date]['text'] .
        "</a>" .
        "</div>";
    }


    // error_log(sprintf(">>>>>>>>>>>>>> Data is : %s",$events));

    // Close day cell
    $calendar .= "</td>";

    // New row
    if ($running_day == 7) {
      $calendar .= "</tr>";
      if (($day + 1) <= $days_in_month) {
        $calendar .= "<tr class='{$css_cal_row}'>";
      }
      $running_day = 1;
    }

    // Increment the running day
    else {
      $running_day++;
    }

  } // for $day

  // Finish the rest of the days in the week
  if ($running_day != 1) {
    for ($x = $running_day; $x <= 7; $x++) {
      $calendar .= "<td class='{$css_cal_day_blank}'> </td>";
    }
  }

  // Final row
  $calendar .= "</tr>";

  // End the table
  $calendar .= '</table>';

  // All done, return result
  return $calendar;
}

?>

