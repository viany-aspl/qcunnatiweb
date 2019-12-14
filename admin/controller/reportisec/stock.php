<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerReportisecStock extends Controller {
	public function transfer() {
		$this->load->language('report/stock_transfer');

		$this->document->setTitle($this->language->get('heading_title'));

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
			$filter_store = 'null';
		}
                if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
                if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = 'null'; 
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
                if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
                if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
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
			'href' => $this->url->link('reportisec/stock/transfer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/stock');
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_name'	     => $filter_name,
                        'filter_company'        => '3',
                        'filter_name_id'	     => $filter_name_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_report_stock->getTotalOrders_companywise($filter_data);

		$results = $this->model_report_stock->getOrders_companywise($filter_data);

		foreach ($results as $result) { //print_r($result);
                        if($result['store_name']!="")
                        { $store_rec=$result['store_name']; } else { $store_rec=$result["store_id"]; }
                        //$arrray1=explode('Rs.',$result['price']);
                        //$total=$arrray1[1]+$result['tax'];
                
			$data['orders'][] = array(
				'store_name'   => $store_rec,
				'order_date'   => date($this->language->get('date_format_short'), strtotime($result['order_date'])),
				'product_id'     => $result['product_id'],
				'price'   => number_format((float)$result['price'], 2, '.', ''),
                                'tax'   => number_format((float)$result['tax'], 2, '.', ''),
                                'quantity'   => $result['quantity'],
                                'Transaction_Type'   => $result['Transaction_Type'],
                                'total' => number_format((float)$result['Total'], 2, '.', ''),
                                'store_transfer' => $result['store_transfer'],
                                'product_name'  => $result["product_name"],
		   'Current_status' => $result["Current_status"],
		'To_be_Recived' => $result["To_be_Recived"]
                                );
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
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('3');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		

		$url = '';
                if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
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
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportisec/stock/transfer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
                $data['filter_store'] = $filter_store;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['filter_name']= $filter_name;
                $data['filter_name_id']= $filter_name_id;
		$this->response->setOutput($this->load->view('reportisec/stock_transfer.tpl', $data));
	}
	public function transit() {
		$this->load->language('report/stock_transfer');

		$this->document->setTitle('Stock under transit');

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
			$filter_store = 'null';
		}
                if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
                if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = 'null'; 
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
                if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
                if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
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
			'text' => 'Stock under transit',
			'href' => $this->url->link('reportisec/stock/transit', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/stock');
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_name'	     => $filter_name,
                        'filter_company'    => '3',
                        'filter_name_id'	     => $filter_name_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_report_stock->getTotalOrdersTransit_companywise($filter_data);

		$results = $this->model_report_stock->getOrdersTransit_companywise($filter_data);

		foreach ($results as $result) { //print_r($result);
                         if($result['store_name']!="")
                        { $store_rec=$result['store_name']; } else { $store_rec=$result["store_id"]; }
                        //$arrray1=explode('Rs.',$result['price']);
                        //$total=$arrray1[1]+$result['tax'];
                
			$data['orders'][] = array(
				'store_name'   => $store_rec,
				'order_date'   => date($this->language->get('date_format_short'), strtotime($result['order_date'])),
				'product_id'     => $result['product_id'],
				'price'   => number_format((float)$result['price'], 2, '.', ''),
                                'tax'   => number_format((float)$result['tax'], 2, '.', ''),
                                'quantity'   => $result['quantity'],
                                'Transaction_Type'   => $result['Transaction_Type'],
                                'total' => number_format((float)$result['Total'], 2, '.', ''),
                                'store_transfer' => $result['store_transfer'],
                                'product_name'  => $result["product_name"],
		   'Current_status' => $result["Current_status"],
		'To_be_Recived' => $result["To_be_Recived"],
		'order_id' => $result["order_id"]
                                );
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
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('3');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		

		$url = '';
                if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
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
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportisec/stock/transit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
                $data['filter_store'] = $filter_store;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['filter_name']= $filter_name;
                $data['filter_name_id']= $filter_name_id;
		$this->response->setOutput($this->load->view('reportisec/stock_transit.tpl', $data));
	}
        public function download_transfer() {
		
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
			$filter_store = 'null';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
                if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = 'null';
		}
		$this->load->model('report/stock');
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_name'	     => $filter_name,
                         'filter_company'        => '3',
                        'filter_name_id'	     => $filter_name_id
		);

		//$order_total = $this->model_report_stock->getTotalOrders_companywise($filter_data);

		$results = $this->model_report_stock->getOrders_companywise($filter_data);

		
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
          'Store Name(Sender)',
        'Store Name (Receiver)',
        'Date',
        'Product Name',
        'Product ID',
        'Transaction Type',
        'To be received Qnty',
        'Received Qnty',
        'Price',
        'Tax',
        'Total Value',
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
        if($data['store_name']!="")
                        { $store_rec=$data['store_name']; } else { $store_rec=$data["store_id"]; }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_transfer']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $store_rec);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['order_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Transaction_Type']);   
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['To_be_Recived']);     
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['quantity']);            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, number_format((float)$data['price'], 2, '.', ''));            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format((float)$data['tax'], 2, '.', ''));  
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, number_format((float)$data['Total'], 2, '.', ''));             
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['Current_status']);            
                   
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="stock_transfer_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
		
	} 
  
 public function download_transit() {
		
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
			$filter_store = 'null';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
                if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = 'null';
		}
		$this->load->model('report/stock');
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_name'	     => $filter_name,
                        'filter_company'    => '3',
                        'filter_name_id'	     => $filter_name_id
		);

		//$order_total = $this->model_report_stock->getTotalOrdersTransit_companywise($filter_data);

		$results = $this->model_report_stock->getOrdersTransit_companywise($filter_data);

		
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name(Sender)',
        'Store Name (Receiver)',
        'Order ID',
        'Date',
        'Product Name',
        'Product ID',
        'Transaction Type',
        'To be received Qnty',
        'Received Qnty',
        'Price',
        'Tax',
        'Total Value',
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
        if($data['store_name']!="")
                        { $store_rec=$data['store_name']; } else { $store_rec=$data["store_id"]; }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_transfer']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $store_rec);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date('Y-m-d',strtotime($data['order_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['Transaction_Type']);   
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['To_be_Recived']);     
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['quantity']);            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format((float)$data['price'], 2, '.', ''));            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, number_format((float)$data['tax'], 2, '.', ''));  
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, number_format((float)$data['Total'], 2, '.', ''));             
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $data['Current_status']);            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="stock_under_transit_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
		
	} 
  

   public function recived() {
		$this->load->language('report/stock_transfer');

		$this->document->setTitle('Stock Received report ');

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
			$filter_store = 'null';
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
               	 if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
                	if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = 'null';
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
                if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
                if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
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
			'text' => 'Stock Received report',
			'href' => $this->url->link('reportisec/stock/recived', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/stock');
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_name'	     => $filter_name,
                        'filter_company'        =>'3',
                        'filter_name_id'	     => $filter_name_id,
	'status'=>$filter_status,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_report_stock->getTotalOrdersReceived_comapnywise($filter_data);

		$results = $this->model_report_stock->getOrdersReceived_companywise($filter_data);

		foreach ($results as $result) { //print_r($result);
                        if($result['store_name']!="")
                        { $store_rec=$result['store_name']; } else { $store_rec=$result["store_id"]; }
                        $arrray1=explode('Rs.',$result['price']);
                        if($arrray1[1]!="")
                        {
                        $total=$arrray1[1]+$result['tax'];
                        }
                        else
                        {
                          $total=$arrray1[0]+$result['tax'];  
                        }
                        if($arrray1[1]!="")
                        {
                          $price=$arrray1[1];
                        }
                        else
                        {
                          $price=$arrray1[0];  
                        }
			$data['orders'][] = array(
				'store_name'   => $store_rec,
				'order_date'   => date($this->language->get('date_format_short'), strtotime($result['order_date'])),
				'recive_date'  => date($this->language->get('date_format_short'), strtotime($result['recive_date'])),
                                'product_id'     => $result['product_id'],
				'price'   => number_format((float)$price, 2, '.', ''),
                                'tax'   => number_format((float)$result['tax'], 2, '.', ''),
                                'quantity'   => $result['quantity'],
		   'received_products'=>$result['received_products'],
                                'Transaction_Type'   => $result['Transaction_Type'],
                                'total' => number_format((float)$total, 2, '.', ''),
                                'store_transfer' => $result['store_transfer'],
                                'product_name'  => $result["product_name"],
			'Current_status'  => $result["Current_status"],
				'order_id'  => $result["order_id"]
                                );
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
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('3');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		

		$url = '';
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}

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
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportisec/stock/recived', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_status'] = $filter_status;
                $data['filter_store'] = $filter_store;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['filter_name']= $filter_name;
                $data['filter_name_id']= $filter_name_id;
		$this->response->setOutput($this->load->view('reportisec/stock_recived.tpl', $data));
	}
        public function download_recived() {
		
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
			$filter_store = 'null';
		}
                if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
                if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = 'null';
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		$this->load->model('report/stock');
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_name'	     => $filter_name,
                        'filter_company'        =>'3',
                        'filter_name_id'	     => $filter_name_id,
						'status'=>$filter_status
			
		);

		//$order_total = $this->model_report_stock->getTotalOrdersReceived($filter_data);

		$results = $this->model_report_stock->getOrdersReceived_companywise($filter_data);

		//echo "here";exit;	 	
			

		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Store Name (Receiver)',
        'Order Date',
        'Received date',
        'Product Name',
        'Product ID',
        'Transaction Type',
        'Sent Qnty',
        'Received Qnty',
       
        'Price',
        'Tax',
        'Total Value',
        'Store Name(Sender)',
        'Status',
		'Order ID'
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
if($data['store_name']!="")
                        { $store_rec=$data['store_name']; } else { $store_rec=$data["store_id"]; }
        $col = 0;
         $arrray1=explode('Rs.',$data['price']);
                        if($arrray1[1]!="")
                        {
                        $total=$arrray1[1]+$data['tax'];
                        }
                        else
                        {
                          $total=$arrray1[0]+$data['tax'];  
                        }
                        if($arrray1[1]!="")
                        {
                          $price=$arrray1[1];
                        }
                        else
                        {
                          $price=$arrray1[0];  
                        }
	if($data['Current_status']=="Recived")
	{
		$quantity=$data['quantity'];
	}
	else
	{
	$quantity=0;
	}
	$quantity=$data['quantity'];
	$total_value=$total*$quantity;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $store_rec);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('Y-m-d',strtotime($data['order_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['recive_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Transaction_Type']);            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $quantity);        
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['received_products']);          
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, number_format((float)$price, 2, '.', ''));            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format((float)$data['tax'], 2, '.', ''));  
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, number_format((float)$total_value, 2, '.', ''));             
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['store_transfer']);      
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $data['Current_status']);        
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $data['order_id']); 
        //received_products

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="stock_recieved_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
    
public function download_recived_for_store_report() {
		
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
			$filter_store = 'null';
		}
                if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
                if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = 'null';
		}
		
		$this->load->model('report/stock');
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_name'	     => $filter_name,
                        'filter_company'        =>'3',
                        'filter_name_id'	     => $filter_name_id 
						
			
		);

		//$order_total = $this->model_report_stock->getTotalOrdersReceived($filter_data);
		if($filter_store!="null")
		{ //echo "here";
		$results = $this->model_report_stock->getOrdersReceived_companywise($filter_data);
		}
		//echo "here";exit;	 	
			

		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Store Name (Receiver)',
        'Order Date',
        'Received date',
        'Product Name',
        'Product ID',
        'Transaction Type',
        'Sent Qnty',
        'Received Qnty',
       
        'Price',
        'Tax',
        'Total Value',
        'Store Name(Sender)',
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
if($data['store_name']!="")
                        { $store_rec=$data['store_name']; } else { $store_rec=$data["store_id"]; }
        $col = 0;
         $arrray1=explode('Rs.',$data['price']);
                        if($arrray1[1]!="")
                        {
                        $total=$arrray1[1]+$data['tax'];
                        }
                        else
                        {
                          $total=$arrray1[0]+$data['tax'];  
                        }
                        if($arrray1[1]!="")
                        {
                          $price=$arrray1[1];
                        }
                        else
                        {
                          $price=$arrray1[0];  
                        }
	if($data['Current_status']=="Recived")
	{
		$quantity=$data['quantity'];
	}
	else
	{
	$quantity=0;
	}
	$quantity=$data['quantity'];
	$total_value=$total*$quantity;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $store_rec);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('Y-m-d',strtotime($data['order_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['recive_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Transaction_Type']);            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $quantity);        
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['received_products']);          
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, number_format((float)$price, 2, '.', ''));            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format((float)$data['tax'], 2, '.', ''));  
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, number_format((float)$total_value, 2, '.', ''));             
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['store_transfer']);      
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $data['Current_status']);     
        //received_products

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="stock_recieved_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
      public function download_excel() {
        
        $this->load->language('report/sale_order');

		$this->document->setTitle($this->language->get('heading_title'));

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

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}
                if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}

		
		$this->load->model('report/sale');
                
		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_group'           => $filter_group,
			'filter_order_status_id' => $filter_order_status_id
		);

		

		$results = $this->model_report_sale->getOrders($filter_data);

		

        
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Date Start',
        'Date End',
        'No. Orders',
        'Store',
        'Total'
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
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date('Y-m-d',strtotime($data['date_start'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('Y-m-d',strtotime($data['date_end'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['orders']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['total']);            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="tagged_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
    
    }

     public function email_excel() {
        
        

				
		$this->load->model('report/sale');
                
		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => '',
			'filter_date_start'	     => date('Y-m-d'),
			'filter_date_end'	     => date('Y-m-d'),
			
			'filter_order_status_id' => '5'
		);

		

		$results = $this->model_report_sale->getOrders($filter_data);

		

        
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Date Start',
        'Date End',
        'No. Orders',
        'Store',
        'Total'
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
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date('Y-m-d',strtotime($data['date_start'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('Y-m-d',strtotime($data['date_end'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['orders']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['total']);            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="tagged_report_'.date('dMy').'.xls"');
    //header('Cache-Control: max-age=0');

    //$objWriter->save('php://output');
    $filename='sale_order_report_'.date('ymdhis').'.xls';
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
		//$mail->From = "mail.akshamaala.in";
		//$mail->FromName = "Support Team";
                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Sale orders Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('chetan.singh@akshamaala.com', "Chetan Singh");
                //$mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
                

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
        
        public function meterial() {
		$this->load->language('report/stock_transfer');

		$this->document->setTitle('Material transfer report');

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
			$filter_store = 'null';
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
			'text' => 'Material transfer report',
			'href' => $this->url->link('report/stock/meterial', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/stock');
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_report_stock->getTotalOrdersReceived($filter_data);

		$results = $this->model_report_stock->getOrdersReceived($filter_data);

		foreach ($results as $result) { //print_r($result);
                
                        $arrray1=explode('Rs.',$result['price']);
                        $total=$arrray1[1]+$result['tax'];
                
			$data['orders'][] = array(
				'store_name'   => $result['store_name'],
				'order_date'   => date($this->language->get('date_format_short'), strtotime($result['order_date'])),
				'product_id'     => $result['product_id'],
				'price'   => number_format((float)$arrray1[1], 2, '.', ''),
                                'tax'   => number_format((float)$result['tax'], 2, '.', ''),
                                'quantity'   => $result['quantity'],
                                'Transaction_Type'   => $result['Transaction_Type'],
                                'total' => number_format((float)$total, 2, '.', ''),
                                'store_transfer' => $result['store_transfer'],
                                'product_name'  => $result["product_name"]
                                );
		}

		$data['heading_title'] = 'Material transfer report';
		
		$data['text_list'] = 'Material transfer report';
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
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStores();
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		

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

		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/stock/meterial', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
                $data['filter_store'] = $filter_store;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/stock_meterial_report.tpl', $data));
		}

	public function transit_po() {
        $this->load->language('report/stock_transfer');

        $this->document->setTitle('Po under transit');

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
            $filter_store = 'null';
        }
                if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }
                if (isset($this->request->get['filter_name_id'])) {
            $filter_name_id = $this->request->get['filter_name_id'];
        } else {
            $filter_name_id = 'null';
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
                if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
                if (isset($this->request->get['filter_name_id'])) {
            $url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
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
            'text' => 'Po under transit',
            'href' => $this->url->link('reportisec/stock/po_transit', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $this->load->model('report/stock');
                 $this->load->model('setting/store');

        $data['orders'] = array();

        $filter_data = array(
                        'filter_store'         => $filter_store,
            'filter_date_start'         => $filter_date_start,
            'filter_date_end'         => $filter_date_end,
            'filter_company'            =>'3',
                        'filter_name'         => $filter_name,
                        'filter_name_id'         => $filter_name_id,
            'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                  => $this->config->get('config_limit_admin')
        );

        $order_total = $this->model_report_stock->getTotalOrdersTransit_po_companywise($filter_data);

        $results = $this->model_report_stock->getOrdersTransit_po_companywise($filter_data);

        foreach ($results as $result) { //print_r($result);
                         if($result['store_name']!="")
                        { $store_rec=$result['store_name']; } else { $store_rec=$result["store_id"]; }
                        //$arrray1=explode('Rs.',$result['price']);
                        //$total=$arrray1[1]+$result['tax'];
                
            $data['orders'][] = array(
                'store_name'   => $store_rec,
                'order_date'   => date($this->language->get('date_format_short'), strtotime($result['order_date'])),
                'product_id'     => $result['product_id'],
                'price'   => number_format((float)$result['price'], 2, '.', ''),
                                'tax'   => number_format((float)$result['tax'], 2, '.', ''),
                                'quantity'   => $result['quantity'],
                                'Transaction_Type'   => $result['Transaction_Type'],
                                'total' => number_format((float)$result['Total'], 2, '.', ''),
                                'store_transfer' => $result['store_transfer'],
                                'product_name'  => $result["product_name"],
           'Current_status' => $result["Current_status"],
        'To_be_Recived' => $result["To_be_Recived"],
        'order_id' => $result["order_id"]
                                );
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
        $data['entry_group'] = $this->language->get('entry_group');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_filter'] = $this->language->get('button_filter');

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('3');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        

        $url = '';
                if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
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
        
        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('reportisec/stock/transit_po', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        
                $data['filter_store'] = $filter_store;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
                $data['filter_name']= $filter_name;
                $data['filter_name_id']= $filter_name_id;
        $this->response->setOutput($this->load->view('reportisec/po_transit.tpl', $data));
    }
        public function download_transit_po() {
        
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
            $filter_store = 'null';
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }
                if (isset($this->request->get['filter_name_id'])) {
            $filter_name_id = $this->request->get['filter_name_id'];
        } else {
            $filter_name_id = 'null';
        }
        $this->load->model('report/stock');
                 $this->load->model('setting/store');

        $data['orders'] = array();

        $filter_data = array(
                        'filter_store'         => $filter_store,
            'filter_date_start'         => $filter_date_start,
            'filter_date_end'         => $filter_date_end,
                        'filter_name'         => $filter_name,
            'filter_company'            =>'3',
                        'filter_name_id'         => $filter_name_id
        );

        //$order_total = $this->model_report_stock->getTotalOrdersTransit($filter_data);

        $results = $this->model_report_stock->getOrdersTransit_po_companywise($filter_data);

        
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name(Sender)',
        'Store Name (Receiver)',
        'Order ID',
        'Date',
        'Product Name',
        'Product ID',
        'Transaction Type',
        'To be received Qnty',
        'Received Qnty',
        'Price',
        'Tax',
        'Total Value',
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
        if($data['store_name']!="")
                        { $store_rec=$data['store_name']; } else { $store_rec=$data["store_id"]; }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_transfer']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $store_rec);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date('Y-m-d',strtotime($data['order_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['Transaction_Type']);   
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['To_be_Recived']);     
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['quantity']);            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format((float)$data['price'], 2, '.', ''));            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, number_format((float)$data['tax'], 2, '.', ''));  
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, number_format((float)$data['Total'], 2, '.', ''));             
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $data['Current_status']);            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="po_under_transit_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }

}