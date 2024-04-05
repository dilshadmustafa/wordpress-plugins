<?php 
/**
 * Author: Dilshad Mustafa
 */
// This file is meant to be included in custom php files, backend form, db processing php files, etc.
// Security check, best to use an early exit
require_once('securityLibrary.php');
if (!defined('WPINC')) {
     echo "Hi there!  I can not do much when called directly.";
     die;
}

$mydm_security_result= mydm_security_check_display();

if (FALSE === $mydm_security_result) {
    return;
}

?>