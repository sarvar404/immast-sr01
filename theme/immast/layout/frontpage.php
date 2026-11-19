<?php

defined('MOODLE_INTERNAL') || die;
// Get the HTML for the settings bits.
$html = theme_immast_get_html_for_settings($OUTPUT, $PAGE);

if (right_to_left()) {
	$regionbsid = 'region-bs-main-and-post';
} else {
	$regionbsid = 'region-bs-main-and-pre';
}

$courserenderer = $PAGE->get_renderer('core', 'course');
require_once(dirname(__DIR__, 3) . '/immast-custom/course_calendar_func.php');


$res  =  course_calendar_data();


// echo '<pre>';
// print_r($res);
// echo '</pre>';
// die(); 



$course_calendar = $res[0];
$course_by_month = $res[1];
$categories      = $res[2];
$all_categories  = $res[3];


$color_table  = array(
	'10' => '#2e75b6',
	'51' => '#9dc3e6',
	'14' => '#333f50',
	'52' => '#dae3f3',
	'54' => '#c5e0b4',
	'33' => '#ffd966',
	'32' => '#ff3b8a',
	'31' => '#ccff99',
	'50' => '#2e5c00',
	'23' => '#33cc33',
	'55' => '#708b39',
	'38' => '#a5a5a5',
	'56' => '#6e562c',
	'11' => '#d48482',
	'35' => '#f9f303',
	'9' => '#973007',
	'46' => '#00b0f0',
	'30' => '#afabab',
	'40' => '#70ad47',
	'41' => '#ffff99',
	'47' => '#ffff99',
	'57' => '#a6a6a6',
	'58' => '#22518a',
	'34' => '#00b0f0',
	'44' => '#333f50'
);



/* 
10 2e75b6
51 9dc3e6
14 333f50
52 dae3f3
54 c5e0b4
33 ffd966
32 ff3b8a
31 ccff99
50 2e5c00
23 33cc33
55 708b39
38 a5a5a5
56 6e562c
11 d48482
35 f9f303
9  973007
46 00b0f0
30 afabab
40 70ad47
41 ffff99
57 a6a6a6
58 22518a
*/

echo $OUTPUT->doctype()

?>


<html <?php echo $OUTPUT->htmlattributes(); ?>>

<head>
	<title><?php echo $OUTPUT->page_title(); ?></title>
	<link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
	<?php echo $OUTPUT->standard_head_html() ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://unpkg.com/@popperjs/core@2"></script>
	<script src="https://unpkg.com/tippy.js@6"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

	<?php echo $OUTPUT->standard_top_of_body_html() ?>

	<?php
	require_once(dirname(__FILE__) . '/includes/header.php');
	echo $headerlayout;
	?>

	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>






		<!--<div style="background-color: red; color: white; font-size: 26px; overflow: hidden; width: 100%; margin-top: 20px; position: relative;">
  <p style="white-space: nowrap; animation: scroll-left 30s linear infinite; padding: 10px; font-weight: bold;">
    Please note that the IMMAST contact number <span style="color:black">8655659845 </span>has been hacked. Please do not click any links sent from this number. Please ignore all messages from this number.
  </p>
</div>-->

<!-- Inline CSS for Scroll Animation -->
<script>
  const style = document.createElement('style');
  style.innerHTML = `
    @keyframes scroll-left {
      0% {
        transform: translateX(100%);
      }
      100% {
        transform: translateX(-100%);
      }
    }
  `;
  document.head.appendChild(style);
</script>







	<!--Custom theme header-->
	<div class="">
		<?php
		$toggleslideshow = theme_immast_get_setting('toggleslideshow');
		if ($toggleslideshow == 1) {
			require_once(dirname(__FILE__) . '/includes/slideshow.php');
		}
		?>   
	</div>
	<?php
	$whotitle = theme_immast_get_setting('whoweare_title');
	// $whodesc = theme_immast_get_setting('whoweare_description', 'format_html');
	$whodesc = "Please select the specialty you are interested in from the column on the left.
    Then move the cursor over highlighted dates to know training courses scheduled.
    Click on the name of a training course to know more about it.
    Watch this video to know <a href=\"https://youtu.be/iieBuvTTEqA?si=l8tPeEdZJUF3ye8y\" target=\"_blank\">how to register</a> for a course at IMMAST.
";
?>

	<div class="container-fluid inner-content">
		<div id="page" class="container"> <!-- open a -->
			<header id="page-header" class="clearfix">
				<?php echo $html->heading; ?>
				<div id="course-header">
					<?php echo $OUTPUT->course_header(); ?>
				</div>
			</header>
			<div id="page-content">
				<?php
				if (!empty($OUTPUT->blocks_for_region('side-pre'))) {
					$class = "col-md-9";
				} else {
					$class = "col-md-12";
				}
				?>
				<div id="<?php echo $regionbsid ?>" class="<?php echo $class; ?>">
					<!--
                    <?php
					echo $OUTPUT->course_content_header();
					echo $OUTPUT->main_content();
					echo $OUTPUT->course_content_footer();
					?>
	     -->

				</div>


				<div class="row" id="yui_3_17_2_1_1663580301027_21">
					<div class="col-sm-12 pb-5">
						<?php echo $whodesc; ?>
					</div>


					<!-- Left Area Start -->

					<div class="col-xxl-3 col-xl-3 col-lg-3 col-md-4 col-sm-12">

						<div class="coures-filter">
						 <!--	<div class="coures_title">Type</div> -->
<!--
							<div>
								<input class="course_filter" value="" type="radio" name="course_filter">
								<label class="form-check-label" for="course_filter1">
									Courses
								</label>
							</div>

-->						<div  style="display:none;">
								<input class="course_filter" value="hl" type="radio" name="course_filter">
								<label class="form-check-label" for="course_filter1">
									Hybrid Learning <span class="coures_tag red">hl</span>
								</label>
							</div>
							<div  style="display:none;" >
								<input class="course_filter" value="os" type="radio" name="course_filter">
								<label class="form-check-label" for="course_filter1">
									On-Site Courses <span class="coures_tag green">os</span>
								</label>
							</div>
							<div  style="display:none;">
								<input class="course_filter" value="fl" type="radio" name="course_filter">
								<label class="form-check-label" for="course_filter1">
									Fellowship &amp; Observer-ship <span class="coures_tag blue">fl</span>
								</label>
							</div>

							<div class="coures_title mt-1">Choose Specialty From Below</div>
							<ul class="coures-list">
								<style>
                                   .coures-list li {
  									background-color: white !important;
											       }

                                     .coures-list li a,
                                     .coures-list li span,
                                     .coures-list li a span {
                                       color: black !important;
                                     }

									</style>
								<?php

								// $res  =  course_calendar_data ();
								// $categories      = $res[2];
								// 	foreach ( $categories as $cat) {
								// 	echo sprintf ( "<div style='background-color: %s'>" , $color_table[$cat->id] ) ;
								// 		echo sprintf("<a href='/?redirect=0&category=%d'>" , $cat->id) ;
								// 		echo sprintf ('<img src="/immast-custom/static/icons/img-%d.png" width="20" > %s ' , $cat->id, $cat->name ); 
								// 		echo "</a>";
								// echo sprintf ( "<input type='checkbox' name='course_filter_multi' value='%d' class='course_filter_multi' ></input>" , $cat->id, $cat->id  ) ;
								// 	echo "</div>";
								// }



								foreach ($categories as $cat) {
									$li_class = $cat->category_type;
									$classes =  explode(',', $li_class);
									$all_classes = join(" ", array_map(function ($x) {
										return "category_$x";
									}, $classes));
									echo sprintf('<li style="background-color: %s;" class="course_categories %s" data-category-id="%s" ><a data-category-id="%s" class="category_link" href="/?redirect=0&category=%d"><img src="/immast-custom/static/icons/img-%d.png" width="20" height="20"> %s ', $color_table[$cat->id], $all_classes, $cat->id,  $cat->id,  $cat->id, $cat->id, $cat->name);
									foreach ($classes as $class) {
										echo sprintf('<span class="coures_tag tag_%s">%s</span> ', strtolower($class), strtolower($class));
									}
									echo sprintf('</a>');
									echo sprintf("<input type='checkbox' name='course_filter_multi' value='%d' class='course_filter_multi' ></input>", $cat->id, $cat->id);

									echo sprintf('</li>');
								}
		





								$keys = array_keys((array)$color_table);
                                                                foreach ($all_categories as $cat) {
                                                                        $li_class = $cat->categery_type;
                                                                        $classes =  explode(',', $li_class);
                                                                        $all_classes = join(" ", array_map(function ($x) {
                                                                                return "category_$x";
                                                                        }, $classes));
									$color_code_key =  $keys[ rand(1 , count($color_table) ) ] ;
									$cl = $color_table[ $color_code_key ] ;
									echo sprintf('<li style="background-color: %s;" class="course_categories %s" data-category-id="%s" ><a data-category-id="%s" class="category_link" href="/?redirect=0&category=%d"><img src="/immast-custom/static/icons/img-%d.png" width="20" height="20"> %s ', $cl , $all_classes, $cat->id,  $cat->id,  $cat->id, 35, $cat->name);
                                                                        foreach ($classes as $class) {
                                                                                echo sprintf('<span class="coures_tag tag_%s">%s</span> ', strtolower($class), strtolower($class));
                                                                        }
                                                                        echo sprintf('</a>');
                                                                        echo sprintf("<input type='checkbox' name='course_filter_multi' value='%d' class='course_filter_multi' ></input>", $cat->id, $cat->id);

                                                                        echo sprintf('</li>');
                                                                }









						?>
							</ul>
                            <script>
                             document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".coures-list li").forEach(function (li) {
    li.style.backgroundColor = "white";
    const link = li.querySelector("a");
    if (link) {
      link.style.color = "black";
    }
    const span = li.querySelector("span");
    if (span) {
      span.style.color = "black";
    }
  });
});

								</script>

						</div>

					</div>

					<!-- Left Area End -->

					<!-- Right Area Start -->

					<div class="col-xxl-9 col-xl-9 col-lg-9 col-md-8 col-sm-12">
						<!-- Content Area Start -->

						


						<div class="category_boxes">

							<?php
							// $res  =  course_calendar_data ();
							// $categories      = $res[2];
							//     foreach ( $categories as $cat) {
							// 	echo sprintf ( "<div style='background-color: %s'>" , $color_table[$cat->id] ) ;
							// 		echo sprintf("<a href='/?redirect=0&category=%d'>" , $cat->id) ;
							// 		echo sprintf ('<img src="/immast-custom/static/icons/img-%d.png" width="20" > %s ' , $cat->id, $cat->name ); 
							// 		echo "</a>";
							// 	echo sprintf ( "<input type='checkbox' name='course_filter_multi' value='%d' class='course_filter_multi' ></input>" , $cat->id, $cat->id  ) ;
							// 	echo "</div>";
							// }
							?>
						</div>
						<p><strong><span style="font-size: xx-large;">Courses calendar</span></strong></p>
						<p><strong><span style="font-size: xx-medium;">Legends: [] around date indicates multiple courses.</span></strong></p>
						<div class="row">
							<pre>
<?php
// echo print_r ( $course_by_month , 1);
?>
</pre>


							<?php echo create_calendars(["year" => date('Y'), "course_by_month" => $course_by_month])  ?>
						</div>
						<!-- Right Area End -->
					</div> <!-- close 3 -->
				</div> <!-- close - 2 -->
			</div> <!-- close 1 -->
			
			<script nonce="RmGmR5d8fs76kDZNRcrK">
				var month = 1;
				var course_calendar;
				


				var color_table = {
					'30': '#afabab',
					'10': '#2e75b6',
					'51': '#9dc3e6',
					'14': '#333f50',
					'52': '#dae3f3',
					'54': '#c5e0b4',
					'33': '#ffd966',
					'32': '#ff3b8a',
					'31': '#ccff99',
					'50': '#2e5c00',
					'23': '#33cc33',
					'55': '#708b39',
					'38': '#a5a5a5',
					'56': '#6e562c',
					'11': '#d48482',
					'35': '#f9f303',
					'9': '#973007',
					'46': '#00b0f0',
					'40': '#70ad47',
					'41': '#ffff99',
					'47': '#ffff99',
					'57': '#a6a6a6',
					'58': '#22518a',
					'34': '#00b0f0',
					'44': '#333f50',


				};

				$(function() {
					$("#menu").menu();
				});


				function getJsonFromUrl(url) {
					if (!url) url = location.search;
					var query = url.substr(1);
					var result = {};
					query.split("&").forEach(function(part) {
						var item = part.split("=");
						result[item[0]] = decodeURIComponent(item[1]);
					});
					return result;
				}

				<!--init JS-- >

				course_calendar = <?php echo json_encode($course_calendar,  JSON_PRETTY_PRINT); ?>;

				function contrastColor(red, green, blue) {
					var brightness;
					brightness = (299* red) + (587 * green) + (114 * blue)
					brightness = brightness / 255000;
					console.log("bright: ", brightness)
					return (brightness > 0.5) ? '#000000' : '#ffffff';
					// brightness = (red * 299) + (green * 587) + (blue * 114);
					// values range from 0 to 1
					// anything greater than 0.5 should be bright enough for dark text
					// if (brightness >= 0.5) {
					// 	return "#000000";
					// } else {
					// 	return "#ffffff";
					// }
				}

				// function getRGB(str) {
				// var match = str.match(/rgba?\((\d{1,3}), ?(\d{1,3}), ?(\d{1,3})\)?(?:, ?(\d(?:\.\d?))\))?/);
				// return match
				// console.log(match)
				// return match ? {
				// 	red: match[1],
				// 	green: match[2],
				// 	blue: match[3]
				// } : {};
				// }

				// console.log(getRGB("rgb(211, 211, 211)"));


				/*
				$(function() {
						var all_cats = new Array();

						course_calendar.map ( function f(x) {
							all_cats.push ( x.category_id);
						});
						// console.log("all cats is: " + JSON.stringify (all_cats));

						const urlParams = new URLSearchParams(window.location.search);
						let category_id_param = urlParams.get('category')

						var category_id = '';

						clearAllHighlight();
						if (category_id_param) {
							category_id = category_id_param;
							highlightCourses(category_id, course_calendar, color_table);
						} else {
							// highlightCourses(category_id, course_calendar, color_table);
							all_cats.map ( function f(x) {
								// console.log(`highlighing category: ${x}`);
								highlightCourses(x , course_calendar, color_table);

							});
						}

				});
				*/
				$('.course_filter_multi').on('click', function(e) {
					clearAllHighlight();
					var cnt = 1;
					$("input[name='course_filter_multi']:checked").each(
						function(i) {
							if (cnt > 2) {
								$(this).prop('checked', false)
								alert("You can choose to see a maximum of two specialties at a time")
							} else {
								var category_id = $(this).val();
								console.log( category_id );
								highlightCourses(category_id, course_calendar, color_table);
							}
							cnt++;
						}
					);
				});

				function hex_to_RGB(hex) {
					if (hex !== undefined){
						var m = hex.match(/^#?([\da-f]{2})([\da-f]{2})([\da-f]{2})$/i);
						return {
							r: parseInt(m[1], 16),
							g: parseInt(m[2], 16),
							b: parseInt(m[3], 16)
						};
					}else{
						return {}
					}
				}
				// ex.
				// hex_to_RGB('#FF0110') // {r: 255, g: 1, b: 16}



				$('.course_filter').on('click', function() {
					var value = $("input[name='course_filter']:checked").val()
					// console.log('hiding all elements with class .course_categories');

					$('.course_categories').hide();
					if (value === "") {
						clearAllHighlight();
						$('.course_categories').show();
						highlightCourses('', course_calendar, color_table);
					} else {
						clearAllHighlight();
						$('.category_' + value).each(function() {
							$(this).show();
							var category_id = $(this).attr('data-category-id');
							highlightCourses(category_id, course_calendar, color_table);
						});
					}
				});

				$('.category_link').on('click', function(event) {
					event.preventDefault();
					var category_id = $(this).attr('data-category-id');
					clearAllHighlight();
					highlightCourses(category_id, course_calendar, color_table);

				});
				$("input[name='course_filter_multi']").each(
					function(i) {
						var category_id = $(this).val();
						rgb = hex_to_RGB(color_table[category_id])

						bg = contrastColor(rgb['r'],rgb['g'],rgb['b'])
						a = $(this).parent().children("a")
						a.css("color",bg)
						console.log(bg)
						
					}
				);
			</script>
<script src="/theme/immast/javascript/immast_calendar.js"></script>
			<script>
				if (typeof clearAllHighlight !== 'function') {
					function clearAllHighlight() {
						$('.calendar-day').css('background-color', '');
						$('.calendar-day').css('color', '#000');
						$('.calendar-day').each(function() {
							var value = $(this).text().trim();
							if (value && !isNaN(value)) {
								$(this).html(value);
							}
						});
					}
				}
				if (typeof highlightCourses !== 'function') {
					function highlightCourses(category_id, course_calendar, color_table) {
						$('.calendar-day').css('background-color', '');
					}
				}
			</script>



		</div> <!-- closed extra -->
	</div>


	<!--Custom theme footer starts -->
	<?php
	require_once(dirname(__FILE__) . '/includes/footer.php');
	echo $footerlayout;
	?>
	<!--Custom theme footer ends-->

</body> <!-- body ends here -->

</html>
