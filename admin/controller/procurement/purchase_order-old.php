<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerPurchasePurchaseOrder extends Controller{
	public function index()
	{
		//set the title of the page
			$this->document->setTitle("Purchase Order List");
			
			$data['column_left'] = $this->load->controller('common/column_left');
			/*$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');*/
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
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
		/*getting the list of the orders*/
		
		$this->load->model('purchase/purchase_order');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$start = ($page-1)*20;
		$limit = 20;
		
		$data['order_list'] = $this->model_purchase_purchase_order->getList($start,$limit);
		/*getting the list of the orders*/
		
		//getting total orders
		
		$total_orders = $this->model_purchase_purchase_order->getTotalOrders();
		
		//getting total orders
		$data['view'] = $this->url->link('purchase/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, true);
		$data['receive'] = $this->url->link('purchase/purchase_order/receive_order', 'token=' . $this->session->data['token'] . $url, true);
		//getting pages

		
		//getting pages
		
		/*pagination*/
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));

		
		/*pagination*/
		$data['my_custom_text'] = "This is purchase order page.";
		$this->response->setOutput($this->load->view('purchase/purchase_order_list.tpl', $data));
	}
	
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
			
			$data['action'] = $this->url->link('purchase/purchase_order/insert_purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
		$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
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
		$this->load->model('purchase/supplier');
		$data['suppliers'] = $this->model_purchase_supplier->get_total_suppliers();
                
                //stores
                $this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();                
		$this->response->setOutput($this->load->view('purchase/purchase_order_form.tpl', $data));
	}
	
	/*--------------------load attributes function starts here---------------------*/
	
	/*---------------------Delete Function starts here-----------------------------*/
	
	public function delete()
	{
		$ids = $this->request->post['selected'];
		$this->load->model('purchase/purchase_order');
		$deleted = $this->model_purchase_purchase_order->delete($ids);
		if($deleted)
		{
			$_SESSION['delete_success_message'] = "The order successfully deleted";
			$this->response->redirect($this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
		}
		else
		{
			$_SESSION['delete_unsuccess_message'] = "Sorry!! something went wrong";
			$this->response->redirect($this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
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
			$data['action'] = $this->url->link('purchase/purchase_order/insert_purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$this->load->model('purchase/supplier');
			$data['suppliers'] = $this->model_purchase_supplier->get_total_suppliers();
		                //stores
                $this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();  
			$this->load->model('catalog/option');
			$data['options'] = $this->model_catalog_option->getOptions();
			$this->response->setOutput($this->load->view('purchase/purchase_order_form.tpl', $data));
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
			
			$this->load->model('purchase/purchase_order');
			$order_id = $this->model_purchase_purchase_order->insert_purchase_order($data);
			
			
			
			if(isset($this->request->post['mail_bit']))
			{
                               
				$data['company_name'] = $this->config->get('config_name'); // store name
				$data['company_title'] = $this->config->get('config_title'); // store title
				$data['company_owner'] = $this->config->get('config_owner'); // store owner name
				$data['company_email'] = $this->config->get('config_email'); // store email
				$data['company_address'] = $this->config->get('config_address');//store address
				
				$this->load->model('purchase/purchase_order');
				$data['order_information'] = $this->model_purchase_purchase_order->view_order_details($order_id);
		
				
				$html = $this->load->view('purchase/mail_purchase_order.tpl',$data);
				
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
				
				$mail->AddAddress($address, "Turaab Ali");
				
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
				$this->response->redirect($this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
			}
		}
	}
	
	/*--------------------Insert purchase order ends here----------------------------*/
	
	///////////////////////////////////////////////////////////////////////////////////
	
	/*----------------------------view_order_details function starts here------------*/
	
	public function view_order_details()
	{
		$order_id = $this->request->get['order_id'];
		$data['column_left'] = $this->load->controller('common/column_left');
		/*$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');*/
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$url = '';
			
			if (isset($this->request->get['order_id'])) {
				$url .= '&order_id=' . urlencode(html_entity_decode($this->request->get['order_id'], ENT_QUOTES, 'UTF-8'));
			}
			
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

			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
		
		$this->load->model('purchase/purchase_order');
		$data['order_information'] = $this->model_purchase_purchase_order->view_order_details($order_id);
		$data['cancel'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
		$data['pdf_export'] = $this->url->link('purchase/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, true);
		if(isset($_GET['export']))
		{
			
			$data['company_name'] = $this->config->get('config_name'); // store name
			$data['company_title'] = $this->config->get('config_title'); // store title
			$data['company_owner'] = $this->config->get('config_owner'); // store owner name
			$data['company_email'] = $this->config->get('config_email'); // store email
			$data['company_address'] = $this->config->get('config_address');//store address
				
			$html = $this->load->view('purchase/print_order.tpl',$data);
			
			//$base_url = $this->config->get('config_url');

			$base_url = HTTP_CATALOG;
			
			//new mPDF($mode, $format, $font_size, $font, $margin_left, $margin_right, $margin_top, $margin_bottom, $margin_header, $margin_footer, $orientation);
			
			$mpdf = new mPDF('c','A4','','' , 5 , 5 , 25 , 10 , 5 , 7); 
			
			$header = '<div class="header"><div class="logo"><img src="'.$base_url.'image/catalog/logo.png" /></div><div class="company"><h3>'.$data['company_name'].'</h3></div></div><hr />';
 
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
			$this->response->setOutput($this->load->view('purchase/view_order.tpl',$data));
		}
	}
	
	/*----------------------------view_order_details function ends here--------------*/
	
	
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
			'href' => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$this->load->model('purchase/purchase_order');
		$total_orders = $this->model_purchase_purchase_order->getTotalOrders();
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
			
			$data['order_list'] = $this->model_purchase_purchase_order->filter($filter,$start,$limit);
			if(!$data['order_list'])
			{
				$data['order_list'] = $this->model_purchase_purchase_order->getList($start,$limit);
				$_SESSION['nothing_found_error'] = "Sorry! no data matches your query,try another";
			}
			else
			{
				$data['filter_id'] = $post['filter_id'];
				$data['status'] = $post['status'];
				$data['from'] = $post['from'];
				$data['to'] = $post['to'];
				
				$total_orders = $this->model_purchase_purchase_order->filterCount($filter);
			}
		}
		else
		{
			$data['order_list'] = $this->model_purchase_purchase_order->getList($start,$limit);
		}
		
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchase/purchase_order/filter', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
				
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
		$data['view'] = $this->url->link('purchase/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, true);
		$data['filter'] = $this->url->link('purchase/purchase_order/filter', 'token=' . $this->session->data['token'] . $url, true);
		$data['receive'] = $this->url->link('purchase/purchase_order/receive_order', 'token=' . $this->session->data['token'] . $url, true);
		$data['add'] = $this->url->link('purchase/purchase_order/add', 'token=' . $this->session->data['token'] . $url, true);
		$this->response->setOutput($this->load->view('purchase/purchase_order_list.tpl', $data));
	}
	
	/*-----------------------------Filter function ends here--------------------------*/
	
	/*-----------------------------Receive order function starts here------------------*/
	
	public function receive_order()
	{
		$order_id = $this->request->get['order_id'];
		$data['order_id'] = $order_id;
		$data['column_left'] = $this->load->controller('common/column_left');
		/*$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');*/
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
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

			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
		
		$this->load->model('purchase/purchase_order');
		$data['order_information'] = $this->model_purchase_purchase_order->view_order_details($order_id);
		if($data['order_information']['order_info']['receive_bit']==1)
		{
			$data['receive_bit'] = $data['order_information']['order_info']['receive_bit'];
		}
		else
		{
			$data['ftime_bit'] = 1;
		}
		$data['action'] = $this->url->link('purchase/purchase_order/insert_receive_order', 'token=' . $this->session->data['token'] . $url, true);
		$data['cancel'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
		$this->load->model('purchase/supplier');
		$data['suppliers'] = $this->model_purchase_supplier->get_total_suppliers();
		$this->response->setOutput($this->load->view('purchase/receive_order.tpl',$data));
	
	}
	
	/*-----------------------------Receive order function ends here-----------------*/
	
	/*-----------------------------insert receive order function starts here-------------------*/
	
	public function insert_receive_order()
	{
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
		if((count($received_quantities) != count(array_filter($received_quantities))) || (count($prices) != count(array_filter($prices))) || $order_receive_date == '')
		{
			$_SESSION['empty_fields_error'] = 'Warning: Please check the form carefully for errors!';
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			
			$this->load->model('purchase/purchase_order');
			$data['order_information'] = $this->model_purchase_purchase_order->view_order_details($order_id);
			
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
			//echo $order_receive_date;
			$data['action'] = $this->url->link('purchase/purchase_order/insert_receive_order', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$this->load->model('purchase/supplier');
			$data['suppliers'] = $this->model_purchase_supplier->get_total_suppliers();
			//$this->response->redirect($this->url->link('purchase/purchase_order/receive_order', 'token=' . $this->session->data['token'] . $url, true));
			$this->response->setOutput($this->load->view('purchase/receive_order.tpl',$data));
		}
		else
		{
			if(isset($this->request->post['disable_bit']))
			{
				unset($received_order_info['suppliers_ids']);
			}
			$this->load->model('purchase/purchase_order');
			$inserted = $this->model_purchase_purchase_order->insert_receive_order($received_order_info,$order_id);
			if($inserted)
			{
				$_SESSION['receive_success_message'] = 'Order received Successfully!!';
				$this->response->redirect($this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
			}
			else
			{
				$_SESSION['something_wrong_message'] = 'Sorry!! something went wrong, try again';
				$this->response->redirect($this->url->link('purchase/purchase_order/insert_receive_order', 'token=' . $this->session->data['token'] . $url, true));
			}
				
		}
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/
	
}

?>