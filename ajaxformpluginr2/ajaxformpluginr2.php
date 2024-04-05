<?php
/**
 * Plugin Name: My Ajax Form Plugin R2
 * Plugin URI: https://dilshadmustafa.bss.design/
 * Description: Custom Form plugin using jQuery Ajax, PHP, Database, Bootstrap
 * Version: 1.0
 * Author: Dilshad Mustafa
 * Author URI: https://dilshadmustafa.bss.design/
 * License: Mozilla Public License Version 2.0, https://www.mozilla.org/en-US/MPL/2.0/
 */
?>
<?php
// Use shortcode [mydm_datatable] to embed this Form in any page
require('securityPluginCheck.php');


function mydm_datatable_shortcode($atts, $content = null){
    
    $result= mydm_security_check_display();

	if(FALSE === $result){
	    return;
	}

	extract(shortcode_atts(array(), $atts ) );
   	ob_start();

   	require ('frontend-page-template.php');
   	$content = ob_get_clean();

   	return $content;

}

add_shortcode('mydm_datatable', 'mydm_datatable_shortcode' );

function my_plugin_scripts() {
      
        wp_enqueue_script('prefix_jquery', plugins_url( '/js/jquery-3.6.0.min.js' , __FILE__ ) );
      
        wp_enqueue_script('prefix_bootstrap', plugins_url( '/js/bootstrap.bundle.min.js' , __FILE__ ));
            
        wp_enqueue_script('prefix_dt', plugins_url(  '/js/dt-1.10.25datatables.min.js' , __FILE__ ));
                
        wp_enqueue_style('prefix_bootstrap', plugins_url(   '/css/bootstrap5.0.1.min.css' , __FILE__ ));
                
        wp_enqueue_style('prefix_dt', plugins_url(   '/css/datatables-1.10.25.min.css' , __FILE__ ));
        
}
add_action( 'wp_enqueue_scripts', 'my_plugin_scripts' );
?>
<?php
add_action( 'wp_enqueue_scripts', 'mydm_enqueue' );
function mydm_enqueue() {

    wp_enqueue_script( 'mydm-javascript', plugins_url( '/js/myscript.js' , __FILE__ ),  array('jquery'), '', true );

    // in JavaScript, object properties are accessed as ajax_object.ajax_url
    wp_localize_script( 'mydm-javascript', 'ajax_object',
             [
                'ajax_url' => wp_nonce_url(admin_url('admin-ajax.php'), 'mydm-ajax-url'),
                'security'  => wp_create_nonce( 'mydm-security-nonce' ), 
             ]
    );
}

add_action('wp_ajax_nopriv_backendProcess', 'backendProcessCallPHP');
add_action('wp_ajax_backendProcess', 'backendProcessCallPHP');

function backendProcessCallPHP() {
    
    if ( ! check_admin_referer( 'mydm-ajax-url' ) ) {
        wp_send_json_error( 'Invalid url.' );
        wp_die();
    }

    if ( ! check_ajax_referer( 'mydm-security-nonce', 'security', false ) ) {
        wp_send_json_error( 'Invalid request.' );
        wp_die();
    }

    if ( isset( $_POST['route']) ) {
        $route = $_POST['route'];
        switch ($route) {
            case 'processForm1':
                require('processForm1.php');
                break;
            case 'processForm2':
                require('processForm2.php');
                break;
            default:
                print_r("Invalid route");
                break;
        }
    } else {
        print_r("No route specified");
    }

    
    wp_die();
}?>