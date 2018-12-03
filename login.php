<?php
function main() {
	$user_name="";
	$error="";
	global $db_helper_obj;
	if(isset($_POST["submit"])){ 
		extract($_POST);
		include("core/class/validation_class.php");
	// setting rule for validation
	$rules_array = array(
		'user_name'=>array('type'=>'string','required'=>true,'trim'=>true,'error-msg'=>"Please enter valid Username",'min'=>1 , 'max'=>150),
		'password'=>array('type'=>'string','required'=>true,'trim'=>true,'min'=>1 , 'max'=>150,'error-msg'=>"Please enter valid Password"),
	);
	$val = new validation;
    $val->addSource($_POST);
	$val->addRules($rules_array);
	$val->run();
	$validation_error=array();
	$validation_error=$val->errors;
	//print_r($validation_error);
	if((count($validation_error)==0 )){ // checking the validation errors
		$result_user=$db_helper_obj->get_loggedIn_user($user_name,$password);
		if($result_user){ // checking the user is exist or not
			if($result_user["status"]==1){
				$_SESSION["user_id"]=$result_user["id"];
				$_SESSION["name"]=$result_user["name"];
				$_SESSION["user_name"]=$result_user["user_name"];
				header("location: dashboard.php");
				exit();
			}else{
				$error="Not Yet Activated";
			}
	
		}else{
			$error="Invalid Username or Password";
		}
	}else
		$error=$val->getErrorMsg();
	}
?>
 <!-- login area start -->
    <div class="login-area">
        <div class="container">
            <div class="login-box ptb--100">
			
			  
                <form name="login_form" method="post">
				
                    <div class="login-form-head">
                        <h4>Sign In</h4>
                        <p>Hello there, Sign in and start managing your Admin Panel</p>
                    </div>
                    <div class="login-form-body">
					<?php if($error!=''){?>
					<div class="alert alert-danger alert-dismissible">
					<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
					<?php echo $error; ?>
					</div>
				  <?php } ?>
                        <div class="form-gp">
                            <label for="exampleInputEmail1">User Name</label>
                            <input name="user_name" type="text" value="<?php echo $user_name; ?>" id="exampleInputEmail1">
                            <i class="ti-user"></i>
                        </div>
                        <div class="form-gp">
                            <label for="exampleInputPassword1">Password</label>
                            <input name="password" type="password" id="exampleInputPassword1">
                            <i class="ti-lock"></i>
                        </div>
						<div class="submit-btn-area">
							<button class="" id="form_submit" name="submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
						</div>
                    </div>
					
                        

                </form>
            </div>
        </div>
    </div>
    <!-- login area end -->

<?php }
include 'template-login.php';
?>