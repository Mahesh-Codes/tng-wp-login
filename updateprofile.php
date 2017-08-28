<?php
require_once "templates/view_profile.html.php";
require_once "showprofile.php";

function profile_complete($_POST) {

   if (isset($data['submitProfile'])) {
        update_wp($_POST, $data, $data_meta);
    }
}
$display_data = $data['values']->data;
$NickName = $display_meta[nickname][0];


function update_wp($post, $data) {
$Id = $data['values']->data->ID;
	global $wpdb;
	$userdata = array(
  	'ID'  => $Id,
	'user_email'   =>  $post['user_email'],
	'display_name'   =>  $post['display_name'],
	'nickname'   =>  $post['nickname'],
	'first_name'   =>  $post['first_name'],
	'last_name'   =>  $post['last_name'],
	'description'   =>  $post['description'],
	'show_admin_bar_front'   =>  false
	);
	if ($post['new_pass']) {
	$newpass = array(
  	'user_pass'  => $post['new_pass']
	);
	$userdata = array_merge($userdata, $newpass);
	}
	wp_update_user($userdata);
	update_user_meta($Id, 'tng_interest', $post['tng_interest']);
	update_tng($post, $data);
	}

function update_tng($post, $data) {
$tngPath = getTngPath(). "config.php";
include ($tngPath);
$password_type = $tngconfig['password_type'];
$db = mysqli_connect($database_host, $database_username, $database_password, $database_name);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
	}
$userName = $_POST['user_login'];
$description = $_POST['display_name'];
$hashed_pass = $password_type($_POST['new_pass']);
$notes = $_POST['description'];;
$realname = $_POST['firstname']. " ". $_POST['lastname'];;
$email = $_POST['email'];
if ($_POST['new_pass']) {
$sql = "UPDATE tng_users SET description='$description', email='$email',  realname='$realname', notes='$notes', password='$hashed_pass' WHERE username='$userName' ";
} else {	
$sql = "UPDATE tng_users SET description='$description', email='$email',  realname='$realname', notes='$notes' WHERE username='$userName' ";
}
if ($db->query($sql) === TRUE) {
    echo "<div style='color: green;'>". "<b>Your changes have been updated</b>"."</div>";
} else {
    echo "Ooops: something went wrong. Please try again " . $db->error;
}
}


