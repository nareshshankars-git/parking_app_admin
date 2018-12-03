<?php
$page_name="Vehicle";
include("core/pagination/pagination.php");
function main() {
	global $db_helper_obj;
	// Pagination 
	$item_per_page=10;
	$page_number=get_page_no();
	
	$sort_by=get_sort_by();
	if($sort_by=="")
		$sort_by=" order by b.created_datetime desc"; // default sort by
	$where="1";
	$where_arr=array();
	$page_url_form="";
	// Search condition ==========================> Starts
	if(isset($_REQUEST['name']) && $_REQUEST['name']!=""){
		$where.=" and ( a.name like ? or a.mobile_number like ?)";
		$where_arr[]=$_REQUEST['name']."%";
		$where_arr[]=$_REQUEST['name']."%";
		$page_url_form.="&name=".$_REQUEST['name'];
	}
	if(isset($_REQUEST['veh_no']) && $_REQUEST['veh_no']!=""){
		$where.=" and ( b.city like ? or b.alpha like ? or b.reg_no like ?)";
		$where_arr[]=$_REQUEST['veh_no']."%";
		$where_arr[]=$_REQUEST['veh_no']."%";
		$where_arr[]=$_REQUEST['veh_no']."%";
		$page_url_form.="&veh_no=".$_REQUEST['veh_no'];
	}
	if(isset($_REQUEST['slot_id']) && $_REQUEST['slot_id']!=""){
		$where.=" and b.slot_id=?";
		$where_arr[]=$_REQUEST['slot_id'];
		$page_url_form.="&slot_id=".$_REQUEST['slot_id'];
	}

	// Search condition ==========================> Ends
	$vehicle_list=$db_helper_obj->get_vehicle_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($vehicle_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$vehicle_list=$db_helper_obj->get_vehicle_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	$slot_result=$db_helper_obj->get_slots();
	?>
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          Vehicle List</div>
        <div class="card-body">
			<form name="search_form" method="get">
				<div class="form-row">
					<div class="col-md-4">
                    <label for="name_id">Customer Name/ Mobile No</label>
					<input name="name" type="text" class="form-control" id="name_id" placeholder="Name \ Mobile Number" value="<?php echo form_search('name')?>">
					 
                    </div> 
                    <div class="col-md-3">
					
                    <label for="name_id">Vehicle Number</label>
					<input name="veh_no" type="text" class="form-control" id="name_id" placeholder="Vehicle Number" value="<?php echo form_search('veh_no')?>">
					 
                    </div> 
					<div class="col-md-3">
					
                    <label for="slot_id">Slot</label>
					<select name="slot_id" class="form-control" id="slot_id" >
					<option value="" selected >Choose Slot</option>
					<?php foreach($slot_result as $va=>$key){?>
						<option <?php form_search_select($key["id"],"slot_id");?> value="<?php echo $key["id"]; ?>"><?php echo $key["name"]; ?></option>
						<?php }?>
					</select>
					 
                    </div>
					
					<div class="col-md-2 ">
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
						<th class="<?php echo get_sort_class("model");?>"><a href="<?php echo get_sort_url("model"); ?> ">Make Model</a></th>
						<th class="<?php echo get_sort_class("slot_name");?>"><a href="<?php echo get_sort_url("slot_name"); ?> ">Default Slot</a></th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($vehicle_list) > 0){ $cnt=0; foreach($vehicle_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $cnt; ?></td>
						  <td><?php echo $row["mobile_number"];if($row["name"]) echo "(".$row["name"].")"; ?></td>
						  <td><?php echo get_veh_no($row); ?></td>
						  <td><?php echo $row["model"]; ?></td>
						  <td><?php echo $row["slot_name"]; ?></td>
						  <td><a href="vehicle_edit.php?id=<?php echo $row["id"]; ?>" title="Edit">   <i class="fa fa-pencil"></i></a>
							&nbsp;&nbsp;&nbsp;<button onclick="call_ajax_url('trnsc_hstry','trnsc_content','id=<?php echo $row['id']; ?>')" type="button" class="btn btn-primary  btn-xs mb-3" data-toggle="modal" data-target="#trans_list_modal">Transaction History</button></td>
						  
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
<div class="modal fade" id="trans_list_modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Transaction History</h5>
					<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				</div>
				<div class="modal-body" id="trnsc_content">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<?php } 
include 'template-admin.php';
?>
