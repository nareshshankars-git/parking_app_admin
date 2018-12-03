<?php
if(isset($_GET['id']) && $_GET['id']!=""){
	include('../config/config.php');
	include("../core/class/db_query.php");
	include("../core/class/db_helper_admin.php");
	include("../core/function/common.php");
	$db_helper_obj=new db_helper();
	$result=$db_helper_obj->get_cust_veh($_GET['id']);
?>
<table class="table table-hover text-center dataTable"  cellspacing="0" role="grid" aria-describedby="dataTable_info">
<thead>
<tr role="row">
	<th>S.No</th>
	<th>Veh No</th>
	<th>Model</th>
	<th>Slot Name</th>
</tr>
</thead>
<tbody>
<?php if(count($result) > 0){ $cnt=0; foreach($result as $va=>$row){ $cnt++; ?>
	<tr>
	  <td><?php echo $cnt; ?></td>
	  <td><?php echo get_veh_no($row); ?></td>
	  <td><?php echo $row["model"]; ?></td>
	  <td><?php echo $row["slot_name"]; ?></td>
	  
	</tr>
<?php } }else{ ?>
<tr>
<td colspan="3"><div align="center">No Record Found</div></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
