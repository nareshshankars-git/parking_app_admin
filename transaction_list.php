<?php
$page_name="Transaction List";
include("core/pagination/pagination.php");
function main() {
	global $db_helper_obj;
	if(isset($_GET['id']) && isset($_GET['action']) && $_GET['id']!="" ){
		$data=$db_helper_obj->get_transaction_by_id($_GET['id']);
		if(count($data)>0){
			$ins_arr=$data;
			$ins_arr["log_id"]=$data["id"];
			unset($ins_arr['id']);
			$new_id=$db_helper_obj->add_transaction_cancel($ins_arr);
			if($_GET['action']=="cancel_in"){
				$db_helper_obj->delete_transaction($data["id"]);
				set_success_msg('Cancelled Check In Successfully');
			}else{
				$upd_arr=array();
				$upd_arr['check_out']=null;
				$upd_arr['slot_count']=0;
				$upd_arr['amount']=0;
				$upd_arr['check_out_transaction']=0;
				$upd_arr['last_updated']=date("Y-m-d H:i:s",time());
				$db_helper_obj->update_check_in_out_log($upd_arr,$data["id"]);
				$ins_trans=array();
				$ins_trans['amount']=$data['amount'];
				$ins_trans['trans_from']=6;
				$ins_trans['trans_type']=2;
				$ins_trans['trans_for_id']=$new_id;
				$ins_trans["created_datetime"]=date("Y-m-d H:i:s",time());
				$ins_trans["created_by"]=$_SESSION["user_id"];
				$db_helper_obj->add_transaction($ins_trans);
				$db_helper_obj->update_transaction(array('trans_from'=>7),$data["id"],1);
				set_success_msg('Cancelled Check Out Successfully');
			}
			header("location: transaction_list.php");
			exit();
		}
	}
	$trans_type=array(1=>'Print',2=>'SMS',3=>'WhatsApp',4=>'Audit');
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
	if(isset($_REQUEST['date']) && $_REQUEST['date']!=""){
		$arr= explode("-",$_REQUEST['date']);
		$from_date=trim($arr[0]);
		$to_date=trim($arr[1]);
		$to_date=date("Y-m-d H:i:s", strtotime(str_replace("@", "", $to_date)));
		$from_date=date("Y-m-d H:i:s", strtotime(str_replace("@", "", $from_date)));
		$where.=" and (f.last_updated >=? and  f.last_updated <=?)";
		$where_arr[]=$from_date;
		$where_arr[]=$to_date;
		$page_url_form.="&date=".$_REQUEST['date'];
	}
	// Search condition ==========================> Ends
	$transaction_list=$db_helper_obj->get_transaction_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($transaction_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$transaction_list=$db_helper_obj->get_transaction_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	?>
	 <link rel="stylesheet" type="text/css" media="all" href="assets/vendor/daterange/daterangepicker.css" />
	
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          Transaction List</div>
        <div class="card-body">
			<form name="search_form" method="get">
				<div class="form-row">
					<div class="col-md-3 ">
                    <label for="name_id">Cust Name/ Mobile No</label>
					<input name="name" type="text" class="form-control" id="name_id" placeholder="Name \ Mobile Number" value="<?php echo form_search('name')?>">
					 
                    </div> 
                    <div class="col-md-3 ">
					
                    <label for="name_id">Vehicle Number</label>
					<input name="veh_no" type="text" class="form-control" id="name_id" placeholder="Vehicle Number" value="<?php echo form_search('veh_no')?>">
					 
                    </div> 
					<div class="col-md-4 ">
					
                    <label for="name_id">Date Range</label>
					 <input name="date" value="<?php echo form_search('date')?>" type="text" id="config-demo" autocomplete="off" class="form-control">
					 
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
						<th>Token No</th>
						<th>Vehicle No</th>
						<th class="<?php echo get_sort_class("model");?>"><a href="<?php echo get_sort_url("model"); ?> ">Make Model</a></th>
						<th class="<?php echo get_sort_class("slot_name");?>"><a href="<?php echo get_sort_url("slot_name"); ?> ">Slot Name</a></th>
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Customer</a></th>
						<th class="<?php echo get_sort_class("check_in");?>"><a href="<?php echo get_sort_url("check_in"); ?> ">Check In</a></th>
						<th class="<?php echo get_sort_class("check_out");?>"><a href="<?php echo get_sort_url("check_out"); ?> ">Check Out</a></th>
						<th >Amount (Slot)</th>
						<th >Action</th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($transaction_list) > 0){ $cnt=0; foreach($transaction_list as $va=>$row){ $cnt++; ?>
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
						  <td><?php if(isset($row["check_out"])){  if($row["show_co_cancel"]=="")echo '<a title="Cancel Check Out" href="?action=cancel_out&id='.$row['id'].'" class="btn btn-danger  btn-xs mb-3" ><i class="fa fa-ban" aria-hidden="true"></i> Check Out</a>'; }else echo '<a title="Cancel Check In"	href="?action=cancel_in&id='.$row['id'].'"					  class="btn btn-danger  btn-xs mb-3" ><i class="fa fa-ban" aria-hidden="true"></i> Check In</a>'; ?></td>
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
?>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>

      <script type="text/javascript" src="assets/vendor/daterange/daterangepicker.js"></script>
 <script type="text/javascript">
      $(document).ready(function() {
		  var  options={"timePicker": true,"timePicker24Hour":true,autoUpdateInput: false,'locale':{}};
		  options.locale = {
              direction: 'ltr',
              format: 'YYYY/MM/DD @ h:mm A',
              separator: ' - ',
              applyLabel: 'Apply',
              cancelLabel: 'Cancel',
              fromLabel: 'From',
              toLabel: 'To',
              customRangeLabel: 'Custom',
              daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
              monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
              firstDay: 1
            };
			$('#config-demo').daterangepicker(options, function(start, end, label) { //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
			//console.log(label);
			 $('#config-demo').val(start.format('YYYY/MM/DD @ HH:mm:ss')+' - '+end.format('YYYY/MM/DD @ HH:mm:ss'));
			});
			$('#config-demo').on('cancel.daterangepicker', function(ev, picker) {
				$('#config-demo').val('');
			});

	  });
	</script>