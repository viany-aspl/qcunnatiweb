<?php
class ControllerReportLeadger extends Controller {
	public function index() {
		$this->load->language('report/product_purchased');

		$this->document->setTitle("Store Ledger Report");

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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'] . $url, 'SSL')
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
			if($result['Mode']=='Sale Return')
			{
				$Withdrawals='';
			}
			else
			{
				$Withdrawals=$result['Withdrawals'];
			}
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$Withdrawals, 2, '.', ''),
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
		$data['heading_title'] = $this->language->get('heading_title');
		
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
		$pagination->url = $this->url->link('report/leadger', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

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
		  $this->response->setOutput($this->load->view('report/store_lazer_own.tpl', $data));
		}
		else
		{
                              $this->response->setOutput($this->load->view('report/store_lazer_franchise.tpl', $data));
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
			if($result['Mode']=='Sale Return')
			{
				$Withdrawals='';
			}
			else
			{
				$Withdrawals=$result['Withdrawals'];
			}
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$Withdrawals, 2, '.', ''),
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
		$data['closed_credit']=$ord_total['Credit_Balance'];
		$data['closed_balance']=$ord_total['Cash_Balance'];
		

		$data['token'] = $this->session->data['token'];

		if(($data['store_type_id']=="1") || ($data['store_type_id']=="2"))
		{
		 	$this->response->setOutput($this->load->view('report/store_ledger_pdf_own.tpl', $data));
		}
		else
		{
                              	$this->response->setOutput($this->load->view('report/store_ledger_pdf_franchise.tpl', $data));
		}

		
		
		
		 require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             		$mpdf = new mPDF('c','A4','','' , 0 , 0 , 25 , 10 , 5 , 7);
		if(($data['store_type_id']=="1") || ($data['store_type_id']=="2"))
		{
		 		$html=$this->load->view('report/store_ledger_pdf_own.tpl', $data);
		}
		else
		{
                              		$html=$this->load->view('report/store_ledger_pdf_franchise.tpl', $data);
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
                  
                	$footer = '<div class="footer" style="margin-top: -50px;">
                        
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
}