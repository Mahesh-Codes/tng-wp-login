<!--
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
-->
<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
/*
Plugin Name: tng-wordpress-login-byMahesh
Plugin URI: https://github.com/upavadi/TngApi
Description: login to TNG and Wordpress, allow new registrations and show user profiles
Version:     1.0.0
Author:      Mahesh Upadhyaya
Author URI:  https://developer.wordpress.org/
License:     MIT
License URI: Lhttp://opensource.org/licenses/MIT
*/

require_once(ABSPATH . 'wp-includes/pluggable.php'); //this to allow "if username_exists to work
require_once 'newreg.php';
require_once 'newregcomplete.php';
require_once 'showprofile.php';
add_action( 'wp_enqueue_scripts', 'add_tng_wp_login_stylesheets' );
function add_tng_wp_login_stylesheets() {
		wp_register_style( 'register-tngapi_bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css');
		wp_enqueue_style( 'register-tngapi_bootstrap' );
		wp_register_style( 'register-tng_wp_css', plugins_url('css/newreg.css', __FILE__) );
		wp_enqueue_style( 'register-tng_wp_css' );
} 
add_shortcode('user_profile', 'show_profile');

if ($newreg_entries == "incomplete") {
add_shortcode('user_registration', 'new_reg');
} elseif ($newreg_entries == "complete" && $add_reg_WP = "WP") {
add_shortcode('user_registration', 'newreg_complete');
}

