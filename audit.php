<?php
$page_name="Audit";
include("core/pagination/pagination.php");

function main() {
	global $db_helper_obj;
	// Search condition ==========================> Starts
	if(isset($_REQUEST['date']) && $_REQUEST['date']!=""){
		
		$arr= explode("-",$_REQUEST['date']);
		$from_date=trim($arr[0]);
		$to_date=trim($arr[1]);
		
		$from_date=str_replace("/", "-", $from_date);
		$from_time_stamp=strtotime(str_replace("@", "", $from_date));
		$_SESSION['from_date']=date('Y-m-d H:i:s',$from_time_stamp);
		$to_time_stamp=strtotime(str_replace("@", "",str_replace("/", "-", $to_date)));
		$_SESSION['to_date']=date('Y-m-d H:i:s',$to_time_stamp);
		$_SESSION['audit_name']=$_REQUEST['audit_name'];
		$_SESSION['audit_detail']=array();
		header("location: audit.php");
		exit();
		
	}
	if(isset($_REQUEST['next']) && $_REQUEST['next']!=""){
		$_SESSION['next']=true;
	}
	if(isset($_REQUEST['reset']) && $_REQUEST['reset']!=""){
		$ins_arr=array();
		$ins_arr["name"]=$_SESSION['audit_name'];
		$ins_arr["from_date"]=$_SESSION['from_date'];
		$ins_arr["to_date"]=$_SESSION['to_date'];
		$ins_arr["created_datetime"]=date("Y-m-d H:i:s",time());
		$ins_arr["created_by"]=$_SESSION["user_id"];
		$id=$db_helper_obj->add_audit($ins_arr);
		$tot=0;
		foreach($_SESSION['audit_detail'] as $va=>$key){
			$tot+=($key["org_amount"]-$key["amount"]);
			$ins_detail=array();
			$ins_detail=$key;
			$ins_detail["audit_id"]=$id;
			$ins_detail["created_datetime"]=date("Y-m-d H:i:s",time());
			$db_helper_obj->add_audit_details($ins_detail);
			$upd_arr=array();
			if($key["org_amount"]==0){
				$upd_arr["check_out"]=$ins_arr["created_datetime"];
				$upd_arr["check_out_transaction"]=4;
			}
			$upd_arr["last_updated"]=$ins_arr["created_datetime"];
			$upd_arr["amount"]=$key["amount"];
			$db_helper_obj->update_check_in_out_log($upd_arr,$key["trans_id"]);
		}
		$ins_trans=array();
		if($tot>0){
			$ins_trans['amount']=$tot;
			$ins_trans['trans_type']=2;
		}else{
			$ins_trans['amount']=$tot * (-1);
			$ins_trans['trans_type']=1;
		}
		$ins_trans['trans_from']=5;
		$ins_trans['trans_for_id']=$id;
		$ins_trans["created_datetime"]=date("Y-m-d H:i:s",time());
		$ins_trans["created_by"]=$_SESSION["user_id"];
		$db_helper_obj->add_transaction($ins_trans);
		
		unset($_SESSION['audit_name']);
		unset($_SESSION['audit_detail']);
		unset($_SESSION['from_date']);
		unset($_SESSION['to_date']);
		unset($_SESSION['next']);
		unset($_SESSION['audited_id']);
		header("location: audit.php");
		exit();
		
	}
	// Search condition ==========================> Ends
	if(isset($_SESSION['from_date']) && isset($_SESSION['to_date'])){
		$trans_type=array(1=>'Print',2=>'SMS');
		global $db_helper_obj;
		$where="1";
		$where_arr=array();
		if(isset($_SESSION["audited_id"]) && count($_SESSION["audited_id"])>0){
			if(isset($_SESSION['next']) && $_SESSION['next']===true){
				$where.=" and f.id in (".implode(',',$_SESSION["audited_id"]).")";
			}else{
				$where.=" and f.id not in (".implode(',',$_SESSION["audited_id"]).")";
			}
		}
		$where.=" and (f.last_updated >=? and  f.last_updated <=?)";
		$where_arr[]=$_SESSION['from_date'];
		$where_arr[]=$_SESSION['to_date'];
		$page_url_form="";
		$sort_by="";
		$transaction_list=$db_helper_obj->get_transaction_list($where,$where_arr,$sort_by); // getting all the users data
	}
	?>
<link rel="stylesheet" type="text/css" media="all" href="assets/vendor/daterange/daterangepicker.css" />

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          Audit</div>
        <div class="card-body">
		<?php if(!isset($_SESSION["from_date"])){?>
			<form name="search_form" method="get">
				<div class="form-row">
					<div class="col-md-3 ">
					<label for="name_id">Audit Name</label>
					<input required name="audit_name" id="name_id" value="<?php echo form_search('audit_name')?>" type="text"  autocomplete="off" class="form-control">
					</div>
					<div class="col-md-8 ">
                    <label for="config-demo">Date Range</label>
					 <input name="date" value="<?php echo form_search('date')?>" type="text" id="config-demo" autocomplete="off" class="form-control">
					 
                    </div>
					
					<div class="col-md-2 ">
					 <button type="submit" style="margin-top: 28px;" class="btn btn-primary "><i class="fa fa-search"></i>&nbsp;Search</button>
                    </div>
                </div>
			</form>
		<?php }else{ 
			if(isset($_SESSION['next']) && $_SESSION['next']===true){?>
		<a href="audit.php?reset=1"><button class="btn btn-danger">Finish</button></a>
			<?php }else{?>
		<a href="audit.php?next=1"><button class="btn btn-primary">Next</button></a>
			<?php } ?>
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
						<th>Make Model</th>
						<th>Slot Name</th>
						<th>Customer </th>
						<th>Check In </th>
						<th>Check Out </th>
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
						  <td><?php echo get_date_format($row["check_in"]); ?></td>
						  <td><?php if(isset($row["check_out"])) echo get_date_format($row["check_out"]); ?></td>
						  <td><?php if($row["amount"])echo $row["amount"]." (".$row["slot_count"].")"; ?></td>
						  <td>
						  <?php if(isset($_SESSION['next']) && $_SESSION['next']===true){?>
						  <i data-toggle="modal" data-target="#myModal" class="fa fa-pencil" aria-hidden="true" onclick="edit_row(this,<?php echo $row["id"]; ?>,<?php echo $row["amount"]; ?>)"></i>
						  <?php }else{ ?>
						  <i  class="fa fa-sign-out" aria-hidden="true" onclick="delete_row(this,<?php echo $row["id"]; ?>,0)"></i>
						  <?php } ?>
						  </td>
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
			
		  </div>
        </div>
		<?php } ?>
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">Edit</h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	  <form name="edit" method="post" id="edit_form" >
        <div class="row">
		<div class="col-md-12 ">
			<div class="form-group">
			<label>Amount</label>
				<input type="text" id="amount_id" class="form-control input-sm" name="amount" />
			</div>
			</div>
		</div> 
		<input type="hidden" name="org_amount" id="org_amount_id" value="" />
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- /.container-fluid-->
<?php }
include 'template-admin.php';
?>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
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
	  var dtList;
	  $(document).ready( function () {
     dtList= $('#dataTable').DataTable();
	  
	} );
function delete_row(obj,id){
	 $.ajax({
           type: "POST",
           url: 'ajax/audit_edit.php?id='+id,
           success: function(data)
           {
               //alert(data); // show response from the php script.
           }
         });
	dtList.row(obj.closest('tr')).remove()
        .draw();
}
var edit_obj;
function edit_row(obj,id,amnt){
	edit_obj=obj;
	document.getElementById('notes_id').value='';
	document.getElementById('amount_id').value='';
	document.getElementById('org_amount_id').value='';
	document.getElementById('trans_id').value=id;
	if(amnt){
		document.getElementById('amount_id').value=amnt;
		document.getElementById('org_amount_id').value=amnt;
	}
	console.log(obj);
}
//$("#").ajaxSubmit({url: 'ajax/edit_audit.php', type: 'post'})
$("#edit_form").submit(function(e) {

    var form = $(this);
    var url = form.attr('action');

    $.ajax({
           type: "POST",
           url: 'ajax/audit_edit.php',
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {
               //alert(data); // show response from the php script.
           }
         });
	dtList.row(edit_obj.closest('tr')).remove()
        .draw();
		$('#myModal').modal('hide');
    e.preventDefault(); // avoid to execute the actual submit of the form.
});

	</script>