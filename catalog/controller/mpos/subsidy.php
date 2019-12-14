<?php

class Controllermpossubsidy extends Controller{

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


function tagbilldetail()
{
$log=new Log("subsidybill-".date('Y-m-d').".log");
$mcrypt=new MCrypt();

$log->write('tagbilldetail called');
$log->write($this->request->post);
//$log->write($this->request->get);
$uid=$mcrypt->decrypt($this->request->post['username']);

if (isset($this->request->post['date'])) {
$filter_date_start = date('Y-m-d',strtotime($mcrypt->decrypt($this->request->post['date'])));
} else {
$filter_date_start = date('Y-m-d');
}
$log->write('date:'.$mcrypt->decrypt($this->request->post['date']));
if (isset($this->request->post['date'])) {
$filter_date_end = date('Y-m-d',strtotime($mcrypt->decrypt($this->request->post['date'])));
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->post['store_id'])) {
$filter_store = $mcrypt->decrypt($this->request->post['store_id']);
} else {
$filter_store = 0;
}
$log->write('store_id:'.$mcrypt->decrypt($this->request->post['store_id']));

if (isset($this->request->get['username'])) {
$filter_username = $mcrypt->decrypt($this->request->get['username']);
} else {
$filter_username = 0;
}
if (isset($this->request->get['start'])) {
$start = $mcrypt->decrypt($this->request->get['start']);
} else {
$start = 0;
}
if (isset($this->request->post['start'])) {
$start = $mcrypt->decrypt($this->request->post['start']);
} else {
$start = 0;
}
$filter_data = array(
'filter_store' => $filter_store,
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'filter_username' => $filter_username,
'filter_payment_method' => 'Subsidy',
'start' => $start,
'limit' => 20
);
$log->write($filter_data);
$this->adminmodel('report/reconciliation');
$results = $this->model_report_reconciliation->getSubsidyOrders($filter_data);

foreach ($results as $result) {

//$grower_info = $result['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
//$farmer_info=explode('-', $grower_info);

//$grower_id=@$farmer_info[0];
//$farmer_name=ucwords(strtolower(@$farmer_info[1]));
$data['results'][] = array(
'store_name' => $mcrypt->encrypt($result['store_name']),
'store_id' => $mcrypt->encrypt($result['store_id']),
'total' => $mcrypt->encrypt(number_format((float)$result['total'], 0, '.', '')),
'tagged' => $mcrypt->encrypt(number_format((float)$result['subsidy'], 0, '.', '')),
'grower_id' => $mcrypt->encrypt($result['grower_id']),
'farmer_name' => $mcrypt->encrypt(' '),
'unit' => $mcrypt->encrypt('unit_name'));
}



$log->write($this->request->post);
$this->response->setOutput(json_encode($data));
}

function tagbill()
{
$log=new Log("subsidybill-".date('Y-m-d').".log");
$mcrypt=new MCrypt();
$log->write('tagbill called');
$log->write($this->request->post);
$log->write($this->request->get);
$uid=$mcrypt->decrypt($this->request->post['username']);


if (isset($this->request->post['date'])) {
$filter_date_start = $mcrypt->decrypt($this->request->post['date']);
} else {
$filter_date_start = date('Y-m-d');
}

if (isset($this->request->post['date'])) {
$filter_date_end = $mcrypt->decrypt($this->request->post['date']);
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->post['store_id'])) {
$filter_store = $mcrypt->decrypt($this->request->post['store_id']);
} else {
$filter_store = 0;
}
if (isset($this->request->post['username'])) {
$filter_username = $mcrypt->decrypt($this->request->post['username']);
} else {
$filter_username = 0;
}
if (isset($this->request->post['start'])) {
$start = $mcrypt->decrypt($this->request->post['start']);
} else {
$start = 0;
}
if (isset($this->request->post['type'])) {
$type = $mcrypt->decrypt($this->request->post['type']);
} else {
$type = '';
}
$filter_data = array(
'filter_store' => $filter_store,
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'filter_username' => $filter_username,
'filter_payment_method' => 'Subsidy',
'type' => $type,
'start' => $start,
'limit' => 20
);
$log->write($filter_data);

$this->adminmodel('report/reconciliation');
$results = $this->model_report_reconciliation->getOrdersSubsidytotal($filter_data);
$log->write($results);
foreach ($results as $result)
{
$filter_data2 = array(
'filter_store' => $filter_store,
'filter_date_start' => $result['date'],
'filter_date_end' => $result['date']
);
$total_orders = count($this->model_report_reconciliation->getSubsidyOrders($filter_data2));

$data['results'][] = array(
'date' => $mcrypt->encrypt(date('Y-m-d',strtotime($result['date']))),
'date_display' => $mcrypt->encrypt(date('d/m/Y',strtotime($result['date']))),
'total' => $mcrypt->encrypt($total_orders),
'tagged' => $mcrypt->encrypt(number_format((float)$result['subsidy'], 0, '.', '')),
'submit_date' => $mcrypt->encrypt($result['tagged_submit_date']),
'runner_name' => $mcrypt->encrypt($result['runner_name'])
);

}
//$mcrypt->encrypt(number_format((float)$result['total'], 0, '.', '')),
$log->write($this->request->post);
$this->response->setOutput(json_encode($data));
}
function tagbillsubmit()
{
$log=new Log("subsidybill-submit-".date('Y-m-d').".log");
$mcrypt=new MCrypt();
$log->write('tagbillsubmit called');
$log->write($this->request->post);
$keys = array(
			'store_id',
			'amount',
			'date',
			'runner_id',
			'user_id'
		);

	foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
	$filter_data = array(
'filter_store' => $this->request->post['store_id'],
'filter_date_start' => $this->request->post['date'],
'filter_date_end' => $this->request->post['date'],
'filter_payment_method' => 'Subsidy',
'start' => 0,
'limit' => 200
);
//$this->request->post['date']
$log->write($filter_data);
$runner_id=$this->request->post['runner_id'];
$log->write($runner_id);
$this->adminmodel('report/reconciliation');
$log->write('come here 2');
	$orders =$this->model_report_reconciliation->getSubsidyOrders($filter_data);	
	foreach($orders as $order)
	{
		$order_id=$order['order_id'];
		$this->model_report_reconciliation->update_runner_id($order_id,$runner_id);
	}
	$this->model_report_reconciliation->insert_into_bank_transaction_subsidy($this->request->post);
	$this->response->setOutput('1');
}

}

?>