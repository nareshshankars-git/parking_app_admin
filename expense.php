<?php
$page_name="Expense";
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
	
	if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!=""){
		$where.=" and b.created_by=?";
		$where_arr[]=$_REQUEST['user_id'];
		$page_url_form.="&user_id=".$_REQUEST['user_id'];
	}

	// Search condition ==========================> Ends
	$expense_list=$db_helper_obj->get_expense_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($expense_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$expense_list=$db_helper_obj->get_expense_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	$staff_result=$db_helper_obj->get_staffs();
	?>
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          Expense List</div>
        <div class="card-body">
			<form name="search_form" method="get">
				<div class="form-row">
					
                   
					<div class="col-md-4">
					
                    <label for="user_id">Staff</label>
					<select name="user_id" class="form-control" id="user_id" >
					<option value="" selected >Choose Staff</option>
					<?php foreach($staff_result as $va=>$key){?>
						<option <?php form_search_select($key["id"],"user_id");?> value="<?php echo $key["id"]; ?>"><?php echo $key["name"]."(".$key["user_name"].")"; ?></option>
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
						<th class="<?php echo get_sort_class("name");?>"><a href="<?php echo get_sort_url("name"); ?> ">Staff</a></th>
						<th class="<?php echo get_sort_class("amount");?>"><a href="<?php echo get_sort_url("amount"); ?> ">Amount</a></th>
						<th class="<?php echo get_sort_class("notes");?>"><a href="<?php echo get_sort_url("notes"); ?> ">Notes</a></th>
						<th class="<?php echo get_sort_class("created_datetime");?>"><a href="<?php echo get_sort_url("created_datetime"); ?> ">Created Date Time</a></th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($expense_list) > 0){ $cnt=0; foreach($expense_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $cnt; ?></td>
						  <td><?php echo $row["name"]." (".$row["user_name"].")"; ?></td>
						  <td><?php echo $row["amount"]; ?></td>
						  <td><?php echo $row["notes"]; ?></td>
						  <td><?php  echo get_date_format($row["created_datetime"]);?></td>
						  <td><?php if($row["edit_history"]>1){?><a href="javascript:void(0)" title="History" onclick="call_ajax_url('expense_hstry','trnsc_content','id=<?php echo $row['id']; ?>')"  data-toggle="modal" data-target="#expn_list_modal">   <i class="fa fa-history"></i> Show Edit History(<?php echo $row["edit_history"]; ?>)</a><?php }?></td>
						  
						  
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
<div class="modal fade" id="expn_list_modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit History</h5>
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
