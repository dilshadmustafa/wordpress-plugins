<?php 
/*  
    Plugin Name: Event & User Registration Form R1
    Plugin URI: https://dilshadmustafa.bss.design/
    Description: Event & User Registration Form
    Version: 1.0
    Author: Dilshad Mustafa
    Author URI: https://dilshadmustafa.bss.design/
    License: Mozilla Public License Version 2.0, https://www.mozilla.org/en-US/MPL/2.0/    
*/


register_activation_hook(__FILE__, 'createPluginActivation'); 


function createPluginActivation(){

    global $wpdb; 

    $charset_collate = $wpdb->get_charset_collate(); 

    $table_name = 'mydm_members_registration'; 
    $table_name_2 = 'mydm_service_setup'; 

    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
        `user_id` int(11) NOT NULL AUTO_INCREMENT, 
        `form_id` int(11) DEFAULT NULL, 
        `first_name` varchar(220) DEFAULT NULL, 
        `last_name` varchar(220) DEFAULT NULL, 
        `email` varchar(220) DEFAULT NULL, 
        `phone` varchar(220) DEFAULT NULL, 
        `classification` varchar(220) DEFAULT NULL, 
        `comment` text,
        `attendant` int(11) DEFAULT NULL, 
        `service_day` DATE DEFAULT NULL,
        `creation_time` DATETIME DEFAULT CURRENT_TIMESTAMP, 
        PRIMARY KEY(user_id)
    ); 
    "; 

$sql_two = "CREATE TABLE IF NOT EXISTS `$table_name_2` (
    `id` int(11) NOT NULL AUTO_INCREMENT, 
    `title` varchar(225) DEFAULT NULL, 
    `body` text,
    `auditorium` int(11) NOT NULL DEFAULT '155',
    `conference` int(11) NOT NULL DEFAULT '30', 
    `banquet` int(11) NOT NULL DEFAULT '150', 
    `service_day` DATE DEFAULT NULL,
    `active_on` enum ('0','1','2') default '0', 
    `creation_time` DATETIME DEFAULT CURRENT_TIMESTAMP, 
     PRIMARY KEY(id)
); 
"; 




    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){

        require_once(ABSPATH.'wp-admin/includes/upgrade.php'); 
        dbDelta($sql);
        dbDelta($sql_two); 
    }
}
    add_action('admin_menu', 'addAdminMenu'); 

    function addAdminMenu(){
        add_menu_page('Event Registration Page','Event Registration Menu', 'manage_options', __FILE__, 'AdminPage', 'dashicons-admin-home' ); 
    }
function AdminPage() {
    global $wpdb; 
    $table_name_2 = 'mydm_service_setup'; 

 //inserting of new records
    if (isset($_POST['newsubmit'])){

       // echo "okay"; 

       $title = $_POST['title']; 
       $body = $_POST['body']; 
       $auditorium = $_POST['auditorium']; 
       $conference = $_POST['conference']; 
       $banquet = $_POST['banquet']; 
       $service_day = $_POST['service_day']; 
       $active_on = $_POST['active_on']; 
       

 //echo "$title"; 
$wpdb->query("INSERT INTO $table_name_2(title,body,auditorium,conference,banquet,service_day,active_on) VALUES ('$title','$body', '$auditorium', '$conference', '$banquet', '$service_day', '$active_on') "); 

echo "Submitted Successfully"; 


    }

    //UPDATING RECORDS

    if (isset($_POST['uptsubmit'])){

        // echo "okay"; 
        $id = $_POST['uptid'];
        $title = $_POST['title']; 
        $body = $_POST['body']; 
        $auditorium = $_POST['auditorium']; 
        $conference = $_POST['conference']; 
        $banquet = $_POST['banquet']; 
        $service_day = $_POST['service_day']; 
        $active_on = $_POST['active_on']; 


        $wpdb->query("UPDATE $table_name_2 SET title='$title',body='$body',auditorium='$auditorium',conference='$conference',banquet='$banquet',service_day='$service_day',active_on='$active_on' WHERE id=$id"); 

        echo "Updated Successfully"; 

    }


    //CREATING DELETE FUNCTION

    if (isset($_GET['del'])){

        $del_id =  $_GET['del']; 

        $wpdb->query("DELETE FROM $table_name_2 WHERE id='$del_id' "); 
        echo "Deleted Successfully"; 
    }
    ?>

<?php 



if(isset($_GET['upt'])){

   $upt_id = $_GET['upt']; 

   $result = $wpdb->get_results("SELECT * FROM $table_name_2 WHERE id = '$upt_id'"); 

   //var_dump($result); 

   foreach ($result as $print){

        $id_e = $print->id; 
        $title_e = $print->title; 
        $body_e = $print->body; 
        $auditorium_e = $print->auditorium; 
        $conference_e = $print->conference; 
        $banquet_e = $print->banquet; 
        $service_day_e = $print->service_day; 
        $active_on_e = $print->active_on; 


        if($print->active_on == 0){
            $status = "Disabled"; 
        }else if($print->active_on == 1){
            $status = "Active"; 
        }else{
            $status = "Closed";
        }
   }
   ?>

<form action="" method="post">

<input type="hidden" id="uptid" name="uptid" value="<?php echo "$id_e"; ?>"
    <div class="wrap container" >
        <h2>Editing: <?php echo "$title_e"; ?> </h2>
                <div id="post-body-content" class="edit-form-section edit-comment-section" style="max-width: 50%; padding: 10px;">
                    <div class="inside">
                        <div id="comment-link-box">

                        </div>
                    </div>
                    <div id="namediv" class="stuffbox">
                        <div class="inside">
                            <h2 style=" padding: 10px;" class="edit-comment-author">Service Registration</h2>
                            <fieldset>
                                <legend class="screen-reader-text">Service Registration</legend>
                                <table class="form-table editcomment" role="presentation">
                                    <tbody>
                                    <tr>
                                        <td class="first"><label for="name">Title</label></td>
                                        <td><input type="text" required name="title" size="30" value="<?php echo "$title_e"; ?>" id="title"></td>
                                    </tr>
                                    <tr>
                                        <td class="first"><label for="email">Auditorium Size</label></td>
                                        <td>
                                            <input type="number"  required name="auditorium" size="30" value="<?php echo "$auditorium_e"; ?>" id="auditorium">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="first"><label for="name">Conference Room Size</label></td>
                                        <td><input type="number" required name="conference" size="30" value="<?php echo "$conference_e"; ?>" id="conference"></td>
                                    </tr>
                                    <tr>
                                        <td class="first"><label for="email">Banquet Size</label></td>
                                        <td>
                                            <input type="number" required name="banquet" size="30" value="<?php echo "$banquet_e"; ?>" id="banquet">
                                        </td>
                                    </tr>
                                    <tr>
                                        <?php $nextSunday = date('Y-m-d', strtotime($service_day_e)); ?>
                                        <td class="first"><label for="name">Service Date</label></td>
                                        <td><input type="date" required name="service_day" value="<?php echo "$nextSunday" ?>" id="service_day"></td>
                                    </tr>
                                    <tr>
                                        <td class="first"><label for="body">Body</label></td>
                                        <td>

                                        <?php 
                                        $settings = array('teeny' => true, 'textarea_rows' =>10, 'tabindex'=>1); 
                                        wp_editor(esc_html((get_option('text',strip_tags($body_e)))),'body', $settings);
                                         ?>

                                                 <!-- <textarea style="width: 100%" name="body" id="body">  </textarea> -->
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="first"><label for="email">Status</label></td>
                                        <td>
                                            <select name="active_on" required id="active_on">
                                                <option selected value="<?php echo "$active_on_e"; ?>"><?php echo "$status"; ?></option>
                                                <option value="0">Disabled</option>
                                                <option value="1">Active</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td> <button id="uptsubmit" name="uptsubmit" class="button button-success"  type="submit">Edit Post</button> </td>

                                        <td> <a class='button btn-primary' style='color:#fff; background-color:red'   href='<?php echo ''.admin_url('admin.php?page=EventRegistrationForm%2Findex.php').'' ?>'> Cancel and Return </a> </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                    </div>


                </div>

    
</form>

   <?php
}elseif(isset($_GET['new'])) {

?>
<form action="" method="post">
    <div class="wrap container" >
        <h2>Setup Service Registration</h2>
                <div id="post-body-content" class="edit-form-section edit-comment-section" style="max-width: 50%; padding: 10px;">
                    <div class="inside">
                        <div id="comment-link-box">

                        </div>
                    </div>
                    <div id="namediv" class="stuffbox">
                        <div class="inside">
                            <h2 style=" padding: 10px;" class="edit-comment-author">Service Registration</h2>
                            <fieldset>
                                <legend class="screen-reader-text">Service Registration</legend>
                                <table class="form-table editcomment" role="presentation">
                                    <tbody>
                                    <tr>
                                        <td class="first"><label for="name">Title</label></td>
                                        <td><input type="text" required name="title" size="30" value="" id="title"></td>
                                    </tr>
                                    <tr>
                                        <td class="first"><label for="email">Auditorium Size</label></td>
                                        <td>
                                            <input type="number" required name="auditorium" size="30" value="150" id="auditorium">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="first"><label for="name">Conference Room Size</label></td>
                                        <td><input type="number" required name="conference" size="30" value="30" id="conference"></td>
                                    </tr>
                                    <tr>
                                        <td class="first"><label for="email">Banquet Size</label></td>
                                        <td>
                                            <input type="number" required name="banquet" size="30" value="155" id="banquet">
                                        </td>
                                    </tr>
                                    <tr>
                                        <?php $nextSunday = date('Y-m-d', strtotime('next sunday')); ?>
                                        <td class="first"><label for="name">Service Date</label></td>
                                        <td><input type="date" required name="service_day" value="<?php echo "$nextSunday" ?>" id="service_day"></td>
                                    </tr>
                                    <tr>
                                        <td class="first"><label for="body">Body</label></td>
                                        <td>

                                        <?php 
                                        $settings = array('teeny' => true, 'textarea_rows' =>10, 'tabindex'=>1); 
                                        wp_editor(esc_html((get_option('text',''))),'body', $settings);
                                         ?>

                                                 <!-- <textarea style="width: 100%" name="body" id="body">  </textarea> -->
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="first"><label for="email">Status</label></td>
                                        <td>
                                            <select name="active_on" required id="active_on">
                                                <option value="0">Disabled</option>
                                                <option value="1">Active</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td> <button id="newsubmit" name="newsubmit" class="button button-success"  type="submit">Create</button> </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                    </div>


                </div>

    </div>
</form>


<!--  -->
<?php }else{ ?>


<?php if (isset($_GET['id'])){ 
// get the members registration
include "registration_list.php";
}else { 
 //event setup   
 include "event_list.php";

} 


    
}
}

//Front End Development with Shortcode
include "frontend.php"

?>
