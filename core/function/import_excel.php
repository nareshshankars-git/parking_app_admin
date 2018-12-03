<?php 
function importData($file){


require_once 'PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load($file);
$worksheet=$objPHPExcel->getSheet(0);
    $highestRow         = $worksheet->getHighestRow(); // e.g. 10
    $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    $nrColumns = ord($highestColumn) - 64;
	$header=array();
	for ($col = 0; $col < $highestColumnIndex; ++ $col) {
		$cell = $worksheet->getCellByColumnAndRow($col, 1);
		$header[$col] = $cell->getValue();
	}
	
	$data=array();
	
	
    for ($row = 2; $row <= $highestRow; ++ $row) {
		$data_row=array();
        for ($col = 0; $col < $highestColumnIndex; ++ $col) {
            $cell = $worksheet->getCellByColumnAndRow($col, $row);
            $val = $cell->getValue();
			$data_row[$header[$col]]=$val;
        }
		$data[]=$data_row;
    }
	return $data;
}
function exportdata(){
	require_once 'PHPExcel/IOFactory.php';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// If you want to output e.g. a PDF file, simply do:
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');

$objWriter->save('MyExcel.xslx');
}
