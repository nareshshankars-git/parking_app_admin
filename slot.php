<?php
$page_name="Slots";
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

	$slot_list=$db_helper_obj->get_slots_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($slot_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$slot_list=$db_helper_obj->get_slots_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	//$users_list=$db_helper_obj->get_users();
	?>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-address-book"></i>&nbsp;Slots List</div>
        <div class="card-body">
          <div class="table-responsive">
            <div id="dataTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
			<div class="row">
				<div class="col-sm-12">
				<?php echo get_success_alert();?>
					<a href="slot_add.php"><button class="btn btn-success mb-3 pull-right" type="button">Add Slot</button></a>
					<table class="table table-hover text-center dataTable" id="dataTable" cellspacing="0" role="grid" aria-describedby="dataTable_info">
					<thead>
					<tr role="row">
						<th>S.No</th>
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Slot Name</a></th>
						<th class="<?php echo get_sort_class("type_name");?>"><a href="<?php echo get_sort_url("type_name"); ?> ">Slot Type</a></th>
						<th class="<?php echo get_sort_class("amount");?>"><a href="<?php echo get_sort_url("amount"); ?> ">Amount</a></th>
						<th class="<?php echo get_sort_class("hours");?>"><a href="<?php echo get_sort_url("hours"); ?> ">Hours</a></th>
						<th class="<?php echo get_sort_class("color");?>"><a href="<?php echo get_sort_url("color"); ?> ">Color</a></th>
						<th class="<?php echo get_sort_class("status");?>"><a href="<?php echo get_sort_url("status"); ?> ">Status</a></th>
						<th class="<?php echo get_sort_class("grace_period");?>"><a href="<?php echo get_sort_url("grace_period"); ?> ">Grace Period</a></th>
					  <th >Action</th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($slot_list) > 0){ $cnt=0; foreach($slot_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $cnt; ?></td>
						  <td><?php echo $row["name"]; ?></td>
						  <td><?php echo $row["type_name"]; ?></td>
						  <td><?php echo $row["amount"]; ?></td>
						  <td><?php echo $row["hours"]; ?></td>
						  <td><?php echo $row["color"]; ?></td>
						  <td><?php if($row["status"]==1) echo '<span class="badge badge-success">Active</span>'; else echo '<span class="badge badge-danger">In Active</span>'; ?></td>
						   <td><?php echo $row["grace_period"]; ?></td>
						 <td>
						  	<a href="slot_add.php?id=<?php echo $row["id"]; ?>" title="Make it as Inactive">   <i class="fa fa-pencil"></i></a>
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
    <!-- /.container-fluid-->
<?php }
include 'template-admin.php';
?>