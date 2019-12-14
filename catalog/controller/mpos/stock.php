<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class Controllermposstock extends Controller{


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

	public function history(){

		$log=new Log("stockhis-".date('Y-m-d').".log");
		$log->write($this->request->post);
		$log->write($this->request->get);
		$mcrypt=new MCrypt();
		$this->adminmodel('stock/purchase_order');
		$search=0;
		
		if ( isset($this->request->post['q']) ) 
		{
			$page = 1;
			$search=$mcrypt->decrypt($this->request->post['q']);
		}
		elseif (isset($this->request->get['page'])) {
			$page = $mcrypt->decrypt($this->request->get['page']);
		}
		 else {
			$page = 1;
		}		
		$log->write("st=".$search);
		$start = ($page-1)*20;
		$limit = 20;
		$store_id=$mcrypt->decrypt($this->request->post['sid']);
		if(isset($this->request->post['rsid'])){
		$store_id_to=$mcrypt->decrypt($this->request->post['rsid']);		
		$results = $this->model_stock_purchase_order->getListRecSearch($start,$limit,$store_id,$search,$store_id_to);
		}
		else{
			$results =$this->model_stock_purchase_order->getListRecSup($start,$limit,$store_id,$search);
			}
		$log->write($results);
		/*getting the list of the orders*/		
						
		
		foreach ($results as $result) {
			$data['products'][] = array(
				'order_id'      =>$mcrypt->encrypt( $result['id']),
				'customer'      =>$mcrypt->encrypt( $result['user_id']),
				'status'        =>$mcrypt->encrypt( $result['receivetype']),
				'total'		=>$mcrypt->encrypt( ($result['tax']+$result['subtotal'])),
				'date_added'    =>$mcrypt->encrypt( date($this->language->get('date_format_short'), strtotime($result['order_date']))),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['order_sup_send'])),
				'telephone'	=> $mcrypt->encrypt($result['recipient_number'])
							
			);
		}
	$log->write($data);
	$this->response->setOutput(json_encode($data));	

}

	public function orderlist()
	{
		
		$log=new Log("stockhis-".date('Y-m-d').".log");

		$mcrypt=new MCrypt();
		$this->adminmodel('stock/purchase_order');
		
		if (isset($this->request->get['page'])) {
			$page = $mcrypt->decrypt($this->request->get['page']);
		} else {
			$page = 1;
		}		
		$start = ($page-1)*20;
		$limit = 20;
		$store_id=$mcrypt->decrypt($this->request->post['sid']);
		$log->write($store_id);
		//$data['order_list'] =$mcrypt->encrypt(serialize( $this->model_stock_purchase_order->getListRec($start,$limit,$store_id)));
		$da_data=$this->model_stock_purchase_order->getListRec($start,$limit,$store_id);
		//$log->write($da_data);
		foreach($da_data as $d_data)
		{
		 $data['order_list'][]=array
        (
            'id' => $mcrypt->encrypt($d_data['id']),
            'order_date' => $mcrypt->encrypt($d_data['order_date']),
            'order_sup_send' => $mcrypt->encrypt($d_data['order_sup_send']),
            'delete_bit' => $mcrypt->encrypt($d_data['delete_bit']),
            'user_id' => $mcrypt->encrypt($d_data['user_id']),
            'receive_date' => $mcrypt->encrypt($d_data['receive_date']),
            'receive_bit' => $mcrypt->encrypt($d_data['receive_bit']),
            'pending_bit' => $mcrypt->encrypt($d_data['pending_bit']),
            'pre_supplier_bit' => $mcrypt->encrypt($d_data['pre_supplier_bit']),
            'order_status_id' => $mcrypt->encrypt($d_data['order_status_id']),
            'canceled_by' => $mcrypt->encrypt($d_data['canceled_by']),
            'canceled_message' => $mcrypt->encrypt($d_data['canceled_message']),
            'store_id' => $mcrypt->encrypt($d_data['store_id']),
            'store_type' => $mcrypt->encrypt($d_data['store_type']),
            'potential_date' => $mcrypt->encrypt($d_data['potential_date']),
            'receivetype' => $mcrypt->encrypt($d_data['receivetype'])
        );
		}
		//$log->write($data);
		
		
		//getting total orders
		
		//$total_orders = $this->model_stock_purchase_order->getTotalOrders();
		
		//getting pages

		
		//getting pages
		
		
		
		//$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));
		//$log->write($data['results']);
		
		$this->response->setOutput(json_encode($data));	

	}

public function orderlistAll()
    {
        /*getting the list of the orders*/
        $log=new Log("stockhis-".date('Y-m-d').".log");

        $mcrypt=new MCrypt();
        $this->adminmodel('stock/purchase_order');
       
        if (isset($this->request->get['page'])) {
            $page = $mcrypt->decrypt($this->request->get['page']);
        } else {
            $page = 1;
        }       
        $start = ($page-1)*20;
        $limit = 20;
        $log->write('orderlistAll called for seacrh purchase request order');
        $log->write($this->request->post);
       
        $store_id=$mcrypt->decrypt($this->request->post['sid']);
        $order_id=$mcrypt->decrypt($this->request->post['order_id']);
        $log->write('order_id-'.$order_id.' && store_id-'.$store_id);
        //$data['order_list'] =$mcrypt->encrypt(serialize( $this->model_stock_purchase_order->getListRec($start,$limit,$store_id)));
        $da_data=$this->model_stock_purchase_order->getListStore($start,$limit,$store_id,$order_id);
        //$log->write($da_data);
        foreach($da_data as $d_data)
        {
         $data['order_list'][]=array
        (
            'id' => $mcrypt->encrypt($d_data['id']),
            'order_date' => $mcrypt->encrypt($d_data['order_date']),
            'order_sup_send' => $mcrypt->encrypt($d_data['order_sup_send']),
            'delete_bit' => $mcrypt->encrypt($d_data['delete_bit']),
            'user_id' => $mcrypt->encrypt($d_data['user_id']),
            'receive_date' => $mcrypt->encrypt($d_data['receive_date']),
            'receive_bit' => $mcrypt->encrypt($d_data['receive_bit']),
            'pending_bit' => $mcrypt->encrypt($d_data['pending_bit']),
            'pre_supplier_bit' => $mcrypt->encrypt($d_data['pre_supplier_bit']),
            'order_status_id' => $mcrypt->encrypt($d_data['order_status_id']),
            'canceled_by' => $mcrypt->encrypt($d_data['canceled_by']),
            'canceled_message' => $mcrypt->encrypt($d_data['canceled_message']),
            'store_id' => $mcrypt->encrypt($d_data['store_id']),
            'store_type' => $mcrypt->encrypt($d_data['store_type']),
            'potential_date' => $mcrypt->encrypt($d_data['potential_date']),
            'receivetype' => $mcrypt->encrypt($d_data['receivetype'])
        );
        }
        //$log->write($data);
        /*getting the list of the orders*/
       
        //getting total orders
       
        //$total_orders = $this->model_stock_purchase_order->getTotalOrders();
       
        //getting pages

       
        //getting pages
       
       
       
        //$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));
        //$log->write($data['results']);
       
        $this->response->setOutput(json_encode($data));   

    }

 /*
	public function orderlist()
	{
								
						
					
			$log=new Log("stockhis-".date('Y-m-d').".log");

		$mcrypt=new MCrypt();
		$this->adminmodel('stock/purchase_order');
		
		if (isset($this->request->get['page'])) {
			$page = $mcrypt->decrypt($this->request->get['page']);
		} else {
			$page = 1;
		}		
		$start = ($page-1)*20;
		$limit = 20;
		$store_id=$mcrypt->decrypt($this->request->post['sid']);
		$log->write($store_id);
		$data['order_list'] =$mcrypt->encrypt(serialize( $this->model_stock_purchase_order->getListRec($start,$limit,$store_id)));
		$log->write($data['order_list']);
		
		//getting total orders
		
		$total_orders = $this->model_stock_purchase_order->getTotalOrders();
		
		//getting pages

		
		//getting pages
		
		
		
		$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));
		$log->write($data['results']);
		
		$this->response->setOutput(json_encode($data));	

	}
 */

/*----------------------------view_order_details function starts here------------*/

	public function order_details()
	{
				 $mcrypt=new MCrypt();
//$mcrypt->decrypt
		$order_id = $mcrypt->decrypt($this->request->get['order_id']);							
		$this->adminmodel('stock/purchase_order');
		$data['order_information'] =$this->model_stock_purchase_order->view_order_details($order_id);		
//print_r($data['order_information']['products']);
		$this->response->setOutput(json_encode($data['order_information']['products']));
		
	}

	
	public function order_details_search()
	{
				 $mcrypt=new MCrypt();

		$order_id = $mcrypt->decrypt($this->request->get['order_id']);							
		$this->adminmodel('stock/purchase_order');
		$data['order_information'] =$this->model_stock_purchase_order->view_order_details($order_id);	

	foreach ($data['order_information']['products'] as $result) {
			$datas['products'][] = array(
				'order_id'      =>$mcrypt->encrypt( $result['order_id']),
				'name'      =>$mcrypt->encrypt( $result['name']),
				'product_id'        =>$mcrypt->encrypt( $result['product_id']),
				'quantity'        =>$mcrypt->encrypt( $result['quantity']),
				'price'		=>$mcrypt->encrypt( str_replace("Rs.","",$result['price'])/$result['quantity']),
				'tax'		=>$mcrypt->encrypt( $result['tax']),
				'total'		=>$mcrypt->encrypt(str_replace("Rs.","",$result['price'])+($result['tax']*$result['quantity'])),
											
			);
		}
	
	//print_r($data['order_information']['products']);

	$datas['tax']=$mcrypt->encrypt($data['order_information']['order_info']['tax']);
	$datas['subtotal']=$mcrypt->encrypt($data['order_information']['order_info']['subtotal']);
	$datas['total']=$mcrypt->encrypt(($data['order_information']['order_info']['tax']+$data['order_information']['order_info']['subtotal']));
	$datas['to']=$mcrypt->encrypt($this->model_stock_purchase_order->getOrdersStore($order_id));


		$this->response->setOutput(json_encode($datas));
		
	}
	
	/*----------------------------view_order_details function ends here--------------*/

	

	/*-----------------------------insert receive order function starts here-------------------*/
	
	public function receive_order()
	{

		 $mcrypt=new MCrypt();
		$log=new Log("receive-stock-".date('Y-m-d').".log");

		$log->write($this->request->post);
		$order_id = $mcrypt->decrypt($this->request->post['order_id']);
		$received_quantities = $this->request->post['receive_quantity'];
		$suppliers_ids = $this->request->post['supplier'];
		$received_product_ids = $this->request->post['product_id'];
		$user_id=$mcrypt->decrypt($this->request->post['username']);
		$log->write('user_id-'.$user_id);
		$this->session->data['user_id']=$user_id;
		$this->load->library('user');
                	$this->user = new User($this->registry);

		$user_store=$this->user->getStoreId();
		$log->write('user_store-'.$user_store);
		if(empty($user_store))
		{
			$data['receive_message'] = $mcrypt->encrypt('Sorry!! something went wrong, try again');
			$this->response->setOutput(json_encode($data));
		}
			$i=0;
			$received_quantities_de =array();
			foreach($received_quantities as $qnty)
			{
				$log->write($qnty);
				if($i!=0)
				{
					
					$received_quantities_de[$i]="next product";
					$i++;
					
				}
				$received_quantities_de[$i]=$mcrypt->decrypt($qnty);
						
				$i++;
			}
		//$log->write($received_quantities_de);
		$received_quantities=$received_quantities_de;
		$received_product_idss=array();
		$i=0;
			foreach($received_product_ids as $pid)
			{
				$received_product_idss[$i]=$mcrypt->decrypt($pid);
				$i++;
			}

		//$log->write($received_product_idss);
		$received_product_ids=$received_product_idss;


		$order_receive_date = date("Y-m-d");//$this->request->post['order_receive_date'];
		$prices = $this->request->post['price'];
		$rq = $this->request->post['remaining_quantity'];
		if(isset($this->request->post['disable_bit']))
		{
			$data['disable_bit'] = 1;
		}
		/*print_r($received_quantities);
		print_r($suppliers_ids);
		print_r($received_product_ids);
		print_r($order_receive_date);
		print_r($prices);
		exit;*/
		$received_order_info['received_quantities'] = $received_quantities;
		$received_order_info['received_product_ids'] = $received_product_ids;
		$received_order_info['suppliers_ids'] = $suppliers_ids;
		$received_order_info['order_receive_date'] = $order_receive_date;
		$received_order_info['prices'] = $prices;
		
		$received_order_info['rq'] = $rq;
		$url = ''; 
		if($order_id)
		{
			$url .= '&order_id=' . $order_id;
		}
		$log->write("before check");
		if((count($received_quantities) != count(array_filter($received_quantities))) || (count($prices) != count(array_filter($prices))) || $order_receive_date == '')
		{
			$_SESSION['empty_fields_error'] = 'Warning: Please check the form carefully for errors!';
			
				$log->write("in check");		
			
			
			$this->adminmodel('stock/purchase_order');
			$data['order_information'] = $this->model_stock_purchase_order->view_order_details($order_id);
			
			for($i =0; $i<count($data['order_information']['products']); $i++)
			{
				unset($data['order_information']['products'][$i]['quantities']);
				unset($data['order_information']['products'][$i]['prices']);
			}
			
			$start_loop = 0;
			$data['validation_bit'] = 1;
			
			for($i = 0; $i<count($received_product_ids); $i++)
			{
				for($j = $start_loop; $j<count($prices); $j++)
				{
					if($prices[$j] == 'next product')
					{
						$start_loop = $j + 1;
						break;
					}
					else
					{
						$data['order_information']['products'][$i]['quantities'][$j] = $received_quantities[$j];
						$data['order_information']['products'][$i]['suppliers'][$j] = $suppliers_ids[$j];
						$data['order_information']['products'][$i]['prices'][$j] = $prices[$j];
					}
				}
				
				$data['order_information']['products'][$i]['quantities'] = array_values($data['order_information']['products'][$i]['quantities']);
				$data['order_information']['products'][$i]['suppliers'] = array_values($data['order_information']['products'][$i]['suppliers']);
				$data['order_information']['products'][$i]['prices'] = array_values($data['order_information']['products'][$i]['prices']);
				$data['order_information']['products'][$i]['rq'] = $received_order_info['rq'][$i];
			}
			
			$data['order_id'] = $order_id;
			if($order_receive_date)
			{
				$data['order_information']['order_info']['receive_date'] =  $order_receive_date;
			}
			else
			{
				$data['order_information']['order_info']['receive_date'] =  '0000-00-00';
			}
			$datas['receive_message'] = $mcrypt->encrypt('Warning: Please check the form carefully for errors!');

			$this->response->setOutput(json_encode($datas));
		}
		else
		{
			$log->write("after check");
			if(isset($this->request->post['disable_bit']))
			{
				unset($received_order_info['suppliers_ids']);
			}
			$this->adminmodel('stock/purchase_order');
			//check stock already received
			$checkAccepted=$this->model_stock_purchase_order->getList_order($order_id);
			if(!$checkAccepted)
			{
				//////////////////////////////
				
				for($i =0; $i<count($received_order_info['received_quantities']); $i++)
				{
					$log->write($received_order_info['received_quantities'][$i]);
					if($received_order_info['received_quantities'][$i] != "next product")
					{
						$received_qnties[$i] = $received_order_info['received_quantities'][$i]; 
					}
				}
				
				$received_qnties = array_values($received_qnties);
				
				for($a=0;$a<count($received_order_info['received_product_ids']);$a++)
				{
					$product_id=$received_order_info['received_product_ids'][$a];
					$product_qnty=$received_qnties[$a];
					$log->write('product_id :'.$product_id);
					$log->write('product_qnty :'.$product_qnty);
					$log->write('order_id :'.$order_id);
					
					$checkedqnty=$this->check_for_qnty($order_id,$product_id,$product_qnty);
					if(!empty($checkedqnty))
					{
						$data['receive_message'] = $mcrypt->encrypt($checkedqnty);
						$this->response->setOutput(json_encode($data));
						
						return;
					}
				}
				
				/////////////////////////////////////////

				$inserted = $this->model_stock_purchase_order->insert_receive_order($received_order_info,$order_id);
				if($inserted)
				{
					$data['receive_message'] = $mcrypt->encrypt('Order received Successfully!!');
					$this->response->setOutput(json_encode($data));
				}
				else
				{
					$data['receive_message'] = $mcrypt->encrypt('Sorry!! something went wrong, try again');
					$this->response->setOutput(json_encode($data));	
				}
			}
			else{
				$data['receive_message'] = $mcrypt->encrypt('This order has already been received');
				$this->response->setOutput(json_encode($data));	

			}
				
		}
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/
private function check_for_qnty($order_id,$product_id,$product_qnty)
    {
        $log=new Log("receive-stock-".date('Y-m-d').".log");
		$log->write('check_for_qnty called');
        $log->write( $order_id.'-'.$product_id.'-'.$product_qnty); 
        
            
        $this->adminmodel('stock/purchase_order');
		$filter_data = array(
                        'order_id'         => $order_id,
                        'product_id'         => $product_id,
                        'product_qnty'=>$product_qnty
                    );
        
        $results = $this->model_stock_purchase_order->getproductQnty($filter_data);
        $log->write($results);
        
        if($results['quantity']!=$product_qnty)
        {
            $log->write('in received qnty is differ');
            $msg='Enter quantity not matched with sent quantity for '.$results['name'];
            $log->write($msg);
            return $msg;
        }
        
    }
private function check_for_transit($receiver_store_id,$product_ids)
    {
        $log=new Log("stock_transit-".date('Y-m-d').".log");
        $log->write( $receiver_store_id);
        $log->write( $product_ids);
            
        $this->adminmodel('report/stock');
        foreach($product_ids as $product)
        {
            $product_id=$product[0];
            $filter_data = array(
                        'filter_store'         => 'null',
                        'filter_date_start'         => '2016-01-01',
                        'filter_date_end'         => date('Y-m-d'),
                        'filter_name'         => null,
                        'filter_name_id'         => $product_id,
                        'filter_receiver'=>$receiver_store_id
                    );
            $log->write( $filter_data);
            $results = $this->model_report_stock->getOrdersTransit($filter_data);
            $log->write(count($results));
            
            $mcrypt=new MCrypt();
            if(count($results)>0)
            {
                $log->write('in if count is greater then 0');
                //print_r($results[0]);
                $msg=$product[1].' is already under Transit for '.$results[0]['store_name'];
                $log->write($msg);
                
                return $msg;
            }
        }
    }

	/*--------------------Insert Purchase Order starts heres-------------------------------------------------*/
	
	public function request_order()
	{
		 $mcrypt=new MCrypt();
		$data['products'] = $_POST['product'];
		$data['prices'] = $_POST['prices'];	
		$data['taxes'] = $_POST['taxes'];	
		$data['options'] = $_POST['options'];
		$data['option_values'] = $_POST['option_values'];
		$data['quantity'] = $_POST['quantity'];
		$data['supplier_id'] =$_POST['supplier_id'];//"--Supplier--";
		$data['stores'] = $_POST['stores'];
		$data['recipient_number']=$mcrypt->decrypt($_POST['cmt']);
		$data['transport_id']=$mcrypt->decrypt($_POST['tid']);
		$data['tax']=$mcrypt->decrypt($_POST['ta']);
		$data['subtotal']=$mcrypt->decrypt($_POST['sub']);
		$this->session->data['user_id']=$mcrypt->decrypt($_POST['username']);
		$this->load->library('user');
                $this->user = new User($this->registry);

		$log=new Log("requeststock-".date('Y-m-d').".log");
		//$log->write( $this->request->post);//$_POST);
		$log->write($data);



		//credit
		$this->adminmodel('setting/store');
		if(isset($this->request->post["stock_fm"]))
		{
			
			//stores

			foreach($data['stores'] as $store)
			{
				$productvalcheck=explode('_',$mcrypt->decrypt($store));
				$storecheck=$productvalcheck[0]."_".$productvalcheck[1];	
				//$store_namescheck[$i] = explode('_',$storecheck);
				$log->write('productvalcheck->'.$productvalcheck);
				$log->write($storecheck);
				$data["stock_fm"]=$productvalcheck[0];
				$circle_data=$this->model_setting_store->getCircleCredit($productvalcheck[0],$mcrypt->decrypt($this->request->post["stock_fm"]));
				$log->write($circle_data);
				$cr_limit=$circle_data["creditlimit"];
				$cr_credit=$circle_data["currentcredit"];
				$log->write('--'.$cr_limit.','.$cr_credit);

				if(($data['tax']+$data['subtotal']+$cr_credit)>$cr_limit)
				{
				   $log->write('Error: Amount greater than credit limit');
					$json['success']="";
				   $json['error'] = ('Amount greater than current credit limit');
				   $this->response->setOutput(json_encode($json));	
	                           return "";
				}
			}


		}
		//end

		
		/*to let the user add products without options*/
		for($i = 0 ; $i <count($data['options']); $i++)
		{
			if($data['options'][$i] == '')
			{
				$data['options'][$i] = '0_option';
			}
		}
		
		/*to let the user add products without option values*/
		for($i = 0 ; $i <count($data['option_values']); $i++)
		{
			if($data['option_values'][$i] == '')
			{
				$data['option_values'][$i] = '0_optionvalue';
			}
		}
		
		if((in_array("--products--",$data['products'])) || (in_array("--stores--",$data['stores'])) || (in_array("--Product Options--",$data['options'])) || (in_array("--Option Values--",$data['option_values'])) || (count($data['quantity']) != count(array_filter($data['quantity']))) || (count($data['options']) != count(array_filter($data['options']))) || (count($data['products']) != count(array_filter($data['products']))) || (count($data['option_values']) != count(array_filter($data['option_values']))))
		{
			$log->write("in if");

			$data['form_bit'] = 0;
			$_SESSION['errors'] = "Warning: Please check the form carefully for errors!";
			/*------------Working with data received starts-----*/
			
			$i = 0;
			foreach($data['products'] as $product)
			{
				if(strrchr($product,"_"))
				{
				$product_names[$i] = explode('_',$product);
				}
				else
				{
					$product_names[$i] = $product;
				}
				$i++;
			}
			$data['product_received'] = $product_names;
			$i = 0;
			foreach($data['options'] as $option)
			{
				if(strrchr($option,"_"))
				{
					$options[$i] = explode('_',$option);
				}
				else
				{
					$options[$i] = $option;
				}
				$i++;
			}
			$data['options_received'] = $options;
			$i = 0;
			foreach($data['option_values'] as $option_value)
			{
				if(strrchr($option_value,"_"))
				{
					$option_values[$i] = explode('_',$option_value);
				}
				else
				{
					$option_values[$i] = $option_value;
				}
				$i++;
			}
			$data['option_values_received'] = $option_values;
			//print_r($data['option_values_received']);
			$data['quantities_received'] = $data['quantity'];
			/*------working with data received ends---------*/
			$this->load->model('catalog/product');
			$products = $this->model_catalog_product->getProducts();
			$i = 0;
			foreach($products as $product)
			{
				$products[$i] = $product['name'];
				$product_ids[$i] = $product['product_id'];
				$i++;
			}
			$data['products'] = $products;
			$data['product_ids'] = $product_ids;
			$this->load->model('catalog/option');
			$data['options'] = $this->model_catalog_option->getOptions();
			/*$i = 0;
			foreach($data['options_received'] as $option)
			{
				$option_values[$i] = $this->model_catalog_option->getOptionValues($option[0]); 
				$i++;
			}*/
			$data['option_values'] = $option_values;
			$url = '';
								
			$this->load->model('catalog/option');
			$data['options'] = $this->model_catalog_option->getOptions();

					}
		else
		{

			$log->write("in else");
			$iq = 0;
					foreach($data['quantity'] as $qnty){

					$qntry_final[$iq]=$mcrypt->decrypt($qnty);
					$iq++;					

				}
$log->write($qntry_final);

$data['quantity']=$qntry_final;

			$i = 0;
			foreach($data['products'] as $product)
			{
				//$product=$mcrypt->decrypt($product);
			$log->write($product);
					
					$productval=explode('_',$product);
				$product=$mcrypt->decrypt($productval[0])."_".$mcrypt->decrypt($productval[1]);					
				$product_names[$i] = explode('_',$product);
				$i++;
			}


$log->write($product_names);


			$data['products'] = $product_names;



//prices
$ip = 0;
			foreach($data['prices'] as $product)
			{
				//$product=$mcrypt->decrypt($product);
			$log->write($product);
					
					$productval=explode('_',$product);
				$product=$mcrypt->decrypt($productval[0])."_".$mcrypt->decrypt($productval[1]);					
				$product_price[$ip] = explode('_',$product);
				$ip++;
			}
$log->write($product_price);


			$data['prices'] = $product_price;

$log->write("in taxs");

//taxes
$it = 0;
			foreach($data['taxes'] as $product)
			{
				//$product=$mcrypt->decrypt($product);
			$log->write($product);
					
					$productval=explode('_',$product);
				$product=$mcrypt->decrypt($productval[0])."_".($productval[1]);	
			$log->write($product);
			$log->write("pri");				
				$product_tax[$it] = explode('_',$product);
				$it++;
			}
$log->write("in tax");

$log->write($product_tax);


			$data['taxes'] = $product_tax;

			//stores
                        $i = 0;
			foreach($data['stores'] as $store)
			{
					$productval=explode('_',$store);
				$store=$mcrypt->decrypt($productval[0])."_".$mcrypt->decrypt($productval[1]);	
				$store_names[$i] = explode('_',$store);
				$i++;
			}
			$data['stores'] = $store_names;
            $log->write($store_names);
            //////////call of checktransit
            
            $checkedtransit=$this->check_for_transit($store_names[0][0],$data['products']);
            if(!empty($checkedtransit))
            {
                $json['success']='';
                $json['error'] = $checkedtransit;
                $this->response->setOutput(json_encode($json));   
                
                return;
            }
            /////end  of checktransit           
			$i = 0;
			foreach($data['options'] as $option)
			{
				if(strrchr($option,"_"))
				{
					$options[$i] = explode('_',$option);
				}
				else
				{
					$options[$i] = $option;
				}
				$i++;
			}
			$data['options'] = $options;
			$i = 0;
			foreach($data['option_values'] as $option_value)
			{
				if(strrchr($option_value,"_"))
				{
					$option_values[$i] = explode('_',$option_value);
				}
				else
				{
					$option_values[$i] = $option_value;
				}
				$i++;
			}
			
			//data

			$iqs = 0;
					foreach($data['supplier_id'] as $supplier_id){

					$supplier_id_final[$iqs]=$mcrypt->decrypt($supplier_id);
					$iqs++;					

				}
			$data['supplier_id']=$supplier_id_final;
			//	
			
                                          $log->write("before");				
			$data['option_values'] = $option_values;			
			$this->adminmodel('stock/purchase_order');
			$log->write("after");	
			$log->write($data["products"][0][0].",".$data['supplier_id'][0]);

			$store_current_quantity=$this->model_stock_purchase_order->get_store_quantity($data["products"][0][0],$data['supplier_id'][0]);
			$log->write($store_current_quantity);
			$log->write($data["quantity"][0]);
			if($store_current_quantity>=$data["quantity"][0])  
                                          {
			$order_id = $this->model_stock_purchase_order->insert_purchase_order($data);
			  $log->write("after id".$order_id);													
			if($order_id)
			{
				$_SESSION['success_order_message'] = "The Order has been added";
					                $json['order_id'] = $mcrypt->encrypt( $order_id);
				                $json['success'] = $mcrypt->encrypt('Success: new order placed with ID: '.$order_id);
				$this->load->model('checkout/order');
				 $gtax=$this->model_checkout_order->getgtax($order_id);

	  			 $json['gtax']= $mcrypt->encrypt(json_encode($gtax));
				$this->response->setOutput(json_encode($json));	


			}
	                          }
                                        else
		            {
                                           $log->write("You dont have enough stock for transfer");
			 $json['success']='';
			 $json['error'] = 'You dont have enough stock for transfer';
			 $this->response->setOutput(json_encode($json));	
		             } 
		}
	}
	
	/*--------------------Insert purchase order ends here----------------------------*/
	
	///////////////////////////////////////////////////////////////////////////////////
	
	
	
	
		 
	
}

?>