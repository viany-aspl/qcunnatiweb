<?php
class ControllerLeadgerLeadger extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('lead/orderleads');

		$this->document->setTitle('Leadger report');

		$this->load->model('lead/orderleads');

		$this->getList();
	}

	
	
	protected function getList() {
		

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		

		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Leadger report',
			'href' => $this->url->link('leadger/leadger', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		

		$data['heading_title'] = 'Leadger report';
		
		
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');

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

		
		$url = '';

		

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		

		

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('lead/orderleads', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		
                            $this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();//print_r($data['stores'] );

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('leadger/leadger.tpl', $data));
	}

	
public function download_excel() {
        
                           $this->load->model('leadger/Inventory');
	             $this->load->model('leadger/saleorder');
	             $this->load->model('leadger/cash');
                           $this->load->model('sale/order');
                           $this->load->model('leadger/stock');
			
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}

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

		$data['orders'] = array();

		$filter_data = array(
			'filter_store'      => $filter_store,
			'filter_start_date'    => $filter_start_date,
			'filter_end_date' => $filter_end_date	
		);

$stock_results = $this->model_leadger_stock->getOrders($filter_data);
$inventory_results = $this->model_leadger_Inventory->getInventory_report($filter_data);

$sale_results = $this->model_leadger_saleorder->getOrders($filter_data);
       
$cash_results = $this->model_leadger_cash->getCash_report($filter_data);
$eod_cash_results = $this->model_leadger_cash->getCash_position($filter_data);


    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
/////////////1st sheet start from here///////////
    
    $objPHPExcel->createSheet();
   
    $objPHPExcel->setActiveSheetIndex(0); 
    $objPHPExcel->getActiveSheet()->setTitle("Stock Position");
    $fields = array(
       'Store Name (Receiver)',
        'Product ID',
        'Product Name',
        'Qnty',
        'Order date',
        'Transaction type',
        'Store Name(Sender)'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
    $row = 2;
    foreach($stock_results as $data)
    {         
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['quantity']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['order_date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Transaction_Type']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['store_transfer']);
        
        $row++;
    }
////////////////////////2nd sheet start here//////////////////////

    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->setTitle("Inventory (Current Inventory)");
    // Field names in the first row
    $fields = array(
       'Store Name',
        'Product ID',
        'Product Name',
        'Qnty'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
    		
    $row = 2;
    
    foreach($inventory_results as $data)
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Qnty']);
        
        $row++;
    }

    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
    
    $objPHPExcel->setActiveSheetIndex(2);
    $objPHPExcel->getActiveSheet()->setTitle("Sales-Itemized Bill");

///////////Sales-Itemized Bill sheet 3 start here////////
    // Field names in the first row
    $fields = array(
              'Store Name',
              'Store ID',
              'Order ID',
	'Payment Method',
              'Date',
	'Product Name',
	'Quantity',
	'Price',
	'Tax',
	'Total'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
    
    $row = 2;
    
    foreach($sale_results as $data)
    { 
              $col = 0;
	//get product details row		                
	$orderinfos=$this->model_sale_order->getOrder_detail($data['order_id']);
	foreach($orderinfos as $orderinfo){
       
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_id']);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['order_id']);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['payment_method']);
        
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date('Y-m-d',strtotime($data['date_added'])));
                  
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $orderinfo['name']);           
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $orderinfo['quantity']);           
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $orderinfo['price']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $orderinfo['tax']);           
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row,number_format((float)($orderinfo['total']+($orderinfo['tax']*$orderinfo['quantity'])),2,'.',''));           
          

        $row++;
	}

    }
///////////Sales-Itemized Bill sheet 3 end here////////

////////////4th sheet start from here///////////

    $objPHPExcel->createSheet();
   
    $objPHPExcel->setActiveSheetIndex(3); 
    $objPHPExcel->getActiveSheet()->setTitle("Deposit (Cash + Tagged)"); 
// Field names in the first row
    $fields = array(
        'Store Name ',
        'Bank',
        'Date',
        'Amount',
        'Status',
        'By whom'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    $row = 2;
    foreach($cash_results as $data)
    {
              $col = 0;
              if($data['status']=="0") { $status="Pending"; } 
	else if($data['status']=="1") { $status="Accepted"; } 
	else if($data['status']=="2") { $status="Rejected"; } 
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['bank_name']);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['date_added'])));
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['amount']);
        	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $status);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['firstname']." ".$data['lastname']);
            
        

        $row++;
    }

  //////sheet 5 eod cash start here//////////////// 
    $objPHPExcel->createSheet();
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(4);
    $objPHPExcel->getActiveSheet()->setTitle("Cash In Hand");
   
    $fields = array(
        
        'Store Name ',
        'Store ID',
        
        'Amount',
        'Update Date',
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    $row = 2;
    
    foreach($eod_cash_results as $data)
    {
        $col = 0;   
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, number_format((float)$data['amount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date('Y-m-d',strtotime($data['update_date'])));
     
        $row++;
    }

    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="leadger_report_'.$filter_start_date.'_'.$filter_end_date.'_'.$filter_store.'_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
    
    }
}