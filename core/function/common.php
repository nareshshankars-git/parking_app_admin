<?php 

	function set_success_msg($msg){
		$_SESSION["success_msg"]=$msg;
	}
	function get_success_msg(){
		$msg="";
		if(isset($_SESSION["success_msg"]) && $_SESSION["success_msg"]){
		$msg=$_SESSION["success_msg"];
		unset($_SESSION["success_msg"]); 
		}
		return $msg;
		
	}
	function validate_mobile($mobile){
	  return preg_match('/^[0-9]{10}+$/', $mobile);
	}
	function get_success_alert(){
		if($msg=get_success_msg()){
			echo '<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4>'.$msg.'</h4>
			</div>';
		}
		 
	}
	function set_failed_msg($msg){
		$_SESSION["failed_msg"]=$msg;
	}
	function get_failed_msg(){
		$msg="";
		if(isset($_SESSION["failed_msg"]) && $_SESSION["failed_msg"]){
		$msg=$_SESSION["failed_msg"];
		unset($_SESSION["failed_msg"]); 
		}
		return $msg;
		
	}
	function get_failed_alert(){
		if($msg=get_failed_msg()){
			echo '<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4>'.$msg.'</h4>
			</div>';
		}
		 
	}
	function get_menu_active($page){
		global $current_page;
		if($current_page==$page){
			return 'active';
		}
	}
	function get_parent_active($page){
		global $current_page;
		if(in_array($current_page,$page))
			return 'active';
	}
	function get_date_format($data){
		$date=date_create($data);
		return date_format($date,"D j M y - h:i A");
	}
	function getselected($id,$value){
		if($id==$value)
			echo 'selected="selected"';
	}	
	function form_search($value){
		if(isset($_REQUEST[$value]) && $_REQUEST[$value]!="")
			return $_REQUEST[$value];
	}
	function form_search_select($id,$value){
		if(isset($_REQUEST[$value]) && $_REQUEST[$value]==$id)
			echo 'selected="selected"';
	}
	function get_veh_no($data){
		if($data["city"]==0){
			$veh_no=$data["alpha"]." ".$data["reg_no"];
		}else{
			$veh_no=$data["state"]." ".$data["city"];
			if($data["alpha"])
				$veh_no.=" ".$data["alpha"];
			$veh_no.=" ".$data["reg_no"];
		}
	return $veh_no;
}
function get_month($id){
	$arr[1]="January";
	$arr[2]="February";
	$arr[3]="March";
	$arr[4]="April";
	$arr[5]="May";
	$arr[6]="June";
	$arr[7]="July";
	$arr[8]="August";
	$arr[9]="September";
	$arr[10]="October";
	$arr[11]="November";
	$arr[12]="December";
	return $arr[$id];
}

?>