<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(1);
ini_set('max_execution_time', 600); //600 seconds = 10 minutes

global $reg;
class ControllerReportbcmlReportFm extends Controller {
	
	 public function adminmodel($model) {
      
      $admin_dir = DIR_SYSTEM; 
      $admin_dir = str_replace('system/','admin/',$admin_dir);
      $file = $admin_dir . 'model/' . $model . '.php';      
      //$file  = DIR_APPLICATION . 'model/' . $model . '.php';
      $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
      
      if (file_exists($file)) {
         include_once($file);
         
         $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
      } else {
         trigger_error('Error: Could not load model ' . $model . '!');
         exit();               
      }
   }
	
	
	public function index() {
		$this->load->language('report/product_sale');

		$this->document->setTitle('FM Item Wise Summary');

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = date('Y-m-d');
		}
  		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_report'])) {
			$filter_report = $this->request->get['filter_report'];
		} else {
			$filter_report = 'ADVANCE';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['filter_report'])) {
			$url .= '&filter_report=' . $this->request->get['filter_report'];
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'FM Item Wise Summary',
			'href' => $this->url->link('reportbcml/report_fm', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/fmreport');
        
		$data['orders'] = array();

		$filter_data = array(
                               
			'filter_date'	     => $filter_date,
			'filter_store'	     => $filter_store,
			'filter_report'	     => $filter_report,
			'filter_company' => '2',
                        
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		if(!empty($this->request->get['filter_store']))
		{
        		$data['orders']=$results=$this->model_report_fmreport->getRecords($filter_data);
       
		$order_total = $this->model_report_fmreport->getTotalRecords($filter_data);
		}
		/*
		foreach($results as $stores)
		{  
	   		$data['orders'][] = array(
				
								'store_name'          => $stores['name'],
								'unit_name'    => $stores['unit_name'],
								'store_id'    => $stores['store_id'],
                                'today_cash'  => $today_cash['Cash'],
                                'today_tagged'   => $today_tagged['Tagged'],
                                'till_date_cash'     => $till_date_cash['Cash'],
                                'till_date_tagged'         => $till_date_tagged['Tagged']
			);
		}
		*/
		$data['heading_title'] = 'FM Item Wise Summary';
		
		$data['text_list'] = $this->language->get('text_list');
		if(empty($this->request->get['filter_store']))
		{
			
		$data['text_no_results'] = 'Please Select Store';
		}
		else
		{
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		}
		$data['text_confirm'] = $this->language->get('text_confirm');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];


		$url = '';

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_report'])) {
			$url .= '&filter_report=' . $this->request->get['filter_report'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/report_fm', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date'] = $filter_date;
		$data['filter_store'] = $filter_store;
		$data['filter_report'] = $filter_report;

		$this->load->model('setting/store'); 
                	$data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/fm_itwm_wise_summary.tpl', $data)); 
	}
        public function download_report() {
        
        		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = date('Y-m-d');
		}
             		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		

			
        $this->load->model('report/fmreport');
        
		$data['orders'] = array();

		$filter_data = array(
                               
			'filter_date'	     => $filter_date,
			'filter_store'	     => $filter_store,
			'filter_company' => '2',
                        
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		if(!empty($this->request->get['filter_store']))
		{
        		$data['orders']=$results=$this->model_report_fmreport->getRecords($filter_data);
       
		
		}
		
	$file_name="FM_Item_Wise_Summary_".$filter_date.'.xls';
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

        echo '<table id="example2" class="table table-striped table-bordered table-hover">
                <thead>
	<tr>
                <td class="text-left">Date</td>
                <td class="text-left">Store Name</td>
	<td class="text-left">Fm Code</td>
                <td class="text-left">Fm Name</td>
                <td class="text-left">Product Name</td>
          
                <td class="text-left">Quantity</td>
                <td class="text-left">Total Amount</td>
                <td class="text-left">Total Invoice</td>
                
              </tr>
            </thead>
                <tbody>';
$tblbody=" ";

foreach($data['orders'] as  $order)
		{  
       
			echo  '<tr>
                <td class="text-left">'.$order['create_date'].'</td>
                <td class="text-left">'.$order['store_name'].'</td>
          	<td class="text-left">'.$order['fmcode'].'</td>
                <td class="text-left">'.$order['fmname'].'</td>
	  <td class="text-left">'.$order['model'].'</td>
                <td class="text-left">'.$order['qnty'].'</td>
                <td class="text-left">'.number_format((float)$order['ttotal'],2,'.','').'</td>
                <td class="text-left">'.$order['cnt'].'</td>
              </tr>';

		}

echo '</tbody>
          </table>';

        
    }
       
} 