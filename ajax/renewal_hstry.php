<?php
if(isset($_GET['id']) && $_GET['id']!=""){
	echo "";
	include('../config/config.php');
	include("../core/class/db_query.php");
	include("../core/class/db_helper_admin.php");
	include("../core/function/common.php");
	$db_helper_obj=new db_helper();
	// getting related serv station name
	$result=$db_helper_obj->get_renewal_hstry($_GET['id']);
?>
<table class="table table-hover text-center dataTable"  cellspacing="0" role="grid" aria-describedby="dataTable_info">
<thead>
<tr role="row">
	<th>S.No</th>
	<th>Slot Name</th>
	<th>Amount</th>
	<th>Month</th>
	<th>Date Time</th>
	<th>Renewaled By</th>
</tr>
</thead>
<tbody>
<?php if(count($result) > 0){ $cnt=0; foreach($result as $va=>$row){ $cnt++; ?>
	<tr>
	  <td><?php echo $cnt; ?></td>
	 
	  <td><?php echo $row["slot_name"]; ?></td>
	  <td><?php echo $row["amount"]; ?></td>
	  <td><?php echo get_month($row["month"]); ?></td>
	  <td><?php echo get_date_format($row["created_datetime"]); ?></td>
	   <td><?php echo $row["name"]; echo "(".$row["user_name"].")"; ?></td>
	  
	</tr>
<?php } }else{ ?>
<tr>
<td colspan="3"><div align="center">No Renewal History</div></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
