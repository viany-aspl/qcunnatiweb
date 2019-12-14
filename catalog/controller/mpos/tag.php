<?php

class Controllermpostag extends Controller{

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

function tag(){



		$log=new Log("tag-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();

		
		$log->write($this->request->post);
		$log->write($this->request->get);
		$uid=$mcrypt->decrypt($this->request->post['username']);
		$sid=$mcrypt->decrypt($this->request->post['store_id']);
		$this->adminmodel('tag/order');

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['sdate'])) {
			$filter_date_added = $mcrypt->decrypt($this->request->get['sdate']);
					$log->write($filter_date_added);
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['pdate'])) {
			$filter_date_potential = $mcrypt->decrypt($this->request->get['pdate']);
			$log->write($filter_date_potential);

		} else {
			$filter_date_potential = null;
		}


		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['products'] = array();

		$filter_data = array(
			'filter_order_id'      => $filter_order_id,
			'filter_customer'	   => $filter_customer,
			'filter_order_status'  => '1',
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_potential' => $filter_date_potential,
			'filter_store_id'	=>$sid,
			'sort'			=>$sort,
			'order'                => $order,
			'start'                => $mcrypt->decrypt($this->request->post['start']),
			'limit'                => $this->config->get('config_limit_admin')
		);


		$results = $this->model_tag_order->getOrders($filter_data);

		foreach ($results as $result) { 
			$data['products'][] = array(
				'id'      => $mcrypt->encrypt($result['order_id']),
				'name'      => $mcrypt->encrypt($result['customer']),
				'pirce'        => $mcrypt->encrypt($result['status']),
                                'quantity'     => $mcrypt->encrypt($result['telephone']),
                                'store_name'    => $mcrypt->encrypt($result['store_name']),
				'total'         => $mcrypt->encrypt($this->currency->format($result['total'], $result['currency_code'], $result['currency_value'])),
				'date_added'    => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['date_added']))),
				'date' => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['date_potential'])))
				);
		}

				
				$keys = array(
			'store_id',
			'limit',
			'start',
			'username'		
		);

foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
		$log->write($this->request->post);
		$this->response->setOutput(json_encode($data));

}
function tagbilldetail()
{
$log=new Log("tagbill-".date('Y-m-d').".log");
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
'start' => $start,
'limit' => 20
);
$log->write($filter_data);
$this->adminmodel('report/reconciliation');
$this->adminmodel('pos/pos');
$results = $this->model_report_reconciliation->gettaggedOrders($filter_data);

foreach ($results as $result) {

$grower_info = $result['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
$farmer_info=explode('-', $grower_info);

$grower_id=@$farmer_info[0];
if(empty($grower_id))
{
	$grower_id=$result['grower_id'];
}
$farmer_name=ucwords(strtolower(@$farmer_info[1]));

if(empty($farmer_name))
{
	$farmer_name=$result['payment_firstname'];
}

$companydata=$this->model_pos_pos->getunitidandcompanyid(array('storeid'=>$result['store_id'])); 

$unitid=$companydata[0]['unit_id'];
$unit_name=$companydata[0]['unit_name'];

$data['results'][] = array(

'store_name' => $mcrypt->encrypt($result['store_name']),
'store_id' => $mcrypt->encrypt($result['store_id']),
'total' => $mcrypt->encrypt(number_format((float)$result['tagged'], 0, '.', '')),
'tagged' => $mcrypt->encrypt(number_format((float)$result['tagged'], 0, '.', '')),
'grower_id' => $mcrypt->encrypt($grower_id),
'farmer_name' => $mcrypt->encrypt($farmer_name),
'unit' => $mcrypt->encrypt($unit_name),
'order_id' => $mcrypt->encrypt($result['order_id'])

);
}



$log->write($this->request->post);
$this->response->setOutput(json_encode($data));
}

function tagbill()
{
$log=new Log("tagbill-".date('Y-m-d').".log");
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
'type' => $type,
'start' => $start,
'limit' => 20
);
$log->write($filter_data);

$this->adminmodel('report/reconciliation');
$results = $this->model_report_reconciliation->getOrderstaggedtotal($filter_data);
$log->write($results);
foreach ($results as $result)
{
$filter_data2 = array(
'filter_store' => $filter_store,
'filter_date_start' => $result['date'],
'filter_date_end' => $result['date']
);
$total_orders = count($this->model_report_reconciliation->gettaggedOrders($filter_data2));

$data['results'][] = array(
'date' => $mcrypt->encrypt(date('Y-m-d',strtotime($result['date']))),
'date_display' => $mcrypt->encrypt(date('d/m/Y',strtotime($result['date']))),
'total' => $mcrypt->encrypt($total_orders),
'tagged' => $mcrypt->encrypt(number_format((float)$result['tagged'], 0, '.', '')),
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
$log=new Log("tagbill-".date('Y-m-d').".log");
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
'start' => 0,
'limit' => 200
);
//$this->request->post['date']
$log->write($filter_data);
$runner_id=$this->request->post['runner_id'];
$log->write($runner_id);
$this->adminmodel('report/reconciliation'); 
$log->write('come here 2');
	$orders =$this->model_report_reconciliation->gettaggedOrders($filter_data);	
	foreach($orders as $order)
	{ 
		$order_id=$order['order_id'];
		$this->model_report_reconciliation->update_runner_id($order_id,$runner_id);
	}
	$this->model_report_reconciliation->insert_into_bank_transaction($this->request->post);
	$this->response->setOutput('1');
}
////////////////////taggedbill letter wise start here////////////////////

function tagbillletter()
{ 
	$log=new Log("tagbill-letter-".date('Y-m-d').".log");
	$mcrypt=new MCrypt();
	$log->write('tagbillletter called');
	$log->write($this->request->post);
	$log->write($this->request->get);
	$uid=$mcrypt->decrypt($this->request->post['username']); 


	if (isset($this->request->post['date'])) 
	{
		$filter_date_start =$filter_date_end= $mcrypt->decrypt($this->request->post['date']);
	} 
	else 
	{
		$filter_date_start =$filter_date_end= date('Y-m-d');
	}

	if (isset($this->request->post['store_id'])) 
	{
		$filter_store = $mcrypt->decrypt($this->request->post['store_id']);
	} 
	else 
	{
		$filter_store = 0;
	}
	if (isset($this->request->post['username'])) 
	{
		$filter_username = $mcrypt->decrypt($this->request->post['username']);
	} 
	else 
	{
		$filter_username = 0;
	}
	if (isset($this->request->post['start'])) 
	{
		$start = $mcrypt->decrypt($this->request->post['start']);
	} 
	else 
	{
		$start = 0;
	}
	if (isset($this->request->post['type'])) 
	{
		$type = $mcrypt->decrypt($this->request->post['type']);
	} 
	else 
	{
		$type = 'pending';//submitted
	}
	$filter_data = array(
		'filter_store' => $filter_store,
		'filter_date_start' => $filter_date_start,
		'filter_date_end' => $filter_date_end,
		'type' => $type,
		'start' => $start,
		'limit' => 20
	);
	$log->write($filter_data);

	$this->adminmodel('report/reconciliation');
	$results = $this->model_report_reconciliation->getTaggedBillWithLetter($filter_data); 
	//$log->write($results);
	//print_r($results);
	foreach ($results as $result)
	{ 
		$filter_data2 = array(
			'filter_store' => $filter_store,
			'filter_date_start' => $result['date_start'],
			'filter_date_end' => $result['date_start']
		);
		$total_orders = 0;//count($this->model_report_reconciliation->gettaggedOrdersletter($filter_data2));

		$data['results'][] = array(
			'letter_number' => $mcrypt->encrypt($result['sid']),
			'date' => $mcrypt->encrypt(date('Y-m-d',strtotime($result['date_start']))),
			'date_display' => $mcrypt->encrypt(date('d/m/Y',strtotime($result['date_start']))),
			'number_of_orders' => $mcrypt->encrypt($total_orders),
			'tagged_amount' => $mcrypt->encrypt(number_format((float)$result['total_amount'], 0, '.', '')),
			'submit_date' => $mcrypt->encrypt($result['tagged_submit_date']),
			'runner_name' => $mcrypt->encrypt($result['runner_name']) 
		);

	}
	//$mcrypt->encrypt(number_format((float)$result['total'], 0, '.', '')),
	//$log->write($data);
	$this->response->setOutput(json_encode($data));
}

//////////////////

function tagbilldetailletter()
{
	$log=new Log("tagbill-letter-".date('Y-m-d').".log");
	$mcrypt=new MCrypt();

	$log->write('tagbilldetailletter called');
	$log->write($this->request->post);
	//$log->write($this->request->get);
	$uid=$mcrypt->decrypt($this->request->post['username']);

	if (isset($this->request->post['letter_number'])) 
	{
		$letter_number = $mcrypt->decrypt($this->request->post['letter_number']);
	} 
	else 
	{
		$letter_number = 0;
	}
	$log->write('letter_number:'.$mcrypt->decrypt($this->request->post['letter_number']));

	if (isset($this->request->get['start'])) 
	{
		$start = $mcrypt->decrypt($this->request->get['start']);
	} 
	else 
	{
		$start = 0;
	}
	if (isset($this->request->post['start'])) 
	{
		$start = $mcrypt->decrypt($this->request->post['start']);
	} 
	else 
	{
		$start = 0;
	}	
	$filter_data = array(
		'letter_number' => $letter_number,
		'start' => $start,
		'limit' => 20
	);
	$log->write($filter_data);
	$this->adminmodel('report/reconciliation');
	$results = $this->model_report_reconciliation->gettaggedOrdersletter($filter_data);
	$log->write($results);
	foreach ($results as $result) 
	{
		if($result['bcml_tagged']!='0.00')
		{
		$taggedvalue=$result['bcml_tagged'];
		}
		else	
		{
			$taggedvalue=$result['tagged'];
		}
	
		$grower_info = $result['payment_firstname'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
		$farmer_info=explode('-', $grower_info);

		$grower_id=@$result['shipping_firstname'];
		$farmer_name=ucwords(strtolower(@$farmer_info[0]));
		$data['results'][] = array(
			'store_name' => $mcrypt->encrypt($result['store_name']),
			'store_id' => $mcrypt->encrypt($result['store_id']),
			'total' => $mcrypt->encrypt(number_format((float)$result['total'], 0, '.', '')),
			'tagged' => $mcrypt->encrypt(number_format((float)$taggedvalue, 0, '.', '')),
			'grower_id' => $mcrypt->encrypt($grower_id),
			'farmer_name' => $mcrypt->encrypt($farmer_name),
			'unit' => $mcrypt->encrypt($result['unit_name'])
			);
	}



	$log->write($data);
	$this->response->setOutput(json_encode($data));
}

/////////////////////////
function tagbillsubmitletter()
{
	$log=new Log("tagbill-letter-".date('Y-m-d').".log");
	$mcrypt=new MCrypt();
	$log->write('tagbillsubmitletter called');
	$log->write($this->request->post);
	$keys = array(
			'store_id',
			'amount',
			'date',
			'runner_id',
			'user_id',
			'letter_number'
		);

	foreach ($keys as $key) 
	{
        $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
    }
	$filter_data = array(
			'letter_number' => $this->request->post['letter_number'],
			
			'start' => 0,
			'limit' => 200
	);
	$log->write($this->request->post);
	$log->write($filter_data);
	$runner_id=$this->request->post['runner_id'];
	$letter_number=$this->request->post['letter_number']; 
	$log->write($letter_number);
	$this->adminmodel('report/reconciliation');
	$log->write('come here 2');
	$orders =$this->model_report_reconciliation->gettaggedOrdersletter($filter_data);	
	foreach($orders as $order) 
	{
		$order_id=$order['order_id'];
		$this->model_report_reconciliation->update_runner_id($order_id,$runner_id);
	}
	$this->model_report_reconciliation->insert_into_bank_transaction_letter($this->request->post);
	$this->response->setOutput('1');
}
public function sendtorunner()
{

        $log=new Log("tagbill-letter-".date('Y-m-d').".log"); 
		$log->write('sendtorunner called');
		$log->write($this->request->post);
		$mcrypt=new MCrypt();
	    $this->adminmodel('pos/pos');			       
			$data=array();
			
			$data['userid']=$mcrypt->decrypt($this->request->post['ce']);     
			$data['amount']=$mcrypt->decrypt($this->request->post['amount']); 
			$data['username']=$mcrypt->decrypt($this->request->post['store_incharge_name']);//$mcrypt->decrypt($this->request->post['ce_name']);  
			$data['storeid']=$mcrypt->decrypt($this->request->post['store_id']);   
			$data['storename']= $this->model_pos_pos->getstorename($data['storeid']);//$mcrypt->decrypt($this->request->post['storename']);  	//
			$data['letter_number']=$mcrypt->decrypt($this->request->post['letter_number']); 
			$log->write('decrypted data');			
			$log->write($data);
			
			$ids = $this->model_pos_pos->getusermobile($data['userid']);
			$log->write($ids);
		
			$mobile='9911427348';//$ids['username'];
			//SMS LIB
			$this->load->library('sms');	
			$sms=new sms($this->registry);
			$pin = rand(1000, 9999);
		 
			$data['otp']=$pin;
			$log->write('data to sendsms');			
			$log->write($data);
			$sms->sendsms($mobile,"20",$data);
			
		
			$query_return=$this->model_pos_pos->insert_runner_otp($data['userid'],$data['storeid'],$pin);
			$log->write($query_return);
			if($query_return>0)
			{
			$this->response->setOutput('1');
			}
			else
			{
				$this->response->setOutput('0');
			}
}
///////////////taggedbill letter wise end here///////////////////////// 

}

?>