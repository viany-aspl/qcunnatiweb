<?php

class ControllermposProduct extends Controller {
    
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
	* Get Categories
	*/
    
    public function Categories(){
            $this->language->load('api/cart');
                $json = array();

                    
                    $this-> adminmodel('pos/pos');
                    $this-> adminmodel('setting/store');
                    $this-> adminmodel('tool/image');

                    //$this->load->model('pos/pos');
                    //get categories 
                $categories = $this->model_pos_pos->getTopCategories();
		 $mcrypt=new MCrypt();
		$json['categories'] = array();
		
		foreach ($categories as $category_info) {
                    $json['categories'][] = array(
                        'category_id' => $mcrypt->encrypt($category_info['category_id']),
                        'image'       =>$mcrypt->encrypt( $category_info['image']?$this->model_tool_image->resize($category_info['image'], 70, 70):'view/image/pos/logo.png'),
                        'name'        =>$mcrypt->encrypt( $category_info['name']),
                    );
		}

                $balance = $this->model_pos_pos->get_user_balance("1");//$this->user->getId());

                $json['cash'] =$mcrypt->encrypt( $this->currency->format($balance['cash']));
                $json['card'] =$mcrypt->encrypt( $this->currency->format($balance['card']));

                $json['hold_carts'] =$mcrypt->encrypt("");// $this->model_pos_pos->get_hold_cart_list_user("1");

                $json['storename']=$mcrypt->encrypt( $this->model_setting_store->getStore("2")["name"]);//$this->session->data['api_store_id'])["name"];
		$json['storeadd']= $mcrypt->encrypt($this->model_setting_store->getStore("2")["name"]);//$this->config->get('config_address');
                //load template 

                    
                    
                    
                    if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}        
    }


    /*
	* Get products
	*/
	public function products() {
            $this->load->language('api/cart');
                $json = array();
/*		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}
                else*/
                    {
		$this->load->model('catalog/product');
	
		$json = array('success' => true, 'products' => array());
		 $mcrypt=new MCrypt();


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

			if ($product['image']) {
				$image = $product['image'];
			} else {
				$image = false;
			}

			if ((float)$product['special']) {
				$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}

			$json['products'][] = array(
					'id'			=> $mcrypt->encrypt($product['product_id']),
					'name'			=> $mcrypt->encrypt($product['name']),
					'description'	=> $mcrypt->encrypt($product['description']),
					'pirce'			=> $mcrypt->encrypt($this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')))),
					'href'			=> $mcrypt->encrypt($this->url->link('product/product', 'product_id=' . $product['product_id'])),
					'thumb'			=> $mcrypt->encrypt($image),
					'special'		=> $mcrypt->encrypt($special),
					'rating'		=> $mcrypt->encrypt($product['rating']),
					'tax'			=> $mcrypt->encrypt(round($this->tax->getTax($product['price'], $product['tax_class_id']),2, PHP_ROUND_HALF_UP))
			);
		}
        }
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
}
