<?php
$page_name="Customer";
include("core/pagination/pagination.php");
function main() {
	global $db_helper_obj;
	// Pagination 
	$item_per_page=10;
	$page_number=get_page_no();
	
	$sort_by=get_sort_by();
	if($sort_by=="")
		$sort_by=" order by created_datetime desc"; // default sort by
	$where="1";
	$where_arr=array();
	$page_url_form="";
	// Search condition ==========================> Starts
	if(isset($_REQUEST['name']) && $_REQUEST['name']!=""){
		$where.=" and ( name like ? or mobile_number like ?)";
		$where_arr[]=$_REQUEST['name']."%";
		$where_arr[]=$_REQUEST['name']."%";
		$page_url_form.="&name=".$_REQUEST['name'];
	}

	// Search condition ==========================> Ends
	$customer_list=$db_helper_obj->get_customer_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($customer_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$customer_list=$db_helper_obj->get_customer_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	//$users_list=$db_helper_obj->get_users();
	?>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          Customer List</div>
        <div class="card-body">
			<form name="search_form" method="post">
				<div class="form-row">
                    <div class="col-md-6 mb-6">
                    <label for="name_id">Search</label>
					<input name="name" type="text" class="form-control" id="name_id" placeholder="Name \ Mobile Number" value="<?php echo form_search('name')?>">
					 
                    </div> 
					<div class="col-md-6 mt-6">
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
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Name</a></th>
						<th class="<?php echo get_sort_class("mobile_number");?>"><a href="<?php echo get_sort_url("mobile_number"); ?> ">Mobile Number</a></th>
						<th class="<?php echo get_sort_class("address");?>"><a href="<?php echo get_sort_url("address"); ?> ">Address</a></th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($customer_list) > 0){ $cnt=0; foreach($customer_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $cnt; ?></td>
						  <td><?php echo $row["name"]; ?></td>
						  <td><?php echo $row["mobile_number"]; ?></td>
						  <td><?php echo $row["address"]; ?></td>
						    <td><button onclick="call_ajax_url('cust_veh','cust_content','id=<?php echo $row['id']; ?>')" type="button" class="btn btn-primary  btn-xs mb-3" data-toggle="modal" data-target="#cust_veh_modal">Vehicles</button>&nbsp;&nbsp;&nbsp;<button onclick="call_ajax_url('cust_trnsc_hstry','cust_transc_content','id=<?php echo $row['id']; ?>')" type="button" class="btn btn-primary  btn-xs mb-3" data-toggle="modal" data-target="#cust_transc_modal">Transaction</button></td>
						  
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
</div>
    <!-- /.container-fluid-->
	<div class="modal fade" id="cust_veh_modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Vehicles</h5>
					<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				</div>
				<div class="modal-body" id="cust_content">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div> <!-- /.container-fluid-->
	<div class="modal fade" id="cust_transc_modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Vehicles Transaction</h5>
					<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				</div>
				<div class="modal-body" id="cust_transc_content">
					
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