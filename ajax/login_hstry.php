<?php
if(isset($_GET['id']) && $_GET['id']!=""){
	echo "";
	include('../config/config.php');
	include("../core/class/db_query.php");
	include("../core/class/db_helper_admin.php");
	include("../core/function/common.php");
	$db_helper_obj=new db_helper();
	// getting related serv station name
	$result=$db_helper_obj->user_login_history($_GET['id']);
?>
<table class="table table-hover text-center dataTable"  cellspacing="0" role="grid" aria-describedby="dataTable_info">
<thead>
<tr role="row">
	<th>S.No</th>
	<th>Login Time</th>
	<th>Logout Time</th>
</tr>
</thead>
<tbody>
<?php if(count($result) > 0){ $cnt=0; foreach($result as $va=>$row){ $cnt++; ?>
	<tr>
	  <td><?php echo $cnt; ?></td>
	 
	  <td><?php echo get_date_format($row["login_datetime"]); ?></td>
	  <td><?php if(isset($row["logout_datetime"])) echo get_date_format($row["logout_datetime"]); ?></td>
	  
	</tr>
<?php } }else{ ?>
<tr>
<td colspan="3"><div align="center">No Renewal History</div></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
