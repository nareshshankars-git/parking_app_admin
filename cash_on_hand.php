<?php
$page_name="Cash On Hand";
include("core/pagination/pagination.php");
function main() {
	global $db_helper_obj;
	// Pagination 
	$item_per_page=5;
	$page_number=get_page_no();
	
	$sort_by=get_sort_by();
	if($sort_by=="")
		$sort_by=" order by created_datetime desc"; // default sort by
	$where="1";
	$where_arr=array();
	$page_url_form="";

	$cash_list=$db_helper_obj->cash_on_hand_list($where,$where_arr,$sort_by); // getting all the users data
	$get_total_rows = count($cash_list);
	
	if($get_total_rows > $item_per_page){
		$page_position = (($page_number-1) * $item_per_page);
		$cash_list=$db_helper_obj->cash_on_hand_list($where,$where_arr," $sort_by LIMIT $page_position, $item_per_page");
		$page_url=get_page_url().$page_url_form;
	}
	?>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-address-book"></i>&nbsp;Cash On Hand List</div>
        <div class="card-body">
          <div class="table-responsive">
            <div id="dataTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
			<div class="row">
				<div class="col-sm-12">
				<?php echo get_success_alert();?>
					<a href="coh_add.php"><button class="btn btn-success mb-3 pull-right" type="button">Add Cash On Hand</button></a>
					<table class="table table-hover text-center dataTable" id="dataTable" cellspacing="0" role="grid" aria-describedby="dataTable_info">
					<thead>
					<tr role="row">
						<th>S.No</th>
					  
					  <th >Amount</th>
					  <th >Date Time</th>
					  <th >Action</th>
					</tr>
					</thead>
					<tbody>
					<?php if(count($cash_list) > 0){ $cnt=0; foreach($cash_list as $va=>$row){ $cnt++; ?>
						<tr>
						  <td><?php echo $cnt; ?></td>
						  <td><?php echo $row["amount"]; ?></td>
						  <td><?php echo get_date_format($row["created_datetime"]); ?></td>
						  
						 <td>
						  	<a href="coh_add.php?id=<?php echo $row["id"]; ?>" ><i class="fa fa-pencil"></i></a>
						 </td>
						</tr>
					<?php } }else{ ?>
                  <tr>
                    <td colspan="3"><div align="center">No data Found</div></td>
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