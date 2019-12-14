<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerCashVerify extends Controller {
	public function index() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Cash List');

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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/cash_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                $this->load->model('cash/verify');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		 $totalsss=$this->model_cash_verify->getTotalCash_transation($filter_data);
                            $data["total_amount"]=$totalsss["total_amount"];
		$order_total = $totalsss["total"];//$array[0];

		$data['orders'] = array();

		$results = $this->model_cash_verify->getCash_report($filter_data);
                
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				'SIID' => $result['transid'],
				'amount'   => $result['amount'],
				'store_id'      => $result['store_id'],
				'name'     => $result['name'],
                                'status'     => $result['status'],
                                'date_added'=>  date($this->language->get('date_format_short'), strtotime($result['date_added'])),   
				'bank_name'      => $result['bank_name']
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		

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
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('cash/verify', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cash/cash_report.tpl', $data));
	}
        
 public function verify_runner_deposit() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Cash Deposited by CE Verification');

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
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
                            if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
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
        if (isset($this->request->get['filter_store'])) 
		{
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['page'])) 
		{
			$url .= '&page=' . $this->request->get['page'];
		}
        if (isset($this->request->get['filter_user'])) 
		{
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_status'])) 
		{
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Cash Deposited by CE Verification',
			'href' => $this->url->link('cash/verify/verify_list', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                $this->load->model('cash/verify');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store,
            'filter_user'	     => $filter_user,
			'filter_status'	     => $filter_status,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$this->load->model('runner/cash');
		
		$data['runner_list']=$this->model_runner_cash->getAllCe(array());
                $totalsss=$this->model_cash_verify->getTotalCash_transationByRunner($filter_data); 
                $data["total_amount"]=$totalsss["total_amount"];
		 $order_total = $totalsss["total"];//$array[0];

		$data['orders'] = array();

		$results = $this->model_cash_verify->getCash_reportByRunner($filter_data);
                     $mcrypt=new MCrypt(); 
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				
				
				'amount'      => $result['amount'],
				'bank'     => $result['bank_name'],
                                'branch'      => $result['branch'],
                                'deposit_date'=>  date($this->language->get('date_format_short'), strtotime($result['deposit_date'])),   
		    'deposited_by'     => $result['deposited_by'],
                                'submit_date'   => date($this->language->get('date_format_short'), strtotime($result['deposit_date'])),
                                'filled_user'   => $result['firstname']." ".$result['lastname'],
                                'transaction_number'   => $result['transaction_number'],
                                'remarks'   => $result['remarks'],
		'tr_id'   => $mcrypt->encrypt($result['SID']),
		'status' => $result["status"],
		'runner_id'=> $result["user_id"],
                            'SID'=> $result["SID"],
                             'uploded_file'=> $result["uploded_file"]
                                
			);
		}

		$data['heading_title'] = 'Cash Deposited by CE Verification';
		
		

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		

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
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
        if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_status'] = $filter_status;
                            $data['filter_user'] = $filter_user;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
 $this->load->model('user/user');
		$data['download_link']=$this->url->link('cash/verify/pic_download', 'token=' . $this->session->data['token'], 'SSL');
        $data["logged_user"] = $mcrypt->encrypt($logged_user_data = $this->user->getId());
		$this->response->setOutput($this->load->view('cash/verified_report_runner.tpl', $data));
	}
        
/////////////////////////////////
public function verify_runner_deposit_download() {
		
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
                		if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
                           $this->load->model('cash/verify');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_user'	     => $filter_user,
			'filter_status'=>$filter_status
		);
               

		$data['orders'] = array();
                       $results = $this->model_cash_verify->getCash_reportByRunner($filter_data);
                      
		
		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Trans ID',
        'Deposit Amount',
        'Bank',
      
        'Deposit Date',
        'Deposit by',
        'Transaction Number',
        'Remarks',
        'Status'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    // Fetching the table data
   
    $row = 2;
    
    foreach($results as $data)
    { 
        $col = 0;
        if($data["status"]=="1") { $status="Accepted"; } else if($data["status"]=="2") { $status="Rejected"; } else { $status="Deposit by Runner"; }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['SID']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['amount']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['bank_name']);
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['branch']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date($this->language->get('date_format_short'), strtotime($data['deposit_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['firstname']." ".$data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['transaction_number']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['remarks']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $status);
        
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Cash_Deposited_by_CE'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
         
       ////////////
public function check_bank_tr_number()
{
	$bank_tr_number=$this->request->get['bank_tr_number'];
	$mcrypt=new MCrypt();
	$tr_id=$mcrypt->decrypt($this->request->get['tr_id']); 
	$this->load->model('cash/verify');
	echo $oldstatus=$this->model_cash_verify->check_bank_tr_number($tr_id,$this->request->get['bank_tr_number']);
}	
///////////////////////////////
	public function accept_cash_by_account()
	{
            $this->load->model('cash/verify');
            $this->load->model('runner/cash');
            $this->load->model('user/user');
            $mcrypt=new MCrypt();
            if(($this->request->get['logged_user'])== ($mcrypt->encrypt($logged_user_data = $this->user->getId())))
			{

                  if (($this->request->get['tr_id'] != '') && ($this->request->get['logged_user'] != ''))
                  { 
					$tr_id=$mcrypt->decrypt($this->request->get['tr_id']); 
					$logged_user=$mcrypt->decrypt($this->request->get['logged_user']); 
					$oldstatus=$this->model_cash_verify->accept_reject_cash($tr_id,$logged_user,"1",$this->request->get['bank_tr_number']);
					if($oldstatus=='0')
					{
						$this->model_runner_cash->add_to_runner_debit($this->request->get['runner_id'],$this->request->get['amount']); 
						$this->model_runner_cash->add_to_trans_table($tr_id,$this->request->get['runner_id'],"DB",$this->request->get['amount']);
						$this->session->data['success'] = 'Transaction Accepted Successfully';
						header('location: '.$_SERVER['HTTP_REFERER']);
					}
					else
					{
						if($oldstatus=='1')
						{
							$this->session->data['success'] = 'Transaction is allready accepted !';	
							$this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
						}
						if($oldstatus=='2')
						{
							$this->session->data['success'] = 'Transaction is allready rejected !';	
							$this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
						}
					}
                    //$this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 }
		}
		else
		{ 
            $this->session->data['success'] = 'Unknown User !';	
			$this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	}


	public function reject_cash_by_account()
	{
	 $this->load->model('user/user');
  $mcrypt=new MCrypt();
                           if($this->request->get['logged_user']== $mcrypt->encrypt($logged_user_data = $this->user->getId()))
		{
	$this->load->model('cash/verify');
             
                  if (($this->request->get['tr_id'] != '') && ($this->request->get['logged_user'] != ''))
                  { 
		 
                            $tr_id=$mcrypt->decrypt($this->request->get['tr_id']); 
	 $logged_user=$mcrypt->decrypt($this->request->get['logged_user']); 
                   $this->model_cash_verify->accept_reject_cash($tr_id,$logged_user,"2",'');
                   
                    $this->session->data['success'] = 'Transaction Rejected Successfully';
		header('location: '.$_SERVER['HTTP_REFERER']);
                 //  $this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 }
	}
              else
	{
              $this->session->data['success'] = 'Unknown User !';	
	 $this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	}

////////////////////////////////////////////////////////////////


/////////////////////
	 public function verify_list() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Cash Verified List');

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
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Cash Verified List',
			'href' => $this->url->link('cash/verify/verify_list', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                $this->load->model('cash/verify');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
                $totalsss=$this->model_cash_verify->getTotalCash_transationVerified($filter_data);
                $data["total_amount"]=$totalsss["total_amount"];
		$order_total = $totalsss["total"];//$array[0];

		$data['orders'] = array();

		$results = $this->model_cash_verify->getCash_reportVerified($filter_data);
                
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				
				
				'store_id'      => $result['store_id'],
				'store_name'     => $result['name'],
                                'bank_name'      => $result['bank_name'],
                                'deposit_date'=>  date($this->language->get('date_format_short'), strtotime($result['deposit_date'])),   
				'transaction_number'     => $result['transaction_number'],
                                'amount'   => $result['amount'],
                                'branch_code'   => $result['branch_code'],
                                'branch_location'   => $result['branch_location'],
                                'remarks'   => $result['remarks'],
		'Verified_By'   => $result['firstname']." ".$result['lastname']
                                
			);
		}

		$data['heading_title'] = 'Cash Verified List';
		
		

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		

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
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('cash/verify/verify_list', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cash/verified_report.tpl', $data));
	}
public function verify_list_download() {
		
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
                $this->load->model('cash/verify');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store
		);
                $totalsss=$this->model_cash_verify->getTotalCash_transationVerified($filter_data);
                $data["total_amount"]=$totalsss["total_amount"];
		$order_total = $totalsss["total"];//$array[0];

		$data['orders'] = array();

		$results = $this->model_cash_verify->getCash_reportVerified($filter_data);
                
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				
				
				'store_id'      => $result['store_id'],
				'store_name'     => $result['name'],
                                'bank_name'      => $result['bank_name'],
                                'deposit_date'=>  date($this->language->get('date_format_short'), strtotime($result['deposit_date'])),   
				'transaction_number'     => $result['transaction_number'],
                                'amount'   => $result['amount'],
                                'branch_code'   => $result['branch_code'],
                                'branch_location'   => $result['branch_location'],
                                'remarks'   => $result['remarks'],
                                'Verified_By'   => $result['firstname']." ".$result['lastname']
                                
			);
		}

		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store ID',
        'Store Name',
        'Transaction Type',
        'Deposit Date',
        'Transaction Number',
        'Deposit Amount',
        'Branch Code',
        'Branch Location',
        'Verified by',
        'Remarks'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    // Fetching the table data
    //$this->load->model('report/searchattendance');
    //$results = $this->model_report_searchattendance->getmdoattendance($filter_data);
    
    $row = 2;
    
    foreach($results as $data)
    { 
        $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['bank_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date($this->language->get('date_format_short'), strtotime($data['deposit_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['transaction_number']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['amount']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['branch_code']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['branch_location']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['firstname']." ".$data["lastname"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['remarks']);
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="cash_veriefied_list'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
        public function get_store_data()
        {
           $this->load->language('report/cash_report');
            //$_REQUEST["store_id"];
            $this->load->model('cash/verify');
            $getstoresdata = $this->model_cash_verify->getstoresdata($_REQUEST["store_id"]);
            echo $getstoresdata["currentcredit"];

        }
         public function verify() {
             
             $this->document->setTitle('Verify Cash');
             
             
             $this->load->model('cash/verify');
             
             if ($this->request->server['REQUEST_METHOD'] == 'POST')
             { 
                 $this->model_cash_verify->verify_cash($this->request->post);
                 $this->model_cash_verify->insert_into_store_trans($this->request->post);
                 $this->session->data['success'] = 'Transaction Verified Successfully';
                 $this->response->redirect($this->url->link('cash/verify/verify', 'token=' . $this->session->data['token'] . $url, 'SSL'));
             }
		

		
                
             $this->getForm();
         }
         protected function getForm() {
             $SID = $this->request->get["SID"];
	     $filter_data = array(
			'transid'	     => $SID
		);	
                $results = $this->model_cash_verify->getCash_record($filter_data);
                $getTransactionTypesresults = $this->model_cash_verify->getTransactionTypes();
                
                
                $this->load->model('user/user');
                $this->load->model('setting/store');
                $user_data = $this->model_user_user->getUser($results["user_id"]);
                
                //print_r($results);
                $logged_user_data = $this->user->getId();
                
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['deposit_date'])) {
			$data['error_deposit_date'] = $this->error['deposit_date'];
		} else {
			$data['error_deposit_date'] = '';
		}

		

		$url = '';

		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Verify Cash',
			'href' => $this->url->link('cash/verify', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		

		$data['cancel'] = $this->url->link('cash/verify', 'token=' . $this->session->data['token'] . $url, 'SSL');
                $data['submit_by'] =$user_data["firstname"]." ".$user_data["lastname"];
		$data['bank_name'] = $results["bank_name"];
                $data['amount'] = $results["amount"];
                $data['store_name'] = $results["name"];
                $data['store_id'] = $results["store_id"];
                $data['creditlimit'] = $results["creditlimit"];
                $data['currentcredit'] = $results["currentcredit"];
                $data['transid'] = $this->request->get["SID"];
                $data['date_added'] = $results["date_added"];
                $data['verify_date'] = $results["deposit_date"];
                $data['transaction_number'] = $results["transaction_number"];
                $data['branch_code'] = $results["branch_code"];
                $data['branch_location'] = $results["branch_location"];
                $data['remarks'] = $results["remarks"];
                $data['stores'] = $this->model_setting_store->getStores();
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
                $data['action'] = $this->url->link('cash/verify/verify', 'token=' . $this->session->data['token'] . $url.'&SID='.$this->request->get["SID"], 'SSL');
		$this->response->setOutput($this->load->view('cash/verify_form.tpl', $data));
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

	/////////////////cash new start here///////////

public function verify_runner_deposit_mid() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Cash Deposited by CE Verification');

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
                            if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
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
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
                            if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Cash Deposited by CE Verification',
			'href' => $this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                $this->load->model('cash/verify');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store,
                                          'filter_user'	     => $filter_user,
			'filter_status'=>$filter_status,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		$this->load->model('runner/cash');
		$data['runner_list']=$this->model_runner_cash->getAllCe(array());
                $totalsss=$this->model_cash_verify->getTotalCash_transationByRunner_mid($filter_data); 
                $data["total_amount"]=$totalsss["total_amount"];
		$order_total = $totalsss["total"];//$array[0];

		$data['orders'] = array();

		$results = $this->model_cash_verify->getCash_reportByRunner_mid($filter_data); 
                     $mcrypt=new MCrypt(); 
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				
				
				'amount'      => $result['amount'],
				'bank'     => $result['bank_name'],
                                'branch'      => $result['branch'],
                                'deposit_date'=>  date($this->language->get('date_format_short'), strtotime($result['date_added'])),   
		    'deposited_by'     => $result['deposited_by'],
                                'submit_date'   => date($this->language->get('date_format_short'), strtotime($result['date_updated'])),
                                'filled_user'   => $result['firstname']." ".$result['lastname'],
                                'transaction_number'   => $result['transaction_number'],
                                'remarks'   => $result['remarks'],
		'tr_id'   => $mcrypt->encrypt($result['transid']),
		'status' => $result["status"],
		'runner_id'=> $result["accept_by"],
                            'SID'=> $result["transid"],
		'store_name'=> $result["store_name"],
                             'uploded_file'=> $result["cash_slip"]
                                
			);
		}

		$data['heading_title'] = 'Cash Deposited by CE Verification';
		
		

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		

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
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                            if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('cash/verify/verify_runner_deposit_mid', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_status'] = $filter_status;
		$data['filter_store'] = $filter_store;
                            $data['filter_user'] = $filter_user;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
 $this->load->model('user/user');
  
                            $data["logged_user"] = $mcrypt->encrypt($logged_user_data = $this->user->getId());
		$this->response->setOutput($this->load->view('cash/verified_report_runner_mid.tpl', $data));
	}
	/*
	public function accept_cash_by_account()
	{
                  $this->load->model('cash/verify');
                  $this->load->model('runner/cash');
             	    $this->load->model('user/user');

                  $mcrypt=new MCrypt();
                           if(($this->request->get['logged_user'])== ($mcrypt->encrypt($logged_user_data = $this->user->getId())))
		{

                  if (($this->request->get['tr_id'] != '') && ($this->request->get['logged_user'] != ''))
                  { 
		 
                            $tr_id=$mcrypt->decrypt($this->request->get['tr_id']); 
	     $logged_user=$mcrypt->decrypt($this->request->get['logged_user']); 
                   $this->model_cash_verify->accept_reject_cash($tr_id,$logged_user,"3",$this->request->get['bank_tr_number']);
		
                   $this->model_runner_cash->add_to_trans_table($tr_id,$this->request->get['runner_id'],"DB",$this->request->get['amount']);
	     $this->model_runner_cash->add_to_runner_debit($this->request->get['runner_id'],$this->request->get['amount']);

                    $this->session->data['success'] = 'Transaction Accepted Successfully';
                    header('location: '.$_SERVER['HTTP_REFERER']);
                    //$this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 }
	}
	else
	{ 
              $this->session->data['success'] = 'Unknown User !';	
	 $this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	}
	public function reject_cash_by_account()
	{
	 $this->load->model('user/user');
  $mcrypt=new MCrypt();
                           if($this->request->get['logged_user']== $mcrypt->encrypt($logged_user_data = $this->user->getId()))
		{
	$this->load->model('cash/verify');
             
                  if (($this->request->get['tr_id'] != '') && ($this->request->get['logged_user'] != ''))
                  { 
		 
                            $tr_id=$mcrypt->decrypt($this->request->get['tr_id']); 
	 $logged_user=$mcrypt->decrypt($this->request->get['logged_user']); 
                   $this->model_cash_verify->accept_reject_cash($tr_id,$logged_user,"2",'');
                   
                    $this->session->data['success'] = 'Transaction Rejected Successfully';
		header('location: '.$_SERVER['HTTP_REFERER']);
                 //  $this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 }
	}
              else
	{
              $this->session->data['success'] = 'Unknown User !';	
	 $this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	}
*/
////////////////////////////////////////////////////////////////
/////////////////////////////////
public function verify_runner_deposit_download_mid() {
		
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
                		if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
                           $this->load->model('cash/verify');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_user'	     => $filter_user,
			'filter_status'=>$filter_status
		);
               

		$data['orders'] = array();
                       $results = $this->model_cash_verify->getCash_reportByRunner_mid($filter_data);
                      
		
		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Trans ID',
        'Deposit Amount',
        'Bank',
       
        'Deposit Date',
        'Deposit by',
        'Store',
      
        'Status'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    // Fetching the table data
    //$this->load->model('report/searchattendance');
    //$results = $this->model_report_searchattendance->getmdoattendance($filter_data);
    
    $row = 2;
    
    foreach($results as $data)
    { 
        $col = 0;
        if($data["status"]=="3") { $status="Accepted"; } else if($data["status"]=="2") { $status="Rejected"; } else if($data["status"]=="0"){ $status="Pending at Runner"; } else if($data["status"]=="1"){ $status="Deposit by Runner"; } 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['transid']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['amount']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['bank_name']);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date($this->language->get('date_format_short'), strtotime($data['date_updated'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['firstname']." ".$data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['store_name']);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $status);
        
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Cash_Deposited_by_CE'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}

	public function amount_with_account() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Cash Deposited by CE Verification');

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
                            if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
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
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
                            if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Cash Deposited by CE Verification',
			'href' => $this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                $this->load->model('cash/verify');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store,
                                          'filter_user'	     => $filter_user,
			'filter_status'=>$filter_status,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$this->load->model('runner/cash');
		$data['runner_list']=$this->model_runner_cash->getAllCe(array());
                $totalsss=$this->model_cash_verify->getTotalCash_transationByRunner($filter_data); 
                $data["total_amount"]=$totalsss["total_amount"];
		$order_total = $totalsss["total"];//$array[0];

		$data['orders'] = array();

		$results = $this->model_cash_verify->getCash_reportByRunner($filter_data); 
                     $mcrypt=new MCrypt(); 
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				
				
				'amount'      => $result['amount'],
				'bank'     => $result['bank_name'],
                                'branch'      => $result['branch'],
                                'deposit_date'=>  date($this->language->get('date_format_short'), strtotime($result['date_added'])),   
		    'deposited_by'     => $result['deposited_by'],
                                'submit_date'   => date($this->language->get('date_format_short'), strtotime($result['date_updated'])),
                                'filled_user'   => $result['firstname']." ".$result['lastname'],
                                'transaction_number'   => $result['transaction_number'],
                                'remarks'   => $result['remarks'],
		'tr_id'   => $mcrypt->encrypt($result['transid']),
		'status' => $result["status"],
		'runner_id'=> $result["accept_by"],
                            'SID'=> $result["transid"],
		'store_name'=> $result["store_name"],
                             'uploded_file'=> $result["cash_slip"]
                                
			);
		}

		$data['heading_title'] = 'Cash Deposited by CE Verification';
		
		

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		

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
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                            if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
                            $data['filter_user'] = $filter_user;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
 $this->load->model('user/user');
  
                            $data["logged_user"] = $mcrypt->encrypt($logged_user_data = $this->user->getId());
		$this->response->setOutput($this->load->view('cash/amount_with_account.tpl', $data));
	}
///////////////////////////////

	/////////////////letter new start here///////////
public function verify_runner_tagged() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Tagged Deposited by CE Verification');

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
                            if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
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
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
                            if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Cash Deposited by CE Verification',
			'href' => $this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                $this->load->model('cash/verify');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store,
                                          'filter_user'	     => $filter_user,
			'filter_status'=>$filter_status,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$this->load->model('runner/cash');
		$data['runner_list']=$this->model_runner_cash->getAllCe(array());
                $totalsss=$this->model_cash_verify->getTotalCash_transationByRunner($filter_data); 
                $data["total_amount"]=$totalsss["total_amount"];
		$order_total = $totalsss["total"];//$array[0];

		$data['orders'] = array();

		$results = $this->model_cash_verify->getCash_reportByRunner($filter_data); 
                     $mcrypt=new MCrypt(); 
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				
				
				'amount'      => $result['amount'],
				'bank'     => $result['bank_name'],
                                'branch'      => $result['branch'],
                                'deposit_date'=>  date($this->language->get('date_format_short'), strtotime($result['date_added'])),   
		    'deposited_by'     => $result['deposited_by'],
                                'submit_date'   => date($this->language->get('date_format_short'), strtotime($result['date_updated'])),
                                'filled_user'   => $result['firstname']." ".$result['lastname'],
                                'transaction_number'   => $result['transaction_number'],
                                'remarks'   => $result['remarks'],
		'tr_id'   => $mcrypt->encrypt($result['transid']),
		'status' => $result["status"],
		'runner_id'=> $result["accept_by"],
                            'SID'=> $result["transid"],
		'store_name'=> $result["store_name"],
                             'uploded_file'=> $result["cash_slip"]
                                
			);
		}

		$data['heading_title'] = 'Cash Deposited by CE Verification';
		
		

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		

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
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                            if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_status'] = $filter_status;
		$data['filter_store'] = $filter_store;
                            $data['filter_user'] = $filter_user;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
 $this->load->model('user/user');
  
                            $data["logged_user"] = $mcrypt->encrypt($logged_user_data = $this->user->getId());
		$this->response->setOutput($this->load->view('cash/verified_report_runner.tpl', $data));
	}
	public function  pic_download() 
	{
		echo $file=DIR_UPLOAD.'cashslips/'.$this->request->get['filename'];
		if (file_exists($file)) 
		{ 
		header('Content-Description: File Transfer');
		header('Content-Type: application/vnd.ms-excel; charset=utf-8');
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		readfile($file);
		exit;
	}
	else
	{
		echo 'file not found !';
	}
		
	}
}