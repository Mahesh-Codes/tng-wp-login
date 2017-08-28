<?php
require_once "newreg_config.php"; 
require_once "templates/registration_complete.html.php";
function newreg_complete() {
	$input = $_POST;
	$newloginname = $_POST['newloginname'];
	$newemail = $_POST['newemail'];
	$config = newRegConfig();
	$data['values'] = $_POST;
	$data['errors'] = validate($_POST);
	
	echo registration_complete($data, $config['reg_complete']);
	}
	if(validate($_POST)) {
	
	$newreg_entries = "incomplete";
	} else {
	$newreg_entries = "complete";
	insertUserWp($_POST);
	insertUserTng($_POST);
	registration_mail($_POST);
	}

function insertUserWP($_POST) {
	global $wpdb;
	$userdata = array(
    'user_login'  =>  $_POST['loginname'],
    'user_pass'   =>  $_POST['password'],
	'user_nicename'   =>  $_POST['loginname'],
	'user_email'   =>  $_POST['email'],
	'display_name'   =>  $_POST['firstname']. " ". $_POST['lastname'],
	'nickname'   =>  $_POST['nickName'],
	'first_name'   =>  $_POST['firstname'],
	'last_name'   =>  $_POST['lastname'],
	'description'   =>  $_POST['bioinfo'],
	'tng_interest'   =>  $_POST['tng_interest'],
	'show_admin_bar_front'   =>  false
	
	);
	wp_insert_user($userdata);
	$newuser = get_user_by('login', $_POST['loginname']);
	$Id = ($newuser->ID);
	add_user_meta($Id, 'tng_interest', $_POST['tng_interest'], false);
}
function insertUserTng($_POST) {
$tngPath = getTngPath(). "config.php";
include ($tngPath);
$password_type = $tngconfig['password_type'];
$db = mysqli_connect($database_host, $database_username, $database_password, $database_name);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
	}
$username = $_POST['loginname'];
$description = $_POST['firstname']. " ". $_POST['lastname'];
$hashed_pass = ($_POST['password']);
$role = "guest";
$allow_living = -1;
$realname = $description;
$email = $_POST['email'];
$notes = $_POST['bioinfo'];
$date_registered = date('Y-m-d H:i:s');
$sql = "INSERT INTO tng_users (username,description,password,password_type,role,allow_living,realname,email,notes,dt_registered)
VALUES ('$username', '$description', '$hashed_pass', '$password_type', '$role', '$allow_living', '$realname', '$email', '$notes', '$date_registered')";
if ($db->query($sql) === TRUE) {
    echo "Registration Success";
} else {
    echo "Error: " . $sql . "<br>" . $db->error;
}

$db->close();
}

function registration_mail($_POST) {
$date = date('c');
$email = esc_attr(get_option('tng-api-email'));
$msg = <<<MSG
New registration Request({$date}):

MSG;
$msg .= print_r($_REQUEST, true);
echo "<pre>{$msg}</pre>";
//mail($email, 'New data', $msg);

}

