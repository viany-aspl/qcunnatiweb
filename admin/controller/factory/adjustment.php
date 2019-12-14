<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerFactoryAdjustment extends Controller{
       
        public function get_to_store_data()
        {
            $store_id = $this->request->get['store_id'];
            $this->load->model('partner/purchase_order');
            echo $data=$this->model_partner_purchase_order->get_to_store_data($store_id);
            
        }
        public function index()
	{
			$this->document->setTitle("Bill Submission and Adjustment");
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			$url = '';

			
			if (isset($this->request->get['filter_store'])) {
				$url .= '&filter_store=' . $this->request->get['filter_store'];
			}
			if (isset($this->request->get['filter_unit'])) {
				$url .= '&filter_unit=' . $this->request->get['filter_unit'];
			}
			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . $this->request->get['filter_company'];
			}
                        		if (isset($this->request->get['filter_letterno'])) {
				$url .= '&filter_letterno=' . $this->request->get['filter_letterno'];
			}
			if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			else
			{	
			$url .= '';
			}
			if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			else
			{	
			$url .= '';
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
                       		
			
                       		 if (isset($this->request->get['filter_store'])) {
				$filter_store =  $this->request->get['filter_store'];
			}
			      if (isset($this->request->get['filter_unit'])) {
				$filter_unit =  $this->request->get['filter_unit'];
			}
			      if (isset($this->request->get['filter_company'])) {
				$filter_company =  $this->request->get['filter_company'];
			}
			      if (isset($this->request->get['filter_letterno'])) {
				$filter_letterno =  $this->request->get['filter_letterno'];
			}
               		if (isset($this->request->get['filter_date_start'])) {
				$data['filter_date_start']=$filter_date_start= $this->request->get['filter_date_start'];
			}
			else
			{
				$data['filter_date_start']=$filter_date_start=date('Y-m').'-01';
			}

			if (isset($this->request->get['filter_date_end'])) {
				$data['filter_date_end']=$filter_date_end= $this->request->get['filter_date_end'];
			}    
			else
			{
				$data['filter_date_end']=$filter_date_end=date('Y-m-d');
			}
			if (isset($this->request->get['page'])) {
                                		$page = $this->request->get['page'];
                        		} 
                       	 	else {
                                	$page = 1;
                       		 }
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
			$data['breadcrumbs'][] = array(
			'text' => "Bill Submission and Adjustment",
			'href' => $this->url->link('factory/adjustment', 'token=' . $this->session->data['token'] . $url, true)
		);

		$this->load->model('factory/adjustment');
		
                       	 	
                        $filter_data=array(
                            'filter_store'=>$filter_store,
                            'filter_letterno'=>$filter_letterno,
							'filter_unit'=>$filter_unit,
							'filter_date_start'	     => $filter_date_start,
							'filter_date_end'	     => $filter_date_end,
			'filter_date_start'	     => $filter_date_start,
                            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                            'limit'   => $this->config->get('config_limit_admin')
                        );
		if(!empty($filter_unit))
		{
		$data['order_list'] = $this->model_factory_adjustment->getList($filter_data);
		
		$total_orders = $this->model_factory_adjustment->getTotalOrders($filter_data);
          }      
                         
                
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		/*pagination*/
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('factory/adjustment', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
                $this->load->model('setting/store');
                $data['stores']=$this->model_setting_store->getStores();
                $this->load->model('company/company');
				$this->load->model('factory/paymentdtl');
                $data['order_company']=$this->model_company_company->getcompany();
				if(!empty($filter_company))
				{
				$data['units']= $this->model_factory_paymentdtl->getunitbycompany($filter_company);
				if(!empty($filter_unit))
				{
				$data['unit_balance']=$this->model_factory_adjustment->getunitbalance($filter_unit,$filter_company);
				}
				}
				if(!empty($filter_unit))
				{
				$data['storess']= $this->model_factory_adjustment->getstorebyunit($filter_unit);
				
				}
		$data['filter_store']=$filter_store; 
		$data['filter_letterno']=$filter_letterno;
		$data['filter_unit']=$filter_unit;
		$data['filter_company']=$filter_company;
                $data['token']=$this->request->get['token'];
                $data['adjust_invoice']=$this->url->link('factory/adjustment/adjust_invoice', 'token=' . $this->session->data['token'] . $url, true);
		$data['my_custom_text'] = "This is purchase order page.";
		
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
		
		$data['user_group_id'] = $user_info['user_group_id']; 
		
		$this->response->setOutput($this->load->view('factory/payment_adjustment_list.tpl', $data));
	}
	  public function getUnitbyCompany(){


$this->load->model('factory/paymentdtl');

$cid=$this->request->get['companyid'];
if (isset($this->request->get['companyid']))

{

$dunit= $this->model_factory_paymentdtl->getunitbycompany($cid);

$dpunit= count($dunit);
//echo $dpunit;
echo ' <option value=""> Select Unit</option> ';
for($n=0;$n<$dpunit;$n++)
{

echo '<option value="'.$dunit[$n]['unit_id'].'">'.$dunit[$n]['unit_name'].'</option>';


}

}

}


        public function paidconfirmation()
        {          
                $sid=$this->request->get['sid'];
                $company=$this->request->get['company'];
                $unit=$this->request->get['unit'];
                $tamount=$this->request->get['tamount'];
                $store_id=$this->request->get['store_id'];
                $updated_by = $this->user->getId();
				$this->load->model('factory/adjustment');
                $this->model_factory_adjustment->updatestatus($sid,$company,$unit,$tamount,$store_id,$updated_by);
                
        }
		public function save_submission_date()
        {          
                $sid=$this->request->get['sid'];
                $company=$this->request->get['company'];
                $unit=$this->request->get['unit'];
                $tamount=$this->request->get['tamount'];
                $store_id=$this->request->get['store_id'];
                $updated_by = $this->user->getId();
				$this->load->model('factory/adjustment');
                $this->model_factory_adjustment->save_submission_date($sid,$company,$unit,$tamount,$store_id,$updated_by);
                
        }
		
		public function getUnitbalace()
		{
		  $unit=$this->request->get['unit'];
		  $company=$this->request->get['company'];
		  $this->load->model('factory/adjustment');
          echo $this->model_factory_adjustment->getunitbalance($unit,$company);
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
if (isset($this->request->get['filter_store'])) {
$filter_store= $this->request->get['filter_store'];
} else {
$filter_store = 0;
}
if (isset($this->request->get['filter_letterno'])) {
$filter_letterno= $this->request->get['filter_letterno'];
} else {
$filter_letterno = 0;
}


$filter_data = array(
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'filter_unit' => $filter_unit,
'filter_company' => $filter_company,
'filter_store' => $filter_store,
'filter_letterno' => $filter_letterno,
'start' => ($page - 1) * $this->config->get('config_limit_admin'),
'limit' => $this->config->get('config_limit_admin')
);

$this->load->model('factory/adjustment');
$results = $this->model_factory_adjustment->downloadgetList($filter_data);
//print_r($filter_data);
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
'Store Name',
'Letter No',
'Date Of Generation',
'Total Amount',
'Bill Included From',
'Status'
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
 if($data['status']=='0')
{
  $stst="Unpaid";
 }
if($data['status']=='1')
{
  $stst="Paid";
}

$col = 0;
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['company_name']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['unit_name']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_name']);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, "ASPL/BB/".$data['sid']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['create_date']);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['total_amount']);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['date_start']);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $stst);

$row++;

}
//exit;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// Sending headers to force the user to download the file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Factory Adjustment List"'.date('Y-m-d').'".xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

}
public function getstorebyunit(){


$this->load->model('factory/adjustment');

$uid=$this->request->get['unitid'];
if (isset($this->request->get['unitid']))

{

$dstore= $this->model_factory_adjustment->getstorebyunit($uid);

$dpstore= count($dstore);
echo $dpstore;
echo ' <option value=""> Select Store</option> ';
for($n=0;$n<$dpstore;$n++)
{

echo '<option value="'.$dstore[$n]['store_id'].'">'.$dstore[$n]['name'].'</option>';


}

}

}


	
}

?>