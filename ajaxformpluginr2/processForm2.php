<?php
require('securityPhpCheck.php');

if ( isset( $_POST['dataForPHP']) ) {
        //echo data array
        print_r($_POST['dataForPHP']);
}

?>
