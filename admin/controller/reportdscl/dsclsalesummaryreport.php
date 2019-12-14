<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');

  
class ControllerreportdsclDsclsalesummaryreport extends Controller {
	private $error = array();

	public function index() {		

		$this->document->setTitle('DSCL SALE Summary Report');

		$this->load->model('farmerrequest/farmerrequest');
		$this->load->model('pos/dscl');

		$this->getList();
	}
        protected function getList() {
		             //error_reporting(0);
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}


		$url = '';
	
		if (isset($this->request->get['filter_date_start'])) {
$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
}

if (isset($this->request->get['filter_date_end'])) {
$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
}
		
		if (isset($this->request->get['page'])) {
			$page=$this->request->get['page'];
		}
		else
		{
			$page=1;
			$_SESSION[session_id()]='';
			$_SESSION['all_selected']='';
		}
		$data['breadcrumbs'] = array();

		

		$data['breadcrumbs'][] = array(
			'text' => 'Card Print Form',
			'href' => $this->url->link('reportdscl/dsclsalesummaryreport', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
	
		$data['orders'] = array();
		$cardsql="SELECT 
        unit_code AS unit_id,
SUM(CASE WHEN ORDER_STATUS_ID IN (1, 5 ) THEN 1 ELSE 0 END) AS requsition,
SUM(CASE WHEN ORDER_STATUS_ID IN (1) THEN 1 ELSE 0 END) AS request,
SUM(CASE WHEN ORDER_STATUS_ID IN (5) THEN 1 ELSE 0 END) AS printing
FROM
oc_order_req_delivery
where ORDER_STATUS_ID IS NOT NULL ";


		if ((!empty($filter_date_start))&& (!empty($filter_date_end))) {
$cardsql .= " and to_char(DATE_ADDED,'YYYY-MM-dd') between '".$filter_date_start."' and  '".$filter_date_end."'";
}



	
$cardsql.=" GROUP BY unit_code";




		$filter_data = array(
			'filter_date_start'	     => '',
			'filter_date_end'	     => '',
			'sql'					=> $cardsql	
		);

		$results = $this->model_pos_dscl->GetCardDataSql('GetCardDataSql',$filter_data,0);

		foreach ($results as $result) {
		
			$data['orders'][] = array(
                'FACTORY' => $result['UNIT_ID'],
                'REQUTITION' => $result['REQUSITION'],
				'REQUEST' => $result['REQUEST'],
				'PRINTING'     => $result['PRINTING'],
							);
		}
		
		//$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = 'Please Select Grower Id';
		

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportdscl/dsclsalesummaryreport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_association'] = $filter_association;
		$data['pagination'] = $pagination->render();
       		$data['companys'] = $this->model_farmerrequest_farmerrequest->getComapny();
        	$data['units'] = $this->model_farmerrequest_farmerrequest->getUnit();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportdscl/dsclsalesummaryreport.tpl', $data));
}


public function paymentmethodsummary()
{
	//summary detail
	$this->load->model('pos/dscl');
	$cardsql="select STORE_NAME,PAYMENT_METHOD,sum(TOTAL) AS TOTAL_AMT from  oc_order_req_delivery group by STORE_NAME,PAYMENT_METHOD";
	$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'sql'					=> $cardsql	
		);

	$results = $this->model_pos_dscl->GetCardDataSql('GetCardDataSql',$filter_data,0);
foreach ($results as $result) {
		
			$data['orders'][] = array(
                'FACTORY' => $result['STORE_NAME'],
                'REQUTITION' => $result['PAYMENT_METHOD'],
				'REQUEST' => $result['TOTAL_AMT']
							);
		}

			$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('farmerrequest/cardprint', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_association'] = $filter_association;
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('farmerrequest/dsclcardsummaryreport.tpl', $data));


}


public function productwisesummary()
{
	//summary detail
	$this->load->model('pos/dscl');
	$cardsql="select  Product_id,ROUND(SUM(QUANTITY), 2)  as Total_Qty from oc_order_product_req_delivery Group by product_id";
	$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'sql'					=> $cardsql	
		);

	$results = $this->model_pos_dscl->GetCardDataSql('GetCardDataSql',$filter_data,0);
	foreach ($results as $result) {
		
			$data['orders'][] = array(
                'FACTORY' => $result['PRODUCT_ID'],
                'REQUTITION' => $result['TOTAL_QTY'],
							);
		}

			$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('farmerrequest/cardprint', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_association'] = $filter_association;
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('farmerrequest/dsclproductsummaryreport.tpl', $data));




}

public function orderstatuscountsummary()
{
	//summary detail
	$this->load->model('pos/dscl');
	$cardsql="select ORDER_STATUS_ID,Count(ORDER_STATUS_ID) cnt from oc_order_req_delivery Group by ORDER_STATUS_ID";
	$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'sql'					=> $cardsql	
		);

	$results = $this->model_pos_dscl->GetCardDataSql('GetCardDataSql',$filter_data,0);
//print_r($results);
//exit;
	foreach ($results as $result) {
		
			$data['orders'][] = array(
                'FACTORY' => $result['ORDER_STATUS_ID'],
                'REQUTITION' => $result['CNT']
			);
		}

			$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('farmerrequest/cardprint', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_association'] = $filter_association;
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('farmerrequest/dsclordersummaryreport.tpl', $data));

}


public function download_excel() {



$this->load->model('farmerrequest/farmerrequest');
$results = $this->model_farmerrequest_farmerrequest->getcardsummarydtl($filter_data);
//print_r($results);exit; 

include_once '../system/library/PHPExcel.php';

include_once '../system/library/PHPExcel/IOFactory.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->createSheet();

$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

$objPHPExcel->setActiveSheetIndex(0);

// Field names in the first row
$fields = array( 
'Factory',
'Requisition',

'Printing',
'Printed',
'Dispatch',
'Verify',
'Pending Approval',
'Approved',
'Delivered', 
'Rejected', 
'Blocked'
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
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['unit_name']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['requsition']);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['printing']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['printed']);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['dispatch']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['verify']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['request']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['approved']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['deliver']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['rejected']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['blocked']);
$row++;

}
//exit;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// Sending headers to force the user to download the file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$companyname.''.$unitname."Card Summary Report".date('d-m-Y').'.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output'); 

}

	

}