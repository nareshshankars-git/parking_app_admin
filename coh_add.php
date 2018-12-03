<?php
$page_name="Cash on Hand Add";
function main() {
	$error="";
	//$user_id="";
	$amount="";
	$action="Submit";
	global $db_helper_obj;
	if(isset($_POST["Submit"]) || isset($_POST["Update"])){ 
		$status=0;
		extract($_POST);
		include("core/class/validation_class.php");
	// setting rule for validation
	/*'user_id'=>array('type'=>'number','required'=>true,'trim'=>true,'error-msg'=>"Please choose the staff"),*/
	$rules_array = array(
		'amount'=>array('type'=>'number','required'=>true,'trim'=>true,'error-msg'=>"Please enter the Valid Amount")
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
			$ins_coh=array();
			//$ins_coh["user_id"]=$user_id;
			$ins_coh["amount"]=$amount;
			$db_helper_obj->update_coh($ins_coh,$_GET["id"]);
			$db_helper_obj->update_trans($ins_coh,$_GET["id"],3);
			set_success_msg('Cash on Hand Updated Successfully');
			header("location: cash_on_hand.php");
			exit();
		}else{
			$ins_coh=array();
			//$ins_coh["user_id"]=$user_id;
			$ins_coh["amount"]=$amount;
			$ins_coh["created_datetime"]=date("Y-m-d H:i:s",time());
			$ins_coh["created_by"]=$_SESSION["user_id"];
			$ins_trans=array();
			$ins_trans['amount']=$amount;
			$ins_trans['trans_from']=3;
			$ins_trans['trans_type']=1;
			$ins_trans['trans_for_id']=$db_helper_obj->add_coh($ins_coh);
			$ins_trans["created_datetime"]=date("Y-m-d H:i:s",time());
			$ins_trans["created_by"]=$_SESSION["user_id"];
			$db_helper_obj->add_trans($ins_trans);
			set_success_msg('Cash Recieved Added Successfully');
			header("location: cash_on_hand.php");
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
			if($data=$db_helper_obj->get_coh_by_id($_GET["id"])){
				extract($data);
				$action="Update";
			}else
				$error="Invalid Data";
		}
	}
	
$staffs=$db_helper_obj->get_staffs();
	?>
<div class="container-fluid">

	<div class="card mb-3">
        <div class="card-header">Cash Recieved Add Form</div>  
	<div class="card-body">
		<?php if($error){ 
		 ?>
			<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-ban"></i> <?php echo $error; ?></h4>
			</div>
		<?php } ?>
		<form role="form" name="contact"  method="post" >
		<?php /*
			<div class="row">
				<div class="col-md-12">		
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="staff_id">Staff <span>*</span></label>
						<select id="staff_id" name="user_id" class="form-control" >
						<option value="" disabled selected>Select</option>
						<?php foreach($staffs as $va=>$key){?>
						
						<option <?php getselected($key["id"],$user_id);?> value="<?php echo $key["id"]; ?>"><?php echo $key["user_name"]." (".$key["name"].")"; ?></option>
						<?php }?>
						</select>
					</div>
				</div>
			</div>
		*/ ?>	
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="amount_id">Amount<span>*</span></label>
						<input type="text" value="<?php echo $amount;?>"  id="amount_id" name="amount"  class="form-control" />
					</div>
				</div>
			</div>
			

			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6 col-sm-6" >
						
						<button type="submit" name="<?php echo $action; ?>"  class="btn-primary btn"><?php echo $action; ?></button>
						<a class="btn-danger btn" href="cash_on_hand.php" />Cancel</a>
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