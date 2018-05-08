<?php 
$api = new PHP_CRUD_API(array(
	'dbengine'=>'MySQL',
	'hostname'=>'localhost',
	'username'=>'bpmspace_coms_RO',
	'password'=>'',
	'database'=>'bpmspace_coms_v1',
	'charset'=>'utf8mb4'
));
$api->executeCommand();
?>