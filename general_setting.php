<?php
$page_name="General Setting";
function main() {
	$error="";
	$name="";
	$user_name="";
	$password="";
	$status=1;
	$action="Submit";
	global $db_helper_obj;
	if(isset($_POST["Submit"]) || isset($_POST["Update"])){ 
		$ins_setting=array();
		extract($_POST);
		$ins_setting["value"]=$lock_screen;
		$db_helper_obj->update_setting($ins_setting,1);
		set_success_msg('Setting Updated Successfully');
		header("location:general_setting.php");
		exit();
	}
	if(isset($_POST["reset_pswrd"]) && isset($_POST["pswrd"])){ 
		$ins=array();
		extract($_POST);
		$ins["password"]=$_POST["pswrd"];
		$db_helper_obj->update_password($ins);
		header("location:logout.php");
		exit();
	}
	$data=$db_helper_obj->get_general_setting();
	
	?>
<div class="container-fluid">

	<div class="card mb-3">
        <div class="card-header">General Setting Form</div>  
	<div class="card-body">
		
		<div class="row">
		<div class="col-12 mt-5">
			<form role="form" name="setting"  method="post" >
			<div class="row">
				<div class="col-md-12">	
<?php echo get_success_alert();?>				
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="lock_screen_id">Lock Screen Timing <span>*</span></label>
						<input type="tel" id="lock_screen_id" name="lock_screen" value="<?php echo $data["lock_screen"] ?>" class="form-control" />
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6 col-sm-6" >
						
						<button type="submit" name="<?php echo $action; ?>"  class="btn-primary btn"><?php echo $action; ?></button>
					
					</div>
				</div>
			</div>
			</form>
			</div>
		</div>
		<div class="row">
		<div class="col-12 mt-5">
		<form role="form" name="setting"  method="post" >
			<div class="row">
				<div class="col-md-12">	
					<div class="form-group col-xs-8 col-sm-8" >
						<label for="reset_pswrd_id">Reset Password <span>*</span></label>
						<input type="password" id="reset_pswrd_id" name="pswrd" value="" class="form-control" />
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6 col-sm-6" >
						
						<button type="submit" name="reset_pswrd"  class="btn-primary btn">Reset Password</button>
						
					</div>
				</div>
			</div>
		</form>
					</div>
		</div>

	</div>
	</div>
    

</div>
    <!-- /.container-fluid-->
<?php }
include 'template-admin.php';
?>