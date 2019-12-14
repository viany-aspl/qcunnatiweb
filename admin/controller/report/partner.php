<?php
class ControllerReportPartner extends Controller {

public function collection_report() {
		$this->load->language('report/product_purchased');

		$this->document->setTitle("Outstanding Report");

		
		if (isset($this->request->get['filter_stores_id'])) {
			$filter_stores_id = $this->request->get['filter_stores_id'];
		} else {
			$filter_stores_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		
		if (isset($this->request->get['filter_stores_id'])) {
			$url .= '&filter_stores_id=' . $this->request->get['filter_stores_id'];
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
			'text' => 'Outstanding Report',
			'href' => $this->url->link('report/partner/collection_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		//echo "here";
		
                	$this->load->model('report/partner');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);


		
		$ord_total = $this->model_report_partner->getTotal_Collection_Report($filter_data);
		$product_total=$ord_total['total'];
		$results = $this->model_report_partner->getCollection_Report($filter_data);
			
		//echo "here";
		
		foreach ($results as $result) {
			
				$actual_outstanding=$result['currentcredit']-$result['wallet_balance'];
			
			
			$data['products'][] = array(
				'name'      => $result['name'],
				'partner_name'      => $result['partner_name'],
				'address'      => $result['address'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'creditlimit'      => number_format((float)$result['creditlimit'], 2, '.', ''),
				'currentcredit'      => number_format((float)$result['currentcredit'], 2, '.', ''),
                'mobile'      => $result['username'],
				'wallet_balance'=>number_format((float)($result['wallet_balance']), 2, '.', ''),
				'actual_outstanding'=>number_format((float)($actual_outstanding), 2, '.', '') 
                            		);
		
		}

		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/leadger');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');
		$ord_total2 = $this->model_report_partner->getTotal_Credit($filter_data);

		$data['totalcredit']=$ord_total2['totalcredit'];;
		
		$data['heading_title'] = 'Outstanding Report';
		
		$data['text_list'] = 'Outstanding Report';
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		

		
		$data['column_total'] = $this->language->get('column_total');


		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
                	$this->load->model('setting/store');

		
                	$data['stores'] = $this->model_setting_store->getFranchiseStores(array());
		
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/partner/collection_report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
                
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		
		$data['filter_store']=$filter_stores_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
                              $this->response->setOutput($this->load->view('report/partner/collection_report.tpl', $data)); 
		
	}
public function collection_reportdownload_excel() {
		
		if (isset($this->request->get['filter_stores_id'])) {
			$filter_stores_id = $this->request->get['filter_stores_id'];
		} else {
			$filter_stores_id = 0;
		}

		
		
                	$this->load->model('report/partner');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);


		include_once '../system/library/PHPExcel.php';
    		include_once '../system/library/PHPExcel/IOFactory.php';
    		$objPHPExcel = new PHPExcel();
    
    		$objPHPExcel->createSheet();
    
    		$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    		$objPHPExcel->setActiveSheetIndex(0);

    		// Field names in the first row
    		$fields = array(
       			'Store Name',
        			'Partner Name',
        			'Location',
        			'Mobile',
        			'Credit Limit',
			'Current Outstanding',
			'Wallet Balance',
			'Actual Outstanding'
    		);
   
    		$col = 0;
    		foreach ($fields as $field)
    		{
        			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        			$col++;
    		}
    		 $results = $this->model_report_partner->getCollection_Report($filter_data);
		
    		$row = 2;
  
    		foreach($results as $data)
    		{   
					$actual_outstanding=$data['currentcredit']-$data['wallet_balance'];
					$col = 0;
        			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['partner_name']);
        			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['address']);
        			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['username']);
        			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, number_format((float)$data['creditlimit'], 2, '.', ''));
        			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, number_format((float)$data['currentcredit'], 2, '.', ''));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, number_format((float)$data['wallet_balance'], 2, '.', ''));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, number_format((float)($actual_outstanding), 2, '.', ''));
        			$row++;
    		}

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Collection_Report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
		
	}



	public function index() {
		$this->load->language('report/product_purchased');

		$this->document->setTitle("Partner Ledger Report");

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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => 'Dashboard',
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Partner Ledger Report',
			'href' => $this->url->link('report/partner', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                	$this->load->model('report/storelazer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		

		if($filter_stores_id!="")
		{
			if(($data['store_type_id']=="1") || ($data['store_type_id']=="2"))
			{
		 		$ord_total = $this->model_report_storelazer->getTotalTransaction_Own($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Own($filter_data);
			}
			else
			{
                              		$ord_total = $this->model_report_storelazer->getTotalTransaction_Franchise($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Franchise($filter_data);
			}
		
		}
		foreach ($results as $result) {
			
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'Credit_Balance'      => number_format((float)$result['Credit_Balance'], 2, '.', ''),
				 'Cash_Balance'      => number_format((float)$result['Cash_Balance'], 2, '.', ''),
                                                        'remarks'      => $result['remarks'],
				'order_id'   => $result['order_id'],
				'store_name'      => $result['store_name'],
                            		'user_Name'      => $result['user_Name']
                            		);
		
		}

		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/leadger');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');

		$data['closed_credit']=$ord_total['Credit_Balance'];
		$data['closed_balance']=$ord_total['Cash_Balance'];
		$data['heading_title'] = 'Partner Ledger Report';
		
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

		
                	$data['order_stores'] = $this->model_setting_store->getFranchiseStores(array());
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
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/partner', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
                
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
		$data['filter_stores_id']=$filter_stores_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		if(($data['store_type_id']=="1") || ($data['store_type_id']=="2"))
		{
		  $this->response->setOutput($this->load->view('report/partner/store_lazer_own.tpl', $data));
		}
		else
		{
                              $this->response->setOutput($this->load->view('report/partner/store_lazer_franchise.tpl', $data));
		}
	}



	public function download_pdf() {
		
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


		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;

               	 $this->load->model('report/storelazer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id
		);

		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		

		if($filter_stores_id!="")
		{
			if(($data['store_type_id']=="1") || ($data['store_type_id']=="2"))
			{
		 		$ord_total = $this->model_report_storelazer->getTotalTransaction_Own($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Own($filter_data);
			}
			else
			{
                              		$ord_total = $this->model_report_storelazer->getTotalTransaction_Franchise($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Franchise($filter_data);
			}
		
		}
		
		foreach ($results as $result) {
			
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'Credit_Balance'      => number_format((float)$result['Credit_Balance'], 2, '.', ''),
				 'Cash_Balance'      => number_format((float)$result['Cash_Balance'], 2, '.', ''),
                                                        'remarks'      => $result['remarks'],
				'order_id'   => $result['order_id'],
				
                            
                           
                            
                            'store_name'      => $result['store_name'],
                            'user_Name'      => $result['user_Name']
                            
                                
			);
		if($filter_stores_id!="")
		{
		$data['store_name']=$result['store_name'];
		}
		
		}
		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		$data['closed_credit']=number_format((float)$ord_total['Credit_Balance'], 2, '.', '');
		$data['closed_balance']=number_format((float)$ord_total['Cash_Balance'], 2, '.', '');
		

		$data['token'] = $this->session->data['token'];

		if(($data['store_type_id']=="1") || ($data['store_type_id']=="2"))
		{
		 	$this->response->setOutput($this->load->view('report/partner/store_ledger_pdf_own.tpl', $data));
		}
		else
		{
                              	$this->response->setOutput($this->load->view('report/partner/store_ledger_pdf_franchise.tpl', $data));
		}

		
		
		
		 require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             		$mpdf = new mPDF('c','A4','','' , 0 , 0 , 25 , 10 , 5 , 7);
		if(($data['store_type_id']=="1") || ($data['store_type_id']=="2"))
		{
		 		$html=$this->load->view('report/partner/store_ledger_pdf_own.tpl', $data);
		}
		else
		{
                              		$html=$this->load->view('report/partner/store_ledger_pdf_franchise.tpl', $data);
		}
		

		$header = '<br/><div class="header" style="margin-top: 20px;">
		<div class="logo">
		<img style="float: right;margin-right: 40px;height: 30px;" src="https://unnati.world/shop/image/catalog/logo.png"  />
		</div>
 		
		<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;;" /> 
			</div>';
                
                	$header = '<div class="header" style="">
                   
			<div class="logo" style="width: 100%;" >
			<div style="padding-left: 50px;">
			<img src="https://unnati.world/shop/image/letterhead_text.png" style="height: 40px; width: 121px;margin-top: 20px;marging-left: 100px;" />
			<img src="https://unnati.world/shop/image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />
			</div>
                        		 </div>
			<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;" /> 

			</div>';
                	$mpdf->setAutoTopMargin = 'stretch';
                	$mpdf->SetHTMLHeader($header, 'O', false);
                  
                	$footer = '<div class="footer" style="margin-top: 40px;">
                        
                        		<img src="https://unnati.world/shop/image/letterhead_bottomline.png" style="height: 10px; width: 150% !important;" /> 
                        		<div class="address"><img src="../image/letterhead_footertext.png" style="width: 120% !important;" /> </div>'
                        		. '</div>';

                	$mpdf->setAutoBottomMargin = 'stretch';       	 
                	$mpdf->SetHTMLFooter($footer);
                    
                	$mpdf->SetDisplayMode('fullpage');
    
                	$mpdf->list_indent_first_level = 0;
    
                	$mpdf->WriteHTML($html);
                
                	$filename='store_statement_'.$filter_date_start.'-'.$filter_date_end.'.pdf';
                
                	$mpdf->Output($filename,'D');
		
		
	}
	public function download_excel() {
		
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


		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;

               	 $this->load->model('report/storelazer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id
		);

		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		

		if($filter_stores_id!="")
		{
			if(($data['store_type_id']=="1") || ($data['store_type_id']=="2"))
			{
		 		$ord_total = $this->model_report_storelazer->getTotalTransaction_Own($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Own($filter_data);
			}
			else
			{
                              		$ord_total = $this->model_report_storelazer->getTotalTransaction_Franchise($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Franchise($filter_data);
			}
		
		}
		
		foreach ($results as $result) 
		{
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'Credit_Balance'      => number_format((float)$result['Credit_Balance'], 2, '.', ''),
				 'Cash_Balance'      => number_format((float)$result['Cash_Balance'], 2, '.', ''),
                                                        'remarks'      => $result['remarks'],
				'order_id'   => $result['order_id'],
				'store_name'      => $result['store_name'],
                            		'user_Name'      => $result['user_Name']
                            		);
				if($filter_stores_id!="")
				{
					$data['store_name']=$result['store_name'];
				}
		}
		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		$data['closed_credit']=number_format((float)$ord_total['Credit_Balance'], 2, '.', '');
		$data['closed_balance']=number_format((float)$ord_total['Cash_Balance'], 2, '.', '');
		
		//print_r($data);
		
		$html='
		<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Sheet 1</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
</head>

<body>
	
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Date</td>
                <td class="text-left">Transaction Type</td>
                <td class="text-right">Transaction No.</td>
                <td class="text-right"> Payment Received (CR)</td>
                <td class="text-right">Invoice (DB)</td>
                <td class="text-right">Balance</td>
               <td class="text-right">Remarks</td>
              </tr>
            </thead>
            <tbody>';
	$$allDeposite=0;
	$allWithdrawals=0;
              foreach($data['products'] as $product)
	{

			if($product['Mode']=="Cash")
			{
				$mode= "SALE IN CASH"; 
			}
			else if($product['Mode']=="Tagged Cash")
			{
				$mode= "SALE IN TAGGED CASH"; 
			}
			else if($product['Mode']=="Tagged")
			{
				$mode= "SALE IN TAGGED"; 
			}
			else if($product['Mode']=="Subsidy")
			{
				$mode= "SALE IN SUBSIDY"; 
			}
			else if($product['Mode']=="CASHDEPOSIT")
			{
				$mode= "CASH IN HAND DEPOSIT"; 
			}
			else if($product['Mode']=="ST")
			{
				$mode= "STOCK TRANSFER"; 
			}
			else if($product['Mode']=="SR")
			{
				$mode= "STOCK RECEIVED"; 
			}
			else if($product['Mode']=="PO")
			{
				$mode= "INVOICE"; 
			}
			else if($product['Mode']=="EXPWOFF")
			{
				$mode= "EXPENSE"; 
			}
			else if($product['Mode']=="WOFF")
			{
				$mode= "WAIVER"; 
			}
			else
			{
				$mode= $product['Mode'];
			}
			 if($product['Deposite']!='0.00') 
			 { 
				$Deposite=$product['Deposite'];
			 }
			else
			{
				$Deposite='';
			}
			if($product['Withdrawals']!='0.00') 
			 { 
				$Withdrawals=$product['Withdrawals'];
			 }
			else
			{
				$Withdrawals='';
			}
              $html=$html.'<tr>
                <td class="text-left">'.date('d-m-Y',strtotime($product['Date'])).'</td>
                <td class="text-left">'.$mode.'</td>
                <td class="text-right">'.$product['order_id'].'</td>
                <td class="text-right">'.$Deposite.'</td>
                <td class="text-right">'.$Withdrawals.'</td>
                <td class="text-right">'.$product['Credit_Balance'].'</td>
                <td class="text-left" style="max-width: 200px;">'.$product['remarks'].'</td>
              </tr>';
	$allDeposite=$allDeposite+$Deposite;
	$allWithdrawals=$allWithdrawals+$Withdrawals;
              }
	$html=$html.'<tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-right">Total : </td>
                <td class="text-right">'.$allDeposite.'</td>
                <td class="text-right">'.$allWithdrawals.'</td>
                <td class="text-right"></td>
                <td class="text-left" style="max-width: 200px;"></td>
              </tr>';

	$html=$html.'<tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-right">Current Outstanding  : </td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right">'.($allDeposite-$allWithdrawals).'</td>
                <td class="text-left" style="max-width: 200px;"></td>
              </tr>';

            $html=$html.'</tbody>
          </table></body></html>';
	
	$file=$data['store_name']."_".$filter_date_start."_".$filter_date_end."_leadger_.xls";
	$file=str_replace(' ','-',$file);
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$file");
	echo $html;
		
	}
	public function partner_billing() {
		$this->load->language('report/product_purchased');

		$this->document->setTitle("Partner Billing");

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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => 'Dashboard',
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Partner Billing',
			'href' => $this->url->link('report/partner/partner_billing', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                	$this->load->model('report/storelazer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		

		if($filter_stores_id!="")
		{
			
                              		$ord_total = $this->model_report_storelazer->getTotalTransaction_Franchise_partner_billing($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Franchise_partner_billing($filter_data);
			
		
		}
		foreach ($results as $result) {
			$order_d="&order_id=".$result['order_id'];
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'Credit_Balance'      => number_format((float)$result['Credit_Balance'], 2, '.', ''),
				 'Cash_Balance'      => number_format((float)$result['Cash_Balance'], 2, '.', ''),
                                                        'remarks'      => $result['remarks'],
				'order_id'   => $result['order_id'],
				'store_name'      => $result['store_name'],
                            		'user_Name'      => $result['user_Name'],
				'party_name'      => $result['party_name'],
				'paid_status'      => $result['paid_status'],
				'product_name'      => $result['product_name'],
				'p_qnty'      => $result['p_qnty'],
				'p_price'      => $result['p_price'],
				'p_amount'      => $result['p_amount'],

				'download_link'=>$this->url->link('partner/purchase_order/download_invoice', 'token=' . $this->session->data['token'] . $order_d , 'SSL'),
				'mail_link'=>$this->url->link('partner/purchase_order/email_invoice', 'token=' . $this->session->data['token'] . $order_d , 'SSL')
                            		);
		
		}

		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/leadger');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');

		$data['closed_credit']=$ord_total['Credit_Balance'];
		$data['closed_balance']=$ord_total['Cash_Balance'];
		$data['heading_title'] = 'Partner Billing';
		
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

		
                	$data['order_stores'] = $this->model_setting_store->getFranchiseStores(array());
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
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/partner/partner_billing', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
                
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
		$data['filter_stores_id']=$filter_stores_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('report/partner/store_lazer_franchise_partner_billing.tpl', $data));
		
	}

	public function download_pdf_partner_billing() {
		
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


		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;

               	 $this->load->model('report/storelazer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id
		);

		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		

		if($filter_stores_id!="")
		{
			
                              		$ord_total = $this->model_report_storelazer->getTotalTransaction_Franchise_partner_billing($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Franchise_partner_billing($filter_data);
			
		
		}
		foreach ($results as $result) {
			$order_d="&order_id=".$result['order_id'];
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'Credit_Balance'      => number_format((float)$result['Credit_Balance'], 2, '.', ''),
				 'Cash_Balance'      => number_format((float)$result['Cash_Balance'], 2, '.', ''),
                                                        'remarks'      => $result['remarks'],
				'order_id'   => $result['order_id'],
				'store_name'      => $result['store_name'],
                            		'user_Name'      => $result['user_Name'],
				'party_name'      => $result['party_name'],
				'paid_status'      => $result['paid_status'],
				'download_link'=>$this->url->link('partner/purchase_order/download_invoice', 'token=' . $this->session->data['token'] . $order_d , 'SSL')
                            		);
		
		}

		if($filter_stores_id!="")
		{
		$data['store_name']=$result['store_name'];
		}
		
		
		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		$data['closed_credit']=number_format((float)$ord_total['Credit_Balance'], 2, '.', '');
		$data['closed_balance']=number_format((float)$ord_total['Cash_Balance'], 2, '.', '');
		

		$data['token'] = $this->session->data['token'];

		
                              	//$this->response->setOutput($this->load->view('report/partner/store_ledger_pdf_franchise_partner_billing.tpl', $data));
		

		
		
		
		 require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             		$mpdf = new mPDF('c','A4','','' , 0 , 0 , 25 , 10 , 5 , 7);
		
                              		$html=$this->load->view('report/partner/store_ledger_pdf_franchise_partner_billing.tpl', $data);
		
		

		$header = '<br/><div class="header" style="margin-top: 20px;">
		<div class="logo">
		<img style="float: right;margin-right: 40px;height: 30px;" src="https://unnati.world/shop/image/catalog/logo.png"  />
		</div>
 		
		<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;;" /> 
			</div>';
                
                	$header = '<div class="header" style="">
                   
			<div class="logo" style="width: 100%;" >
			<div style="padding-left: 50px;">
			<img src="https://unnati.world/shop/image/letterhead_text.png" style="height: 40px; width: 121px;margin-top: 20px;marging-left: 100px;" />
			<img src="https://unnati.world/shop/image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />
			</div>
                        		 </div>
			<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;" /> 

			</div>';
                	$mpdf->setAutoTopMargin = 'stretch';
                	$mpdf->SetHTMLHeader($header, 'O', false);
                  
                	$footer = '<div class="footer" style="margin-top: 40px;">
                        
                        		<img src="https://unnati.world/shop/image/letterhead_bottomline.png" style="height: 10px; width: 150% !important;" /> 
                        		<div class="address"><img src="../image/letterhead_footertext.png" style="width: 120% !important;" /> </div>'
                        		. '</div>';

                	$mpdf->setAutoBottomMargin = 'stretch';       	 
                	$mpdf->SetHTMLFooter($footer);
                    
                	$mpdf->SetDisplayMode('fullpage');
    
                	$mpdf->list_indent_first_level = 0;
    
                	$mpdf->WriteHTML($html);
                
                	$filename='store_statement_'.$filter_date_start.'-'.$filter_date_end.'.pdf';
                
                	$mpdf->Output($filename,'D');
		
		
	}
	public function download_excel_partner_billing() {
		
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


		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;

               	 $this->load->model('report/storelazer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id
		);

		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		

		if($filter_stores_id!="")
		{
			
                              		$ord_total = $this->model_report_storelazer->getTotalTransaction_Franchise_partner_billing($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Franchise_partner_billing($filter_data);
			
		
		}
		foreach ($results as $result) {
			$order_d="&order_id=".$result['order_id'];
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'Credit_Balance'      => number_format((float)$result['Credit_Balance'], 2, '.', ''),
				 'Cash_Balance'      => number_format((float)$result['Cash_Balance'], 2, '.', ''),
                                                        'remarks'      => $result['remarks'],
				'order_id'   => $result['order_id'],
				'store_name'      => $result['store_name'],
                            		'user_Name'      => $result['user_Name'],
				'party_name'      => $result['party_name'],
				'paid_status'      => $result['paid_status'],
				'download_link'=>$this->url->link('partner/purchase_order/download_invoice', 'token=' . $this->session->data['token'] . $order_d , 'SSL')
                            		);
		
		}

		if($filter_stores_id!="")
		{
		$data['store_name']=$result['store_name'];
		}
		
		
		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		$data['closed_credit']=number_format((float)$ord_total['Credit_Balance'], 2, '.', '');
		$data['closed_balance']=number_format((float)$ord_total['Cash_Balance'], 2, '.', '');
		$html='
		<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Sheet 1</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
</head>

<body>
	
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Party Name</td>
                <td class="text-left">Store Name</td>
                <td class="text-right">Date of Invoice</td>
                <td class="text-right"> Invoice Number</td>
                <td class="text-right">Amount</td>
                <td class="text-right">Payment Status</td>
               
              </tr>
            </thead>
            <tbody>';
	
              foreach($data['products'] as $product)
	{

			if($product['Withdrawals']!='0.00') 
			{ 
				$Withdrawals= $product['Withdrawals']; 
			}
			else
			{
				$Withdrawals='';
			}
			if($product['paid_status']=="0")
			{ 
				$paid_status= "Un-Paid"; 
			} 
			else if($product['paid_status']=="1")
			{ 
				$paid_status= "Paid"; 
			}
              $html=$html.'<tr>
                
                <td class="text-left">'.$product['party_name'].'</td>
				<td class="text-left">'.$product['store_name'].'</td>
				<td class="text-left">'.date('d-m-Y',strtotime($product['Date'])).'</td>
				<td class="text-left">'.$product['order_id'].'</td>
                <td class="text-left">'.$Withdrawals.'</td>
                <td class="text-left" style="max-width: 200px;">'.$paid_status.'</td>
	
              </tr>';
	
              }
	

	

            $html=$html.'</tbody>
          </table></body></html>';
	
	$file=$data['store_name']."_".$filter_date_start."_".$filter_date_end."_billing.xls";
	$file=str_replace(' ','-',$file);
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$file");
	echo $html;
		
	}

	public function payment_received() {
		$this->load->language('report/product_purchased');

		$this->document->setTitle("Partner Payment Received");

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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => 'Dashboard',
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Partner Payment Received',
			'href' => $this->url->link('report/partner/payment_received', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                	$this->load->model('report/storelazer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		

		if($filter_stores_id!="")
		{
			
                              		$ord_total = $this->model_report_storelazer->getTotalTransaction_Franchise_payment_received($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Franchise_payment_received($filter_data);
				if(empty($results))
				{
				$data['text_no_results'] = 'No result Found';
				}
		}
		else
		{
			$data['text_no_results'] = 'Please Select Store';
		}
		foreach ($results as $result) { 
			
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'transaction_type'      => $result['transaction_type'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'Credit_Balance'      => number_format((float)$result['Credit_Balance'], 2, '.', ''),
				 'Cash_Balance'      => number_format((float)$result['Cash_Balance'], 2, '.', ''),
                                                        'remarks'      => $result['remarks'],
				'order_id'   => $result['order_id'],
				'store_name'      => $result['store_name'],
                            		'user_Name'      => $result['user_Name']
                            		);
		
		}

		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/leadger');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');

		$data['closed_credit']=$ord_total['Credit_Balance'];
		$data['closed_balance']=$ord_total['Cash_Balance'];
		$data['heading_title'] = 'Partner Payment Received';
		
		$data['text_list'] = $this->language->get('text_list');
		
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

		
                	$data['order_stores'] = $this->model_setting_store->getFranchiseStores(array());
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
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/partner/payment_received', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
                
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
		$data['filter_stores_id']=$filter_stores_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
                              $this->response->setOutput($this->load->view('report/partner/store_payment_received_franchise.tpl', $data));
		
	}

	public function download_pdf_payment_received() {
		
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


		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;

               	 $this->load->model('report/storelazer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id
		);

		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		

		if($filter_stores_id!="")
		{
			
                              		$ord_total = $this->model_report_storelazer->getTotalTransaction_Franchise_payment_received($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Franchise_payment_received($filter_data);
			
		
		}
		foreach ($results as $result) {
			$order_d="&order_id=".$result['order_id'];
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'transaction_type'      => $result['transaction_type'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'Credit_Balance'      => number_format((float)$result['Credit_Balance'], 2, '.', ''),
				 'Cash_Balance'      => number_format((float)$result['Cash_Balance'], 2, '.', ''),
                                                        'remarks'      => $result['remarks'],
				'order_id'   => $result['order_id'],
				'store_name'      => $result['store_name'],
                            		'user_Name'      => $result['user_Name'],
				'party_name'      => $result['party_name'],
				'paid_status'      => $result['paid_status'],
				'download_link'=>$this->url->link('partner/purchase_order/download_invoice', 'token=' . $this->session->data['token'] . $order_d , 'SSL')
                            		);
		
		}

		if($filter_stores_id!="")
		{
		$data['store_name']=$result['store_name'];
		}
		
		
		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		$data['closed_credit']=number_format((float)$ord_total['Credit_Balance'], 2, '.', '');
		$data['closed_balance']=number_format((float)$ord_total['Cash_Balance'], 2, '.', '');
		

		$data['token'] = $this->session->data['token'];

		
                              	//$this->response->setOutput($this->load->view('report/partner/store_ledger_pdf_franchise_partner_billing.tpl', $data));
		

		
		
		
		 require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             		 $mpdf = new mPDF('c','A4','','' , 0 , 0 , 25 , 10 , 5 , 7);
		
                             $html=$this->load->view('report/partner/store_ledger_pdf_franchise_payment_received.tpl', $data);
		
		

		$header = '<br/><div class="header" style="margin-top: 20px;">
		<div class="logo">
		<img style="float: right;margin-right: 40px;height: 30px;" src="https://unnati.world/shop/image/catalog/logo.png"  />
		</div>
 		
		<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;;" /> 
			</div>';
                
                	$header = '<div class="header" style="">
                   
			<div class="logo" style="width: 100%;" >
			<div style="padding-left: 50px;">
			<img src="https://unnati.world/shop/image/letterhead_text.png" style="height: 40px; width: 121px;margin-top: 20px;marging-left: 100px;" />
			<img src="https://unnati.world/shop/image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />
			</div>
                        		 </div>
			<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;" /> 

			</div>';
                	$mpdf->setAutoTopMargin = 'stretch';
                	$mpdf->SetHTMLHeader($header, 'O', false);
                  
                	$footer = '<div class="footer" style="margin-top: 40px;">
                        
                        		<img src="https://unnati.world/shop/image/letterhead_bottomline.png" style="height: 10px; width: 150% !important;" /> 
                        		<div class="address"><img src="../image/letterhead_footertext.png" style="width: 120% !important;" /> </div>'
                        		. '</div>';

                	$mpdf->setAutoBottomMargin = 'stretch';       	 
                	$mpdf->SetHTMLFooter($footer);
                    
                	$mpdf->SetDisplayMode('fullpage');
    
                	$mpdf->list_indent_first_level = 0;
    
                	$mpdf->WriteHTML($html);
                
                	$filename='store_statement_'.$filter_date_start.'-'.$filter_date_end.'.pdf';
                
                	$mpdf->Output($filename,'D'); 
		
		
	}
	public function download_excel_payment_received() {
		
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


		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;

               	 $this->load->model('report/storelazer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id
		);

		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		

		if($filter_stores_id!="")
		{
			
                              		$ord_total = $this->model_report_storelazer->getTotalTransaction_Franchise_payment_received($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Franchise_payment_received($filter_data);
			
		
		}
		foreach ($results as $result) {
			$order_d="&order_id=".$result['order_id'];
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'transaction_type'      => $result['transaction_type'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'Credit_Balance'      => number_format((float)$result['Credit_Balance'], 2, '.', ''),
				 'Cash_Balance'      => number_format((float)$result['Cash_Balance'], 2, '.', ''),
                                                        'remarks'      => $result['remarks'],
				'order_id'   => $result['order_id'],
				'store_name'      => $result['store_name'],
                            		'user_Name'      => $result['user_Name'],
				'party_name'      => $result['party_name'],
				'paid_status'      => $result['paid_status'],
				'download_link'=>$this->url->link('partner/purchase_order/download_invoice', 'token=' . $this->session->data['token'] . $order_d , 'SSL')
                            		);
		
		}

		if($filter_stores_id!="")
		{
		$data['store_name']=$result['store_name'];
		}
		
		
		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		$data['closed_credit']=number_format((float)$ord_total['Credit_Balance'], 2, '.', '');
		$data['closed_balance']=number_format((float)$ord_total['Cash_Balance'], 2, '.', '');
		

		$data['token'] = $this->session->data['token'];

		$html='
		<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Sheet 1</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
</head>

<body>
	
          <table class="table table-bordered">
            <thead>
               <tr>
              
                <td class="text-left">Party Name</td>
				<td class="text-left">Date of Payment</td>
                <td class="text-left">Transaction Number</td> 
                <td class="text-left"> Amount</td>
				<td class="text-left">Transaction type</td>
                <td class="text-left">Mode of payment</td>
              </tr>
            </thead>
            <tbody>';
	
              foreach($data['products'] as $product)
	{

			if($product['Deposite']!='0.00') 
			{ 
				$Deposite= $product['Deposite']; 
			}
			else
			{
				$Deposite='';
			}
              $html=$html.'<tr>
				<td class="text-left">'.$product['store_name'].'</td>
                <td class="text-left">'.date('d-m-Y',strtotime($product['Date'])).'</td>
				<td class="text-left">'.$product['order_id'].'</td>
                <td class="text-left">'.$Deposite.'</td>
                <td class="text-left">'.$product['transaction_type'].'</td>
                <td class="text-left">'.$product['Mode'].'</td>
              </tr>';
	
              }
	

	

            $html=$html.'</tbody>
          </table></body></html>';
	
	$file=$data['store_name']."_".$filter_date_start."_".$filter_date_end."_payment_received.xls";
	$file=str_replace(' ','-',$file);
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$file");
	echo $html;
	}
    public function outstanding() {
		$this->load->language('report/product_purchased');

		$this->document->setTitle("Outstanding Report");

		
		if (isset($this->request->get['filter_stores_id'])) {
			$filter_stores_id = $this->request->get['filter_stores_id'];
		} else {
			$filter_stores_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		
		if (isset($this->request->get['filter_stores_id'])) {
			$url .= '&filter_stores_id=' . $this->request->get['filter_stores_id'];
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
			'text' => 'Outstanding Report',
			'href' => $this->url->link('report/partner/collection_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		//echo "here";
		
                	$this->load->model('report/partner');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);


		
		$ord_total = 0;//$this->model_report_partner->getTotal_Collection_Report($filter_data);
		$product_total=$ord_total['total'];
		$results = $this->model_report_partner->getOutstanding($filter_data);
			
		//echo "here";
		
		foreach ($results as $result) {
			
			$data['products'][] = array(
				'name'      => $result['name'],
				'partner_name'      => $result['partner_name'],
				'partner_name'      => $result['partner_name'],
				'partner_name'      => $result['partner_name'],
				'partner_name'      => $result['partner_name'],
				'partner_name'      => $result['partner_name'],
				'partner_name'      => $result['partner_name']
                            		);
		
		}

		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/leadger');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');
		$ord_total2 = $this->model_report_partner->getTotal_Credit($filter_data);

		$data['totalcredit']=$ord_total2['totalcredit'];;
		
		$data['heading_title'] = 'Outstanding Report';
		
		$data['text_list'] = 'Outstanding Report';
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		

		
		$data['column_total'] = $this->language->get('column_total');


		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
                	$this->load->model('setting/store');

		
                	$data['stores'] = $this->model_setting_store->getFranchiseStores(array());
		
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/partner/collection_report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
                
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		
		$data['filter_store']=$filter_stores_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
                              $this->response->setOutput($this->load->view('report/partner/collection_report.tpl', $data)); 
		
	}   
}