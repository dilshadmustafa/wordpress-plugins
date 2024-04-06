<br /> <br />
<div class="wrap wp-submenu-head widefat">
<a class='button button-primary'    href='<?php echo ''.admin_url('admin.php?page=EventRegistrationForm%2Findex.php&new=true').'' ?>'> Add New Service </a>
<h2>Displaying Results</h2>

<table class="wp-list-table widefat striped">

<thead>

<tr>
    <th> ID </th>
    <th> Title </th>
    <th> Service Date </th>
    <th> Status </th>
    <th> Shortcode </th>
    <th> Auditorium Size </th>
    <th> Conference Size </th>
    <th> Banquet Size </th>
    <th> Body </th>
    <th> Actions </th>
    
</tr>
</thead>

<tbody>
    <?php 

$main_body = ""; 
            $results =  $wpdb->get_results("SELECT * FROM $table_name_2 order by id desc"); 

            //var_dump($results);

            foreach ($results as $print) {

                if($print->active_on == 0) {
                    $status =  "<span style='color:red'>Disabled</span>"; 
                }elseif($print->active_on == 1) {
                    $status =  "<span style='color:darkgreen'>Active</span>"; 
                }else{
                    $status =  "<span style='color:darkorange'>Closed</span>"; 
                }
               $main_body = substr(strip_tags($print->body),0,50);
               $main_title_display = substr(strip_tags($print->title),0,20);

               // var_dump($main_body);

                echo "
                
                    <tr> 
                    <td> $print->id</td>
                    <td> $main_title_display</td>
                    <td> $print->service_day</td>
                    <td>  $status</td>
                    <td>  [events_registration id=$print->id] </td>
                    <td> $print->auditorium</td>
                    <td> $print->conference</td>
                    <td> $print->banquet</td>
                    <td>   $main_body </td>
                    <td> 
                    <a class='button button-primary' href='".admin_url('admin.php?page=EventRegistrationForm%2Findex.php&id='.$print->id.'')."'> View  </a>
                    <a class='button btn-primary' style='color:#fff; background-color:green'   href='".admin_url('admin.php?page=EventRegistrationForm%2Findex.php&upt='.$print->id.'')."'> Edit </a>
                    <a class='button btn-primary' style='color:#fff; background-color:red'   href='".admin_url('admin.php?page=EventRegistrationForm%2Findex.php&del='.$print->id.'')."'>Delete  </a>
                    </td>

                    
                    </tr>
                "; 
            }
    ?>
</tbody>
</table>

</div>