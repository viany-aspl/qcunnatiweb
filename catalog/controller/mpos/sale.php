<?php
class Controllermpossale extends Controller {


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


public function mysale()
{

		$log=new Log("mysale-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']); 
		if(isset($this->request->get['sdate'])){
			$sdate=$mcrypt->decrypt($this->request->get['sdate']);
	}
	else{
	}
             		$this->load->model('account/customer');
	$jsons = $this->model_account_customer->getsale($uid,$sdate);/////////for cash sale only
		$log->write($jsons);

	$log->write("Total check");

	//$total=$this->model_account_customer->getUserSale($uid);

	//$log->write($total);
	if(empty($sdate))
	{
		$sdate=date('Y-m-d');
	}

	$json['total']=$mcrypt->encrypt(round($this->model_account_customer->getUserSale($uid,$sdate)["total"],2,PHP_ROUND_HALF_UP));			  
	foreach ($jsons as $ids) {		
	$json['products'][] = array(
                        'id'       =>$mcrypt->encrypt($ids['product_id']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
			'pirce' =>$mcrypt->encrypt( $ids['price']+$ids['tax']),
			'quantity' => $mcrypt->encrypt($ids['quantity']),
						
                                            );
	}


		$this->response->setOutput(json_encode($json));

}

//tagged sales ---getsaleTagged

public function mysaletag()
{

		$log=new Log("mysale-tag-".date('Y-m-d').".log");
		$mcrypt=new MCrypt(); 
		$log->write($this->request->post);
		$log->write($this->request->get);
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);
		if(isset($this->request->get['sdate']))
		{
			$sdate=$mcrypt->decrypt($this->request->get['sdate']);
		}
	else{
	}
             		$this->load->model('account/customer');
	$jsons = $this->model_account_customer->getsaleTagged($uid,$sdate,$sid);
		$log->write($jsons);

	$log->write("Total check");

	if(empty($sdate))
	{
		$sdate=date('Y-m-d');
	}
	
	$total_tagged_sale=round($this->model_account_customer->getStoreSaleTagged($uid,$sdate,$sid)["total"],2,PHP_ROUND_HALF_UP);
	$json['total']=$mcrypt->encrypt($total_tagged_sale);			  
		$log->write(round($total_tagged_sale));
	foreach ($jsons as $ids) {		
	$json['products'][] = array(
                        'id'       =>$mcrypt->encrypt($ids['product_id']), 
                        'name'       =>$mcrypt->encrypt($ids['name']),
			'pirce' =>$mcrypt->encrypt( $ids['price']+$ids['tax']),
			'quantity' => $mcrypt->encrypt($ids['quantity']),
						
                                            );
	}

		$this->response->setOutput(json_encode($json));

}


function customersale()
{

		$log=new Log("customersale.log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);

		if (isset($this->request->get['sdate'])) {
			$filter_date_start = $this->request->get['sdate'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['edate'])) {
			$filter_date_end = $this->request->get['edate'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 5;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}


		$this->adminmodel('report/customer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_store'		=> $sid,	
			'start'                  => $mcrypt->decrypt($this->request->post['start']),
			'limit'                  => $this->config->get('config_limit_admin')
		);


		$results = $this->model_report_customer->getOrders($filter_data);

		foreach ($results as $result) {
			$data['products'][] = array(
				'id'       => $mcrypt->encrypt($result['customer']),
				'name'          => $mcrypt->encrypt($result['email']),				
				'pirce'         => $mcrypt->encrypt($result['orders']),
				'quantity'       => $mcrypt->encrypt($result['products']),
				'total'          =>$mcrypt->encrypt( $this->currency->format($result['total'], $this->config->get('config_currency')))				
			);
		}
		$this->response->setOutput(json_encode($data));

}



public function mysalesub()
{

		$log=new Log("mysale.log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);
		if(isset($this->request->get['sdate'])){
			$sdate=$mcrypt->decrypt($this->request->get['sdate']);
	}
	else{
	}
             		$this->load->model('account/customer');
	$jsons = $this->model_account_customer->getsaleSub($uid,$sdate);
		$log->write($jsons);

	$log->write("Total check");

	if(empty($sdate))
	{
		$sdate=date('Y-m-d');
	}

	$json['total']=$mcrypt->encrypt(round($this->model_account_customer->getUserSaleSub($uid,$sdate)["total"],2,PHP_ROUND_HALF_UP));			  
	foreach ($jsons as $ids) {		
	$json['products'][] = array(
                        'id'       =>$mcrypt->encrypt($ids['product_id']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
			'pirce' =>$mcrypt->encrypt( $ids['price']+$ids['tax']),
			'quantity' => $mcrypt->encrypt($ids['quantity']),
						
                                            );
	}


		$this->response->setOutput(json_encode($json));

}


public function mysalechq()
{

		$log=new Log("mysale.log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);
		if(isset($this->request->get['sdate'])){
			$sdate=$mcrypt->decrypt($this->request->get['sdate']);
	}
	else{
	}
             		$this->load->model('account/customer');
	$jsons = $this->model_account_customer->getsaleChq($uid,$sdate);
		$log->write($jsons);

	$log->write("Total check");

	if(empty($sdate))
	{
		$sdate=date('Y-m-d');
	}

	$json['total']=$mcrypt->encrypt(round($this->model_account_customer->getUserSaleChq($uid,$sdate)["total"],2,PHP_ROUND_HALF_UP));			  
	foreach ($jsons as $ids) {		
	$json['products'][] = array(
                        'id'       =>$mcrypt->encrypt($ids['product_id']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
			'pirce' =>$mcrypt->encrypt( $ids['price']+$ids['tax']),
			'quantity' => $mcrypt->encrypt($ids['quantity']),
						
                                            );
	}


		$this->response->setOutput(json_encode($json));

}


public function getcontractor_transactions()
		{  
 
		$log=new Log("contractor-".date('Y-m-d').".log");
		$log->write($_POST); 

		 $mcrypt=new MCrypt();
		$circle_code = $mcrypt->decrypt($_POST['circle_code']);
		$cr_dr_1 = $mcrypt->decrypt($_POST['cr_dr']);	
		if($cr_dr_1=="1") { $cr_dr="cr"; } elseif($cr_dr_1=="2") { $cr_dr="dr"; }else { $cr_dr=""; } 
		$limit_start = $mcrypt->decrypt($_POST['limit_start']);	
		$limit_end = $mcrypt->decrypt($_POST['limit_end']);
		$this->adminmodel('stock/purchase_order');
		$results=$this->model_stock_purchase_order->getcontractor_transactions($circle_code,$cr_dr,$limit_start,$limit_end); 
                            $log->write($results);  
		$totals=0;
		foreach ($results as $result) {
			$datas['products'][] = array(
				
				'product_id'        =>$mcrypt->encrypt( $result['product_id']),
				'order_id'      =>$mcrypt->encrypt( $result['order_id']),
				'name'      =>$mcrypt->encrypt( $result['name']),
				'quantity'        =>$mcrypt->encrypt( $result['quantity']),
				'pirce'		=>$mcrypt->encrypt( str_replace("Rs.","",$result['price'])/$result['quantity']),
				'tax'		=>$mcrypt->encrypt( $result['tax']),
				'total'		=>$mcrypt->encrypt(str_replace("Rs.","",$result['price'])),
				'date'		=>$mcrypt->encrypt(date('d/m/Y',strtotime($result['crdate'])))  
											
			);
		$totals=$totals+(str_replace("Rs.","",$result['price']));  
		}
		$datas["total"]=$mcrypt->encrypt($totals); //$mcrypt->encrypt('123');
		 $log->write($datas); 
		$this->response->setOutput(json_encode($datas));

		}


public function gettodaysales()
		{  
 
		$log=new Log("today-".date('Y-m-d').".log");
		$log->write($_POST); 
		$log->write($_GET); 
		$mcrypt=new MCrypt();
		$today_date = $mcrypt->decrypt($_GET['today_date']);
		$store_id = $mcrypt->decrypt($_POST['store_id']);
		if($_GET['today_date']=="")
		{
			$today_date =date('Y-m-d');
		}
		$this->adminmodel('sale/order');
		$results=$this->model_sale_order->gettodaysales_cash_tageed_subsidy($today_date,$store_id); 
                        $log->write($results);  //print_r($results);
		
		if($_POST['store_id']!="")
		{
			$datas['products'][] = array(
				'name'=>$mcrypt->encrypt("Cash"),
				'price'        =>$mcrypt->encrypt($results['cash'])											
 			);
                                   $datas['products'][] = array(
				'name'=>$mcrypt->encrypt("Tagged"),
				'price'        =>$mcrypt->encrypt($results['tagged'])											
			);
                                   $datas['products'][] = array(
				'name'=>$mcrypt->encrypt("Subsidy"),
				'price'        =>$mcrypt->encrypt($results['subsidy'])											
			);
		 
		$datas['total']=$mcrypt->encrypt($results['total']);
		}
		else
		{
		$datas['products'][] = array(
				'name'=>$mcrypt->encrypt("Cash"),
				'price'        =>''											
 			);
                                   $datas['products'][] = array(
				'name'=>$mcrypt->encrypt("Tagged"),
				'price'        =>''										
			);
                                   $datas['products'][] = array(
				'name'=>$mcrypt->encrypt("Subsidy"),
				'price'        =>''										
			);
                $datas['total']='';
		}
		 $log->write($datas); 
		$this->response->setOutput(json_encode($datas));

		}


}