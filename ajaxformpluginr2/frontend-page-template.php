<?php
echo "<form id='my-form'>
    Select Date Range:
    <input type='date' name='date1'>
    <input type='date' name='date2'>"

    . wp_nonce_field( 'mydm-form1' ) . 

    "<input type='submit' id='submit' value='submit'>
    </form>";
?>