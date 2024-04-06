<?php



global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
$table_name_details = 'mydm_members_registration';

$my_form_id = $_GET['id'];

$table_name2 = 'mydm_service_setup'; 

$result =  $wpdb->get_results("SELECT * FROM $table_name2 WHERE id ='".$my_form_id."'");

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

?>

<br />
    <div class="wrap">
    <h2>Displaying Registrations for : <?php echo "".$title_e."" ?> </h2>

        <table class="wp-list-table widefat striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>

                <th>Hall</th>
                <th>Attendants</th>
                <th>Service Date</th>
                <th>Comments</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $result1 = $wpdb->get_results("SELECT * FROM $table_name_details WHERE form_id = " . $my_form_id ." order by user_id asc");
            foreach ($result1 as $print1) {
                if($print1->classification == 1){ $status_classification = "<span style='color:red'>Auditorium</span>";}else if($print1->classification == 2) { $status_classification = "<span style='color:darkgreen'>Conference Hall</span>";}else{ $status_classification = "<span style='color:darkorange'>Banquet Hall</span>"; }

//               $main_body = substr($print1->body,0,50);
                echo "

              <tr>
                <td>$print1->user_id</td>
                <td>$print1->first_name</td>
                <td>$print1->last_name </td>
                <td>$print1->email</td>
                <td>$print1->phone</td>
                
                <td>$status_classification</td>
                <td>$print1->attendant </td>
                <td>$print1->service_day</td>
                <td>$print1->comment </td>
                
              </tr>
            ";
            }
            ?>
        </tbody>
        </table>
    </div>
        <br>
        <br>

    </div>


