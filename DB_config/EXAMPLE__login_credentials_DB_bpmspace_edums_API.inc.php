<?php
/**
 * Created by PhpStorm.
 * User: cwalonka
 * Date: 06.10.15
 * Time: 19:06
 */
$config['db']['host'] = "localhost";
$config['db']['user'] = "bpmspace_edums";
$config['db']['password'] = "";
$config['db']['database'] = "bpmspace_edums";
$db = new mysqli($config['db']['host'],$config['db']['user'],$config['db']['password'], $config['db']['database']);
/* check connection */
if($db->connect_errno){
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$config['text']['defaultfooter'] = "powered by mITSM.de";

$db->query("SET NAMES utf8");
?>
