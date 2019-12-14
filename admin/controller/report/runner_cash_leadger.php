<?php
class ControllerReportRunnerCashLeadger extends Controller {
	public function index() 
	{
		$this->load->language('report/product_purchased');

		$this->document->setTitle("Runner's Cash ledger Report");

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

		if (isset($this->request->get['filter_user_id'])) {
			$filter_user_id = $this->request->get['filter_user_id'];
		} else {
			$filter_user_id = null;
		}
		if (isset($this->request->get['filter_tr_type'])) {
			$filter_tr_type = $this->request->get['filter_tr_type'];
		} else {
			$filter_tr_type = null;
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

		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
		if (isset($this->request->get['filter_tr_type'])) {
			$url .= '&filter_tr_type=' . $this->request->get['filter_tr_type'];
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
			'text' => 'Users Cash ledger',
			'href' => $this->url->link('report/runner_cash_leadger', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/cash');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_tr_type'		=>$filter_tr_type,
			'filter_user_id' => $filter_user_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$product_total = $this->model_report_cash->getrunnercashtransTotal($filter_data);
		if(empty($filter_tr_type))
		{
			$data['TotalCR'] = $this->model_report_cash->getrunnerTotalCR($filter_data);
			$data['TotalDB'] = $this->model_report_cash->getrunnerTotalDB($filter_data);
			$data['TotalEXPENSE'] = $this->model_report_cash->getrunnerTotalEXPENSE($filter_data);
		}
		else if($filter_tr_type=='CR')
		{
			$data['TotalCR'] = $this->model_report_cash->getrunnerTotalCR($filter_data);
			$data['TotalDB'] = 0;
			$data['TotalEXPENSE'] =0;
		}
		else if($filter_tr_type=='DB')
		{
			$data['TotalCR'] = 0;
			$data['TotalDB'] = $this->model_report_cash->getrunnerTotalDB($filter_data);
			$data['TotalEXPENSE'] =0;
		}
		else if($filter_tr_type=='EXPENSE')
		{
			$data['TotalCR'] = 0;
			$data['TotalDB'] = 0;
			$data['TotalEXPENSE'] = $this->model_report_cash->getrunnerTotalEXPENSE($filter_data);
		}
		$results = $this->model_report_cash->getrunnercashtrans($filter_data);

		foreach ($results as $result) { //print_r($results);
		if($result['tr_type']=='DR')
		{
			$result['tr_type']='DB';
		}
			$data['products'][] = array(
			'storename'       => $result['storename'],
			'user'       => $result['user'],
				'amount'       => $result['amount'],
				'order_id'      => $result['order_id'],
				'tr_type'   => $result['tr_type'],
				'runner_id'      => $result['runner_id'],
				'username'      => $result['username'],
                            'create_time'      => $result['create_time'],
                            
		'updated_cash'=>$result['updated_cash'],
		'remarks'=>$result['remarks']
                                
			);
		}

		$data['heading_title'] = "Runner's Cash ledger";
		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/inventory_ledger');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');

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
		
		$data['users'] = $this->model_report_cash->getAllRunners(); 
		
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}
		if (isset($this->request->get['filter_tr_type'])) {
			$url .= '&filter_tr_type=' . $this->request->get['filter_tr_type'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/runner_cash_leadger', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_user_id'] = $filter_user_id;
		$data['filter_tr_type'] = $filter_tr_type;
		
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/runner_cash_leadger.tpl', $data)); 
	}
	public function download_excel() 
	{
		
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

		if (isset($this->request->get['filter_stores_id'])) {
			$filter_stores_id = $this->request->get['filter_stores_id'];
		} else {
			$filter_stores_id = 0;
		}
		if (isset($this->request->get['filter_user_id'])) {
			$filter_user_id = $this->request->get['filter_user_id'];
		} else {
			$filter_user_id = null;
		}
		
		$this->load->model('report/cash');

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_tr_type'		=>$filter_tr_type,
			'filter_user_id' => $filter_user_id
		);

		$results = $this->model_report_cash->getrunnercashtrans($filter_data);

		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Runner Name',
        'Runner ID',
        'Runner Username',
        'Amount',
		'Order Id',
		'CR_DB',
		'Current Cash',
		'Trans Date',
		'Remarks'
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
		if($data['tr_type']=='DR')
		{
			$data['tr_type']='DB';
		}
		if($data['tr_type']=='DB')
		{
			$data['tr_type']='Accepted by Account';
		}
		if($data['tr_type']=='CR')
		{
			$data['tr_type']='Accepted from store';
		}
		$col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['user']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['runner_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['username']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['amount']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['order_id']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['tr_type']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['updated_cash']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['create_time']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['remarks']);
        $row++;
    }

    

    
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="runner_cash_ledger_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
	public function get_store_users()
	{
		$store_id=$this->request->get['store_id'];
		$this->load->model('report/cash');
		$results = $this->model_report_cash->get_store_users($store_id);
		//print_r($results);
		echo '<option value="0">Select User</option>';
		foreach($results as $user)
		{
			
                  echo '<option value="'.$user['user_id'].'">'.$user['name'].'</option>';
                  
		}
	}
}