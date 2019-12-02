<?php
$page_name="Staffs Login History";
include("core/pagination/pagination.php");
function main() {
	global $db_helper_obj;
	// Pagination 
	$item_per_page=10;
	$page_number=get_page_no();
	
	$sort_by=get_sort_by();
	if($sort_by=="")
		$sort_by=" order by a.created_datetime desc"; // default sort by
	$where="1";
	$where_arr=array();
	$page_url_form="";
	if(isset($_GET['id']) && $_GET['id']!=""){
		$where="b.id=?";
		$where_arr=array($_GET['id']);
		$page_url_form="&id=".$_GET['id'];
	}
	
	$user_history_list=$db_helper_obj->login_history($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($user_history_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$user_history_list=$db_helper_obj->login_history($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	?>
	 

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-address-book"></i>&nbsp;Login History</div>
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
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Name</a></th>
						<th class="<?php echo get_sort_class("user_name");?>"><a href="<?php echo get_sort_url("user_name"); ?> ">User Name</a></th>
						<th class="<?php echo get_sort_class("login_datetime");?>"><a href="<?php echo get_sort_url("login_datetime"); ?> ">Log In</a></th>
						<th class="<?php echo get_sort_class("logout_datetime");?>"><a href="<?php echo get_sort_url("logout_datetime"); ?> ">Log Out</a></th>
						<th>Amount</th>
					  
					</tr>
					</thead>
					<tbody>
					<?php if(count($user_history_list) > 0){ $cnt=0; foreach($user_history_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $cnt; ?></td>
						  <td><?php echo $row["name"]; ?></td>
						  <td><?php echo $row["user_name"]; ?></td>
						  <td><?php echo get_date_format($row["login_datetime"]); ?></td>
						  <td><?php if($row["logout_datetime"]) echo get_date_format($row["logout_datetime"]); ?></td>
						  <td><?php if($row["amount"]) echo $row["amount"]; ?></td>
						  
						</tr>
					<?php } }else{ ?>
                  <tr>
                    <td colspan="3"><div align="center">No Records Found</div></td>
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
    <!-- /.container-fluid-->
<?php }
include 'template-admin.php';
?>