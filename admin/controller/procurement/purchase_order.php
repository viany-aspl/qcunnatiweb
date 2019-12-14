<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerProcurementPurchaseOrder extends Controller{
    
    public function index() {
                $this->load->language('report/Inventory_report');
		$this->document->setTitle("Purchase Order List");
                $data['column_left'] = $this->load->controller('common/column_left');
                $data['footer'] = $this->load->controller('common/footer');
                $data['header'] = $this->load->controller('common/header');
                        
                $user_info = $this->model_user_user->getUser($this->user->getId());
                $user_group_id=$user_info['user_group_id'];
                $data['user_id']=$user_info['user_id'];
		if (!empty($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
                if (isset($this->request->get['filter_id'])) {
			$filter_id = $this->request->get['filter_id'];
		} 
                else 
                {
			$filter_id = null;
		}
                if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} 
                else 
                {
			$filter_status = null;
		}
                
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start =  date('Y-m').'-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end =date('Y-m-d');
		}
              	if ($this->request->get['filter_store']!="") {
			$filter_store= $this->request->get['filter_store'];
		}

		$url = '';
              
                if ($this->request->get['filter_id']!="") {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
                if ($this->request->get['filter_status']!="") {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
                if ($this->request->get['filter_date_start']!="") {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
                if ($this->request->get['filter_date_end']!="") {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if ($this->request->get['filter_store']!="") {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => 'Home',
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Purchase Orders',
			'href' => $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
                        'filter_id'           => $filter_id,
                        'filter_status'           => $filter_status,
                        'filter_store'         =>$filter_store,
                        'user_id'         =>$data['user_id'],
                        'sort'                   => $sort,
                        'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
                $this->load->model('procurement/purchase_order');
		$results = $this->model_procurement_purchase_order->getList($filter_data);
		foreach ($results as $result) { //print_r($result);exit;
                                if($result["receive_bit"]=="1")
                                {
                                $order_status='Completed';
                                }
                                if($result["receive_bit"]=="0")
                                {
                                    $order_status="Pending";
                                }
                                if($result['canceled_message']!='')
                                {
                                    $order_status="Canceled";
                                }
			         $data['order_list'][] = array(
                                'id' => $result['id'],
				'order_date' => $result['order_date'],
                                'firstname'      => $result['firstname'],
                                'lastname'      => $result['lastname'],
				'pre_supplier_bit'   => $result['pre_supplier_bit'],
				'first_name'      => $result['first_name'],
				'last_name'     => $result['last_name'],
                                'store_name'     => $result['store_name'],
                                'creditlimit'     => $result['creditlimit'],
                                'currentcredit'     => $result['currentcredit'],
                                'order_sup_send'     => $result['order_sup_send'],
                                'order_status'     => $order_status,
                                'order_status_id'     => $result['order_status_id'],
				'product' =>$result['product'],
				'quantity' =>$result['quantity'],
			'driver_otp' => $result['driver_otp'],
				'view'  =>$this->url->link('procurement/purchase_order/view_order_details', 'token=' . $this->session->data['token'], 'SSL'),
                                'receive'  =>$this->url->link('procurement/account/receive_order', 'token=' . $this->session->data['token'], 'SSL')
			);
		}
                //$data['receive'] = $this->url->link('purchase/purchase_order/receive_order', 'token=' . $this->session->data['token'] . $url, true);
                //print_r($data['order_list']);
                $order_total = $this->model_procurement_purchase_order->getTotalOrders($filter_data);
		//$data['order_statuses'] = $this->model_procurement_purchase_order->getStatuses($user_group_id);
		
                $data['heading_title'] = 'Purchase Orders';
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
		$url = '';

		
                if ($this->request->get['filter_id']!="") {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
                if ($this->request->get['filter_status']!="") {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
                if ($this->request->get['filter_date_start']!="") {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
                	if ($this->request->get['filter_date_end']!="") {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if ($this->request->get['filter_store']!="") {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		} else {
			
		}
                
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
                	$data['filter_id'] = $filter_id;
                	$data['filter_status'] = $filter_status;
                	$data['filter_store'] =$filter_store;	
		
		$this->load->model('setting/store');		
		$data['stores']=$this->model_setting_store->getWarehouses();
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('procurement/purchase_order_list.tpl', $data));
	}
    /*----------------------------view_order_details function starts here------------*/
	
	public function view_order_details()
	{
                $this->document->setTitle("Purchase Order Details");
		$order_id = $this->request->get['order_id'];
                $data['order_id']=$order_id;
		$data['column_left'] = $this->load->controller('common/column_left');
		/*$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');*/
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$url = '';
			
			if ($this->request->get['filter_id']!="") {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
                if ($this->request->get['filter_status']!="") {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
                if ($this->request->get['filter_date_start']!="") {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
                if ($this->request->get['filter_date_end']!="") {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                if ($this->request->get['order_id']!="") {
			$url .= '&order_id=' . $this->request->get['order_id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
		$user_info = $this->model_user_user->getUser($this->user->getId());
                $data['user_id']=$user_info['user_id'];
                $data['user_group_id']=$user_info['user_group_id'];
                if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
                        unset($this->session->data['warning']);
		} else {
			
		}
                
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			
		}
		$this->load->model('procurement/purchase_order');
		$data['order_information'] = $this->model_procurement_purchase_order->view_order_details($order_id);
		
                $data['store_information'] = $this->model_procurement_purchase_order->view_store_details($data['order_information']['products'][0]['store_id']);
                
                //print_r($data['order_information']);
                //echo data['store_information']['name'];
                $data['token'] = $this->session->data['token'];
                $data['cancel'] = $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
		$data['pdf_export'] = $this->url->link('procurement/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, true);
		if(isset($_GET['export']))
		{
			
			$data['company_name'] = $data['store_information']['name'];//$this->config->get('config_name'); // store name
			$data['company_title'] = $this->config->get('config_title'); // store title
			$data['company_owner'] = $data['order_information']['order_info']['firstname']." ".$data['order_information']['order_info']['lastname'];//$this->config->get('config_owner'); // store owner name
			$data['company_email'] = $this->config->get('config_email'); // store email
			$data['company_address'] = $this->config->get('config_address');//store address
				
			$html = $this->load->view('procurement/print_order.tpl',$data);
			
			//$base_url = $this->config->get('config_url');

			$base_url = HTTP_CATALOG;
			
			//new mPDF($mode, $format, $font_size, $font, $margin_left, $margin_right, $margin_top, $margin_bottom, $margin_header, $margin_footer, $orientation);
			
			$mpdf = new mPDF('c','A4','','' , 5 , 5 , 25 , 10 , 5 , 7); 
			
			$header = '<div class="header"><div class="logo"><img src="'.$base_url.'image/catalog/logo.png" /></div><div class="company"><h3>'.$data['store_information']['name'].'</h3></div></div><hr />';
 
			$mpdf->SetHTMLHeader($header, 'O', false);
				
			$footer = '<div class="footer"><div class="address"><b>Adress: </b>'.$data['company_address'].'</div><div class="pageno">{PAGENO}</div></div>';
				
			$mpdf->SetHTMLFooter($footer);
			
			//$mpdf->setFooter('{PAGENO}');
				 
			$mpdf->SetDisplayMode('fullpage');
 
			$mpdf->list_indent_first_level = 0;
 
			$mpdf->WriteHTML($html);
			
			$mpdf->Output();
		}
		else
		{
			$this->response->setOutput($this->load->view('procurement/view_order.tpl',$data));
		}
	}
	
	/*----------------------------view_order_details function ends here--------------*/
    
    /*
	public function index()
	{ 
                        $this->document->setTitle("Purchase Order List");
                        $data['column_left'] = $this->load->controller('common/column_left');
                        $data['footer'] = $this->load->controller('common/footer');
                        $data['header'] = $this->load->controller('common/header');
                        
                        $user_info = $this->model_user_user->getUser($this->user->getId());
                        $user_group_id=$user_info['user_group_id'];
                
			
                        $data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                        );
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
                        );

		$data['add'] = $this->url->link('purchase/purchase_order/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('purchase/purchase_order/delete', 'token=' . $this->session->data['token'] . $url, true);
		$data['filter'] = $this->url->link('purchase/purchase_order/filter', 'token=' . $this->session->data['token'] . $url, true);
		
		
		$this->load->model('purchase/purchase_order');
                
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
                if (isset($this->request->get['filter_id'])) {
			$filter_id = $this->request->get['filter_id'];
		} 
                else 
                {
			$filter_id = null;
		}
                if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} 
                else 
                {
			$filter_status = null;
		}
                
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start =  date('Y-m').'-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end =date('Y-m-d');
		}
              
                
		$filter_data = array(
			
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
                        'filter_id'           => $filter_id,
                        'filter_status'           => $filter_status,
                        'user_group_id'         =>$user_group_id,
                        'sort'                   => $sort,
                        'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
                $data['order_list'] = $this->model_purchase_purchase_order->getList($filter_data);
		
		$total_orders = $this->model_purchase_purchase_order->getTotalOrders($filter_data);
		$data['order_statuses'] = $this->model_purchase_purchase_order->getStatuses($user_group_id);
		//getting total orders
		$data['view'] = $this->url->link('purchase/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, true);
		$data['receive'] = $this->url->link('purchase/purchase_order/receive_order', 'token=' . $this->session->data['token'] . $url, true);
		
		
		
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
                
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
                $data['filter_id'] = $filter_id;
                $data['filter_status'] = $filter_status;
                $data['user_group_id'] =$user_group_id;	
		$data['my_custom_text'] = "This is purchase order page.";
		$this->response->setOutput($this->load->view('purchase/purchase_order_list.tpl', $data));
	}
	*/
	public function add()
	{
		$data['column_left'] = $this->load->controller('common/column_left');
		/*$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');*/
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['breadcrumbs'] = array();
		$data['token'] = $this->session->data['token'];
		$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}

			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			
			$data['action'] = $this->url->link('procurement/purchase_order/insert_purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
		$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
		/*For loading the products from database*/
		$this->load->model('catalog/product');
		$products = array();
		$products = $this->model_catalog_product->getProducts($products);
		$i = 0;
		foreach($products as $product)
		{
			$products[$i] = $product['name'];
			$product_ids[$i] = $product['product_id'];
			$i++;
		}
		$data['products'] = $products;
		$data['product_ids'] = $product_ids;
		
		/*For getting the products from database*/
		
		//for loading the attribute groups
		
		$this->load->model('catalog/attribute_group');
		$attribute_groups = $this->model_catalog_attribute_group->getAttributeGroups();
		$data['attribute_groups'] = $attribute_groups;
		$data['form_bit'] = 1;
		//print_r($attribute_groups);
		//exit;
		//for loading the attribute groups
		
		/*getting the product options from database*/
		
		$this->load->model('catalog/option');
		$data['options'] = $this->model_catalog_option->getOptions();
		//print_r($data['options']);
		//exit;
		
		/*getting the product options from database*/
		$this->load->model('procurement/supplier');
		$data['suppliers'] = $this->model_procurement_supplier->get_total_suppliers();
                
                //stores
                $this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();                
		$this->response->setOutput($this->load->view('procurement/purchase_order_form.tpl', $data));
	}
	
	/*--------------------load attributes function starts here---------------------*/
	
	/*---------------------Delete Function starts here-----------------------------*/
	
	public function delete()
	{
		$ids = $this->request->post['selected'];
		$this->load->model('procurement/purchase_order');
		$deleted = $this->model_procurement_purchase_order->delete($ids);
		if($deleted)
		{
			$_SESSION['delete_success_message'] = "The order successfully deleted";
			$this->response->redirect($this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
		}
		else
		{
			$_SESSION['delete_unsuccess_message'] = "Sorry!! something went wrong";
			$this->response->redirect($this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
		}
	}
	
	/*---------------------Delete Function ends here-----------------------------*/
	
	public function loadAttributes()
	{
		$product_id = $_GET['product_id'];
		$this->load->model('catalog/product');

		$attributes = $this->model_catalog_product->getProductAttributes($product_id);
		$attribute_ids = array();
		$i=0;
		foreach($attributes as $attribute)
		{
			$attributes[$i] = $attribute['product_attribute_description'][1]['text'];
			$attribute_ids[$i] = $attribute['attribute_id'];
			$i++;
		}
		$data['attributes'] = $attributes;
		$data['attribute_ids'] = $attribute_ids;
		if($attributes)
		{
			echo json_encode($data);
		}
		else
		{
			echo "0";
		}
		
	}
	
	/*--------------------load attributes function ends here-----------------------*/
	
	////////////////////////////////////////////////////////////////////////////////////
	
	/*-------------------Loading the related attributes under specific attribute group----------*/
	
	public function getRelatedAttributes()
	{
		//echo $_GET['attribute_group_id'];
		$this->load->model('catalog/attribute');
		$attributes = $this->model_catalog_attribute->getAttributes(array('filter_attribute_group_id' => $_GET['attribute_group_id']));
		$attribute_ids = array();
		$i = 0;
		foreach($attributes as $attribute)
		{
			$attributes[$i] = $attribute['name'];
			$attribute_ids[$i] = $attribute['attribute_id'];
			$i++;
		}
		$data['attributes'] = $attributes;
		$data['attribute_ids'] = $attribute_ids;
		if($attributes)
		{
			echo json_encode($data);
		}
		else
		{
			echo "0";
		}
	}
	
	/*-------------------Loading the related attributes under specific attribute group----------*/
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	
	/*------------------------loading related option values funcstion starts here--------------*/
	
	public function getRelatedOptionValues()
	{
		$option = explode('_',$_GET['option_id']);
		$option_id = $option[0];
		//echo $option_id;
		$this->load->model('catalog/option');
		$option_values = $this->model_catalog_option->getOptionValues($option_id);
		$option_value_ids = array();
		//print_r($option_values);
		$i = 0;
		foreach($option_values as $option_value)
		{
			$option_values[$i] = $option_value['name'];
			$option_value_ids[$i] = $option_value['option_value_id'];
			$i++;
		}
		$data['option_values'] = $option_values;
		$data['option_value_ids'] = $option_value_ids;
		if($option_values)
		{
			echo json_encode($data);
		}
		else
		{
			echo "0";
		}
		
	}
	
	/*------------------------loading related option values function ends here-----------------*/
	
	/*--------------------Insert Purchase Order starts heres-------------------------------------------------*/
	
	public function insert_purchase_order()
	{
		$data['products'] = $_POST['product'];
		$data['options'] = $_POST['options'];
		$data['option_values'] = $_POST['option_values'];
		$data['quantity'] = $_POST['quantity'];
		$data['supplier_id'] = $_POST['supplier_id'];
		$data['stores'] = $_POST['stores'];
		$data['column_left'] = $this->load->controller('common/column_left');
		/*$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');*/
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['breadcrumbs'] = array();
		$data['token'] = $this->session->data['token'];
		
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
		
		if((in_array("--products--",$data['products'])) || (in_array("--stores--",$data['storess'])) || (in_array("--Product Options--",$data['options'])) || (in_array("--Option Values--",$data['option_values'])) || (count($data['quantity']) != count(array_filter($data['quantity']))) || (count($data['options']) != count(array_filter($data['options']))) || (count($data['products']) != count(array_filter($data['products']))) || (count($data['option_values']) != count(array_filter($data['option_values']))))
		{
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
			$data['action'] = $this->url->link('procurement/purchase_order/insert_purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$this->load->model('procurement/supplier');
			$data['suppliers'] = $this->model_procurement_supplier->get_total_suppliers();
		                //stores
                $this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();  
			$this->load->model('catalog/option');
			$data['options'] = $this->model_catalog_option->getOptions();
			$this->response->setOutput($this->load->view('procurement/purchase_order_form.tpl', $data));
			//$this->response->redirect($this->url->link('purchase/purchase_order/add', 'token=' . $this->session->data['token'] . $url, true));
		}
		else
		{
			$i = 0;
			foreach($data['products'] as $product)
			{
				$product_names[$i] = explode('_',$product);
				$i++;
			}
			$data['products'] = $product_names;
			//stores
                        $i = 0;
			foreach($data['stores'] as $store)
			{
				$store_names[$i] = explode('_',$store);
				$i++;
			}
			$data['stores'] = $store_names;
                        
                        
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
			
			$data['option_values'] = $option_values;
			
			$this->load->model('procurement/purchase_order');
			$order_id = $this->model_procurement_purchase_order->insert_purchase_order($data);
			
			
			
			if(isset($this->request->post['mail_bit']))
			{
                               
				$data['company_name'] = $this->config->get('config_name'); // store name
				$data['company_title'] = $this->config->get('config_title'); // store title
				$data['company_owner'] = $this->config->get('config_owner'); // store owner name
				$data['company_email'] = $this->config->get('config_email'); // store email
				$data['company_address'] = $this->config->get('config_address');//store address
				
				$this->load->model('procurement/purchase_order');
				$data['order_information'] = $this->model_procurement_purchase_order->view_order_details($order_id);
		
				
				$html = $this->load->view('procurement/mail_purchase_order.tpl',$data);
				
				$base_url = HTTP_CATALOG;
				
				$mpdf = new mPDF('c','A4','','' , 5 , 5 , 25 , 10 , 5 , 7); 
				
				$header = '<div class="header"><div class="logo"><img src="'.$base_url.'image/catalog/logo.png" /></div><div class="company"><h3>'.$data['company_name'].'</h3></div></div><hr />';
	 
				$mpdf->SetHTMLHeader($header, 'O', false);
					
				$footer = '<div class="footer"><div class="address"><b>Adress: </b>'.$data['company_address'].'</div><div class="pageno">{PAGENO}</div></div>';
					
				$mpdf->SetHTMLFooter($footer);
					 
				$mpdf->SetDisplayMode('fullpage');
	 
				$mpdf->list_indent_first_level = 0;
	 
				$mpdf->WriteHTML($html);
				
				$mpdf->Output('../orders/order.pdf','F');
				
				//mailing
				
				$mail             = new PHPMailer();

				$body = "<p>Akshamaala Solution Pvt. Ltd.</p>";
				
				$mail->IsSMTP(); 
				$mail->Host       = "mail.akshamaala.in"; 
														   
				$mail->SMTPAuth   = false;                 
				$mail->SMTPSecure = "";                 
				$mail->Host       = "mail.akshamaala.in";      
				$mail->Port       = 25;                  
				$mail->Username   = "mis@akshamaala.in";  
				$mail->Password   = "mismis";            

				$mail->SetFrom($data['company_email'], $data['company_name']);

				$mail->AddReplyTo($data['company_email'],$data['company_name']);

				$mail->Subject    = "Product Order to Supplier";

				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

				$mail->MsgHTML($body);
				
				//to get the email of supplier
				$query = $this->db->query('SELECT email FROM oc_po_supplier WHERE id = ' .$data['supplier_id']);
				
				$address = $query->row['email'];
				
				$mail->AddAddress($address, $address);
				
				$file_to_attach = '../orders/order.pdf';

				$mail->AddAttachment($file_to_attach);
				
				if(!$mail->Send()) 
				{
				  echo "Mailer Error: " . $mail->ErrorInfo;
				} 
				else
				{
				  if(!unlink($file_to_attach))
				  {
					  echo ("Error deleting $file_to_attach");
				  }
				  else
				  {
					 echo ("Deleted $file_to_attach");
				  }
				}
			}
			
			if($order_id)
			{
				$_SESSION['success_order_message'] = "The Order has been added";
				$this->response->redirect($this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
			}
		}
	}
	
	/*--------------------Insert purchase order ends here----------------------------*/
	
	///////////////////////////////////////////////////////////////////////////////////
	
	
	
	
	/*-----------------------------Filter function starts here------------------------*/
	
	public function filter()
	{
		
			$data['column_left'] = $this->load->controller('common/column_left');
			/*$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');*/
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$url = '';

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$this->load->model('procurement/purchase_order');
		$total_orders = $this->model_procurement_purchase_order->getTotalOrders();
		/*$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();*/
		
		$start = ($page-1)*20;
		$limit = 20;
		
		if(!empty($_POST))
		{
			$_SESSION['post'] = $_POST;
			$post = $_SESSION['post'];
		}
		else
		{
			$post = $_SESSION['post'];
		}
		
		if(count(array_filter($post,'strlen')) != 0)
		{
			$filter = array_filter($post,'strlen');
			
			$data['order_list'] = $this->model_procurement_purchase_order->filter($filter,$start,$limit);
			if(!$data['order_list'])
			{
				$data['order_list'] = $this->model_procurement_purchase_order->getList($start,$limit);
				$_SESSION['nothing_found_error'] = "Sorry! no data matches your query,try another";
			}
			else
			{
				$data['filter_id'] = $post['filter_id'];
				$data['status'] = $post['status'];
				$data['from'] = $post['from'];
				$data['to'] = $post['to'];
				
				$total_orders = $this->model_procurement_purchase_order->filterCount($filter);
			}
		}
		else
		{
			$data['order_list'] = $this->model_procurement_purchase_order->getList($start,$limit);
		}
		
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('procurement/purchase_order/filter', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
				
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
		$data['view'] = $this->url->link('procurement/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, true);
		$data['filter'] = $this->url->link('procurement/purchase_order/filter', 'token=' . $this->session->data['token'] . $url, true);
		$data['receive'] = $this->url->link('procurement/purchase_order/receive_order', 'token=' . $this->session->data['token'] . $url, true);
		$data['add'] = $this->url->link('procurement/purchase_order/add', 'token=' . $this->session->data['token'] . $url, true);
		$this->response->setOutput($this->load->view('procurement/purchase_order_list.tpl', $data));
	}
	
	/*-----------------------------Filter function ends here--------------------------*/
	
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('procurement/purchase_order');
			

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_name,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_procurement_purchase_order->getProducts($filter_data);

			foreach ($results as $result) { //print_r($result);
				
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['model'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
                                        'hstn'=>$result['hstn'],
                                        'price'      => round($result['price'],PHP_ROUND_HALF_UP) ,
                                        'product_tax_type'=>$result['product_tax_type'],
                                        'price_wo_t'=>round($result['price_wo_t'],PHP_ROUND_HALF_UP),
                                        'product_tax_rate'=>round($result['product_tax_rate'],PHP_ROUND_HALF_UP)
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        public function download_invoice()
        {
             $data['column_left'] = $this->load->controller('common/column_left');
             $data['footer'] = $this->load->controller('common/footer');
             $data['header'] = $this->load->controller('common/header');
                
             $order_id = $this->request->get['order_id'];
             $this->load->model('procurement/purchase_order');
             $data['order_information'] = $this->model_procurement_purchase_order->view_order_details_for_created_invoice($order_id); 
             //print_r($data['order_information']['order_info']['po_invoice_n']);
             $data['created_po']=$data['order_information']['order_info']['po_invoice_n'];
             $data['order_id']=$order_id;
             //$this->response->setOutput($this->load->view('purchase/order_invoice_print.tpl',$data));
             
             
              $html=$this->load->view('procurement/order_invoice_print.tpl',$data);
             require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             $mpdf = new mPDF('c','A4','','' , 5 , 5 , 25 , 10 , 5 , 7);
                
                $header = '<div class="header">
		<div class="logo">
		<img style="float: right;margin-right: 40px;height: 30px;" src="'.$base_url.'image/catalog/logo.png"  />
		</div>
 		
		<img src="../image/letterhead_topline.png" style="height: 10px; margin-left: -16px;width: 120%;" /> 
	</div>';
    
                $mpdf->SetHTMLHeader($header, 'O', false);
                    
                $footer = '<div class="footer">
                        
                        <img src="../image/letterhead_bottomline.png" style="height: 10px; margin-left: -40px;width: 150% !important;" /> 
                        <div class="address"><img src="../image/letterhead_footertext.png" style="margin-left: -40px;width: 120% !important;" /> </div>'
                        . '</div>';

                       	 
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
    
                $mpdf->WriteHTML($html);
                
                $filename='Invoice_'.$order_id.'.pdf';
                
                $mpdf->Output($filename,'D');
                
        }
	public function invoice()
	{
                $this->document->setTitle("Purchase Order Invoice");
		$order_id = $this->request->get['order_id'];
		$data['column_left'] = $this->load->controller('common/column_left');
                $this->load->model('procurement/purchase_order');
                if($this->request->server['REQUEST_METHOD'] == 'POST')
                {
                    //print_r($this->request->post);
                    $url="&order_id=".$order_id;
                    $this->model_procurement_purchase_order->submit_po_invoice($this->request->post);
                    $this->response->redirect($this->url->link('procurement/purchase_order/invoice', 'token=' . $this->session->data['token'] . $url, true));
                }
                
                
                $data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
                $data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
		$data['token']=$this->session->data['token'];
                $data['cancel'] = $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
                
                $created_po=$this->model_procurement_purchase_order->check_po_invoice($order_id);
                if($created_po>0)
                {
                   $data['order_information'] = $this->model_procurement_purchase_order->view_order_details_for_created_invoice($order_id); 
                   $data['created_po']=$created_po;
                }
		else
                {
                   $data['order_information'] = $this->model_procurement_purchase_order->view_order_details_invoice($order_id); 
                   $data['created_po']='';
                   
                }
		$data['order_id']=$order_id;
		//print_r($data['order_information']);
		
		
		$this->response->setOutput($this->load->view('procurement/order_invoice.tpl',$data));
		
	}
	
	/*----------------------------view_order_details function ends here--------------*/
	
	
	
}

?>