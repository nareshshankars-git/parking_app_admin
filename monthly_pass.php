<?php
$page_name="Monthly Pass";
include("core/pagination/pagination.php");

function main() {
	global $db_helper_obj;
	// Pagination 
	$item_per_page=10;
	$page_number=get_page_no();
	
	$sort_by=get_sort_by();
	if($sort_by=="")
		$sort_by=" order by f.created_datetime desc"; // default sort by
	$where="1";
	$where_arr=array();
	$page_url_form="";
	// Search condition ==========================> Starts
	if(isset($_REQUEST['name']) && $_REQUEST['name']!=""){
		$where.=" and ( a.name like ? or a.mobile_number like ?)";
		$where_arr[]="%".$_REQUEST['name']."%";
		$where_arr[]="%".$_REQUEST['name']."%";
		$page_url_form.="&name=".$_REQUEST['name'];
	}
	if(isset($_REQUEST['veh_no']) && $_REQUEST['veh_no']!=""){
		$where.=" and ( b.city like ? or b.alpha like ? or b.reg_no like ?)";
		$where_arr[]=$_REQUEST['veh_no']."%";
		$where_arr[]=$_REQUEST['veh_no']."%";
		$where_arr[]=$_REQUEST['veh_no']."%";
		$page_url_form.="&veh_no=".$_REQUEST['veh_no'];
	}	
	if(isset($_REQUEST['status']) && $_REQUEST['status']!=""){
		$where.=" and ( f.status)";
		$where_arr[]=$_REQUEST['status'];
		$page_url_form.="&status=".$_REQUEST['status'];
	}

	// Search condition ==========================> Ends
	$monthly_list=$db_helper_obj->get_monthly_pass($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($monthly_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$monthly_list=$db_helper_obj->get_monthly_pass($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	?>
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          Monthly Pass List</div>
        <div class="card-body">
			<form name="search_form" method="get">
				<div class="form-row">
					<div class="col-md-3 ">
                    <label for="name_id">Customer Name/ Mobile No</label>
					<input name="name" type="text" class="form-control" id="name_id" placeholder="Name \ Mobile Number" value="<?php echo form_search('name')?>">
					 
                    </div> 
                    <div class="col-md-3 ">
					
                    <label for="name_id">Vehicle Number</label>
					<input name="veh_no" type="text" class="form-control" id="name_id" placeholder="Vehicle Number" value="<?php echo form_search('veh_no')?>">
					 
                    </div>
					<div class="col-md-3 ">
					
                    <label for="status_id">Status</label>
					<select name="status" class="form-control" id="status_id" >
					<option value="" selected >Choose Slot</option>
						<option <?php form_search_select(0,"status");?> value="0">Inactive</option>
						<option <?php form_search_select(1,"status");?> value="1">Active</option>
						<option <?php form_search_select(2,"status");?> value="2">Cancelled</option>
					</select>
					 
                    </div>
					
					<div class="col-md-3 ">
					 <button type="submit" style="margin-top: 28px;" class="btn btn-primary "><i class="fa fa-search"></i>&nbsp;Search</button>
                    </div>
                </div>
			</form>
		</div>
          <div class="table-responsive">
            <div id="dataTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
			<div class="row">
				<div class="col-sm-12">
				<?php echo get_success_alert();?>
					<table class="table table-hover text-center dataTable" id="dataTable" cellspacing="0" role="grid" aria-describedby="dataTable_info">
					<thead>
					<tr role="row">
						<th>S.No</th>
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Customer</a></th>
						<th>Vehicle No</th>
						<th class="<?php echo get_sort_class("pass_no");?>"><a href="<?php echo get_sort_url("pass_no"); ?> ">Pass No</a></th>
						<th class="<?php echo get_sort_class("model");?>"><a href="<?php echo get_sort_url("model"); ?> ">Make Model</a></th>
						<th class="<?php echo get_sort_class("slot_name");?>"><a href="<?php echo get_sort_url("slot_name"); ?> ">Default Slot</a></th>
						<th class="<?php echo get_sort_class("month");?>"><a href="<?php echo get_sort_url("month"); ?> ">Renewaled Month</a></th>
						<th class="<?php echo get_sort_class("updated_datetime");?>"><a href="<?php echo get_sort_url("updated_datetime"); ?> ">Renewaled Date Time</a></th>
						<th class="<?php echo get_sort_class("status");?>"><a href="<?php echo get_sort_url("status"); ?> ">Status</a></th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($monthly_list) > 0){ $cnt=0; foreach($monthly_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $cnt; ?></td>
						  <td><?php echo $row["mobile_number"];if($row["name"]) echo "(".$row["name"].")"; ?></td>
						  <td><?php echo get_veh_no($row); ?></td>
						  <td><?php echo $row["pass_no"]; ?></td>
						  <td><?php echo $row["model"]; ?></td>
						  <td><?php echo $row["slot_name"]; ?></td>
						  <td><?php echo get_month($row["month"]); ?></td>
						  <td><?php echo get_date_format($row["updated_datetime"]); ?></td>
						  <td><?php if($row["status"]==1) echo '<span class="badge badge-success">Active</span>'; else if($row["status"]==2) echo '<span class="badge badge-danger">Cancelled</span>';else  echo '<span class="badge badge-warning">Inactive</span>'; ?></td>
						  <td><a href="monthly_pass_edit.php?id=<?php echo $row["id"]; ?>" title="Edit">   <i class="fa fa-pencil"></i></a>
							&nbsp;<button onclick="call_ajax_url('renewal_hstry','renewal_content','id=<?php echo $row['id']; ?>')" type="button" class="btn btn-primary  btn-xs mb-3" data-toggle="modal" data-target="#renewal_list_modal">Renewal History</button></td>
						</tr>
					<?php } }else{ ?>
                  <tr>
                    <td colspan="3"><div align="center">No Record Found</div></td>
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
<!-- Large modal -->

	<div class="modal fade" id="renewal_list_modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Renewal History</h5>
					<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				</div>
				<div class="modal-body" id="renewal_content">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>


    <!-- /.container-fluid-->
<?php }
include 'template-admin.php';
?>
