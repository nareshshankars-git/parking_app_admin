<?php
class db_helper extends db_query{
	//Login starts
	function get_loggedIn_user($user_name,$password){
		$data= $this->select_query("users","id,name,user_name,created_datetime,status,password","user_type=2 and user_name=? and password=?",[$user_name,$password]);
		if(count($data)>0)
			return $data[0];
		else
			return 0;
	}
	function get_current_staff_user(){
		$data=$this->select_query("login_history a, users b","b.id,b.name,b.user_name","b.id=a.user_id and logout_datetime is null",[],"ORDER BY `id` DESC");
		if(count($data)>0)
			return $data[0]["name"]." (".$data[0]["user_name"].")";
		else
			return "";
	}
	function get_dashboard_data(){
		$data= $this->select_query("check_in_out_log","count(id) as vehicle_in,(SELECT count(id) FROM `customer`where 1) as customer,(SELECT count(id) FROM `monthly_pass` where month=".date('m') .") as monthly_pass_active,(SELECT count(id) FROM `monthly_pass` where 1) as monthly_pass,(SELECT count(id) FROM `vehicle`where 1) as vehicle,(SELECT balance FROM `money_in_out` ORDER BY `id` DESC limit 0,1) as balance,(SELECT sum(amount) FROM `money_in_out` where trans_from=2 and created_datetime>-'".date("Y-m-d")." 00:00:00' and created_datetime<='".date("Y-m-d")." 23:59:59') as expense,(SELECT sum(amount) FROM `money_in_out` where trans_from=3 and created_datetime>-'".date("Y-m-d")." 00:00:00' and created_datetime<='".date("Y-m-d")." 23:59:59') as cash_recv","check_out is null",[],"");
		if(count($data)>0)
			return $data[0];
		else
			return 0;
	}
	//users starts
	function users_list($where="1",$where_array,$sort_by="") {
		$data= $this->select_query("users","id,name,user_name,created_datetime,status,password","user_type=1 and ".$where,$where_array,$sort_by);
		return $data;
	}
	function validate_username($user_name) {
		$data= $this->select_query("users","id,user_name","user_name=?",array($user_name));
		if(count($data)>0)
			return 0;
		else
			return 1;
	}
	function add_user($insr_array){
		return $this->insert_query("users",$insr_array);
	}
	function update_user($upd_arr,$id){
		$where="id =:id";
		return $this->update_query("users",$upd_arr,$where,array('id'=>$id));
	}
	function get_user($id) {
		$data= $this->select_query("users","id,name,user_name,status,password","id=?",array($id));
		if(count($data)>0)
			return $data[0];
		else
			return 0;
	}
	function get_staffs() {
		return $this->select_query("users","id,name,user_name","status=1 and user_type=1",array());
	}
	function get_all_staff() {
		return $this->select_query("users","id,name,user_name","1",array());
	}
	function login_history($where="1",$where_array,$sort_by="") {
		$data= $this->select_query("login_history a,users b","a.id,a.login_datetime,a.logout_datetime,a.amount,b.name,b.user_name","a.	user_id=b.id and b.user_type=1 and ".$where,$where_array,$sort_by);
		return $data;
	}
	function login_unlock_history($where="1",$where_array,$sort_by="") {
		$data= $this->select_query("lock_screen a,users b","a.id,a.img_path,b.name,b.user_name,a.created_datetime","a.user_id=b.id and b.user_type=1 and ".$where,$where_array,$sort_by);
		return $data;
	}
	function user_login_history($id) {
		return $this->select_query("login_history","login_datetime,logout_datetime","user_id=?",[$id],"order by created_datetime desc");
	}
	//users ends
	//slot starts
	function get_slots_list($where="1",$where_array,$sort_by=""){
		$where.=" and a.slot_type=b.id";
		$data= $this->select_query("slot_master a,slot_type b","a.id,a.name,a.amount,a.hours,a.color,a.status,b.name as type_name,a.grace_period",$where." and a.slot_type=b.id ",$where_array,$sort_by);
		return $data;
	}
	function get_slot_by_id($id){
		$data= $this->select_query("slot_master a","a.id,a.name,a.amount,a.hours,a.color,a.slot_type,a.status,a.grace_period","id=?",[$id]);
		return $data[0];
	}
	function get_slots(){
		$data= $this->select_query("slot_master","id,name,amount,hours,color,slot_type","1",[],"order by name asc");
		return $data;
	}
	function get_mont_slots(){
		$data= $this->select_query("slot_master","id,name,amount,hours,color,slot_type","slot_type=2",[],"order by name asc");
		return $data;
	}
	function get_slot_type(){
		return $this->select_query("slot_type","id,name","1",[]);
	}
	function add_slot($insr_array){
		return $this->insert_query("slot_master",$insr_array);
	}
	function update_slot($upd_arr,$id){
		$where="id =:id";
		return $this->update_query("slot_master",$upd_arr,$where,array('id'=>$id));
	}
	//slot ends
	function update_setting($upd_arr,$id){
		$where="id =:id";
		return $this->update_query("settings",$upd_arr,$where,array('id'=>$id));
	}
	function get_general_setting(){
		$data= $this->select_query("settings","*",1,array());
		$arr=array();
		foreach($data as $va=>$key){
			$arr[$key["name"]]=$key["value"];
		}
		return $arr;
	}
	//state starts
	function get_state_list($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("reg_state","id,name,del_status",$where,$where_array,$sort_by);
		return $data;
	}
	function get_state_by_id($id){
		$data= $this->select_query("reg_state","id,name,del_status","id=?",[$id]);
		return $data[0];
	}
	function get_state(){
		$data= $this->select_query("reg_state","id,name,del_status","del_status=0",[],"order by name asc");
		return $data;
	}
	function add_state($insr_array){
		return $this->insert_query("reg_state",$insr_array);
	}
	function update_state($upd_arr,$id){
		$where="id =:id";
		return $this->update_query("reg_state",$upd_arr,$where,array('id'=>$id));
	}
	//state ends
	//model starts
	function get_model_list($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("make_model","id,name,del_status",$where,$where_array,$sort_by);
		return $data;
	}
	function get_model_by_id($id){
		$data= $this->select_query("make_model","id,name,del_status","id=?",[$id]);
		return $data[0];
	}
	function get_model(){
		$data= $this->select_query("make_model","id,name,del_status","del_status=0",[],"order by name asc");
		return $data;
	}
	function add_model($insr_array){
		return $this->insert_query("make_model",$insr_array);
	}
	function update_model($upd_arr,$id){
		$where="id =:id";
		return $this->update_query("make_model",$upd_arr,$where,array('id'=>$id));
	}
	//model ends	
	//transc starts
	function update_check_in_out_log($upd_arr,$id){
		$where="id =:id";
		return $this->update_query("check_in_out_log",$upd_arr,$where,array('id'=>$id));
	}
	function get_trans_type(){
		return $this->select_query("transaction_type","id,name",1,[]);
	}
	function get_trans_from(){
		return $this->select_query("transaction_from","id,name",1,[]);
	}
	function add_transaction($insr_array){
		return $this->insert_query("money_in_out",$insr_array);
	}
	function update_transaction($upd_arr,$trans_for_id,$trans_from){
		$where="trans_from =:trans_from and trans_for_id=:trans_for_id";
		return $this->update_query("money_in_out",$upd_arr,$where,array('trans_for_id'=>$trans_for_id,'trans_from'=>$trans_from));
	}
	//transc ends
	//coh starts
	function pass_book_list($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("money_in_out a,transaction_type b,transaction_from c,users e ","a.id,a.trans_from,a.trans_for_id,a.amount,a.balance,a.created_datetime,e.name,e.user_name,c.name as from_name,b.name as t_type,a.trans_type","a.trans_type=b.id and a.trans_from=c.id and a.created_by=e.id and ".$where,$where_array,$sort_by);
		return $data;
	}
	function cash_on_hand_list($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("money_in_out","id,amount,created_datetime",$where,$where_array,$sort_by);
		return $data;
	}
	function get_coh_by_id($id){
		$data= $this->select_query("cash_on_hand","id,user_id,amount,created_datetime","id=?",[$id]);
		return $data[0];
	}
	function add_coh($insr_array){
		return $this->insert_query("cash_on_hand",$insr_array);
	}
	function add_audit($insr_array){
		return $this->insert_query("audit",$insr_array);
	}
	function add_audit_details($insr_array){
		return $this->insert_query("audit_details",$insr_array);
	}
	function update_coh($upd_arr,$id){
		$where="id =:id";
		return $this->update_query("cash_on_hand",$upd_arr,$where,array('id'=>$id));
	}
	//coh ends
	//other starts
	function get_audit_list($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("audit","id,name,from_date,to_date,created_datetime",$where,$where_array,$sort_by);
		return $data;
	}
	function get_audit_detail($id){
		$data= $this->select_query("customer a,reg_state c,make_model d,slot_master e,vehicle b,check_in_out_log f,audit_details g","f.id,a.mobile_number,a.name,b.alpha,b.city,b.reg_no,c.name as state,d.name as model,e.name as slot_name,e.color,f.token_no,f.check_in,f.check_out,f.check_out_transaction,f.check_in_transaction,f.slot_count,g.org_amount,g.amount,g.notes","a.id=f.customer_id and c.id=b.state_id and d.id=b.make_model_id and e.id=f.slot_id and b.id=f.vehicle_id and g.trans_id=f.id and g.audit_id=?",[$id]);
		return $data;
	}
	function get_otp_list($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("customer a,reg_state c,make_model d,slot_master e,vehicle b,check_in_out_log f,check_out_otp g","f.id,a.mobile_number,a.name,b.alpha,b.city,b.reg_no,c.name as state,d.name as model,e.name as slot_name,e.color,f.token_no,f.check_in,f.check_out,f.check_out_transaction,f.check_in_transaction,f.amount,f.slot_count,g.otp,g.created_datetime,g.status","a.id=f.customer_id and c.id=b.state_id and d.id=b.make_model_id and e.id=f.slot_id and b.id=f.vehicle_id and  g.c_log_id=f.id and ".$where,$where_array,$sort_by);
		return $data;
	}
	function get_customer_list($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("customer","id,name,address,mobile_number",$where,$where_array,$sort_by);
		return $data;
	}
	function get_vehicle_by_id($id){
		$data= $this->select_query("vehicle","id,make_model_id,state_id,city,alpha,reg_no,slot_id","id=?",[$id]);
		return $data[0];
	}
	function update_vehicle($upd_arr,$id){
		$where="id =:id";
		return $this->update_query("vehicle",$upd_arr,$where,array('id'=>$id));
	}
	function get_vehicle_list($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("customer a,reg_state c,make_model d,slot_master e,vehicle b","b.id,a.mobile_number,a.name,b.alpha,b.city,b.reg_no,c.name as state,d.name as model,e.name as slot_name","a.id=b.customer_id and c.id=b.state_id and d.id=b.make_model_id and e.id=b.slot_id and ".$where,$where_array,$sort_by);
		return $data;
	}
	function get_expense_list($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("users a,expense b,expense_history c","b.id,b.notes,b.amount,b.created_datetime,a.name,a.user_name,count(c.id) as edit_history","a.id=b.created_by and b.id=c.expense_id and ".$where." group by c.expense_id ",$where_array,$sort_by);
		return $data;
	}
	function get_expense_detail($id){
		$data= $this->select_query("users a,expense b","b.id,b.notes,b.amount,b.created_datetime,a.name,a.user_name","a.id=b.created_by and b.id=?",[$id]);
		return $data;
	}
	function expense_history($id){
		$data= $this->select_query("expense_history","notes,amount,updated_datetime","expense_id=?",[$id],"order by updated_datetime desc");
		return $data;
	}	
	function get_cust_veh($id){
		$data= $this->select_query("reg_state c,make_model d,slot_master e,vehicle b","b.id,b.alpha,b.city,b.reg_no,c.name as state,d.name as model,e.name as slot_name","c.id=b.state_id and d.id=b.make_model_id and e.id=b.slot_id and b.customer_id=?",[$id]);
		return $data;
	}
	function get_monthly_pass($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("customer a,reg_state c,make_model d,slot_master e,vehicle b,monthly_pass f","f.id,a.mobile_number,a.name,b.alpha,b.city,b.reg_no,c.name as state,d.name as model,e.name as slot_name,f.month,f.updated_datetime,f.status","a.id=f.customer_id and c.id=b.state_id and d.id=b.make_model_id and e.id=f.slot_id and b.id=f.vehicle_id and ".$where,$where_array,$sort_by);
		return $data;
	}
	function get_montly_pass_details($id){
		$data= $this->select_query("customer a,reg_state c,make_model d,slot_master e,vehicle b,monthly_pass f,monthly_pass_renewal g","f.id,a.mobile_number,a.name,b.alpha,b.city,b.reg_no,c.name as state,d.name as model,e.name as slot_name,g.month,g.amount,g.created_datetime","a.id=f.customer_id and c.id=b.state_id and d.id=b.make_model_id and e.id=f.slot_id and b.id=f.vehicle_id and g.pass_id=f.id and g.id=?",[$id]);
		return $data;
	}	
	function get_mnt_pass_by_id($id){
		$data= $this->select_query("monthly_pass","id,month,slot_id,status","id=?",[$id]);
		return $data[0];
	}
	function update_mntly_pass($upd_arr,$id){
		$where="id =:id";
		return $this->update_query("monthly_pass",$upd_arr,$where,array('id'=>$id));
	}	
	function get_renewal_hstry($id){
		$data= $this->select_query("slot_master a,monthly_pass_renewal b, users c","a.id,a.name as slot_name,b.month,b.created_datetime,b.amount,c.name,c.user_name","c.id=b.created_by and a.id=b.slot_id and b.pass_id=?",[$id]);
		return $data;
	}
	function get_transaction_list($where="1",$where_array,$sort_by=""){
		$data= $this->select_query("customer a,reg_state c,make_model d,slot_master e,vehicle b,check_in_out_log f","f.id,a.mobile_number,a.name,b.alpha,b.city,b.reg_no,c.name as state,d.name as model,e.name as slot_name,e.color,f.token_no,f.check_in,f.check_out,f.check_out_transaction,f.check_in_transaction,f.slot_count,f.amount","a.id=f.customer_id and c.id=b.state_id and d.id=b.make_model_id and e.id=f.slot_id and b.id=f.vehicle_id and ".$where,$where_array,$sort_by);
		return $data;
	}
	function get_trans_details($id){
		$data= $this->select_query("customer a,reg_state c,make_model d,slot_master e,vehicle b,check_in_out_log f","f.id,a.mobile_number,a.name,b.alpha,b.city,b.reg_no,c.name as state,d.name as model,e.name as slot_name,e.color,f.token_no,f.check_in,f.check_out,f.check_out_transaction,f.check_in_transaction,f.slot_count,f.amount","a.id=f.customer_id and c.id=b.state_id and d.id=b.make_model_id and e.id=f.slot_id and b.id=f.vehicle_id and f.id=?",[$id]);
		return $data;
	}
	function get_check_in_list($where,$where_array,$sort_by=""){
		$data= $this->select_query("customer a,reg_state c,make_model d,slot_master e,vehicle b,check_in_out_log f","f.id,a.mobile_number,a.name,b.alpha,b.city,b.reg_no,c.name as state,d.name as model,e.name as slot_name,e.color,f.token_no,f.check_in,f.check_out,f.check_out_transaction,f.check_in_transaction,f.slot_count,f.amount,f.notes","a.id=f.customer_id and c.id=b.state_id and d.id=b.make_model_id and e.id=f.slot_id and b.id=f.vehicle_id and f.check_out is null and ".$where,$where_array,$sort_by);
		return $data;
	}
	function cust_trans_history($id){
		$data= $this->select_query("reg_state c,make_model d,slot_master e,vehicle b,check_in_out_log f","f.id,b.alpha,b.city,b.reg_no,c.name as state,d.name as model,e.name as slot_name,f.token_no,f.check_in,f.check_out,f.slot_count,f.amount","f.customer_id=? and c.id=b.state_id and d.id=b.make_model_id and e.id=f.slot_id and b.id=f.vehicle_id",[$id],"order by f.check_in desc");
		return $data;
	}
	function veh_trans_history($id){
		$data= $this->select_query("slot_master a,check_in_out_log f","f.id,a.name as slot_name,f.token_no,f.check_in,f.check_out,f.slot_count,f.amount","a.id=f.slot_id and f.vehicle_id=? ",[$id],"order by check_in desc");
		return $data;
	}
	//other ends
	
	

} ?>
