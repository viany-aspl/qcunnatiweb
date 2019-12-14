<?php

class ControllerPosDashboard extends Controller {
	private $error = array(); 
	
        private function getStoreId() {
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
			// get the default store id
			$this->load->model('setting/store');
			$stores = $this->model_setting_store->getStores();
			if (!empty($stores)) {
				$store_id = $stores[0]['store_id'];
			}
		}
		return $store_id;
	}
	
        public function index(){
            
            $this->currency->set($this->config->get('config_currency'));
            
            $this->load->model('pos/pos');
            $this->language->load('pos/pos');
            
            $this->document->setTitle($this->language->get('heading_title')); 
            
            //add metro css, js             
            $this->document->addScript('view/javascript/pos/fancybox/jquery.fancybox.pack.js');
            $this->document->addStyle('view/stylesheet/pos/style.css');
            $this->document->addStyle('view/javascript/pos/fancybox/jquery.fancybox.css');
                   
            $data['token'] = $this->session->data['token'];
            $rows = $this->model_pos_pos->getStatistics();
            $data['heading_title'] = $this->language->get('heading_title');
            $data['column_username'] = $this->language->get('column_username');
            $data['column_name'] = $this->language->get('column_name');
            $data['column_cash'] = $this->language->get('column_cash');
            $data['column_card'] = $this->language->get('column_card');
            $data['column_action'] = $this->language->get('column_action');
            $data['currency_left'] = $this->currency->getSymbolLeft();
            $data['currency_right'] = $this->currency->getSymbolRight();
            
            $data['rows'] = array();
             
            foreach ($rows as $row){                
                $row['total_cash'] = $this->currency->format($this->model_pos_pos->get_today_cash($row['user_id']));
                $row['total_card'] = $this->currency->format($this->model_pos_pos->get_today_card($row['user_id']));
                $row['cash'] = $this->currency->format($row['cash']);
                $row['card'] = $this->currency->format($row['card']);
                $data['rows'][] = $row;
            }

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

            $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('pos/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

            $data['header'] = $this->load->controller('common/header');
            $data['footer'] = $this->load->controller('common/footer');
            $data['column_left'] = $this->load->controller('common/column_left');
            
            $this->response->setOutput($this->load->view('pos/statistics.tpl', $data));
        }
        
        public function withdraw(){        
            
            //validation 
            if(empty($this->request->post['amount']) or $this->request->post['amount']==''){
                $json['error'] = 'Error: Please, enter the amount!';            
                echo json_encode($json);
                die();
            }            
            
            $data = array(
              'user_id' => $this->request->post['user_id'],
              'amount'  => $this->request->post['amount'], 
            );
            
            $this->load->model('pos/pos');
            
            $this->model_pos_pos->withdraw($data);
            
            $json['success'] = 'Success: amount withdrawed from selected user!';
            
            $this->response->setOutput(json_encode($json));
        }
        
        public function history(){
            
            if(isset($this->request->get['user_id'])){
                $user_id = $this->request->get['user_id'];
            }else{
                $user_id = 0;
            }
            
            if(isset($this->request->get['page'])){
                $page = $this->request->get['page'];
            }else{
                $page = 1;
            }
            
            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

            $data['breadcrumbs'][] = array(
                    'text' => 'Withdraw history',
                    'href' => $this->url->link('pos/dashboard/history&user_id='.$user_id, 'token=' . $this->session->data['token'], 'SSL')
            );
            
            $limit  =  $this->config->get('config_catalog_limit');
            
            if(!$limit) $limit = 15;
            
            $offset = ($page-1)*$limit;
            
            $this->load->model('pos/pos');
            $this->language->load('pos/pos');
            
            $this->currency->set($this->config->get('config_currency'));
            
            $this->document->setTitle($this->language->get('heading_title')); 
            
            $data['heading_title'] = $this->language->get('heading_title');
            $data['column_username'] = $this->language->get('column_username');
            $data['column_name'] = $this->language->get('column_name');
            $data['column_withdraw'] = $this->language->get('column_withdraw');
            $data['column_time'] = $this->language->get('column_time');
            
            $result = $this->model_pos_pos->history($user_id, $limit, $offset);
            
            foreach($result as $row){
                $row['amount'] = $this->currency->format($row['amount']);
                $data['rows'][] = $row;
            }
            
            $total = $this->model_pos_pos->total_history($user_id);
            
            $pagination = new Pagination();
            $pagination->total = $total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->text = $this->language->get('text_pagination');
            $pagination->url = $this->url->link('pos/pos/history&user_id='.$user_id, 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

            $data['pagination'] = $pagination->render();
            
            $data['header'] = $this->load->controller('common/header');
            $data['footer'] = $this->load->controller('common/footer');
            $data['column_left'] = $this->load->controller('common/column_left');
            
            $this->response->setOutput($this->load->view('pos/history.tpl', $data));            
        }
        
        public function orderHistory(){
            
            $limit = 15;
            $this->language->load('pos/pos');            
            $this->document->setTitle($this->language->get('heading_title')); 
            $data['heading_title'] = $this->language->get('heading_title');
            $data['token'] = $this->session->data['token'];
            
            $this->document->addScript('view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
            $this->document->addStyle('view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
            
            if(isset($this->request->get['user_id'])){
                $filter_user_id = $this->request->get['user_id'];
            }else{
                $filter_user_id = 0;
            }
            
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

            if (isset($this->request->get['filter_order_status_id'])) {
                    $filter_order_status_id = $this->request->get['filter_order_status_id'];
            } else {
                    $filter_order_status_id = null;
            }

            if (isset($this->request->get['filter_total'])) {
                    $filter_total = $this->request->get['filter_total'];
            } else {
                    $filter_total = null;
            }

            if (isset($this->request->get['filter_date_added'])) {
                    $filter_date_added = $this->request->get['filter_date_added'];
            } else {
                    $filter_date_added = null;
            }

            if (isset($this->request->get['filter_date_modified'])) {
                    $filter_date_modified = $this->request->get['filter_date_modified'];
            } else {
                    $filter_date_modified = null;
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

            $url = '';
            
            if (isset($this->request->get['filter_order_id'])) {
                    $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
            }

            
            if (isset($this->request->get['filter_order_id'])) {
                    $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_customer'])) {
                    $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_order_status_id'])) {
                    $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
            }

            if (isset($this->request->get['filter_total'])) {
                    $url .= '&filter_total=' . $this->request->get['filter_total'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                    $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['filter_date_modified'])) {
                    $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
            }

            if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
            }
            
            $filter_data = array(
                'filter_user_id'         => $filter_user_id,
                'filter_order_id'        => $filter_order_id,
                'filter_customer'	 => $filter_customer,
                'filter_order_status_id' => $filter_order_status_id,
                'filter_total'           => $filter_total,
                'filter_date_added'      => $filter_date_added,
                'filter_date_modified'   => $filter_date_modified,
                'sort'                   => $sort,
                'order'                  => $order,
                'start'                  => ($page - 1) * $limit,
                'limit'                  => $limit
            );
            
            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

            $data['breadcrumbs'][] = array(
                    'text' => 'Order history',
                    'href' => $this->url->link('pos/dashboard/orderHistory&user_id='.$filter_user_id, 'token=' . $this->session->data['token'], 'SSL')
            );
            
            $this->load->model('sale/order');
            
            $order_total = $this->model_sale_order->getTotalOrders($filter_data);
            
            $this->load->model('localisation/order_status');
            $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
            
            $rows = $this->model_sale_order->getOrders($filter_data);
            
            $data['rows'] = array();
            
            foreach ($rows as $row){
                $row['total'] = $this->currency->format($row['total']);
                $row['date_added'] = date($this->language->get('date_format_short'), strtotime($row['date_added']));
                $row['date_modified'] = date($this->language->get('date_format_short'), strtotime($row['date_modified']));
                $data['rows'][] = $row;
            }
            
            $data['text_missing'] = 'Missing Orders';
            $data['currency_code'] = $this->config->get('config_currency');
	    $data['currency_value'] = '1.0';
	    $data['store_id'] = $this->getStoreId();
	    $data['token'] = $this->session->data['token'];
                
            $pagination = new Pagination();
            $pagination->total = $order_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->text = $this->language->get('text_pagination');
            $pagination->url = $this->url->link('pos/dashboard/orderHistory', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

            $data['pagination'] = $pagination->render();

            $data['filter_order_id'] = $filter_order_id;
            $data['filter_customer'] = $filter_customer;
            $data['filter_order_status_id'] = $filter_order_status_id;
            $data['filter_total'] = $filter_total;
            $data['filter_date_added'] = $filter_date_added;
            $data['filter_date_modified'] = $filter_date_modified;
            
            $data['url_order_info'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'], 'SSL');
            
            
            $data['header'] = $this->load->controller('common/header');
            $data['footer'] = $this->load->controller('common/footer');
            $data['column_left'] = $this->load->controller('common/column_left');
            
            $this->response->setOutput($this->load->view('pos/order_history.tpl', $data));
            
        }
      
        public function getOrder(){

            $this->load->model('sale/order');

            $json = array();

            $this->load->library('customer');
            $this->customer = new Customer($this->registry);

            $this->load->library('tax');//
            $this->tax = new Tax($this->registry);

            $this->load->library('pos_cart');//
            $this->cart = new Pos_cart($this->registry);

            $this->load->model('catalog/product');

            $order_products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
            $this->cart->clear();
            foreach ($order_products as $order_product) {
                if (isset($order_product['order_option'])) {
                    $order_option = $order_product['order_option'];
                } elseif (isset($this->request->get['order_id'])) {
                    $order_option = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);
                } else {
                    $order_option = array();
                }

                $this->cart->add($order_product['product_id'], $order_product['quantity'], $order_option);
            }

               //html for cart
            $json['products'] = array();
                      
            foreach ($this->cart->getProducts() as $product) {

                    $option_data = array();

                    foreach ($product['option'] as $option) {
                            if ($option['type'] != 'file') {
                                    $value = $option['option_value'];	
                            } else {
                                    $filename = $this->encryption->decrypt($option['option_value']);

                                    $value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
                            }				

                            $option_data[] = array(								   
                                    'name'  => $option['name'],
                                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
                                    'type'  => $option['type']
                            );
                    }

                    // Display prices
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                            $price = $this->currency->format($product['price']);
                    } else {
                            $price = false;
                    }

                    // Display prices
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                            $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
                    } else {
                            $total = false;
                    }

                    $json['products'][] = array(
                            'key'       => $product['key'],
                            'name'      => $product['name'],
                            'model'     => $product['model'], 
                            'option'    => $option_data,
                            'quantity'  => $product['quantity'],
                            'price'     => $price,	
                            'total'     => $total,	
                            'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    );
            }//foreach product in cart generate html 

            // Totals
            $this->load->model('pos/extension');

            $total_data = array();					
            $total = 0;
            $taxes = $this->cart->getTaxes();

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $sort_order = array(); 

                    $results = $this->model_pos_extension->getExtensions('total');

                    foreach ($results as $key => $value) {
                            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                    }

                    array_multisort($sort_order, SORT_ASC, $results);

                    foreach ($results as $result) {
                            if ($this->config->get($result['code'] . '_status')) {
                                    $this->load->model('pos/' . $result['code']);

                                    $this->{'model_pos_' . $result['code']}->getTotal($total_data, $total, $taxes);
                            }

                            $sort_order = array(); 

                            foreach ($total_data as $key => $value) {
                                    $sort_order[$key] = $value['sort_order'];
                            }

                            array_multisort($sort_order, SORT_ASC, $total_data);			
                    }
            }

            $json['total_data'] = $total_data;
            $json['total'] = $this->currency->format($total);
            
            //customer info             
            $this->load->model('pos/pos');
            $json['customer'] = $this->model_pos_pos->getCustomer['customer_id'];
            $json['order_id'] = $this->request->get['order_id'];
            echo json_encode($json);
        }//get order         
}
?>