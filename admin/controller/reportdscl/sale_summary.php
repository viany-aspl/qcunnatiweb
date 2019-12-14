<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
ini_set('max_execution_time', 3000);  //3000 seconds = 50 minutes 

class ControllerReportdsclSaleSummary extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
					$this->load->language('report/sale_order');
		$this->document->setTitle('Sale Summary');
		
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

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Sale Summary',
			'href' => $this->url->link('report/sale_summary', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                $this->load->model('report/sale_summary');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_company' => '1',
			'filter_group'           => $filter_group,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

						$data['groups'] = array();

		$data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$t1=$this->model_report_sale_summary->getTotalSaleComapnywise($filter_data);
		$order_total =$t1["total"] ;

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summaryComapnywise($filter_data);

                		$total_cash_all=$t1["Cash"];
                            $total_tagged_all=$t1["Tagged"];
                            $total_subsidy_all=$t1["Subsidy"];

		foreach ($results as $result) { //print_r($result);
			$total_cash=$total_cash+$result['Cash'];
                                          $total_tagged=$total_tagged+$result['Tagged'];
                                          $total_subsidy=$total_subsidy+$result['Subsidy'];

			         $data['orders'][] = array(
                             				   'store_id' => $result['store_id'],
				'store_name' => $result['store_name'],
				'cash_order' => $result['cash_order'],
				'tagged_order' => $result['tagged_order'],
				'subsidy_order' => $result['Subsidy_order'],
				'cash'		=>$this->currency->format($result['Cash']),	
				'tagged'	=>$this->currency->format($result['Tagged']),	
				'subsidy'	=>$this->currency->format($result['Subsidy']),
                               				 'creditlimit'	=>$this->currency->format($result['creditlimit']),
                                				'currentcredit'	=>$this->currency->format($result['currentcredit']),
				'total'   => $this->currency->format(($result['Cash']+$result['Tagged']+$result['Subsidy']))
			);
		}

		$data['token'] = $this->session->data['token'];
                
		$url = '';
				
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/sale_summary', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		
		$data['total_cash'] = $total_cash;
                            $data['total_tagged'] = $total_tagged;
                            $data['total_subsidy'] = $total_subsidy;   
		$data['total_cash_all'] = $total_cash_all;
                            $data['total_tagged_all'] = $total_tagged_all;
                            $data['total_subsidy_all'] = $total_subsidy_all;


		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sale_summary.tpl', $data));
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

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_group'           => $filter_group
			
		);

        $this->load->model('report/sale_summary');
        $order_total = $this->model_report_sale_summary->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name',
        
        'Store Credit Limit',
        'Current Credit',
        'No. Order (Cash)',
        'No. Order (Tagged)',
        'No. Order (Subsidy)',
	'Cash',
	'Tagged+Cash',
        'Subsidy',
        'Total'
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
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['currentcredit']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['creditlimit']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['cash_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['tagged_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Subsidy_order']);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['Cash']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['Tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['Subsidy']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, ($data['Cash']+$data['Tagged']+$data['Subsidy']));
          
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="sale_summary_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
        public function email_excel() {
           $this->load->model('report/sale_summary');
        $order_total = $this->model_report_sale_summary->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        
        'Store Name',
	'Cash',
	'Tagged',
        'Total'
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
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Cash']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Tagged']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, ($data['Cash']+$data['Tagged']));
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='sale_summary_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    //
    $mail             = new PHPMailer();

                $body = "<p>Akshamaala Solution Pvt. Ltd.</p>";
                
                $mail->IsSMTP();
                $mail->Host       = "mail.akshamaala.in";
                                                           
                $mail->SMTPAuth   = false;                 
                $mail->SMTPSecure = "";                 
                $mail->Host       = "mail.akshamaala.in";      
                $mail->Port       = 25;                  
                $mail->Username   = "mis@akshamaala.in";  
                $mail->Password   = "mismis";            

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Sale Summary";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('chetan.singh@akshamaala.com', "Chetan Singh");
                
                

                $mail->AddAttachment(DIR_UPLOAD.$filename);
                
                if(!$mail->Send())
                {
                  echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                {
                  if(!unlink(DIR_UPLOAD.$filename))
                  {
                      echo ("Error deleting ");
                  }
                  else
                  {
                     echo ("Deleted ");
                  }
                                  
                }
        }
	public function product_payment_method() {
		$this->load->language('report/product_store_wise_sales');

		$this->document->setTitle('Product & Payment type wise sales');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
       
		if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = '';
		}
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
			$filter_store = 8;
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


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
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
			'text' => 'Product & Payment type wise sales',
			'href' => $this->url->link('report/sale_summary/product_payment_method', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/sale_summary');
                            $this->load->model('setting/store');
		$data['products'] = array();

		$filter_data = array(
			'filter_name'	     => $filter_name,
			'filter_name_id'     => $filter_name_id,
			'filter_store'       => $filter_store,
                        'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$t1=$this->model_report_sale_summary->get_Total_reports_payment_type_wise($filter_data);
		$product_total = $t1;
		$data["total_amount_all"]= $t1["total_amount"];
		$total_amount=0;
		$results = $this->model_report_sale_summary->get_reports_payment_type_wise($filter_data);
//print_r($filter_data);exit;
		foreach ($results as $result) { 

			//$total_amount=$total_amount+$result['total'];
			$data['products'][] = array(
				'store_name'      => $result['store_name'],
				'store_id'      => $result['store_id'],
				'product_name'       => $result['product_name'],
				'product_id'      => $result['product_id'],
                                                        				'cash_sale_amount'      => number_format((float)$result['Cash_sale_amount'], 2, '.', ''),
				'tagged_sale_amount'      => number_format((float)$result['Tagged_sale_amount'], 2, '.', ''),
				'subsidy_sale_amount'      => number_format((float)$result['Subsidy_sale_amount'], 2, '.', ''),
				'total_sale_amount'      => number_format((float)($result['Cash_sale_amount']+$result['Tagged_sale_amount']+$result['Subsidy_sale_amount']), 2, '.', ''),
				
				'cash_sale_qnty'   => $result['Cash_sale_qnty'],
				'tagged_sale_qnty'   => $result['Tagged_sale_qnty'],
				'subsidy_sale_qnty'   => $result['Subsidy_sale_qnty'],
				'total_sale_qnty'   => ($result['Cash_sale_qnty']+$result['Tagged_sale_qnty']+$result['Subsidy_sale_qnty']),
				'sale_date'       => date($this->language->get('date_format_short'), strtotime($result['order_date']))
				
				
			);
		}

		$data['heading_title'] = 'Product & Payment type wise sales';
		$data['total_amount'] = $total_amount;
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$url = '';

		
                            if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/sale_summary/product_payment_method', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		
		$data['filter_name'] = $filter_name;
                            $data['filter_date_start'] = $filter_date_start; 
		$data['filter_date_end'] = $filter_date_end;
                            $data['filter_name_id'] = $filter_name_id;
                            $data['filter_store'] = $filter_store;
                            $data['stores'] = $this->model_setting_store->getStores();
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/product_payment_method_wise_sales.tpl', $data));
	}



	public function product_payment_method_download() {
		

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
       
		if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = '';
		}
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
			$filter_store = '';
		}

		

		$this->load->model('report/sale_summary');
                            
		$data['products'] = array();

		$filter_data = array(
			'filter_name'	     => $filter_name,
			'filter_name_id'     => $filter_name_id,
			'filter_store'       => $filter_store,
                                          'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);
		
		$results = $this->model_report_sale_summary->get_reports_payment_type_wise($filter_data);

		

    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name',
        
        'Store ID',
	'Order ID',
        'Product Name',
        'Product ID',
        'Cash Sale amount',
        'Tagged Sale amount',
	'Subsidy Sale amount',
	'Total Sale amount',
        'Cash Sale qnty',
        'Tagged Sale qnty',
	'Subsidy Sale qnty',
	'Total Sale qnty',
	'Sale date'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     	
    $row = 2;
/*    
foreach ($results as $result) {  print_r($result);

			//$total_amount=$total_amount+$result['total'];
			$data['products'][] = array(
				'store_name'      => $result['store_name'],
				'store_id'      => $result['store_id'],
				'product_name'       => $result['product_name'],
				'product_id'      => $result['product_id'],
                                                        				'cash_sale_amount'      => number_format((float)$result['Cash_sale_amount'], 2, '.', ''),
				'tagged_sale_amount'      => number_format((float)$result['Tagged_sale_amount'], 2, '.', ''),
				'subsidy_sale_amount'      => number_format((float)$result['Subsidy_sale_amount'], 2, '.', ''),
				'total_sale_amount'      => number_format((float)($result['Cash_sale_amount']+$result['Tagged_sale_amount']+$result['Subsidy_sale_amount']), 2, '.', ''),
				
				'cash_sale_qnty'   => $result['Cash_sale_qnty'],
				'tagged_sale_qnty'   => $result['Tagged_sale_qnty'],
				'subsidy_sale_qnty'   => $result['Subsidy_sale_qnty'],
				'total_sale_qnty'   => ($result['Cash_sale_qnty']+$result['Tagged_sale_qnty']+$result['Subsidy_sale_qnty']),
				'sale_date'       => date($this->language->get('date_format_short'), strtotime($result['order_date']))
				
				
			);
		}

*/
//exit;
    foreach($results as $data)
    { //print_r($data);
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_id']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['order_id']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['product_name']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['product_id']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, number_format((float)$data['Cash_sale_amount'], 2, '.', '')); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, number_format((float)$data['Tagged_sale_amount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, number_format((float)$data['Subsidy_sale_amount'], 2, '.', '')); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, number_format((float)($data['Cash_sale_amount']+$data['Tagged_sale_amount']+$data['Subsidy_sale_amount']), 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['Cash_sale_qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['Tagged_sale_qnty']);
          	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['Subsidy_sale_qnty']);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, ($data['Cash_sale_qnty']+$data['Tagged_sale_qnty']+$data['Subsidy_sale_qnty']));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, date($this->language->get('date_format_short'), strtotime($data['order_date'])));
	
             
   
        $row++;
    }
//exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="product_payment_type_sales_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function category() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Sale Summary(Category)');
		
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


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Sale Summary(Category)',
			'href' => $this->url->link('report/sale_summary/category', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                            $this->load->model('report/sale_summary');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$t1=$this->model_report_sale_summary->getTotalSale_category($filter_data);
		$order_total =$t1["total"] ;

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary_category($filter_data);
		//$results = $this->model_report_sale_summary->getSale_summary_subsidy_cash($filter_data);
                	$total_cash_all=$t1["Cash"];
                            $total_tagged_all=$t1["Tagged"];
                            $total_subsidy_all=$t1["Subsidy"];
		$total_cash_tagged_all=$t1["Cash_Tagged"];

		foreach ($results as $result) {      //print_r($result);
			$total_cash=$total_cash+$result['Cash'];
                                          $total_tagged=$total_tagged+$result['Tagged'];
                                          $total_subsidy=$total_subsidy+$result['Subsidy'];
			$total_Cash_Tagged=$total_Cash_Tagged+$result['Cash_Tagged'];

			         $data['orders'][] = array(
                             		'store_id' => $result['store_id'],
				'store_name' => $result['store_name'],
				'cash_order' => $result['cash_order'],
				'tagged_order' => $result['tagged_order'],
				'subsidy_order' => $result['Subsidy_order'],
				'Cash_tagged_order' => $result['Cash_tagged_order'],
				'cash'		=>$this->currency->format($result['Cash']),	
				'tagged'	=>$this->currency->format($result['Tagged']),	
				'subsidy'	=>$this->currency->format($result['Subsidy']),
				'Cash_Tagged'	=>$this->currency->format($result['Cash_Tagged']),
                               		'creditlimit'	=>$this->currency->format($result['creditlimit']),
                                		'currentcredit'	=>$this->currency->format($result['currentcredit']),
				'total'   => $this->currency->format(($result['Cash']+$result['Tagged']+$result['Subsidy']+$result['Cash_Tagged']))
			);
		}

		$data['token'] = $this->session->data['token'];
                
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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/sale_summary/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		$data['stores'] = $this->model_setting_store->getStores();
	
		$data['total_cash'] = $total_cash;
                            $data['total_tagged'] = $total_tagged;
                            $data['total_subsidy'] = $total_subsidy;
		$data["total_Cash_Tagged"]=$total_Cash_Tagged;
		$data['total_cash_all'] = $total_cash_all;
                            $data['total_tagged_all'] = $total_tagged_all;
                            $data['total_subsidy_all'] = $total_subsidy_all;
		$data["total_cash_tagged_all"]=$total_cash_tagged_all;

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sale_summary_category.tpl', $data));
	}
//////////////////////////////////////////////
public function download_excel_category() {

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


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store
			
		);

        $this->load->model('report/sale_summary');
        $order_total = $this->model_report_sale_summary->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary_category($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name',
        'Store Credit Limit',
        'Current Credit',
        'No. Order (Cash)',
        'No. Order (Tagged)',
        'No. Order (Cash_Tagged)',
        'No. Order (Subsidy)', 
         'Cash',
         'Tagged',
         'Cash_Tagged',
         'Subsidy',
         'Total'
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
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['creditlimit']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['currentcredit']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['cash_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['tagged_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Cash_tagged_order']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['Subsidy_order']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['Cash']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['Tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['Cash_Tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['Subsidy']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, ($data['Cash']+$data['Tagged']+$data['Subsidy']+$data['Cash_Tagged']));
          
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="sale_summary_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function subsidycash() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Sale Summary(Category)');
		
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


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Sale Summary(Category)',
			'href' => $this->url->link('reportdscl/sale_summary/subsidycash', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                            $this->load->model('report/sale_summary');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store,
                        		'filter_company'           => '1',
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		//$t1=$this->model_report_sale_summary->getTotalSale_category($filter_data);
                	$t1=$this->model_report_sale_summary->getTotalSale_subsidy_cashCompanywise($filter_data);
		$order_total =$t1["total"] ;
		//print_r($t1);
		$data['orders'] = array();

		//$results = $this->model_report_sale_summary->getSale_summary_category($filter_data);
		$results = $this->model_report_sale_summary->getSale_summary_subsidy_cashCompanywise($filter_data);
                	$total_cash_all=$t1["Cash"];
                            $total_tagged_all=$t1["Tagged"];
                            $total_subsidy_all=$t1["Cash_subsidy"];
		$total_cash_tagged_all=$t1["Cash_Tagged"];
                            $total_cash_subsidy_all=$t1["Subsidy"];

		foreach ($results as $result) {      //print_r($result);
			$total_cash=$total_cash+$result['Cash'];
                                          $total_tagged=$total_tagged+$result['Tagged'];
                                          $total_subsidy=$total_subsidy+$result['Subsidy'];
			$total_Cash_Tagged=$total_Cash_Tagged+$result['Cash_Tagged'];
                                          $total_Cash_subsidy=$total_Cash_subsidy+$result['Cash_subsidy'];

			         $data['orders'][] = array(
                             		'store_id' => $result['store_id'],
				'store_name' => $result['store_name'],
				'cash_order' => $result['cash_order'],
				'tagged_order' => $result['tagged_order'],
				'subsidy_order' => $result['Subsidy_order'],
				'Cash_tagged_order' => $result['Cash_tagged_order'],
				'cash'		=>$this->currency->format($result['Cash']),	
				'tagged'	=>$this->currency->format($result['Tagged']),	
				'subsidy'	=>$this->currency->format($result['Subsidy']),
				'Cash_Tagged'	=>$this->currency->format($result['Cash_Tagged']),
                                                        'Cash_subsidy'	=>$this->currency->format($result['Cash_subsidy']),
                               		'creditlimit'	=>$this->currency->format($result['creditlimit']),
                                		'currentcredit'	=>$this->currency->format($result['currentcredit']),
				'total'   => $this->currency->format(($result['Cash']+$result['Tagged']+$result['Subsidy']+$result['Cash_Tagged']+$result['Cash_subsidy']))
			);
		}
//print_r($data['orders']);
		$data['token'] = $this->session->data['token'];
                
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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportdscl/sale_summary/subsidycash', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		$data['stores'] = $this->model_setting_store->getStoresCompanyWise('1');
	
		$data['total_cash'] = $total_cash;
                            $data['total_tagged'] = $total_tagged;
                            $data['total_subsidy'] = $total_subsidy;
		$data["total_Cash_Tagged"]=$total_Cash_Tagged;
		$data['total_Cash_subsidy'] = $total_Cash_subsidy;

		$data['total_cash_all'] = $total_cash_all;
                            $data['total_tagged_all'] = $total_tagged_all;
                            $data['total_subsidy_all'] = $total_subsidy_all;
		$data["total_cash_tagged_all"]=$total_cash_tagged_all;
		$data["total_cash_subsidy_all"]=$total_cash_subsidy_all;

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportdscl/sale_summary_subsidycash.tpl', $data));
	}
//////////////////////////////////////////////
public function download_excel_subsidycash() {

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


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store,
                        'filter_company'           => '1'
			
		);

        $this->load->model('report/sale_summary');
       

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary_subsidy_cashCompanywise($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
         'Store Name',
         'Store Credit Limit',
         'Current Credit',      
         'Cash',      
         'Cash Tagged',
         'Cash Subsidy',
         'Tagged',
         'Subsidy(By Company )',
         'No. Order (Cash)',
         'No. Order (Tagged)',
         'No. Order (Cash_Tagged)',
         'No. Order (Subsidy)', 
         'Total'
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
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['creditlimit']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['currentcredit']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Cash']);  
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['Cash_Tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Cash_subsidy']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['Tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['Subsidy']);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['cash_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['tagged_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['Cash_tagged_order']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['Subsidy_order']);
        
        
        
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, ($data['Cash']+$data['Tagged']+$data['Subsidy']+$data['Cash_Tagged']+$data['Cash_subsidy']));
          
             //'Cash_subsidy'	=>$this->currency->format($result['Cash_subsidy']),
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="sale_summary_subsidycash_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
//////////////////////////////////////////////
public function download_excel_category_date_wise() {

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


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store
			
		);

        $this->load->model('report/sale_summary');
        //$order_total = $this->model_report_sale_summary->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary_category_date_wise($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name',
        'Sale date',
        
        'No. Order (Cash)',
        'No. Order (Tagged)',
        'No. Order (Cash_Tagged)',
        'No. Order (Subsidy)', 
         'Cash',
         'Tagged',
         'Cash_Tagged',
         'Subsidy',
         'Total'
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
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('d/m/Y',strtotime($data['date_added']))); 

        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['creditlimit']); 
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['currentcredit']); 

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['cash_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['tagged_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['Cash_tagged_order']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Subsidy_order']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['Cash']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['Tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['Cash_Tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['Subsidy']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, ($data['Cash']+$data['Tagged']+$data['Subsidy']+$data['Cash_Tagged']));
          
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="sale_summary_date_wise_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function subsidy() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Sale Summary(Only Subsidy)');
		
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


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Sale Summary(Only Subsidy)',
			'href' => $this->url->link('report/sale_summary/subsidy', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                            $this->load->model('report/sale_summary');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$t1=$this->model_report_sale_summary->getTotalSale_subsidy($filter_data);
		$order_total =$t1["total"] ;

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary_subsidy($filter_data);

                	$total_cash_all=$t1["store_cash"];
                           
                            $total_subsidy_all=$t1["company_subsidy"];
		$total_Total_all=$t1["Total"];

		foreach ($results as $result) {      //print_r($result);
			$total_cash=$total_cash+$result['store_cash'];
                                          $total_total=$total_total+$result['Total'];
                                          $total_subsidy=$total_subsidy+$result['company_subsidy'];
			

			         $data['orders'][] = array(
                             		'store_id' => $result['store_id'],
				'store_name' => $result['store_name'],
				'subsidy_order' => $result['subsidy_order'],
				
				'cash'		=>$this->currency->format($result['store_cash']),	
					
				'subsidy'	=>$this->currency->format($result['company_subsidy']),
				
				'total'   => $this->currency->format($result['Total'])
			);
		}

		$data['token'] = $this->session->data['token'];
                
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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/sale_summary/subsidy', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		$data['stores'] = $this->model_setting_store->getStores();
	
		$data['total_cash'] = $total_cash;
                            $data['total_subsidy'] = $total_subsidy;
                            $data['total_total'] = $total_total;
		
		$data['total_cash_all'] = $total_cash_all;                          
                            $data['total_subsidy_all'] = $total_subsidy_all;
		 $data['total_Total_all'] = $total_Total_all;

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sale_summary_subsidy.tpl', $data));
	}
//////////////////////////////////////////////
public function download_excel_subsidy() {

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


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store
			
		);

        $this->load->model('report/sale_summary');
        

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary_subsidy($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name',
        'Cash taken by store',
        'Subsidy given Company',
        'No. Order (Subsidy)',
         'Total'
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
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_cash']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['company_subsidy']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['subsidy_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['Total']); 
         
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="subsidy_sale_summary_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function sale_summary() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Sale Summary(Category)');

		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/sale_report');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');


		
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


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Sale Summary(Category)',
			'href' => $this->url->link('reportdscl/sale_summary/sale_summary', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                            $this->load->model('report/sale_summary');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store,
			'filter_company'           => '1',
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$t1=$this->model_report_sale_summary->getTotalSale_new($filter_data);
		$order_total =$t1["total"] ;
		
		$data['orders'] = array();
		$results = $this->model_report_sale_summary->getSale_summary_new($filter_data);
        
		$total_cash_all=$t1["Cash"];
        $total_tagged_all=$t1["Tagged"]+$t1["CTagged"];
        $total_subsidy_all=$t1["Cash_subsidy"];
		$total_cash_tagged_all=$t1["Cash_Tagged"];
        $total_cash_subsidy_all=$t1["Subsidy"];

		foreach ($results as $result) {      //print_r($result);
			$total_cash=$total_cash+$result['Cash'];
                                          $total_tagged=$total_tagged+$result['Tagged']+$result['Tagged_cash'];
                                          $total_subsidy=$total_subsidy+$result['Subsidy'];
			$total_Cash_Tagged=$total_Cash_Tagged+$result['Cash_Tagged'];
                                          $total_Cash_subsidy=$total_Cash_subsidy+$result['Cash_subsidy'];

			         $data['orders'][] = array(
                             		'store_id' => $result['store_id'],
				'store_name' => $result['store_name'],
				'cash_order' => $result['cash_order'],
				'tagged_order' => $result['tagged_order'],
				'subsidy_order' => $result['Subsidy_order'],
				'Cash_tagged_order' => $result['Cash_tagged_order'],
				'cash'		=>$this->currency->format($result['Cash']),	
				'tagged'	=>$this->currency->format($result['Tagged']+$result['Tagged_cash']),	
				'subsidy'	=>$this->currency->format($result['Subsidy']),
				'Cash_Tagged'	=>$this->currency->format($result['Cash_Tagged']),
                'Cash_subsidy'	=>$this->currency->format($result['Cash_subsidy']),
                
				'total'   => $this->currency->format(($result['Cash']+$result['Tagged']+$result['Tagged_cash']+$result['Subsidy']+$result['Cash_Tagged']+$result['Cash_subsidy']))
			);
		}
		$data['token'] = $this->session->data['token'];
                
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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportdscl/sale_summary/sale_summary', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		$data['stores'] = $this->model_setting_store->getStoresCompanyWise('1');
	
		$data['total_cash'] = $total_cash;
                            $data['total_tagged'] = $total_tagged;
                            $data['total_subsidy'] = $total_subsidy;
		$data["total_Cash_Tagged"]=$total_Cash_Tagged;
		$data['total_Cash_subsidy'] = $total_Cash_subsidy;

		$data['total_cash_all'] = $total_cash_all;
                            $data['total_tagged_all'] = $total_tagged_all;
                            $data['total_subsidy_all'] = $total_subsidy_all;//-$total_cash_subsidy_all;
		$data["total_cash_tagged_all"]=$total_cash_tagged_all;
		$data["total_cash_subsidy_all"]=$total_cash_subsidy_all;

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportdscl/sale_summary/sale_summary.tpl', $data));
	}
//////////////////////////////////////////////
public function download_excel_sale_summary() 
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


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}	

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store,
			'filter_company'           => '1'
			
		);

        $this->load->model('report/sale_summary');
       

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary_new($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
         'Store Name',
            
         'Cash',      
         'Cash Tagged',
         'Cash Subsidy',
         'Tagged',
         'Subsidy(By Company )',
         'No. Order (Cash)',
         'No. Order (Tagged)',
         'No. Order (Cash_Tagged)',
         'No. Order (Subsidy)', 
         'Total'
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
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
       
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Cash']);  
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Cash_Tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Cash_subsidy']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['Tagged']+$data['Tagged_cash']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Subsidy']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['cash_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['tagged_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['Cash_tagged_order']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['Subsidy_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, ($data['Cash']+$data['Tagged']+$data['Tagged_cash']+$data['Subsidy']+$data['Cash_Tagged']+$data['Cash_subsidy']));
          
             //'Cash_subsidy'	=>$this->currency->format($result['Cash_subsidy']),
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="sale_summary_subsidycash_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
	

}