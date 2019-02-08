<?php
if(isset($_GET['id']) && $_GET['id']!=""){
	include('../config/config.php');
	include("../core/class/db_query.php");
	include("../core/class/db_helper_admin.php");
	$db_helper_obj=new db_helper();
	$result=$db_helper_obj->get_audit_detail($_GET['id']);
?>
<table class="table table-hover text-center dataTable"  cellspacing="0" role="grid" aria-describedby="dataTable_info">
<thead>
<tr role="row">
	<th>S.No</th>
	<th>Amount Modified</th>
	<th>Notes</th>
	
</tr>
</thead>
<tbody>
<?php if(count($result) > 0){ $cnt=0; foreach($result as $va=>$row){ $cnt++; ?>
	<tr>
	  <td><?php echo $cnt; ?></td>
	  <td>(<strike><?php echo $row["org_amount"]; ?></strike>)&nbsp;<?php echo $row["amount"]; ?></td>
	  <td><?php echo $row["notes"]; ?></td>
	  
	</tr>
<?php } }else{ ?>
<tr>
<td colspan="3"><div align="center">No Record Found</div></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
