<?php
$page_name="Check In List";
include("core/pagination/pagination.php");

function main() {
		global $db_helper_obj;
	if(isset($_POST['trans_id']) && $_POST['trans_id']!=""){
		$ins_arr=array();
		$ins_arr["notes"]=$_POST['notes'];
		$id=$db_helper_obj->update_check_in_out_log($ins_arr,$_POST["trans_id"]);
		header("location: audit.php");
		exit();
	}
	$trans_type=array(1=>'Print',2=>'SMS');

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
	$transaction_list=$db_helper_obj->get_check_in_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($transaction_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$transaction_list=$db_helper_obj->get_check_in_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	?>
	 <link rel="stylesheet" type="text/css" media="all" href="assets/vendor/daterange/daterangepicker.css" />
	
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          Check In List</div>
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
						<th>Check In Transaction</th>

						<th >Notes</th>
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
						  <td><?php echo get_date_format($row["check_in"]); ?></td>
						  <td><?php if(isset($row["check_in_transaction"])) echo $trans_type[$row["check_in_transaction"]]; ?></td>
							<td><?php if($row["notes"]) echo $row["notes"];
else{							?><button onclick="add_notes('<?php echo $row["id"]; ?>')" type="button" class="btn btn-primary  btn-xs mb-3" data-toggle="modal" data-target="#add_note_modal">Add Notes</button> <?php } ?></td>
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
<div class="modal fade" id="add_note_modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Note</h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
			</div>
			<div class="modal-body" id="detail_content">
				<form name="edit" method="post" id="edit_form" >
       
					<input type="hidden" name="trans_id" id="trans_id" value="" />
					<div class="row">
					<div class="col-md-12 ">
						<div class="form-group">
						<label>Notes</label>
							<textarea id="notes_id" class="form-control input-sm" name="notes"></textarea>
						</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 mt">
						<button type="submit" class="btn btn-primary" >Submit</button>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>

      <script type="text/javascript" src="assets/vendor/daterange/daterangepicker.js"></script>
 <script type="text/javascript">
      $(document).ready(function() {
		  var  options={"timePicker": true,autoUpdateInput: false,'locale':{}};
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
			$('#config-demo').daterangepicker(options);
			<?php $date="";
			if(isset($_REQUEST['date']) && $_REQUEST['date']!="")
				$date=$_REQUEST['date']; ?>
			
			$('#config-demo').val('<?php echo $date; ?>');
	  });
	  function add_notes(id){
		  document.getElementById('trans_id').value=id;
	  }
	</script>