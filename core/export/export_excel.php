<?php
function export_data($data,$file_name){
// The function header by sending raw excel
header("Content-type: application/vnd-ms-excel");
// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=".$file_name.".xls");
// Add data table
echo $data;
}
?>

