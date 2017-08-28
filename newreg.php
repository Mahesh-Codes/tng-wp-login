<?php
//  mahesh@upavadi.net
/*************************
* tng_path and tng_url
* can be obtained 
* from config.php in tng folder
* $rootpath for tng_path and
* $tngdomain for tng_url
*************************/
require_once "newreg_config.php";
require_once "templates/registration_form.html.php";
/** This function drives shortcode [user_registration] ***/
function new_reg()
{
    $keys = keyValues();
    $input = $_POST;
    $newloginname = $_POST['newloginname'];
    $newemail = $_POST['newemail'];
    $config = newRegConfig();
    $data['values'] = $_POST;
    $data['errors'] = validate($_POST);
    echo registration_form($data, $config['reg_form'], $keys);
}

function validate($form)
{
    $errors = array();
    
    if (!isset($form['firstname']) || empty($form['firstname'])) {
        $errors['firstname'] = 'Cannot be empty';
    }
    if (!isset($form['lastname']) || empty($form['lastname'])) {
        $errors['lastname'] = 'Cannot be empty';
    }
    if (!isset($form['loginname']) || empty($form['loginname'])) {
        $errors['loginname'] = 'Cannot be empty';
    }
    if (!isset($form['email']) || empty($form['email'])) {
        $errors['email'] = 'Cannot be empty';
    }
    if (!is_email($form['email'])) {
        $errors['email'] = 'email not valid';
    }
    if (!isset($form['password']) || empty($form['password'])) {
        $errors['password'] = 'Cannot be empty';
    }
    if (strlen($_POST['password']) >= 1 && strlen($_POST['password']) < 8) {
        $errors['password'] = 'Password must be atleast 8 characters';
    }
    if (username_exists($_POST['loginname'])) {
        $errors['loginname'] = 'User Name in use';
    }
    if (email_exists($_POST['email'])) {
        $errors['email'] = 'Email is in use';
        // Return to email input
    }
    
    return $errors;
}

if (validate($_POST)) {
    $newreg_entries = "incomplete";
} else {
    $newreg_entries = "complete";
}

//Check to see if user name in tng and wp
if ($_POST['loginname']) {
    $tng_name_check = $_POST['loginname'];
    $tng_name_Config = nameTng($tng_name_check);
    $wp_name_check = $_POST['loginname'];
    $wp_name_Config = namewp($wp_name_check);
}

//Check to see if email in tng and wp
if ($_POST['email']) {
    $tng_email_check = $_POST['email'];
    $tng_email_Config = emailTng($tng_email_check);
    $wp_email_check = $_POST['email'];
    $wp_email_Config = emailwp($wp_email_check);
}
//check name AND email NOT in wp
$add_reg_WP = "";
if (!$wp_name_Config && !$wp_email_Config) {
    $add_reg_WP = "WP"; // ADD to WP
}
$add_reg_TNG = "";
if (!$tng_name_Config && !$tng_email_Config) {
    $add_reg_TNG = "TNG"; // ADD to TNG
}
$nameInWp = $wp_name_Config;
$emailInWp = $wp_email_Config;
$nameInTng = $tng_name_Config;
$emailInTng = $tng_email_Config;
$conditions = array($nameInWp, $emailInWp, $nameInTng, $emailInTng);
if ($_POST) {
    switch ($conditions) {
    case array(true, true, true, true):
        echo "name & email in both";
        break;
    case array(true, false, false, false):
        echo "name in wp";
    case array(true, true, false, false):
        echo "name & email in wp";
        break;
    case array(true, false, true, false):
        echo "name in wp & tng";
        break;
    case array(true, true, true, false):
        echo "name in wp & tng - email wp";
        break;
    case array(false, true, false, true):
        echo "email in wp & tng";
        break;
    case array(false, true, false, false):
        echo "email in wp only";
        break;
    case array(true, false, true, true):
        echo "name in wp and tng - email in tng only";
        break;
        default:
        echo "default ok to enter";
    }
}
