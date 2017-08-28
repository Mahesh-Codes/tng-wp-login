<?php
//require_once "newreg_config.php"; 
require_once "templates/view_profile.html.php";
require_once "updateprofile.php";
/** This function drives shortcode [user_registration] ***/
function show_profile() {
	$configPath = getTngPath() . "config.php";
	require_once ($configPath);
	$db = mysqli_connect($database_host, $database_username, $database_password, $database_name);
		if ($db->connect_error) {
			die("Connection failed: " . $db->connect_error);
		}
	//$inputProfile = $_POST;
	$wp_currentuser = (get_currentuser());
	$wp_currentuser_meta = (get_currentuser_meta());
	$profile_user = $wp_currentuser;
	$profile_meta = $wp_currentuser_meta;
	$config = newRegConfig();
	$data['values'] = $wp_currentuser;
	$data_meta['values']= $wp_currentuser_meta;
	$def_photo_path = get_tng_default_photo($db);
	$data['errors'] = validate_profile($_POST);
	//var_dump($data['errors']);
	if (empty($data['errors']) && $_POST['submitProfile'])  {
		$post = $_POST;
		update_wp($post, $data);
		
		}
		
	$data3 = (array_merge($data, $data_meta));
	echo view_profile($data, $data_meta, $def_photo_path, $config['show_profile']);
	//profile_complete($_POST, $data, $data_meta);
}

function get_currentuser() {
	$current_user = wp_get_current_user();
	return ($current_user);
}
function get_currentuser_meta() {
	$current_ID = get_current_user_id();
	$currentuser_meta = get_user_meta($current_ID);
	return ($currentuser_meta);
}
$wp_user = get_currentuser();
function get_tng_user($db) {
	$tng_name = (get_currentuser()->user_login);

	$sql = "SELECT * FROM tng_users WHERE username='$tng_name'";
	$result = $db->query($sql);	
	if ($result) {
		$row = $result->fetch_assoc();
		$tng_username = $row["username"];
	}
	return $row;
}

function get_tng_default_photo($db) {
	$tng_user = get_tng_user($db);
	$personID = $tng_user['personID'];
	$def_photo = "SELECT * FROM tng_medialinks WHERE personID='$personID' AND defphoto=1";
	$result = $db->query($def_photo);	
	if ($result) {
		$row = $result->fetch_assoc();
		$tng_defaultphotoID = $row["mediaID"];
	}
	$def_photo = "SELECT thumbpath FROM tng_media WHERE mediaID='$tng_defaultphotoID'";
	$result = $db->query($def_photo);	
	if ($result) {
		$row = $result->fetch_assoc();
		$tng_defaultphoto_path = $row["thumbpath"];
	}
	return $tng_defaultphoto_path;
}
	
function validate_profile($form) {
	$errors = array();
	
	if (isset($_POST['submitProfile'])) {
	if (empty($form['first_name'])) {
		$errors['first_name'] = 'Cannot be empty';
	}
	
	if (empty($form['last_name'])) {
		$errors['last_name'] = 'Cannot be empty';
	}
	
	
	if (empty($form['user_email'])) {
		$errors['user_email'] = 'Cannot be empty';
	}
	if (!is_email($form['user_email'])) {
		$errors['user_email'] = 'email not valid';
	}
	
		if (strlen($form['new_pass']) > 0 && strlen($form['new_pass']) < 8 ) {
		$errors['new_pass'] = 'New Password must be atleast 8 characters';
		}
	}	
		return $errors;
}
