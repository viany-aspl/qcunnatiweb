<?php
class ControllerFarmerrequestCardprint extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle("Card Print Form");

		$this->load->model('farmerrequest/farmerrequest');
		
		$this->cardPrintForm();
	}

         public function printdata($gid,$uid,$assoc)
        {                         
                
                   $data=array();
                           $this->load->model('unit/unit');
                  $this->load->model('farmerrequest/farmerrequest');
                 	$log=new Log("Card-Print-".date('Y-m-d').".log"); 
	$log->write('printdata called' ); 
                  $StatusId='6';
                  $StatusName='CARD PRINTED';
                
                  			$this->request->post['CARD_STATUS']=$StatusId;
				   $this->request->post['CARD_STATUS_DESC']= $StatusName;
				  $this->request->post['CARD_SERIAL_NUMBER']= "0";
				  $this->request->post['CARD_GROWER_ID']=  $gid;
				  $this->request->post['CARD_UNIT']=$uid;//$this->user->getUnitId();
				  $this->request->post['CARD_QR_SRTING']="0";
				  $this->request->post['USER']=$this->user->getId();

				   $log->write( $this->request->post ); 
				  
				  $log->write('just before getting the data for unit' ); 
				  $unitdata= $this->model_unit_unit->getUnitByID($uid);
				  $log->write('just after getting the data for unit' ); 
				  $log->write($unitdata);
				  if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->load->model('pos/'.$company);
						$datares= $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
						$log->write("Response from DSCl");
						$log->write($datares);						
						}
							

			
			if($datares=="1"){
			 $log->write($retval);
			 $log->write($gid);
			 $retval = $this->model_farmerrequest_farmerrequest->updatePrintStatus($gid,$StatusId,$StatusName,$assoc);
            
                  		$log->write($retval);
	        
				 return $datares;
				 }
                  
                  
        }
		
		
		public function carddata(){
		
		$mcrypt=new MCrypt();
		$this->load->model('farmerrequest/farmerrequest');
		//echo "hello";
		$results = $this->model_farmerrequest_farmerrequest->lostCarddetail();
		
		//print_r($results);
		
		//exit;
		foreach($results as $result){
		$id=$result['SID'];
		//print_r($id);
		$data= $mcrypt->decrypt(strtolower($result['QR_SRTING']));
		$updatelostcard = $this->model_farmerrequest_farmerrequest->UpdateLost($id,$data);
		}
		}
		
		
		
		
		

        protected function cardPrintForm() {
		              error_reporting(0);
		
		if (isset($this->request->get['filter_growerid'])) {
		$filter_growerid=$this->request->get['filter_growerid'];
		}
		else
		{
			$filter_growerid='';
		}
if (isset($this->request->get['filter_villageid'])) {
		$filter_villageid=$this->request->get['filter_villageid'];
		}
		else
		{
			$filter_villageid='';
		}
		if (isset($this->request->get['filter_zone'])) {
		$filter_zone=$this->request->get['filter_zone'];
		}
		else
		{
			$filter_zone='';
		}
		if (isset($this->request->get['filter_circle'])) {
		$filter_circle=$this->request->get['filter_circle'];
		}
		else
		{
			$filter_circle='';
		}
		if (isset($this->request->get['filter_unit'])) {
 $data['filter_unit']=$filter_unit1=$this->request->get['filter_unit'];
 }
 else
 {
 $filter_unit1='';
 }
 if (isset($this->request->get['filter_company'])) {
 $data['filter_company']=$filter_company=$this->request->get['filter_company'];
 $this->load->model('farmerrequest/farmerrequest');
 $cid=$this->request->get['filter_company'];
  $uid=$this->request->get['filter_unit'];
 $data['units2']= $this->model_farmerrequest_farmerrequest->getunitbycompany($cid);
$data['village2']= $this->model_farmerrequest_farmerrequest->getvillagebyunit($uid);
 }
 else
 {
 $filter_company='';
 }
		if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
 
} else {
$filter_date_start = '';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = '';
}


		$url = '';
		if (isset($this->request->get['filter_growerid'])) {
			$url .= '&filter_growerid=' . $this->request->get['filter_growerid'];
		}
if (isset($this->request->get['filter_villageid'])) {
			$url .= '&filter_villageid=' . $this->request->get['filter_villageid'];
		}
		if (isset($this->request->get['filter_zone'])) {
			$url .= '&filter_zone=' . $this->request->get['filter_zone'];
		}
		if (isset($this->request->get['filter_circle'])) {
			$url .= '&filter_circle=' . $this->request->get['filter_circle'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . $this->request->get['filter_company'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['filter_date_start'])) {
$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
}

if (isset($this->request->get['filter_date_end'])) {
$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
}
		
		if (isset($this->request->get['page'])) {
			$page=$this->request->get['page'];
		}
		else
		{
			$page=1;
			$_SESSION[session_id()]='';
			$_SESSION['all_selected']='';
		}
		$data['breadcrumbs'] = array();

		

		$data['breadcrumbs'][] = array(
			'text' => 'Card Print Form',
			'href' => $this->url->link('farmerrequest/cardprint', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
		$log=new Log("Card-Print-".date('Y-m-d').".log");
		
		/*$this->load->model('unit/unit');
		//echo "rohit"; exit;
	    $unitdata= $this->model_unit_unit->getUnitByID($this->user->getUnitId());
				 //echo "rohit"; exit;
				 $log->write($unitdata);
				$filter_unit= $unitdata['unit_id'];
				*/
		$data['orders'] = array();
		
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin'),
			'filter_growerid' => $filter_growerid,
'filter_villageid' => $filter_villageid,
			'filter_unit' => $filter_unit1,
			'filter_company' => $filter_company,
			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end
		);
		//print_r($filter_data);
		if($filter_unit1!="" )
		{
		$results = $this->model_farmerrequest_farmerrequest->getprintdtl($filter_data);
	   $order_total=$revieTotal = $this->model_farmerrequest_farmerrequest->getprintToatal($filter_data);
	  // echo $order_total; 
	   }
		foreach ($results as $result) {
		if(in_array($result['GROWER_ID'],$_SESSION[session_id()]))
 {
 $selected='true';
 }
 else
 {
 $selected='false'; 
 } 
		if($result['COMPANY_ID']=='4')
		{
		$cardimg='view/image/dalmia.png';
		}
		else if($result['COMPANY_ID']=='2')
		{
		$cardimg='view/image/BCML.gif';
		}
		else if($result['COMPANY_ID']=='1')
		{
		$cardimg='view/image/DSCL.png';
		}
		else
		{
		$cardimg='view/image/logo.png';
		}
			$data['orders'][] = array(
                'GROWER_ID' => $result['GROWER_ID'],
				'GROWER_NAME' => $result['GROWER_NAME'],
				'UNIT_ID'     => $result['UNIT_NAME'],
				'UNIT'     => $result['UNIT_ID'],
				'COMPANY_NAME' => $result['company_name'],
				'VILLAGE_NAME' => $result['VILLAGE_NAME'],
                'FTH_HUS_NAME'     => $result['FATHER_NAME'],
                'CARD_SERIAL_NUMBER'     => $result['CARD_SERIAL_NUMBER'],
                'MOB'     => $result['MOB'],
				'VILLAGE'=>$result['VILLAGE_NAME'],
                'CARD_QR_IMG'=>'../system/upload/qrimages/'.$result['CARD_SERIAL_NUMBER'].'.png',
				'CNAME'=>$cardimg,
				'selected'  =>$selected
			);
		}
		
		//$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = 'Please Select Grower Id';
		

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('farmerrequest/cardprint', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['pagination'] = $pagination->render();
        $data['companys'] = $this->model_farmerrequest_farmerrequest->getComapny();
        $data['units'] = $this->model_farmerrequest_farmerrequest->getUnit();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('farmerrequest/cardprintform.tpl', $data));
	}

        public function update_print_status() {
$_SESSION["growerid"]='';
if (isset($this->request->get['filter_growerid'])) {
$filter_growerid = $this->request->get['filter_growerid'];
} else {
$filter_growerid = 0;
}
if (isset($this->request->get['filter_asoc'])) {
$filter_asoc = $this->request->get['filter_asoc'];
} else {
$filter_asoc = 0;
}
if (isset($this->request->get['filter_unit'])) {
$data['filter_unit']=$filter_unit=$this->request->get['filter_unit'];
}
else
{
$filter_unit='';
}
if (isset($this->request->get['filter_company'])) {
$data['filter_company']=$filter_company=$this->request->get['filter_company'];
}
else
{
$filter_company='';
}

if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = '';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = '' ;
}
if (isset($this->request->get['filter_zone'])) {
$filter_zone=$this->request->get['filter_zone'];
}
else
{
$filter_zone='';
}
if (isset($this->request->get['filter_circle'])) {
$filter_circle=$this->request->get['filter_circle'];
}
else
{
$filter_circle='';
}


$this->load->model('farmerrequest/farmerrequest');
$unitname = $this->model_farmerrequest_farmerrequest->getunitname($data['filter_unit']);
$companyname = $this->model_farmerrequest_farmerrequest->getcompanyname($data['filter_company']);
$data['orders'] = array();

$current_array=$_SESSION[session_id()];
$filter_data = array(

'filter_growerid' => $filter_growerid,
'filter_unit' => $filter_unit,
'filter_company' => $filter_company,
'filter_asoc' => $filter_asoc,
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'selected_growers'=>$current_array

);

$data['orders'] = array();

$results = $this->model_farmerrequest_farmerrequest->getprintdtl($filter_data);
$_SESSION["results"]=$results;


foreach($results as $data)
{
$retval=$this->printdata($data['GROWER_ID'],$filter_unit,$filter_asoc);

//print_r($retval);
//print_r($results);

//exit;
}
$log=new Log("Card-Printall_status-".date('Y-m-d').".log"); 
 if($retval==1)
 {

$log->write($retval);
$this->session->data['success'] ="Card Printed Sucessfully !";
$token = $this->session->data['token'];
//header('Location: '.$_SERVER["HTTP_REFERER"]);
echo '1';//exit;
}
 else 
 {
 $this->session->data['error'] ="Unable to connect to server  please try again !";
 echo '0';//exit;
 }

}

public function download_excel() {
session_start();
$results = $_SESSION["results"];
//$results = $this->model_farmerrequest_farmerrequest->getprintdtl($filter_data);
//print_r($results);exit; 

include_once '../system/library/PHPExcel.php';

include_once '../system/library/PHPExcel/IOFactory.php';
PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());

$objPHPExcel = new PHPExcel();

$objPHPExcel->createSheet();

$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

$objPHPExcel->setActiveSheetIndex(0);

// Field names in the first row
$fields = array( 
'GROWER_NAME',
'FATHER_NAME',
'GROWER_ID', 
'VILLAGE_NAME',
'UNIT_NAME',
'CARD SERIAL NUMBER',

'QR STRING' 
);

$col = 0;
foreach ($fields as $field)
{
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
$col++;
} 
$row = 2; 


foreach($results as $data)
{ //print_r($data); 


$StatusId='6';
$StatusName='CARD PRINTED'; 
//$retval = $this->model_farmerrequest_farmerrequest->updatePrintStatus($data['GROWER_ID'],$StatusId,$StatusName);

$col = 0; 
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['GROWER_NAME']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['FATHER_NAME']);
 $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit($data['GROWER_ID'], PHPExcel_Cell_DataType::TYPE_STRING); 
//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['GROWER_ID']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['VILLAGE_NAME']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['UNIT_NAME']);
//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['CARD_SERIAL_NUMBER']);
$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->setValueExplicit($data['CARD_SERIAL_NUMBER'], PHPExcel_Cell_DataType::TYPE_STRING); 
//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['QR_SRTING']);
$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $row)->setValueExplicit($data['QR_SRTING'], PHPExcel_Cell_DataType::TYPE_STRING); 
$row++;

}
//exit;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// Sending headers to force the user to download the file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=DSCL_'.$results[0]['UNIT_NAME']."_Z_C_".date('d-m-Y').'.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output'); 

}


        /*public function download_excel() {
       
	if (isset($this->request->get['filter_growerid'])) {
			$filter_growerid = $this->request->get['filter_growerid'];
		} else {
			$filter_growerid = 0;
		}
		if (isset($this->request->get['filter_asoc'])) {
			$filter_asoc = $this->request->get['filter_asoc'];
		} else {
			$filter_asoc = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
 $data['filter_unit']=$filter_unit=$this->request->get['filter_unit'];
 }
 else
 {
 $filter_unit='';
 }
 if (isset($this->request->get['filter_company'])) {
 $data['filter_company']=$filter_company=$this->request->get['filter_company'];
 }
 else
 {
 $filter_company='';
 }
		
				if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = '';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = ''  ;
}
if (isset($this->request->get['filter_zone'])) {
		$filter_zone=$this->request->get['filter_zone'];
		}
		else
		{
			$filter_zone='';
		}
		if (isset($this->request->get['filter_circle'])) {
		$filter_circle=$this->request->get['filter_circle'];
		}
		else
		{
			$filter_circle='';
		}

               
                $this->load->model('farmerrequest/farmerrequest');
				 $unitname = $this->model_farmerrequest_farmerrequest->getunitname($data['filter_unit']);
				 $companyname = $this->model_farmerrequest_farmerrequest->getcompanyname($data['filter_company']);
				
        $data['orders'] = array();

        $current_array=$_SESSION[session_id()];
        $filter_data = array(
           
            'filter_growerid'         => $filter_growerid,
			'filter_unit'         => $filter_unit,
			'filter_company'         => $filter_company,
			'filter_asoc'         => $filter_asoc,
			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end,
			'selected_growers'=>$current_array
            
        );

        $data['orders'] = array();

        $results = $this->model_farmerrequest_farmerrequest->getprintdtl($filter_data);
            //print_r($results);exit; 
	
    include_once '../system/library/PHPExcel.php';
   
    include_once '../system/library/PHPExcel/IOFactory.php';
    
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(   
		'GROWER_NAME',
		'FATHER_NAME',
        'GROWER_ID',  
		'VILLAGE_NAME',
		'UNIT_NAME',
		'CARD SERIAL NUMBER',
		
        'QR STRING'        
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }     
    $row = 2;  
	foreach($results as $data)
    {
	
	$retval=$this->printdata($data['GROWER_ID'],$filter_unit,$filter_asoc);
	}
	$log=new Log("Card-Print-".date('Y-m-d').".log"); 
	if($retval==1)
	{
	
	$log->write($retval);
	$this->session->data['success'] ="Card Printed Sucessfully !";
	
    foreach($results as $data)
    { //print_r($data); 
	
	
	$StatusId='6';
	$StatusName='CARD PRINTED'; 
	//$retval = $this->model_farmerrequest_farmerrequest->updatePrintStatus($data['GROWER_ID'],$StatusId,$StatusName);
	

        $col = 0;      
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['GROWER_NAME']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['FATHER_NAME']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['GROWER_ID']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['VILLAGE_NAME']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['UNIT_NAME']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['CARD_SERIAL_NUMBER']);

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['QR_SRTING']);
        $row++;
	
	}
	//exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$companyname.'_'.$unitname."_Z_C_".date('d-m-Y').'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output');   
	}
	else	
	{
	$this->session->data['error'] ="Opps Something is wrong ! please try again !";
	
	}
	 $this->response->setOutput($this->load->view('farmerrequest/error.tpl'));   
    }
	*/
	
	
	
	public function checknotzero()
{
if (isset($this->request->get['filter_growerid'])) {
			$filter_growerid = $this->request->get['filter_growerid'];
		} else {
			$filter_growerid = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
 $data['filter_unit']=$filter_unit=$this->request->get['filter_unit'];
 }
 else
 {
 $filter_unit='';
 }
 if (isset($this->request->get['filter_company'])) {
 $data['filter_company']=$filter_company=$this->request->get['filter_company'];
 }
 else
 {
 $filter_company='';
 }
		
				if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = '';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = ''  ;
}
if (isset($this->request->get['filter_zone'])) {
		$filter_zone=$this->request->get['filter_zone'];
		}
		else
		{
			$filter_zone='';
		}
		if (isset($this->request->get['filter_circle'])) {
		$filter_circle=$this->request->get['filter_circle'];
		}
		else
		{
			$filter_circle='';
		}

               
                $this->load->model('farmerrequest/farmerrequest');
				 $unitname = $this->model_farmerrequest_farmerrequest->getunitname($data['filter_unit']);
				
        $data['orders'] = array();
		$filter_growerid=$_SESSION[session_id()];
        $current_array=$_SESSION[session_id()];
		//echo count($_SESSION[session_id()]);
		//print_r($_SESSION[session_id()]);
		//exit;
		if(!empty($_SESSION[session_id()]))
		{
        $filter_data = array(
           
            'selected_growers'         => $filter_growerid,
			'filter_unit'         => $filter_unit,
			'filter_company'         => $filter_company,
			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end,
			'selected_growers'=>$current_array
            
        );
		//print_r($filter_data);
        $data['orders'] = array();

        $results = $this->model_farmerrequest_farmerrequest->getprintdtl($filter_data);
        foreach($results as $data)
    { //print_r($data);
	if($data['CARD_SERIAL_NUMBER']=='0')
	{
	
	
	echo "1";exit;
	}
	if($data['GROWER_ID']=='0')
	{
	echo "2";exit;
    }
	}
	}
	else
	{
	echo "3";exit;
	}
}
	
	
public function getUnitbyCompany(){


 $this->load->model('farmerrequest/farmerrequest');

$cid=$this->request->post['companyid'];
 if (isset($this->request->post['companyid']))

{

$dunit= $this->model_farmerrequest_farmerrequest->getunitbycompany($cid);

$dpunit= count($dunit);
 echo $dpunit;
 echo ' <option value=""> SELECT UNIT</option> ';
 for($n=0;$n<$dpunit;$n++)
 {
 echo '<option value="'.$dunit[$n]['unit_id'].'">'.$dunit[$n]['unit_name'].'</option>';
 }

}

}



public function add_orders_to_array()
 { //$_SESSION[session_id()]='';exit;
 $action=$this->request->get['action'];
 $order_id=$this->request->get['order_id'];
 if($action=='add')
 {
 //echo session_id();
 $current_array=$_SESSION[session_id()];
 //print_r($current_array);

 if(empty($current_array))
 { //echo 'array is empty';
 $current_array=array($order_id);
 $_SESSION[session_id()]=$current_array;
 }
 else
 { //echo 'count is greater then 0';
 array_push($current_array,$order_id); 
 $_SESSION[session_id()]=$current_array;
 }
 //print_r($current_array);
 }
 if($action=='remove')
 {
 //print_r($_SESSION[session_id()]);
 $current_array=$_SESSION[session_id()];
 foreach($current_array as $key=>$val)
 {
 if($val == $order_id)
 {
 unset($current_array[$key]);
 }
 }

 //array_diff($current_array, $order_id);
 $_SESSION[session_id()]=$current_array;
 //print_r($current_array);
 }
 }
public function select_all_bills()
 {
 $action=$this->request->get['action'];
 if($action=='add')
 {
 if (isset($this->request->get['filter_growerid'])) {
 $filter_growerid=$this->request->get['filter_growerid'];
 }
 else
 {
 $filter_growerid='';
 }
 if (isset($this->request->get['filter_unit'])) {
 $data['filter_unit']=$filter_unit1=$this->request->get['filter_unit'];
 }
 else
 {
 $filter_unit1='';
 }
 if (isset($this->request->get['filter_company'])) {
 $filter_company=$this->request->get['filter_company'];

 }
 else
 {
 $filter_company='';
 }
 if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = '';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = '';
}
 $filter_data = array(

 'filter_growerid' => $filter_growerid,
 'filter_unit' => $filter_unit1,
 'filter_company' => $filter_company,
 'filter_date_start' => $filter_date_start,
 'filter_date_end' => $filter_date_end
 );
 //print_r($filter_data);
 if($filter_unit1!="" )
 { $this->load->model('farmerrequest/farmerrequest');
 $results = $this->model_farmerrequest_farmerrequest->getprintdtl($filter_data);

 }

 $current_array=array();
 foreach ($results as $result) 
 {
 //print_r($result);
 array_push($current_array,$result['GROWER_ID']); 
 $_SESSION[session_id()]=$current_array;
 }
 $_SESSION['all_selected']='true';
 }
 if($action=='remove')
 {
 $_SESSION['all_selected']='';
 $_SESSION[session_id()]='';
 }
 }
 public function download_pdf() {
 
                $data['grower_id']=$this->request->get['grower_id'];
				$data['card_number']=$this->request->get['card_number'];
				$data['farmer_name']=$this->request->get['farmer_name'];
				$data['father_name']=$this->request->get['father_name'];
				$data['village']=$this->request->get['village'];
				$data['unit']=$this->request->get['unit'];
				$data['CARD_QR_IMG']=$this->request->get['CARD_QR_IMG'];
				$data['cname']=$this->request->get['cname'];
	
                require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
                
                $html = $this->load->view('farmerrequest/cardprint_pdf.tpl',$data);
                $this->response->setOutput($this->load->view('farmerrequest/cardprint_pdf.tpl', $data));
                   
                $base_url = HTTP_CATALOG;
                
                //$mpdf = new mPDF($mode, $format, $font_size, $font, $margin_left, $margin_right, $margin_top, $margin_bottom, $margin_header, $margin_footer, $orientation);
                $mpdf = new mPDF('c', 'A4', '', '', '5', '5', '25', '25', '5', '1', '');
                $header = '<div class="header" style="">
               
<div class="logo" style="width: 100%;" >
<img src="../image/letterhead_text.png" style="height: 40px; width: 121px;" />
<img src="../image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />

                         </div>
<img src="../image/letterhead_topline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 

</div>';
    //$header='';
                $mpdf->SetHTMLHeader($header, 'O', false);
                    
                $footer = '<div class="footer">
                        
                        <img src="../image/letterhead_bottomline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 
                        <div class="address"><img src="../image/letterhead_footertext.png" style="height: 10px; margin-left: -40px;width: 120% !important;" /> </div>'
                        . '</div>';

            
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
             $mpdf->setAutoTopMargin = 'stretch';
               $mpdf->setAutoBottomMargin = 'stretch';
                $mpdf->WriteHTML($html);

                $filename='Card_Print.pdf';
                //$mpdf->Output(DIR_UPLOAD.$filename,'F');
                $mpdf->Output($filename,'D');
                
 
        }
public function getVillagebyUnit(){


$this->load->model('farmerrequest/farmerrequest');

$cid=$this->request->post['unitid'];
if (isset($this->request->post['unitid']))

{

$dvillage= $this->model_farmerrequest_farmerrequest->getvillagebyunit($cid);

$dpvillage= count($dvillage);
echo $dpvillage;
echo ' <option value=""> SELECT VILLAGE</option> ';
for($n=0;$n<$dpvillage;$n++)
{
echo '<option value="'.$dvillage[$n]['VILLAGE_ID'].'">'.$dvillage[$n]['VILLAGE_NAME'].'</option>';
}

}

}
public function generateqr()
    {
		$log=new Log("CardgenerateQr-".date('Y-m-d').".log"); 
		$cid=$this->request->get['CardSerialNo']; 
		
		$this->load->model('farmerrequest/farmerrequest');
		$cadata= $this->model_farmerrequest_farmerrequest->getqrstring($cid);
		$mcrypt=new MCrypt();
        $card_data="AC='01'"
                        . "UN='".($cadata["UNIT_ID"])."' "
                        . "CI='".($cadata["COMPANY_ID"])."'"
                        . "GC='".($mcrypt->encrypt($cadata["GROWER_ID"]))."' "
                        . "CSN='".($mcrypt->encrypt($cadata["CARD_SERIAL_NUMBER"]))."";
		
			
		$this->load->library('phpqrcode/qrlib');
                    $folder_name='qrimages';
                    $dir=DIR_UPLOAD.$folder_name;
                    if ( !file_exists($dir) ) {
                    $oldmask = umask(0);  // helpful when used in linux server  
                    mkdir ($dir, 0744);
                    }
	
                    $card_qr_img=$dir.'/'.$cid.'.png';
					$log->write($card_qr_img);

                    define('IMAGE_WIDTH',220);
                    define('IMAGE_HEIGHT',220);
                    QRcode::png(($card_data), $card_qr_img);
                    $card_image=$cid.'.png';
					$log->write($card_qr_img);
					echo '../system/upload/qrimages/'.$cid.'.png';
					
    }
	public function deleteqr()
	{
		$cid=$this->request->get['CardSerialNo']; 
		unlink($cid);
	}

}
