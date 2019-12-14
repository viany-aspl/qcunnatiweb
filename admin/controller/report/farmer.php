<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerReportFarmer extends Controller {
	
public function index() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Willowood Farmers');

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
		if (isset($this->request->get['filter_scheme'])) {
			$filter_scheme = $this->request->get['filter_scheme'];
		} else {
			$filter_scheme = '';
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
		if (isset($this->request->get['filter_scheme'])) {
			$url .= '&filter_scheme=' . $this->request->get['filter_scheme'];
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
			'text' => 'Willowood Farmers',
			'href' => $this->url->link('report/farmer/willowood', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                            $this->load->model('report/farmer');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'scheme'=>$filter_scheme,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		if($filter_scheme!="")
		{
		$order_total = $this->model_report_farmer->getTotalFarmers($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_farmer->getFarmers($filter_data);
                
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				
				'firstname'   => $result['firstname'],
				'telephone'      => $result['telephone'],
				'scheme'     => $result['scheme'],
                                                        'date_added'=>  date($this->language->get('date_format_short'), strtotime($result['date_added'])),   
				
			);
		}
		}
		$data['heading_title'] ='Willowood Farmers';
		
		$data['text_list'] = 'Willowood Farmers';
		if($filter_scheme!="")
		{
		$data['text_no_results'] = 'No result found';
		}
		else
		{
		$data['text_no_results'] = 'Please select a Scheme';
		}
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

		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/farmer/willowood', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_scheme'] = $filter_scheme;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['schemes'][]=array('name'=>'Willowood');                          

		$this->response->setOutput($this->load->view('report/farmer/farmer_list.tpl', $data));
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
		if (isset($this->request->get['filter_scheme'])) {
			$filter_scheme = $this->request->get['filter_scheme'];
		} else {
			$filter_scheme = '';
		}


        		$this->load->model('report/farmer');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'scheme'=>$filter_scheme,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		if($filter_scheme!="")
		{
		$order_total = $this->model_report_farmer->getTotalFarmers($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_farmer->getFarmers($filter_data);
                
		
		}
                //print_r($results);exit;
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();  
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Farmer Name',
        'Mobile Number',
        'Scheme',
        'Registration date'
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
     
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['firstname']." ".$data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['telephone']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['scheme']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date('Y-m-d',strtotime($data['date_added'])));
        
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Farmers_list_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
       }
}