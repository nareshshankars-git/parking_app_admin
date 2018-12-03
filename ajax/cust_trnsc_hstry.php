<?php
if(isset($_GET['id']) && $_GET['id']!=""){
	echo "";
	include('../config/config.php');
	include("../core/class/db_query.php");
	include("../core/class/db_helper_admin.php");
	include("../core/function/common.php");
	$db_helper_obj=new db_helper();
	$result=$db_helper_obj->cust_trans_history($_GET['id']);
?>
<table class="table table-hover text-center dataTable"  cellspacing="0" role="grid" aria-describedby="dataTable_info">
<thead>
<tr role="row">
	<th>S.No</th>
	<th>Make & Model</th>
	<th>Veh No</th>
	<th>Token No</th>
	<th>Slot Name</th>
	<th>Total Slot(Amount)</th>
	<th>CheckIn Time</th>
	<th>CheckOut Time</th>
</tr>
</thead>
<tbody>
<?php if(count($result) > 0){ $cnt=0; foreach($result as $va=>$row){ $cnt++; ?>
	<tr>
	  <td><?php echo $cnt; ?></td>
	  <td><?php echo get_veh_no($row); ?></td>
	  <td><?php echo $row["token_no"]; ?></td>
	  <td><?php echo $row["token_no"]; ?></td>
	  <td><?php echo $row["slot_name"]; ?></td>
	  <td><?php echo $row["slot_count"]; if(isset($row["amount"])) echo "(".$row["amount"].")"; ?></td> 
	  <td><?php echo get_date_format($row["check_in"]); ?></td>
	  <td><?php if(isset($row["check_out"])) echo get_date_format($row["check_out"]); ?></td>
	  
	</tr>
<?php } }else{ ?>
<tr>
<td colspan="3"><div align="center">No Record Found</div></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
