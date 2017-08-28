
<?php

$ids[$table] = $field;
$data[$table][$row][$field] = $value;

//print_r($data);

function registration_complete($data, $config) {
ob_start();

?>
<div class="regsubtitle"><?php echo $config['title']; ?></div>
<div class="container-fluid">
	<div class="regsections">
	<?php echo $config['line1'];
		echo $config['line2'];
		echo $config['line3'];
		echo $config['line4'];

	?>
	
	

</div>	
<?php
	return ob_get_clean();
	}
?>