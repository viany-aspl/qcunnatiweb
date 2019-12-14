<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerReportisecStores extends Controller {
	public function index() {
		$this->load->language('report/stock_transfer');

		$this->document->setTitle("Store's Users");

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
		$url = '';

		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => "Store's Users",
			'href' => $this->url->link('reportisec/stores', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/stores');
                $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_company'        => '3',
		'filter_store'=>$filter_store,
			'filter_name'=>$filter_name,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_report_stores->getTotalStores_companywise($filter_data);

		$results = $this->model_report_stores->getStores_companywise($filter_data);

		foreach ($results as $result) { //print_r($result);
                
                
			$data['orders'][] = array(
				'store_name'   => $result['name'],
				
				'store_id'     => $result['store_id'],
				
                                'telephone'   => $result['username'],
                                'name'   => $result['firstname']." ". $result['lastname']
                                );
		}

		$data['heading_title'] = 'Stores';
		
		
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('3');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportisec/stores', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

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
		$this->response->setOutput($this->load->view('reportisec/view_stores.tpl', $data));
	}
         public function download_excel() {
        
        $this->load->model('report/stores');
        if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
        

		$data['orders'] = array();

		$filter_data = array(
			'filter_company'      => '3',
			'filter_store'=>$filter_store,
			'filter_name'=>$filter_name,
			'order'                => $order
			
		);


    $results = $this->model_report_stores->getStores_companywise($filter_data);

        
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        
        'Name',
        'Telephone',
        'Store name',
        'Store ID'
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
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['firstname']." ".$data["lastname"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['username']);
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="stores-user'.'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
    
    }
}