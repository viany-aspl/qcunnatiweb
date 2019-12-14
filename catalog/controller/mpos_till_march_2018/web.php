<?php

class ControllermposWeb extends Controller {
    
    private $debugIt = false;
   
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
    
    /*
	* Get products
	*/
	public function products() {
            $this->load->language('api/cart');
		 $mcrypt=new MCrypt();
//log to system table
				$this->load->model('account/activity');
				$activity_data = array(
					'customer_id' => "1",
					'data'        => $this->request->post
				);

				$this->model_account_activity->addActivity('webproducts', $activity_data);
                        $json = array();
			$log =new Log("webprd-".date('Y-m-d').".log");
			$log->write($this->request->get);
			$log->write($this->request->post);
		$this->load->model('catalog/product');
		$this->load->library('user');
		$log->write("data");
		$this->session->data['user_id']=$mcrypt->decrypt( $this->request->post['username']);
                $this->user = new User($this->registry);
		$log->write("data re");
		$log->write("data");
//get store id
$this->config->set('config_store_id','19');


		$json = array('success' => true, 'products' => array());



		/*check category id parameter*/
		if (isset($this->request->get['category'])) {
			$category_id =		 $mcrypt->decrypt( $this->request->get['category']);
		} else {
			$category_id = 0;
		}

		$products = $this->model_catalog_product->getProducts(array(
			'filter_category_id'        => $category_id
		));

		foreach ($products as $product) {
		$log->write($product);
    $log->write($product['price']);
    if(empty($product['price'])||$product['price']==0.0000)
    {
    		$product['price']=$product['sprice'];
    }
			$json['products'][] = array(
					'id'			=> ($product['product_id']),
					'name'			=> ($product['name']),
					'quantity'		=> ($product['quantity']),					
					'price'			=> ($this->currency->format($product['price'])),																				
					'tax'			=> (($this->tax->getTax($product['price'], $product['tax_class_id']))),
					'category'		=> $this->request->get['category'],
					'subsidy'		=> ( empty($product['subsidy'])? 0:$product['subsidy'])
			);
		}        		
			$this->response->setOutput(json_encode($json));
		
	}

}
