<?php
class ControllerReportUsersCashLeadger extends Controller {
	public function index() 
	{
		$this->load->language('report/product_purchased');

		$this->document->setTitle("Users Cash Ledger Report");

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

		if (isset($this->request->get['filter_stores_id'])) {
			$url .= '&filter_stores_id=' . $this->request->get['filter_stores_id'];
		}
		
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
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
			'text' => 'Users Cash Ledger',
			'href' => $this->url->link('report/user_cash_leadger', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/product');
                $this->load->model('report/cash');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id,
			'filter_user_id' => $filter_user_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$product_total = $this->model_report_cash->getcashtransTotal($filter_data);

		$results = $this->model_report_cash->getcashtrans($filter_data);

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
				'payment_method'      => $result['payment_method'],
                            'create_time'      => $result['create_time'],
                            
		'updated_cash'=>$result['updated_cash'],
		'remarks'=>$result['remarks']
                                
			);
		}

		$data['heading_title'] = 'Users Cash Ledger';
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
		$this->load->model('setting/store');
		$data['order_stores'] = $this->model_setting_store->getStores();
		if(!empty($filter_stores_id))
		{
			$data['users'] =$this->model_report_cash->get_store_users($filter_stores_id);
		}
		else
		{
			$data['users'] = $this->model_report_cash->get_store_users_all();
		}
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_stores_id'])) {
			$url .= '&filter_stores_id=' . $this->request->get['filter_stores_id'];
		}
		if (isset($this->request->get['filter_user_id'])) {
			$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/users_cash_leadger', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_stores_id'] = $filter_stores_id;
		$data['filter_user_id'] = $filter_user_id;
		
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/users_cash_ledger.tpl', $data)); 
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
			'filter_stores_id' => $filter_stores_id,
			'filter_user_id' => $filter_user_id
		);

		$results = $this->model_report_cash->getcashtrans($filter_data);

		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Store Name',
        'User Name',
        'Amount',
        'Order Id',
		'CR_DB',
		'Trans Type',
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
		$col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['storename']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['user']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['amount']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['tr_type']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['payment_method']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['updated_cash']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['create_time']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['remarks']);
        $row++;
    }

    

    
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Users_cash_Ledger_'.date('dMy').'.xls"');
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
	public function get_cash_deposit_by_store_incharge()
	{
		$oid=$this->request->get['oid'];
		$this->load->model('report/cash');
		$user_group_id = $this->model_report_cash->get_user_type($oid);
		
		$html= '<thead>
				<tr>
					<td class="text-left">';
					if($user_group_id==11)
					{
						$html=$html. 'Collection Executive</td>';
						$results = $this->model_report_cash->get_cash_deposit_by_store_incharge($oid);
						
					}
					if($user_group_id==36)
					{
						$html=$html. 'Store In-Charge</td>';
						$results = $this->model_report_cash->get_cash_deposit_by_sub_user($oid);
					}
					
					$html=$html. '<td class="text-left">';
					if($user_group_id==11)
					{
						$html=$html. 'Store In-charge</td>';
					}
					if($user_group_id==36)
					{
						$html=$html. 'Sub User</td>';
					}
				   $html=$html.  '<td class="text-left">Amount</td>
				    <td class="text-left">Ramaining Cash</td>
				    
               </tr>
           </thead>
			<tbody id=""> 
				 <tr><td>'.$results['ce_name'].'</td>	
				<td>'.$results['store_incharge'].'</td>	
				<td>'.$results['amount'].'</td>	
				<td>'.$results['updated_cash'].'</td></tr>	
			</tbody>';
			
			echo $html;
		
	}
	public function cash_deposit_by_sub_user() 
	{
		$oid=$this->request->get['oid'];
		$this->load->model('report/cash');
		$user_group_id = 11;
		
		$html= '<thead>
				<tr>
					<td class="text-left">';
					if($user_group_id==11)
					{
						$html=$html. 'Store In-Charge (Receiver)</td>';
						$results = $this->model_report_cash->cash_deposit_by_sub_user($oid);
						
					}
					
					
					$html=$html. '<td class="text-left">';
					if($user_group_id==11)
					{
					
						$html=$html. 'Sub User (Sender)</td>';
					}
				   $html=$html.  '<td class="text-left">Amount</td>
				    <td class="text-left">Available Cash</td>
				    
               </tr>
           </thead>
			<tbody id=""> 
				 <tr><td>'.$results['store_incharge'].'</td>	
				<td>'.$results['sub_user'].'</td>	
				<td>'.$results['amount'].'</td>	
				<td>'.$results['updated_cash'].'</td></tr>	
			</tbody>';
			
			echo $html;
		
	}
	public function OrderDetail()
	{
		$oid=$this->request->get['oid'];
		$this->load->model('report/cash');
		//echo "nhjfbv";
		$results= $this->model_report_cash->getorder_summarydetail($oid);
		$results2= $this->model_report_cash->getorder_totaldetail($oid);
		foreach ($results as $result) {//print_r($result); 
	    $data['orders'][] = array(
				'name' => $result['name'],
				'quantity'   => $result['quantity'],
				'price'      => $result['price'],
				'tax'     => $result['tax'],  
				'total'      => $result['total']
			);
		}
		$data['order_total']=$results2['total'];
		$data['order_cash']=$results2['cash'];
		$data['order_tagged']=$results2['tagged'];
		$data['order_subsidy']=$results2['subsidy'];
		//print_r( $data);
       $this->response->setOutput(json_encode($data));
	}
}