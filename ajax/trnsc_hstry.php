<?php
if(isset($_GET['id']) && $_GET['id']!=""){
	echo "";
	include('../config/config.php');
	include("../core/class/db_query.php");
	include("../core/class/db_helper_admin.php");
	include("../core/function/common.php");
	$db_helper_obj=new db_helper();
	$result=$db_helper_obj->veh_trans_history($_GET['id']);
?>
<table class="table table-hover text-center dataTable"  cellspacing="0" role="grid" aria-describedby="dataTable_info">
<thead>
<tr role="row">
	<th>S.No</th>
	<th>Token No</th>
	<th>Slot Name</th>
	<th>Total Slot</th>
	<th>Amount</th>
	<th>CheckIn Time</th>
	<th>CheckOut Time</th>
	<th>Token No</th>
						<th>Vehicle No</th>
						<th class="<?php echo get_sort_class("model");?>"><a href="<?php echo get_sort_url("model"); ?> ">Make Model</a></th>
						<th class="<?php echo get_sort_class("slot_name");?>"><a href="<?php echo get_sort_url("slot_name"); ?> ">Slot Name</a></th>
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Customer</a></th>
						<th class="<?php echo get_sort_class("check_in");?>"><a href="<?php echo get_sort_url("check_in"); ?> ">Check In</a></th>
						<th class="<?php echo get_sort_class("check_out");?>"><a href="<?php echo get_sort_url("check_out"); ?> ">Check Out</a></th>
						<th >Amount (Slot)</th>
</tr>
</thead>
<tbody>
<?php if(count($result) > 0){ $cnt=0; foreach($result as $va=>$row){ $cnt++; ?>
	<tr>
	  <td><?php echo $cnt; ?></td>
	  <td><?php echo $row["token_no"]; ?></td>
	  <td><?php echo $row["slot_name"]; ?></td>
	  <td><?php echo $row["slot_count"]; ?></td>
	  <td><?php echo $row["amount"]; ?></td>
	 
	  <td><?php echo get_date_format($row["check_in"]); ?></td>
	  <td><?php if(isset($row["check_out"])) echo get_date_format($row["check_out"]); ?></td>
	  
	</tr>
<?php } }else{ ?>
<tr>
<td colspan="3"><div align="center">No  History</div></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
