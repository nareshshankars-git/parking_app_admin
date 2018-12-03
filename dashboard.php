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
					<div class="col-md-6 mt-5 mb-3">
						<div class="card">
							<div class="seo-fact sbg1">
							<a href="monthly_pass.php">
								<div class="p-4 d-flex justify-content-between align-items-center">
									<div class="seofct-icon"><i class="ti-calendar"></i>Monthly Pass</div>
									<h2>Active:<?php echo $data['monthly_pass_active']; ?></h2><h2>Total:<?php echo $data['monthly_pass']; ?></h2>
								</div>
							</a>	
							</div>
						</div>
					</div>
					<div class="col-md-6 mt-md-5 mb-3">
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
					<div class="col-md-6 mb-3 mb-lg-0">
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
					<div class="col-md-6">
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
				</div>
			</div>
			<!-- seo fact area end -->
			
		</div>
	</div>


<?php }
include 'template-admin.php';
?>