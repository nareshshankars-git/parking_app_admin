<?php
$page_name="Vehicle Edit";
function main() {
	$error="";
	$make_model_id="";
	$state_id="";
	$city="";
	$alpha="";
	$reg_no="";
	$slot_id="";
	$action="Update";
	global $db_helper_obj;
	
	if(isset($_POST["Update"])){ 
		$status=0;
		extract($_POST);
		include("core/class/validation_class.php");
	// setting rule for validation
	$rules_array = array(
		'city'=>array('type'=>'number','required'=>true,'min'=>1 , 'max'=>99,'trim'=>true,'error-msg'=>"Please enter the valid City"),
		'reg_no'=>array('type'=>'string','required'=>true,'min'=>1 , 'max'=>9999,'trim'=>true,'error-msg'=>"Please enter the reg no"),
		
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
			$upd_veh["make_model_id"]=$make_model_id;
			$upd_veh["state_id"]=$state_id;
			$upd_veh["city"]=$city;
			$upd_veh["alpha"]=$alpha;
			$upd_veh["reg_no"]=$reg_no;
			$upd_veh["slot_id"]=$slot_id;
			$db_helper_obj->update_vehicle($upd_veh,$_GET["id"]);
			set_success_msg('Vehicle Updated Successfully');
			header("location: vehicle.php");
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
			if($data=$db_helper_obj->get_vehicle_by_id($_GET["id"])){
				extract($data);
				$action="Update";
			}else
				$error="Invalid Data";
		
		}
	}
	$model_data=$db_helper_obj->get_model();
	$state_data=$db_helper_obj->get_state();
	$slot_data=$db_helper_obj->get_slots();

	?>
<div class="container-fluid">

	<div class="card mb-3">
        <div class="card-header">Vehicle Edit Form</div>  
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
				
				<div class="col-md-2">
					<div class="form-group">
						<label for="slot_type_id">Vehicle Number<span>*</span></label>
						<select name="state_id" class="form-control">
						<option value="" disabled selected="selected">Select</option>
						<?php foreach($state_data as $va=>$key){?>
						
						<option <?php getselected($key["id"],$state_id);?> value="<?php echo $key["id"]; ?>"><?php echo $key["name"]; ?></option>
						<?php }?>
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group" >
						<input type="tel" placeholder="99"  maxlength="2" style="margin-top: 25px;" value="<?php echo $city;?>" name="city" class="form-control" >
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group" >
						<input type="text" placeholder="xx"  maxlength="3" style="margin-top: 25px;" value="<?php echo $alpha;?>" name="alpha" class="form-control" >
					</div>
					
				</div>
				<div class="col-md-2">
					<div class="form-group" >
						<input type="tel" placeholder="9999" maxlength="4"  style="margin-top: 25px;" value="<?php echo $reg_no;?>" name="reg_no" class="form-control" >
					</div>
					
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="slot_type_id">Model<span>*</span></label>
						<select name="make_model_id" class="form-control">
						<option value="" disabled selected>Select</option>
						<?php foreach($model_data as $va=>$key){?>
						
						<option <?php getselected($key["id"],$make_model_id);?> value="<?php echo $key["id"]; ?>"><?php echo $key["name"]; ?></option>
						<?php }?>
						</select>
					</div>
				</div>
			</div>
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
					<div class="col-md-6 col-sm-6" >
						
						<button type="submit" name="<?php echo $action; ?>"  class="btn-primary btn"><?php echo $action; ?></button>
						<a class="btn-danger btn" href="vehicle.php" />Cancel</a>
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