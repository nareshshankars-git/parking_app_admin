<?php
$page_name="Pass Book";
include("core/pagination/pagination.php");

function main() {
	$trans_color=array('1'=>'success','2'=>'danger');
	global $db_helper_obj;
	// Pagination 
	$item_per_page=20;
	$page_number=get_page_no();
	
	$sort_by=get_sort_by();
	if($sort_by=="")
		$sort_by=" order by a.created_datetime desc"; // default sort by
	$where="1";
	$where_arr=array();
	$page_url_form="";
	// Search condition ==========================> Starts

	if(isset($_REQUEST['date']) && $_REQUEST['date']!=""){
		$arr= explode("-",$_REQUEST['date']);
		$from_date=trim($arr[0]);
		$to_date=trim($arr[1]);
		$from_date=str_replace("/", "-", $from_date)." 00:00:00";
		$to_date=str_replace("/", "-", $to_date)." 23:59:59";
		$where.=" and (a.created_datetime >=? and  a.created_datetime <=?)";
		$where_arr[]=$from_date;
		$where_arr[]=$to_date;
		$page_url_form.="&date=".$_REQUEST['date'];
	}
	if(isset($_REQUEST['created_by']) && $_REQUEST['created_by']!=""){
		$where.=" and a.created_by=?";
		$where_arr[]=$_REQUEST['created_by'];
		$page_url_form.="&created_by=".$_REQUEST['created_by'];
	}
	if(isset($_REQUEST['trans_from']) && $_REQUEST['trans_from']!=""){
		$where.=" and a.trans_from=?";
		$where_arr[]=$_REQUEST['trans_from'];
		$page_url_form.="&trans_from=".$_REQUEST['trans_from'];
	}
	if(isset($_REQUEST['trans_type']) && $_REQUEST['trans_type']!=""){
		$where.=" and a.trans_type=?";
		$where_arr[]=$_REQUEST['trans_type'];
		$page_url_form.="&trans_type=".$_REQUEST['trans_type'];
	}
	// Search condition ==========================> Ends
	$transaction_list=$db_helper_obj->pass_book_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($transaction_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$transaction_list=$db_helper_obj->pass_book_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	$trans_type_data=$db_helper_obj->get_trans_type();
	$trans_from_data=$db_helper_obj->get_trans_from();
	$staff_data=$db_helper_obj->get_all_staff();
	?>
	 <link rel="stylesheet" type="text/css" media="all" href="assets/vendor/daterange/daterangepicker.css" />
	
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          Pass Book List</div>
        <div class="card-body">
			<form name="search_form" method="get">
				<div class="form-row">
					<div class="col-md-2 ">
                    <label for="trans_type">Type</label>
					<select name="trans_type" class="form-control" id="trans_type" >
					<option value="" selected >Choose Transaction</option>
					<?php foreach($trans_type_data as $va=>$key){?>
						<option <?php form_search_select($key["id"],"trans_type");?> value="<?php echo $key["id"]; ?>"><?php echo $key["name"]; ?></option>
						<?php }?>
					</select>
					 
                    </div>
					<div class="col-md-2 ">
                    <label for="trans_from">Transaction From</label>
					<select name="trans_from" class="form-control" id="trans_from" >
					<option value="" selected >Choose Transaction</option>
					<?php foreach($trans_from_data as $va=>$key){?>
						<option <?php form_search_select($key["id"],"trans_from");?> value="<?php echo $key["id"]; ?>"><?php echo $key["name"]; ?></option>
						<?php }?>
					</select>
					 
                    </div>
					<div class="col-md-2 ">
                    <label for="created_by">Staffs</label>
					<select name="created_by" class="form-control" id="created_by" >
					<option value="" selected >Choose Staff</option>
					<?php foreach($staff_data as $va=>$key){?>
						<option <?php form_search_select($key["id"],"created_by");?> value="<?php echo $key["id"]; ?>"><?php echo $key["name"]."(".$key["user_name"].")"; ?></option>
						<?php }?>
					</select>
					 
                    </div> 
                     
					<div class="col-md-4 ">
					
                    <label for="name_id">Date Range</label>
					 <input name="date" value="<?php echo form_search('date')?>" type="text" id="config-demo" class="form-control">
					 
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
						<th class="<?php echo get_sort_class("from_name");?>"><a href="<?php echo get_sort_url("from_name"); ?> ">Transaction</th>
						<th class="<?php echo get_sort_class("t_type");?>"><a href="<?php echo get_sort_url("t_type"); ?> ">Credit</th>
						<th class="<?php echo get_sort_class("t_type");?>"><a href="<?php echo get_sort_url("t_type"); ?> ">Debit</th>
						<th class="<?php echo get_sort_class("amount");?>"><a href="<?php echo get_sort_url("amount"); ?> ">Amount</a></th>
						<th >Balance</th>
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Staff</a></th>
						<th class="<?php echo get_sort_class("created_datetime");?>"><a href="<?php echo get_sort_url("created_datetime"); ?> ">Date Time</a></th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($transaction_list) > 0){ $cnt=0; foreach($transaction_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $row["from_name"]; ?></td>
						  <td><?php if($row["trans_type"]==1){?><span class="badge badge-<?php echo $trans_color[$row["trans_type"]]; ?>"><?php echo $row["t_type"]; ?></span><?php }?></td>
						  <td><?php if($row["trans_type"]==2){?><span class="badge badge-<?php echo $trans_color[$row["trans_type"]]; ?>"><?php echo $row["t_type"]; ?></span><?php } ?></td>
						  
						  <td><?php echo $row["amount"]; ?></td>
						  <td><?php echo $row["balance"]; ?></td>
						  <td><?php echo $row["user_name"]."(".$row["name"].")"; ?></td>
						  <td><?php echo get_date_format($row["created_datetime"]); ?></td>
						  <td><button onclick="call_ajax_url('pass_book_detail','detail_content','id=<?php echo $row['id']; ?>&trans_from=<?php echo $row['trans_from']?>&trans_for_id=<?php echo $row['trans_for_id']?>')" type="button" class="btn btn-primary  btn-xs mb-3" data-toggle="modal" data-target="#pass_book_modal">Details</button></td>
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
<div class="modal fade" id="pass_book_modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Details</h5>
					<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				</div>
				<div class="modal-body" id="detail_content">
					
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
<script type="text/javascript" src="assets/vendor/daterange/daterangepicker.js"></script>
<script type="text/javascript">
      $(document).ready(function() {
		  var  options={"timePicker": true,'locale':{}};
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
			$('#config-demo').daterangepicker(options, function(start, end, label) { console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')'); });
	  });
	</script>