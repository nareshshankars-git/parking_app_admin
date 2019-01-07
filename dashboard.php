<?php
$page_name="Dashboard";
	
function main() {
	global $db_helper_obj;
	$data=$db_helper_obj->get_dashboard_data();
?>
	<div class="main-content-inner">
		<div class="row">
			<!-- seo fact area start -->
			<div class="col-lg-12">
				<div class="row">
					
					<div class="col-md-4 mt-5">
						<div class="card">
							<div class="seo-fact sbg2">
							<a href="customer.php">
								<div class="p-4 d-flex justify-content-between align-items-center">
									<div class="seofct-icon"><i class="ti-user"></i>Customer</div>
									<h2><?php echo $data['customer']; ?></h2>
								</div>
							</a>	
							</div>
						</div>
					</div>
					<div class="col-md-4  mt-5">
						<div class="card">
							<div class="seo-fact sbg3">
							<a href="vehicle.php">
								<div class="p-4 d-flex justify-content-between align-items-center">
									<div class="seofct-icon"><i class="ti-car"></i>Total Vehicle</div>
									<h2><?php echo$data['vehicle']; ?></h2>
									
								</div>
							</a>
							</div>
						</div>
					</div>
					<div class="col-md-4 mt-5">
						<div class="card">
							<div class="seo-fact sbg4">
							<a href="transaction_list.php">
								<div class="p-4 d-flex justify-content-between align-items-center">
									<div class="seofct-icon"><i class="ti-hand-point-right"></i>Parked In</div>
									<h2><?php echo$data['vehicle_in']; ?></h2>
								</div>
							</a>
							</div>
						</div>
					</div>
					<div class="col-md-4 mb-3">
						<div class="card">
							<div class="seo-fact sbg5">
							<a href="expense.php">
								<div class="p-4 d-flex justify-content-between align-items-center">
									<div class="seofct-icon"><i class="ti-money"></i>Expense</div>
									<h2><?php echo$data['expense']; ?></h2>
								</div>
							</a>
							</div>
						</div>
					</div>
					<div class="col-md-4 mb-3 mb-lg-0">
						<div class="card">
							<div class="seo-fact sbg6">
							<a href="transaction_list.php">
								<div class="p-4 d-flex justify-content-between align-items-center">
									<div class="seofct-icon"><i class="ti-wallet"></i>Collection</div>
									<h2><?php echo $data['balance']; ?></h2>
								</div>
							</a>
							</div>
						</div>
					</div>
					<div class="col-md-4 mb-3 mb-lg-0">
						<div class="card">
							<div class="seo-fact sbg7">
							<a href="cash_on_hand.php">
								<div class="p-4 d-flex justify-content-between align-items-center">
									<div class="seofct-icon"><i class="ti-receipt"></i>Cash Recieved</div>
									<h2><?php if($data['cash_recv']) echo $data['cash_recv']; else echo 0; ?></h2>
								</div>
							</a>
							</div>
						</div>
					</div>
					<div class="col-md-6 mt-5">
						<div class="card">
							<div class="seo-fact sbg1">
							<a href="monthly_pass.php">
								<div class="p-4 d-flex justify-content-between align-items-center">
									<div class="seofct-icon"><i class="ti-calendar"></i>Monthly Pass</div>
									<span style="color:white">Active:</span><h2><?php echo $data['monthly_pass_active']; ?></h2>
									<span style="color:white">Total:</span><h2><?php echo $data['monthly_pass']; ?></h2>
								</div>
							</a>	
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- seo fact area end -->
			
		</div>
	</div>


<?php }
include 'template-admin.php';
?>