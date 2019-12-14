<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(1);
ini_set('max_execution_time', 600); //600 seconds = 10 minutes

class ControllerReportAsplSales extends Controller {
	public function index() {
		$this->load->language('report/product_sale');

		$this->document->setTitle('Aspl Product Sales Report');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
                	if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Aspl Product Sales Report',
			'href' => $this->url->link('report/aspl_sales', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/aspl_sale');
                	$this->load->model('setting/store');
		$data['orders'] = array();

		$filter_data = array(
                                          'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_name'           => $filter_name_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
           
		$t1=$this->model_report_aspl_sale->getTotalOrders($filter_data);//print_r($t1);
		$order_total = $t1["total"];
		
		$results = $this->model_report_aspl_sale->getOrders($filter_data);
                            
		//print_r($results); 
		foreach ($results as $result) { //print_r($result);
			if($result['store_name']=="")
			{
				$store_name=$result['store_name2'];
			}
			else
			{
				$store_name=$result['store_name'];
			}
			$data['orders'][] = array(
				'dats' => date($this->language->get('date_format_short'), strtotime($result['dats'])),
				'name'          => $result['name'],
				'store_name'    => $store_name,
                                'store_id'    => $result['store_id'],
                                'p_price'   => $result['p_price'],
                                'Total'         => $result['order_total'],
                                'tax_title'    => $result["tax_title"],
		    'total_tax'    =>(($result['order_total'])-($result['p_price']*$result['qnty'])),
                                'qnty'          => $result["qnty"]
		   
				
			);
		}
 
		$data['heading_title'] = 'Aspl Product Sales Report';
		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/product_sales');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		
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
                	$url = '';

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


		//echo $url;
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/aspl_sales', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_name'] = $filter_name;
                	$data['filter_name_id'] = $filter_name_id;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/aspl_product_sales.tpl', $data));
	}
        public function download_excel() {
        
        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
		}
                if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			//$filter_name = '';
		}
                if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} 
		


		$this->load->model('report/product_sale');
                
		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_name'           => $filter_name_id
		);

		
$file_name="Product_sales_report_".date('dMy').'.xls';
 header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
 header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
 header("Expires: 0");
 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 header("Cache-Control: private",false);

/*
header('Content-type: application/excel');
$filename = 'filename.xls';
header('Content-Disposition: attachment; filename='.$filename);
*/
        $results = $this->model_report_product_sale->getOrders($filter_data);
        echo '<table id="example2" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
	      <th>Sale Date</th>
                    <th>Store Name</th>
                    <th>Store ID</th>
                    
                    <th>Product Name</th>
                    <th>Quantity</th>
 	      <th>Rate(without tax)</th>
	      <th>Tax title</th>
	      <th>Tax rate</th>
                    <th>Total (Sales + Tax)</th>
                    <th>Order ID</th>
                </tr>
                </thead>
                <tbody>';
$tblbody=" ";
foreach($results as $data)
{


                    echo  '<tr> 
	      <td>'.date('Y-m-d',strtotime($data['dats'])).'</td>
                    <td>'.$data['store_name'].'</td>
                    <td>'.$data['store_id'].'</td>
                    <td>'.$data['name'].'</td>
	      <td>'.$data['qnty'].'</td>
                    <td>'.number_format((float)($data['Total_sales']/$data['qnty']), 2, '.', '').'</td>
	      <td>'.$data['tax_title'].'</td>
                    <td>'.number_format((float)$data['Total_tax'], 2, '.', '').'</td>
	      <td>'.number_format((float)($data['qnty']*(($data['Total_sales']/$data['qnty'])+$data['Total_tax'])), 2, '.', '').'</td>
                    <td>'.$data['order_id'].'</td>
                   </tr>';


}


echo '</tbody>
          </table>';
        }
   
}