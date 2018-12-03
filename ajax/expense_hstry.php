<?php
if(isset($_GET['id']) && $_GET['id']!=""){
	echo "";
	include('../config/config.php');
	include("../core/class/db_query.php");
	include("../core/class/db_helper_admin.php");
	include("../core/function/common.php");
	$db_helper_obj=new db_helper();
	$result=$db_helper_obj->expense_history($_GET['id']);
?>
<table class="table table-hover text-center dataTable"  cellspacing="0" role="grid" aria-describedby="dataTable_info">
<thead>
<tr role="row">
	<th>S.No</th>
	<th>Amount</th>
	<th>notes</th>
	<th>Date Time</th>
</tr>
</thead>
<tbody>
<?php if(count($result) > 0){ $cnt=0; foreach($result as $va=>$row){ $cnt++; ?>
	<tr>
	  <td><?php echo $cnt; ?></td>
	  <td><?php echo $row["amount"]; ?></td>
	  <td><?php echo $row["notes"]; ?></td>
	 
	  <td><?php echo get_date_format($row["updated_datetime"]); ?></td>
	  
	</tr>
<?php } }else{ ?>
<tr>
<td colspan="3"><div align="center">No Record Found</div></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
