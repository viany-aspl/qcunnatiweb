<?php
ini_set('memory_limit', '2048M');
class ControllerReportInventoryLedger extends Controller {
	public function index() {
		$this->load->language('report/product_purchased');

		$this->document->setTitle("Inventory Leadger Report");

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
		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = null;
		}
		if (isset($this->request->get['filter_product_name'])) {
			$filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$filter_product_name = null;
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
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . $this->request->get['filter_product_name'];
		}
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
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
			'href' => $this->url->link('report/inventory_ledger', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/product');
                $this->load->model('report/inventory_ledger');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id,
			'filter_product_id' => $filter_product_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$product_total = $this->model_report_inventory_ledger->getproducttransTotal($filter_data);

		$results = $this->model_report_inventory_ledger->getproducttrans($filter_data);

		foreach ($results as $result) { //print_r($results);
		if($result['trans_type']=='SALE')
		{
			$result['sale_by']=$result['sale_by'];
		}
		else
		{
			$result['sale_by']='';
		}
			$data['products'][] = array(
				'quantity'       => $result['quantity'],
				'order_id'      => $result['order_id'],
				'cr_db'   => $result['cr_db'],
				'trans_type'      => $result['trans_type'],
                            'trans_time'      => $result['trans_time'],
                            'model'      => $result['model'],
                            'name'      => $result['name'],
		'current_quantity'=>$result['current_quantity'],
		'sale_by'=>$result['sale_by'],
		'billing_type' =>$result['billing_type']
                                
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
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

		$this->load->model('localisation/order_status');
                $this->load->model('setting/store');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
                $data['order_stores'] = $this->model_setting_store->getStores();
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
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . $this->request->get['filter_product_name'];
		}
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/inventory_ledger', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
                $data['filter_stores_id']=$filter_stores_id;
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_stores_id'] = $filter_stores_id;
		$data['filter_name_id'] = $filter_product_id;
		$data['filter_name'] = $filter_product_name;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/inventory_ledger.tpl', $data));
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
		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = null;
		}
		if (isset($this->request->get['filter_product_name'])) {
			$filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$filter_product_name = null;
		}
		
		$this->load->model('report/inventory_ledger');

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id,
			'filter_product_id' => $filter_product_id
		);

		$results = $this->model_report_inventory_ledger->getproducttrans($filter_data);

		

		
	include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Store Name',
        'Product Name',
        'Quantity',
        'Order Id',
		'CR_DB',
		'Trans Type',
		'CURRENT INVENTORY',
		'Trans Date',
		'Sale By',
		'System'
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
		if($data['trans_type']=='SALE')
		{
			$data['sale_by']=$data['sale_by'];
		}
		else
		{
			$data['sale_by']='';
		}
		$col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['model']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['quantity']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['cr_db']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['trans_type']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['current_quantity']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['trans_time']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['sale_by']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['billing_type']);
        $row++;
    }

    

    
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="product_Ledger_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
}