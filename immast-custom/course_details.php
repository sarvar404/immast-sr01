
<?php

// Standard config file and local library.
require_once(__DIR__ . '/../../config.php');

// Setting up the page.
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout("simple");
$PAGE->set_url(new moodle_url('/local/immast/demo.php'));

// Ouput the page header.
echo $OUTPUT->header();

?>


<!-- content area -->
<div class="container-fluid inner-content">
  <div class="container">
    <div class="row">

<!-- Page Title -->       
      <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-12">
      	<h1 class="mb-4">Certificate Course in CO2 Lasers in Dermatology</h1>
      </div>  
<!-- Page Title -->        
        
<!-- Left Area Start -->         
        
      <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-12">
      
        <div class="coures_date"><strong>Date:</strong> Jun 28, 2022 – Jun 30, 2022</div>
        
        <div class="mb-5">
        	<img class="img-fluid" src="About - IMMAST_files/dermatology.jpg" alt="Certificate Course in CO2 Lasers in Dermatology" width="962" height="688">
        </div>
       
        <div class="sub_title">About The Training Course</div>
        
<!-- Curriculum Start -->       
        <div class="curriculum_area">
        	<div class="curriculum_title">Curriculum</div>
            <div class="under_area">
            	<div class="curriculum_heading">Learning Objectives</div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                
                <div class="curriculum_heading">Content Highlights</div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                
                <div class="curriculum_heading">Who Should Attend</div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </div>
        </div>
<!-- Curriculum End -->        
        
<!-- Professional Start -->        
        <div class="sub_title">Meet Our Professional</div>
        
        <div class="professor_area">
            <div class="professor_img"><img src="About - IMMAST_files/profeser.jpg" alt="Jason Furman" width="141" height="142"></div>
            <div>
                <span class="curriculum_heading">Jason Furman</span>
                <p>Master of Computer Applications (MCA) is a two year professional post-graduate programme for candidates wanting to delve deeper into the world of computer application development with the help of learning modern programming language. The programme is a blend of both theoretical and practical knowledge.</p>
            </div>
        </div>
<!-- Professional End -->        
        
<!-- 6 Reasons Start -->        
        <div class="sub_title">6 Reasons Why Professionals Prefer This Training Course</div>
        
        <div class="mb-4">
        <div class="row">
            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12">
            	<div class="reasons_area">
                <img src="About - IMMAST_files/icon/unique-design.webp" alt="Unique Design" width="141" height="142">
                <div class="reasons_title">Unique<br>Design</div>
                <p>Reap the benefit of structured step-by-step learning design and comprehensive agenda that teaches A to Z of the procedure, customized to match individual learning speed.</p>
                </div>
            </div>
            
            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12">
            	<div class="reasons_area">
                <img src="About - IMMAST_files/icon/hands-on.webp" alt="Hands-on Practice" width="141" height="142">
                <div class="reasons_title">Hands-on<br>Practice</div>
                <p>Gain skills and the confidence to perform a procedure, with extensive hands-on practice on near-life animal models, physical simulators and virtual reality simulators. Learn to manage complications. Improve motor skills with unique hand-eye coordination exercises.</p>
                </div>
            </div>
            
            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12">
            	<div class="reasons_area">
                <img src="About - IMMAST_files/icon/accreditation.webp" alt="Accreditation Advantage" width="141" height="142">
                <div class="reasons_title">Accreditation<br>Advantage</div>
                <p>Get CPD points from The Royal College of Surgeons of England and CME points from Maharashtra Medical Council.</p>
                </div>
            </div>
            
            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12">
            	<div class="reasons_area">
                <img src="About - IMMAST_files/icon/renowned.webp" alt="Renowned Experts" width="141" height="142">
                <div class="reasons_title">Renowned<br>Experts</div>
                <p>Learn under personal supervision and 1:1 mentoring of stalwarts of medicine & surgery, with over-the-shoulder training in labs. Spend informal time with them to gain insights on setting up your own practice, their secret tips and tricks, and more.</p>
                </div>
            </div>
            
            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12">
            	<div class="reasons_area">
                <img src="About - IMMAST_files/icon/infrastructure.webp" alt="World Class Simulation Infrastructure" width="141" height="142">
                <div class="reasons_title">World Class Simulation<br>Infrastructure</div>
                <p>Train in modern VR and Tissue Labs, equipped and laid out to simulate real Operating Theatres.</p>
                </div>
            </div>
            
            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12">
            	<div class="reasons_area">
                <img src="About - IMMAST_files/icon/alumni.webp" alt="Alumni Advantage" width="141" height="142">
                <div class="reasons_title">Alumni<br>Advantage</div>
                <p>Access world class papers, articles and updates in the vast Knowledge Repository through IMMAST’s website. Surge ahead with 1:1 mentoring and peer networking through Alumni Support groups continued for life.</p>
                </div>
            </div>
            
        </div>
        </div>    
<!-- 6 Reasons End -->         
        
<!-- Gallery Start -->         
        <div class="sub_title">Gallery</div>
        
        <div class="container">
        <div class="row">
        
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 mt-3 mb-3">
            	<img class="img-fluid" src="About - IMMAST_files/gallery/1.jpg" alt="" width="310" height="206">
            </div>
            
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 mt-3 mb-3">
            	<img class="img-fluid" src="About - IMMAST_files/gallery/2.jpg" alt="" width="310" height="206">
            </div>
            
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 mt-3 mb-3">
            	<img class="img-fluid" src="About - IMMAST_files/gallery/3.jpg" alt="" width="310" height="206">
            </div>
            
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 mt-3 mb-3">
            	<img class="img-fluid" src="About - IMMAST_files/gallery/4.jpg" alt="" width="310" height="206">
            </div>
            
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 mt-3 mb-3">
            	<img class="img-fluid" src="About - IMMAST_files/gallery/5.jpg" alt="" width="310" height="206">
            </div>
            
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6 mt-3 mb-3">
            	<img class="img-fluid" src="About - IMMAST_files/gallery/6.jpg" alt="" width="310" height="206">
            </div>
            
        </div>
        </div>    
<!-- Gallery End -->         
        
      </div>

<!-- Left Area End -->      
      
<!-- Right Area Start -->      
      
      <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4 col-sm-12 offset-xxl-1">
      
<!-- Button Start -->      
      <div class="button_area">
      	<button type="submit" id="inquiry_button" class="border-button">Next Dates</button>
        <button type="submit" id="inquiry_button" class="border-button">Register Now!</button>
      </div>
<!-- Button End -->
      
<!-- Support Start -->         
      <div class="upgrade">
      	Upgrade your supporting staff’s skills to assist you better, for a nominal fee.<br>
        <a href="#" class="red_text">Click here to know more.</a> 
      </div>
<!-- Support End -->
      
<!-- Other Courses Start -->      
      <div class="other_courses">
      	<div class="courses_sub_title">Other Courses in This Specialty</div>
        
        <ul class="other_courses_list">
          <li><a href="">Certificate Course in Functional Endoscopic Sinus Surgery</a></li>
          <li><a href="">Master Course in Minimal Invasive Proctology</a></li>
          <li><a href="">Certificate Course in Functional Endoscopic Sinus Surgery</a></li>
          <li><a href="">Master Course in Minimal Invasive Proctology</a></li>
          <li><a href="">Master Course in Minimal Invasive Proctology</a></li>
          <li><a href="">Certificate Course in Functional Endoscopic Sinus Surgery</a></li>
          <li><a href="">Master Course in Minimal Invasive Proctology</a></li>
        </ul>
      </div>
<!-- Other Courses End -->         	
        
<!-- Review Section Start -->        
        
        <div class="sub_title">What They Say</div>
        
<!-- Review 1 Start -->         
        <div class="coures_review mb-3">
        	<div class="review_area">
            
            <div class="review_area">
            	<div class="review_img">
                	<img src="About - IMMAST_files/profeser.jpg" width="141" height="142">
                </div>
                
                <div class="">
                    <div class="review_title">Akshat Kumar</div>
                    <div class="left"><div class="star-ratings" title="5 Stars" style="position: relative; box-sizing: border-box; display: inline-block;"><svg class="star-grad" style="position: absolute; z-index: 0; width: 0px; height: 0px; visibility: hidden;"><defs><linearGradient id="starGrad868761755844741" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" class="stop-color-first" style="stop-color: rgb(255, 83, 61); stop-opacity: 1;"></stop><stop offset="0%" class="stop-color-first" style="stop-color: rgb(255, 83, 61); stop-opacity: 1;"></stop><stop offset="0%" class="stop-color-final" style="stop-color: rgb(203, 211, 227); stop-opacity: 1;"></stop><stop offset="100%" class="stop-color-final" style="stop-color: rgb(203, 211, 227); stop-opacity: 1;"></stop></linearGradient></defs></svg><div class="star-container" style="position: relative; display: inline-block; vertical-align: top; padding-right: 0px;"><svg viewBox="0 0 51 48" class="widget-svg" style="width: 15px; height: 15px; transition: transform 0.2s ease-in-out 0s;"><path class="star" style="fill: rgb(255, 83, 61); transition: fill 0.2s ease-in-out 0s;" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"></path></svg></div><div class="star-container" style="position: relative; display: inline-block; vertical-align: top; padding-left: 0px; padding-right: 0px;"><svg viewBox="0 0 51 48" class="widget-svg" style="width: 15px; height: 15px; transition: transform 0.2s ease-in-out 0s;"><path class="star" style="fill: rgb(255, 83, 61); transition: fill 0.2s ease-in-out 0s;" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"></path></svg></div><div class="star-container" style="position: relative; display: inline-block; vertical-align: top; padding-left: 0px; padding-right: 0px;"><svg viewBox="0 0 51 48" class="widget-svg" style="width: 15px; height: 15px; transition: transform 0.2s ease-in-out 0s;"><path class="star" style="fill: rgb(255, 83, 61); transition: fill 0.2s ease-in-out 0s;" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"></path></svg></div><div class="star-container" style="position: relative; display: inline-block; vertical-align: top; padding-left: 0px; padding-right: 0px;"><svg viewBox="0 0 51 48" class="widget-svg" style="width: 15px; height: 15px; transition: transform 0.2s ease-in-out 0s;"><path class="star" style="fill: rgb(255, 83, 61); transition: fill 0.2s ease-in-out 0s;" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"></path></svg></div><div class="star-container" style="position: relative; display: inline-block; vertical-align: top; padding-left: 0px;"><svg viewBox="0 0 51 48" class="widget-svg" style="width: 15px; height: 15px; transition: transform 0.2s ease-in-out 0s;"><path class="star" style="fill: rgb(255, 83, 61); transition: fill 0.2s ease-in-out 0s;" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"></path></svg></div></div>
                    <!--<span>(5/5)</span>-->
                    </div>
                </div>
            </div>  
            
            <div class="">
            	<div class=""><img src="About - IMMAST_files/google-icon.jpg" width="27" height="28"></div>
            </div>
            
            </div>
            
             <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley.</p>  
             
        </div>
<!-- Review 1 End -->  

<!-- Review 2 Start -->          
        <div class="coures_review mb-3">
        	<div class="review_area">
            
            <div class="review_area">
            	<div class="review_img">
                	<img src="About - IMMAST_files/profeser.jpg" width="141" height="142">
                </div>
                
                <div class="">
                    <div class="review_title">Akshat Kumar</div>
                    <div class="left"><div class="star-ratings" title="5 Stars" style="position: relative; box-sizing: border-box; display: inline-block;"><svg class="star-grad" style="position: absolute; z-index: 0; width: 0px; height: 0px; visibility: hidden;"><defs><linearGradient id="starGrad868761755844741" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" class="stop-color-first" style="stop-color: rgb(255, 83, 61); stop-opacity: 1;"></stop><stop offset="0%" class="stop-color-first" style="stop-color: rgb(255, 83, 61); stop-opacity: 1;"></stop><stop offset="0%" class="stop-color-final" style="stop-color: rgb(203, 211, 227); stop-opacity: 1;"></stop><stop offset="100%" class="stop-color-final" style="stop-color: rgb(203, 211, 227); stop-opacity: 1;"></stop></linearGradient></defs></svg><div class="star-container" style="position: relative; display: inline-block; vertical-align: top; padding-right: 0px;"><svg viewBox="0 0 51 48" class="widget-svg" style="width: 15px; height: 15px; transition: transform 0.2s ease-in-out 0s;"><path class="star" style="fill: rgb(255, 83, 61); transition: fill 0.2s ease-in-out 0s;" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"></path></svg></div><div class="star-container" style="position: relative; display: inline-block; vertical-align: top; padding-left: 0px; padding-right: 0px;"><svg viewBox="0 0 51 48" class="widget-svg" style="width: 15px; height: 15px; transition: transform 0.2s ease-in-out 0s;"><path class="star" style="fill: rgb(255, 83, 61); transition: fill 0.2s ease-in-out 0s;" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"></path></svg></div><div class="star-container" style="position: relative; display: inline-block; vertical-align: top; padding-left: 0px; padding-right: 0px;"><svg viewBox="0 0 51 48" class="widget-svg" style="width: 15px; height: 15px; transition: transform 0.2s ease-in-out 0s;"><path class="star" style="fill: rgb(255, 83, 61); transition: fill 0.2s ease-in-out 0s;" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"></path></svg></div><div class="star-container" style="position: relative; display: inline-block; vertical-align: top; padding-left: 0px; padding-right: 0px;"><svg viewBox="0 0 51 48" class="widget-svg" style="width: 15px; height: 15px; transition: transform 0.2s ease-in-out 0s;"><path class="star" style="fill: rgb(255, 83, 61); transition: fill 0.2s ease-in-out 0s;" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"></path></svg></div><div class="star-container" style="position: relative; display: inline-block; vertical-align: top; padding-left: 0px;"><svg viewBox="0 0 51 48" class="widget-svg" style="width: 15px; height: 15px; transition: transform 0.2s ease-in-out 0s;"><path class="star" style="fill: rgb(255, 83, 61); transition: fill 0.2s ease-in-out 0s;" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"></path></svg></div></div>
                    <!--<span>(5/5)</span>-->
                    </div>
                </div>
            </div>  
            
            <div class="">
            	<div class=""><img src="About - IMMAST_files/google-icon.jpg" width="27" height="28"></div>
            </div>
            
            </div>
            
             <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley.</p>  
             
             
        </div>
        
<!-- Review 2 End -->

<!-- Review Section End -->         

<!-- Video Section Start -->        
        
        <div class="video_title">Hear What Faculty & Participants Have to Say </div>
        
        <div class="embed-responsive embed-responsive-16by9" mb-3>
        	<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe>
        </div>
        
<!-- Video Section End -->          
        
        
      
      </div>
      
<!-- Right Area End -->       
      
    </div>
    
  </div>
</div>
<!-- content area --> 




<?php
// Output the page footer.
echo $OUTPUT->footer();
?>
