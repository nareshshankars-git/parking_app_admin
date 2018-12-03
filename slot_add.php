<?php
$page_name="Slot Add";
function main() {
	$color_arr=array("black","voilet","red","yellow","green","blue","grey","royal","lavender");
	$error="";
	$name="";
	$slot_type="";
	$amount="";
	$color="";
	$hours="";
	$status=1;
	$grace_period=1;
	$action="Submit";
	global $db_helper_obj;
	if(isset($_POST["Submit"]) || isset($_POST["Update"])){ 
		$status=0;
		extract($_POST);
		include("core/class/validation_class.php");
	// setting rule for validation
	$rules_array = array(
		'name'=>array('type'=>'string','required'=>true,'min'=>1 , 'max'=>50,'trim'=>true,'error-msg'=>"Please enter the valid name"),
		'slot_type'=>array('type'=>'number','required'=>true,'trim'=>true,'error-msg'=>"Please choose the slot"),
		'hours'=>array('type'=>'number','required'=>true,'trim'=>true,'error-msg'=>"Please choose the hour"),
		'amount'=>array('type'=>'number','required'=>true,'trim'=>true,'error-msg'=>"Please enter the amount"),
		'grace_period'=>array('type'=>'number','required'=>true,'trim'=>true,'error-msg'=>"Please choose the grace period"),
		'color'=>array('type'=>'string','required'=>true,'trim'=>true,'min'=>1 , 'max'=>50,'error-msg'=>"Please choose the color"),
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
			$ins_slot=array();
			$ins_slot["name"]=$name;
			$ins_slot["slot_type"]=$slot_type;
			$ins_slot["amount"]=$amount;
			$ins_slot["hours"]=$hours;
			$ins_slot["color"]=$color;
			$ins_slot["status"]=$status;
			$ins_slot["grace_period"]=$grace_period;
			$db_helper_obj->update_slot($ins_slot,$_GET["id"]);
			set_success_msg('Slot Updated Successfully');
			header("location: slot.php");
			exit();
		}else{
			$ins_slot=array();
			$ins_slot["name"]=$name;
			$ins_slot["slot_type"]=$slot_type;
			$ins_slot["amount"]=$amount;
			$ins_slot["hours"]=$hours;
			$ins_slot["color"]=$color;
			$ins_slot["status"]=$status;
			$ins_slot["grace_period"]=$grace_period;
			$ins_slot["created_datetime"]=date("Y-m-d H:i:s",time());
			$ins_slot["created_by"]=$_SESSION["user_id"];
			$db_helper_obj->add_slot($ins_slot);
			set_success_msg('Slot Added Successfully');
			header("location: slot.php");
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
			if($data=$db_helper_obj->get_slot_by_id($_GET["id"])){
				extract($data);
				$action="Update";
			}else
				$error="Invalid Data";
		
		}
	}
	
	$slot_data=$db_helper_obj->get_slot_type();

	?>
<div class="container-fluid">

	<div class="card mb-3">
        <div class="card-header">Slot Add Form</div>  
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
						<label for="nameId">Slot Name <span>*</span></label>
						<input type="text" id="nameId" name="name" value="<?php echo $name ?>" class="form-control" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="slot_type_id">Slot Type<span>*</span></label>
						<select name="slot_type" class="form-control">
						<option value="" disabled selected>Select</option>
						<?php foreach($slot_data as $va=>$key){?>
						
						<option <?php getselected($key["id"],$slot_type);?> value="<?php echo $key["id"]; ?>"><?php echo $key["name"]; ?></option>
						<?php }?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="color_id">Slot Color<span>*</span></label>
						<select id="color_id" name="color" class="form-control">
						<option value="" disabled selected>Select</option>
						<?php foreach($color_arr as $va=>$key){?>
						<option <?php getselected($key,$color);?>  value="<?php echo $key; ?>"><?php echo $key; ?></option>
						<?php }?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="grace_id">Grace Period<span>*</span></label>
						<select id="grace_id" name="grace_period" class="form-control">
						<option value="" disabled selected>Select</option>
					<?php for($i=1;$i<25;$i++){?>
						<option <?php getselected($i,$grace_period);?>  value="<?php echo $i; ?>"><?php echo $i; ?>
						</option>
						<?php }?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group col-xs-10 col-sm-10" >
						<label for="amount_id">Amount<span>*</span></label>
						<input type="text" value="<?php echo $amount;?>" id="amount_id" name="amount"  class="form-control" />
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="hour_id">Hour<span>*</span></label>
						<select id="hour_id" name="hours" class="form-control">
						<?php for($i=1;$i<25;$i++){?>
						<option <?php getselected($i,$hours);?>  value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php }?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<div class="custom-control custom-checkbox">
						<input value="1" type="checkbox" <?php if($status==1) echo 'checked="checked"'; ?> name="status" class="custom-control-input" id="status_id">
						<label class="custom-control-label" for="status_id">Status</label>
                        </div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6 col-sm-6" >
						
						<button type="submit" name="<?php echo $action; ?>"  class="btn-primary btn"><?php echo $action; ?></button>
						<a class="btn-danger btn" href="slot.php" />Cancel</a>
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