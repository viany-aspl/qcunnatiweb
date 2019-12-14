<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(1);
ini_set('max_execution_time', 600); //600 seconds = 10 minutes

class ControllerReportDocumentUpload extends Controller {
	public function index() {
		//$this->load->language('report/customer');

		$this->document->setTitle("Document Upload Report");
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

				
        if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
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
			'href' => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/customer');
                       // $this->load->model('setting/store');
		$data['orders'] = array();

		$filter_data = array(
            'filter_user' => $filter_user,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

                         
		$order_total = $this->model_report_customer->getTOtaldocUpload($filter_data);
	   
		 $results = $this->model_report_customer->getdocUpload($filter_data);
	   
//print_r($results); 
//exit;
		foreach ($results as $result) { //print_r($result);
			   $data['orders'][] = array(
			
                'document_description' => $result['document_description'],
				'sid' => $result['sid'],
                'dat' => $result['dat'],
                'remarks'     => $result['remarks'],
                'storename'     => $result['storename'],
                'username' => $result['username']
            );
		}
 
		$data['heading_title'] = $this->language->get('heading_title');
		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/product_sales');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');
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
               
		

		$url = '';

	


		//echo $url;
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/document_upload', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
//echo $order_total; exit;
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

                        $data['filter_name_id'] = $filter_name_id;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
			$data['filter_user'] = $filter_user;
        $data['getuser'] = $this->model_report_customer->getUser();
		$this->response->setOutput($this->load->view('report/doc_upload_report.tpl', $data));
	}
	
	public function download_excel() {
		
		
        if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
 		
        $this->load->model('report/customer');
		
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_user'	     => $filter_user
		);
               
		//print_r($filter_user);
		$data['orders'] = array();
            $results = $this->model_report_customer->getdocUpload($filter_data);                     
		//print_r($results); exit;
		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'User Name',
        'Store Name',
		'Document Type',
        'Remarks',
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
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['username']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['storename']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['document_description']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['remarks']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['dat']);

        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Document Upload Report_'.date('d-M-y').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
	
	
	
	
	public function modaldocdisplay(){


 $this->load->model('report/customer');

$sid=$this->request->get['sid'];
//echo $sid;


$imagename= $this->model_report_customer->modaldocdisplayimg($sid);

		$this->response->setOutput($imagename);

}
}