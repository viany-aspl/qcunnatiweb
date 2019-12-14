<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerSubsidySubsidy extends Controller {
	
         public function index() {
             
             $this->document->setTitle('Set Subsidy');
             
             
             $this->load->model('subsidy/subsidy');
             
             if ($this->request->server['REQUEST_METHOD'] == 'POST')
             { 
                 
                 $this->model_subsidy_subsidy->submit_form($this->request->post);
                 $this->session->data['success'] = 'Submitted Successfully';
                 //$this->response->redirect($this->url->link('target/target', 'token=' . $this->session->data['token'] . $url, 'SSL'));
             } 
             $this->getForm();
         }
         protected function getForm() {
             
                $this->load->model('user/user');
                $this->load->model('setting/store');
                $user_data = $this->model_user_user->getUser($results["user_id"]);
                
                $logged_user_data = $this->user->getId();
                
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Set subsidy',
			'href' => $this->url->link('subsidy/subsidy', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL');
                
                	$data['stores'] = $this->model_setting_store->getStores();
		$data['categories'] = $this->model_subsidy_subsidy->getcategories();
		//print_r($data['categories']);
                	$data['TransactionTypes'] =  $getTransactionTypesresults;
                
                	$data['logged_user'] = $logged_user_data;
                	if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
                $data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['action'] = $this->url->link('subsidy/subsidy', 'token=' . $this->session->data['token'] , 'SSL');
		$this->response->setOutput($this->load->view('subsidy/subsidy_form.tpl', $data));
	}
        public function view() {
		$this->load->language('report/cash_report');

		$this->document->setTitle("View Subsidy");
                //print_r($_REQUEST);
		if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = '';
		}
                	if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
                	if (isset($this->request->get['filter_category'])) {
			$filter_category = $this->request->get['filter_category'];
		} else {
			$filter_category = 0;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
		}
                	if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
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
			'text' => "View Subsidy",
			'href' => $this->url->link('subsidy/subsidy/view', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

                $this->load->model('subsidy/subsidy');
		$data['orders'] = array();

		$filter_data = array(
			'filter_name_id'	     => $filter_name_id,
			'filter_store'	     => $filter_store,
			'filter_category'=>$filter_category,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_subsidy_subsidy->get_Totalsubsidy($filter_data);

		$data['orders'] = array();

		$results = $this->model_subsidy_subsidy->get_subsidy($filter_data);
                
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				'SIID'       => $result['product_subsidy_id'],
				'store_name' => $result['store_name'],
				'store_id'   => $result['store_id'],
				'product_id'      => $result['product_id'],
                               		'subsidy'       => $result['subsidy'],
				'category_id'=> $result['category_id'],
				'category_name'       => $result['category_name'],
                                		'date_start' =>  date($this->language->get('date_format_short'), strtotime($result['date_start'])),   
				'product_name'  => $result['product_name']
			);
		}
		$data['categories'] = $this->model_subsidy_subsidy->getcategories();
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
		}
                if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
               	if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}
                if (!isset($this->request->get['filter_butt'])&&isset($this->request->get['filter_store'])&& count($results)>0) {
			$url .= '&filter_butt=1';
               
		}
  if(isset($this->request->get['filter_butt'])&& count($results)>0) {
			$url .= '&filter_butt=' . $this->request->get['filter_butt'];
                        
		}
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('subsidy/subsidy/view', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
                $this->load->model('setting/store');
                $data['stores'] = $this->model_setting_store->getStores();
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
                	$data['filter_name_id'] = $filter_name_id;
		$data['filter_store'] = $filter_store;
		$data['filter_category']=$filter_category;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $v=$this->request->server['REQUEST_METHOD'];
   if ($this->request->server['REQUEST_METHOD'] == 'GET' && !isset($this->request->get['filter_butt']) && isset($this->request->get['filter_store']) && count($results)>0)
             { 
		
                
                $this->response->redirect($this->url->link('subsidy/subsidy/view', 'token=' . $this->session->data['token'] . $url, 'SSL'));
             }
             else
             {
		$this->response->setOutput($this->load->view('subsidy/view_subsidy.tpl', $data));
             }
	}
        public function download_excel() {
		
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
                
                $this->load->model('report/cash');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
			
		);

		$data['orders'] = array();

		$results = $this->model_report_cash->getCash_report($filter_data);
                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Store Name ',
        'Bank',
        'Date',
        'Amount'
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
    
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['bank_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['date_added'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['amount']);
        
            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Cash_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
		
	}
        public function email_excel() {
           
                $this->load->model('report/cash');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => date('Y-m-d'),
			'filter_date_end'	     => date('Y-m-d')
			
		);

		$data['orders'] = array();

		$results = $this->model_report_cash->getCash_report($filter_data);
                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Store Name ',
        'Bank',
        'Date',
        'Amount'
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
    
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['bank_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['date_added'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['amount']);
        
            
        

        $row++;
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='cash_report_'.date('ymdhis').'.xls';
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

				$mail->Subject    = "Tagged orders Report";

				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

				$mail->MsgHTML($body);
				
				//to get the email of supplier
				
				$mail->AddAddress('vipin.kumar@aspltech.com', "vipin Chahal");
				
				

				$mail->AddAttachment(DIR_UPLOAD.$filename);
				
				if(!$mail->Send()) 
				{
				  echo "Mailer Error: " . $mail->ErrorInfo;
				} 
				else
				{ 
				  if(!unlink($file_to_attach))
				  {
					  echo ("Error deleting $file_to_attach");
				  }
				  else
				  {
					 echo ("Deleted $file_to_attach");
				  }
                                  
				}
        }
public function updateSubsidyZero() 
        {
                $json = array();
                $this->load->model('subsidy/subsidy');
                 $storeid=$this->request->get['store_id'];   
                 $product=$this->request->get['product_id'];  
	   $category=$this->request->get['category_id']; 
		$mdodata= $this->model_subsidy_subsidy->updSubsidyZero($storeid,$product,$category);
         
                echo $mdodata;
        }
           public function updateProductSubsidyZero() 
        {
                $json = array();
                $this->load->model('subsidy/subsidy');
                $storeid=$this->request->get['store_id'];   
                
		$upddata= $this->model_subsidy_subsidy->updProductSubsidyZero($storeid);
            
                echo $upddata;
        }
}