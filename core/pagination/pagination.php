<?php function paginate($item_per_page, $total_records,  $page_url="")
{
	if(isset($_GET["page"])){ //Get page number from $_GET["page"]
		$current_page = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
		if(!is_numeric($current_page)){die('Invalid page number!');} //incase of invalid page number
	}else{
		$current_page = 1; //if there's no page number, set it to 1
	}
	$total_pages = ceil($total_records/$item_per_page);
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<div class="dataTables_paginate paging_simple_numbers"><ul class="pagination">';
        
        $right_links    = $current_page + 3; 
        $previous       = $current_page - 1; //previous link 
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link
        
        if($current_page > 1){
            $previous_link = ($previous<=0)?1:$previous;
            $pagination .= '<li class="paginate_button page-item"><a class="page-link" href="?page=1'.$page_url.'" title="First">&laquo;</a></li>'; //first link
            $pagination .= '<li class="paginate_button page-item "><a class="page-link" href="?page='.$previous_link.$page_url.'" title="Previous">&lt;</a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li class="paginate_button page-item "><a class="page-link" href="?page='.$i.$page_url.'">'.$i.'</a></li>';
                    }
                }   
            $first_link = false; //set first link to false
        }
        
        if($first_link){ //if current active page is first link
            $pagination .= '<li class="paginate_button page-item active"><a class="page-link">'.$current_page.'</a></li>'; //first
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<li class="paginate_button page-item active"><a class="page-link">'.$current_page.'</a></li>';//last
        }else{ //regular current link
            $pagination .= '<li class="paginate_button page-item active"><a class="page-link">'.$current_page.'</a></li>';
        }
                
        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li><a class="page-link" href="?page='.$i.$page_url.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){ 
                //$next_link = ($i > $total_pages)? $total_pages : $i;
                $pagination .= '<li><a class="page-link" href="?page='.$next.$page_url.'" >&gt;</a></li>'; //next link
                $pagination .= '<li class="last"><a class="page-link" href="?page='.$total_pages.$page_url.'" title="Last">&raquo;</a></li>'; //last link
        }
        
        $pagination .= '</ul></div>'; 
    }
    return $pagination; //return pagination links
}
function get_sort_class($id){
$class_name="sorting";
if(isset($_GET["sort_id"]) && $_GET["sort_id"]==$id){
	$class_name="sorting_";
	$class_name.=($_GET["sortby"]=="asc")?"desc":"asc";
}
//$class_name="".($_GET["sort_by"]=="asc")?"desc":"asc";
return $class_name;
}
function get_sort_url($id){
$sort_url="?sort_id=$id&sortby=asc";
	if(isset($_GET["sort_id"]) && $_GET["sort_id"]==$id){
		$sort_url="?sort_id=$id&sortby=";
		$sort_url.=($_GET["sortby"]=="asc")?"desc":"asc";
	}
return $sort_url;
}
function get_page_no(){
	if(isset($_GET["page"])){ //Get page number from $_GET["page"]
		$page_number = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
	if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
	}else{
		$page_number = 1; //if there's no page number, set it to 1
	}
	return $page_number;
}
function get_page_url(){
	$url_val="";
	if(isset($_GET["sort_id"]))
		$url_val="&sort_id=".$_GET["sort_id"]."&sortby=".$_GET["sortby"];
	return $url_val;
}
function get_sort_by(){
	$sort_by="";
	if(isset($_GET["sort_id"])){
		$sort_by="ORDER BY ".$_GET["sort_id"]." ".$_GET["sortby"];
	}
	return $sort_by;
}

 
/*$db_username        = 'root'; //database username
$db_password        = ''; //dataabse password
$db_name            = 'gaadiz'; //database name
$db_host            = '192.168.20.150'; //hostname or IP
$item_per_page      = 5; //item to display per page
$page_url           = "";

$mysqli_conn = new mysqli($db_host, $db_username, $db_password,$db_name); //connect to MySql
if ($mysqli_conn->connect_error) { //Output any connection error
    die('Error : ('. $mysqli_conn->connect_errno .') '. $mysqli_conn->connect_error);
}

if(isset($_GET["page"])){ //Get page number from $_GET["page"]
    $page_number = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
}else{
    $page_number = 1; //if there's no page number, set it to 1
}


$results = $mysqli_conn->query("SELECT COUNT(*) FROM users"); //get total number of records from database
$get_total_rows = $results->fetch_row(); //hold total records in variable

$total_pages = ceil($get_total_rows[0]/$item_per_page); //break records into pages

################# Display Records per page ############################
$page_position = (($page_number-1) * $item_per_page); //get starting position to fetch the records
//Fetch a group of records using SQL LIMIT clause
$results = $mysqli_conn->query("SELECT * FROM users ORDER BY id ASC LIMIT $page_position, $item_per_page");

//Display records fetched from database.

echo '<ul class="contents">';
while($row = $results->fetch_assoc()) {
    echo '<li>';
    echo  $row["id"]. '. <strong>' .$row["name"].'</strong> &mdash; '.$row["email_id"];
    echo '</li>';
}  
echo '</ul>';
################### End displaying Records #####################

//create pagination 
echo '<div align="center">';
// We call the pagination function here. 
echo paginate($item_per_page, $page_number, $get_total_rows[0], $total_pages, $page_url);
echo '</div>';*/