<?php
$page_name="Staffs";
include("core/pagination/pagination.php");
function main() {
	global $db_helper_obj;
	// Pagination 
	$item_per_page=5;
	$page_number=get_page_no();
	
	$sort_by=get_sort_by();
	if($sort_by=="")
		$sort_by=" order by created_datetime desc"; // default sort by
	$where="1";
	$where_arr=array();
	$page_url_form="";

	$user_list=$db_helper_obj->users_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($user_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$user_list=$db_helper_obj->users_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	//$users_list=$db_helper_obj->get_users();
	?>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-address-book"></i>&nbsp;Staffs List</div>
        <div class="card-body">
          <div class="table-responsive">
            <div id="dataTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
			<div class="row">
				<div class="col-sm-12">
				<?php echo get_success_alert();?>
					<a href="users_add.php"><button class="btn btn-success mb-3 pull-right" type="button">Add Staff</button></a>
					<table class="table table-hover text-center dataTable" id="dataTable" cellspacing="0" role="grid" aria-describedby="dataTable_info">
					<thead>
					<tr role="row">
						<th>S.No</th>
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Name</a></th>
						<th class="<?php echo get_sort_class("user_name");?>"><a href="<?php echo get_sort_url("user_name"); ?> ">User Name</a></th>
					  
					  <th >Password</th>
					  <th >Status</th>
					  <th >Action</th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($user_list) > 0){ $cnt=0; foreach($user_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $cnt; ?></td>
						  <td><?php echo $row["name"]; ?></td>
						  <td><?php echo $row["user_name"]; ?></td>
						  <td><?php echo $row["password"]; ?></td>
						  <td><?php if($row["status"]==1) echo '<span class="badge badge-success">Active</span>'; else echo '<span class="badge badge-danger">In Active</span>'; ?></td>
						  <td>
						 
						  	<a href="users_add.php?id=<?php echo $row["id"]; ?>" title="Make it as Inactive">   <i class="fa fa-pencil"></i></a>
							&nbsp;&nbsp;&nbsp;
							<button onclick="call_ajax_url('login_hstry','login_content','id=<?php echo $row['id']; ?>')" type="button" class="btn btn-primary  btn-xs mb-3" data-toggle="modal" data-target="#login_list_modal">Login History</button>
						 </td>
						</tr>
					<?php } }else{ ?>
                  <tr>
                    <td colspan="3"><div align="center">No Staffs Found</div></td>
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
	
	<div class="modal fade" id="login_list_modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Login History</h5>
					<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				</div>
				<div class="modal-body" id="login_content">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>



</div>
    <!-- /.container-fluid-->
<?php }
include 'template-admin.php';
?>