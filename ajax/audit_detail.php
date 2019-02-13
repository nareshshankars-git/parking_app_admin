<?php
if(isset($_GET['id']) && $_GET['id']!=""){
	include('../config/config.php');
	include("../core/class/db_query.php");
	include("../core/class/db_helper_admin.php");
	include("../core/function/common.php");
	$db_helper_obj=new db_helper();
	$result=$db_helper_obj->get_audit_detail($_GET['id']);
?>
<table class="table table-hover text-center dataTable"  cellspacing="0" role="grid" aria-describedby="dataTable_info">
<thead>
<tr role="row">
	<th>S.No</th>
	<th>Amount Modified</th>
	<th>Notes</th>
	<th>Token No</th>
	<th>Vehicle No</th>
	<th>Make Model</th>
	<th>Slot Name</th>
	<th>Customer </th>
</tr>
</thead>
<tbody>
<?php if(count($result) > 0){ $cnt=0; foreach($result as $va=>$row){ $cnt++; ?>
	<tr>
	  <td><?php echo $cnt; ?></td>
	  <td>(<strike><?php echo $row["org_amount"]; ?></strike>)&nbsp;<?php echo $row["amount"]; ?></td>
	  <td><?php echo $row["notes"]; ?></td>
	  <td><span class="badge badge-<?php echo $row["color"]; ?>"><?php echo $row["token_no"]; ?></span></td>
						  <td><?php echo get_veh_no($row); ?></td>
						  <td><span class="badge badge-<?php if(isset($row["check_out"])) echo 'success'; else echo 'danger'; ?>"><?php echo $row["model"]; ?></span></td>
						  <td><?php echo $row["slot_name"]; ?></td>
						  <td><?php echo $row["mobile_number"];if($row["name"]) echo "(".$row["name"].")"; ?></td>
	</tr>
<?php } }else{ ?>
<tr>
<td colspan="3"><div align="center">No Record Found</div></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
