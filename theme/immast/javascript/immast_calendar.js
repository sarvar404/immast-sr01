

function contrastColor(red, green, blue) {
	var brightness;
	brightness = (red * 299) + (green * 587) + (blue * 114);
	brightness = brightness / 255000;

	// values range from 0 to 1
	// anything greater than 0.5 should be bright enough for dark text
	if (brightness >= 0.5) {
		return "#000000";
	} else {
		return "#ffffff";
	}
}

function contrastColor2(red, green, blue) {
	var brightness;
	brightness = (299* red) + (587 * green) + (114 * blue)
	brightness = brightness / 255000;
	console.log("bright: ", brightness)
	return (brightness > 0.5) ? '#000000' : '#ffffff';

}


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

function highlightCourses(category_id,course_calendar,color_table ) {

	var month_courses = {};

	$('.short_courses').hide();

	// populate courses of each single day.
	var day_courses = {};
        for (i = 0 ; i < course_calendar.length ; i++) {
                var entry = course_calendar[i];
                var startdate = entry.startdate ;
                var enddate   = entry.enddate ;
                var course_id = entry.course_id ;

                var sdate = moment(startdate);
                var edate = moment(enddate);
                for (var d=sdate ; d <= edate ; d.add(1,'days') ) {
                        var ymd = d.format ('YYYY_MM_DD');
			if (day_courses[ymd]) {
				day_courses[ymd][course_id]= entry;
			} else {
				day_courses[ymd] = {} ;
				day_courses[ymd][course_id]= entry;
			}
		}
	}
	// done.


	// console.log( 'day_courses is:' + JSON.stringify(day_courses , ' ' , 2)) ;

        for (i = 0 ; i < course_calendar.length ; i++) {
                var entry = course_calendar[i];
                if ( category_id && entry.category_id != category_id) {
                        continue;
                }
                var course_name   = entry.fullname ;
                var course_id = entry.course_id ;
                var category_id = entry.category_id ;
		var bgcolor = color_table[category_id] ;

                var startdate = entry.startdate ;
                var enddate   = entry.enddate ;

                var sdate = moment(startdate);
                var edate = moment(enddate);

                for (var d=sdate ; d <= edate ; d.add(1,'days') ) {
                        var date_class = 'date_' +  d.format ('YYYY_MM_DD');
                        $('.' + date_class).css( 'background-color' , bgcolor );
						rgb = hex_to_RGB(color_table[category_id])
						$('.' + date_class).css('color' , contrastColor2(rgb['r'],rgb['g'],rgb['b']) );

			var day = d.format('DD');

			var courses = new Array();
			var num_courses = 0;

			var ymd = d.format ('YYYY_MM_DD'); 
                        var mdy =  d.format ('DD/MM/YYYY');
			if ( day_courses[ymd] ) {
				// courses = day_courses[ymd];
				Object.keys(day_courses[ymd]).forEach(
					function (key) { 
						courses.push ( day_courses[ymd][key] );
					}
				);
				num_courses = courses.length ;
			}

			if ( num_courses == 1) {
				// $('.' + date_class).html (`<a style="color:${fgcolor}"  href="/course/view.php?id=${course_id}">${day}</a>`);
				$('.' + date_class).html (`<a  href="/course/view.php?id=${course_id}">${day}</a>`);
				tippy( '.' + date_class  , { content: `${course_name}` });
			}
			if ( num_courses > 1) {
				// var html = `<ul id='course_menu_${ymd}' style="color:${fgcolor};"  >` ;
				var html = `<ul id='course_menu_${ymd}'  >` ;
				html += `<li><div style="font-size: 12px;text-align: left"  >Courses on ${mdy}</div><ul>`;
				courses.map ( function f(i) {
					var fullname = i['fullname'];
					var cid = i['course_id'];
					html += `<li style="font-size: 12px;text-align: left" ><div><a href="/course/view.php?id=${cid}"> ${fullname}</a></div></li>`;
				});
				html += '</ul></li>';
				$('.' + date_class).html (`[${day}]`);
				tippy( '.' + date_class  , { content: `${html}` ,  allowHTML: true,  interactive: true   });
			}

                        $( '#short_course_' + course_id).show() ;

                        tippy( '#short_course_' + course_id  , { content: course_name });
						// rgb = hex_to_RGB(color_table[category_id])
                        // $( '#course_category_' + category_id).css('color' , contrastColor2(rgb['r'],rgb['g'],rgb['b']) );

                        var mm = parseInt( d.format ('MM') , 12) ;
			month_courses[mm] = 1;
                }

        }
	for ( i=1; i <= 12 ; i++) {
		if ( month_courses[i]) {
			$(`#month_footer_${i}`).show();

		} else {
			$(`#month_footer_${i}`).hide();
		}
	}
}

function clearAllHighlight() {
	$('.calendar-day'  ).css( 'background-color' , "");
	$('.calendar-day'  ).css( 'color' , "#000");
	var sdate = moment();
	var edate = moment ( '2023-12-31') ;  // hardcoded !!

	// alert(`sdate: ${sdate} edate: ${edate}` );
	for (var d=sdate ; d <= edate ; d.add(1,'days') ) {
		var date_class = 'date_' +  d.format ('YYYY_MM_DD');
		var day = d.format ('DD');
		$('.' + date_class).css( 'background-color' , "" );
		$('.' + date_class).css( 'color' , "#000000" );
		$('.' + date_class).html (day);
		// console.log(`setting class for day: ${date_class} -> ${day}`);
	}
}

function course2color(course_calendar) {
	var course_id2color={};
	var colors = [
		'#EAE4E9', '#FFF1E6', '#FDE2E4', '#FAD2E1', '#E2ECE9', '#BEE1E6', '#F0EFEB', '#DFE7FD', '#CDDAFD',
		'#D0F5FC', '#EDE1FF', '#DCDEFF', '#C9DEFE', '#FCEDD0', '#F2FBA3', '#F4D9FD', '#E2FFD7', '#D6FFC2'
	];
	var color_counter = 0;
	for (i = 0 ; i < course_calendar.length ; i++) {
		var entry = course_calendar[i];
		var course_id = entry.course_id ;
		if ( ! course_id2color[course_id] ) {
			course_id2color[course_id] = colors[color_counter];
			color_counter++;
		}
	}
	return course_id2color;
}

function category2color(course_calendar) {
	var category_id2color={};
	var colors = [
		'#EAE4E9', '#FFF1E6', '#FDE2E4', '#FAD2E1', '#E2ECE9', '#BEE1E6', '#F0EFEB', '#DFE7FD', '#CDDAFD',
		'#D0F5FC', '#EDE1FF', '#DCDEFF', '#C9DEFE', '#FCEDD0', '#F2FBA3', '#F4D9FD', '#E2FFD7', '#D6FFC2'
	];

	var cat2color = {
	      '9' : '#c00000'
	   , '10' : '#0070c0'
	   , '11' : '#ffabd5'
	   , '14' : '#7030a0'
	   , '30' : '#4c7080'
	   , '31' : '#1ac01e'
	   , '32' : '#00594c'
	   , '33' : '#d909e9'
	   , '34' : '#c7f2fb'
	   , '35' : '#ffc000'
	   , '36' : '#319517'
	   , '38' : '#ce8a28'
	   , '39' : '#806000'
	   , '40' : '#a7c4d1'
	   , '42' : '#757247'
	   , '41' : '#eb6919'
	   , '44' : '#385623'
	   , '46' : '#9cc2e5'
	   , '47' : '#cf7d9a'
	   , '48' : '#00b050'
	   , '50' : '#00b050'
	   , '51' : '#153553'
	   , '52' : '#2bc8c8'
	   , '53' : '#319517'
	   } ;

	return cat2color;

	var color_counter = 0;
	for (i = 0 ; i < course_calendar.length ; i++) {
		var entry = course_calendar[i];
		var category_id = entry.category_id ;
		if ( ! category_id2color[category_id] ) {
			category_id2color[category_id] = colors[color_counter];
			color_counter++;
		}
	}
	return category_id2color;
}
