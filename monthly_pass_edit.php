<?php
$page_name="Monthly Pass Edit";
function main() {
	$error="";
	$month="";
	$slot_id="";
	$status="";
	$action="Submit";
	$arr_mnth=array(1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
	global $db_helper_obj;
	if(isset($_POST["Submit"]) || isset($_POST["Update"])){ 
		$status=0;
		extract($_POST);
		if(isset($_POST["Update"]))
			$action="Update";
		include("core/class/validation_class.php");
	// setting rule for validation
	$rules_array = array(
		'month'=>array('type'=>'number','required'=>true,'min'=>1 , 'max'=>24,'trim'=>true,'error-msg'=>"Please choose the valid Month"),
		'slot_id'=>array('type'=>'number','required'=>true,'trim'=>true,'error-msg'=>"Please choose the slot"),
		'status'=>array('type'=>'number','required'=>true,'trim'=>true,'error-msg'=>"Please choose the status"),
		
	);

	$val = new validation;
    $val->addSource($_POST);
	$val->addRules($rules_array);
	$val->run();
	$validation_error=array();
	$validation_error=$val->errors;
	//print_r($validation_error);
	if((count($validation_error)==0 )){ // checking the validation errors
		if(isset($_POST["Update"]) && isset($_GET["id"])){
			$upd_veh=array();
			$upd_veh["month"]=$month;
			$upd_veh["slot_id"]=$slot_id;
			$upd_veh["status"]=$status;
			$db_helper_obj->update_mntly_pass($upd_veh,$_GET["id"]);
			set_success_msg('Monthly Pass Updated Successfully');
			header("location: monthly_pass.php");
			exit();
		}
	}else
		$error=$val->getErrorMsg();
	}
	else if(isset($_GET["id"])){ 
		include("core/class/validation_class.php");
		// setting rule for validation
		$rules_array = array(
			'id'=>array('type'=>'number','required'=>true,'trim'=>true,'error-msg'=>"Invalid Id")
		);
		$val = new validation;
		$val->addSource($_GET);
		$val->addRules($rules_array);
		$val->run();
		$validation_error=array();
		$validation_error=$val->errors;
		if((count($validation_error)==0 )){
			if($data=$db_helper_obj->get_mnt_pass_by_id($_GET["id"])){
				extract($data);
				
				$action="Update";
			}else
				$error="Invalid Data";
		
		}
	}
	$slot_data=$db_helper_obj->get_mont_slots();

	?>
	
<div class="container-fluid">

	<div class="card mb-3">
        <div class="card-header">Monthly Pass Edit Form</div>  
	<div class="card-body">
		<?php if($error){ 
		 ?>
			<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-ban"></i> <?php echo $error; ?></h4>
			</div>
		<?php } ?>
		<form role="form" name="contact"  method="post" >
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="slot_type_id">Slot Type<span>*</span></label>
						<select name="slot_id" class="form-control">
						<option value="" disabled selected>Select</option>
						<?php foreach($slot_data as $va=>$key){?>
						
						<option <?php getselected($key["id"],$slot_id);?> value="<?php echo $key["id"]; ?>"><?php echo $key["name"]; ?></option>
						<?php }?>
						</select>
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="month_id">Month<span>*</span></label>
						<select name="month" id="month_id" class="form-control">
						<option value="" disabled selected>Select</option>
						<?php foreach($arr_mnth as $va=>$key){?>
						<option <?php getselected($va,$month);?> value="<?php echo $va; ?>"><?php echo $key; ?></option>
						<?php }?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="status_id">Status<span>*</span></label>
						<select name="status" id="status_id" class="form-control">
						<option value="" disabled selected>Select</option>
						<option <?php getselected(0,$status);?> value="0">Inactive</option>
						<option <?php getselected(1,$status);?> value="1">Active</option>
						<option <?php getselected(2,$status);?> value="2">Cancelled</option>
						</select>
					</div>
				</div>
			</div>
			
			
			
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6 col-sm-6" >
						
						<button type="submit" name="<?php echo $action; ?>"  class="btn-primary btn"><?php echo $action; ?></button>
						<a class="btn-danger btn" href="monthly_pass.php" />Cancel</a>
					</div>
				</div>
			</div>
		</form>
	</div>
	</div>
    

</div>
    <!-- /.container-fluid-->
<?php }
include 'template-admin.php';
?>