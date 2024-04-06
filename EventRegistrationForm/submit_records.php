<?php 

//getting the submittion records
if(isset($_POST['first_name'])){
    
    $table_name = 'mydm_members_registration'; 

    //sanitize users form input
    global $first_name, $last_name, $email, $phone, $comment, $classification, $attendant, $service_day;

    $first_name = sanitize_text_field($_POST['first_name']); 
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $service_day = sanitize_text_field($_POST['service_day']);
    $form_id = sanitize_text_field($_POST['form_id']);
    $service_day_show = date('Y-m-d', strtotime($_POST['service_day']));
    $classification = sanitize_text_field($_POST['classification']);
    $attendant = sanitize_text_field($_POST['attendant']);
    $comment = esc_textarea($_POST['comment']);


    ///geting form data
    $userdata = array(
        'form_id' => $form_id, 
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'comment' => $comment,
        'classification' => $classification,
        'attendant' => $attendant,
        'service_day' => $service_day_show,
    );


    //checkmating records. 

    $table_name2 = 'mydm_service_setup'; 

    $result =  $wpdb->get_results("SELECT * FROM $table_name2 WHERE id ='".$form_id."'");

    foreach($result as $print) {
        $id_e = $print->id;
        $title_e = $print->title;
        $body_e = $print->body;
        $auditorium_e = $print->auditorium;
        $conference_e = $print->conference;
        $banquet_e = $print->banquet;
        $service_day_e = $print->service_day;
        $active_on_e = $print->active_on;

        $total_expected_attendants = $auditorium_e+$conference_e+$banquet_e;

    }

    //check the numbers of attendants 
    $attendants = $wpdb->get_results("SELECT SUM(attendant) AS total_attendant FROM $table_name WHERE form_id = '" . $form_id . "' ");
    $total_attendants = $attendants[0]->total_attendant;


    //checking the email user
    $datum = $wpdb->get_results("SELECT * FROM $table_name WHERE email = '" . $email . "' AND form_id = '" . $form_id . "' ");
    if($wpdb->num_rows > 0){
        $result['success'] = 0;
        $result['message'] = "You have already registered. Please check your email";
        print json_encode($result);
        die();
    }


    //getting the submitted number of attendants 
    $attendants_submitted = $wpdb->get_results("SELECT SUM(attendant) AS total_attendant FROM $table_name WHERE form_id = '" . $id_e . "' AND classification = '".$classification."' ");
    $total_attendants_submitted = $attendants_submitted[0]->total_attendant;


    //checking based on classification
    if($classification ==  1) { // auditorium
        if( ($total_attendants_submitted+$attendant) >  $auditorium_e){

            $total_attendants_slot_left = $auditorium_e - $total_attendants_submitted;  
            $result['success'] = 0; 
            $result['message'] = "No $attendant slot(s) left for auditorium hall as the capacity is filled. Please try again for conference hall or apply for $total_attendants_slot_left slot(s) "; 

            print json_encode($result); 
            die(); 

        }
       // 295+10 > 300 
    }elseif($classification ==  2) { // conference
        if( ($total_attendants_submitted+$attendant) >  $conference_e){

            $total_attendants_slot_left = $conference_e - $total_attendants_submitted;  
            $result['success'] = 0; 
            $result['message'] = "No $attendant slot(s) left for conference hall as the capacity is filled. Please try again for banquet hall or apply for $total_attendants_slot_left slot(s)"; 

            print json_encode($result); 
            die(); 

        }

    }else{ //banquet

        if( ($total_attendants_submitted+$attendant) >  $banquet_e){

            $total_attendants_slot_left = $banquet_e - $total_attendants_submitted;  
            $result['success'] = 0; 
            $result['message'] = "No $attendant slot(s) left for this event. Registration is closed."; 

            print json_encode($result); 
            die(); 

        }

    }



    //insert into database
    $result_check = $wpdb->insert(''.$table_name.'', $userdata); 
    $lastid = $wpdb->insert_id;

    //check if successful
    if($result_check){

        $result['success'] = 1; 
        $result['message'] = "Your Event Registration was successful, Registration ID is : REG/0001/".$lastid."";

        //send mail to users
        //add_action( 'phpmailer_init', 'send_smtp_email' );
        // function send_smtp_email( $phpmailer ) {
        //     $phpmailer->isSMTP();
        //     $phpmailer->Host       = SMTP_HOST;
        //     $phpmailer->SMTPAuth   = SMTP_AUTH;
        //     $phpmailer->Port       = SMTP_PORT;
        //     $phpmailer->SMTPSecure = SMTP_SECURE;
        //     $phpmailer->Username   = SMTP_USERNAME;
        //     $phpmailer->Password   = SMTP_PASSWORD;
        //     $phpmailer->From       = SMTP_FROM;
        //     $phpmailer->FromName   = SMTP_FROMNAME;
        // }

        // $email_message = "Thank you for your registration, Please find your registration Number : "; 
        // wp_mail("".$email."","Successful Registration", "".$email_message.""); 

    }else{
        $result['success'] = 0; 
        $result['message'] = "Your Event Registration failed, please try again or contact Admin";
    }

    print json_encode($result); 
    die();

}

?>