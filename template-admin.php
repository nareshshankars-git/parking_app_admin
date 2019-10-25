<?php
include("config/config.php");
if((isset($_SESSION["user_id"]))){ // checking whether valid session
	
}else{
	header("location: logout.php");
	exit();
}
include("core/class/db_query.php");                               // lib class for query builder
include("core/class/db_helper_admin.php");                      // helper class to cnct with db
global $current_page;
$current_page = basename($_SERVER['PHP_SELF']);
include("core/function/common.php");
ob_start();
global $db_helper_obj;
$db_helper_obj=new db_helper();
$site_path = 'http://'.$_SERVER['HTTP_HOST'];   // setting path

        // getting current page name
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Parking App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <!-- others css -->
    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<style>
	.dataTables_wrapper .dataTables_paginate .paginate_button {
    box-sizing: border-box;
    display: inline-block;
    min-width: 1.5em;
     padding: 0em 0em; 
    margin-left: 2px;
    text-align: center;
    text-decoration: none !important;
    cursor: pointer;
    *: ;
    cursor: hand;
    color: #333 !important;
    border: 1px solid transparent;
    border-radius: 2px;
}
.modal-body {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1rem;
]   overflow-y: scroll;
    height: 400px;
}
	</style>
</head>

<body>
    <!-- preloader area start 
        <div id="preloader">
        <div class="loader"></div>
    </div>-->
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="dashboard.php">Parking App</a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li class="<?php echo get_menu_active('dashboard.php'); ?>">
                                <a href="dashboard.php" aria-expanded="true"><i class="ti-dashboard"></i><span>Dashboard</span></a>
							</li>
							<li class="<?php echo get_menu_active('transaction_list.php'); ?>">
                                <a href="transaction_list.php" aria-expanded="true"><i class="ti-exchange-vertical"></i><span>Transactions</span></a>
							</li>
							<li class="<?php echo get_menu_active('check_in_list.php'); ?>">
                                <a href="check_in_list.php" aria-expanded="true"><i class="ti-list"></i><span>Check In List</span></a>
							</li>
							<li class="<?php echo get_menu_active('pass_book.php'); ?>">
                                <a href="pass_book.php" aria-expanded="true"><i class="ti-book"></i><span>Pass Book</span></a>
							</li>
							<li class="<?php echo get_menu_active('cash_on_hand.php'); ?>">
                                <a href="cash_on_hand.php" aria-expanded="true"><i class="ti-briefcase"></i><span>Cash Recieved</span></a>
							</li>
							<li class="<?php echo get_parent_active(array('audit.php','audit_list.php')); ?>">
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-agenda"></i><span>Audit</span></a>
								<ul class="collapse">
                                    <li class="<?php echo get_menu_active('audit.php'); ?>"><a href="audit.php">Audit Add</a></li> <li class="<?php echo get_menu_active('audit_list.php'); ?>"><a href="audit_list.php">Audit List</a></li>
								</ul>
							</li>
							<li class="<?php echo get_parent_active(array('customer.php','vehicle.php','monthly_pass.php','otp_list.php')); ?>">
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-link"></i><span>Customer</span></a>
                                <ul class="collapse">
                                    <li class="<?php echo get_menu_active('customer.php'); ?>"><a href="customer.php">Customer List</a></li>
									<li class="<?php echo get_menu_active('vehicle.php'); ?>"><a href="vehicle.php">Vehicle List </a></li>
									<li class="<?php echo get_menu_active('monthly_pass.php'); ?>"><a href="monthly_pass.php">Monthly Pass List</a></li>
									<li class="<?php echo get_menu_active('otp_list.php'); ?>"><a href="otp_list.php">Check out OTP List</a></li>
                                    
                                </ul>
                            </li>
							<li class="<?php echo get_parent_active(array('users.php','login_history.php','expense.php','login_history_pic.php')); ?>">
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i><span>Staffs</span></a>
                                <ul class="collapse">
                                    <li class="<?php echo get_menu_active('users.php'); ?>"><a href="users.php">Staff List</a></li> 
									<li class="<?php echo get_menu_active('expense.php'); ?>"><a href="expense.php">Expense List</a></li>
									<li class="<?php echo get_menu_active('login_history.php'); ?>"><a href="login_history.php">Login History</a></li><li class="<?php echo get_menu_active('login_history_pic.php'); ?>"><a href="login_history_pic.php">Unlock History</a></li>
                                    
                                </ul>
                            </li>
							<li class="<?php echo get_parent_active(array('slot.php','make_model.php','state.php','general_setting.php')); ?>">
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i><span>Settings</span></a>
                                <ul class="collapse">
                                    <li class="<?php echo get_menu_active('slot.php'); ?>"><a href="slot.php">Slots</a></li>
									<li class="<?php echo get_menu_active('state.php'); ?>"><a href="state.php">State</a></li>
									<li class="<?php echo get_menu_active('make_model.php'); ?>"><a href="make_model.php">Make Model</a></li>
									<li class="<?php echo get_menu_active('general_setting.php'); ?>"><a href="general_setting.php">General Setting</a></li>
                                    
                                </ul>
                            </li>
							
							
						</ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <!-- nav and search button -->
                    <div class="col-md-12 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
						<div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left"><?php echo $page_name; ?></h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="index.php">Home</a></li>
                                <li><span><?php echo $page_name; ?></span></li>
                            </ul>
							
								<div class="user-profile pull-right">
								<p>Current Logged in Staff</p>
                            
                            <h4 class="user-name" ><?php echo $db_helper_obj->get_current_staff_user(); ?> </h4>
						</div>
							<a class="nav-link pull-right" data-toggle="modal" data-target="#logoutModal">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
                        </div>
                   
 
					</div>
                   
                </div>
            </div>
            <!-- header area end -->
			 <?php main();?>
		</div>
        <!-- main content area end -->
        <!-- footer area start-->
        <footer>
            <div class="footer-area">
                <p>© Copyright 2018. All right reserved by <a href="">AppsComp</a>.</p>
            </div>
        </footer>
        <!-- footer area end-->
    </div>
    <!-- page container area end -->
   <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="logout.php">Logout</a>
          </div>
        </div>
      </div>
    </div>

      <!-- jquery latest version -->
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>

    <!-- others plugins -->
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/scripts.js"></script>
		<script src="core/js/core_validation.js"></script>
		<script src="core/js/core_common.js"></script>

</body>

 
<script>
	$.validate();     // initializing validation script
	
	</script>
</html>
