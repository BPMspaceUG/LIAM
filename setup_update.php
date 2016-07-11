<?php
	include_once '_header.inc.php';
?>
</br>

</br>
<h1>Pseudo Code for setup and update Script "setup_update.php" for BPMspace</h1>
</br>

<ul>
<li>case 1) setup a new BPMspace Suite</li>
<li>case 2) update an existing BPMSpace Suite or add another BPMspace-App</li>
<li>NOTE for every APP the APP LIAM is neccesary - therfor 2 repositories have to be installed in the wwwroot directory</li>
<li>Assume that at least setup-update.php is in the [wwwroot directory]</li>
<li>for EACH App the follwing parameters shall be stored in in a config.inc.php file </li>
<ol>
<li>APP_ID</li>
<li>APP_NAME</li>
<li>git_repostitory (URL)</li>
<li>path to sql_dump_create_user_and_grant</li>
<li>path to sql_dump_schema_no_data_last_release</li>
<li>path to sql_dump_diff_structur_after_last_release</li>
<li>path to sql_dump_minimum_data_last_release</li>
<li>path to sql_dump_demo_data_last_release</li>
</ol>
<li>please change in file "_header_LIAM.inc.php" the "Button Admin" - it shall be a dropdown menu (bootstrap) with 2 submenues</br>see http://www.w3schools.com/bootstrap/bootstrap_navbar.asp -> Navigation Bar With Dropdown</li>
<ol>
<li>setup & upgrade with link to setup_update.php</li>
<li>user management - link comes later</li>
</ol>
<li> The menu ADMIN should only be present when the logged in user has role "admin" -> secure_login_v2.role </li>
</ul>
<h1>Pseudo Code Script </h1>
<ol>
<li>check if user is logged in AND user belongs to role "admin" -> secure_login_v2.role - if not peresent index.php (LIAM Start Page) </li>
<li>if folder ".git" AND "phpSecureLogin/.git" is present set $UPDATE == TRUE else $UPDATE == FALSE </li>
<ol>
<li>if NOT $UPDATE </li>
<ol>
<li>git clone https://github.com/BPMspaceUG/LIAM.git . #the "DOT" Is important otherwise a subfolder is created</li>
<li>git clone https://github.com/BPMspaceUG/phpSecureLogin.git   #without a "DOT" at the ende - a subfolder is created</li>
<li>ask for the addiitional APS to be installe (depending on the config.inc.php) - at least one more app MUST be instelled e.g. EduMS , IPMS , SQMS </li>
<li>ask for the mysql connection and credentials (-server: default localhost -user: default root -port: default 3306 -password: default empty) - don't store the rootpasswd in a file it is only needed for the DB creation</li>
<li>for each selected APP clone the git repostitory - remember the info wher to find the repo is stored in config.inc.php which is allready cloned (root of LIAM)</li>
<li>for LIAM
<ol>
<li>create DB with name `secure_login_v2` (as defined in config.inc.php) and restore data from $sql_dump_schema_no_data_last_release</li>
<li>update DB Shema of `secure_login_v2` with infomation in $sql_dump_diff_structur_after_last_release</li>
<li>input DATA in `secure_login_v2` with infomation in $sql_dump_minimum_data_last_release</li>
<li>ask if demo data shall be installed? -> input DATA in `secure_login_v2` with infomation in path to $sql_dump_demo_data_last_release</li>
<li>exceute sql_dump_create_user_and_grant.sql</li>
</ol>
<li>for all selected APPS</li>
<ol>
<li>create DB with name as defined in config.inc.php and restore data from $sql_dump_schema_no_data_last_release</li>
<li>update DB Schema of with infomation in $sql_dump_diff_structur_after_last_release</li>
<li>input DATA in `secure_login_v2` with infomation in $sql_dump_minimum_data_last_release</li>
<li>ask if demo data shall be installed? -> input DATA in `secure_login_v2` with infomation in path to $sql_dump_demo_data_last_release</li>
<li>exceute sql_dump_create_user_and_grant.sql</li>
</ol>
</ol>



<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
</ol>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>







</ol>

<?php
include_once '_footer.inc.php';
?>
