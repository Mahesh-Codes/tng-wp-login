<?php
/*
* JSON file used is config.json.
* Sections: "label": Title for each section
* Fields: "name": Name of the field,
*	"enabled": true to display false to hide
*	"textenabled": true for text area else false ,
*	"type": "text",
*	"label": "Label for field",
*	"description": "Field Description",
*	"placeholder": "Text inside the field"
*
*/
function keyValues() {
	static $key_value;
	if (!$key_value) {
		$key_url = plugin_dir_url( __FILE__ ). "keyValue.json";
		$key_value = json_decode(file_get_contents($key_url), true);
	}
	return $key_value;
}

function newRegConfig() {
	static $config;
	
	if (!$config) {
		$url = plugin_dir_url( __FILE__ ). "config.json";
		$config = json_decode(file_get_contents($url), true);
	}
	return $config;
}
function getTng_photo_folder() {
	$config = newRegConfig();
	$tngPhotos = $config['paths']['tng_photo_folder'];
	return $tngPhotos;
}
function getTngUrl() {
	$config = newRegConfig();
	$tngUrl = $config['paths']['tng_url'];
	if (!$tngUrl) {
	   // Show an error and die
	   echo "TNG url is not configured";
	   die;
	}
	return $tngUrl . DIRECTORY_SEPARATOR;
}

function getTngPath() {
	$config = newRegConfig();
	$tngPath = $config['paths']['tng_path'];
	if (!$tngPath) {
	   // Show an error and die
	   echo "TNG path is not configured";
	   die;
	}
	$tngPath = realpath($tngPath);
	if (!$tngPath) {
	   // Show an error and die
	   echo "TNG path is not real";
	   die;
	}
	return $tngPath . DIRECTORY_SEPARATOR;
}

function nameTng($tng_name_check) {
//does user name in tng exist
$tng_path = getTngPath(). "config.php";
require_once ($tng_path); // absolute path!!!
$db = mysqli_connect($database_host, $database_username, $database_password, $database_name);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
	}

$sql = "SELECT username FROM tng_users WHERE username='$tng_name_check'";
$result = $db->query($sql);
if ($result) {
$row = $result->fetch_assoc();
$tng_username = $row["username"];
}
if ($tng_username) {
$tng_name_Config = true;
} else {
$tng_name_Config = false;
}
mysqli_close($db);
return $tng_name_Config;
}

function emailTng($tng_email_check) {
//does email in tng exist
$tngPath = getTngPath(). '/config.php';
include ($tngPath); // NEED TO USE __dir__!!!
$db = mysqli_connect($database_host, $database_username, $database_password, $database_name);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
	}
$sql = "SELECT email FROM tng_users WHERE email='$tng_email_check'";
$result = $db->query($sql);
$row = $result->fetch_assoc();
$tng_email = $row["email"];
if ($tng_email) {
$tng_email_Config = true;
} else {
$tng_email_Config = false;
}
return $tng_email_Config;
}
//check name in wp
function namewp($wp_name_check) {
if (username_exists($_POST['loginname'])) {
$wp_name_Config = True;

} else {
$wp_name_Config = False;
}
return $wp_name_Config;
}
//check email in wp
function emailwp($wp_email_check) {
if (email_exists($_POST['email'])) {
$wp_email_Config = True;

} else {
$wp_email_Config = False;
}
return $wp_email_Config;
}