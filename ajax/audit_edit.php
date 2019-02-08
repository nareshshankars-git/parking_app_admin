<?php
if(isset($_POST['trans_id']) && $_POST['trans_id']!=""){
	include('../config/config.php');
	$_SESSION['audit_detail'][$_POST['trans_id']]=$_POST;
	$_SESSION['audited_id'][$_POST['trans_id']]=$_POST['trans_id'];
}else if(isset($_GET['id']) && $_GET['id']!=""){
	include('../config/config.php');
	$_SESSION['audited_id'][$_GET['id']]=$_GET['id'];
}
exit();

?>
