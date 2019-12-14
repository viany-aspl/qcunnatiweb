<?php
class ControllerCcareCcare extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('ccare/ccare');

		$this->document->setTitle('Orders Leads (Care) - Pending');

		$this->load->model('ccare/ccare');

		$this->getList();
	}
        public function completed() {
		$this->load->language('ccare/ccare');

		$this->document->setTitle('Orders Leads (Care) - Completed');

		$this->load->model('ccare/ccare');

		$this->getListCompleted();
	}
        public function customer() {
		$this->load->language('ccare/ccare');

		$this->document->setTitle('Customer care (Farmers)');

		$this->load->model('ccare/ccare');

		$this->getListCustomer();
	}
        public function submit_call_data()
        {
            date_default_timezone_set("Asia/Kolkata");
            //$_SERVER["HTTP_REFERER"];
            $order_id=$this->request->get['order_id'];
            $mobile=$this->request->get['mobile_number'];
            $current_call_status=$this->request->get['current_call_status'];
            $call_status=$this->request->get['call_status'];
            $farmer_first_name=$this->request->get['farmer_first_name'];
            $farmer_last_name=$this->request->get['farmer_last_name'];
            $village=$this->request->get['village'];
            $sowing_date=$this->request->get['sowing_date'];
            $txt_response=$this->request->get['txt_response'];
            $buy_new=$this->request->get['buy_new'];
            $buy_product_text=$this->request->get['buy_product_text'];
	    $Reason_of_response=$this->request->get['Reason_of_response'];
            $Acres=$this->request->get['Acres'];
            $logged_user_data=$this->request->get['logged_user_data'];
            $current_order_status=$this->request->get['current_order_status'];
            $buying_date=$this->request->get['buying_date'];
            
            $data = array(
			'order_id'             => $order_id,
			'mobile'	       => $mobile,
			'call_status'          => $call_status,
			'farmer_first_name'    => $farmer_first_name,
			'farmer_last_name'     => $farmer_last_name,
			'village'              => $village,
			'sowing_date'          => $sowing_date,
                        'current_call_status'  => $current_call_status,
                        'txt_response'         => $txt_response,
                        'buy_new'              => $buy_new,
                        'buy_product_text'     => $buy_product_text,
		        'Reason_of_response'   => $Reason_of_response,
                        'Acres'                => $Acres,
                        'logged_user_data'     => $logged_user_data,
                        'current_order_status' => $current_order_status,
                        'datetime'             => date('Y-m-d h:i:s'),
                        'buying_date'          => $buying_date
			
		);
           
            $this->load->model('ccare/ccare');
            $result = $this->model_ccare_ccare->SubmitCallData($data);   
            header('location: '.$_SERVER["HTTP_REFERER"]);
        }
         public function download_reports_completed()
        {
            
		$this->load->model('ccare/ccare');
            

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

		

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'    => $filter_date_start,
			'filter_date_end' => $filter_date_end
			
		);

		$order_total = $this->model_ccare_ccare->getTotalCallscompleted($filter_data);

		$results = $this->model_ccare_ccare->getCallscompleted($filter_data);

    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Customer Mobile',
        'Store Name ',
        'Order id',
        'Call status',
        'Call time',
        'Farmer name',
        'Village',
        'Sowing Date',
        'Response',
        'Reason of response',
        'Acres',
        'Will buy',
        'When buy',
        'What buy'
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
    
        $ct_SID=$data["SID"];
        $feedback_data = $this->model_ccare_ccare->getCallFeedback($ct_SID);
        $call_data = $this->model_ccare_ccare->getCallData($ct_SID,$data["order_id"]);
        $sowing_date="";
        if($call_data['sowing_date']!="0000-00-00") { $sowing_date=date($this->language->get('date_format_short'), strtotime($call_data['sowing_date']));}
        if($data['to']=="1"){ $call_status="Answered" ;} if($data['to']=="2"){ $call_status= "Busy" ;} if($data['to']=="3"){ $call_status= "Not Reachable" ;} 
        $buying_date="";
        if($feedback_data['buy_new_date']!="") { $buying_date=date($this->language->get('date_format_short'), strtotime($feedback_data['buy_new_date']));}
	
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['mobile_number']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $call_data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $call_status);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date($this->language->get('date_format_short'), strtotime($data['call_time'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $call_data['firstname']." ".$call_data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $call_data['village_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $sowing_date);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $feedback_data['txt_response']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $feedback_data['Reason_of_response']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $feedback_data['Acres']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $feedback_data['buy_new']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $feedback_data['buy_new_date']);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $feedback_data['buy_product_text']);
        
         

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Call_report_completed_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        } 
   public function get_reports_completed()
        {
            $this->load->language('ccare/ccare');

		$this->document->setTitle('Care Reports - Completed');

		$this->load->model('ccare/ccare');
            

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

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'text' => 'Care Reports - Completed',
			'href' => $this->url->link('ccare/ccare/get_reports_completed', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
                $this->load->model('user/user');
                
                $data['logged_user_data'] = $this->user->getId();
                $data['current_order_status'] = '1';
		$data['group'] =$this->user->getGroupId();

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'    => $filter_date_start,
			'filter_date_end' => $filter_date_end,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_ccare_ccare->getTotalCallscompleted($filter_data);

		$results = $this->model_ccare_ccare->getCallscompleted($filter_data);

		 foreach ($results as $result) { 
                     $ct_SID=$result["SID"];
                     $feedback_data = $this->model_ccare_ccare->getCallFeedback($ct_SID);
                     $call_data = $this->model_ccare_ccare->getCallData($ct_SID,$result["order_id"]);
                     //echo $result['call_time'];
                     //print_r($result);
                     $sowing_date="";
                     if($call_data['sowing_date']!="0000-00-00") { $sowing_date=date($this->language->get('date_format_short'), strtotime($call_data['sowing_date']));}
	             $buying_date="";
                     if($feedback_data['buy_new_date']!="") { $buying_date=date($this->language->get('date_format_short'), strtotime($feedback_data['buy_new_date']));}
	
                     $data['orders'][] = array(
				'order_id'      => $result['order_id'],
				
				'call_status'   => $result['to'],
                                'mobile_number'     => $result['mobile_number'],
                                'store_name'    => $call_data['store_name'],
				
				'datetime'    => date($this->language->get('date_format_short'), strtotime($result['call_time'])),
				'sowing_date' => $sowing_date,
				'farmer_name' => $call_data['firstname']." ".$call_data['lastname'],
				'village_name' => $call_data['village_name'],
				'txt_response'     => $feedback_data['txt_response'],
                                'Reason_of_response'     => $feedback_data['Reason_of_response'],
                                'Acres'     => $feedback_data['Acres'],
                                'buy_new'     => $feedback_data['buy_new'],
                                'buying_date'     => $feedback_data['buy_new_date'],
                                'buy_product_text'     => $feedback_data['buy_product_text']
                            
				
			);
		}

		$data['heading_title'] = 'Care Reports - Pending';
		
		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('ccare/ccare/get_reports_completed', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('ccare/ccare_reports_completed.tpl', $data));
        }
        
        //////////////////////////////////////////////////////////////
        public function download_reports_pending()
        {
            
		$this->load->model('ccare/ccare');
            

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

		

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'    => $filter_date_start,
			'filter_date_end' => $filter_date_end
			
		);

		$order_total = $this->model_ccare_ccare->getTotalCallsPending($filter_data);

		$results = $this->model_ccare_ccare->getCallsPending($filter_data);

    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Customer Mobile',
        'Store Name ',
        'Order id',
        'Call status',
        'Call date',
        'Farmer name',
        'Village',
        'Sowing Date',
        'When you will come to buy the product',
        'Remarks',
        'Acres',
        'Will buy',
        'When buy',
        'What buy'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    $row = 2;
    
    foreach($results as $data)
    {   //print_r($data);
        $col = 0;
        $data['to'];
        $ct_SID=$data["SID"];
        $feedback_data = $this->model_ccare_ccare->getCallFeedback($ct_SID);
        $call_data = $this->model_ccare_ccare->getCallData($ct_SID,$data["order_id"]);
        $sowing_date="";
        if($call_data['sowing_date']!="0000-00-00") { $sowing_date=date($this->language->get('date_format_short'), strtotime($call_data['sowing_date']));}
        if($data['to']=="1"){ $call_status="Answered" ;} if($data['to']=="2"){ $call_status= "Busy" ;} if($data['to']=="3"){ $call_status= "Not Reachable" ;} 
        $buying_date="";
        if($feedback_data['buy_new_date']!="") { $buying_date=date($this->language->get('date_format_short'), strtotime($feedback_data['buy_new_date']));}
			
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['mobile_number']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $call_data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $call_status);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date($this->language->get('date_format_short'), strtotime($data['call_time'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $call_data['firstname']." ".$call_data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $call_data['village_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $sowing_date);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $feedback_data['txt_response']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $feedback_data['Reason_of_response']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $feedback_data['Acres']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $feedback_data['buy_new']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $feedback_data['buy_new_date']);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $feedback_data['buy_product_text']);
        
         

        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Call_report_pending_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        } 
   public function get_reports_pending()
        {
            $this->load->language('ccare/ccare');

		$this->document->setTitle('Care Reports - Pending');

		$this->load->model('ccare/ccare');
            

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

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'text' => 'Care Reports - Pending',
			'href' => $this->url->link('ccare/ccare/get_reports_pending', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
                $this->load->model('user/user');
                
                $data['logged_user_data'] = $this->user->getId();
                $data['current_order_status'] = '1';
		$data['group'] =$this->user->getGroupId();

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'    => $filter_date_start,
			'filter_date_end' => $filter_date_end,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_ccare_ccare->getTotalCallsPending($filter_data);

		$results = $this->model_ccare_ccare->getCallsPending($filter_data);

		 foreach ($results as $result) { 
                     $ct_SID=$result["SID"];
                     $feedback_data = $this->model_ccare_ccare->getCallFeedback($ct_SID);
                     $call_data = $this->model_ccare_ccare->getCallData($ct_SID,$result["order_id"]);
                     //echo $result['call_time'];
                     //print_r($feedback_data);
                     $sowing_date="";
                     if($call_data['sowing_date']!="0000-00-00") { $sowing_date=date($this->language->get('date_format_short'), strtotime($call_data['sowing_date']));}
	                $buying_date="";
                     if($feedback_data['buy_new_date']!="") { $buying_date=date($this->language->get('date_format_short'), strtotime($feedback_data['buy_new_date']));}
			
                     $data['orders'][] = array(
				'order_id'      => $result['order_id'],
				
				'call_status'   => $result['to'],
                                'mobile_number'     => $result['mobile_number'],
                                'store_name'    => $call_data['store_name'],
				
				'datetime'    => date($this->language->get('date_format_short'), strtotime($result['call_time'])),
				'sowing_date' => $sowing_date,
				'farmer_name' => $call_data['firstname']." ".$call_data['lastname'],
				'village_name' => $call_data['village_name'],
				'txt_response'     => $feedback_data['txt_response'],
                                'Reason_of_response'     => $feedback_data['Reason_of_response'],
                                'Acres'     => $feedback_data['Acres'],
                                'buy_new'     => $feedback_data['buy_new'],
                                'buying_date'     => $feedback_data['buy_new_date'],
                                'buy_product_text'     => $feedback_data['buy_product_text']
                            
				
			);
		}

		$data['heading_title'] = 'Care Reports - Pending';
		
		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('ccare/ccare/get_reports_pending', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('ccare/ccare_reports_pending.tpl', $data));
        } 
         
        public function get_order_info()
        {
            
            $order_id=$this->request->get['order_id'];
            $mobile=$this->request->get['mobile'];
            $this->load->model('ccare/ccare');
            //$result = $this->model_ccare_ccare->getOrder($order_id);
            //print_r($result);
            //echo $order_id;
            $this->order_info();
            
        }
        function order_info()
        {
            $this->load->model('ccare/ccare');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_ccare_ccare->getOrder($order_id);

		if ($order_info) {
			
			$data['order_id'] = $this->request->get['order_id'];

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['store_name'] = $order_info['store_name'];
			$data['store_url'] = $order_info['store_url'];
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];

			if ($order_info['customer_id']) {
				$data['customer'] = $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], 'SSL');
			} else {
				$data['customer'] = '';
			}

			$this->load->model('sale/customer_group');

			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['fax'] = $order_info['fax'];
			$data['comment'] = nl2br($order_info['comment']);
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['payment_method'] = $order_info['payment_method'];
			$data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

			$this->load->model('sale/customer');

			$data['reward'] = $order_info['reward'];

			$data['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

			$data['affiliate_firstname'] = $order_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $order_info['affiliate_lastname'];

			if ($order_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], 'SSL');
			} else {
				$data['affiliate'] = '';
			}

			$data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);

			$this->load->model('marketing/affiliate');

			$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);

			$this->load->model('localisation/order_status');

			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

			if ($order_status_info) {
				$data['order_status'] = $order_status_info['name'];
			} else {
				$data['order_status'] = '';
			}

			

			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->model_ccare_ccare->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_ccare_ccare->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], 'SSL')
							);
						}
					}
				}

				$data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			$data['vouchers'] = array();

			$vouchers = $this->model_ccare_ccare->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'href'        => $this->url->link('sale/voucher/edit', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], 'SSL')
				);
			}

			$totals = $this->model_ccare_ccare->getOrderTotals($this->request->get['order_id']);

			foreach ($totals as $total) { //print_r($total);
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			$data['order_status_id'] = $order_info['order_status_id'];
                        $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
			$data['date_modified'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));
                        
                        if($order_info['date_potential']=="0000-00-00 00:00:00")
{
$date_potential_temp="00/00/0000";
}
else
{
$date_potential_temp=date($this->language->get('date_format_short'), strtotime($order_info['date_potential']));
}
                        
                        $order_info_return='<div class="tab-pane active" id="tab-order">
            <table class="table table-bordered">
              <tr>
                <td>Order ID:</td>
                <td>'.$order_id.'</td>
              </tr>
             
              <tr>
                <td>Store Name:</td>
                <td>'.$data['store_name'].'</td>
              </tr>
              
              <tr>
                <td>Customer:</td>
                <td>'.$data['firstname'].' '.$data['lastname'].'</td>
              </tr>
             
              <tr>
                <td>Customer Group:</td>
                <td>'.$data['customer_group'].'</td>
              </tr>
              
              <tr>
                <td>E-Mail:</td>
                <td>'.$data['email'].'</td>
              </tr>
              <tr>
                <td>Telephone:</td>
                <td>'.$data['telephone'].'</td>
              </tr>
              
              <tr>
                <td>Fax:</td>
                <td>'.$data['fax'].'</td>
              </tr>
              
              <tr>
                <td>Total:</td>
                <td>'.$data['total'].'</td>
              </tr>
            
              <tr>
                <td>Order Status:</td>
                <td id="order-status">'.$data['order_status'].'</td>
              </tr>
              
              <tr>
                <td>Date Added:</td>
                <td>'.$data['date_added'].'</td>
              </tr>
              <tr>
                <td>Date Potential:</td>
                <td>'.$date_potential_temp.'</td>
              </tr>
            </table>
          </div>';
          
          $product_info_return_1='<div class="tab-pane" id="tab-product">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td class="text-left">Product</td>
                  <td class="text-left">Model</td>
                  <td class="text-right">Quantity</td>
                  <td class="text-right">Unit Price</td>
                  <td class="text-right">Total</td>
                </tr>
              </thead>
              <tbody>';
                foreach ($products as $product) {
                    
                $prod_qunat_text.='
                <tr>
                  <td class="text-left">'.$product['name'].'</td>
                  <td class="text-left">'.$product['model'].'</td>
                  <td class="text-right">'.$product['quantity'].'</td>
                  <td class="text-right">'.$product['price'].'</td>
                  <td class="text-right">'.$product['total'].'</td>
                </tr>';
                 } 
                foreach ($vouchers as $voucher) {
               $prod_voucher_text.=' <tr>
                  <td class="text-left">'.$voucher['description'].'</td>
                  <td class="text-left"></td>
                  <td class="text-right">1</td>
                  <td class="text-right">'.$voucher['amount'].'</td>
                  <td class="text-right">'.$voucher['amount'].'</td>
                </tr>';
                } 
                    
               foreach ($data['totals'] as $total2) { 
                $prod_total_text.='<tr>
                  <td colspan="4" class="text-right">'.$total2['title'].':</td>
                  <td class="text-right">'.$total2['text'].'</td>
                </tr>';
                 } 
              $product_info_return_2='</tbody>
            </table>
          </div>';
          $product_info_return=$product_info_return_1.$prod_qunat_text.$prod_voucher_text.$prod_total_text.$product_info_return_2;
                        
                        
            echo $order_info_return.'----and----'.$product_info_return.'----and----'.$order_info['call_status'];;            
        }
        }

	protected function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'text' => 'Orders Leads (Care) - Pending',
			'href' => $this->url->link('ccare/ccare', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
                $this->load->model('user/user');
                
                $data['logged_user_data'] = $this->user->getId();
                $data['current_order_status'] = '1';
		$data['group'] =$this->user->getGroupId();

		$data['orders'] = array();

		$filter_data = array(
			'filter_order_id'      => $filter_order_id,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => $filter_order_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);
if($data['group']=="11")
{
$filter_data = array(
			'filter_user_id' => $this->user->getId(),
			'filter_order_id'      => $filter_order_id,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => $filter_order_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);


}

		$order_total = $this->model_ccare_ccare->getTotalOrders($filter_data);

		$results = $this->model_ccare_ccare->getOrders($filter_data);

		foreach ($results as $result) { //print_r($result);
			$data['orders'][] = array(
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'status'        => $result['status'],
                                		'telephone'     => $result['telephone'],
                                		'store_name'    => $result['store_name'],
				'ase_name'      => $result['ase_name'],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $result['shipping_code'],
				'view'          => $this->url->link('sale/orderleads/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'edit'          => $this->url->link('sale/orderleads/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'delete'        => $this->url->link('sale/orderleads/delete', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		


		$data['sort_order'] = $this->url->link('ccare/ccare', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('ccare/ccare', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
	 	$data['sort_status'] = $this->url->link('ccare/ccare', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
                            $data['sort_store_name'] = $this->url->link('ccare/ccare', 'token=' . $this->session->data['token'] . '&sort=store_name' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('ccare/ccare', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('ccare/ccare', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('ccare/ccare', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('ccare/ccare', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		//$mcrypt=new MCrypt();
		//echo $mcrypt->decrypt('ccb48108a740fd8f6f0148be0c80a7fa');

		$this->response->setOutput($this->load->view('ccare/ccare.tpl', $data));
	}
         protected function getListCompleted() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'text' => 'Orders Leads (Care) - Completed',
			'href' => $this->url->link('ccare/ccare/completed', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
                $this->load->model('user/user');
                
                $data['logged_user_data'] = $this->user->getId();
                $data['current_order_status'] = '5';
		$data['group'] =$this->user->getGroupId();

		$data['orders'] = array();

		$filter_data = array(
			'filter_order_id'      => $filter_order_id,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => $filter_order_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);
if($data['group']=="11")
{
$filter_data = array(
			'filter_user_id' => $this->user->getId(),
			'filter_order_id'      => $filter_order_id,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => $filter_order_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);


}

		$order_total = $this->model_ccare_ccare->getTotalOrdersCompleted($filter_data);

		$results = $this->model_ccare_ccare->getOrdersCompleted($filter_data);

		foreach ($results as $result) { //print_r($result);
			$data['orders'][] = array(
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'status'        => $result['status'],
                                'telephone'     => $result['telephone'],
                                'store_name'    => $result['store_name'],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $result['shipping_code'],
				'view'          => $this->url->link('sale/orderleads/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'edit'          => $this->url->link('sale/orderleads/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'delete'        => $this->url->link('sale/orderleads/delete', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		


		$data['sort_order'] = $this->url->link('ccare/ccare/completed', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('ccare/ccare/completed', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('ccare/ccare/completed', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
                            $data['sort_store_name'] = $this->url->link('ccare/ccare/completed', 'token=' . $this->session->data['token'] . '&sort=store_name' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('ccare/ccare/completed', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('ccare/ccare/completed', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('ccare/ccare/completed', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('ccare/ccare/completed', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('ccare/ccare.tpl', $data));
	}

protected function getListCustomer() {
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'text' => 'Customer care (Farmers)',
			'href' => $this->url->link('ccare/ccare/customer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
                $this->load->model('user/user');
                
                $data['logged_user_data'] = $this->user->getId();
                $data['current_order_status'] = '5';
		$data['group'] =$this->user->getGroupId();

		$data['orders'] = array();

		$filter_data = array(
			
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);
                if($data['group']=="11")
                {
                 $filter_data = array(
			
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);


                }

		$order_total = $this->model_ccare_ccare->getTotalCustomers($filter_data);

		$results = $this->model_ccare_ccare->getCustomers($filter_data);

		foreach ($results as $result) { //print_r($result);
			$data['orders'][] = array(
				'firstname'      => $result['firstname'],
                                'lastname'      => $result['lastname'],
				'customer_id'      => $result['customer_id'],
				'date_added'        => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                                'telephone'     => $result['telephone'],
                                'store_name'    => $result['store_name'],
				'call_status' => $result['call_status']
				
			);
		}

		$data['heading_title'] = 'Customer care (Farmers)';
		
		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		


		$data['sort_order'] = $this->url->link('ccare/ccare/customer', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('ccare/ccare/customer', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('ccare/ccare/customer', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
                $data['sort_store_name'] = $this->url->link('ccare/ccare/customer', 'token=' . $this->session->data['token'] . '&sort=store_name' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('ccare/ccare/customer', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('ccare/ccare/customer', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('ccare/ccare/customer', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('ccare/ccare/customer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('ccare/ccare_customer.tpl', $data));
	}
public function rechargerecord() {
        $this->load->language('ccare/ccare');

        $this->document->setTitle('Recharge Call');

        $this->load->model('ccare/ccare');

        $this->getListRecharge();
    }
protected function getListRecharge() {
                if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
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
            'text' => 'Recharge Call',
            'href' => $this->url->link('ccare/ccare/rechargerecord', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
                $this->load->model('user/user');
                
                $data['logged_user_data'] = $this->user->getId();
                $data['current_order_status'] = '5';
        $data['group'] =$this->user->getGroupId();

        $data['orders'] = array();

        $filter_data = array(
            'filter_order_id'      => $filter_order_id,
            'filter_customer'       => $filter_customer,
            'filter_order_status'  => $filter_order_status,
            'filter_total'         => $filter_total,
            'filter_date_added'    => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'sort'                 => $sort,
            'order'                => $order,
            'start'                => ($page - 1) * 10,
            'limit'                => 10
        );
if($data['group']=="11")
{
$filter_data = array(
            'filter_user_id' => $this->user->getId(),
            'filter_order_id'      => $filter_order_id,
            'filter_customer'       => $filter_customer,
            'filter_order_status'  => $filter_order_status,
            'filter_total'         => $filter_total,
            'filter_date_added'    => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'sort'                 => $sort,
            'order'                => $order,
            'start'                => ($page - 1) * 10,
            'limit'                => 10
        );


}

        $order_total = $this->model_ccare_ccare->getTotalRechargeCustomers($filter_data);

        $results = $this->model_ccare_ccare->getRechargeCustomers($filter_data);

        foreach ($results as $result) { //print_r($result);
            $data['orders'][] = array(
                    'telephone'      => $result['telephone'],
                                'order_id'      => $result['order_id'],
                'recharge_amount'      => $result['recharge_amount'],
                'recharge_date' => $result['recharge_date'],
                                'call_status'     => $result['call_status'],
                                'STATUS_NAME'    => $result['STATUS_NAME'],
                                 'ResSerSts' => $result['ResSerSts'],
                            'ResRocTransID' => $result['ResRocTransID'],
                            'transid' => $result['transid'],
                            'call_STATUS_NAME'=>$result["call_STATUS_NAME"],
				'scheme_name'=>$result["scheme_name"]
            );
        }

        $data['heading_title'] ='Recharge Record';
        
        $this->load->model('ccare/incommingcall');
                
                $data["callstatus"] = $this->model_ccare_incommingcall->getCallStatus();

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        


        
        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->url = $this->url->link('ccare/ccare/rechargerecord', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

        $data['filter_order_id'] = $filter_order_id;
        $data['filter_customer'] = $filter_customer;
        $data['filter_order_status'] = $filter_order_status;
        $data['filter_total'] = $filter_total;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_modified'] = $filter_date_modified;

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ccare/rechargerecord.tpl', $data));
    }
    public function check_recharge_status()
        {
          $mobile=$this->request->get['mobile'];
          $ResRocTransID=$this->request->get['ResRocTransID'];
          $rtransid=$this->request->get["rtransid"];
          
$surl="https://unnati.world/shop/index.php?route=mpos/recharge/recharge_status&ResRocTransID=".$ResRocTransID."&transid=".$rtransid; 
//echo $surl;

    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$surl);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
   
    $buffer = curl_exec($curl_handle);
    if($buffer === false)
    {
        //echo 'Curl error: ' . curl_error($curl_handle);
                            
    }
    else
    {
        
        //print_r($resstatus);
                echo $buffer;           
    }

curl_close($curl_handle);
//echo "Success";   
exit;
       
        }
        public function send_to_re_recharge()
        {
            $mobile=$this->request->get['mobile'];
            $operator_code=$this->request->get['operator_code'];
            $recharge_amount=$this->request->get['recharge_amount'];
            $transtblid=$this->request->get['transtblid'];
            $rockettranstblid=$this->request->get['rockettranstblid'];
            $pre_post=$this->request->get['pre_post'];
            //$mobile=8010478716;
            //$operator_code=8;
            //$rockettranstblid='';
            //$recharge_amount=10;
        $surl="https://unnati.world/shop/index.php?route=mpos/recharge/recharge_re_hit_get&mobile=".$mobile."&operator_code=".$operator_code."&recharge_amount=".$recharge_amount."&transtblid=".$transtblid."&rockettranstblid=".$rockettranstblid."&success_status=0&pre_post=".$pre_post;
        //echo $surl;
    
    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$surl);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
   
    $buffer = curl_exec($curl_handle);
    if($buffer === false)
    {
        echo 'Curl error: ' . curl_error($curl_handle);
                            
    }
    else
    {
            echo $buffer;           
    }

curl_close($curl_handle);
//echo "Success";   
exit;
        }
        public function submit_recharge_call_data()
        {
           date_default_timezone_set("Asia/Kolkata");
            //$_SERVER["HTTP_REFERER"];
            $order_id=$this->request->get['order_id'];
            $mobile=$this->request->get['mobile_number'];
            $current_call_status=$this->request->get['current_call_status'];/////////old like 0
            $call_status=$this->request->get['call_status'];//////selected like 27
            $farmer_first_name=$this->request->get['farmer_first_name'];
            $farmer_last_name=$this->request->get['farmer_last_name'];
            
            $logged_user_data=$this->request->get['logged_user_data'];
            $remarks=$this->request->get['remarks'];
            $ResRocTransID=$this->request->get["ResRocTransID"];
            $recharge_amount=$this->request->get["recharge_amount"];
            $rtransid=$this->request->get["rtransid"];
            
            $data = array(
            'order_id'             => $order_id,
            'mobile'           => $mobile,
            'call_status'          => $call_status,
            'farmer_first_name'    => $farmer_first_name,
            'ResRocTransID'     => $ResRocTransID,
            'farmer_last_name'     => $farmer_last_name,
                        'current_call_status'  => $current_call_status,
                        'recharge_amount'     => $recharge_amount,
                        'rtransid'     => $rtransid,
                        'logged_user_data'     => $logged_user_data,
                        'remarks' => $remarks,
                        'datetime'             => date('Y-m-d h:i:s')
                       
            
        );
           
            $this->load->model('ccare/ccare');
            //print_r($data);
            $result = $this->model_ccare_ccare->SubmitRechargeCallData($data);   
            header('location: '.$_SERVER["HTTP_REFERER"]);
        }
        public function get_order_info_recharge()
        {
            
            $order_id=$this->request->get['order_id'];
            $mobile=$this->request->get['mobile'];
            $this->load->model('ccare/ccare');
            //$result = $this->model_ccare_ccare->getOrder($order_id);
            //print_r($result);
            //echo $order_id;
            $this->order_info_for_recharge();
            
        }
        function order_info_for_recharge()
        {
            $this->load->model('ccare/ccare');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_ccare_ccare->getOrder_recharge($order_id);

		if ($order_info) {
			
			$data['order_id'] = $this->request->get['order_id'];

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['store_name'] = $order_info['store_name'];
			$data['store_url'] = $order_info['store_url'];
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];

			if ($order_info['customer_id']) {
				$data['customer'] = $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], 'SSL');
			} else {
				$data['customer'] = '';
			}

			$this->load->model('sale/customer_group');

			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['fax'] = $order_info['fax'];
			$data['comment'] = nl2br($order_info['comment']);
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['payment_method'] = $order_info['payment_method'];
			$data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

			$this->load->model('sale/customer');

			$data['reward'] = $order_info['reward'];

			$data['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

			$data['affiliate_firstname'] = $order_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $order_info['affiliate_lastname'];

			if ($order_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], 'SSL');
			} else {
				$data['affiliate'] = '';
			}

			$data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);

			$this->load->model('marketing/affiliate');

			$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);

			$this->load->model('localisation/order_status');

			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

			if ($order_status_info) {
				$data['order_status'] = $order_status_info['name'];
			} else {
				$data['order_status'] = '';
			}

			

			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->model_ccare_ccare->getOrderProducts_recharge($this->request->get['order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_ccare_ccare->getOrderOptions_recharge($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], 'SSL')
							);
						}
					}
				}

				$data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			$data['vouchers'] = array();

			$vouchers = $this->model_ccare_ccare->getOrderVouchers_for_recharge($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'href'        => $this->url->link('sale/voucher/edit', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], 'SSL')
				);
			}

			$totals = $this->model_ccare_ccare->getOrderTotals_recharge($this->request->get['order_id']);

			foreach ($totals as $total) { //print_r($total);
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			$data['order_status_id'] = $order_info['order_status_id'];
                        $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
			$data['date_modified'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));
                        
                        if($order_info['date_potential']=="0000-00-00 00:00:00")
{
$date_potential_temp="00/00/0000";
}
else
{
$date_potential_temp=date($this->language->get('date_format_short'), strtotime($order_info['date_potential']));
}
                        
                        $order_info_return='<div class="tab-pane active" id="tab-order">
            <table class="table table-bordered">
              <tr>
                <td>Order ID:</td>
                <td>'.$order_id.'</td>
              </tr>
             
              <tr>
                <td>Store Name:</td>
                <td>'.$data['store_name'].'</td>
              </tr>
              
              <tr>
                <td>Customer:</td>
                <td>'.$data['firstname'].' '.$data['lastname'].'</td>
              </tr>
             
              <tr>
                <td>Customer Group:</td>
                <td>'.$data['customer_group'].'</td>
              </tr>
              
              <tr>
                <td>E-Mail:</td>
                <td>'.$data['email'].'</td>
              </tr>
              <tr>
                <td>Telephone:</td>
                <td>'.$data['telephone'].'</td>
              </tr>
              
              <tr>
                <td>Fax:</td>
                <td>'.$data['fax'].'</td>
              </tr>
              
              <tr>
                <td>Total:</td>
                <td>'.$data['total'].'</td>
              </tr>
            
              <tr>
                <td>Order Status:</td>
                <td id="order-status">'.$data['order_status'].'</td>
              </tr>
              
              <tr>
                <td>Date Added:</td>
                <td>'.$data['date_added'].'</td>
              </tr>
              
            </table>
          </div>';
          
          $product_info_return_1='<div class="tab-pane" id="tab-product">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td class="text-left">Product</td>
                  <td class="text-left">Model</td>
                  <td class="text-right">Quantity</td>
                  <td class="text-right">Unit Price</td>
                  <td class="text-right">Total</td>
                </tr>
              </thead>
              <tbody>';
                foreach ($products as $product) {
                    
                $prod_qunat_text.='
                <tr>
                  <td class="text-left">'.$product['name'].'</td>
                  <td class="text-left">'.$product['model'].'</td>
                  <td class="text-right">'.$product['quantity'].'</td>
                  <td class="text-right">'.$product['price'].'</td>
                  <td class="text-right">'.$product['total'].'</td>
                </tr>';
                 } 
                foreach ($vouchers as $voucher) {
               $prod_voucher_text.=' <tr>
                  <td class="text-left">'.$voucher['description'].'</td>
                  <td class="text-left"></td>
                  <td class="text-right">1</td>
                  <td class="text-right">'.$voucher['amount'].'</td>
                  <td class="text-right">'.$voucher['amount'].'</td>
                </tr>';
                } 
                    
               foreach ($data['totals'] as $total2) { 
                $prod_total_text.='<tr>
                  <td colspan="4" class="text-right">'.$total2['title'].':</td>
                  <td class="text-right">'.$total2['text'].'</td>
                </tr>';
                 } 
              $product_info_return_2='</tbody>
            </table>
          </div>';
          $product_info_return=$product_info_return_1.$prod_qunat_text.$prod_voucher_text.$prod_total_text.$product_info_return_2;
                        
                        
            echo $order_info_return.'----and----'.$product_info_return.'----and----'.$order_info['call_status'];;            
        }
        }

       public function get_customer_info($mobile)
       {
           //echo $_REQUEST["mobile"];
       }

}