<?php 
	include_once("controller/Controller.php");
	$controller = new Controller();
	if(!isset($_GET)){
		echo "Invalid Profile Number;";
	}
	else{
		$controller->profile($_GET['id']);
	}
?>
