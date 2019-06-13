<?php
$page_name="Audit List";
include("core/pagination/pagination.php");

function main() {
	$trans_type=array(1=>'Print',2=>'SMS');
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
	if(isset($_REQUEST['date']) && $_REQUEST['date']!=""){
		$arr= explode("-",$_REQUEST['date']);
		$from_date=trim($arr[0]);
		$to_date=trim($arr[1]);
		$to_date=date("Y-m-d H:i:s", strtotime(str_replace("@", "", $to_date)));
		$from_date=date("Y-m-d H:i:s", strtotime(str_replace("@", "", $from_date)));
		$where.=" and (created_datetime >=? and  created_datetime <=?)";
		$where_arr[]=$from_date;
		$where_arr[]=$to_date;
		$page_url_form.="&date=".$_REQUEST['date'];
	}
	// Search condition ==========================> Ends
	$transaction_list=$db_helper_obj->get_audit_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($transaction_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$transaction_list=$db_helper_obj->get_audit_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	?>
	 <link rel="stylesheet" type="text/css" media="all" href="assets/vendor/daterange/daterangepicker.css" />
	
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          Audit List</div>
        <div class="card-body">
			<form name="search_form" method="get">
				<div class="form-row">
					
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
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Name</th>
						<th class="<?php echo get_sort_class("from_date");?>"><a href="<?php echo get_sort_url("from_date"); ?> ">From Date</th>
						<th class="<?php echo get_sort_class("to_date");?>"><a href="<?php echo get_sort_url("to_date"); ?> ">To Date</a></th>
						<th class="<?php echo get_sort_class("created_datetime");?>"><a href="<?php echo get_sort_url("created_datetime"); ?> ">Created Datetime</a></th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($transaction_list) > 0){ $cnt=0; foreach($transaction_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $row["name"]; ?></td>
						  <td><?php echo $row["from_date"]; ?></td>
						  <td><?php echo $row["to_date"]; ?></td>
						  <td><?php  echo $row["created_datetime"]; ?></td>
						 <td><button onclick="call_ajax_url('audit_detail','detail_content','id=<?php echo $row['id']; ?>')" type="button" class="btn btn-primary  btn-xs mb-3" data-toggle="modal" data-target="#cust_transc_modal">Details</button></td>
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
<div class="modal fade" id="cust_transc_modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Audit Details</h5>
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