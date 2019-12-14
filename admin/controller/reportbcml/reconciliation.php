<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');  
error_reporting(0); 
ini_set('max_execution_time', 30000);  //3000 seconds = 50 minutes
ini_set("pcre.backtrack_limit", "1000000");
ini_set('memory_limit','1024M');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ControllerReportbcmlReconciliation extends Controller {
	
	public function getorderdata()
	{
		$this->load->language('report/reconciliation');
		$this->document->setTitle('Get Order data from BCML');
		if (isset($this->request->get['filter_id'])) 
		{
			$filter_id = $this->request->get['filter_id'];
		} 
		else 
		{
			$filter_id = '';
		}
		if (isset($this->request->get['filter_unit'])) 
		{
			$filter_unit = $this->request->get['filter_unit'];
		} 
		else 
		{
			$filter_unit = '';
		}
		if (isset($this->request->get['filter_store'])) 
		{
			$filter_store = $this->request->get['filter_store'];
		} 
		else 
		{
			$filter_store = '';
		}	
		$url = '';

		if (isset($this->request->get['filter_id'])) {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}

		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Get Order data from BCML',
			'href' => $this->url->link('reportbcml/reconciliation/getorderdata', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		$data['text_no_results'] = $this->language->get('text_no_results');
		$this->load->model('report/reconciliation');
        $this->load->model('setting/store');
		$this->load->model('unit/unit');
		$this->load->model('pos/bcml');
		$data['units']=$this->model_unit_unit->getunit(array('filter_company'=>'2'));
		$data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
		if((!empty($filter_id)) && (!empty($filter_store)) && (!empty($filter_unit)))
		{
					$datatosend=array();
					$datatosend=array('unitid'=>$filter_unit,'invoiceno'=>$filter_id,'storeid'=>$filter_store);	
					$databybcml=$this->model_pos_bcml->GetIndentByInvoiceNo('GetIndentByInvoiceNo', $datatosend); 
					if(!empty($databybcml))
					{
					if(!empty($databybcml[0]['IndentNo']))
					{
					$orderdata['OrderID']=$databybcml[0]['InvoiceNo'];
					$orderdata['IndentNo']=$databybcml[0]['IndentNo'];
					$orderdata['DeliveryDate']=$this->decryptRJ256($databybcml[0]['DeliveryDate']);
					$orderdata['InvoiceValue']=$this->decryptRJ256($databybcml[0]['InvoiceValue']);
					$orderdata['CashValue']=$this->decryptRJ256($databybcml[0]['CashValue']);
					$orderdata['TaggedValue']=$this->decryptRJ256($databybcml[0]['TaggedValue']);
					$orderdata['DeliveryMode']=$this->decryptRJ256($databybcml[0]['DeliveryMode']);
					$orderdata['FMCode']=$this->decryptRJ256($databybcml[0]['FMCode']);
					$orderdata['VerifiedThrough']=$this->decryptRJ256($databybcml[0]['VerifiedThrough']);
					$orderdata['DeliveryReceipt']=$this->decryptRJ256($databybcml[0]['DeliveryReceipt']);
					$data['orderdata'][]=$orderdata;
					}
					else
					{
						$data['text_no_results'] =$databybcml ;
					}
					}
		}
		//print_r($databybcml);
		
		$data['heading_title'] = 'Get Order data from BCML';
		
		$data['text_list'] = $this->language->get('text_list');
		
		
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];
		
		$data['filter_id'] = $filter_id;
        $data['filter_store'] = $filter_store;
		$data['filter_unit'] = $filter_unit;
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/getorderdatafrombcml.tpl', $data));
		
	}
	
	public function index() 
	{
		$this->load->language('report/reconciliation');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('reportbcml/reconciliation', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/reconciliation');
                		 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_company' => '2',
			'filter_unit'	     => $filter_unit,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		if(!empty($filter_store))
		{
		$t1=$this->model_report_reconciliation->getTotalOrdersCompanywise($filter_data);
		$order_total = $t1["total"];
		$total_tagged_amount_all=$t1["total_bcml_tagged_amount"];
		$total_bcml_subsidy_amount=$t1["total_bcml_subsidy_amount"];
		$total_tagged_amount=0;
		$results = $this->model_report_reconciliation->getOrdersCompanywise($filter_data);
		}
		foreach ($results as $result) {
			//print_r($result);
                        		
               $total_subsidy_amount=$total_subsidy_amount+$result['subsidy'];
                        $grower_info = $result['o_payment_firstname'];//$result['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                        $farmer_info=explode('-', $grower_info);
      
                $grower_id='';//@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[0]));
                $father_name=ucwords(strtolower(@$farmer_info[1]));
	if(empty($grower_id))
	{
	$grower_id=$result['shipping_firstname'];
	}
	if(empty($farmer_name))
	{
	$farmer_name=$result['o_payment_address_1'];
	}
	
	  $inv_no=$result['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
                if(empty($result['company']))
	 {
		$unit_name=$result['unit_name'];
	 }
	else
	{
		$unit_name=$result['company'];
	}
	$o_payment_address_1=$result['o_payment_address_1'];
					$o_payment_firstname=$result['o_payment_firstname'];
					
					if($result['bcml_tagged']!='0.00')
					{
						$taggedvalue=$result['bcml_tagged'];
						$total_tagged_amount=$total_tagged_amount+$result['bcml_tagged'];
					}
					else
					{
						$taggedvalue=$result['tagged'];
						$total_tagged_amount=$total_tagged_amount+$result['tagged'];
					}
			//if(strlen($inv_no)<10)
			{
			$data['orders'][] = array(
				
				'date'       => date($this->language->get('date_format_short'), strtotime($result['date'])),
		  'order_id'   => $inv_no ,
			'inv_no'   => $result['order_id'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => $result['total'],
                                'tagged'     => $taggedvalue,
								'subsidy'      => $result['subsidy'],
                                'grower_id'  => $grower_id,
                                'farmer_name'=> $farmer_name,
                                'unit'       => $unit_name,
								'o_payment_address_1'=>$o_payment_address_1,
								'o_payment_firstname'=>$o_payment_firstname,
								'father_name'=>$father_name
				
			);
			}
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_tax'] = $this->language->get('column_tax');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                
		
		$url = '';
                if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}


		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/reconciliation', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
                $data['filter_store'] = $filter_store;
		$data['filter_unit'] = $filter_unit;
		$data['total_tagged_amount'] =$total_tagged_amount;
		$data['total_tagged_amount_All'] =$total_tagged_amount_all;
		$data['total_subsidy_amount'] =$total_subsidy_amount;
		$data['total_subsidy_amount_All'] =$total_subsidy_amount_All;

		$this->load->model('unit/unit');
		$data['units']=$this->model_unit_unit->getunit(array('filter_company'=>'2'));
		
		$data['user'] = $this->model_report_reconciliation->getstoregroup($this->user->getId());
		$data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
	
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}
		
		//print_r($data['units']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/reconciliation.tpl', $data));
	}
	
	
function cmp($a, $b)
{
    if ($a["order_id"] == $b["order_id"]) {
        return 0;
    }
    return ($a["order_id"] < $b["order_id"]) ? -1 : 1;
}

        
         public function download_excel() {
            
               if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
                           if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
		}

		

		$this->load->model('report/reconciliation');
                

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_unit'	     => $filter_unit,
                     'filter_company' => '2',
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);

		
		$results = $this->model_report_reconciliation->getOrdersCompanywise($filter_data);

    

    // Field names in the first row
    $fields = array(
        'Unit',
        'Store Name',
        'Store ID',
        'Order ID',
        'Inv no.',
        'Grower ID',
        'Grower Name',
        'Village Name',
        'Date',
       
        'Tagged',
        'Cash',
		'Subsidy'
    );
	$fileIO = fopen('php://memory', 'w+');
	fputcsv($fileIO, $fields,',');
	
	foreach($results as $data)
    { 
        
        $col = 0;
       $grower_info = $data['o_payment_firstname'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                    $farmer_info=explode('-', $grower_info);
      
                    $grower_id='';//@$farmer_info[0];
                    $farmer_name=ucwords(strtolower(@$farmer_info[0]));
                    $father_name=ucwords(strtolower(@$farmer_info[1]));
                    if(empty($grower_id))
                    {
                        $grower_id=$data['shipping_firstname'];
                    }
                    if(empty($farmer_name))
                    {
                        $farmer_name=$data['o_payment_address_1'];
                    }
					
					$o_payment_address_1=$data['o_payment_address_1'];
					$o_payment_firstname=$data['o_payment_firstname'];
                    $inv_no=$data['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
					if(empty($inv_no))
                    {
                           $inv_no=$data['o_requisition_id'];
                    }
	
                    if(empty($data['company']))
                    {
                           $unit_name=$data['unit_name'];
                    }
							else
							{
                            $unit_name=$data['company'];
							}
							
					if($data['bcml_tagged']!='0.00')
					{
						$taggedvalue=$data['bcml_tagged'];
					}
					else
					{
						$taggedvalue=$data['tagged'];
					}
							//if(strlen($inv_no)<10)
							if(!is_numeric($farmer_name))
							{
        
		$fdata=array(
                            $unit_name,
                            $data['store_name'],
                            $data['store_id'],
                            $inv_no,
							$data['order_id'],
							$grower_id,
							$farmer_name,
							$o_payment_address_1,
                            date('Y-m-d',strtotime($data['date'])),
                            number_format((float)$taggedvalue, 2, '.', ''),
                            number_format((float)$data['cash'], 2, '.', ''),
							number_format((float)$data['subsidy'], 2, '.', '')
                            );
			 fputcsv($fileIO,  $fdata,",");
                                                        
		} 
    }
	
	fseek($fileIO, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment;filename="reconciliation_report_'.date('dMy').'.csv"');
    header('Cache-Control: max-age=0');
    fpassthru($fileIO);  
    fclose($fileIO);
	/*
	include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
    
    $row = 2;
    
    foreach($results as $data)
    { 
        
        $col = 0;
       $grower_info = $data['o_payment_firstname'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                    $farmer_info=explode('-', $grower_info);
      
                    $grower_id='';//@$farmer_info[0];
                    $farmer_name=ucwords(strtolower(@$farmer_info[0]));
                    $father_name=ucwords(strtolower(@$farmer_info[1]));
                    if(empty($grower_id))
                    {
                        $grower_id=$data['shipping_firstname'];
                    }
                    if(empty($farmer_name))
                    {
                        $farmer_name=$data['o_payment_address_1'];
                    }
					
					$o_payment_address_1=$data['o_payment_address_1'];
					$o_payment_firstname=$data['o_payment_firstname'];
                    $inv_no=$data['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
		if(empty($inv_no))
                    {
                           $inv_no=$data['o_requisition_id'];
                    }
	
                    if(empty($data['company']))
                    {
                           $unit_name=$data['unit_name'];
                    }
							else
							{
                            $unit_name=$data['company'];
							}
							
					if($data['bcml_tagged']!='0.00')
					{
						$taggedvalue=$data['bcml_tagged'];
					}
					else
					{
						$taggedvalue=$data['tagged'];
					}
							//if(strlen($inv_no)<10)
							if(!is_numeric($farmer_name))
							{
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $unit_name );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row,  $inv_no);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $grower_id);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $farmer_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $o_payment_address_1);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, date('Y-m-d',strtotime($data['date'])));
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format((float)$data['total'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format((float)$taggedvalue, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, number_format((float)$data['cash'], 2, '.', ''));
           
        $row++;
		
                                                        } 
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reconciliation_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
    */
    }

//download item

public function download_item_excel() {
            
               if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
		}

		

		$this->load->model('report/reconciliation');
                

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
                        'filter_unit'	     => $filter_unit,
                        'filter_company' => '2',
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);

		//$order_total = $this->model_report_reconciliation->getTotalOrdersCompanywise($filter_data);

		$results = $this->model_report_reconciliation->getOrdersCompanywise($filter_data);
    //exit;
    

    // Field names in the first row
    $fields = array(
        'Unit',
        'Store Name',
        'Store ID',
        'Order ID',
	'Invoice_No',
        'Grower ID',
        'Name',
        'Date',
	'Product Name',
	'Quantity',
	'Price',
	'Tax',
	'Total (Cash+Tagged+Subsidy)'
    );
	$fileIO = fopen('php://memory', 'w+');
	fputcsv($fileIO, $fields,',');
    foreach($results as $data)
    { 
        if(empty($data['company']))
		{
			$data['company']=$data['unit_name'];
		}
        $col = 0;
   
		$grower_info = $data['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                $farmer_info=explode('-', $grower_info);
      
                $grower_id=@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[1]));
                $father_name=ucwords(strtolower(@$farmer_info[2]));
	  $inv_nos=$data['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));

		//get product details row		                
		$orderinfos=$this->model_report_reconciliation->getOrder_detail($data['order_id']);
		foreach($orderinfos as $orderinfo)
		{
			$fdata=array(
                            @$data['company'],
                            $data['store_name'],
                            $data['store_id'],
                            $inv_nos,
							$data['order_id'],
							$grower_id,
							$farmer_name,
                            date('Y-m-d',strtotime($data['date'])),
                            $orderinfo['name'],
                            $orderinfo['quantity'],
                            $orderinfo['price'],
                            $orderinfo['tax'],
                            number_format((float)($orderinfo['total']+($orderinfo['tax']*$orderinfo['quantity'])),2,'.','')
							
                            );
			 fputcsv($fileIO,  $fdata,",");
        
		}

    }
	
	fseek($fileIO, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment;filename="reconciliation_report_'.date('dMy').'.csv"');
    header('Cache-Control: max-age=0');
    fpassthru($fileIO);  
    fclose($fileIO);
	/*
	include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
    
    $row = 2;
    
    foreach($results as $data)
    { 
        if(empty($data['company']))
		{
			$data['company']=$data['unit_name'];
		}
        $col = 0;
   
 $grower_info = $data['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                $farmer_info=explode('-', $grower_info);
      
                $grower_id=@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[1]));
                $father_name=ucwords(strtolower(@$farmer_info[2]));
	  $inv_nos=$data['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));

	//get product details row		                
	$orderinfos=$this->model_report_reconciliation->getOrder_detail($data['order_id']);
	foreach($orderinfos as $orderinfo){
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, @$data['company'] );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $inv_nos);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $grower_id);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $farmer_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, date('Y-m-d',strtotime($data['date'])));
        
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $orderinfo['name']);           
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $orderinfo['quantity']);           
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $orderinfo['price']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $orderinfo['tax']);           
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row,number_format((float)($orderinfo['total']+($orderinfo['tax']*$orderinfo['quantity'])),2,'.',''));           
          

        $row++;
	}

    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reconciliation_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	*/
    
    }

///////////create bill for check start here//////////////
public function create_bill_check() { 
            $this->load->language('report/reconciliation');

		

		if (isset($this->request->post['filter_date_check'])) {
			$filter_date = $this->request->post['filter_date_check'];
		} else {
			$filter_date= date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		

		if (isset($this->request->post['filter_store_2_check'])) {
			$filter_store = $this->request->post['filter_store_2_check'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->post['filter_unit_2_check'])) {
			$filter_unit = $this->request->post['filter_unit_2_check'];
		} else {
			$filter_unit = 0;
		}
		$this->load->model('report/reconciliation');
        $this->load->model('setting/store');
		$this->load->model('pos/bcml');
					
		$data['orders'] = array();

		$filter_data = array(
            'filter_store'	     => $filter_store,
			'filter_unit'	     => $filter_unit,
			'filter_date'	     => $filter_date
		);

       
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
                           $data['store']="";
                           $data['unit']="";
                
                    $data["file_aspl"]='test';
					$filename='check_by_bcml.pdf';
					 	
					$file_a_array=$this->model_report_reconciliation->get_file_data_tagged($filter_data);
					
					//$log->write($file_a_array);
					if(!empty($file_a_array))
					{
					$data["file_aspl"]=$file_a_array[0]["sid"];
					$filename=$file_a_array[0]["file_name"];
					}
                
				
				$filter_data2 = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date,
			'filter_date_end'	     => $filter_date,
                        'filter_company' => '2',
			'filter_unit'	     => $filter_unit,
			'start'                  => 0,
			'limit'                  => 10000
		);		
	   $t1=$this->model_report_reconciliation->getTotalOrdersCompanywiseAllStatus($filter_data2);
	///print_r($t1);
		$order_total = $t1["total"];
		$data['total_tagged_amount_all']=$t1["total_tagged_amount"];
				$results = $this->model_report_reconciliation->getOrdersAllStatus($filter_data);
					//print_r($results);

				$data['product_results'] = $this->model_report_reconciliation->getOrdersProductsSummaryAllStatus($filter_data);
                $total_amount=0;
//exit;
				foreach ($results as $result) 
				{
                      
					$datatosend=array();
					$datatosend=array('unitid'=>$filter_unit,'invoiceno'=>$result['order_id'],'storeid'=>$filter_store);	
					$databybcml=$this->model_pos_bcml->GetIndentByInvoiceNo('GetIndentByInvoiceNo', $datatosend);  
					$bcml_invoice_value=$this->decryptRJ256($databybcml[0]['TaggedValue']);
					
					  
					$grower_info = $result['o_payment_firstname'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                    $farmer_info=explode('-', $grower_info);
      
                    $grower_id='';//@$farmer_info[0];
                    $farmer_name=ucwords(strtolower(@$farmer_info[0]));
                    $father_name=ucwords(strtolower(@$farmer_info[1]));
                    if(empty($grower_id))
                    {
                        $grower_id=$result['shipping_firstname'];
                    }
                    if(empty($farmer_name))
                    {
                        $farmer_name=$result['o_payment_address_1'];
                    }
					
					$o_payment_address_1=$result['o_payment_address_1'];
					$o_payment_firstname=$result['o_payment_firstname'];
                    $inv_no=$result['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
					if(empty($inv_no))
					{
						$inv_no=$result['req_id2'];
					}
					
                    if(empty($result['company']))
                    {
                           $unit_name=$result['unit_name'];
                    }
							else
							{
                            $unit_name=$result['company'];
							}
							
					if($result['bcml_tagged']!='0.00')
					{
						$taggedvalue=$result['bcml_tagged'];
					}
					else
					{
						$taggedvalue=$result['tagged'];
					}
							$data['orders'][] = array(
								'date'       => date($this->language->get('date_format_short'), strtotime($result['date'])),
                                'order_id'   => $inv_no ,
                                'inv_no'   => $result['order_id'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => number_format((float)$result['total'], 2, '.', ''),//$result['total'],
								'subsidy'      => number_format((float)$result['subsidy'], 2, '.', ''),//$result['total'],
                                'tagged'     => number_format((float)$taggedvalue, 2, '.', ''),//$result['tagged'],
                                'grower_id'  => $grower_id,
                                'farmer_name'=> $farmer_name,
                                'unit'       => $unit_name,
                                'selected'  =>$selected,
								'bcml_invoice_value'=>$bcml_invoice_value,
								'order_status_id'   => $result['order_status_id'] ,
								'o_payment_address_1'=>$o_payment_address_1,
								'o_payment_firstname'=>$o_payment_firstname,
								'father_name'=>$father_name,
								'cash'      => number_format((float)$result['cash'], 2, '.', '')
				
							);
                        $total_amount=$total_amount+$result['tagged'];
                        $data['store']=$result['store_name'];
                        $data['unit']=$result['company']; 
				}
				if($filter_store==0)
                {
                    
                  $data['store']='';
                  $data['unit']='';  
                } 
	 

	$data['start_date'] = date($this->language->get('date_format_short'), strtotime($filter_date));
                $data['end_date']  = date($this->language->get('date_format_short'), strtotime($filter_date));
		
                require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
                
				$html = $this->load->view('reportbcml/reconciliation_pdf_check.tpl',$data);
                
				//exit;
                $base_url = HTTP_CATALOG;
                
                
                $mpdf = new mPDF('c', 'A4', '', '', '5', '5', '25', '25', '5', '1', '');
                $header = '<div class="header" style="">
                   
<div class="logo" style="width: 100%;" >
<img src="../image/letterhead_text.png" style="height: 40px; width: 121px;" />
<img src="../image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />

                         </div>
<img src="../image/letterhead_topline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 

</div>';
   
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


                //$filename='tagged_order_'.$filter_date.'_'.$filter_store.'_'.$filter_unit.'.pdf';    
	  //$this->model_report_reconciliation->add_file_data_tagged($filter_data,$filename); 
               // $mpdf->Output(DIR_UPLOAD.'tagged_pdf/'.$filename,'F');  
	   $this->load->model('user/user'); 
	  $logged_user = $this->user->getId();
	  //$update_total=$this->model_report_reconciliation->update_file_data_tagged($filter_data,$data["file_aspl"],$total_amount,$logged_user); 
                $mpdf->Output($filename,'D');
	echo $html;
	  header('location: '.$_SERVER['HTTP_REFERER']);
	  echo "File created successfully"; 
           
        }

/////////////create bill check end here//////////////////

///////////create bill start here//////////////
	public function create_bill() 
	{ 
            $this->load->language('report/reconciliation');

		//echo 'here';
		$log=new Log("create_bill_bcml-".date('Y-m-d').".log"); 
		if (isset($this->request->post['filter_date'])) {
			$filter_date = $this->request->post['filter_date'];
		} else {
			$filter_date= date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		

		if (isset($this->request->post['filter_store_2'])) {
			$filter_store = $this->request->post['filter_store_2'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->post['filter_unit_2'])) {
			$filter_unit = $this->request->post['filter_unit_2'];
		} else {
			$filter_unit = 0;
		}
		$this->load->model('report/reconciliation');
        $this->load->model('setting/store');
		$this->load->model('pos/bcml');
						
		$data['orders'] = array();

		$filter_data = array(
            'filter_store'	     => $filter_store,
			'filter_unit'	     => $filter_unit,
			'filter_date'	     => $filter_date,
			  'filter_company' => '2',
			'start'				 =>0,
			'limit'				 =>3000
		);

		
		$log->write($filter_data);
        $file_a_array=$this->model_report_reconciliation->get_file_data_tagged($filter_data);
		$log->write($file_a_array);
        $file_in_database=""; 
		$file_server= "";
		if(count($file_a_array)>0)
		{
			$file_in_database="yes"; 
			$path = "../system/upload/tagged_pdf";
                                          $files = scandir($path);
                                          $file_server="";
                                          foreach ($files as &$value) 
                                          {
                                            
                                            if(trim($file_a_array[0]["file_name"])==trim($value))
                                           { 
                                               $file_server= "yes";
		
                                             }
                 
                                          }
			
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$isatbcmldone="";
			    if(($file_in_database=="yes") && ($file_server=="yes"))
			    {
					$log->write('file_in_database==yes && file_server==yes');
					
					$file_url = DIR_UPLOAD.'tagged_pdf/'.$file_a_array[0]["file_name"];
                                                                      header('Content-Type: application/pdf');
                                                                      header("Content-Transfer-Encoding: Binary");
                                                                      header("Content-disposition: attachment; filename=".$file_a_array[0]["file_name"]);
                                                                      readfile($file_url);
				              header('location: '.$_SERVER['HTTP_REFERER']);
				             exit;
			}
			else ///if file is already generated for same filter////////  
			{
                           $data['store']="";
                           $data['unit']="";
                
				 
                 		if(($file_a_array[0]["file_name"]!="") && ($file_server!="yes"))//////means data in database but file is not available at server////////////
                		 {
                    			$filename=$file_a_array[0]["file_name"];
					$data["file_aspl"]=$file_a_array[0]["sid"];
					$log->write('means data in database but file is not available at server');
					
                 		} 
				else
				{ 
					$log->write('in else');
					$filename='tagged_order_'.$filter_date.'_'.$filter_store.'_'.$filter_unit.'.pdf';  
					$data["file_aspl"]=$this->model_report_reconciliation->add_file_data_tagged($filter_data,$filename);//'1065';//
					
				}
					$log->write($data["file_aspl"]);
					$resultsb = $this->model_report_reconciliation->getOrdersBcml($filter_data);
					
					$this->load->model('user/user');
					$this->user->getId();
					$user_info = $this->model_user_user->getUser($this->user->getId());
					$user_firstname = $user_info['firstname'];
					$user_lastname = $user_info['lastname'];
					$log->write($data["file_aspl"]);
					$arrays = array_chunk($resultsb, 10);
					//print_r($arrays);
					foreach ($arrays as $array_num => $array) 
					{ 
								$data['DebitNoteDetail']=array();
								
								foreach ($array as $item_num => $resultb) 
								{	
									
								if($resultb['bcml_tagged']!='0.00')
								{
									$taggedvalueb=$resultb['bcml_tagged'];
								}
								else
								{
									$taggedvalueb=$resultb['tagged'];
								}
								if(!empty($resultb['requisition_id']))
								{
									$inv_nob=$resultb['requisition_id'];
								}
								else
								{
									$inv_nob=$resultb['o_requisition_id'];
								}
								$grower_info = $resultb['o_payment_firstname'];
                    							$farmer_info=explode('-', $grower_info);
                    							$farmer_name=ucwords(strtolower(@$farmer_info[0]));
                   
                   							 if(empty($farmer_name))
                    							{
                        								$farmer_name=$resultb['o_payment_address_1'];
                    							}
								if(!is_numeric($farmer_name))
								{
								$data['DebitNoteDetail'][] = array(
								'debitnoteno'=> $data["file_aspl"],
								'indentno'   => $inv_nob ,
                                				'invoiceno'   => $resultb['order_id'],
                                				'invoicedate'       => date('Ymd', strtotime($resultb['date'])),
                                				'username'       => $user_firstname.' '.$user_lastname,
								'taggedvalue'=>number_format((float)$taggedvalueb, 2, '.', '')
                                				);
								}//////end of is numeric if
								}
								$DebitNoteDetail=json_encode($data['DebitNoteDetail']);
								$dbdata=array();
								$dbdata['DebitNoteDetail']=$DebitNoteDetail;
								$dbdata['unitid']=$filter_unit;
								$log->write("sending bill to bcml");
								$resultbcbcl=$this->model_pos_bcml->CreateDebitNote('CreateDebitNote', $dbdata);
								$log->write($resultbcbcl);
								if (strpos($resultbcbcl, 'The duplicate key value is ('.$data["file_aspl"]) !== false) 
								{
									$resultbcbcl=1;
									$log->write($resultbcbcl);
								}
								if (strpos($resultbcbcl, 'Cannot insert duplicate key ('.$data["file_aspl"]) !== false) 
								{
									$resultbcbcl=1;
									$log->write($resultbcbcl);
								}
								if( (strpos($resultbcbcl, 'The conversion of the nvarchar value') !== false)  && (strpos($resultbcbcl, 'overflowed an int column') !== false))
								{
									//$resultbcbcl=1;
									$log->write($resultbcbcl);
								}
								if($resultbcbcl==1)
								{
									$this->model_report_reconciliation->update_bcml_upload($data['DebitNoteDetail']);
									$isatbcmldone='yes';									
								}
								else
								{
									$isatbcmldone='no';
								}
								//exit;
					}
					
					//echo $isatbcmldone;
					$log->write($isatbcmldone);
					if($isatbcmldone=='no')
					{ 
								//$update_total=$this->model_report_reconciliation->delete_file_data_tagged($data["file_aspl"]);
								$log->write('Oops! Some error at Sugar Cane server '.$resultbcbcl);
								$this->session->data['error']='Oops! Some error at Sugar Cane server : '.$resultbcbcl; 
								header('Location: '.$_SERVER['HTTP_REFERER']);
								exit;
					}
					
						
					
		$filter_data2 = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date,
			'filter_date_end'	     => $filter_date,
                        'filter_company' => '2',
			'filter_unit'	     => $filter_unit,
			'start'                  => 0,
			'limit'                  => 3000
		);		
	   $t1=$this->model_report_reconciliation->getTotalOrdersCompanywise($filter_data2);
	///print_r($t1);
		$order_total = $t1["total"];
		$data['total_tagged_amount_all']=$t1["total_bcml_tagged_amount"];
				
				$results = $this->model_report_reconciliation->getOrders($filter_data);
				$data['product_results'] = $this->model_report_reconciliation->getOrdersProductsSummaryNewBilling($filter_data); 	
				//print_r($data['product_results']);exit;
                $total_amount=0;
				foreach ($results as $result) 
				{
                      
					$grower_info = $result['o_payment_firstname'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                    $farmer_info=explode('-', $grower_info);
      
                    $grower_id='';//@$farmer_info[0];
                    $farmer_name=ucwords(strtolower(@$farmer_info[0]));
                    $father_name=ucwords(strtolower(@$farmer_info[1]));
                    if(empty($grower_id))
                    {
                        $grower_id=$result['shipping_firstname'];
                    }
                    if(empty($farmer_name))
                    {
                        $farmer_name=$result['o_payment_address_1'];
                    }
					
					$o_payment_address_1=$result['o_payment_address_1'];
					$o_payment_firstname=$result['o_payment_firstname'];
                    $inv_no=$result['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
		if(empty($inv_no))
                    {
                           $inv_no=$result['o_requisition_id'];
                    }
	
                    if(empty($result['company']))
                    {
                           $unit_name=$result['unit_name'];
                    }
							else
							{
                            $unit_name=$result['company'];
							}
							
					if($result['bcml_tagged']!='0.00')
					{
						$taggedvalue=$result['bcml_tagged'];
					}
					else
					{
						$taggedvalue=$result['tagged'];
					}
							//if(strlen($inv_no)<10)
							if(!is_numeric($farmer_name))
							{
							$data['orders'][] = array(
								'date'       => date($this->language->get('date_format_short'), strtotime($result['date'])),
                                'order_id'   => $inv_no ,
                                'inv_no'   => $result['order_id'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => number_format((float)$result['total'], 2, '.', ''),//$result['total'],
								'subsidy'      => number_format((float)$result['subsidy'], 2, '.', ''),//$result['total'],
                                'tagged'     => number_format((float)$taggedvalue, 2, '.', ''),//$result['tagged'],
                                'grower_id'  => $grower_id,
                                'farmer_name'=> $farmer_name,
                                'unit'       => $unit_name,
                                'selected'  =>$selected,
								'o_payment_address_1'=>$o_payment_address_1,
								'o_payment_firstname'=>$o_payment_firstname,
								'father_name'=>$father_name,
								'cash'      => number_format((float)$result['cash'], 2, '.', '')
				
							);
                        $total_amount=$total_amount+$result['tagged'];
                        $data['store']=$result['store_name'];
                        $data['unit']=$result['company'];
							}
							
				}
				
				if($filter_store==0)
                {
                    
                  $data['store']='';
                  $data['unit']='';  
                } 
				$data['start_date'] = date($this->language->get('date_format_short'), strtotime($filter_date));
                $data['end_date']  = date($this->language->get('date_format_short'), strtotime($filter_date));
	
	  
                require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
                
               $html = $this->load->view('reportbcml/reconciliation_pdf.tpl',$data);
           
				//exit;
                //exit;	
			$log->write("Creating pdf");
                $base_url = HTTP_CATALOG;               
                
                $mpdf = new mPDF('c', 'A4', '', '', '5', '5', '25', '25', '5', '1', '');
				$mpdf->simpleTables = true;
				$mpdf->shrink_tables_to_fit=1;
				$mpdf->packTableData = true;	
                $header = '<div class="header" style="">
							<div class="logo" style="width: 100%;" >
							<img src="../image/letterhead_text.png" style="height: 40px; width: 121px;" />
							
							<img src="../image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />
							
                         </div>
						<img src="../image/letterhead_topline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 
						
						</div>';
   
                $mpdf->SetHTMLHeader($header, 'O', false);
                    
                $footer = '<div class="footer">
                        
                        <img src="../image/letterhead_bottomline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 
						
                        <div class="address"><img src="../image/letterhead_footertext.png" style="height: 10px; margin-left: -40px;width: 120% !important;" /> </div>
                       <span style="margin-left:-50px;font-size: 8px;">Letter No. - ASPL-'.$data["file_aspl"].'</span>
					   </div>';

                
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->setAutoBottomMargin = 'stretch'; 
				//echo $filename;
                $mpdf->WriteHTML($html);
 
				$this->load->model('user/user'); 
				$logged_user = $this->user->getId();
				$update_total=$this->model_report_reconciliation->update_file_data_tagged($filter_data,$data["file_aspl"],$total_amount,$logged_user,$filename);

				//echo $filename;
	  
				//$mpdf->Output(DIR_UPLOAD.$filename,'F');
				$mpdf->Output($filename,'D');
				header('location: '.$_SERVER['HTTP_REFERER']);
				echo "File created successfully"; 
           }
        }

/////////////create bill end here//////////////////

////////////////get bill start here/////////////////////////
public function get_bill()
{
                            if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
		}
		$this->load->model('report/reconciliation');
                            $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                                          'filter_store'	     => $filter_store,
			'filter_unit'	     => $filter_unit,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);
                           $file_a_array=$this->model_report_reconciliation->get_file_data_tagged_within($filter_data);
		if(count($file_a_array)>0)
	              {
                              echo "1";
		}
		else
		{
		 echo "0";
		}

}
///////////////get bill end here///////////////
 
public function get_store_unit()
{
$store_id=$this->request->get['store_id'];
if($store_id=="20")
{
 echo "<option value='' selected='selected'>SELECT UNIT</option><option value='03'>HARIYAWAN</option><option value='04' >LONI</option>";
}
else
{
$this->load->model('report/reconciliation');
$get_data=$this->model_report_reconciliation->get_store_unit($store_id);
//print_r($get_data[0]);
echo "<option value='' >SELECT UNIT</option><option value='".$get_data[0]['unit_id']."' selected='selected'>".$get_data[0]["unit_name"]."</option>";
}

}
public function getdebitnotedetail() {
		$this->load->language('report/reconciliation');

		$this->document->setTitle('Get Debit Note Detail');

		if (isset($this->request->get['filter_letter_number'])) {
			$filter_letter_number = $this->request->get['filter_letter_number'];
		} else {
			$filter_letter_number = '';
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = '';
		}
		

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_letter_number'])) {
			$url .= '&filter_letter_number=' . $this->request->get['filter_letter_number'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Get Debit Note Detail',
			'href' => $this->url->link('reportbcml/reconciliation/getdebitnotedetail', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/reconciliation');
		$this->load->model('pos/bcml');
        	$this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
            		'DebitNoteNo'	     => $filter_letter_number,
			'unitid'	     => $filter_unit,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		//$t1=$this->model_report_reconciliation->getTotalOrdersCompanywise($filter_data);
		$order_total = 0;//$t1["total"];
		//$total_tagged_amount_all=$t1["total_tagged_amount"];
		//$total_tagged_amount=0;
			
		if((!empty( $filter_unit)) && (!empty($filter_letter_number)))
		{
			$results = $this->model_pos_bcml->GetDebitNoteDetail('GetDebitNoteDetail',$filter_data);
		
		$TotalTaggedValue=0;
		if(is_array($results))
		{
			foreach ($results as $result) 
			{
                        		
				$data['orders'][] = array(
				
								'InvoiceDate'       => $result['InvoiceDate'],
								
								'DebitNoteNo'   => $result['DebitNoteNo'],
                                'IndentNo' => $result['IndentNo'],
                                'InvoiceNo'   => $result['InvoiceNo'],
                                'TaggedValue'      => $result['TaggedValue'],
								'DebitNoteStatus'=>$result['DebitNoteStatus'],
								'UserName'=>$result['UserName']
				
				);
				$TotalTaggedValue=$TotalTaggedValue+$result['TaggedValue'];
			}
		}
		else
		{
			$data['text_no_results']=$results;
		}
		}
		else
		{
			$data['text_no_results']='Please Provide Letter Number And Unit';
		}
		$data['TotalTaggedValue']=$TotalTaggedValue;
		$data['heading_title'] = 'Get Debit Note Detail';
		
		$data['text_list'] = $this->language->get('text_list');
		
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_tax'] = $this->language->get('column_tax');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
		
		$url = '';
        if (isset($this->request->get['filter_letter_number'])) {
			$url .= '&filter_letter_number=' . $this->request->get['filter_letter_number'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/reconciliation', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_letter_number'] = $filter_letter_number;
		
		$data['filter_unit'] = $filter_unit;
		$data['total_tagged_amount'] =$total_tagged_amount;
		$data['total_tagged_amount_All'] =$total_tagged_amount_all;

		$this->load->model('unit/unit');
		$data['units']=$this->model_unit_unit->getunit(array('filter_company'=>'2'));
		//print_r($data['units']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/reconciliation_get_payment_indent.tpl', $data));
	}
///////////////////////////////////////
public function duplicate() {
		$this->load->language('report/reconciliation');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('reportbcml/reconciliation', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/reconciliation');
                		 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_company' => '2',
			'filter_unit'	     => $filter_unit,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$t1=array();//$this->model_report_reconciliation->getTotalOrdersCompanywise($filter_data);
		$order_total = $t1["total"];
		$total_tagged_amount_all=$t1["total_tagged_amount"];
		$total_tagged_amount=0;
		$results = array();//$this->model_report_reconciliation->getOrdersCompanywise($filter_data);

		foreach ($results as $result) {
                        		$total_tagged_amount=$total_tagged_amount+$result['tagged'];

                        $grower_info = $result['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                        $farmer_info=explode('-', $grower_info);
      
                $grower_id=@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[1]));
                $father_name=ucwords(strtolower(@$farmer_info[2]));
	if(empty($grower_id))
	{
	$grower_id=$result['shipping_firstname'];
	}
	if(empty($farmer_name))
	{
	$farmer_name=$result['o_payment_address_1'];
	}
	  $inv_no=$result['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
                if(empty($result['company']))
	 {
		$unit_name=$result['unit_name'];
	 }
	else
	{
		$unit_name=$result['company'];
	}
			$data['orders'][] = array(
				
				'date'       => date($this->language->get('date_format_short'), strtotime($result['date'])),
		  'order_id'   => $inv_no ,
			'inv_no'   => $result['order_id'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => $result['total'],
                                'tagged'     => $result['tagged'],
                                'grower_id'  => $grower_id,
                                'farmer_name'=> $farmer_name,
                                'unit'       => $unit_name
				
			);
		}
		//$data['orders']=usort($data['orders'], "cmp");
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_tax'] = $this->language->get('column_tax');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
		
		$url = '';
                if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}


		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/reconciliation', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
                $data['filter_store'] = $filter_store;
		$data['filter_unit'] = $filter_unit;
		$data['total_tagged_amount'] =$total_tagged_amount;
		$data['total_tagged_amount_All'] =$total_tagged_amount_all;

		$this->load->model('unit/unit');
		$data['units']=$this->model_unit_unit->getunit(array('filter_company'=>'2'));
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}
		
		//print_r($data['units']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/reconciliation_duplicate.tpl', $data));
	}
	///////////create bill duplicate start here//////////////
public function create_bill_duplicate() { 
            $this->load->language('report/reconciliation'); 
		$log=new Log("create_bill_bcml_duplicate-".date('Y-m-d').".log"); 
		if (isset($this->request->post['filter_date'])) {
			$filter_date = $this->request->post['filter_date'];
		} else {
			$filter_date= date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}
		if (isset($this->request->post['filter_date_end'])) {
			$filter_date_end = $this->request->post['filter_date_end'];
		} else {
			$filter_date_end= date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}
		$filter_number = $this->request->post['filter_number'];

		if (isset($this->request->post['filter_store_2'])) {
			$filter_store = $this->request->post['filter_store_2'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->post['filter_unit_2'])) {
			$filter_unit = $this->request->post['filter_unit_2'];
		} else {
			$filter_unit = 0;
		}
		if (isset($this->request->post['invoice_number'])) {
			$invoice_number = $this->request->post['invoice_number'];
		} else {
			$invoice_number = '';
		}
		$this->load->model('report/reconciliation');
        $this->load->model('setting/store');
		$this->load->model('pos/bcml');
						
		$data['orders'] = array();

		$filter_data = array(
            'filter_store'	     => $filter_store,
			'filter_unit'	     => $filter_unit,
			'filter_date'	     => $filter_date,
			'filter_company'	     => 2,
			'invoice_number'=>$invoice_number
		);

        $log->write($filter_data);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$isatbcmldone="";
			   
                           $data['store']="";
                           $data['unit']="";
					$data["file_aspl"]=$filter_number;
					$filename='tagged_order_'.$filter_date.'_'.$filter_store.'_'.$filter_unit.'_'.$filter_number.'.pdf';  
					///'1296'; //
					//$this->model_report_reconciliation->add_file_data_tagged_duplicate($filter_data,$filename,$filter_number);//'1065';// 
					$log->write($data["file_aspl"]);
					$resultsb = $this->model_report_reconciliation->getOrdersDuplicate($filter_data);
					$this->load->model('user/user');
					$this->user->getId();
					$user_info = $this->model_user_user->getUser($this->user->getId());
					$user_firstname = $user_info['firstname'];
					$user_lastname = $user_info['lastname'];
					$log->write($data["file_aspl"]);
					$arrays = array_chunk($resultsb, 10);
					//print_r($arrays);
					foreach ($arrays as $array_num => $array) 
					{ 
								$data['DebitNoteDetail']=array();
								
								foreach ($array as $item_num => $resultb) 
								{	
									
								if($resultb['bcml_tagged']!='0.00')
								{
									$taggedvalueb=$resultb['bcml_tagged'];
								}
								else
								{
									$taggedvalueb=$resultb['tagged'];
								}
								if(!empty($resultb['requisition_id']))
								{
									$inv_nob=$resultb['requisition_id'];
								}
								else
								{
									$inv_nob=$resultb['o_requisition_id'];
								}
								$grower_info = $resultb['o_payment_firstname'];
                    							$farmer_info=explode('-', $grower_info);
                    							$farmer_name=ucwords(strtolower(@$farmer_info[0]));
                   
                   							 if(empty($farmer_name))
                    							{
                        								$farmer_name=$resultb['o_payment_address_1'];
                    							}
								if(!is_numeric($farmer_name))
								{
								$data['DebitNoteDetail'][] = array(
								'debitnoteno'=> $data["file_aspl"],
								'indentno'   => $inv_nob ,
                                				'invoiceno'   => $resultb['order_id'],
                                				'invoicedate'       => date('Ymd', strtotime($resultb['date'])),
                                				'username'       => $user_firstname.' '.$user_lastname,
								'taggedvalue'=>number_format((float)$taggedvalueb, 2, '.', '')
                                				);
								}//////end of is numeric if
								}
								$DebitNoteDetail=json_encode($data['DebitNoteDetail']);
								$dbdata=array();
								$dbdata['DebitNoteDetail']=$DebitNoteDetail;
								$dbdata['unitid']=$filter_unit;
								$log->write("sending bill to bcml");
								$resultbcbcl=$this->model_pos_bcml->CreateDebitNote('CreateDebitNote', $dbdata);
								$log->write($resultbcbcl);
								if (strpos($resultbcbcl, 'The duplicate key value is ('.$data["file_aspl"]) !== false) 
								{
									$resultbcbcl=1;
									$log->write($resultbcbcl);
								}
								if( (strpos($resultbcbcl, 'The conversion of the nvarchar value') !== false)  && (strpos($resultbcbcl, 'overflowed an int column') !== false))
								{
									//$resultbcbcl=1;
									$log->write($resultbcbcl);
								}
								if($resultbcbcl==1)
								{
									$this->model_report_reconciliation->update_bcml_upload($data['DebitNoteDetail']);
									$isatbcmldone='yes';									
								}
								else
								{
									$isatbcmldone='no';
								}
								//exit;
					}
					
					//echo $isatbcmldone;
					$log->write($isatbcmldone);
					if($isatbcmldone=='no')
					{ 
								//$update_total=$this->model_report_reconciliation->delete_file_data_tagged($data["file_aspl"]);
								$log->write('Oops! Some error at Sugar Cane server '.$resultbcbcl);
								$this->session->data['error']='Oops! Some error at Sugar Cane server : '.$resultbcbcl; 
								header('Location: '.$_SERVER['HTTP_REFERER']);
								exit;
					}
					
						
					
				//echo count($results);
				//exit;
				$results = $this->model_report_reconciliation->getOrdersDuplicate($filter_data);
				$data['product_results'] = $this->model_report_reconciliation->getOrdersProductsSummaryNewBillingDuplicate($filter_data); 
                $total_amount=0;
				foreach ($results as $result) 
				{
                      
					$grower_info = $result['o_payment_firstname'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                    $farmer_info=explode('-', $grower_info);
      
                    $grower_id='';//@$farmer_info[0];
                    $farmer_name=ucwords(strtolower(@$farmer_info[0]));
                    $father_name=ucwords(strtolower(@$farmer_info[1]));
                    if(empty($grower_id))
                    {
                        $grower_id=$result['shipping_firstname'];
                    }
                    if(empty($farmer_name))
                    {
                        $farmer_name=$result['o_payment_address_1'];
                    }
					
					$o_payment_address_1=$result['o_payment_address_1'];
					$o_payment_firstname=$result['o_payment_firstname'];
                    $inv_no=$result['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
		if(empty($inv_no))
                    {
                           $inv_no=$result['o_requisition_id'];
                    }
	
                    if(empty($result['company']))
                    {
                           $unit_name=$result['unit_name'];
                    }
							else
							{
                            $unit_name=$result['company'];
							}
							
					if($result['bcml_tagged']!='0.00')
					{
						$taggedvalue=$result['bcml_tagged'];
					}
					else
					{
						$taggedvalue=$result['tagged'];
					}
							//if(strlen($inv_no)<10)
							if(!is_numeric($farmer_name))
							{
							$data['orders'][] = array(
								'date'       => date($this->language->get('date_format_short'), strtotime($result['date'])),
                                'order_id'   => $inv_no ,
                                'inv_no'   => $result['order_id'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => number_format((float)$result['total'], 2, '.', ''),//$result['total'],
                                'tagged'     => number_format((float)$taggedvalue, 2, '.', ''),//$result['tagged'],
                                'grower_id'  => $grower_id,
                                'farmer_name'=> $farmer_name,
                                'unit'       => $unit_name,
                                'selected'  =>$selected,
								'o_payment_address_1'=>$o_payment_address_1,
								'o_payment_firstname'=>$o_payment_firstname,
								'father_name'=>$father_name,
								'cash'      => number_format((float)$result['cash'], 2, '.', '')
				
							);
                        $total_amount=$total_amount+$result['tagged'];
                        $data['store']=$result['store_name'];
                        $data['unit']=$result['company'];
							}
							
				}
				if($filter_store==0)
                {
                    
                  $data['store']='';
                  $data['unit']='';  
                } 
				$data['start_date'] = date($this->language->get('date_format_short'), strtotime($filter_date));
                $data['end_date']  = date($this->language->get('date_format_short'), strtotime($filter_date_end));
		
                require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
                
                $html = $this->load->view('reportbcml/reconciliation_pdf.tpl',$data);
                //exit;
               
                $base_url = HTTP_CATALOG;
                
                
                $mpdf = new mPDF('c', 'A4', '', '', '5', '5', '25', '25', '5', '1', '');
                $header = '<div class="header" style="">
                   
<div class="logo" style="width: 100%;" >
<img src="../image/letterhead_text.png" style="height: 40px; width: 121px;" />
<img src="../image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />

                         </div>
<img src="../image/letterhead_topline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 

</div>';
   
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


                //$filename='tagged_order_'.$filter_date.'_'.$filter_store.'_'.$filter_unit.'.pdf';    
	  //$this->model_report_reconciliation->add_file_data_tagged($filter_data,$filename); 
               // $mpdf->Output(DIR_UPLOAD.'tagged_pdf/'.$filename,'F');  
	   //$this->load->model('user/user'); 
	  //$logged_user = $this->user->getId();
	  //$update_total=$this->model_report_reconciliation->update_file_data_tagged($filter_data,$data["file_aspl"],$total_amount,$logged_user); 
                $mpdf->Output($filename,'D');
	  header('location: '.$_SERVER['HTTP_REFERER']);
	  echo "File created successfully"; 
           
        }

/////////////create bill duplicate end here//////////////////
function decryptRJ256($encrypted)
{
			
     $iv = '!QAZ2WSX#EDC4RFV'; #Same as in C#.NET
     $key = '5TGB&YHN7UJM(IK<'; #Same as in C#.NET	
    //PHP strips "+" and replaces with " ", but we need "+" so add it back in...
    $encrypted = str_replace(' ', '+', $encrypted);
    //get all the bits
    $encrypted = base64_decode($encrypted);
    $rtn = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted, MCRYPT_MODE_CBC, $iv);
    $rtn = $this->unpad($rtn);
    return($rtn);
}

function encryptRJ256($encrypted)
{
			
     $iv = '!QAZ2WSX#EDC4RFV'; #Same as in C#.NET
     $key = '5TGB&YHN7UJM(IK<'; #Same as in C#.NET	
    //PHP strips "+" and replaces with " ", but we need "+" so add it back in...
    //$encrypted = str_replace(' ', '+', $encrypted);
    //get all the bits
$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC);
$pad = $blockSize - (strlen($encrypted) % $blockSize);
    $rtn = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted.str_repeat(chr($pad), $pad), MCRYPT_MODE_CBC, $iv);
$rtn = base64_encode($rtn);
    return($rtn);
}
function pkcs7pad($plaintext, $blocksize)
{
$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC);
    $padsize = $blocksize - (strlen($plaintext) % $blocksize);
    return $plaintext . str_repeat(chr($padsize), $padsize);
}

//removes PKCS7 padding
function unpad($value)
{
    $blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $packing = ord($value[strlen($value) - 1]);
    if($packing && $packing < $blockSize)
    {
        for($P = strlen($value) - 1; $P >= strlen($value) - $packing; $P--)
        {
            if(ord($value{$P}) != $packing)
            {
                $packing = 0;
            }
        }
    }

    return substr($value, 0, strlen($value) - $packing); 
}

public function getdebitnotedetail1() {
		$this->load->language('report/reconciliation');

		$this->document->setTitle('Get Debit Note Detail');

		if (isset($this->request->get['filter_letter_number'])) {
			$filter_letter_number = $this->request->get['filter_letter_number'];
		} else {
			$filter_letter_number = '';
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = '';
		}
		

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_letter_number'])) {
			$url .= '&filter_letter_number=' . $this->request->get['filter_letter_number'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Get Debit Note Detail',
			'href' => $this->url->link('reportbcml/reconciliation/getdebitnotedetail', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/reconciliation');
		$this->load->model('pos/bcml');
        	$this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
            		'DebitNoteNo'	     => $filter_letter_number,
			'unitid'	     => $filter_unit,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		//$t1=$this->model_report_reconciliation->getTotalOrdersCompanywise($filter_data);
		$order_total = 0;//$t1["total"];
		//$total_tagged_amount_all=$t1["total_tagged_amount"];
		//$total_tagged_amount=0;
			
		if(!empty($filter_letter_number))
		{
			$results = $this->model_pos_dscl->GetOrderData('GetDebitNoteDetail',$filter_data);
		}
		
		foreach ($results as $result) {
                        		
			$data['orders'][] = array(
				
								'InvoiceDate'       => $result['InvoiceDate'],
								
								'DebitNoteNo'   => $result['DebitNoteNo'],
                                'IndentNo' => $result['IndentNo'],
                                'InvoiceNo'   => $result['InvoiceNo'],
                                'TaggedValue'      => $result['TaggedValue'],
								'DebitNoteStatus'=>$result['DebitNoteStatus'],
								'UserName'=>$result['UserName']
				
			);
		}
		/*
		$data['DebitNoteDetail'][] = array(
								'debitnoteno'=> $data["file_aspl"],
								'indentno'   => $inv_nob ,
                                'invoiceno'   => $resultb['order_id'],
                                'invoicedate'       => date('Ymd', strtotime($resultb['date'])),
                                'username'       => $user_firstname.' '.$user_lastname,
								'taggedvalue'=>number_format((float)$resultb['tagged'], 2, '.', '')
                                );
		*/
		$data['heading_title'] = 'Get Debit Note Detail';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_tax'] = $this->language->get('column_tax');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
		
		$url = '';
        if (isset($this->request->get['filter_letter_number'])) {
			$url .= '&filter_letter_number=' . $this->request->get['filter_letter_number'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/reconciliation', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_letter_number'] = $filter_letter_number;
		
		$data['filter_unit'] = $filter_unit;
		$data['total_tagged_amount'] =$total_tagged_amount;
		$data['total_tagged_amount_All'] =$total_tagged_amount_all;

		$this->load->model('unit/unit');
		$data['units']=$this->model_unit_unit->getunit(array('filter_company'=>'2'));
		//print_r($data['units']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/reconciliation_get_payment_indent1.tpl', $data));
	}

 
}