<?php
	include_once '_header.inc.php';
	//print_r($_SERVER);
?>
</br>
<?php
	if ($logged == 'out')
		include_once 'login.inc.php';
?>
</br>
<div class="container centering 90_percent">

	<div class="row" >
		<div class="col-md-4">
			<a href="EduMS/"><img
				src="images/bpmspace_icon-EduMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Education Management System" width="304" height="236"></a>
		</div>
		<div class="col-md-2" style="margin-top: 30px">
			<?php
			$content = "";
			$content = @file_get_contents("EduMS/.git/config");
			if (!empty($content) && strpos($content,"https://github.com/BPMspaceUG/EduMS.git")){
				echo "<i class=\"fa fa-check fa-2x  text-success\" aria-hidden=\"true\"></i>";
			} else {
				echo "<i class=\"fa fa-ban fa-2x text-danger\"></i>";
			} 			
			?>
		</div>
		<div class="col-md-4">
			<a href="SQMS/"><img src="images/bpmspace_icon-SQMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Syllabus & Question Management System" width="304" height="236"></a>
		</div>
		<div class="col-md-2" style="margin-top: 30px">
			<?php
			$content = "";
			$content = @file_get_contents("SQMS/.git/config");
			if (!empty($content) && strpos($content,"https://github.com/BPMspaceUG/SQMS.git")){
				echo "<i class=\"fa fa-check fa-2x  text-success\" aria-hidden=\"true\"></i>";
			} else {
				echo "<i class=\"fa fa-ban fa-2x text-danger\"></i>";
			} 			
			?>
		</div>
		<div class="col-md-4">
			<a href="COMS/"><img src="images/bpmspace_icon-COMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Certification Organization Management System" width="304"
				height="236"></a>
		</div>
		<div class="col-md-2" style="margin-top: 30px">

			<?php
			$content = "";
			$content = @file_get_contents("COMS/.git/config");
			if (!empty($content) && strpos($content,"https://github.com/BPMspaceUG/COMS.git")){
					echo "<i class=\"fa fa-check fa-2x  text-success\" aria-hidden=\"true\"></i>";
			} else {
				echo "<i class=\"fa fa-ban fa-2x text-danger\"></i>";
			} 			
			?>
		</div>
		<div class="col-md-4">
			<a href="./index.php"><img
				src="images/bpmspace_icon-LIAM-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Lightweight Identity & Access Management" width="304"
				height="236"></a>
		</div>
		<div class="col-md-2" style="margin-top: 30px">

			<?php
			$content = "";
			$content = @file_get_contents("./.git/config");
			if (!empty($content) && strpos($content,"https://github.com/BPMspaceUG/LIAM.git")){
					echo "<i class=\"fa fa-check fa-2x  text-success\" aria-hidden=\"true\"></i>";
			} else {
				echo "<i class=\"fa fa-ban fa-2x text-danger\"></i>";
			} 			
			?>
		</div>
		<div class="col-md-4">
			<a href="#"><img src="images/bpmspace_icon-SCMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Simple Configuration Management System" width="304"
				height="236"></a>
		</div>
		<div class="col-md-2" style="margin-top: 30px">

			<?php
			$content = "";
			$content = @file_get_contents("SCMS/.git/config");
			if (!empty($content) && strpos($content,"https://github.com/BPMspaceUG/SCMS.git")){
					echo "<i class=\"fa fa-check fa-2x  text-success\" aria-hidden=\"true\"></i>";
			} else {
				echo "<i class=\"fa fa-ban fa-2x text-danger\"></i>";
			} 			
			?>
		</div>
		<div class="col-md-4">
			<a href="IPMS/"><img src="images/bpmspace_icon-IPMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Intelligent Process Management System" width="304" height="236"></a>
		</div>
		<div class="col-md-2" style="margin-top: 30px">

			<?php
			$content = "";
			$content = @file_get_contents("IPMS/.git/config");
			if (!empty($content) && strpos($content,"https://github.com/BPMspaceUG/IPMS.git")){
					echo "<i class=\"fa fa-check fa-2x  text-success\" aria-hidden=\"true\"></i>";
			} else {
				echo "<i class=\"fa fa-ban fa-2x text-danger\"></i>";
			} 			
			?>
		</div>
		<div class="col-md-4">
			<a href="HEMS/"><img src="images/bpmspace_icon-HEMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Hacking Enviroment Management System" width="304" height="236"></a>
		</div>
		<div class="col-md-2" style="margin-top: 30px">

			<?php
			$content = "";
			$content = @file_get_contents("HEMS/.git/config");
			if (!empty($content) && strpos($content,"https://github.com/BPMspaceUG/HEMS.git")){
				echo "<i class=\"fa fa-check fa-2x  text-success\" aria-hidden=\"true\"></i>";
			} else {
				echo "<i class=\"fa fa-ban fa-2x text-danger\"></i>";
			} 			
			?>
		</div>
		<div class="col-md-4">
			<a href="REPLACER/"><img src="images/bpmspace_icon-REPLACER-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Multilingual Replacer Application" width="304" height="236"></a>
		</div>
		<div class="col-md-2" style="margin-top: 30px">

			<?php
			$content = "";
			$content = @file_get_contents("REPLACER/.git/config");
			if (!empty($content) && strpos($content,"https://github.com/BPMspaceUG/REPLACER.git")){
				echo "<i class=\"fa fa-check fa-2x  text-success\" aria-hidden=\"true\"></i>";
			} else {
				echo "<i class=\"fa fa-ban fa-2x text-danger\"></i>";
			} 			
			?>
		</div>
		<div class="col-md-4">
			<a href="#"><img src="images/bpmspace_icon-LDMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Lightweight Document Management System" width="304"
				height="236"></a>
		</div>
		<div class="col-md-2" style="margin-top: 30px">

			<?php
			$content = "";
			$content = @file_get_contents("LDMS/.git/config");
			if (!empty($content) && strpos($content,"https://github.com/BPMspaceUG/LDMS.git")){
					echo "<i class=\"fa fa-check fa-2x  text-success\" aria-hidden=\"true\"></i>";
			} else {
				echo "<i class=\"fa fa-ban fa-2x text-danger\"></i>";
			} 			
			?>
		</div>
		
		
		<div class="col-md-4">
			<a href="ASMS/"><img src="images/bpmspace_icon-ASMS-left-200px-text.png"
				class="img-responsive img-thumbnail center-block"
				alt="Advanced Slider Management System" width="304"
				height="236"></a>
		</div>
		<div class="col-md-2" style="margin-top: 30px">

			<?php
			$content = "";
			$content = @file_get_contents("ASMS/.git/config");
			if (!empty($content) && strpos($content,"https://github.com/BPMspaceUG/ASMS.git")){
					echo "<i class=\"fa fa-check fa-2x  text-success\" aria-hidden=\"true\"></i>";
			} else {
				echo "<i class=\"fa fa-ban fa-2x text-danger\"></i>";
			} 			
			?>
		</div>
	</div>
</div>
</br></br>

<?php
include_once '_footer.inc.php';
?>

