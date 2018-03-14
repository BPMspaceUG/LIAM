$api = new PHP_CRUD_API(array(
	'dbengine'=>'MySQL',
	'hostname'=>'localhost',
	'username'=>'bpmspace_coms_RO',
	'password'=>'d85b9220-2d9d-4cfd-9bc8-9a69d3c8d9b3',
	'database'=>'bpmspace_coms_v1',
	'charset'=>'utf8mb4'
));
$api->executeCommand();