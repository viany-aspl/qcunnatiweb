<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class Controllermposinventory extends Controller{


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
		
		$start = ($page-1)*20;
		$limit = 20;

		$data['order_list'] =$mcrypt->encrypt(serialize( $this->model_inventory_purchase_order->getList($start,$limit)));
		/*getting the list of the orders*/
		
		//getting total orders
		
		$total_orders = $this->model_inventory_purchase_order->getTotalOrders();
		
		//getting pages

		
		//getting pages
		
		
		
		$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));

		$this->response->setOutput(json_encode($data));	

	}
/*----------------------------view_order_details function starts here------------*/
	
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

		$log=new Log("cntrece.log");
		$order_id = $this->request->get['order_id'];
		$received_quantities = $this->request->post['receive_quantity'];
		$suppliers_ids = $this->request->post['supplier'];
		$received_product_ids = $this->request->post['product_id'];
		
		$order_receive_date = $this->request->post['order_receive_date'];
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

			$this->response->setOutput($data);
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
				$data['receive_message'] = 'Order received Successfully!!';
				$this->response->setOutput(json_encode($data));
			}
			else
			{
				$data['receive_message'] = 'Sorry!! something went wrong, try again';
				$this->response->setOutput(json_encode($data));	
			}
				
		}
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/



	/*--------------------Insert Purchase Order starts heres-------------------------------------------------*/
	
	public function request_order()
	{
		 $mcrypt=new MCrypt();
		$data['products'] = $_POST['product'];
		$data['options'] = $_POST['options'];
		$data['option_values'] = $_POST['option_values'];
		$data['quantity'] = $_POST['quantity'];
		$data['supplier_id'] ="--Supplier--"; //$_POST['supplier_id'];
		$data['stores'] = $_POST['stores'];


		$this->load->library('user');
                $this->user = new User($this->registry);

$log=new Log("request.log");
$log->write( $this->request->post);//$_POST);
$log->write($data);



$this->session->data['user_id']=$mcrypt->decrypt($_POST['username']);
		
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
			
			                        $log->write("before");	
			
			$data['option_values'] = $option_values;
			
			$this->adminmodel('inventory/purchase_order');
			  $log->write("after");	
			$order_id = $this->model_inventory_purchase_order->insert_purchase_order($data);
			  $log->write("after id".$order_id);										

			
			if($order_id)
			{
				$_SESSION['success_order_message'] = "The Order has been added";
					                $json['order_id'] = $mcrypt->encrypt( $order_id);
				                $json['success'] = $mcrypt->encrypt('Success: new order placed with ID: '.$order_id);
				$this->response->setOutput(json_encode($json));	


			}
		}
	}
	
	/*--------------------Insert purchase order ends here----------------------------*/
	
	///////////////////////////////////////////////////////////////////////////////////
	
	
	
	
		
	
}

?>