<?php
/**
 * Author: Dilshad Mustafa
 */

if (!defined('WPINC')) {
    echo "Hi there!  I can not do much when called directly.";
    die;
}

function mydm_security_check_display() {
 
 if ( is_user_logged_in() ) { 
     $user = wp_get_current_user();
     $user_login = $user->user_login;
     $user_id = $user->ID;

     if (!current_user_can( 'manage_options' )) { ?>
<div class="container justify-content-center ">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
        <h4>You do not have sufficient permissions to access this page.</h4>
        <h4>Please contact Website owner.</h4>
        </div>
    </div>
</div>
<?php      
        return FALSE;
    } else {
        return TRUE;
    }
         
 }      
 else { ?>
<div class="container justify-content-center ">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
        <h4>You are not logged in.</h4>
        <a href="/mysite1/wp-login.php" title="Members Area Login" rel="home"><span><h4>Members Area. Please Login.</h4></span></a>
        </div>
    </div>
</div>
<?php 
     return FALSE;
    } 

}

?>