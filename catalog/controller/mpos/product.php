<?php

class ControllermposProduct extends Controller {

    private $debugIt = false;

    public function adminmodel($model) {

        $admin_dir = DIR_SYSTEM;
        $admin_dir = str_replace('system/', 'admin/', $admin_dir);
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

    //storemenu

    /* public function storemenu()
      {
      $log=new Log("getmenu-".date('Y-m-d').".log");
      $mcrypt=new MCrypt();
      $this->language->load('api/cart');

      $json = array();

      $this-> adminmodel('catalog/storemenu');

      $this-> adminmodel('setting/store');

      $this-> adminmodel('tool/image');
      $log->write($this->request->post);
      //$this->load->model('pos/pos');

      //get categories
      $log->write($mcrypt->decrypt($this->request->post['store_id']));

      $categories = $this->model_catalog_storemenu->getCategories(array(
      'filter_parent'=> '1','filter_store'=>$mcrypt->decrypt($this->request->post['store_id']),
      'filter_role'=>$mcrypt->decrypt($this->request->post['rid']),
      'user_id' => $mcrypt->decrypt($this->request->post['username'])
      ));



      $json['navigation'] = array();


      foreach ($categories as $category_info)
      {

      $json['navigation'][] = array(

      'id' => ($category_info['category_id']),

      'name'        =>( $category_info['name']),

      'original_id' =>($category_info['category_id'])	,

      'image'	=>($category_info['image'])	,

      'tab'	=>($category_info['meta_title'])	,

      'mob'	=>($category_info['meta_keyword'])	,

      'children'   => $this->model_catalog_storemenu->getChildCategories($category_info['category_id'],$mcrypt->decrypt($this->request->post['store_id']),$mcrypt->decrypt($this->request->post['rid']),$mcrypt->decrypt($this->request->post['username']))

      );

      }



      if ($this->debugIt)
      {

      echo '<pre>';

      print_r($json);

      echo '</pre>';

      }

      else
      {

      $this->response->addHeader('Content-Type: application/json');

      $this->response->setOutput(json_encode($json,JSON_UNESCAPED_UNICODE));

      }

      } */
    public function storemenu() {
        $log = new Log("getmenu-" . date('Y-m-d') . ".log");
        $mcrypt = new MCrypt();
        $this->language->load('api/cart');

        $json = array();

        $this->adminmodel('catalog/storemenu');

        $this->adminmodel('setting/store');
        $this->adminmodel('user/user_group');

        $this->adminmodel('tool/image');
        $log->write($this->request->post);
        //$this->load->model('pos/pos');
        //get categories
        $log->write($mcrypt->decrypt($this->request->post['store_id']));
        $log->write("group access");
        $categories_group = $this->model_user_user_group->getUserGroup($mcrypt->decrypt($this->request->post['rid']));
        $log->write($categories_group);
        $log->write("mobile permission");
        $log->write($categories_group['permission']['mobile']);
        $cid_access = array();
        foreach ($categories_group['permission']['mobile'] as $cids) {
            $cid_access[] = explode("-", $cids)[1];
        }
        $log->write($cid_access);

        $categories = $this->model_catalog_storemenu->getCategories(array(
            'filter_parent' => '1', 'filter_store' => $mcrypt->decrypt($this->request->post['store_id']),
            'filter_role' => $mcrypt->decrypt($this->request->post['rid']),
            'user_id' => $mcrypt->decrypt($this->request->post['username'])
        ));



        $json['navigation'] = array();


        foreach ($categories as $category_info) {
            $log->write($category_info['category_id']);
            if (in_array($category_info['category_id'], $cid_access)) {

                $child = $this->model_catalog_storemenu->getChildCategories($category_info['category_id'], $mcrypt->decrypt($this->request->post['store_id']), $mcrypt->decrypt($this->request->post['rid']), $mcrypt->decrypt($this->request->post['username']));
                $log->write("child data");
                $log->write($child);
                $cid_child_access = array();
                foreach ($child as $ccids) {
                    $log->write("child loop data");
                    $log->write($ccids['id']);
                    foreach ($categories_group['permission']['mobile']['child' . $ccids['id']] as $cc_cids) {
                        $log->write("child loop inner data");
                        $log->write($cc_cids);
                        $cid_child_access[] = $ccids;
                    }
                }

                $log->write("child loop final data");
                $log->write($cid_child_access);

                $json['navigation'][] = array(
                    'id' => ($category_info['category_id']),
                    'name' => ( $category_info['name']),
                    'original_id' => ($category_info['category_id']),
                    'image' => ($category_info['image']),
                    'tab' => ($category_info['meta_title']),
                    'mob' => ($category_info['meta_keyword']),
                    'children' => $cid_child_access
                );
            }
        }



        if ($this->debugIt) {

            echo '<pre>';

            print_r($json);

            echo '</pre>';
        } else {

            $this->response->addHeader('Content-Type: application/json');

            $this->response->setOutput(json_encode($json, JSON_UNESCAPED_UNICODE));
        }
    }

//end storemenu






    public function sproductsinv() {
        $log = new Log("prdsearch-" . date('Y-m-d') . ".log");
        $mcrypt = new MCrypt();

        //log to system table
        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $mcrypt->decrypt($this->request->post['username']),
            'data' => $this->request->post
        );

        //$this->model_account_activity->addActivity('serachinventory', $activity_data);
        $this->adminmodel('pos/pos');
        $this->load->library('user');
        $log->write("data");
        $this->session->data['user_id'] = $mcrypt->decrypt($this->request->post['username']);
        $this->user = new User($this->registry);

        $json = array('success' => true, 'products' => array());
        if (isset($this->request->get['q'])) {
            $q = $mcrypt->decrypt($this->request->get['q']);
        } else {
            $q = '';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $limit = 20;
        $offset = ($page - 1) * $limit;

        $log->write("products" . $q);
        $products = $this->model_pos_pos->searchProductsStore($q, $limit, $offset);
        $log->write($products);
        $log->write(sizeof($products));
        foreach ($products as $product) {

            if ($product['image']) {
                $image = $product['image'];
            } else {
                $image = false;
            }

            if ((float) $product['special']) {
                $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $special = false;
            }

            if ($product['store_price'] == '0.0000') {
                $product['price'] = $product['price'];
            } else {
                $product['price'] = $product['store_price'];
            }


            $json['products'][] = array(
                'id' => $mcrypt->encrypt($product['product_id']),
                'name' => $mcrypt->encrypt($product['name']),
                'quantity' => $mcrypt->encrypt($product['quantity']),
                'description' => $mcrypt->encrypt($product['description']),
                'pirce' => $mcrypt->encrypt($this->currency->format(round($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))) + round($this->tax->getTax($product['price'], $product['tax_class_id'])))),
                'href' => $mcrypt->encrypt($this->url->link('product/product', 'product_id=' . $product['product_id'])),
                'thumb' => $mcrypt->encrypt($image),
                'special' => $mcrypt->encrypt($special),
                'rating' => $mcrypt->encrypt($product['rating']),
                'tax' => $mcrypt->encrypt(round($this->tax->getTax($product['price'], $product['tax_class_id'])))
            );
        }
        $json['total'] = $mcrypt->encrypt("0");
        $json['listcount'] = $mcrypt->encrypt(sizeof($products));
        return $this->response->setOutput(json_encode($json));
    }

    public function getstorecr() {
        $json = array();
        $log = new Log("storecr.log");
        $log->write($this->request->post);
        $mcrypt = new MCrypt();

        //log to system table
        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $mcrypt->decrypt($this->request->post['username']),
            'data' => json_encode($this->request->post),
        );

        //$this->model_account_activity->addActivity('getStoreCR', $activity_data);
        $this->adminmodel('pos/pos');
        $this->request->post['store_id'] = $mcrypt->decrypt($this->request->post['store_id']);
        $json['hold_cr'] = $mcrypt->encrypt("Live"); //$this->model_pos_pos->get_store_balance($this->request->post['store_id']));
        return $this->response->setOutput(json_encode($json));
    }

    /*
     * Get Categories
     */

    public function Categories() {
        $this->language->load('api/cart');
        $json = array();
        $log = new Log("category-" . date('Y-m-d') . ".log");
        $log->write($this->request->post);
        $mcrypt = new MCrypt();

        //log to system table
        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $mcrypt->decrypt($this->request->post['username']),
            'data' => json_encode($this->request->post),
        );

        //$this->model_account_activity->addActivity('Categories', $activity_data);

        $this->adminmodel('pos/pos');
        $this->adminmodel('setting/store');
        $this->adminmodel('tool/image');
        $this->request->post['store_id'] = $mcrypt->decrypt($this->request->post['store_id']);
        $log->write($this->request->post);
        $this->config->set('config_store_id', $this->request->post['store_id']);
        //$this->load->model('pos/pos');
        if (isset($this->request->post['store_id']) && isset($this->request->post['store_emp'])) {

            $categories = $this->model_pos_pos->getTopStoreCategories('19');
        } else if (isset($this->request->post['store_id'])) {
            $log->write("in if");
            //get categories 
            $categories = $this->model_pos_pos->getTopStoreCategories($this->request->post['store_id']);
        } else {
            $log->write("in else");
            //get categories 
            $categories = $this->model_pos_pos->getTopCategories();
        }

        $log->write($categories);

        $json['categories'] = array();

        foreach ($categories as $category_info) {
            $json['categories'][] = array(
                'category_id' => $mcrypt->encrypt($category_info['category_id']),
                'image' => $mcrypt->encrypt($category_info['image'] ? $this->model_tool_image->resize($category_info['image'], 70, 70) : 'view/image/pos/logo.png'),
                'name' => $mcrypt->encrypt($category_info['name']),
            );
        }
        $this->session->data['user_id'] = $mcrypt->decrypt($this->request->post['username']);

        $this->load->library('user');
        $this->user = new User($this->registry);
        $balance = $this->model_pos_pos->get_user_balance($this->user->getId());

        $json['cash'] = $mcrypt->encrypt($this->currency->format($balance['cash']));
        $json['card'] = $mcrypt->encrypt($this->currency->format($balance['card']));

        $json['hold_carts'] = $mcrypt->encrypt(""); // $this->model_pos_pos->get_hold_cart_list_user("1");
        $json['hold_cr'] = $mcrypt->encrypt("Live"); //$this->model_pos_pos->get_store_balance($this->request->post['store_id']));
        $json['systype'] = $mcrypt->encrypt("System");
        $json['headoffice'] = $mcrypt->encrypt($this->config->get('config_head_office'));
        $json['storename'] = $mcrypt->encrypt($this->model_setting_store->getStore(( $this->request->post['store_id']))["name"]); //$this->session->data['api_store_id'])["name"];
        $json['storeaddress'] = $mcrypt->encrypt($this->config->get('config_address')); //$this->config->get('config_address');
        $json['geocode'] = $mcrypt->encrypt($this->config->get('config_geocode'));
        $json['storestatus'] = $mcrypt->encrypt($this->config->get('config_storestatus'));
        $json['storecin'] = $mcrypt->encrypt($this->config->get('config_cin'));
        $json['storetin'] = $mcrypt->encrypt($this->config->get('config_tin'));
        $json['storegst'] = $mcrypt->encrypt($this->config->get('config_gstn'));
        $json['storemsmfid'] = $mcrypt->encrypt($this->config->get('config_MSMFID'));
        $json['storetype'] = $mcrypt->encrypt($this->model_setting_store->getstoretype($this->config->get('config_storetype')));
        $json['storetypeid'] = $mcrypt->encrypt($this->config->get('config_storetype'));
        $json['printer_status'] = ($this->config->get('config_printer'));
        $log->write($this->config->get('config_gstn'));
        $log->write($json);
        //load template                                                             
        if ($this->debugIt) {
            echo '<pre>';
            print_r($json);
            echo '</pre>';
        } else {
            $this->response->setOutput(json_encode($json));
        }
    }

    /* farmer  data */

    public function CategoriesFarmer() {
        $this->language->load('api/cart');
        $json = array();


        $this->adminmodel('pos/pos');
        $this->adminmodel('setting/store');
        $this->adminmodel('tool/image');

        //$this->load->model('pos/pos');
        //get categories 
        $categories = $this->model_pos_pos->getTopCategories();
        $mcrypt = new MCrypt();
        $json['navigation'] = array();

        /* foreach ($categories as $category_info) {
          $json['navigation'][] = array(
          'id' => ($category_info['category_id']),
          'name'        =>( $category_info['name']),
          'original_id' =>($category_info['category_id'])	,
          'children'   =>array( array('id'=>($category_info['category_id']),
          'type'=>'category',
          'name'=>'ALL',
          'original_id' =>($category_info['category_id'])

          ))
          );
          } */


        $datasub = array();
        foreach ($categories as $category_info) {
            $datasub[] = array('id' => ($category_info['category_id']),
                'type' => 'category',
                'name' => (empty($category_info['meta_hindi']) ? $category_info['name'] : $category_info['meta_hindi']),
                'original_id' => ($category_info['category_id'])
            );
        }
        $json['navigation'][] = array(
            'id' => ("1"),
            'name' => ("इनपुट"),
            'original_id' => ("1"),
            'children' => ($datasub )
        );


        if ($this->debugIt) {
            echo '<pre>';
            print_r($json);
            echo '</pre>';
        } else {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json, JSON_UNESCAPED_UNICODE));
        }
    }

    /*
     * Get products
     */

    public function products() {
        $this->load->language('api/cart');
        $mcrypt = new MCrypt();
        //log to system table
        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $mcrypt->decrypt($this->request->post['username']),
            'data' => $this->request->post
        );

        //$this->model_account_activity->addActivity('products', $activity_data);

        $json = array();
        /* 		
          if (!isset($this->session->data['api_id']))
          {
          $json['error']['warning'] = $this->language->get('error_permission');
          }
          else */ {
            $log = new Log("prd-store-listing " . date('Y-m-d') . ".log");
            $log->write('products called in mpos/product');
            $log->write($this->request->get);
            $log->write($this->request->post);
            
            $log->write($mcrypt->decrypt($this->request->post['username']));

            $this->load->model('catalog/product');
            $this->load->library('user');
            $log->write("data");
            $this->session->data['user_id'] = $mcrypt->decrypt($this->request->post['username']);
            $this->user = new User($this->registry);
            $log->write("data re");
            //$log->write("data");

            if (isset($this->request->post['stype']) && isset($this->request->post['store_emp'])) {
                $this->config->set('config_store_id', '19');
            } else if (isset($this->request->post['stype'])) {
                //get indent
                $this->config->set('config_store_id', $mcrypt->decrypt($this->request->post['stype'])); //$this->request->post['stype']));
                $log->write("in stype");
            } else {

                //get store id
                $this->config->set('config_store_id', $mcrypt->decrypt($this->request->post['store_id']));
            }
            $json = array('success' => true, 'products' => array());



            /* check category id parameter */
            if (isset($this->request->get['category'])) {
                $category_id = $mcrypt->decrypt($this->request->get['category']);
            } else {
                $category_id = 0;
            }
            $servicetype = null;
            if (isset($this->request->post['servicetype'])) {
                $servicetype = $mcrypt->decrypt($this->request->post['servicetype']);
            }
            $products = $this->model_catalog_product->getProducts(array(
                'filter_category_id' => $category_id,
                'servicetype' => $servicetype,
                'uid' => $mcrypt->decrypt($this->request->post['username'])
            ));
            if ($this->request->post['store_id'] == 18) {
                //$log->write($products);
            }
            //$log->write($products);
            foreach ($products as $product) {
                $linked_products = array();
                $log->write($product);

                if ($product['image']) {
                    $image = $product['image'];
                } else {
                    $image = false;
                }

                if ((float) $product['special']) {
                    $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }
                //$log->write($product['price']);
                if (empty($product['sprice']) || $product['sprice'] == 0.0000) {
                    $product['price'] = $product['price'];
                } else {
                    $product['price'] = $product['sprice'];
                }
                if (empty($product['HSTN'])) {
                    $product['HSTN'] = "0000";
                }
                ////$mcrypt->encrypt($this->currency->format(round($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))) )),
                //$product['description']
                $log->write('servicetype');
                $log->write($servicetype);
                if (!empty($servicetype)) {
                    $log->write('servicetype is ' . $servicetype . ' so quantity will be updated to cquantity');
                    $log->write('quantity is ' . $product['quantity']);
                    $log->write('cquantity is ' . $product['cquantity']);
echo hi; exit;
                    $product['quantity'] = $product['cquantity'];
                    $log->write('update quantity is ' . $product['quantity']);
                }
                if ($servicetype == 'subuserreturn') {
                    if ($product['quantity'] > 0) {
                        $json['products'][] = array(
                            'id' => $mcrypt->encrypt($product['product_id']),
                            'name' => $mcrypt->encrypt($product['name']),
                            'hstn' => $mcrypt->encrypt($product['HSTN']),
                            'quantity' => $mcrypt->encrypt($product['quantity']),
                            'description' => $mcrypt->encrypt("0"),
                            'pirce' => $mcrypt->encrypt($this->currency->format($product['price'])),
                            'href' => $mcrypt->encrypt($this->url->link('product/product', 'product_id=' . $product['product_id'])),
                            'thumb' => $mcrypt->encrypt($image),
                            'special' => $mcrypt->encrypt($special),
                            'rating' => $mcrypt->encrypt($product['rating']),
                            'tax' => $mcrypt->encrypt(($this->tax->getTax($product['price'], $product['tax_class_id']))),
                            'per_tax' => $mcrypt->encrypt(json_encode($this->tax->getRates($product['price'], $product['tax_class_id']))),
                            'category' => $this->request->get['category'],
                            'subsidy' => $mcrypt->encrypt(empty($product['subsidy']) ? 0 : $product['subsidy']),
                            'combo' => $mcrypt->encrypt(empty($product['combo']) ? 0 : $product['combo']),
                            'linked_products' => $mcrypt->encrypt(json_encode(array("products" => $linked_products))),
                            'packsize' => $mcrypt->encrypt(empty($product['isbn']) ? 1 : $product['isbn']),
                            'multipliefactor' => $mcrypt->encrypt(empty($product['mpn']) ? 1 : $product['mpn']),
                        );
                    }
                } else {
                    $json['products'][] = array(
                        'id' => $mcrypt->encrypt($product['product_id']),
                        'name' => $mcrypt->encrypt($product['name']),
                        'hstn' => $mcrypt->encrypt($product['HSTN']),
                        'quantity' => $mcrypt->encrypt($product['quantity']),
                        'description' => $mcrypt->encrypt("0"),
                        'pirce' => $mcrypt->encrypt($this->currency->format($product['price'])),
                        'href' => $mcrypt->encrypt($this->url->link('product/product', 'product_id=' . $product['product_id'])),
                        'thumb' => $mcrypt->encrypt($image),
                        'special' => $mcrypt->encrypt($special),
                        'rating' => $mcrypt->encrypt($product['rating']),
                        'tax' => $mcrypt->encrypt(($this->tax->getTax($product['price'], $product['tax_class_id']))),
                        'per_tax' => $mcrypt->encrypt(json_encode($this->tax->getRates($product['price'], $product['tax_class_id']))),
                        'category' => $this->request->get['category'],
                        'subsidy' => $mcrypt->encrypt(empty($product['subsidy']) ? 0 : $product['subsidy']),
                        'combo' => $mcrypt->encrypt(empty($product['combo']) ? 0 : $product['combo']),
                        'linked_products' => $mcrypt->encrypt(json_encode(array("products" => $linked_products))),
                        'packsize' => $mcrypt->encrypt(empty($product['isbn']) ? 1 : $product['isbn']),
                       // 'packsize' =>empty($product['isbn']) ? 1 : $product['isbn'],
                        'multipliefactor' => $mcrypt->encrypt(empty($product['mpn']) ? 1 : $product['mpn']),
                    );
                }
                $log->write($product . ' && Quantity :  ' . $product['quantity']);
            }//////////foreach end here
        }///////////extra bracket of else end here
        if ($this->debugIt) {
            echo '<pre>';
            print_r($json);
            echo '</pre>';
        } else {
            $log->write($json);
            $this->response->setOutput(json_encode($json));
        }
    }

/////////////////products function end here



    public function invproducts() {
        $this->load->language('api/cart');
        $json = array();

        $mcrypt = new MCrypt();
        {
            $log = new Log("prdinv-" . date('Y-m-d') . ".log");

            $log->write($this->request->get);
            $log->write($this->request->post);


//log to system table
            $this->load->model('account/activity');
            $activity_data = array(
                'customer_id' => $mcrypt->decrypt($this->request->post['username']),
                'data' => $this->request->post
            );

            //$this->model_account_activity->addActivity('inventory products', $activity_data);

            $this->load->model('catalog/product');
            $this->load->library('user');
            $log->write("data");
            $this->session->data['user_id'] = $mcrypt->decrypt($this->request->post['username']);
            $this->user = new User($this->registry);
            $log->write("data re");
            $log->write("data");

//get store id
            $this->config->set('config_store_id', $mcrypt->decrypt($this->request->post['store_id']));
            $json = array('success' => true, 'products' => array());



            $json['listcount'] = $mcrypt->encrypt($this->model_catalog_product->getTotalQntyProducts(array()));

            $products = $this->model_catalog_product->getProducts(array(
                'start' => $mcrypt->decrypt($this->request->post['start']),
                'limit' => $mcrypt->decrypt($this->request->post['limit'])
            ));


            $json['total'] = $mcrypt->encrypt(round($this->model_catalog_product->getTotalInventoryAmount($mcrypt->decrypt($this->request->post['store_id']))));

            foreach ($products as $product) {
                $log->write($product);


                if ($product['image']) {
                    $image = $product['image'];
                } else {
                    $image = false;
                }

                if ((float) $product['special']) {
                    $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                $log->write($product['price']);
                if (empty($product['price']) || $product['price'] == 0.0000) {
                    $product['price'] = $product['sprice'];
                }
                if (empty($product['quantity'])) {
                    $pquantity = 0;
                } else {
                    $pquantity = $product['quantity'];
                }
                $json['products'][] = array(
                    'id' => $mcrypt->encrypt($product['product_id']),
                    'name' => $mcrypt->encrypt($product['name']),
                    'quantity' => $mcrypt->encrypt($pquantity),
                    'fquantity' => $mcrypt->encrypt($product['fquantity']),
                    'description' => $mcrypt->encrypt($product['description']),
                    'pirce' => $mcrypt->encrypt($this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) + ($this->tax->getTax($product['price'], $product['tax_class_id'])))),
                    'href' => $mcrypt->encrypt($this->url->link('product/product', 'product_id=' . $product['product_id'])),
                    'thumb' => $mcrypt->encrypt($image),
                    'special' => $mcrypt->encrypt($special),
                    'rating' => $mcrypt->encrypt($product['rating']),
                    'tax' => $mcrypt->encrypt(($this->tax->getTax($product['price'], $product['tax_class_id'])))
                );
                //'pirce'			=> $mcrypt->encrypt( $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))+($this->tax->getTax($product['price'], $product['tax_class_id'])))),
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

    public function productdetail() {
        $log = new Log("prd-detail-" . date('Y-m-d') . ".log");


        $mcrypt = new MCrypt();
//log to system table
        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $mcrypt->decrypt($this->request->post['username']),
            'data' => $this->request->post
        );

        //$this->model_account_activity->addActivity('product detail', $activity_data);

        if (isset($this->request->get['product_id'])) {
            $product_id = (int) $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }
        $log->write($product_id);
        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($product_id);


//check


        if ($product_info) {
            $url = '';
            $this->load->model('catalog/review');
            //$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
            $data['description'] = $product_info['description'];

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $data['price'] = ($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $data['price'] = false;
            }

            $data['price_formatted'] = ($this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))));
            $data['id'] = (int) $this->request->get['product_id'];
            $data['remote_id'] = (int) $this->request->get['product_id'];
            $data['brand'] = $product_info['manufacturer'];
            $data['category'] = $this->request->get['category_id'];
            $data['discount_price'] = "0";
            $data['discount_price_formated'] = '0';
            $data['currency'] = 'INR';
            $data['code'] = '1';
            $data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
            $data['name'] = (empty($product_info['meta_hindi']) ? $product_info['name'] : $product_info['meta_hindi']);
            $data['reward'] = $product_info['reward'];
            $data['points'] = $product_info['points'];

            if ($product_info['quantity'] <= 0) {
                $data['stock'] = $product_info['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $data['stock'] = $product_info['quantity'];
            } else {
                $data['stock'] = $this->language->get('text_instock');
            }

            $this->load->model('tool/image');

            if ($product_info['image']) {
                $data['url'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            } else {
                $data['url'] = '';
            }

            if ($product_info['image']) {
                $data['main_image'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else {
                $data['main_image'] = '';
            }


            $data['main_image_high_res'] = $data['main_image'];
            $data['images'] = array();



            $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

            foreach ($results as $result) {
                $data['images'][] = array(
                    $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                    $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
                );
            }





            $datasize = array('id' => "1", "remote_id" => "1", value => "1");
            array_push($datasize, array('id' => "2", "remote_id" => "2", value => "2"));
            $data['variants'][] = array(
                'id' => "1",
                'color' => array('id' => "1", "remote_id" => "1", value => "1", code => "1", img => "1"),
                'size' => $datasize,
                'images' => $data['images'],
                'code' => "1",
                'related' => array()
            );
        }

//end check


        $this->response->setOutput(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    public function productsfarmer() {

        $this->load->language('api/cart');
        $json = array();
        /* 		if (!isset($this->session->data['api_id'])) {
          $json['error']['warning'] = $this->language->get('error_permission');
          }
          else */ {
            $log = new Log("prd.log");
            $log->write($this->request->get);
            $log->write($this->request->post);
            $mcrypt = new MCrypt();
            $this->load->model('catalog/product');
//		$this->load->library('user');
            $log->write("data");
//		$this->session->data['user_id']=( $this->request->post['username']);
            //              $this->user = new User($this->registry);
            $log->write("data re");
            $log->write("data");

//get store id
            $this->config->set('config_store_id', '0'); //( $this->request->post['store_id']));
            $json = array('metadata' => array());



            /* check category id parameter */
            if (isset($this->request->get['category'])) {
                $category_id = ( $this->request->get['category']);
            } else {
                $category_id = 0;
            }

            if (isset($this->request->get['search'])) {
                $filter_name = ( $this->request->get['search']);
            } else {
                $filter_name = '';
            }
            $products = $this->model_catalog_product->getProducts(array(
                'filter_category_id' => $category_id,
                'filter_name' => $filter_name
            ));



            foreach ($products as $product) {

                if ($product['image']) {
                    $image = $product['image'];
                } else {
                    $image = false;
                }

                if ((float) $product['special']) {
                    $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }
                $json['metadata']['links'] = array('first' => '1', 'last' => '1', 'next' => '1', 'prev' => '1', 'self' => '1');
                $json['metadata']['sorting'] = "";
                $json['metadata']['records_count'] = "3";
//records ['metadata'] ($this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))))
                $json['records'][] = array(
                    'id' => ($product['product_id']),
                    'remote_id' => ($product['product_id']),
                    'name' => (empty($product['meta_hindi']) ? $product['name'] : $product['meta_hindi']),
                    'description' => ($product['description']),
                    'price' => $product['price'],
                    'price_formatted' => ($this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')))),
                    'category' => $category_id,
                    'brand' => 'Unnati',
                    'discount_price' => '11',
                    'discount_price_formated' => '11',
                    'currency' => 'INR',
                    'code' => '1',
                    'url' => ($this->url->link('product/product', 'product_id=' . $product['product_id'])),
                    'main_image' => "https://unnati.world/shop/image/" . ($image),
                    'main_image_high_res' => "https://unnati.world/shop/image/" . ($image),
                    'images' => array(),
                    'variants' => array(),
                    'special' => ($special),
                    'rating' => ($product['rating']),
                    'tax' => (round($this->tax->getTax($product['price'], $product['tax_class_id']), 2, PHP_ROUND_HALF_UP))
                );
            }
        }
        if ($this->debugIt) {
            echo '<pre>';
            print_r($json);
            echo '</pre>';
        } else {
            //$this->response->setOutput(json_encode($json));
            $this->response->setOutput(json_encode($json, JSON_UNESCAPED_UNICODE));
        }
    }

//circle
//circle inventory
    public function invwebloanproducts() {
        $this->load->language('api/cart');
        $json = array();

        $mcrypt = new MCrypt();
        {
            $log = new Log("loanprdinv.log");

            $log->write($this->request->get);
            $log->write($this->request->post);


//log to system table
            $this->load->model('account/activity');
            $activity_data = array(
                'customer_id' => $mcrypt->decrypt($this->request->post['username']),
                'data' => $this->request->post
            );

            //$this->model_account_activity->addActivity('Loan inventory', $activity_data);

            $this->load->model('catalog/product');
            $this->load->library('user');
            $log->write("data");
            $this->session->data['user_id'] = $mcrypt->decrypt($this->request->post['username']);
            $this->user = new User($this->registry);
            $log->write("data re");
            $log->write("data");

            //get store id
            $this->config->set('config_store_id', $mcrypt->decrypt($this->request->post['store_id']));
            $json = array('success' => true, 'products' => array());




            $products = $this->model_catalog_product->getloanProducts(array(
                'start' => $mcrypt->decrypt($this->request->post['start']),
                'limit' => $mcrypt->decrypt($this->request->post['limit']),
                'user' => $mcrypt->decrypt($this->request->post['username'])
            ));
            $json['listcount'] = $mcrypt->encrypt(sizeof($products));

            $log->write($products);
            $json['total'] = $mcrypt->encrypt(round($this->model_catalog_product->getTotalLoanInventoryAmount($mcrypt->decrypt($this->request->post['store_id']))));

            foreach ($products as $product) {



                $log->write($product);


                $json['products'][] = array(
                    'id' => ($product[0]['product_id']),
                    'name' => ($product[0]['name']),
                    'quantity' => ($product[0]['quantity']),
                    'price' => (str_replace("Rs.", "", $product[0]['price']) + str_replace("Rs.", "", $product[0]['tax']) ),
                    'tax' => ($product[0]['tax'])
                );
            }
        }
        if ($this->debugIt) {
            echo '<pre>';
            print_r($json);
            echo '</pre>';
        } else {
            $log->write($json);
            $this->response->setOutput(json_encode($json));
        }
    }

//circle inventory
    public function invloanproducts() {
        $this->load->language('api/cart');
        $json = array();

        $mcrypt = new MCrypt();
        {
            $log = new Log("loanprdinv-" . date('Y-m-d') . ".log");

            $log->write($this->request->get);
            $log->write($this->request->post);
            $log->write($mcrypt->decrypt($this->request->post['username']));
            $log->write($mcrypt->decrypt($this->request->post['circle_code']));



//log to system table
            $this->load->model('account/activity');
            $activity_data = array(
                'customer_id' => $mcrypt->decrypt($this->request->post['username']),
                'data' => $this->request->post
            );

            //$this->model_account_activity->addActivity('Loan inventory', $activity_data);

            $this->load->model('catalog/product');
            $this->load->library('user');
            $log->write("data");
            $this->session->data['user_id'] = $mcrypt->decrypt($this->request->post['username']);
            $this->user = new User($this->registry);
            $log->write("data re");
            $log->write("data");

            //get store id
            $this->config->set('config_store_id', $mcrypt->decrypt($this->request->post['store_id']));
            $json = array('success' => true, 'products' => array());




            $products = $this->model_catalog_product->getloanProducts(array(
                'start' => $mcrypt->decrypt($this->request->post['start']),
                'limit' => $mcrypt->decrypt($this->request->post['limit']),
                'user' => $mcrypt->decrypt($this->request->post['circle_code'])
            ));
            $json['listcount'] = $mcrypt->encrypt(sizeof($products));

            $log->write($products);
            $json['total'] = $mcrypt->encrypt(round($this->model_catalog_product->getTotalLoanInventoryAmount($mcrypt->decrypt($this->request->post['store_id']))));

            foreach ($products as $product) {



                $log->write($product);


                $json['products'][] = array(
                    'id' => $mcrypt->encrypt($product[0]['product_id']),
                    'name' => $mcrypt->encrypt($product[0]['name']),
                    'quantity' => $mcrypt->encrypt($product[0]['quantity']),
                    'pirce' => $mcrypt->encrypt(str_replace("Rs.", "", $product[0]['price']) + str_replace("Rs.", "", $product[0]['tax'])),
                    'tax' => $mcrypt->encrypt($product[0]['tax'])
                );
            }
        }
        if ($this->debugIt) {
            echo '<pre>';
            print_r($json);
            echo '</pre>';
        } else {
            $log->write($json);
            $this->response->setOutput(json_encode($json));
        }
    }

    public function productsrelatedtocrop() {

        $this->load->language('api/cart');
        $json = array(); {
            $log = new Log("prdgen-" . date('Y-m-d') . ".log");
            $log->write($this->request->get);
            $log->write($this->request->post);
            $mcrypt = new MCrypt();
            $this->adminmodel('catalog/product');
            //		$this->load->library('user');
            $log->write("data");
            //		$this->session->data['user_id']=( $this->request->post['username']);
            //              $this->user = new User($this->registry);

            $log->write("data");

            //get store id
            $this->config->set('config_store_id', '0'); //( $this->request->post['store_id']));
            $json = array('metadata' => array());

            $log->write($this->request->get);

            /* check category id parameter */
            if (isset($this->request->get['crop_id'])) {
                $crop_id = ( $this->request->get['crop_id']);
            } else {
                $crop_id = 0;
            }
            if (isset($this->request->get['category'])) {
                $category_id = ( $this->request->get['category']);
            } else {
                $category_id = 0;
            }
            if (!isset($this->request->get['search'])) {

                $log->write("in catgory");

                if ($crop_id == 'true') {
                    $log->write('if crop_id is true');
                    $products = $this->model_catalog_product->getProductsByCategoryId($category_id);
                } else {
                    $log->write('if crop_id is not true');
                    $products = $this->model_catalog_product->getProductsRelatedToCrop(array(
                        'filter_crop_id' => $crop_id ///'12'  // 
                    ));
                }
            } else {
                $log->write("in serach");
                //search product by name filter_name
                $products = $this->model_catalog_product->getProductsRelatedToCrop(array(
                    'filter_name' => $this->request->get['search'],
                    'filter_crop_id' => $crop_id ///'12'  // 
                ));
            }
            //echo "here";
            $count_pr = count($products);
            foreach ($products as $product) {

                if ($product['image']) {
                    $image = $product['image'];
                } else {
                    $image = false;
                }

                if ((float) $product['special']) {
                    $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }
                $json['metadata']['links'] = array('first' => ($this->url->link('product/product', 'product_id=' . $product['product_id'])), 'last' => ($this->url->link('product/product', 'product_id=' . $product['product_id'])), 'next' => ($this->url->link('product/product', 'product_id=' . $product['product_id'])), 'prev' => ($this->url->link('product/product', 'product_id=' . $product['product_id'])), 'self' => ($this->url->link('product/product', 'product_id=' . $product['product_id'])));
                $json['metadata']['sorting'] = "";
                $json['metadata']['records_count'] = $count_pr;
                //records ['metadata'] ($this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))))
                $json['records'][] = array(
                    'id' => ($product['product_id']),
                    'remote_id' => ($product['product_id']),
                    'name' => (empty($product['meta_hindi']) ? $product['name'] : $product['meta_hindi']),
                    'description' => ($product['description']),
                    'price' => $product['price'],
                    'price_formatted' => ($this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')))),
                    'category' => $category_id,
                    'brand' => 'Unnati',
                    'discount_price' => '11',
                    'discount_price_formated' => '11',
                    'currency' => 'INR',
                    'code' => '1',
                    'url' => ($this->url->link('product/product', 'product_id=' . $product['product_id'])),
                    'main_image' => "https://unnati.world/shop/image/" . ($image),
                    'main_image_high_res' => "https://unnati.world/shop/image/" . ($image),
                    'images' => array(),
                    'variants' => array(),
                    'special' => ($special),
                    'rating' => ($product['rating']),
                    'tax' => (round($this->tax->getTax($product['price'], $product['tax_class_id']), 2, PHP_ROUND_HALF_UP))
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

    public function getsubsidycategory() {
        $json = array();
        $log = new Log("getsubsidycategory-" . date('Y-m-d') . ".log");
        $log->write($this->request->post);
        $mcrypt = new MCrypt();

        $filter_data = array(
            'store_id' => $mcrypt->decrypt($this->request->post['store_id'])
        );
        $this->adminmodel('subsidy/subsidy');

        $cat_data = $this->model_subsidy_subsidy->getsubsidycategory($filter_data);
        $log->write($cat_data);
        $data = array();
        foreach ($cat_data as $cat_dat) {
            $cat_product_data = $this->model_subsidy_subsidy->getsubsidycategory_products($filter_data, $cat_dat['category_id']);
            $product = array();
            foreach ($cat_product_data as $cat_product_da) {
                $product[] = array(
                    'product_id' => $mcrypt->encrypt($cat_product_da['product_id']),
                    'product_name' => $mcrypt->encrypt($cat_product_da['product_name']),
                    'subsidy' => $mcrypt->encrypt($cat_product_da['subsidy'])
                );
            }
            $data['category'][] = array(
                'category_id' => $mcrypt->encrypt($cat_dat['category_id']),
                'category_name' => $mcrypt->encrypt($cat_dat['category_name']),
                'product' => $product
            );
            //print_r($cat_dat);
        }
        //print_r($data);
        //$json['hold_cr'] =$mcrypt->encrypt($this->model_pos_pos->get_store_balance($this->request->post['store_id']));
        $log->write($data);
        return $this->response->setOutput(json_encode($data));
    }

    public function categories_with_product() {
        $this->language->load('api/cart');
        $json = array();
        $log = new Log("categories_with_product-" . date('y-m-d') . ".log");
        $log->write($this->request->post);
        $mcrypt = new MCrypt();

        $this->adminmodel('pos/pos');

        $this->adminmodel('tool/image');
        $this->adminmodel('catalog/product');
        $this->request->post['store_id'] = $mcrypt->decrypt($this->request->post['store_id']);
        $log->write($this->request->post);

        if (isset($this->request->post['store_id'])) {
            $categories = $this->model_pos_pos->getTopStoreCategories($this->request->post['store_id']);
        } else {
            $categories = $this->model_pos_pos->getTopStoreCategories('0');
        }
        $log->write($categories);

        $json['categories'] = array();

        foreach ($categories as $category_info) {
            $products_array = array();
            $products = $this->model_catalog_product->getProductsByCategoryId($category_info['category_id']);
            $log->write($products);
            foreach ($products as $product) {
                //print_r($product);

                if ($product['image']) {
                    $image = $product['image'];
                } else {
                    $image = false;
                }

                if ((float) $product['special']) {
                    $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }
                $log->write($product['price']);
                if (empty($product['price']) || $product['price'] == 0.0000) {
                    $product['price'] = $product['sprice'];
                }

                $products_array[] = array(
                    'id' => $mcrypt->encrypt($product['product_id']),
                    'name' => $mcrypt->encrypt($product['name']),
                    'quantity' => $mcrypt->encrypt($product['quantity']),
                    'description' => $mcrypt->encrypt("0"),
                    'pirce' => $mcrypt->encrypt($this->currency->format($product['price'])),
                    'href' => $mcrypt->encrypt($this->url->link('product/product', 'product_id=' . $product['product_id'])),
                    'thumb' => $mcrypt->encrypt($image),
                    'special' => $mcrypt->encrypt($special),
                    'rating' => $mcrypt->encrypt($product['rating']),
                    'tax' => $mcrypt->encrypt(($this->tax->getTax($product['price'], $product['tax_class_id']))),
                    'category' => $this->request->get['category'],
                    'subsidy' => $mcrypt->encrypt(empty($product['subsidy']) ? 0 : $product['subsidy'])
                );
            }

            $json['categories'][] = array(
                'category_id' => $mcrypt->encrypt($category_info['category_id']),
                'image' => $mcrypt->encrypt($category_info['image'] ? $this->model_tool_image->resize($category_info['image'], 70, 70) : 'view/image/pos/logo.png'),
                'name' => $mcrypt->encrypt($category_info['name']),
                'products' => $products_array
            );
        }

        $log->write($json);

        $this->response->setOutput(json_encode($json));
    }

    public function field_quantity() {
        $json = array();
        $log = new Log("field_quantity-" . date('Y-m-d') . ".log");
        $log->write($this->request->post);
        $mcrypt = new MCrypt();

        //log to system table
        $this->adminmodel('pos/pos');
        $json = array(
            'store_id' => $mcrypt->decrypt($this->request->post['store_id']),
            'product_id' => $mcrypt->decrypt($this->request->post['product_id']),
            'quantity' => $mcrypt->decrypt($this->request->post['quantity']),
            'username' => $mcrypt->decrypt($this->request->post['username']),
        );

        $up = $this->model_pos_pos->insert_product_to_store($json);
        $data = array();
        if ($up) {
            $up = 1;
        }
        $data['status'] = $up;
        $log->write($up);
        if ($up > 0) {

            $data['message'] = "Submit Successfully";
        } else {

            $data['message'] = "System Error !";
        }
        $log->write($data);
        $this->response->setOutput(json_encode($data));
    }

}
