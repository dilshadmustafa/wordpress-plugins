<?php
require('securityPhpCheck.php');
//print_r($_POST['dataForPHP']);
//echo "dataForPHP " . $_POST['dataForPHP']['2']['value'];

if ( ! wp_verify_nonce( $_POST['dataForPHP']['2']['value'], 'mydm-form1' ) ) {
        wp_send_json_error( 'Invalid form submission.' );
        wp_die();
}

if ( isset( $_POST['dataForPHP']) ) {
        //echo data array
        print_r($_POST['dataForPHP']);
}

?>
