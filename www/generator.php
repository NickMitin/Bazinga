<?php
  
	require_once($_SERVER['DOCUMENT_ROOT'] . '/global.php');
	print $application->generator->generate($_SERVER['REQUEST_URI']);
  
?>
