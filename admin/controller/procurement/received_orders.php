<?php
	//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
	class ControllerPurchaseReceivedOrders extends Controller {
		public function index() 
		{
			$this->load->language('purchase/received_orders');
			$this->load->model('purchase/received_orders');
			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			
			$url = '';
			
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('purchase/received_orders', 'token=' . $this->session->data['token'] . $url, true)
			);

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_list'] = $this->language->get('text_list');
			$data['text_no_results'] = $this->language->get('text_no_results');
			$data['text_confirm'] = $this->language->get('text_confirm');
			$data['text_all_status'] = $this->language->get('text_all_status');
			$data['entry_date_start'] = $this->language->get('entry_date_start');
			$data['entry_date_end'] = $this->language->get('entry_date_end');
			$data['entry_supplier'] = $this->language->get('entry_supplier');
			$data['entry_product'] = $this->language->get('entry_product');
			$data['entry_order_id'] = $this->language->get('entry_order_id');
			$data['button_filter'] = $this->language->get('button_filter');
			
			$data['column_order_id'] = $this->language->get('column_order_id');
			$data['column_date_start'] = $this->language->get('column_date_start');
			$data['column_date_end'] = $this->language->get('column_date_end');
			$data['column_supplier'] = $this->language->get('column_supplier');
			$data['column_product'] = $this->language->get('column_product');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total_products'] = $this->language->get('column_total_products');
			$data['column_total'] = $this->language->get('column_total');
			$data['grand_total_text'] = $this->language->get('grand_total');
			
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			
			$data['token'] = $this->session->data['token'];
			$this->load->model('purchase/received_orders');
			
			$data['filter'] = $this->url->link('purchase/received_orders/filter', 'token=' . $this->session->data['token'] . $url, true);
			$data['pdf_export'] = $this->url->link('purchase/received_orders', 'token=' . $this->session->data['token'] . $url, true);
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			//$start = ($page - 1) * 20;
			//$limit = 20;
			
			
			$data['suppliers'] = $this->model_purchase_received_orders->getAllSuppliers();
			
			$this->load->model('catalog/product');
			$products = array();
			$products = $this->model_catalog_product->getProducts($products);
			$i = 0;
			foreach($products as $product)
			{
				$products[$i] = $product['name'];
				$i++;
			}
			$data['products'] = $products;
			
			if(isset($_POST['export_bit']) && isset($_POST['page_no']))
			{
				$data['start_date'] = $_POST['date_start'];
				$data['end_date'] = $_POST['date_end'];
				$data['filter_supplier'] = $_POST['supplier'];
				$data['filter_product'] = $_POST['product'];
				$data['order_id'] = $_POST['order_id'];
				/*$data['supplier'] = $supplier;
				$data['product'] = $product;
				$data['order_id'] = $order_id;*/
				if($data['start_date'] == '' && $data['end_date'] == '' && $data['filter_supplier'] =='--supplier--' && $data['filter_product']=='--product--' && $data['order_id']=='')
				{
					$data['received_orders'] = $this->model_purchase_received_orders->get_all_received_orders();
				}
				else
				{
					$data['received_orders'] = $this->model_purchase_received_orders->get_filtered_orders($data);
				}
				
				
				/*if($_POST['page_no'] > 1)
				{
					$omit = ($_POST['page_no'] * 20) - 20;
					$data['received_orders'] = array_slice ($data['received_orders'], $omit);
				}
				else
				{
					$data['received_orders'] = array_slice($data['received_orders'],0,20);
				}*/
				
				
				$data['company_name'] = $this->config->get('config_name'); // store name
				$data['company_title'] = $this->config->get('config_title'); // store title
				$data['company_owner'] = $this->config->get('config_owner'); // store owner name
				$data['company_email'] = $this->config->get('config_email'); // store email
				$data['company_address'] = $this->config->get('config_address');//store address
				
				
				$html = $this->load->view('purchase/print_received_orders.tpl',$data);
				
				//$base_url = $this->config->get('config_url');
				$base_url = HTTP_CATALOG;
				
				
				//new mPDF($mode, $format, $font_size, $font, $margin_left, $margin_right, $margin_top, $margin_bottom, $margin_header, $margin_footer, $orientation);
				
				$mpdf = new mPDF('c','A4','','' , 10 , 10 , 25 , 12 , 5 , 7); 
				
				$header = '<div class="header"><div class="logo"><img src="'.$base_url.'image/catalog/logo.png" /></div><div class="company"><h3>'.$data['company_name'].'</h3></div></div><hr />';
 
				$mpdf->SetHTMLHeader($header, 'O', false);
				
				$footer = '<div class="footer"><div class="address"><b>Adress: </b>'.$data['company_address'].'</div><div class="pageno">{PAGENO}</div></div>';
				
				$mpdf->SetHTMLFooter($footer);
				
				//$mpdf->setFooter($footer);
				
				$mpdf->SetDisplayMode('fullpage');	
 
				$mpdf->list_indent_first_level = 0;
 
				$mpdf->WriteHTML($html);
			
				$mpdf->Output();
			}
			else
			{
				$data['received_orders'] = $this->model_purchase_received_orders->get_all_received_orders();
				
				$pages = $this->perform_pagination($page,count($data['received_orders']),$url);
				
				if($page > 1)
				{
					$omit = ($page * 20) - 20;
					$data['received_orders'] = array_slice ($data['received_orders'], $omit,20);
					$data['page_no'] = $page; 
				}
				else
				{
					$data['received_orders'] = array_slice($data['received_orders'],0,20);
					$data['page_no'] = $page;
				}
					
				$data['pagination'] = $pages['pagination'];

				$data['results'] = $pages['results'];
				
				$this->response->setOutput($this->load->view('purchase/received_orders.tpl', $data));
			}
		}
		
		public function perform_pagination($page,$total_orders,$url)
		{
			$pagination = new Pagination();
			$pagination->total = $total_orders;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			$pagination->url = $this->url->link('purchase/received_orders', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
			
			return $data;
		}
		
		public function filter()
		{
			$this->load->language('purchase/received_orders');
			$this->load->model('purchase/received_orders');
			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			
			$url = '';
			
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('purchase/received_orders', 'token=' . $this->session->data['token'] . $url, true)
			);

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_list'] = $this->language->get('text_list');
			$data['text_no_results'] = $this->language->get('text_no_results');
			$data['text_confirm'] = $this->language->get('text_confirm');
			$data['text_all_status'] = $this->language->get('text_all_status');
			$data['entry_date_start'] = $this->language->get('entry_date_start');
			$data['entry_date_end'] = $this->language->get('entry_date_end');
			$data['entry_supplier'] = $this->language->get('entry_supplier');
			$data['entry_product'] = $this->language->get('entry_product');
			$data['entry_order_id'] = $this->language->get('entry_order_id');
			$data['button_filter'] = $this->language->get('button_filter');
			
			$data['column_order_id'] = $this->language->get('column_order_id');
			$data['column_date_start'] = $this->language->get('column_date_start');
			$data['column_date_end'] = $this->language->get('column_date_end');
			$data['column_supplier'] = $this->language->get('column_supplier');
			$data['column_product'] = $this->language->get('column_product');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total_products'] = $this->language->get('column_total_products');
			$data['column_total'] = $this->language->get('column_total');
			$data['grand_total_text'] = $this->language->get('grand_total');
			
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			
			$data['token'] = $this->session->data['token'];
			$this->load->model('purchase/received_orders');
			
			$data['filter'] = $this->url->link('purchase/received_orders/filter', 'token=' . $this->session->data['token'] . $url, true);
			$data['pdf_export'] = $this->url->link('purchase/received_orders', 'token=' . $this->session->data['token'] . $url, true);
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			//$start = ($page - 1) * 20;
			//$limit = 20;
			
			
			$data['suppliers'] = $this->model_purchase_received_orders->getAllSuppliers();
			
			$this->load->model('catalog/product');
			$products = array();
			$products = $this->model_catalog_product->getProducts($products);
			$i = 0;
			foreach($products as $product)
			{
				$products[$i] = $product['name'];
				$i++;
			}
			$data['products'] = $products;
			
			if(!empty($_POST))
			{
				$_SESSION['post'] = $_POST;
				$post = $_SESSION['post'];
			}
			else
			{
				if(isset($_SESSION['post']))
				{
					$post = $_SESSION['post'];
				}
			}
			$this->load->model('purchase/received_orders');
			if(isset($post['filter_bit']))
			{
				$start_date = $post['date_start'];
				$end_date = $post['date_end'];
				$supplier = $post['supplier'];
				$product = $post['product'];
				$order_id = $post['order_id'];
				if($start_date == '' && $end_date == '' && $supplier =='--supplier--' && $product=='--product--' && $order_id=='')
				{
					$data['received_orders'] = $this->model_purchase_received_orders->get_all_received_orders();
					
					$pages = $this->perform_pagination($page,count($data['received_orders']),$url);
					
					$data['pagination'] = $pages['pagination'];

					$data['results'] = $pages['results'];
					
					if($page > 1)
					{
						$omit = ($page * 20) - 20;
						$data['received_orders'] = array_slice ($data['received_orders'], $omit,20);
					}
					else
					{
						$data['received_orders'] = array_slice($data['received_orders'],0,20);
					}
			
					$this->response->setOutput($this->load->view('purchase/received_orders.tpl', $data));
			
				}
				else
				{
					$data['start_date'] = $start_date;
					$data['end_date'] = $end_date;
					$data['filter_supplier'] = $supplier;
					$data['filter_product'] = $product;
					$data['order_id'] = $order_id;
					$data['received_orders'] = $this->model_purchase_received_orders->get_filtered_orders($data);
					
					$total_orders = count($data['received_orders']);
					
					$pagination = new Pagination();
					$pagination->total = $total_orders;
					$pagination->page = $page;
					$pagination->limit = $this->config->get('config_limit_admin');
					$pagination->url = $this->url->link('purchase/received_orders/filter', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

					$data['pagination'] = $pagination->render();

					$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
					
					if(count($data['received_orders']) <= 0)
					{
						$data['start_date'] = '';
						$data['end_date'] = '';
						$data['filter_supplier'] = '';
						$data['filter_product'] = '';
						$data['order_id'] = '';
					}
					if($page > 1)
					{
						$omit = ($page * 20) - 20;
						$data['received_orders'] = array_slice ($data['received_orders'], $omit,20);
					}
					else
					{
						$data['received_orders'] = array_slice($data['received_orders'],0,20);
					}
					
					$this->response->setOutput($this->load->view('purchase/received_orders.tpl', $data));
				}
			}
		}
	}
?>