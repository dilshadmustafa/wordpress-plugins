<?php 
/**
 * Author: Dilshad Mustafa
 */
// This file is meant to be included in main plugin file only.
// In main plugin file, explicitly call php functions defined in securityLibrary.php file 
// and take action accordingly.
// Security check, best to use an early exit
require_once('securityLibrary.php');
if (!defined('WPINC')) {
     echo "Hi there!  I can not do much when called directly.";
     die;
}
?>