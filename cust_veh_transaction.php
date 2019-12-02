<?php
if(isset($_GET['id']) && $_GET['id']!=""){
$page_name="Transaction History";
include("core/pagination/pagination.php");
function main() {
	global $db_helper_obj;
	// Pagination 
	$trans_type=array(1=>'Print',2=>'SMS',3=>'WhatsApp',4=>'Audit');
	$item_per_page=10;
	$page_number=get_page_no();
	
	$sort_by=get_sort_by();
	if($sort_by=="")
		$sort_by=" order by a.created_datetime desc"; // default sort by
	$where="f.customer_id=?";
	$where_arr=array($_GET['id']);
	$page_url_form="&id=".$_GET['id'];

	$trans_history_list=$db_helper_obj->veh_trans_history_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($trans_history_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$trans_history_list=$db_helper_obj->veh_trans_history_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	?>
	 

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-address-book"></i>&nbsp;Transaction History</div>
        <div class="card-body">
          <div class="table-responsive">
            <div id="dataTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
			<div class="row">
				<div class="col-sm-12">
				<?php echo get_success_alert();?>
					<table class="table table-hover text-center dataTable" id="dataTable" cellspacing="0" role="grid" aria-describedby="dataTable_info">
					<thead>
					<tr role="row">
						<th>Token No</th>
						<th>Vehicle No</th>
						<th class="<?php echo get_sort_class("model");?>"><a href="<?php echo get_sort_url("model"); ?> ">Make Model</a></th>
						<th class="<?php echo get_sort_class("slot_name");?>"><a href="<?php echo get_sort_url("slot_name"); ?> ">Slot Name</a></th>
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Customer</a></th>
						<th class="<?php echo get_sort_class("check_in");?>"><a href="<?php echo get_sort_url("check_in"); ?> ">Check In</a></th>
						<th class="<?php echo get_sort_class("check_out");?>"><a href="<?php echo get_sort_url("check_out"); ?> ">Check Out</a></th>
						<th >Amount (Slot)</th>
					  
					</tr>
					</thead>
					<tbody>
					<?php if(count($trans_history_list) > 0){ $cnt=0; foreach($trans_history_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><span class="badge badge-<?php echo $row["color"]; ?>"><?php echo $row["token_no"]; ?></span></td>
						  <td><?php echo get_veh_no($row); ?></td>
						  <td><span class="badge badge-<?php if(isset($row["check_out"])) echo 'success'; else echo 'danger'; ?>"><?php echo $row["model"]; ?></span></td>
						  <td><?php echo $row["slot_name"]; ?></td>
						  <td><?php echo $row["mobile_number"];if($row["name"]) echo "(".$row["name"].")"; ?></td>
						  <td><?php echo get_date_format($row["check_in"]);  if(isset($row["check_in_transaction"])) echo " - (".$trans_type[$row["check_in_transaction"]].
					")"; ?></td>
						  <td><?php if(isset($row["check_out"])) echo get_date_format($row["check_out"]); if(isset($row["check_out"])) echo " - (".$trans_type[$row["check_out_transaction"]].")"; ?></td>
						  <td><?php if($row["amount"])echo $row["amount"]." (".$row["slot_count"].")"; ?></td>
						  
						</tr>
					<?php } }else{ ?>
                  <tr>
                    <td colspan="3"><div align="center">No Transaction Found</div></td>
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
}
?>