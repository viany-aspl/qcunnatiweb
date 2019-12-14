<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class Controllermposreturn extends Controller{


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
/*--------------------Insert return Order starts heres-------------------------------------------------*/
	
	public function return_order()
	{
            $log=new Log("po-return-".date('Y-m-d').".log");
            $log->write( $this->request->post);
		$mcrypt=new MCrypt();
		$data=array();
		for($i=0;$i<count($this->request->post['product']);$i++)
                	{
		$log->write('in loop');
		$log->write($mcrypt->decrypt($this->request->post['product'][$i]));

		$pp=explode('_',$mcrypt->decrypt($this->request->post['product'][$i]));
		$log->write($pp);
		$data['products'][] = $pp[0];
		
		$data['quantity'][] = $mcrypt->decrypt($this->request->post['quantity'][$i]);
		}
		$data['store'] = $mcrypt->decrypt($_POST['store']);
		$data['username'] = $mcrypt->decrypt($_POST['username']);
        		$data['remarks'] = $mcrypt->decrypt($_POST['remarks']);
		$this->load->library('user');
                	$this->user = new User($this->registry);

		$log->write( $mcrypt->decrypt($this->request->post['product'][0]));
		$log->write( $mcrypt->decrypt($this->request->post['quantity'][0]));
		//$log->write( $mcrypt->decrypt($this->request->post['store']));
		//$log->write( $mcrypt->decrypt($this->request->post['username']));
		//$log->write( $mcrypt->decrypt($this->request->post['remarks']));
		$log->write($data);

		$this->session->data['user_id']=$mcrypt->decrypt($_POST['username']);

	
			
			  $this->adminmodel('inventory/return_order');
			  $log->write("before");	
			  $order_id = $this->model_inventory_return_order->insert_return_order($data);
			  $log->write("after id".$order_id);										

			
			if($order_id)
			{
				$_SESSION['success_order_message'] = "The Order has been added";
				$json['order_id'] = $mcrypt->encrypt( $order_id);
				$json['success'] = $mcrypt->encrypt('Success: Return order placed with ID: '.$order_id);
				$this->response->setOutput(json_encode($json));	


			}
		
	}
	
	/*--------------------Insert return order ends here----------------------------*/


	public function orderlist()
	{
								
						
					/*getting the list of the orders*/
						 $mcrypt=new MCrypt();
		$this->adminmodel('inventory/purchase_order');
		
		if (isset($this->request->get['page'])) {
			$page = $mcrypt->decrypt($this->request->get['page']);
		} else {
			$page = 1;
		}
		$log=new Log("inv-".date('Y-m-d').".log");
		$start = ($page-1)*20;
		$limit = 20;
                //echo $uid=$mcrypt->encrypt('7');
		//$uid=$mcrypt->decrypt($this->request->post['username']);
                $uid=7;
		//$log->write($this->model_inventory_purchase_order->getListRecStore($uid,$start,$limit));
		//$data['order_list'] =$mcrypt->encrypt(serialize( $this->model_inventory_purchase_order->getListRecStore($uid,$start,$limit)));
		/*getting the list of the orders*/
		$da_data=$this->model_inventory_purchase_order->getListRecStore($uid,$start,$limit);
		$log->write($da_data);
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
		$log->write($data);
		//getting total orders
		
		//$total_orders = $this->model_inventory_purchase_order->getTotalOrders();
		//$log->write($total_orders);
		//getting pages

		
		//getting pages
		
		
		
		//$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));

		$this->response->setOutput(json_encode($data));	

	}
	
/*----------------------------view_order_details function starts here------------*/
	public function orderlistAll()
    {
                                
                        
                    /*getting the list of the orders*/
                         $mcrypt=new MCrypt();
        $this->adminmodel('inventory/purchase_order');
        
        if (isset($this->request->get['page'])) {
            $page = $mcrypt->decrypt($this->request->get['page']);
        } else {
            $page = 1;
        }
        $log=new Log("inv-".date('Y-m-d').".log");
        $start = ($page-1)*20;
        $limit = 20;
        $uid=$mcrypt->decrypt($this->request->post['username']);        
        $da_data=$this->model_inventory_purchase_order->getListStore($uid,$start,$limit);
        $log->write($da_data);
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
        $log->write($data);
        //getting total orders
        
        //$total_orders = $this->model_inventory_purchase_order->getTotalOrders();
        //$log->write($total_orders);
        //getting pages

        
        //getting pages
        
        
        
        //$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));

        $this->response->setOutput(json_encode($data));    

    }
	public function order_details()
	{
				 $mcrypt=new MCrypt();
//$mcrypt->decrypt
		$order_id = $mcrypt->decrypt($this->request->get['order_id']);							
		$this->adminmodel('inventory/purchase_order');
		$data['order_information'] =$this->model_inventory_purchase_order->view_order_details($order_id);		
//print_r($data['order_information']['products']);
		$this->response->setOutput(json_encode($data['order_information']['products']));
		
	}
	
	/*----------------------------view_order_details function ends here--------------*/

	

	/*-----------------------------insert receive order function starts here-------------------*/
	
	public function receive_order()
	{

		 $mcrypt=new MCrypt();
		$log=new Log("receive.log");

		$log->write($this->request->post);
		$order_id = $mcrypt->decrypt($this->request->post['order_id']);
		$received_quantities = $this->request->post['receive_quantity'];
		$suppliers_ids = $this->request->post['supplier'];
		$received_product_ids = $this->request->post['product_id'];
		$user_id=$mcrypt->decrypt($this->request->post['username']);
		$log->write($user_id);
		$this->session->data['user_id']=$user_id;
		$this->load->library('user');
                $this->user = new User($this->registry);

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
		$log->write($received_quantities_de);
		$received_quantities=$received_quantities_de;
		$received_product_idss=array();
		$i=0;
			foreach($received_product_ids as $pid)
			{
				$received_product_idss[$i]=$mcrypt->decrypt($pid);
				$i++;
			}

		$log->write($received_product_idss);
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
			
			
			$this->adminmodel('inventory/purchase_order');
			$data['order_information'] = $this->model_inventory_purchase_order->view_order_details($order_id);
			
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
			$this->adminmodel('inventory/purchase_order');
			$inserted = $this->model_inventory_purchase_order->insert_receive_order($received_order_info,$order_id);
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
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/



	
	
	///////////////////////////////////////////////////////////////////////////////////
	
	
	
	
		
	
}

?>