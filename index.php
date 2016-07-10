<?php
	include_once '_header.inc.php';
?>
</br>
<?php
	if ($logged == 'out')
		include_once 'login.inc.php';
?>
</br>
<div class="container centering 90_percent well">

	<div class="row">
		<div class="col-md-4">
			<a href="EduMS/"><img
				src="images/bpmspace_icon-EduMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Education Management System" width="304" height="236"></a>
		</div>
		<div class="col-md-4">
			<a href="SQMS/"><img src="images/bpmspace_icon-SQMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Syllabus & Question Management System" width="304" height="236"></a>
		</div>
		<div class="col-md-4">
			<a href="#"><img src="images/bpmspace_icon-LDMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Lightweight Document Management System" width="304"
				height="236"></a>
		</div>
		</br></br>
		<div class="col-md-4">
			<a href=".index.php"><img
				src="images/bpmspace_icon-LIAM-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Lightweight Identity & Access Management" width="304"
				height="236"></a>
		</div>
		<div class="col-md-4">
			<a href="#"><img src="images/bpmspace_icon-SCMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Simple Configuration Management System" width="304"
				height="236"></a>
		</div>
		<div class="col-md-4">
			<a href="IPMS/"><img src="images/bpmspace_icon-IPMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Intelligent Process Management System" width="304" height="236"></a>
		</div>
</div>
</div>


<?php
echo '<div class="container centering 90_percent">';
include_once 'setup.inc.php';
echo '</br></div>';
?>
	
<div class="clearfix"></div>

<?php
include_once '_footer.inc.php';
?>

