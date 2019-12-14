<?php
	//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
	class ControllerFactoryPaymentdtl extends Controller {
		public function index($return_orders = array()) {
			$url = '';
			
			$this->load->model('purchase/return_orders');
			$this->document->setTitle('Factory Payment');
			$data['heading_title'] = 'Factory Payment';
			$data['text_list'] = 'Factory Payment';
			
			
			
			$data['button_filter'] = $this->language->get('button_filter');
			$data['button_clear'] = $this->language->get('button_clear');
			
			$data['token'] = $this->session->data['token'];
			
		
                        $this->load->model('factory/paymentdtl');
                
                       // $data["units"] = $this->model_factory_paymentdtl->getAllUnits();
                        $data["comopanys"] = $this->model_factory_paymentdtl->getAllCompanys();
                        $data["store"] = $this->model_factory_paymentdtl->getStores();
                        
                        if ($this->request->server['REQUEST_METHOD'] == 'POST')
                        {
			//print_r($this->request->post);exit;
                        $this->load->model('factory/paymentdtl');
                        $data['logged_user_data'] = $this->user->getId();
                        $this->model_factory_paymentdtl->insrtPaymentdtl($this->request->post,$data['logged_user_data']);


                        $this->session->data['success'] = 'Factory Payment done successfully';
                        $this->response->redirect($this->url->link('factory/paymentdtl/paymentlist', 'token=' . $this->session->data['token'] , 'SSL'));
                        }
                        
                        
                     
			
			$url ='';
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Credit Posting',
				'href' => $this->url->link('payout/payoutdtl', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['cancel']=$this->url->link('factory/paymentdtl/paymentlist', 'token=' . $this->session->data['token'], 'SSL');
			$data['header'] = $this->load->controller('common/header');
			
			
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['footer'] = $this->load->controller('common/footer');
				$this->response->setOutput($this->load->view('factory/paymentdtl.tpl', $data));
			
		}
                public function getUnitbyCompany(){


$this->load->model('factory/paymentdtl');

$cid=$this->request->get['companyid'];
if (isset($this->request->get['companyid']))

{

$dunit= $this->model_factory_paymentdtl->getunitbycompany($cid);

$dpunit= count($dunit);
echo $dpunit;
echo ' <option value=""> Select Unit</option> ';
for($n=0;$n<$dpunit;$n++)
{
echo '<option value="'.$dunit[$n]['unit_id'].'">'.$dunit[$n]['unit_name'].'</option>';
}

}

}


public function paymentlist()
{
$this->load->language('report/product_purchased');

$this->document->setTitle("Factory Payment List");

$this->load->model('factory/paymentdtl');


$this->getList();
}
protected function getList() {

if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$data['filter_date_start']="";
}

if (isset($this->request->get['filter_date_end'])) {
$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = "";
}

if (isset($this->request->get['filter_unit'])) {
$filter_unit= $this->request->get['filter_unit'];
} else {
$filter_unit = "";
}
if (isset($this->request->get['filter_company'])) {
$filter_company= $this->request->get['filter_company'];
$cid=$this->request->get['filter_company'];
$data['units2']= $this->model_factory_paymentdtl->getUnitbyCompany($cid);

} else {
$filter_company = "";
}
$data['filter_date_start']=$filter_date_start;
$data['filter_date_end']=$filter_date_end;
$data['filter_unit']=$filter_unit;
$data['filter_company']=$filter_company;

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

if (isset($this->request->get['filter_unit'])) {
				$url .= '&filter_unit=' . $this->request->get['filter_unit'];
			}

			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . $this->request->get['filter_company'];
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
'href' => $this->url->link('factory/paymentdtl/paymentlist', 'token=' . $this->session->data['token'], 'SSL')
);
$data['cancel']=$this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
$this->load->model('factory/paymentdtl');
 $data["comopanys"] = $this->model_factory_paymentdtl->getAllCompanys();
$data['payment'] = array();

$filter_data = array( 
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'filter_unit' => $filter_unit,
'filter_company' => $filter_company,
'start' => ($page - 1) * $this->config->get('config_limit_admin'),
'limit' => $this->config->get('config_limit_admin')
);

if((!empty($filter_date_start)) || (!empty($filter_date_end)) || (!empty($filter_unit)) || (!empty($filter_company)))
{
$product_total1 = $this->model_factory_paymentdtl->getTotalPaymentList($filter_data);
$data['total_amount']=$product_total1['amounttotal'];
$product_total=$product_total1['total'];

//print_r($product_total1);
$results = $this->model_factory_paymentdtl->getPaymentList($filter_data);
}

foreach ($results as $result) {
$data['payment'][] = array(
'amount' => $result['amount'],
'bank'=> $result['bank'],
'transaction_type' => $result['transaction_type'],
'transaction_no' => $result['transaction_no'],
'create_date' => $result['create_date'],
'recieve_date' => $result['recieve_date'],
'payment_method' => $result['payment_method'],
'unit_name' => $result['unit_name'],
'firstname' => $result['firstname'],
'lastname' => $result['lastname'],
'company_name' => $result['company_name']

);
}

$data['heading_title'] ="Factory Payment List";

$data['text_list'] = "Factory Payment List";
$data['text_no_results'] ="Factory Payment List" ;


$data['column_name'] = $this->language->get('column_name');
$data['column_model'] = $this->language->get('column_model');
$data['column_quantity'] = $this->language->get('column_quantity');
$data['column_total'] = $this->language->get('column_total');

$data['entry_date_start'] = $this->language->get('entry_date_start');
$data['entry_date_end'] = $this->language->get('entry_date_end');
$data['entry_status'] = $this->language->get('entry_status');


if (isset($this->error['warning'])) {
$data['error_warning'] = $this->error['warning'];
} else {
$data['error_warning'] = '';
}

if (isset($this->session->data['success'])) {
$data['success'] = $this->session->data['success'];

unset($this->session->data['success']);
} else {
$data['success'] = '';
}

$data['button_filter'] = $this->language->get('button_filter');

$data['token'] = $this->session->data['token'];

$data['units']= $this->model_factory_paymentdtl->getunitbycompany($filter_company);
$url = '';

if (isset($this->request->get['filter_date_start'])) {
$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
}

if (isset($this->request->get['filter_date_end'])) {
$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
}

if (isset($this->request->get['payment'])) {
$url .= '&filter_unit_id=' . $this->request->get['filter_unit_id'];
}

$pagination = new Pagination();
$pagination->total = $product_total;
$pagination->page = $page;
$pagination->limit = $this->config->get('config_limit_admin');
$pagination->url = $this->url->link('factory/paymentdtl/paymentlist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

$data['pagination'] = $pagination->render();
$data['filter_unit_id']=$filter_unit_id;
$data['filter_company']=$filter_company;
$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));
//print_r($product_total); 
$data['filter_date_start'] = $filter_date_start;
$data['filter_date_end'] = $filter_date_end;
$data['filter_order_unit_id'] = $filter_order_unit_id;

$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');

$this->response->setOutput($this->load->view('factory/paymentlist.tpl', $data));
}
public function download_excel() {
if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$data['filter_date_start']=$filter_date_start = date('Y-m').'-01';
}

if (isset($this->request->get['filter_date_end'])) {
$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->get['filter_unit'])) {
$filter_unit= $this->request->get['filter_unit'];
} else {
$filter_unit = 0;
}
if (isset($this->request->get['filter_company'])) {
$filter_company= $this->request->get['filter_company'];
} else {
$filter_company = 0;
}

$filter_data = array(
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'filter_unit' => $filter_unit,
'filter_company' => $filter_company,
'start' => ($page - 1) * $this->config->get('config_limit_admin'),
'limit' => $this->config->get('config_limit_admin')
);

$this->load->model('factory/paymentdtl');
$results = $this->model_factory_paymentdtl->downloadgetPaymentList($filter_data);
//print_r($results);exit;

include_once '../system/library/PHPExcel.php';

include_once '../system/library/PHPExcel/IOFactory.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->createSheet();

$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

$objPHPExcel->setActiveSheetIndex(0);

// Field names in the first row
$fields = array(
'Company',
'Unit',
'Amount',
'Transaction Type',
'Transaction Number',
'Payment Method',
'Create Date',
'Recieve Date'
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
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['company_name']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['unit_name']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['amount']);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['transaction_type']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['transaction_no']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['payment_method']);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['create_date']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['recieve_date']);

$row++;

}
//exit;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// Sending headers to force the user to download the file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Factory Payment List"'.date('Y-m-d').'".xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

}		
	}
?>