<?php
class ControllerSupplierSupplier extends Controller{

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


public function procurement() {

                           $mcrypt=new MCrypt();
                           
                            $log=new Log("supplier-".date('Y-m-d').".log");
		
                            $log->write($this->request->get);
		if (isset($this->request->get['order_id'])) {
			$order_id = $mcrypt->decrypt($this->request->get['order_id']); 		
		} else {
			//$order_id = '21';
		}
		//echo $order_id;
                if (isset($this->request->get['supplier_id'])) {
			$supplier_id = $mcrypt->decrypt($this->request->get['supplier_id']); 		
		} else {
			//$supplier_id = 2;
		}

		$this->adminmodel('procurement/purchase_order');
		$data['order_information'] = $this->model_procurement_purchase_order->view_order_details_by_supplier($order_id,$supplier_id);
		
                $data['store_information'] = $this->model_procurement_purchase_order->view_store_details($data['order_information']['products'][0]['store_id']);
                //print_r($data['order_information']);
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/supplier/supplier.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/supplier/supplier.tpl', $data));
			}
                //$html = $this->load->view('product/search.tpl',$data);
                //echo $html;
	}

public function submit_order_by_supplier() {
    
	        if (isset($this->request->get['supplier_id'])) 
                {
			$supplier_id = $this->request->get['supplier_id']; 
		} 
                else 
                {
			$supplier_id = 0;
		}
                if (isset($this->request->get['order_id'])) 
                {
			$order_id = $this->request->get['order_id']; 
		} 
                else 
                {
			$order_id = 0;
		}
                if (isset($this->request->get['driver_mobile'])) 
                {
			$driver_mobile = $this->request->get['driver_mobile']; 
		} 
                else 
                {
			$driver_mobile = 0;
		}
                if (isset($this->request->get['store_id'])) 
                {
			$store_id = $this->request->get['store_id']; 
		} 
                else 
                {
			$store_id = 0;
		}
		if (isset($this->request->get['supplier_quantity'])) 
                {
			$supplier_quantity = $this->request->get['supplier_quantity']; 
		} 
                else 
                {
			$supplier_quantity = 0;
		}
		if (isset($this->request->get['product_id'])) 
                {
			$product_id = $this->request->get['product_id']; 
		} 
                else 
                {
			$product_id = 0;
		}
		
		$this->adminmodel('procurement/purchase_order');
		
		$otp = rand(1000, 9999);

		$filter_data = array(
			'supplier_id'	     => $supplier_id,
                        'order_id'	     => $order_id,
                        'driver_mobile'	     => $driver_mobile,
                        'store_id'	     => $store_id,
						'supplier_quantity'	     => $supplier_quantity,
						'product_id'	     => $product_id,
						'otp'=>$otp
			
		);
        //print_r($filter_data);exit;
		$data['results'] = array();
		$log=new Log("supplier-".date('Y-m-d').".log");
		$log->write($filter_data);
		$results = $this->model_procurement_purchase_order->submit_order_by_supplier($filter_data);
		//SMS LIB
		$this->load->library('sms');	
		$sms=new sms($this->registry);
        		$sms->sendsms($driver_mobile,"10",$filter_data);

		$this->response->setOutput(json_encode('1'));
	}
            

}
?>