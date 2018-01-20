<?php
	include_once '_path_url.inc.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BPMspace LIAM</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/liam.css">

<style>
#liam-header { margin-top: 5px; float: right;}

</style>
<!----- js scripts are loaded in the footer -------------------->
</head>
<body>
<?php
	if (empty ( $_GET ["error_messages"] )) {
		include_once 'phpSecureLogin/includes/db_connect.inc.php';
		include_once 'phpSecureLogin/includes/functions.inc.php';
		
		sec_session_start ();
		
		if (login_check ( $mysqli ) == true) {
			$logged = 'in';
		} else {
			$logged = 'out';
			//var_dump (basename($_SERVER['PHP_SELF']));
			if((basename($_SERVER['PHP_SELF'])) != 'index.php' ) {
				header("Location: index.php?error_messages='You are not logged in!'");
			}
		}
	} else {
		$logged = 'out';
	}
?>
	<div class="container">
		<div class="row" style="background-color:#003296;" >
			<div class="col-md-7">
				<a href="./index.php"> <img style="margin-left: -14px;" src="images/BPMspace_logo_small.png" class="img-responsive" alt="BPMspace Development" /> </a>
			</div>		
			<div class="col-md-5 text-right" id="liam-header">
				<?php
				include_once '_header_LIAM.inc.php';
				?>			
			</div>
		</div>
	</div>		
<?php
	/* presente $error_messages when not empty */
	if (! empty ( $_GET ["error_messages"] )) {
		echo '<div class="container alert alert-danger centering 90_percent" role="alert"; > <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>';
		echo '&nbsp;error:&nbsp;' . htmlspecialchars ( $_GET ["error_messages"] );
		echo '</div></br>';
	}
?>
<!---------------- MODAL ------------------------------->
