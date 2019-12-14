<?php


class ControllermposFmdelivery extends Controller {
	
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


public function bill()
	{
			
			$log=new Log("printallinvoice-".date('Y-m-d').".log");
			$mcrypt=new MCrypt();
			$log->write("print All invoice");
		    	$this->adminmodel('tagpos/fmdelivery');
			$batch=$mcrypt->decrypt($this->request->post['batch']);
			$log->write("print All invoice");
			$finaldata=array();
			if (isset($this->request->post['batch']))
			{
							
		        	$binvcount=$this->model_tagpos_fmdelivery->getbatchinvoice($batch);
				$log->write($binvcount);
				foreach ($binvcount as $value) {
				$log->write($value['invoice_no']);
				$finaldata['orders'][]=array('inv_no'=>$mcrypt->encrypt($value['invoice_no']) ,'inv_html'=>$this->OrderProducts($value['invoice_no']));				
				}
						
			}
			$log->write('finaldatan return by bill');
			$log->write($finaldata);
		$this->response->setOutput(json_encode($finaldata));
		
	}

	
	public function OrderProducts($filter_order_id ) 
		{

		$log=new Log("printallinvoice-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		 $this->request->get['order_id']=$filter_order_id ;
		$data['products'] = array();
		$log->write("hist");
                            $filter_order_id =$this->request->get['order_id'];                                                                                   			
				$log->write("hist2");
			$this->adminmodel('sale/order');
			$this->adminmodel('setting/setting');
			$this->adminmodel('user/user');
		$resord=$this->model_sale_order->getOrderUser($filter_order_id);
		$results = $this->model_sale_order->getOrderProducts($filter_order_id);
						$log->write("hist3");	
		$store_id=$this->model_sale_order->getOrderStoreId(($this->request->get['order_id']));
						$log->write("hist4");
		$store_add=$this->model_setting_setting->getSettingbykey('config','config_address', $store_id);

		foreach ($results as $result) {

		//$log->write($result);
			$data['products'][] = array(
				'order_product_id'      =>$mcrypt->encrypt( $result['order_product_id']),
				'product_id'      =>$mcrypt->encrypt( $result['product_id']),
				'subsidy'	=> $mcrypt->encrypt(empty($this->model_sale_order->getProductSubsidy($result['product_id'],$store_id))?0:$this->model_sale_order->getProductSubsidy($result['product_id'],$store_id)),
				'name'        =>$mcrypt->encrypt( $result['name']),
				'model'         => $mcrypt->encrypt($result['model']),
				'quantity'    =>$mcrypt->encrypt( $result['quantity']),
				'price' => $mcrypt->encrypt( ($result['price']) ),
				'hstn' => $mcrypt->encrypt( ($result['HSTN']) ),
				'total' => $mcrypt->encrypt(($result['total'])+(round($result['tax'])*$result['quantity'])),
				'tax'	=> $mcrypt->encrypt(($result['tax'])),
			
			);
		
		}

		//$log->write($this->model_sale_order->getorderSubTotalvalue($filter_order_id));

		$data['total']=$mcrypt->encrypt($this->model_sale_order->getorderTotalvalue($filter_order_id));
		$data['tax']=$mcrypt->encrypt($this->model_sale_order->getorderTaxvalue($filter_order_id));
		$data['subtotal']=$mcrypt->encrypt($this->model_sale_order->getorderSubTotalvalue($filter_order_id));
		$data['subsidy']=$mcrypt->encrypt($this->model_sale_order->getOrderSubsidy(($filter_order_id)));
		$data['cash']=$mcrypt->encrypt($this->model_sale_order->getOrdercash(($filter_order_id)));
		$data['gstn']=$mcrypt->encrypt($this->model_setting_setting->getSettingbykey('config','config_gstn',$store_id));		
		try{
				$log->write("hist6");
			$orderDetails=$this->model_sale_order->getOrderInfo($filter_order_id);
			$log->write("hist7");
			$log->write("in data");
			//$log->write($orderDetails);
			$this->adminmodel('lead/orderleads');
			$log->write("in data y");	
		//getOrder
		//new addidtion
		$oid=$this->model_lead_orderleads->getbill_to_requisition($filter_order_id);
					$log->write("in data y ".$oid);
	
		$orderlead=$this->model_lead_orderleads->getOrderdtl($oid);
		$log->write("in data s ");
				$log->write($orderlead);
		$data['cus_id']=($orderlead[0]['payment_address_1']);
		if(empty($orderlead[0]['payment_address_1']))
		{
			if(!empty($orderDetails['shipping_firstname']))
			{
			$data['cus_id']=$mcrypt->encrypt($orderDetails['shipping_firstname']);
			}
			else
			{
			$data['cus_id']=$mcrypt->encrypt($orderDetails['customer_id']);
			}
		}
		$data['far_name']=$mcrypt->encrypt($orderlead[0]['payment_firstname']);
		if(!empty($orderDetails['firstname']))
		{
		$farm_father_array=explode('-',$orderDetails['firstname']);
		}
		else
		{
		$farm_father_array=array($orderDetails['shipping_firstname'],'');
		}
		if(empty($orderlead[0]['payment_firstname']))
		{
			$data['far_name']=$mcrypt->encrypt($farm_father_array[0]);
		}
		$data['fath_name']=$mcrypt->encrypt($orderlead[0]['payment_lastname']);
		if(empty($orderlead[0]['payment_lastname']))
		{
			$data['fath_name']=$mcrypt->encrypt($farm_father_array[1]);
		}
		$data['vill_name']=$mcrypt->encrypt($orderlead[0]['shipping_firstname']);
		if(empty($orderlead[0]['shipping_firstname']))
		{
			$data['vill_name']=$mcrypt->encrypt($orderDetails['payment_address_1']);
		}
		$data['stor_name']=($orderlead[0]['store_name']);
		if(empty($orderlead[0]['store_name']))
		{
			$data['stor_name']=$mcrypt->encrypt($orderDetails['store_name']);
		}

		$data['cid']=$mcrypt->encrypt($orderlead[0]['payment_address_1']);
		if(empty($orderlead[0]['payment_address_1']))
		{
			if(!empty($orderDetails['shipping_firstname']))
			{
			$data['cid']=$mcrypt->encrypt($orderDetails['shipping_firstname']);
			}
			else
			{
			$data['cid']=$mcrypt->encrypt($orderDetails['customer_id']);
			}
		}
		$data['fname']=($orderlead[0]['payment_firstname']);
		if(empty($orderlead[0]['payment_firstname']))
		{
			$data['fname']=$mcrypt->encrypt($farm_father_array[0]);
		}
		$data['lname']=($orderlead[0]['payment_lastname']);
		if(empty($orderlead[0]['payment_lastname']))
		{
			$data['lname']=$mcrypt->encrypt($farm_father_array[1]);
		}
		$data['vname']=($orderlead[0]['shipping_firstname']);
		if(empty($orderlead[0]['shipping_firstname']))
		{
			$data['vname']=$mcrypt->encrypt($orderDetails['payment_address_1']);
		}
		$data['stname']=($orderlead[0]['store_name']);
		if(empty($orderlead[0]['store_name']))
		{
			$data['stname']=$mcrypt->encrypt($orderDetails['store_name']);
		}
		
		$delm=	$this->model_sale_order->getOrderdelivery($filter_order_id);
		$log->write($delm);
		$data['deliverymode']=$mcrypt->encrypt("Tagged");//$mcrypt->encrypt("test");
		$data['fmname']=$mcrypt->encrypt($delm['fmname']);  
		
		$log->write($resord);
		$usernames=$this->model_user_user->getUser($resord);
		$log->write($usernames);
		$data['opname']=$mcrypt->encrypt($usernames["firstname"]." ".$usernames["lastname"]);
		$data['stadd']=$mcrypt->encrypt($store_add);
		$data['date'] =$mcrypt->encrypt($orderDetails['date_added']);		  
		$data['invoiceno'] = $mcrypt->encrypt($orderDetails['invoice_prefix'].'-'.$orderDetails['invoice_no']) ;
		$data['refrenseno'] = $mcrypt->encrypt($orderDetails['order_id']) ;
		$data['order_id'] =$mcrypt->encrypt($orderDetails['comment']) ;
		$data['tagged']=$mcrypt->encrypt($orderDetails['tagged']);
		$data['customer_mobile']=$mcrypt->encrypt($orderDetails['telephone']);
		}catch(Exception $el){
			$log->write($el);
			}
				//$log->write($data);
		$this->adminmodel('tagpos/fmdelivery');                
		$gtax=$this->model_tagpos_fmdelivery->getgtax($filter_order_id);  

	              $data['gtax']=(json_encode($gtax));
				  //tax
				  $ttax=json_decode($data['gtax'],TRUE);
			$log->write($ttax);
			$tax_return='';
			foreach ($ttax as $key => $value) {
			    $log->write($value['title']); 
			    $log->write($value['value']); 
			    $finaltax = round(($value['value'] /  2), 2);
			    $log->write( $value['title'].'  '.$finaltax); 
			if (strpos($value['title'], '18') !== false) {	
				$tax_return.="CGST @9% ".$finaltax."</br>" ;
				$tax_return.="SGST @9% ".$finaltax."</br>" ;
				}
  			if (strpos($value['title'], '12') !== false) {

				$tax_return.="CGST @6% ".$finaltax."</br>" ;
				$tax_return.="SGST @6% ".$finaltax."</br>" ;
			}
			if (strpos($value['title'], '5') !== false) {

			$tax_return.="CGST @2.5% ".$finaltax."</br>" ;
			$tax_return.="SGST @2.5% ".$finaltax."</br>" ;
			}
			if (strpos($value['title'], '28') !== false) {	
			$tax_return.="CGST @14% ".$finaltax."</br>" ;
			$tax_return.="SGST @14% ".$finaltax."</br>" ;
			}

			}
		$data['gtax']=$mcrypt->encrypt($tax_return);
				  //end tax
$log->write("final end".$filter_order_id);
		return ($data);

		}



}