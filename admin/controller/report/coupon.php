<?php
class ControllerReportCoupon extends Controller {
	
	public function history() {
		$this->load->language('report/Inventory_report');

		$this->document->setTitle('Coupon History');

		if (isset($this->request->get['filter_coupon'])) {
			$filter_coupon = $this->request->get['filter_coupon'];
		} else {
			$filter_coupon = '';
		}
                
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
                
               		 if ($this->request->get['filter_coupon']!="") {
			$url .= '&filter_coupon=' . $this->request->get['filter_coupon'];
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
			'text' => 'Coupon History',
			'href' => $this->url->link('report/coupon/history', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('setting/store');
                		$this->load->model('marketing/coupon');
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_coupon' => $filter_coupon,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		

		$data['orders'] = array();
 		if ($this->request->get['filter_coupon']!="") 
		{
     			
     			$results = $this->model_marketing_coupon->getCouponHistories($this->request->get['filter_coupon'], ($page - 1) * 20, 20);

		

			$order_total = $this->model_marketing_coupon->getTotalCouponHistories($this->request->get['filter_coupon']);

 
		 }
		foreach ($results as $result) {
			$data['histories'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'amount'     => $result['amount'],
				'total'     => $result['total'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}
		$data['heading_title'] = 'Coupon History';
		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/coupon/history');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');
		$data['text_list'] = $this->language->get('text_list');
		
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
		$this->load->model('marketing/coupon');
                            $data['coupons']  = $this->model_marketing_coupon->getAllCoupons(); //$this->model_setting_store->getStores();//print_r($data['stores'] );
		
		$url = '';

               		 if ($this->request->get['filter_coupon']!="") {
			$url .= '&filter_coupon=' . $this->request->get['filter_coupon'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/coupon/history', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['filter_coupon'] = $filter_coupon;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		//print_r($data['coupons']);
		$this->response->setOutput($this->load->view('report/coupon_history.tpl', $data));
	}
	public function history_download() {
		
		if (isset($this->request->get['filter_coupon'])) {
			$filter_coupon = $this->request->get['filter_coupon'];
		} else {
			$filter_coupon = '';
		}
                
                		$this->load->model('marketing/coupon');
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_coupon' => $filter_coupon,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		

		$data['orders'] = array();
 		if ($this->request->get['filter_coupon']!="") 
		{
     			
     			$results = $this->model_marketing_coupon->getCouponHistories($this->request->get['filter_coupon'], ($page - 1) * 20, 20);


 
		 }
		
	include_once '../system/library/PHPExcel.php';
    	include_once '../system/library/PHPExcel/IOFactory.php';
   	 $objPHPExcel = new PHPExcel();
    
    	$objPHPExcel->createSheet();
    
    	$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    	$objPHPExcel->setActiveSheetIndex(0);

   	// Field names in the first row
    	$fields = array(
       
        	'Order ID',
        	'Customer',
        	'Discount Amount',
	'Order Total',
        	'Date Added'
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
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['order_id']);
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['customer']);
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['amount']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['total']);
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date($this->language->get('date_format_short'), strtotime($data['date_added'])));
        
        		$row++;
    	}

    

    
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
   	 // Sending headers to force the user to download the file
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename="Coupon_history_'.date('dMy').'.xls"');
    	header('Cache-Control: max-age=0');

    	$objWriter->save('php://output');
	}
}
