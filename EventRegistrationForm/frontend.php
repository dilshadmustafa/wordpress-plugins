<?php 

//register a new shortcode: [events_registration id=5]
add_shortcode('events_registration', 'event_registration_shortcode');

//the callback function that will be the hook
function event_registration_shortcode($atts = ''){

    ob_start(); 

    //extracting the parameters

    extract(shortcode_atts(array(
        'id' => 1,
    ), $atts)); 

   // echo "shortcode set";
   // var_dump($id); 

    registration_form($id); 

    return ob_get_clean();
}


function registration_form($sc_id){

    //declare variable and setup functions

    global $wpdb; 

    $table_name2 = 'mydm_service_setup'; 
    $table_name = 'mydm_members_registration';
    
    //get results
    $results = $wpdb->get_results("SELECT * FROM $table_name2 WHERE id='$sc_id'");

    foreach($results as $print){

        $id_e = $print->id;
        $title_e = $print->title;
        $body_e = $print->body;
        $auditorium_e = $print->auditorium;
        $conference_e = $print->conference;
        $banquet_e = $print->banquet;
        $service_day_e = $print->service_day;
        $active_on_e = $print->active_on;

    }

    //checking classification and numbers of attendants
    $attendants = $wpdb->get_results("SELECT SUM(attendant) AS total_attendant FROM $table_name WHERE form_id = '" . $id_e . "' ");

    //auditorium
    $attendants1 = $wpdb->get_results("SELECT SUM(attendant) AS total_attendant FROM $table_name WHERE form_id = '" . $id_e . "' AND classification = '1' ");

   // confernce room
    $attendants2 = $wpdb->get_results("SELECT SUM(attendant) AS total_attendant FROM $table_name WHERE form_id = '" . $id_e . "' AND classification = '2' ");

    //banquent
    $attendants3 = $wpdb->get_results("SELECT SUM(attendant) AS total_attendant FROM $table_name WHERE form_id = '" . $id_e . "' AND classification = '3' ");

    $total_attendants = $attendants[0]->total_attendant; 
    $total_attendants1 = $attendants1[0]->total_attendant; //auditorium
    $total_attendants2 = $attendants2[0]->total_attendant; //conference
    $total_attendants3 = $attendants3[0]->total_attendant; //banquet

    $total_left_auditorium_e =  $auditorium_e - $total_attendants1;
    $total_left_conference_e = $conference_e - $total_attendants2;
    $total_left_banquet_e = $banquet_e - $total_attendants3;


    //getting the classifcation set
    if(!($total_attendants >= $auditorium_e)){
        //you are in auditorium
        $progress_bar = '<p style="width:100%; margin:2px !important; font-weight: bold; color: darkred" data-value="'.$total_left_auditorium_e.'"> Auditorium</p>
        
        <progress max="'.$auditorium_e.'" value="'.$total_attendants1.'" class="html5">
            <div class="progress-bar">
                <span style="width:80%"> 80% </span>
            </div>
        </progress>
        ';

        $classification_progress = "1"; 
        $classification_display = "Auditorium"; 
    }elseif(!($total_attendants >= $auditorium_e+$conference_e)){
        // you are in conference
        $progress_bar = '<p style="width:100%; margin:2px !important; font-weight: bold; color: darkred" data-value="'.$total_left_conference_e.'"> Conference Room</p>
        
        <progress max="'.$conference_e.'" value="'.$total_attendants2.'" class="html5">
            <div class="progress-bar">
                <span style="width:80%"> 80% </span>
            </div>
        </progress>
        ';
        $classification_progress = "2"; 
        $classification_display = "Conference Room"; 
    }else{
        // you are in banquent
        $progress_bar = '<p style="width:100%; margin:2px !important; font-weight: bold; color: darkred" data-value="'.$total_left_banquet_e.'"> Banquet Room</p>
        
        <progress max="'.$banquet_e.'" value="'.$total_attendants3.'" class="html5">
            <div class="progress-bar">
                <span style="width:80%"> 80% </span>
            </div>
        </progress>
        ';


        $classification_progress = "3"; 

        $classification_display = "Banquet Room"; 
    }
    

    echo '

    <style>
/* Styling an indeterminate progress bar */

progress:not(value) {
	/* Add your styles here. As part of this walkthrough we will focus only on determinate progress bars. */
}

/* Styling the determinate progress element */

progress[value] {
	/* Get rid of the default appearance */
	appearance: none;
	
	/* This unfortunately leaves a trail of border behind in Firefox and Opera. We can remove that by setting the border to none. */
	border: none;
	
	/* Add dimensions */
	width: 100%; height: 20px;
	
	/* Although firefox doesn\'t provide any additional pseudo class to style the progress element container, any style applied here works on the container. */
	  background-color: whiteSmoke;
	  border-radius: 3px;
	  box-shadow: 0 2px 3px rgba(0,0,0,.5) inset;
	
	/* Of all IE, only IE10 supports progress element that too partially. It only allows to change the background-color of the progress value using the \'color\' attribute. */
	color: royalblue;
	
	position: relative;
	margin: 0 0 1.5em; 
}

/*
Webkit browsers provide two pseudo classes that can be use to style HTML5 progress element.
-webkit-progress-bar -> To style the progress element container
-webkit-progress-value -> To style the progress element value.
*/

progress[value]::-webkit-progress-bar {
	background-color: whiteSmoke;
	border-radius: 3px;
	box-shadow: 0 2px 3px rgba(0,0,0,.5) inset;
}

progress[value]::-webkit-progress-value {
	position: relative;
	
	background-size: 35px 20px, 100% 100%, 100% 100%;
	border-radius:3px;
	
	/* Let\'s animate this */
	animation: animate-stripes 5s linear infinite;
}

@keyframes animate-stripes { 100% { background-position: -100px 0; } }

/* Let\'s spice up things little bit by using pseudo elements. */

progress[value]::-webkit-progress-value:after {
	/* Only webkit/blink browsers understand pseudo elements on pseudo classes. A rare phenomenon! */
	content: \'\';
	position: absolute;
	
	width:5px; height:5px;
	top:7px; right:7px;
	
	background-color: white;
	border-radius: 100%;
}

/* Firefox provides a single pseudo class to style the progress element value and not for container. -moz-progress-bar */

progress[value]::-moz-progress-bar {
	/* Gradient background with Stripes */
	background-image:
	-moz-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -moz-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -moz-linear-gradient( left, #09c, #f44);
	
	background-size: 35px 20px, 100% 100%, 100% 100%;
	border-radius:3px;
	
	/* Firefox doesn\'t support CSS3 keyframe animations on progress element. Hence, we did not include animate-stripes in this code block */
}

/* Fallback technique styles */
.progress-bar {
	background-color: whiteSmoke;
	border-radius: 3px;
	box-shadow: 0 2px 3px rgba(0,0,0,.5) inset;

	/* Dimensions should be similar to the parent progress element. */
	width: 100%; height:20px;
}

.progress-bar span {
	background-color: royalblue;
	border-radius: 3px;
	
	display: block;
	text-indent: -9999px;
}

p[data-value] { 
  
  position: relative; 
}

/* The percentage will automatically fall in place as soon as we make the width fluid. Now making widths fluid. */

p[data-value]:after {
	content: attr(data-value) \' spot(s) left\';
	position: absolute; right:0;
}





.html5::-webkit-progress-value,
.python::-webkit-progress-value  {
	/* Gradient background with Stripes */
	background-image:
	-webkit-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -webkit-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -webkit-linear-gradient( left, #09c, #f44);
}

.css3::-webkit-progress-value,
.php::-webkit-progress-value 
{
	/* Gradient background with Stripes */
	background-image:
	-webkit-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -webkit-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -webkit-linear-gradient( left, #09c, #ff0);
}

.jquery::-webkit-progress-value,
.node-js::-webkit-progress-value 
{
	/* Gradient background with Stripes */
	background-image:
	-webkit-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -webkit-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -webkit-linear-gradient( left, #09c, #690);
}

/* Similarly, for Mozillaa. Unfortunately combining the styles for different browsers will break every other browser. Hence, we need a separate block. */

.html5::-moz-progress-bar,
.php::-moz-progress-bar {
	/* Gradient background with Stripes */
	background-image:
	-moz-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -moz-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -moz-linear-gradient( left, #09c, #f44);
}

.css3::-moz-progress-bar,
.php::-moz-progress-bar {
{
	/* Gradient background with Stripes */
	background-image:
	-moz-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -moz-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -moz-linear-gradient( left, #09c, #ff0);
}

.jquery::-moz-progress-bar,
.node-js::-moz-progress-bar {
	/* Gradient background with Stripes */
	background-image:
	-moz-linear-gradient( 135deg,
													 transparent,
													 transparent 33%,
													 rgba(0,0,0,.1) 33%,
													 rgba(0,0,0,.1) 66%,
													 transparent 66%),
    -moz-linear-gradient( top,
														rgba(255, 255, 255, .25),
														rgba(0,0,0,.2)),
     -moz-linear-gradient( left, #09c, #690);
}

/* Now we are good to duplicate html code for other skills and then add the css code for the new skill based on data-skill */

  
/* THE END */
</style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
    <link rel=\'stylesheet\' href=\'https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css\'>

    

    <div id="respond" class="comment-respond" style="background-color:#fbfbfb; padding:30px; margin:1px;" >
    <form id="event_form"  action="" class="comment-form" method="post">

        <h3> '.$title_e.' </h3>
        <hr />
        <h5> '.$body_e.' </h5>
        <hr />
        <h5> Current Allocation : '.$classification_display.' </h5>
        <hr />

'.$progress_bar.'
        <input type="hidden" name="classification" value="'.$classification_progress.'">
        <input type="hidden" name="form_id" value="'.$id_e.'">


        <div>
        <label for="first_name"> First Name <strong style="color:red">*</strong> </label>

        <input type="text" required class="search-field" name="first_name"  value="'.(isset($_POST['first_name']) ? $first_name : null).'" >
        </div>

        <div>
        <label for="last_name">Last Name <strong style="color: red">*</strong></label>
        <input type="text" required name="last_name" value="' . ( isset( $_POST['last_name'] ) ? $last_name : null ) . '">
    </div>

    <div>
        <label for="email">Email <strong style="color: red">*</strong></label>
        <input type="email" required name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">
    </div>

    <div>
        <label for="website">Phone Number <strong style="color: red">*</strong></label>
        <input type="number" required name="phone" value="' . ( isset( $_POST['phone']) ? $phone : null ) . '">
    </div>
    
    <div>
        <label for="service_day">Service Date <strong style="color: red">*</strong></label>
        <input type="text" required name="service_day" readonly value="' .date('d-m-Y', strtotime($service_day_e)). '">
    </div>

    <div>
        <label for="attendant">Number of attendants <strong style="color: red">*</strong></label>
        <select name="attendant" style="width: 100%; height: 30px;"  class="form-control" id="attendant">
            <option selected value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            
</select>

    </div>

  

    <div>
        <label for="bio">If you have further enquires, please inform us below</label>
        <textarea name="comment">' . ( isset( $_POST['comment']) ? $comment : null ) . '</textarea>
    </div>
    <hr />
     <div>
    <input type="submit" name="submit" value="Register For Event"/>
     </div>
</form>
    </div>
    
    
    <script type="text/javascript">

    jQuery("#event_form").submit(function(event){
    
        event.preventDefault();  //prevent default load action
       // alert("yes it is working"); 
    
        var post_url = jQuery(this).attr("action");
        var request_method = jQuery(this).attr("method");
        var form_data = jQuery(this).serialize();
    
        console.log(post_url); 
        console.log(form_data); 
    
        jQuery.ajax({
            url: post_url, 
            type: request_method, 
            cache: false,
            dataType: "json", 
            async: false, 
            data: form_data, 
            complete: function(response){
    
                //alert("yes successful"); 
                // response = jQuery.parseJSON(response); 
                console.log(response.responseJSON.message);
                console.log(response.responseJSON.success);
    
                //checking if it is success or not
                if(response.responseJSON.success == 1){
    
                    swal("Successful Registration", response.responseJSON.message, "success"); 
    
                    document.getElementById("event_form").reset(); 
    
                }else{
    
                    swal("Error in Registration", response.responseJSON.message, "error"); 
    
                    document.getElementById("event_form").reset(); 
    
    
                }
    
            }
    
        })
     }
    ); 
    </script>
    '; 


}


//including the submit functionality
include "submit_records.php"
?>
