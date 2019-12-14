<?php
class ControllerCcareIncommingcall extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('ccare/ccare');

		$this->document->setTitle('Incomming call');

		$this->load->model('ccare/incommingcall');

		$this->getList();
	}
        
        public function submit_call_data()
        {
            date_default_timezone_set("Asia/Kolkata");
            //$_SERVER["HTTP_REFERER"];
            
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
            
            $query=$this->request->get['query'];
            $solution=$this->request->get['solution'];
            $transid=$this->request->get['transid'];
            $data = array(
			
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
                        'query'                => $query,
                        'solution'             => $solution,
                        'logged_user_data'     => $logged_user_data,
                       
                        'datetime'             => date('Y-m-d h:i:s'),
                        'buying_date'          => $buying_date,
                        'transid'              => $transid
			
		);
            
           ///var/www/html/shop/admin/model/ccare/incommingcall.php
            $this->load->model('ccare/incommingcall');
           
            $result = $this->model_ccare_incommingcall->SubmitCallData($data); 
            //exit;
            header('location: '.$_SERVER["HTTP_REFERER"]);
        }

public function getrechargelist()
{
$mobile=$this->request->get['mobile'];

$this->load->model('ccare/incommingcall');
$recharged=array();
$recharged = $this->model_ccare_incommingcall->getrechargedtl($mobile);

$rech="";
foreach ($recharged as $recharge) {

$rech.='<tr><td class="text-center">'.$recharge["mobile"].'</td><td class="text-center">'.$recharge["recharge_amount"].'</td><td class="text-center">'.ucfirst($recharge["ResSerSts"]).'</td> <td class="text-center">'.date('d/m/Y',strtotime($recharge["create_date"])).'</td></tr>';

}
if(count($recharged)==0)
{
 $rech.="<tr><td colspan='4' class='text-center'>No record found</td></tr> ";
}
echo $rech;exit;
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
                 
                <td class="text-left">Store Name</td>
                <td class="text-right">Product Name </td>
                <td class="text-right">Quantity</td>
                <td class="text-right">Price</td> 
                <td class="text-right">Amount</td>
                </tr>
              </thead>
              <tbody>';
             //print_r($products);
           $prod="";
                foreach ($products as $product) {
                  
                   $prod.='<tr><td class="text-left">'.$product["store_name"].'</td><td class="text-left">'.$product["product_name"].'</td><td class="text-right">'.$product["qnty"].'</td> <td class="text-right">'.$product["price"].'</td><td class="text-right">'.$product["price"].'</td></tr>';
               
                 } 
                 
                 $prod2="";
                foreach ($vouchers as $voucher) {
               $prod2.=' <tr><td class="text-left">'.$voucher["description"].'</td>
                  <td class="text-left"></td>
                  <td class="text-right">1</td>
                  <td class="text-right">'.$voucher["amount"].'</td>
                  <td class="text-right">'.$voucher["amount"].'</td>
                </tr>';
                } 
                
                 $prod3="";    
               foreach ($data['totals'] as $total2) { 
                $prod3.='<tr>
                  <td colspan="4" class="text-right">'.$total2["title"].':</td>
                  <td class="text-right">'.$total2["text"].'</td>
                </tr>';
                 } 
                
              $product_info_return_2='</tbody>
            </table>
          </div>';
          $product_info_return=$product_info_return_1.$prod.$prod2.$prod3.$product_info_return_2;
                  
              $abc = $order_info_return.'----and----'.$product_info_return.'----and----'.$order_info['call_status'];          
              echo $abc;
              
              
            //echo $order_info_return.'----and----'.$product_info_return.'----and----'.$order_info['call_status'];           
        }
            
        }
        function productinfo()
{
$product_id=$this->request->get['product_id'];
$filter_store=$this->request->get['filter_store'];
$this->load->model("ccare/incommingcall");
$filter_data=array(
'filter_name_id'=> $product_id,
'filter_store'=> $filter_store,
'start'=>0,
'limit'=>100

);

$inventory = $this->model_ccare_incommingcall->getInventory_reportProductWise($filter_data);
//print_r($inventory);
$tble='<table class="table table-bordered">
<thead>
<tr>
<td class="text-left">SI ID </td>
<td class="text-left">Store Name </td>
<td class="text-right">Product Name </td>
<td class="text-right">Qnty </td>
<td class="text-right">Price</td>

</tr>
</thead><tbody>';

if ($inventory){ $aa=1;
foreach ($inventory as $order) {
$tble.='<tr>
<td class="text-left">'.$aa.'</td>

<td class="text-left">'.$order["store_name"].'</td>
<td class="text-right">'.$order["Product_name"].'</td>
<td class="text-right">'.$order["Qnty"].'</td>
<td class="text-right">'.round($order["price"]).'</td>

</tr>';

$aa++;
}
} else {


//$tble.='<tr><td>This product is not available anywhere</td></tr>';
}
$tble.='</tbody></table>';
echo $tble;
exit;

}
       function cropprodinfo()
        {
           
         $crop_id=$this->request->get['crop_id'];
       
         $this->load->model("catalog/product");
         $products = $this->model_catalog_product->getProductsRelatedToCropdtl(array(
            
                                          'filter_crop_id'        =>$crop_id ///'12'  // 
        ));
         $alldata=array();
         array_push($alldata,$products[0]["complete"]); 
         array_push($alldata,$products[0]["break"]);
          $alldata=   $products[0]["complete"]."-----and----".$products[0]["break"];
        echo $alldata;
        
        }
        function getstorelocation()
        {
            $dist_id=$this->request->get['distid'];
            $this->load->model("ccare/incommingcall");
            $store = $this->model_ccare_incommingcall->getStoreLocationdtl($dist_id);
            //print_r($store);
            $tble='<table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Store ID </td>
                <td class="text-left">Store Name </td>
                <td class="text-right">Location</td>
              </tr>
            </thead><tbody>';
             
              if ($store){  $aa=1;
              foreach ($store as $order) {
              $tble.='<tr>
                <td class="text-left">'.$order["store_id"].'</td>
                <td class="text-right">'.$order["name"].'</td>
                <td class="text-right">'.$order["address"].'</td>
               
              </tr>';
              
              $aa++;
              } 
               } else { 
                   
               
              //$tble.='<tr><td>This product is not available anywhere</td></tr>';
              } 
            $tble.='</tbody></table>';
echo $tble;
exit;
          
        }

	protected function getList() {
		if (isset($this->request->get['filter_start_date'])) {
			$filter_start_date = $this->request->get['filter_start_date'];
		} else {
			$filter_start_date = null;
		}

		if (isset($this->request->get['filter_end_date'])) {
			$filter_end_date = $this->request->get['filter_end_date'];
		} else {
			$filter_end_date = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_number'])) {
			$filter_number = $this->request->get['filter_number'];
		} else {
			$filter_number = null;
		}
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
		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}
		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_number'])) {
			$url .= '&filter_number=' . $this->request->get['filter_number'];
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
			'text' => 'Incomming Call',
			'href' => $this->url->link('ccare/incommingcall', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
                $this->load->model('user/user');
                $this->load->model('setting/store');
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
			'filter_start_date'=>$filter_start_date,
			'filter_end_date'=>$filter_end_date,
			'filter_status'=>$filter_status,
			'filter_number'=>$filter_number,
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
			'filter_start_date'=>$filter_start_date,
			'filter_end_date'=>$filter_end_date,
			'filter_status'=>$filter_status,
			'filter_number'=>$filter_number,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);


}
		//print_r($filter_data);

		$order_total = $this->model_ccare_incommingcall->getAllIncomingCall($filter_data);

		$results = $this->model_ccare_incommingcall->getIncomingCall($filter_data);

		foreach ($results as $result) { //print_r($result);
			$data['orders'][] = array(
				'transid'        => $result['transid'],
				'status'        => $result['status'],
                                'mobile'     => $result['mobile'],
                                'state_name'    => $result['state_name'],
				'status_id' => $result["status_id"],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['datereceived']))
				
			);
		}
               $cropresults = $this->model_ccare_incommingcall->getCrop($filter_data);

		foreach ($cropresults as $result) { //print_r($result);
			$data['crops'][] = array(
				'id'      => $result['id'],
				'crop_name'  => $result['name']
                               
                                
			);
		}
		$data['stores'] = $this->model_setting_store->getStores();//print_r($data['stores'] ); 
		$data['heading_title'] = 'Incomming Call';
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

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}
		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_number'])) {
			$url .= '&filter_number=' . $this->request->get['filter_number'];
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
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('ccare/incommingcall', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('ccare/incommingcall/report');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$data['filter_start_date'] = $filter_start_date;
		$data['filter_end_date'] = $filter_end_date;
		$data['filter_number'] = $filter_number;
		$data['filter_status'] = $filter_status;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('ccare/incommingcall.tpl', $data));
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

		$this->response->setOutput($this->load->view('ccare/incomming.tpl', $data));
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

       public function get_customer_info($mobile)
       {
           //echo $_REQUEST["mobile"];
       }
	public function report() {
		$this->load->language('report/customer_activity');

		$this->document->setTitle('Incoming Call Summary Report');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m').'-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('report/incommingcall/report', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text' => 'Incoming Call Report'
		);

		$this->load->model('ccare/incommingcall');
                            //echo "here";
		$data['activities'] = array();

		$filter_data = array(			
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'             => ($page - 1) * 20,
			'limit'             => 20
		);

		$activity_total = $this->model_ccare_incommingcall->getTotalincomingcall_answer($filter_data);

		$results = $this->model_ccare_incommingcall->incomingcall_answer($filter_data);

		foreach ($results as $result) { //print_r($result);
			
			$data['activities'][] = array(
				'mobile'              => $result['mobile'],
				'STATUS_NAME'         => $result['STATUS_NAME'],
				'Acres'               => $result['Acres'],
                               		'query'               =>$result['query'],
				'transid'               =>$result['transid'],
                                		'solution'            =>$result['solution'],
				'datediscussed'            =>$result['datediscussed']
			);
		}

		$data['heading_title'] ='Incomming Call Answer Report';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$url = '';


		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $activity_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('ccare/incommingcall/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($activity_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($activity_total - $this->config->get('config_limit_admin'))) ? $activity_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $activity_total, ceil($activity_total / $this->config->get('config_limit_admin')));

		$data['filter_customer'] = $filter_customer;
		$data['filter_ip'] = $filter_ip;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('ccare/incommingcall_answer_reprt.tpl', $data));
	}

public function download_reports()
{
            
		$this->load->model('ccare/incommingcall');
            

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


		

		$data['orders'] = array();

		$filter_data = array(			
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end
		);

		

		$results = $this->model_ccare_incommingcall->incomingcall_answer($filter_data);

		

    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Trans ID',
        'Mobile',
        'Status',
        'Acres',
        'Query',
        'Solution',
	'Date Discussed'
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
    
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['transid']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['mobile']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['STATUS_NAME']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Acres']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['query']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['solution']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['datediscussed']);
         

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Call_report_incoming_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        } 


}