<!--
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
-->
<?php
require_once "recaptchalib.php"; 
function input($field, $label, $description, $placeholder, $value, $error, $type = 'text') {
	$errorClass = '';	
	if ($error) {
		$errorClass = 'has-error';
	}

?>
<div class="row rowadjust <?php echo $errorClass; ?>">
	<div class="col-md-2 entrylabel"><?php echo $label; ?></div>
	<div class="col-md-4">
		<input type="<?php echo $type; ?>" 
			   class="form-control" 
			   name="<?php echo $field; ?>" 
			   placeholder="<?php echo $placeholder; ?>" 
			   value="<?php echo $value; ?>">
	</div>
	<div class="col-md-6"><?php echo $description; ?></div>
</div>
<?php if ($error): ?>
<div class="row rowadjust">
	<div class="col-md-12">
		<p class="text-danger"><?php echo $error; ?></p>
	</div>
</div>
<?php endif; ?>
<?php
}


function registration_form($data, $config, $keys) {
ob_start();

if(isset($_POST['g-recaptcha-response'])){
          $captcha=$_POST['g-recaptcha-response'];
        }
if(!$captcha && $_POST){
          $captchaAlert = '<h2>Please check the the captcha form.</h2>';
          
    }
/***
	$secretKey = $keys['key1'];
$ip = $_SERVER['REMOTE_ADDR'];
$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
$responseKeys = json_decode($response,true);
if(intval($responseKeys["success"]) !== 1) {
$captcha = '<h2>You are spammer ! Get the @$%K out</h2>';
        } else {
          echo '<h2>Thanks for posting comment.</h2>';
        }
**/	
?>



<div class="container-fluid">
<form method="post">
<?php
	foreach ($config['sections'] as $section) {
?>
<div class="regsubtitle"><?php echo $section['label']; ?></div>
<div class="regsections">
<?php
		foreach($section['fields'] as $spec) {
			$field = $spec['name'];
			if ($spec['enabled'] === false) {
		
			continue;
			}
			if ($spec['textenabled'] === false) {
			$value = $data['values'][$field];
			$error = $data['errors'][$field] ?: '';
			
			input($field, $spec['label'], $spec['description'], $spec['placeholder'], $value, $error, $spec['type']);
			} else {
			$value = $data['values'][$field];
			?>
		<div class="row rowadjust"><!--Interest -->
			<div class="col-md-2 entrylabel"><?php echo $spec['label']; ?></div>
			<div class="col-md-8"><textarea class="form-control" name="<?php echo $spec['name']; ?>" placeholder="<?php echo $spec['placeholder']; ?>"><?php echo $value; ?></textarea></div>
		
		</div>
		<?php
		}
	}
	
?>
</div>
<?php		
	}

	?>
<!--
	<div class="captcha g-recaptcha" data-sitekey="<?php echo $keys['key1']; ?>"></div>
-->	
<?php echo $captchaAlert; ?>
</div>
	
	<?p
    <div id="alertText"><br /></div>
<input type="submit" onclick="varified()" value="Submit Request">
</form>
<script src='https://www.google.com/recaptcha/api.js'></script>
</div><!--container-->
<?php
		
	return ob_get_clean();
}

?>
<script>
function varified(event) {
console.log(event)
console.log(grecaptcha.getResponse())
if (grecaptcha.getResponse() == ""){
    document.getElementById("alertText").innerHTML = "<b>Please verify that you are not a robot.";
	document.getElementById("theForm").value="";
	} else {
    document.getElementById("theForm").submit();
}
return;
}
</script>
