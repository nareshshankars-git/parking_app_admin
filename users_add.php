<?php
$page_name="Staff Add";
function main() {
	$error="";
	$name="";
	$user_name="";
	$password="";
	$status=1;
	$action="Submit";
	global $db_helper_obj;
	if(isset($_POST["Submit"]) || isset($_POST["Update"])){ 
		$status=0;
		extract($_POST);
		include("core/class/validation_class.php");
	// setting rule for validation
	$rules_array = array(
		'name'=>array('type'=>'string','required'=>true,'min'=>1 , 'max'=>50,'trim'=>true,'error-msg'=>"Please enter the valid name"),
		'password'=>array('type'=>'string','required'=>true,'trim'=>true,'min'=>3 , 'max'=>10,'error-msg'=>"Please enter the Valid Password")
	);
	if(isset($_POST["Submit"])){
		$rules_array["user_name"]=array('type'=>'string','required'=>true,'min'=>1 , 'max'=>50,'trim'=>true,'error-msg'=>"Please enter the valid Username");
	}
	$val = new validation;
    $val->addSource($_POST);
	$val->addRules($rules_array);
	$val->run();
	$validation_error=array();
	$validation_error=$val->errors;
	if(isset($_POST["Update"]))
		$action="Update";
	if((count($validation_error)==0 )){ // checking the validation errors
		if(isset($_POST["Update"]) && isset($_GET["id"])){
			$ins_user=array();
			$ins_user["name"]=$name;
			$ins_user["password"]=$password;
			$ins_user["status"]=$status;
			$db_helper_obj->update_user($ins_user,$_GET["id"]);
			set_success_msg('Staff Updated Successfully');
			header("location: users.php");
			exit();
		}else{
			if($db_helper_obj->validate_username($user_name)){
				$ins_user=array();
				$ins_user["name"]=$name;
				$ins_user["user_name"]=$user_name;
				$ins_user["password"]=$password;
				$ins_user["status"]=$status;
				$ins_user["created_datetime"]=date("Y-m-d H:i:s",time());
				$ins_user["created_by"]=$_SESSION["user_id"];
				$db_helper_obj->add_user($ins_user);
				set_success_msg('Staff Added Successfully');
				header("location: users.php");
				exit();
			}else
			$error="User Name Already Exist";
		}
	}else{
		if(isset($_GET["id"])){ 
			if($data=$db_helper_obj->get_user($_GET["id"])){
				$user_name=$data["user_name"];
				$action="Update";
			}else
				$error="Invalid Data";
		}
		$error=$val->getErrorMsg();
	}}else if(isset($_GET["id"])){ 
		include("core/class/validation_class.php");
		// setting rule for validation
		$rules_array = array(
			'id'=>array('type'=>'number','required'=>true,'trim'=>true,'error-msg'=>"Invalid Id")
		);
		$val1 = new validation;
		$val1->addSource($_GET);
		$val1->addRules($rules_array);
		$val1->run();
		$validation_error=array();
		$validation_error=$val1->errors;
		if((count($validation_error)==0 )){
			if($data=$db_helper_obj->get_user($_GET["id"])){
				extract($data);
				$action="Update";
			}else
				$error="Invalid Data";
		
		}
	}
	

	?>
<div class="container-fluid">

	<div class="card mb-3">
        <div class="card-header">Staff Add Form</div>  
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
						<label for="nameId">Name <span>*</span></label>
						<input type="text" id="nameId" name="name" value="<?php echo $name ?>" class="form-control" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="user_name_id">User Name<span>*</span></label>
						<input <?php if($action=="Update") echo 'disabled=disabled'; ?> type="text" value="<?php echo $user_name ?>"  id="user_name_id" name="user_name"  class="form-control" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="password_id">Password<span>*</span></label>
						<input type="text" value="<?php echo $password;?>"  id="password_id" name="password"  class="form-control" />
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
						<a class="btn-danger btn" href="users.php" />Cancel</a>
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