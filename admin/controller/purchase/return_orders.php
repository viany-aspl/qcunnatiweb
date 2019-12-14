<?php
	//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
	class ControllerPurchaseReturnOrders extends Controller {
		public function index($return_orders = array()) {
                        $this->document->setTitle("Material Reversal List");
			$url = '';
			$this->load->language('purchase/return_orders');
			$this->load->model('purchase/return_orders');
			
			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_list'] = $this->language->get('text_list');
                        
                if (isset($this->request->get['filter_date_start'])) {
			$data['filter_date_start']=$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end =null;
		}
                   if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
                        $data['filter_name']=$filter_name;
		} else {
			$filter_name = null;
		}
                if (isset($this->request->get['filter_name_id'])) {
			$data['filter_name_id']=$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = null; 
		}
                 if (isset($this->request->get['filter_warehouse'])) {
			$data['filter_warehouse']=$filter_warehouse = $this->request->get['filter_warehouse'];
		} else {
			$filter_warehouse = null; 
		}
                  
                 if (isset($this->request->get['filter_id'])) {
			$data['filter_id']=$filter_id = $this->request->get['filter_id'];
		} else {
			$filter_id = null; 
		}
                
			
                if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
                if      (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
		} 
                if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		} 
                if      (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		} 
                if (isset($this->request->get['filter_warehouse'])) {
			$url .= '&filter_warehouse=' . $this->request->get['filter_warehouse'];
		}
                if (isset($this->request->get['filter_id'])) {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
			$data['text_confirm'] = $this->language->get('text_confirm');
			
			$data['button_add'] = $this->language->get('button_add');
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_delete'] = $this->language->get('button_delete');
			$data['button_filter'] = $this->language->get('button_filter');
			$data['button_clear'] = $this->language->get('button_clear');
			
			$data['token'] = $this->session->data['token'];
			
                       $filter_data = array(
			'filter_date_start'	 => $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'filter_returnid'         => $return_id,
                        'filter_warehouse'      => $filter_warehouse,
                        'filter_name'	     => $filter_name,
                        'filter_id'	     => $filter_id,
                        'filter_name_id'	     => $filter_name_id,	
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		     );
                        
                        
                        
                        
			$data['orders'] = array();
                        $data['warehouses'] = $this->model_purchase_return_orders->getAllWareHouses();
			$results = $this->model_purchase_return_orders->getreturnorderdata($filter_data);
                        $order_total = $this->model_purchase_return_orders->getreturnordertotaldata($filter_data);
			$this->load->model('catalog/product');
			foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                'id' =>$result['id'],
				'store_name' => $result['store_name'],
				'supplier'   => $result['supplier'],
				'product_name'      => $result['product_name'],
				'name'     => $result['firstname']." ".$result['lastname'],
                                'order_id'=>  $result['order_id'],   
				'return_date'      => $result['return_date'],
				'return_quantity'      => $result['return_quantity'],
				'reason'      => $result['reason'],
                                'status'      => $result['status']
			);
		        }
                        
                        if (isset($this->session->data['success'])) 
                        {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
                        } 
                        else
                        {
			$data['success'] = '';
                        } 
                        if (isset($this->session->data['error_warning'])) 
                        {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
                        } 
                        else 
                        {
			$data['error_warning'] = '';
                        }
                        
                        $data['ware_houses']=$this->model_purchase_return_orders->get_ware_houses();
			$url = '';
                        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
                        }

                        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
                        }
                        if (isset($this->request->get['filter_id'])) {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
                        }

                        if (isset($this->request->get['filter_warehouse'])) {
			$url .= '&filter_warehouse=' . $this->request->get['filter_warehouse'];
                        }
                        if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
                        } 
                        if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
                        } 
		
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Material Reversal',
				'href' => $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true)
			);
			$total_return_orders = $this->model_purchase_return_orders->getTotalReturnOrders();
			
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			//echo $url;
			/*$start = ($page - 1) * 20;
			$limit = 20;*/
			
			/*pagination*/
			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			if(isset($return_orders['filter_bit']))
			{
				$pagination->url = $this->url->link('purchase/return_orders/filter', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
			}
			else{
				$pagination->url = $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
			}
			$data['pagination'] = $pagination->render();
			
			$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

			
			/*pagination*/
			//$data['add'] = $this->url->link('purchase/return_orders/add', 'token=' . $this->session->data['token'] . $url, true);
			//$data['delete'] = $this->url->link('purchase/return_orders/delete', 'token=' . $this->session->data['token'] . $url, true);
			//$data['filter'] = $this->url->link('purchase/return_orders/filter', 'token=' . $this->session->data['token'] . $url, true);
			//$data['edit'] = $this->url->link('purchase/return_orders/edit', 'token=' . $this->session->data['token'] . $url, true);
			
			$data['header'] = $this->load->controller('common/header');
			$data['create_note'] = $this->url->link('purchase/return_orders/create_note', 'token=' . $this->session->data['token'] . $url, true);
                        $data['download_note'] = $this->url->link('purchase/return_orders/download_note', 'token=' . $this->session->data['token'] . $url, true);
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('purchase/return_orders_list.tpl', $data));
			
		}
        public function create_note()
        {
                $this->document->setTitle("Product Return Credit Note");
		$url = '';
		$this->load->language('purchase/return_orders');
		$this->load->model('purchase/return_orders');
		 
                if (isset($this->request->post['order_id'])) {
			$data['order_id']=$order_id = $this->request->post['order_id'];
		} else {
			$filter_id = null; 
		}
                if (isset($this->request->post['filter_ware_house'])) {
			$data['filter_ware_house']=$filter_ware_house = $this->request->post['filter_ware_house'];
		} else {
			$filter_ware_house = null; 
		}
                if (isset($this->request->post['filter_name'])) {
			$url .= '&filter_name=' . $this->request->post['filter_name'];
		} 
                if      (isset($this->request->post['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->post['filter_name_id'];
		} 
                if (isset($this->request->post['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->post['filter_date_start'];
		} 
                if      (isset($this->request->post['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->post['filter_date_end'];
		} 
                if (isset($this->request->post['filter_warehouse'])) {
			$url .= '&filter_warehouse=' . $this->request->post['filter_warehouse'];
		}
                if (isset($this->request->post['filter_id'])) {
			$url .= '&filter_id=' . $this->request->post['filter_id'];
		}
                $filter_data=array(
                  'order_id'=>$order_id,
                  'filter_ware_house'=>$filter_ware_house
                );
                
                $res1=$this->model_purchase_return_orders->create_note($filter_data);
                $res2=explode(',',$res1);
                $res=$res2[0];
                $res_txt=$res2[1];
                if($res=="1")
                {
                    $this->session->data['success']=$res_txt;
                }
                else
                {
                    $this->session->data['error_warning']=$res_txt;
                }
                $this->response->redirect($this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true));
               	
            }
        public function download_note()
        {
                $this->document->setTitle("Product Return Credit Note");
		$url = '';
		$this->load->language('purchase/return_orders');
		$this->load->model('purchase/return_orders');
			
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
                        
                if (isset($this->request->get['filter_date_start'])) {
			$data['filter_date_start']=$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end =null;
		}
                   if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
                        $data['filter_name']=$filter_name;
		} else {
			$filter_name = null;
		}
                if (isset($this->request->get['filter_name_id'])) {
			$data['filter_name_id']=$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = null; 
		}
                 if (isset($this->request->get['filter_warehouse'])) {
			$data['filter_warehouse']=$filter_warehouse = $this->request->get['filter_warehouse'];
		} else {
			$filter_warehouse = null; 
		}
                  
                 if (isset($this->request->get['filter_id'])) {
			$data['filter_id']=$filter_id = $this->request->get['filter_id'];
		} else {
			$filter_id = null; 
		}
                	
                if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
                if      (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
		} 
                if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		} 
                if      (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		} 
                if (isset($this->request->get['filter_warehouse'])) {
			$url .= '&filter_warehouse=' . $this->request->get['filter_warehouse'];
		}
                if (isset($this->request->get['filter_id'])) {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
                $order_id=$this->request->get['order_id'];
                $results = $this->model_purchase_return_orders->getReturnNote($order_id);
                $store_id=$results[0]['store_id'];
                //print_r($results);
                $store_info = $this->model_purchase_return_orders->getStoreInfo($store_id);
                //print_r($store_info);
                $data['results']=$results;
                $data['store_info']=$store_info;
                
                //$data['header'] = $this->load->controller('common/header');
		$data['create_note'] = $this->url->link('purchase/return_orders/create_note', 'token=' . $this->session->data['token'] . $url, true);
                $data['download_note'] = $this->url->link('purchase/return_orders/download_note', 'token=' . $this->session->data['token'] . $url, true);
			
		//$data['column_left'] = $this->load->controller('common/column_left');
		//$data['footer'] = $this->load->controller('common/footer');
		//$this->response->setOutput($this->load->view('purchase/return_orders_create_note.tpl', $data));
		
                
                $html=$this->load->view('purchase/return_orders_create_note.tpl',$data);
             require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             $mpdf = new mPDF('c','A4','','' , 0 , 0 , 25 , 10 , 5 , 7);
             //$stylesheet = file_get_contents('https://unnati.world/shop/admin/view/stylesheet/pos/bootstrap.min.css'); // external css
             //$mpdf->WriteHTML($stylesheet,1); 
             //$stylesheet = file_get_contents('https://unnati.world/shop/admin/view/stylesheet/sheet.css'); // external css
             //$mpdf->WriteHTML($stylesheet,1);
             
             //exit;
                $header = '<br/><div class="header" style="margin-top: 20px;">
		<div class="logo">
		<img style="float: right;margin-right: 40px;height: 30px;" src="https://unnati.world/shop/image/catalog/logo.png"  />
		</div>
 		
		<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;;" /> 
	</div>';
                
                $header = '<div class="header" style="">
                   
<div class="logo" style="width: 100%;" >
<div style="padding-left: 50px;">
<img src="https://unnati.world/shop/image/letterhead_text.png" style="height: 40px; width: 121px;margin-top: 20px;marging-left: 100px;" />
<img src="https://unnati.world/shop/image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />
</div>
                         </div>
<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;" /> 

</div>';
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->SetHTMLHeader($header, 'O', false);
                  
                $footer = '<div class="footer">
                        
                        <img src="https://unnati.world/shop/image/letterhead_bottomline.png" style="height: 10px; width: 150% !important;" /> 
                        <div class="address"><img src="../image/letterhead_footertext.png" style="width: 120% !important;" /> </div>'
                        . '</div>';

                $mpdf->setAutoBottomMargin = 'stretch';       	 
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
    
                $mpdf->WriteHTML($html);
                
                $filename='Invoice_'.$order_id.'.pdf';
                
                $mpdf->Output($filename,'D');
                
            }
		
		public function add()
		{
			$url = '';
			$this->load->language('purchase/return_orders');
			
			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_order_returns'] = $this->language->get('text_order_returns');
			
			$data['entry_order_id'] = $this->language->get('entry_order_id');
			$data['entry_product'] = $this->language->get('entry_product');
                        $data['entry_store'] = $this->language->get('entry_store');
			$data['entry_supplier'] = $this->language->get('entry_supplier');
			$data['entry_quantity'] = $this->language->get('entry_quantity');
			
			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
			
			$data['token'] = $this->session->data['token'];
			
			$url ='';
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true)
			);
			
		          
			$data['save_return_order'] = $this->url->link('purchase/return_orders/save_return_order', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true);
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('purchase/return_order_form.tpl', $data));
		}
		
		public function save_return_order()
		{
			$url = '';
			$this->load->language('purchase/return_orders');
			$this->load->model('purchase/return_orders');
			
			$order_id = $this->request->post['order_id'];
			$product = $this->request->post['product'];
			$supplier = $this->request->post['supplier'];
                        $store = $this->request->post['store'];
			$return_quantity = $this->request->post['return_quantity'];
			$reason = $this->request->post['reason'];
			
			if ($order_id == "") {
				$_SESSION['error_order_id'] = $this->language->get('error_order_id');
			}
			if ($product == "Product") {
				$product = '';
				$_SESSION['product_error'] = $this->language->get('product_error');
			}
                        if ($store == "Store") {
				$store = '';
				$_SESSION['store_error'] = $this->language->get('store_error');
			}
			if ($supplier == "Supplier") {
				$supplier = '';
				$_SESSION['supplier_error'] = $this->language->get('supplier_error');
			}
			if ($return_quantity == "") {
				$_SESSION['quantity_error'] = $this->language->get('quantity_error');
			}
			if($reason == "")
			{
				$_SESSION['reason_error'] = "Reason is required";
			}
			if(isset($_SESSION['error_order_id']) || isset($_SESSION['product_error']) || isset($_SESSION['supplier_error']) || isset($_SESSION['quantity_error']) || isset($_SESSION['reason_error']))
			{
				$_SESSION['error_warning'] = $this->language->get('error_warning');
				$data['order_id'] = $order_id;
				$data['product'] = $product;
                                $data['store'] = $store;
				$data['supplier'] = $supplier;
				$data['return_quantity'] = $return_quantity;
				$data['reason'] = $reason;
				
				if($order_id)
				{
					$data['products'] = $this->model_purchase_return_orders->getProducts($order_id);
				}
				if($order_id && $product)
				{
					$data['suppliers'] = $this->model_purchase_return_orders->getSuppliers($order_id,$product);
				}
				$data['heading_title'] = $this->language->get('heading_title');
				$data['text_order_returns'] = $this->language->get('text_order_returns');
				
				$data['entry_order_id'] = $this->language->get('entry_order_id');
				$data['entry_product'] = $this->language->get('entry_product');
                                $data['entry_store'] = $this->language->get('entry_store');
				$data['entry_supplier'] = $this->language->get('entry_supplier');
				$data['entry_quantity'] = $this->language->get('entry_quantity');
				
				$data['button_save'] = $this->language->get('button_save');
				$data['button_cancel'] = $this->language->get('button_cancel');
				
				$data['token'] = $this->session->data['token'];
				
				$url ='';
				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
				);

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true)
				);
				
				
				$data['save_return_order'] = $this->url->link('purchase/return_orders/save_return_order', 'token=' . $this->session->data['token'] . $url, true);
				
				$data['header'] = $this->load->controller('common/header');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['footer'] = $this->load->controller('common/footer');
				$this->response->setOutput($this->load->view('purchase/return_order_form.tpl', $data));

			}
			else
			{
				$data['order_id'] = $order_id;
				$data['product_id'] = $product;
				$data['supplier'] = $supplier;
                                $data['store'] = $store;
				$data['return_quantity'] = $return_quantity;
				$data['reason'] = $reason;
				$saved = $this->model_purchase_return_orders->save_return_order($data);
				if($saved)
				{
					$_SESSION['text_success'] = $this->language->get('text_success');
					$this->response->redirect($this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true));
				}
				else
				{
					$_SESSION['error_wrong'] = $this->language->get('error_wrong');
					$this->response->redirect($this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true));
				}
			}
		}
		
		public function getProducts()
		{
			$order_id = $this->request->get['order_id'];
			$this->load->model('purchase/return_orders');
			$data['products'] = $this->model_purchase_return_orders->getProducts($order_id);
			echo json_encode($data['products']);
		}
                
                
                public function getStores()
		{
			$order_id = $this->request->post['order_id'];
			$product_id = $this->request->post['product_id'];
			$this->load->model('purchase/return_orders');
			$data['stores'] = $this->model_purchase_return_orders->getStores($order_id,$product_id);
			echo json_encode($data['stores']);
		}
                
		public function getSuppliers()
		{
			$order_id = $this->request->post['order_id'];
			$product_id = $this->request->post['product_id'];
			$this->load->model('purchase/return_orders');
			$data['suppliers'] = $this->model_purchase_return_orders->getSuppliers($order_id,$product_id);
			echo json_encode($data['suppliers']);
		}
		public function checkQuantity()
		{	
			$order_id = $this->request->post['order_id'];
			$product_id = $this->request->post['product_id'];
			$supplier_id = $this->request->post['supplier_id'];
                        $store_id = $this->request->post['store_id'];
			$this->load->model('purchase/return_orders');
			$quantity = $this->model_purchase_return_orders->checkQuantity($order_id,$product_id,$supplier_id,$store_id);
			echo $quantity['quantity'];
		}
		public function delete()
		{
			$url = '';
			$delete_ids = $this->request->post['selected'];
			$this->load->model('purchase/return_orders');
			$this->load->language('purchase/return_orders');
			$deleted = $this->model_purchase_return_orders->delete($delete_ids);
			if($deleted)
			{
				$_SESSION['text_delete_success'] = $this->language->get('text_delete_success');
				$this->response->redirect($this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true));
			}
			else
			{
				$_SESSION['error_wrong'] = $this->language->get('error_wrong');
				$this->response->redirect($this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true));
			}
		}
		public function filter()
		{
			$url = '';
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
			$data['return_id'] = $post['filter_return_id'];
			$data['order_id'] = $post['filter_order_id'];
			$data['product'] = $post['filter_product'];
			$data['supplier'] = $post['filter_supplier'];
			$data['start_date'] = $post['filter_start_date'];
			$data['end_date'] = $post['filter_end_date'];
			$this->load->model('purchase/return_orders');
			$data['return_orders'] = $this->model_purchase_return_orders->filter($data);
			if(isset($this->request->post['export_bit']))
			{
				$data['export_bit'] = $this->request->post['export_bit'];
			}
			if(isset($post['filter_bit']))
			{
				$data['filter_bit'] = $post['filter_bit'];
			}
			$this->index($data);
		}
		public function edit()
		{
			$url = '';
			$return_order_id = $this->request->get['return_order_id'];
			$this->load->language('purchase/return_orders');
			
			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_order_returns'] = $this->language->get('text_order_returns');
			
			$data['entry_order_id'] = $this->language->get('entry_order_id');
			$data['entry_product'] = $this->language->get('entry_product');
			$data['entry_supplier'] = $this->language->get('entry_supplier');
			$data['entry_quantity'] = $this->language->get('entry_quantity');
			
			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
			
			$data['token'] = $this->session->data['token'];
			
			$url ='';
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			
			$data['update_return_order'] = $this->url->link('purchase/return_orders/update_return_order', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true);
			$this->load->model('purchase/return_orders');
			$return_order = $this->model_purchase_return_orders->getReturnOrder($return_order_id);
			
			$data['order_id'] = $return_order['order_id'];
			$data['product'] = $return_order['product_id'];
			$data['supplier'] = $return_order['supplier_id'];
			$data['return_quantity'] = $return_order['return_quantity'];
			$data['reason'] = $return_order['reason'];
			
			$data['products'] = $this->model_purchase_return_orders->getProducts($return_order['order_id']);
			$data['suppliers'] = $this->model_purchase_return_orders->getSuppliers($return_order['order_id'],$return_order['product_id']);
				
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('purchase/return_order_update_form.tpl', $data));
		
		}
		public function update_return_order()
		{
			$url = '';
			$this->load->language('purchase/return_orders');
			$this->load->model('purchase/return_orders');
			
			$order_id = $this->request->post['order_id'];
			$product = $this->request->post['product'];
			$supplier = $this->request->post['supplier'];
			$return_quantity = $this->request->post['return_quantity'];
			
			if ($return_quantity == "") {
				$_SESSION['quantity_error'] = $this->language->get('quantity_error');
			}
			
			if(isset($_SESSION['quantity_error']))
			{
				$_SESSION['error_warning'] = $this->language->get('error_warning');
				$data['order_id'] = $order_id;
				$data['product'] = $product;
				$data['supplier'] = $supplier;
				$data['return_quantity'] = $return_quantity;
				
				if($order_id)
				{
					$data['products'] = $this->model_purchase_return_orders->getProducts($order_id);
				}
				if($order_id && $product)
				{
					$data['suppliers'] = $this->model_purchase_return_orders->getSuppliers($order_id,$product);
				}
				$data['heading_title'] = $this->language->get('heading_title');
				$data['text_order_returns'] = $this->language->get('text_order_returns');
				
				$data['entry_order_id'] = $this->language->get('entry_order_id');
				$data['entry_product'] = $this->language->get('entry_product');
				$data['entry_supplier'] = $this->language->get('entry_supplier');
				$data['entry_quantity'] = $this->language->get('entry_quantity');
				
				$data['button_save'] = $this->language->get('button_save');
				$data['button_cancel'] = $this->language->get('button_cancel');
				
				$data['token'] = $this->session->data['token'];
				
				$url ='';
				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
				);

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true)
				);
				
				
				$data['update_return_order'] = $this->url->link('purchase/return_orders/update_return_order', 'token=' . $this->session->data['token'] . $url, true);
				$data['cancel'] = $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true);
			
				$data['header'] = $this->load->controller('common/header');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['footer'] = $this->load->controller('common/footer');
				$this->response->setOutput($this->load->view('purchase/return_order_update_form.tpl', $data));

			}
			else
			{
				$data['order_id'] = $order_id;
				$data['product_id'] = $product;
				$data['supplier'] = $supplier;
				$data['return_quantity'] = $return_quantity;
				$updated = $this->model_purchase_return_orders->update_return_order($data);
				if($updated)
				{
					$_SESSION['text_success_updated'] = $this->language->get('text_success_updated');
					$this->response->redirect($this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true));
				}
				else
				{
					$_SESSION['error_no_change'] = $this->language->get('error_no_change');
					$this->response->redirect($this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'] . $url, true));
				}
			}
		}
		public function checkUpdateQuantity()
		{
			$order_id = $this->request->post['order_id'];
			$product_id = $this->request->post['product_id'];
			$supplier_id = $this->request->post['supplier_id'];
			$this->load->model('purchase/return_orders');
			$quantity = $this->model_purchase_return_orders->checkUpdateQuantity($order_id,$product_id,$supplier_id);
			echo $quantity['quantity'];
		}
	}
?>