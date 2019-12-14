<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerReportdsclInventoryReport extends Controller {
	
	public function field_inventory_report() {
		$this->load->language('report/Inventory_report');

		$this->document->setTitle('Physical Inventory ');

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
         if (isset($this->request->get['filter_name'])) {
            $filter_name=$data['filter_name'] = $this->request->get['filter_name'];
        } else
                {
            //$filter_name = '';
        }
        if (isset($this->request->get['filter_name_id'])) {
            $filter_name_id =$data['filter_name_id'] = $this->request->get['filter_name_id'];
        }       
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
              
          if ($this->request->get['filter_store']!="") {
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
			'text' => 'Physical Inventory',
			'href' => $this->url->link('reportdscl/inventory_report/field_inventory_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
        $this->load->model('report/Inventory');
		$data['orders'] = array();

		$filter_data = array(
			'filter_company' => 1,
			'filter_name' => $filter_name,
			'filter_name_id' => $filter_name_id,
			'filter_store' => $filter_store,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		

		$data['orders'] = array();
        //if(!empty($filter_store))
		{
			$order_total = $this->model_report_Inventory->gettotalfieldinventory($filter_data);
           
			$results = $this->model_report_Inventory->getfieldinventory($filter_data);
		}
       // print_r($results);
		foreach ($results as $result) { 
			$data['orders'][] = array(
                'storename' => $result['name'],
				'productname' => $result['model'],
				'field_quantity' => $result['field_quantity'],
				'store_quantity' => $result['store_quantity'],
				'dat' => $result['dat']
			);
		}

		

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
		$url = '';

		
            
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportdscl/inventory_report/field_inventory_report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
//print_r($order_total); exit;
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_store'] = $filter_store;
		    $this->load->model('setting/store');
			$data["stores"]=$this->model_setting_store->getStoresCompanyWise(1);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportdscl/Inventory_fieldinventory_report.tpl', $data));
	}
	public function field_inventory_report_download() {
		
		
        if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
 		if (isset($this->request->get['filter_name'])) {
            $filter_name=$data['filter_name'] = $this->request->get['filter_name'];
        } else
                {
            //$filter_name = '';
        }
        if (isset($this->request->get['filter_name_id'])) {
            $filter_name_id =$data['filter_name_id'] = $this->request->get['filter_name_id'];
        }  
        $this->load->model('report/Inventory');
		
		$data['orders'] = array();

		$filter_data = array(
			'filter_company' => 1,
			'filter_name' => $filter_name,
			'filter_name_id' => $filter_name_id,
			'filter_store'	     => $filter_store
		);
               
		//print_r($filter_user);
		$data['orders'] = array();
		//if(!empty($filter_store))
		{
          $results = $this->model_report_Inventory->getfieldinventory($filter_data);         
		}		  
		//print_r($results); exit;
		
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
        'System Quantity',
		'Physical Quantity', 
        'Field Quantity',
        'Date'
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['model']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_quantity']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['field_quantity']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ($data['store_quantity']-$data['field_quantity']));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['dat']); 
		
        
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Physical Inventory Report_'.date('d-M-y').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
	
	public function index() {
		$this->load->language('report/Inventory_report');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
                
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
                /*
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                */
                if ($this->request->get['filter_store']!="") {
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('reportdscl/inventory_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('setting/store');
                $this->load->model('report/Inventory');
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_store' => $filter_store,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		

		$data['orders'] = array();
 if ($this->request->get['filter_store']!="") {
     $ordt= $this->model_report_Inventory->getTotalInventory($filter_data);
     $order_total=$ordt["total"];
     $data["total_amount"]=$ordt["total_sum"];
 $results = $this->model_report_Inventory->getInventory_report($filter_data);
 
 }
		
                //$taxc=new Tax();                

		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                'product_id' => $result['product_id'],
				'product_name' => $result['Product_name'],
                                'store_id'      => $result['store_id'],
                                'store_name'      => $result['store_name'],
				'qnty'   => $result['Qnty'],
				'Amount'      => $result['Amount'],
				'price'     => $result['price'],
                                //'tax'	    => $mcrypt->encrypt(round($taxc->getTax($result['price'], $result['tax_class_id'])))
                                
				
                                
			);
		}

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

		$this->load->model('setting/store'); 
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('1');//print_r($data['stores'] );
		//echo "here";
		$url = '';

		
                /*
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                */
                if ($this->request->get['filter_store']!="") {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportdscl/inventory_report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_store'] = $filter_store;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportdscl/Inventory_report.tpl', $data));
	}
public function unit_wise() {
                            $this->load->model('unit/unit');
                            $data['unit'] = $this->model_unit_unit->getunit();
                           // print_r($data['unit']);
                            
		$this->load->language('report/Inventory_report');

		$this->document->setTitle($this->language->get('Inventory_report(unit_wise)'));

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store =null;
		}
                 if (isset($this->request->get['unit_id'])) {
			$unit_id = $this->request->get['unit_id'];
		} else {
			$unit_id = null;
		}
  
                
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
                /*
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                */
                if ($this->request->get['filter_store']!="") {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                 if ($this->request->get['unit_id']!="") {
			$url .= '&unit_id=' . $this->request->get['unit_id'];
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
			'text' => $this->language->get('Inventory_report(unit_wise)'),
			'href' => $this->url->link('report/inventory_report/unit_wise', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('setting/store');
                $this->load->model('report/Inventory');
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_store' => $filter_store,
                        'unit_id' => $unit_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		

		$data['orders'] = array();

     $ordt= $this->model_report_Inventory->getTotalInventoryunitwise($filter_data);
     $order_total=$ordt["total"];
     $data["total_amount"]=$ordt["total_sum"];
 $results = $this->model_report_Inventory->getInventory_report_unit_wise($filter_data);
 
 
		
                //$taxc=new Tax();                

		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                'product_id' => $result['product_id'],
				'product_name' => $result['Product_name'],
                                'store_id'      => $result['store_id'],
                                'store_name'      => $result['store_name'],
                                'unit_name'   => $result["unit_name"],
				'qnty'   => $result['Qnty'],
				'Amount'      => $result['Amount'],
				'price'     => $result['price'],
                                //'tax'	    => $mcrypt->encrypt(round($taxc->getTax($result['price'], $result['tax_class_id'])))
                                
				
                                
			);
		}

		$data['heading_title'] = $this->language->get('Inventory_report(unit_wise)');
		
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

		$this->load->model('localisation/order_status');
                            $data['stores'] = $this->model_setting_store->getStores();//print_r($data['stores'] );
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

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

		$url = '';

		
                /*
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                */
                if ($this->request->get['filter_store']!="") {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                 if ($this->request->get['unit_id']!="") {
			$url .= '&unit_id=' . $this->request->get['unit_id'];
		}
                
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/inventory_report/unit_wise', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_store'] = $filter_store;
                $data['unit_id'] = $unit_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/Inventory_report_unit_wise.tpl', $data));
	}

public function linked_product() {
		$this->load->language('report/Inventory_report');

		$this->document->setTitle('Inventory (Linked product)');

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
                
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
                
                if ($this->request->get['filter_store']!="") {
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
			'text' => 'Inventory (Linked product)',
			'href' => $this->url->link('reportdscl/inventory_report/linked_product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('setting/store');
                $this->load->model('report/Inventory');
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_store' => $filter_store,
                        'filter_company' => '1',
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);


		$data['orders'] = array();
                /*
 if ($this->request->get['filter_store']!="") {
    
 
 }
 */
            $order_total = $this->model_report_Inventory->getTotalInventory_linked_productCompanyWise($filter_data);
            $results = $this->model_report_Inventory->getInventory_linked_productCompanyWise($filter_data);		
	//echo "here";
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                'product_id' => $result['product_id'],
				'product_name' => $result['Product_name'],
                                'store_id'      => $result['store_id'],
                                'store_name'      => $result['store_name'],
				'qnty'   => $result['Qnty'],
				'Amount'      => $result['Amount'],
				'price'     => $result['price'],
                                
                                
				
                                
			);
		}

		$data['heading_title'] = 'Inventory (Linked product)';
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('1');
		
		$url = '';

		
                if ($this->request->get['filter_store']!="") {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportdscl/inventory_report/linked_product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_store'] = $filter_store;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportdscl/Inventory_linked_product.tpl', $data));
	}
public function linked_product_download_excel(){
        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = '';
        }
       
        $this->load->model('report/Inventory');
        $data['orders'] = array();

        $filter_data = array(
            
            'filter_store' => $filter_store,
            'filter_company' => '1',
            
        );

        

        $data['orders'] = array();
 
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

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
     
    $results = $this->model_report_Inventory->getInventory_linked_productCompanyWise($filter_data);
		
    $row = 2;
    
    foreach($results as $data)
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Qnty']);
        
        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Inventory_linked_product_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }


public function product_wise()
        {
            $this->load->language('report/Inventory_report');

        $this->document->setTitle('Inventory Report (Product Wise)');

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else
                {
            //$filter_name = '';
        }
                if (isset($this->request->get['filter_name_id'])) {
            $filter_name_id = $this->request->get['filter_name_id'];
        }
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';
                
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
            'href' => $this->url->link('reportdscl/inventory_report/product_wise', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $this->load->model('setting/store');
                $this->load->model('report/Inventory');
        $data['orders'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_name_id' => $filter_name_id,
            'filter_company' => '1',
            'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                  => $this->config->get('config_limit_admin')
        );

        

        $data['orders'] = array();
                if ($this->request->get['filter_name']!="")
                {
                 //$order_total = $this->model_report_Inventory->getTotalInventoryProductWise($filter_data);
                 //$results = $this->model_report_Inventory->getInventory_reportProductWise($filter_data);
 
                }
        	   $totalss=$this->model_report_Inventory->getTotalInventoryProductCompanyWise($filter_data);
                 $order_total =$totalss["total"] ;
	   $data["total_Qnty"] =$totalss["total_Qnty"] ;
                 $results = $this->model_report_Inventory->getInventory_reportProductCompanyWise($filter_data); 
        foreach ($results as $result) { //print_r($result);
                     $data['orders'][] = array(
                                'product_id' => $result['product_id'],
                'product_name' => $result['Product_name'],
                                'store_id'      => $result['store_id'],
                                'store_name'      => $result['store_name'],
                'qnty'   => $result['Qnty'],
                'Amount'      => $result['Amount'],
                'price'     => $result['price'],
                                
                
                                
            );
        }

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

        $this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('1');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        

        $url = '';

                if ($this->request->get['filter_name']!="") {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
                if ($this->request->get['filter_name_id']!="") {
            $url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
        }
        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('reportdscl/inventory_report/product_wise', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        
        $data['filter_name_id'] = $filter_name_id;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('reportdscl/Inventory_report_product_wise.tpl', $data));
        }


      public function download_excel(){
        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = '';
        }
                
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';
                /*
        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }
                */
                if ($this->request->get['filter_store']!="") {
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
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/inventory_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $this->load->model('setting/store');
                $this->load->model('report/Inventory');
        $data['orders'] = array();

        $filter_data = array(
            
            'filter_store' => $filter_store
          
        );

        

        $data['orders'] = array();
 if ($this->request->get['filter_store']!="") 
{

     //$order_total = $this->model_report_Inventory->getTotalInventory($filter_data);
 $results = $this->model_report_Inventory->getInventory_report_excel($filter_data);
 
 }
        
            // Starting the PHPExcel library
    //print_r($this->load->library('PHPExcel'));
    //$this->load->library('PHPExcel/IOFactory');
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Store Name',
        'Product ID',
        'Product Name',
        'Qnty',
        'Price',
        'Amount'
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
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, ($data['Qnty']*$data['Amount']));
        
            
        

        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Inventory_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
    
    public function download_unit_wise(){
        
         $this->load->model('unit/unit');
            $data['unit'] = $this->model_unit_unit->getunit();
                           // print_r($data['unit']);
        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = null;
        }
        
        if (isset($this->request->get['unit_id'])) {
            $unit_id = $this->request->get['unit_id'];
        } else {
            $unit_id = null;
        }
                
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';
                /*
        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }
                */
                if ($this->request->get['filter_store']!="") {
            $url .= '&filter_store=' . $this->request->get['filter_store'];
        }
               if ($this->request->get['unit_id']!="") {
			$url .= '&unit_id=' . $this->request->get['unit_id'];
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
			'text' => $this->language->get('Inventory_report(unit_wise)'),
			'href' => $this->url->link('report/inventory_report/unit_wise', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

        $this->load->model('setting/store');
                $this->load->model('report/Inventory');
        $data['orders'] = array();

        $filter_data = array(
            
            'filter_store' => $filter_store,
            'unit_id' => $unit_id
          
        );
        $data['orders'] = array();

     //$order_total = $this->model_report_Inventory->getTotalInventory($filter_data);
 $results = $this->model_report_Inventory->getdownload_unit_wise($filter_data);
        
            // Starting the PHPExcel library
    //print_r($this->load->library('PHPExcel'));
    //$this->load->library('PHPExcel/IOFactory');
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Unit Name',
        'Store Name',
        'Product ID',
        'Product Name',
        'Qnty',
        'Price',
        'Amount'
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
    
    foreach($results as $data  )
        
    {   $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['unit_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['Qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, ($data['Qnty']*$data['Amount']));
        

        $row++;
    }
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Inventory_report_unit_wise_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
  public function download_excel_product_wise(){
         if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else
                {
            //$filter_name = '';
        }
                if (isset($this->request->get['filter_name_id'])) {
            $filter_name_id = $this->request->get['filter_name_id'];
        }
        

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_company' => '1',
            'filter_name_id' => $filter_name_id
           
        );

        
        $this->load->model('setting/store');
        $this->load->model('report/Inventory');

        $data['orders'] = array();
                if ($this->request->get['filter_name']!="")
                {
                 $order_total = $this->model_report_Inventory->getTotalInventoryProductCompanyWise($filter_data);
                 $results = $this->model_report_Inventory->getInventory_reportProductCompanyWise($filter_data);
 
                }
        $order_total = $this->model_report_Inventory->getTotalInventoryProductCompanyWise($filter_data);
        $results = $this->model_report_Inventory->getInventory_reportProductCompanyWise($filter_data);

            // Starting the PHPExcel library
    //print_r($this->load->library('PHPExcel'));
    //$this->load->library('PHPExcel/IOFactory');
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
        'Qnty',
        'Price (With out Tax)',
        'Amount (With out Tax) '
       
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
    
/*
foreach ($results as $result) { //print_r($result);
                     $data['orders'][] = array(
                                'product_id' => $result['product_id'],
                'product_name' => $result['Product_name'],
                                'store_id'      => $result['store_id'],
                                'store_name'      => $result['store_name'],
                'qnty'   => $result['Qnty'],
                'Amount'      => $result['Amount'],
                'price'     => $result['price'],
                                
                
                                
            );
        }

*/
    foreach($results as $data)
    {         $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ($data['price']*$data['Qnty']));
      
        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Inventory_report_product_wise_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
      public function email_excel(){
        
$this->load->model('report/Inventory');
$data['orders'] = array();

 $results = $this->model_report_Inventory->getInventory_report($filter_data);
             
        
    // Starting the PHPExcel library
    //print_r($this->load->library('PHPExcel'));
    //$this->load->library('PHPExcel/IOFactory');
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Product ID',
        'Product Name',
        'Store name',
        'Qnty',
        'Price',
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
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Amount']);
        
        $row++;
    }

    

    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="Inventory_report_'.date('dMy').'.xls"');
    //header('Cache-Control: max-age=0');

    //$objWriter->save('php://output');
    $filename='inventory_report_'.date('ymdhis').'.xls';
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

                $mail->Subject    = "Inventory Report";

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
    public function old_report() {
		$this->load->language('report/Inventory_report');

		$this->document->setTitle('Inventory old report');

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = '';
		}
                
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
              
                            if ($this->request->get['filter_date']!="") {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
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
			'text' => 'Inventory old report',
			'href' => $this->url->link('report/inventory_report/old_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                            $this->load->model('report/Inventory');
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date' => $filter_date,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		

		$data['orders'] = array();
                                      // if ($this->request->get['filter_date']!="") {
                                       $order_total = $this->model_report_Inventory->get_total_old_report_daily_email($filter_data);
                                       $results = $this->model_report_Inventory->get_old_report_daily_email($filter_data);
 
                                   // }
		
                          

		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                'report_date' => $result['report_date'],
		    'filename' => $result['filename']
			);
		}

		$data['heading_title'] = 'Inventory old report';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
		$url = '';

		
               
                           if ($this->request->get['filter_date']!="") {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/inventory_report/old_report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date'] = $filter_date;
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/Inventory_old_report.tpl', $data));
	}


	public function download_all_stores_products()
	{

include_once DIR_SYSTEM .'/library/PHPExcel.php';
    include_once DIR_SYSTEM .'/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();

		/////////////2nd sheet start from here///////////
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("Stock Position")->setDescription("Stock Position");

    $objPHPExcel->setActiveSheetIndex(1); 
    $objPHPExcel->getActiveSheet()->setTitle("Stock Position");
    
    $this->load->model('report/product_report');
    $this->load->model('report/dscl_input_sales');
    $getproducts = $this->model_report_dscl_input_sales->getproducts();
    $get_stores=$this->model_report_dscl_input_sales->getstores($filter_data);
    $product_fields=array();
    foreach($getproducts as $products)
    { 
        array_push($product_fields,$products["model"]);
    
    }
    
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Product Name');
	$col2=1;
   foreach($get_stores as $stores)
    {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col2, 1, $stores['name']);
        	$col2=$col2+1;
    }
	
	$row = 2;

	foreach ($getproducts as $products)
    	{
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $products["model"]);
		$coll222=1;
		foreach($get_stores as $stores)
    		{
			$getproducts = $this->model_report_dscl_input_sales->getproductquantitybystore($stores["store_id"],$products["product_id"]);
        
        			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coll222, $row, $getproducts["quantity"]);
        
        			$coll222++;
		}
		$coll222=1;
    		$row++;
	}
  
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="DSCL_Store_Inventory_'.date('dMyhis').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');

	}


}