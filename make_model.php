<?php
$page_name="State";
include("core/pagination/pagination.php");
function main() {
	global $db_helper_obj;
	$name="";
	$error="";
	$action="Submit";
	$del_status="0";
	if(isset($_POST["Submit"]) || isset($_POST["Update"])){ 
		$del_status=1;
		extract($_POST);
		include("core/class/validation_class.php");
		// setting rule for validation
		$rules_array = array(
			'name'=>array('type'=>'string','required'=>true,'min'=>1 , 'max'=>50,'trim'=>true,'error-msg'=>"Please enter the valid code")
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
			$ins_state=array();
			$ins_state["name"]=$name;
			$ins_state["del_status"]=$del_status;
			$db_helper_obj->update_model($ins_state,$_GET["id"]);
			set_success_msg('Make Model Updated Successfully');
			header("location: make_model.php");
			exit();
		}else{
			$ins_state=array();
			$ins_state["name"]=$name;
			$ins_state["del_status"]=$del_status;
			$ins_state["created_datetime"]=date("Y-m-d H:i:s",time());
			$ins_state["created_by"]=$_SESSION["user_id"];
			$db_helper_obj->add_model($ins_state);
			set_success_msg('Make Model Added Successfully');
			header("location: make_model.php");
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
			if($data=$db_helper_obj->get_model_by_id($_GET["id"])){
				extract($data);
				$action="Update";
			}else
				$error="Invalid Data";
		
		}
	}
	
	// Pagination 
	$item_per_page=5;
	$page_number=get_page_no();
	
	$sort_by=get_sort_by();
	if($sort_by=="")
		$sort_by=" order by created_datetime desc"; // default sort by
	$where="1";
	$where_arr=array();
	$page_url_form="";

	$model_list=$db_helper_obj->get_model_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($model_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$model_list=$db_helper_obj->get_model_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	?>

<div class="container-fluid">
    <div class="card mb-3">
		<div class="card-header">Add Make Model</div>
        <div class="card-body">
		<?php if($error){ 
		 ?>
			<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-ban"></i> <?php echo $error; ?></h4>
			</div>
		<?php } ?>
			<form name="state_form" method="post">
				<div class="form-row">
                    <div class="col-md-4 mb-3">
                    <label for="name_id">Make Model Name</label>
					<input name="name" type="text" class="form-control" id="name_id" placeholder="name" value="<?php echo $name; ?>">
                    </div>
                </div>
				<div class="form-row">
				<div class="col-md-12">
					<div class="form-group col-xs-8 col-sm-8" >
						<div class="custom-control custom-checkbox">
						<input value="0" type="checkbox" <?php if($del_status==0) echo 'checked="checked"'; ?> name="del_status" class="custom-control-input" id="del_status_id">
						<label class="custom-control-label" for="del_status_id">Status</label>
                        </div>
					</div>
				</div>
			</div>
				<div class="form-row">
				<div class="col-md-12">
					<div class="col-md-4 col-sm-6" >
						
						<button type="submit" name="<?php echo $action; ?>"  class="btn-primary btn"><?php echo $action; ?></button>
						<button type="reset" class="btn-danger btn" />Cancel</button>
					</div>
				</div>
			</div>
			</form>
         </div>
         
	</div>		
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-address-book"></i>&nbsp;Make Model List</div>
        <div class="card-body">
		  <div class="table-responsive">
            <div id="dataTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
			<div class="row">
				<div class="col-sm-12">
				<?php echo get_success_alert();?>
					<table class="table table-hover text-center dataTable" id="dataTable" cellspacing="0" role="grid" aria-describedby="dataTable_info">
					<thead>
					<tr role="row">
						<th>S.No</th>
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> "> Name</a></th>
						<th class="<?php echo get_sort_class("del_status");?>"><a href="<?php echo get_sort_url("del_status"); ?> "> Status</a></th>
						
					  <th >Action</th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($model_list) > 0){ $cnt=0; foreach($model_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $cnt; ?></td>
						  <td><?php echo $row["name"]; ?></td>
						  <td><?php if($row["del_status"]==0) echo '<span class="badge badge-success">Active</span>'; else echo '<span class="badge badge-danger">In Active</span>'; ?></td>
						  <td>
						 <td>
						  	<a href="make_model.php?id=<?php echo $row["id"]; ?>"><i class="fa fa-pencil"></i></a>
						 </td>
						</tr>
					<?php } }else{ ?>
                  <tr>
                    <td colspan="6"><div align="center">No Data Found</div></td>
                  </tr>
                  <?php } ?>
			  </tbody>
					</table>
				</div>
			</div>
			<?php if($get_total_rows > $item_per_page){ ?>
			<div class="row">
				<div class="col-sm-12">
				<?php  echo paginate($item_per_page, $get_total_rows,$page_url); // ?>
				</div>
			</div>
			<?php }  ?>
		  </div>
        </div>
		</div>
	</div>
</div>
    <!-- /.container-fluid-->
<?php }
include 'template-admin.php';
?>