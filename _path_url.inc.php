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

$subdir = array("IPMS/", "SQMS/", "EduMS/", "HEMS/" , "COMS/" , "REPLACER/" , "SCMS/" , "LDMS/", "ASMS/", "TEMS/");
$url_liam = str_replace($subdir,"",$url);
$url_ipms = $url_liam."IPMS";
$url_sqms = $url_liam."SQMS";
$url_edums = $url_liam."EduMS";
$url_hems = $url_liam."HEMS";
$url_coms = $url_liam."COMS";
$url_replacer = $url_liam."REPLACER";
$url_scms = $url_liam."SCMS";
$url_ldms = $url_liam."LDMS";
$url_asms = $url_liam."ASMS";
$url_tems = $url_liam."TEMS";

$filepath	   	= dirname($_SERVER['SCRIPT_FILENAME'])."/";
$filepath_liam 	= str_replace($subdir,"",$filepath);
$filepath_ipms 	= $filepath_liam."/IPMS";
$filepath_sqms 	= $filepath_liam."/SQMS";
$filepath_edums = $filepath_liam."/EduMS";
$filepath_hems 	= $filepath_liam."/HEMS";
$filepath_coms 	= $filepath_liam."/COMS";
$filepath_replacer 	= $filepath_liam."/REPLACER";
$filepath_scms 	= $filepath_liam."/SCMS";
$filepath_ldms 	= $filepath_liam."/LDMS";
$filepath_asms 	= $filepath_liam."/ASMS";
$filepath_tems 	= $filepath_liam."/TEMS";
?>

<div class="container">
  <div class="row">
    <div class="col-lg-1"></div>
    <div class="col-lg-10">
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
echo "</td><tr><td> \$filepath_ipms</td><td>";
if (file_exists($filepath_ipms	)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_ipms);
echo "</td><tr><td> \$filepath_sqms</td><td>";
if (file_exists($filepath_sqms)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_sqms);
echo "</td><tr><td> \$filepath_edums</td><td>";
if (file_exists($filepath_edums)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_edums);
echo "</td><tr><td> \$filepath_hems</td><td>";
if (file_exists($filepath_hems)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_hems);
echo "</td><tr><td> \$filepath_coms</td><td>";
if (file_exists($filepath_coms)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_coms);
echo "</td><tr><td> \$filepath_replacer</td><td>";
if (file_exists($filepath_replacer)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_replacer);
echo "</td><tr><td> \$filepath_scms</td><td>";
if (file_exists($filepath_scms)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_scms);
echo "</td><tr><td> \$filepath_ldms</td><td>";
if (file_exists($filepath_ldms)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_ldms);
echo "</td><tr><td> \$filepath_asms</td><td>";
if (file_exists($filepath_asms)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_asms);
echo "</td><tr><td> \$filepath_tems</td><td>";
if (file_exists($filepath_tems)) {echo "INSTALLED ";} else {echo "NOT INSTALLED ";}
echo "</td><td>";
var_dump($filepath_tems);
echo "</tr></tbody></table>";
echo "</br></br>";

echo "<table class=\"table\"><tbody><tr>";
echo "<td>\$url</td><td>";
var_dump(parse_url($url));
echo "</td><tr><td> \$url_liam</td><td>";
var_dump(parse_url($url_liam));
echo "</td><tr><td> \$url_ipms</td><td>";
var_dump(parse_url($url_ipms));
echo "</td><tr><td> \$url_sqms</td><td>";
var_dump(parse_url($url_sqms));
echo "</td><tr><td> \$url_edums;</td><td>";
var_dump(parse_url($url_edums));
echo "</td><tr><td> \$url_hems;</td><td>";
var_dump(parse_url($url_hems));
echo "</td><tr><td> \$url_coms;</td><td>";
var_dump(parse_url($url_coms));
echo "</td><tr><td> \$url_replacer;</td><td>";
var_dump(parse_url($url_replacer));
echo "</td><tr><td> \$url_scms;</td><td>";
var_dump(parse_url($url_scms));
echo "</td><tr><td> \$url_ldms;</td><td>";
var_dump(parse_url($url_ldms));
echo "</td><tr><td> \$url_asms;</td><td>";
var_dump(parse_url($url_asms));
echo "</td><tr><td> \$url_tems;</td><td>";
var_dump(parse_url($url_tems));
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
  </div>
    <div class="col-lg-1"></div>
  </div>