<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PHPReadExcel
 *
 * @author Admin
 */
if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/');
	require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}

class PHPReadExcel {
    private $_objPHPExcel;
    public function __construct($inputFileName,$name)
	{
        
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    
                if($ext=="xls")
                {
                    $objReader = new PHPExcel_Reader_Excel5();
                }
                else if($ext=="csv"){
                $objReader = new PHPExcel_Reader_CSV();
                }
                else{
                $objReader = new PHPExcel_Reader_Excel2007();
                }
                
               // $objReader->setReadDataOnly(true);
                $this->_objPHPExcel=$objReader->load($inputFileName);
                                
        }
        
        public function getSheetData() {
            
            $objWorksheet=$this->_objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); 
            $highestColumn = $objWorksheet->getHighestColumn(); 
            //$highestColumnIndex =$this->_objPHPExcel->get //PHPExcel_Cell::columnIndexFromString($highestColumn); 
$finaldata = array();            
for ($row = 1; $row <= $highestRow; ++$row) {  
  /*for ($col = 0; $col <= $highestColumnIndex; ++$col) {
    echo '<td>' . $objWorksheet->getCellByColumnAndRow($col, $row)->getValue() . '</td>' . "\n";
  }*/
      $rowData = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
      //array_push($finaldata,$rowData[0]);
      $finaldata[]=$rowData[0];
}
return $finaldata;
        }
    
}
