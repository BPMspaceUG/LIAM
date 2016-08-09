<?php
// Is the user using HTTPS?
$url_scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
// Host, Port, Path and Filename
$url_server_name = $_SERVER['SERVER_NAME'];
$url_server_port = $_SERVER['SERVER_PORT'];
$url_server_path = dirname($_SERVER['PHP_SELF'])."/";
$url_server_filename = str_replace($url_server_path ,"",$_SERVER['PHP_SELF']);
// Complete the URL
$url =$url_scheme . "://".$url_server_name . ":" . $url_server_port . $url_server_path;

$subdir = array("IPMS/", "SQMS/", "EduMS/", "partner/");
$url_liam = str_replace($subdir,"",$url);
$url_sqms = $url_liam."SQMS";
$url_edums = $url_liam."EduMS";
$url_ipms = $url_liam."IPMS";

$filepath	   	= dirname($_SERVER['SCRIPT_FILENAME'])."/";
$filepath_liam 	= str_replace($subdir,"",$filepath);
$filepath_sqms 	= $filepath_liam."/SQMS";
$filepath_edums = $filepath_liam."/EduMS";
$filepath_ipms 	= $filepath_liam."/IPMS";
?>		

<?php if ($logged == 'in') {
			# Button ADMIN only avaiable when define logged in user has role "admin"--->
			
			echo '<button type="button" class="btn btn-secondary-outline" data-toggle="modal" data-target="#Admin_Modal">Admin</button>';
			echo '<button type="button" class="btn btn-secondary-outline" data-toggle="modal" data-target="#MyProfile_Modal">My Profile</button>';
			echo '<a href="' . $url_liam . 'phpSecureLogin/includes/logout.php" title="Logout" class="btn btn-large btn-success-outline"><i class="fa fa-sign-out"></i>&nbsp;Logout</a>';
		} ?>

<?php 


if (!empty($_GET) && !empty($_GET["debug"]) && ($_GET["debug"] == 'on' )) {

echo "<div class=\"container text-right\">";
echo "<a href='#' class=\"btn collapsed row text-right\" data-toggle=\"collapse\" data-target=\"#debug\"><i class=\"fa fa-caret-square-o-down\"></i></a></div>";
echo "<div style=\"background-color: #ffffff;\">";
echo "<div class=\"row collapse in\" id=\"debug\">";
echo "</br></br><table class=\"table\" ><tbody><tr>";
echo "<td> \$filepath</td><td>";
if (file_exists($filepath)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath);
echo "</td><tr><td> \$filepath_liam</td><td>";
if (file_exists($filepath_liam)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_liam);
echo "</td><tr><td> \$filepath_sqms</td><td>";
if (file_exists($filepath_sqms)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_sqms);
echo "</td><tr><td> \$filepath_edums</td><td>";
if (file_exists($filepath_edums)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_edums);
echo "</td><tr><td> \$filepath_ipms</td><td>";
if (file_exists($filepath_ipms	)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_ipms);
echo "</tr></tbody></table>";
echo "</br></br>";

echo "<table class=\"table\"><tbody><tr>";
echo "<td>\$url</td><td>";
var_dump(parse_url($url));
echo "</td><tr><td> \$url_liam</td><td>";
var_dump(parse_url($url_liam));
echo "</td><tr><td> \$url_sqms</td><td>";
var_dump(parse_url($url_sqms));
echo "</td><tr><td> \$url_edums;</td><td>";
var_dump(parse_url($url_edums));
echo "</td><tr><td> \$url_ipms</td><td>";
var_dump(parse_url($url_ipms));
echo "</tr></tbody></table>";
echo "</br></br>";


$indicesServer = array('PHP_SELF',
'argv',
'argc',
'GATEWAY_INTERFACE',
'SERVER_ADDR',
'SERVER_NAME',
'SERVER_SOFTWARE',
'SERVER_PROTOCOL',
'REQUEST_METHOD',
'REQUEST_TIME',
'REQUEST_TIME_FLOAT',
'QUERY_STRING',
'DOCUMENT_ROOT',
'HTTP_ACCEPT',
'HTTP_ACCEPT_CHARSET',
'HTTP_ACCEPT_ENCODING',
'HTTP_ACCEPT_LANGUAGE',
'HTTP_CONNECTION',
'HTTP_HOST',
'HTTP_REFERER',
'HTTP_USER_AGENT',
'HTTPS',
'REMOTE_ADDR',
'REMOTE_HOST',
'REMOTE_PORT',
'REMOTE_USER',
'REDIRECT_REMOTE_USER',
'SCRIPT_FILENAME',
'SERVER_ADMIN',
'SERVER_PORT',
'SERVER_SIGNATURE',
'PATH_TRANSLATED',
'SCRIPT_NAME',
'REQUEST_URI',
'PHP_AUTH_DIGEST',
'PHP_AUTH_USER',
'PHP_AUTH_PW',
'AUTH_TYPE',
'PATH_INFO',
'ORIG_PATH_INFO') ;

echo "<table class=\"table\"><tbody>" ;
foreach ($indicesServer as $arg) {
    if (isset($_SERVER[$arg])) {
        echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ;
    }
    else {
        echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ;
    }
}
echo "</tbody></table>" ; 

echo "</div></div>";
}
?>

<!-- Modal -->

<div id="MyProfile_Modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">My Profile</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<div class="row alert alert-success text-center lead" role="alert">You are currently logged in.</br>Last logon was: [DATE TIME]</div>
								</div>				
							</div>
						</div>
					</div>
				</div>
				<div class="container-fluid">
					<div class="row">
					</br>
					</div>
				</div>
				


				
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6">
									<div class="lead">Lastename: </br>Firstname:</br>E-Mail:</br>Role:</br></div>	
								</div>
								<div class="col-md-6">
									<div class="lead">[Lastname] </br>[Firstname]</br>[e-mail]</br>[Role1, Role2, ...]</br></div>	
								</div>								

								<div class="col-md-12">
									</br>
									<a data-toggle="modal" data-target="#name_modal"><button type="button" class="btn btn-primary-outline">Change Name</i></button></a>
									<a data-toggle="modal" data-target="#email_modal"><button type="button" class="btn btn-primary-outline">Change E-Mail</i></button></a>
									<a data-toggle="modal" data-target="#password_modal"><button type="button" class="btn btn-primary-outline">Change Password</i></button></a>
								</div>								
								
							
							</div>
						</div>
					</div>
				</div>
			</div>	
			<div class="modal-footer">
				<div class="clearfix"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>


<div class="modal fade" id="password_modal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Change Password</h4>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label for="current_password" class="control-label">Current
						Password</label>
					<div class="controls">
						<input type="password" name="current_password">

					</div>
				</div>
				<div class="control-group">
					<label for="new_password" class="control-label">New Password</label>
					<div class="controls">
						<input type="password" name="new_password">

					</div>
				</div>
				<div class="control-group">
					<label for="confirm_password" class="control-label">Confirm
						Password</label>
					<div class="controls">
						<input type="password" name="confirm_password">

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="email_modal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Change E-Mail</h4>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label for="new_E-Mail" class="control-label">E-Mail</label>
					<div class="controls">
						<input type="E-Mail" name="new_E-Mail" placeholder="[E-MAIL}">
					</div>
				</div>
				<div class="control-group">
					<label for="confirm_E-Mail" class="control-label">Confirm E-Mail</label>
					<div class="controls">
						<input type="E-Mail" name="confirm_E-Mail">

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="name_modal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Change Name</h4>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label for="new_firstname" class="control-label">Firstname</label>
					<div class="controls">
						<input type="Firstname" name="new_Firstname" placeholder="[FIRSTNAME}">
					</div>
					<label for="new_lastname" class="control-label">Lastname</label>
					<div class="controls">
						<input type="Lastname" name="new_Lastname" placeholder="[LASTNAME}">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>

