<?php
	include_once '_header.inc.php';
	define('BACKUP_DIR', "./backup");

?>

<div class="container centering 90_percent">

	<div class="row" >
		<div class="col-md-12">

		<li>Checking the environment ... </li>
		<li>Running as <b><?php echo trim(shell_exec('whoami')); ?></b></li></br>
		<?php
	// Check if the required programs are available
	// https://github.com/markomarkovic/simple-php-git-deploy/blob/master/deploy.php */
		$requiredBinaries = array('git', 'rsync');
		if (defined('BACKUP_DIR') && BACKUP_DIR !== false) {
			$requiredBinaries[] = 'tar';
			if (!is_dir(BACKUP_DIR) || !is_writable(BACKUP_DIR)) {
				die(sprintf('<div class="error">BACKUP_DIR `%s` does not exists or is not writeable.</div>', BACKUP_DIR));
			}
		}
		foreach ($requiredBinaries as $command) {
		$path = trim(shell_exec('which '.$command));
		if ($path == '') {
			die(sprintf('<div class="error"><b>%s</b> not available. It needs to be installed on the server for this script to work.</div>', $command));
		} else {
			$version = explode("\n", shell_exec($command.' --version'));
			printf('<b>%s</b> : %s'."</br>"
				, $path
				, $version[0]
			);
		}
		}
		?>

		</div>
	</div>
</div>
<?php
	include_once '_footer.inc.php';
?>