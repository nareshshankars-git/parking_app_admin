<?php
if(isset($_GET['trans_from']) && $_GET['trans_from']!=""){
	if($_GET['trans_from']==5){
		$_GET['id']=$_GET['trans_for_id'];
		include('audit_detail.php');
		exit();	
	}
}
if(isset($_GET['trans_for_id']) && $_GET['trans_for_id']!=""){
	include('../config/config.php');
	include("../core/class/db_query.php");
	include("../core/class/db_helper_admin.php");
	include("../core/function/common.php");
	$db_helper_obj=new db_helper();
	$data=array();
	$trans_type=array(1=>'Print',2=>'SMS',3=>'WhatsApp',4=>'Audit');
	if($_GET['trans_from']==1){
		$result=$db_helper_obj->get_trans_details($_GET['trans_for_id']);
		if(isset($result[0])){
			$row=$result[0];
			$data["Token No"]='<span class="badge badge-'.$row["color"].'">'.$row["token_no"].'</span>';
			$data["Vehicle No"]=get_veh_no($row);
			$data["Make Model"]=$row["model"];
			$data["Slot Name"]=$row["slot_name"];
			$data["Customer"]=$row["mobile_number"];
			if($row["name"]) 
				$data["Customer"].="(".$row["name"].")";
			$data["Check In"]=get_date_format($row["check_in"]);
			$data["Check In Transaction"]=$trans_type[$row["check_in_transaction"]];
			$data["Check Out"]=get_date_format($row["check_out"]);
			$data["Check Out Transaction"]=$trans_type[$row["check_out_transaction"]];
			$data["Amount"]=$row["amount"];
			$data["Slot Count"]=$row["slot_count"];
		}
	}else if($_GET['trans_from']==2){
		$result=$db_helper_obj->get_expense_detail($_GET['trans_for_id']);
		if(isset($result[0])){
			$row=$result[0];
			$data["Staff"]=$row["name"]." (".$row["user_name"].")";
			$data["Notes"]=$row["notes"];
			$data["Amount"]=$row["amount"];
			$data["Created Date Time"]=get_date_format($row["created_datetime"]);
			
		}
	}else if($_GET['trans_from']==3){
		$row=$db_helper_obj->get_coh_by_id($_GET['trans_for_id']);
		if(isset($row)){
			$data["Amount"]=$row["amount"];
			$data["Created Date Time"]=get_date_format($row["created_datetime"]);
			
		}
	}else if($_GET['trans_from']==4){
		$result=$db_helper_obj->get_montly_pass_details($_GET['trans_for_id']);
		if(isset($result[0])){
			$row=$result[0];
			$data["Customer"]=$row["mobile_number"]."(".$row["name"].")";
			$data["Vehicle No"]=get_veh_no($row);
			$data["Make Model"]=$row["model"];
			$data["Month"]=get_month($row["month"]);
			$data["Amount"]=$row["amount"];
			$data["Slot Name"]=$row["slot_name"];
			$data["Created Date Time"]=get_date_format($row["created_datetime"]);
			
		}
	}
?>
<table class="table dataTable"  cellspacing="0" >

<tbody>
<?php foreach($data as $va=>$key){ ?>
	<tr>
	  <td style="text-align: right"><?php echo $va; ?></td>
	  <td>:</td>
	  <td><?php echo $key; ?></td>
	  
	</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
