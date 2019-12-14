<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerMaterialReceivedPurchaseOrder extends Controller{
        public function check_ware_house_quantity()
        {
            //print_r($this->request->post);exit;
            $total_product=count($this->request->post['ware_houses']);
            
            
            
            for($a=0;$a<$total_product;$a++)
            {
               $product_id=$this->request->post['product_id'][$a];
               $product_name=$this->request->post['product_name'][$a];
               $p_qnty=$this->request->post['receive_quantity'][$a];
               $ware_house=$this->request->post['ware_houses'][$a];
               
               $this->load->model('material_received/purchase_order');
               $data_qnty=$this->model_material_received_purchase_order->check_ware_house_quantity($ware_house,$product_id,$p_qnty);
               
               if($data_qnty=="0")
               {
                   echo 'There is not sufficent quantity of '.$product_name.' at ware house';
                   return;
               }
               
               
            }
            //$data_credit=$this->model_partner_purchase_order->check_ship_to_credit($ship_to,$grand_total);
            //if($data_credit=="0")
            //{
             //      echo 'Amount exceed from allowed credit limit ';
             //      return;
            //}
            //product_id
            //$store_id = $this->request->get['store_id'];
            //$this->load->model('partner/purchase_order');
            //echo $data=$this->model_partner_purchase_order->get_to_store_data($store_id);
            
        }
    
	public function index()
	{
		//set the title of the page
			$this->document->setTitle("Purchase Request Acknowledgement");
			
			$data['column_left'] = $this->load->controller('common/column_left');
			/*$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');*/
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$url = '';
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->user->getId());
		    	$data['user_group_id']=$user_info['user_group_id'];
			
			if (isset($this->request->get['filter_id'])) {
				$url .= '&filter_id=' . $this->request->get['filter_id'];
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
                        		if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_store'])) {
				$url .= '&filter_store=' . $this->request->get['filter_store'];
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
			'text' => "Purchase Request Acknowledgement",
			'href' => $this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);

			
		$data['add'] = $this->url->link('material_received/purchase_order/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('material_received/purchase_order/delete', 'token=' . $this->session->data['token'] . $url, true);
		$data['filter'] = $this->url->link('material_received/purchase_order/filter', 'token=' . $this->session->data['token'] . $url, true);
		/*getting the list of the orders*/
		
                if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
                        $this->session->data['error_warning']='';
		} else {
			$data['error_warning'] = '';
		}
                if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
                        $this->session->data['success']='';
		} else {
			$data['success'] = '';
		}
                
		$this->load->model('material_received/purchase_order');
                        if (isset($this->request->get['page'])) {
                                $page = $this->request->get['page'];
                        } 
                        else {
                                $page = 1;
                        }
                        if (isset($this->request->get['filter_id'])) {
				$filter_id =  $this->request->get['filter_id'];
			}

			if (isset($this->request->get['filter_date_start'])) {
				$filter_date_start=$this->request->get['filter_date_start'];
			}
			else
			{
				$filter_date_start=date('Y-m')."-01";
			}
                        		if (isset($this->request->get['filter_date_end'])) {
				$filter_date_end=$this->request->get['filter_date_end'];
			}
			{
				$filter_date_end=date('Y-m-d');
			}
			//if (($this->request->get['filter_status'])) {
				$filter_status=$this->request->get['filter_status'];
			//}
			if (isset($this->request->get['filter_store'])) {
				$filter_store=$this->request->get['filter_store'];
			}
		
                        $filter_data=array(
                            'filter_id'=>$filter_id,
                            'filter_date_start'=>$filter_date_start,
                            'filter_date_end'=>$filter_date_end,
                            'filter_status'=>$filter_status,
		'filter_store'=>$filter_store,
                            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                            'limit'   => $this->config->get('config_limit_admin')
                        );
		
		$order_list = $this->model_material_received_purchase_order->getList($filter_data);
           //     $get_po_number = $this->model_purchase_purchase_order-> get_po_number();
                
		//$data['order_list']=array();
//               
                foreach($order_list as $order)
                {
                    
                    $order_id=$order['id'];
                     $order_information= $this->model_material_received_purchase_order->view_order_details($order_id);
                    $total_product=count($order_information['products']);
                    if($total_product>1)
                    { //echo $order_information['products'][0]['ware_house_name'];
                       if($order_information['products'][0]['ware_house_name']!="")
                       {
                       $ware_house_name='Multiple'; 
                       }
                    }
                    else
                    {
                        $ware_house_name=$order_information['products'][0]['ware_house_name'];
		if($order_information['products'][0]['supplier_name']=="")
                       	{
                       		$ware_house_name=$order_information['products'][0]['ware_house_name']; 
                      	 }
		else
		{
			$ware_house_name=$order_information['products'][0]['supplier_name']; 
		}
                    }
                    //print_r($order_information['products'][0]['ware_house_name']);
                    $data['order_list'][]=array(
                        'id'=>$order_id,
                        'order_date'=>$order['order_date'],
                        'order_sup_send'=>$order['order_sup_send'],
                        'delete_bit'=>$order['delete_bit'],
                        'user_id'=>$order['user_id'],
                        'receive_date'=>$order['receive_date'],
                        'receive_bit'=>$order['receive_bit'],
                        'pending_bit'=>$order['pending_bit'],
                        'pre_supplier_bit'=>$order['pre_supplier_bit'],
                        'order_status_id'=>$order['order_status_id'],
                        'canceled_by'=>$order['canceled_by'],
                        'canceled_message'=>$order['canceled_message'],
                        'store_id'=>$order['store_id'],
                        'store_type'=>$order['store_type'],
                        'potential_date'=>$order['potential_date'],
                        'driver_otp'=>$order['driver_otp'],
                        'driver_mobile'=>$order['driver_mobile'],
                        'firstname'=>$order['firstname'],
                        'lastname'=>$order['lastname'],
                        'store_name'=>$order['store_name'],
                        'creditlimit'=>$order['creditlimit'],
                        'currentcredit'=>$order['currentcredit'],
                        'ware_house_name'=>$ware_house_name,
			'product' =>$order['product'],
			'quantity' =>$order['quantity'],
                        'po_number' =>$order['po_number']
                    ); 
                }
		//print_r($order_list);
		$total_orders = $this->model_material_received_purchase_order->getTotalOrders($filter_data);
		
		//getting total orders
		$data['view'] = $this->url->link('material_received/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, true);
		$data['receive'] = $this->url->link('material_received/purchase_order/receive_order', 'token=' . $this->session->data['token'] . $url, true);
		
		
		/*pagination*/
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
                	$data['filter_id']=$filter_id ;
		$data['from']=$filter_date_start ;
                	$data['to']=$filter_date_end ;
                	$data['status']=$filter_status ;
		$data['filter_store']=$filter_store ;
                	$data['token']=$this->request->get['token'];
		$this->load->model('setting/store');		
		$data['stores']=$this->model_setting_store->getOwnStores();
		/*pagination*/
		$data['my_custom_text'] = "This is material_received order page.";
		$this->response->setOutput($this->load->view('material_received/purchase_order_list.tpl', $data));
	}
	
	public function download_excel()
	{
		
	         		$this->load->model('material_received/purchase_order');
		
                       
                        		if (isset($this->request->get['filter_id'])) {
				$filter_id =  $this->request->get['filter_id'];
			}

			if (isset($this->request->get['filter_date_start'])) {
				$filter_date_start=$this->request->get['filter_date_start'];
			}
			else
			{
				$filter_date_start=date('Y-m')."-01";
			}
                        		if (isset($this->request->get['filter_date_end'])) {
				$filter_date_end=$this->request->get['filter_date_end'];
			}
			{
				$filter_date_end=date('Y-m-d');
			}
			//if (($this->request->get['filter_status'])) {
				$filter_status=$this->request->get['filter_status'];
			//}
			if (isset($this->request->get['filter_store'])) {
				$filter_store=$this->request->get['filter_store'];
			}
			$page=1;
                        $filter_data=array(
                            'filter_id'=>$filter_id,
                            'filter_date_start'=>$filter_date_start,
                            'filter_date_end'=>$filter_date_end,
                            'filter_status'=>$filter_status,
		'filter_store'=>$filter_store,
                            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                            'limit'   => 1000
                        );
		
		$order_list = $this->model_material_received_purchase_order->getList($filter_data);
		//$results=$this->model_purchase_purchase_order->download_excel($filter_data);
               
                foreach($order_list as $order)
                {
                    
                    $order_id=$order['id'];
                     $order_information= $this->model_material_received_purchase_order->view_order_details($order_id);
                    $total_product=count($order_information['products']);
                    if($total_product>1)
                    { //echo $order_information['products'][0]['ware_house_name'];
                       if($order_information['products'][0]['ware_house_name']!="")
                       {
                       $ware_house_name='Multiple'; 
                       }
                    }
                    else
                    {
                        $ware_house_name=$order_information['products'][0]['ware_house_name'];
		if($order_information['products'][0]['supplier_name']=="")
                       	{
                       		$ware_house_name=$order_information['products'][0]['ware_house_name']; 
                      	 }
		else
		{
			$ware_house_name=$order_information['products'][0]['supplier_name']; 
		}
                    }
                    //print_r($order_information['products'][0]['ware_house_name']);
                    $data['order_list'][]=array(
                        'id'=>$order_id,
                        'order_date'=>$order['order_date'],
                        'order_sup_send'=>$order['order_sup_send'],
                        'delete_bit'=>$order['delete_bit'],
                        'user_id'=>$order['user_id'],
                        'receive_date'=>$order['receive_date'],
                        'receive_bit'=>$order['receive_bit'],
                        'pending_bit'=>$order['pending_bit'],
                        'pre_supplier_bit'=>$order['pre_supplier_bit'],
                        'order_status_id'=>$order['order_status_id'],
                        'canceled_by'=>$order['canceled_by'],
                        'canceled_message'=>$order['canceled_message'],
                        'store_id'=>$order['store_id'],
                        'store_type'=>$order['store_type'],
                        'potential_date'=>$order['potential_date'],
                        'driver_otp'=>$order['driver_otp'],
                        'driver_mobile'=>$order['driver_mobile'],
                        'firstname'=>$order['firstname'],
                        'lastname'=>$order['lastname'],
                        'store_name'=>$order['store_name'],
                        'creditlimit'=>$order['creditlimit'],
                        'currentcredit'=>$order['currentcredit'],
                        'ware_house_name'=>$ware_house_name,
			'product' =>$order['product'],
			'quantity' =>$order['quantity']
                    ); 
                }
		
		//print_r($data['order_list']);exit;
		$file_name="Material_Received_Acknowledgement_".date('dMy').'.xls';
 		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
 		header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
 		header("Expires: 0");
 		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 		header("Cache-Control: private",false);

		echo '<table id="example2" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
	      <th>Requisition ID</th>
                    <th>Date</th>
                    <th>Ordered By</th>
                    <th>Store Name</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
 	      <th>Supplier/Ware House</th>
	      <th>Status</th>
                </tr>
                </thead>
                <tbody>';
$tblbody=" ";
foreach($data['order_list'] as $data)
{ 


                    echo  '<tr> 
	      <td>'.$data['id'].'</td>
	      <td>'.date('Y-m-d',strtotime($data['order_date'])).'</td>
                    <td>'.$data['firstname'].' '.$data['lastname'].'</td>
                    <td>'.$data['store_name'].'</td>
	      <td>'.$data['product'].'</td>
                    <td>'.$data['quantity'].'</td>
	      <td>'.$data['ware_house_name'].'</td>
                    <td>'.number_format((float)($data['p_price']), 2, '.', '').'</td>
	      <td>'.number_format((float)($data['p_price']*$data['p_qnty']), 2, '.', '').'</td>';
	      if($data['pending_bit']=="1")
	      {
		echo '<td>Pending</td>';
		
	      }
	     else
	      {
		echo '<td>Received</td>';
	      }
	      
	      
	      
                   echo '</tr>';


}


echo '</tbody>
          </table>';
	}
	
	
	
	
	
	///////////////////////////////////////////////////////////////////////////////////
	
	/*----------------------------view_order_details function starts here------------*/
	
	public function view_order_details()
	{
                $this->document->setTitle("View Order");
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
			
			if (isset($this->request->get['filter_id'])) {
				$url .= '&filter_id=' . $this->request->get['filter_id'];
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
                        if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'text' => "Unnati Krishi Kendra Requisition",
			'href' => $this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
		
		$this->load->model('material_received/purchase_order');
		$data['order_information'] = $this->model_material_received_purchase_order->view_order_details($order_id);
		//print_r($data['order_information']);
                $data['cancel'] = $this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
		$data['pdf_export'] = $this->url->link('material_received/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, true);
		if(isset($_GET['export']))
		{
			
			$data['company_name'] = $this->config->get('config_name'); // store name
			$data['company_title'] = $this->config->get('config_title'); // store title
			$data['company_owner'] = $this->config->get('config_owner'); // store owner name
			$data['company_email'] = $this->config->get('config_email'); // store email
			$data['company_address'] = $this->config->get('config_address');//store address
				
			$html = $this->load->view('material_received/print_order.tpl',$data);
			
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
			$this->response->setOutput($this->load->view('material_received/view_order.tpl',$data));
		}
	}
	
	/*----------------------------view_order_details function ends here--------------*/
	
	
	public function receive_order()
	{
                $this->document->setTitle("Receive Order");
		$order_id = $this->request->get['order_id'];
		$data['order_id'] = $order_id;
		$data['column_left'] = $this->load->controller('common/column_left');
		
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$url = '';
                $this->load->model('material_received/purchase_order');
			
			if (isset($this->request->get['filter_id'])) {
				$url .= '&filter_id=' . $this->request->get['filter_id'];
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
                        if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
                $data['token']=$this->session->data['token'];
                if($this->request->server['REQUEST_METHOD'] == 'POST')
                {
                    //print_r($this->request->post);exit;
                    $data['order_receive_date']=$this->request->post['order_receive_date'];
                    $inserted = $this->model_material_received_purchase_order->insert_receive_order($this->request->post,$order_id);
                    $this->session->data['success'] = 'Order Accepeted successfully';
                    $this->response->redirect($this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            
                }
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
			$data['breadcrumbs'][] = array(
			'text' => "Unnati Krishi Kendra Requisition",
			'href' => $this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
		
		
		$data['order_information'] = $this->model_material_received_purchase_order->view_order_details($order_id);
		if($data['order_information']['order_info']['receive_bit']==1)
		{
			$data['receive_bit'] = $data['order_information']['order_info']['receive_bit'];
		}
		else
		{
			$data['ftime_bit'] = 1;
		}
		$data['action'] = $this->url->link('material_received/purchase_order/receive_order', 'token=' . $this->session->data['token'] . $url, true);
		$data['cancel'] = $this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
		$this->load->model('material_received/supplier');
		$data['suppliers'] = $this->model_material_received_supplier->get_total_suppliers();
		//print_r($data['suppliers']);
                	//$data['ware_houses']=$this->model_purchase_purchase_order->get_ware_houses();
                //print_r($data['order_information']);//['order_info']['user_id']
                $data['user_id']=$data['order_information']['order_info']['user_id'];
		$this->response->setOutput($this->load->view('material_received/receive_order.tpl',$data));
	
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
			'text' => "Unnati Krishi Kendra Requisition",
			'href' => $this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			
			$this->load->model('material_received/purchase_order');
			$data['order_information'] = $this->model_material_received_purchase_order->view_order_details($order_id);
			
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
			$data['action'] = $this->url->link('material_received/purchase_order/insert_receive_order', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$this->load->model('material_received/supplier');
			$data['suppliers'] = $this->model_material_received_supplier->get_total_suppliers();
			//$this->response->redirect($this->url->link('material_received/purchase_order/receive_order', 'token=' . $this->session->data['token'] . $url, true));
			$this->response->setOutput($this->load->view('material_received/receive_order.tpl',$data));
		}
		else
		{
			if(isset($this->request->post['disable_bit']))
			{
				unset($received_order_info['suppliers_ids']);
			}
			$this->load->model('material_received/purchase_order');
			$inserted = $this->model_material_received_purchase_order->insert_receive_order($received_order_info,$order_id);
			if($inserted)
			{
				$_SESSION['receive_success_message'] = 'Order received Successfully!!';
				$this->response->redirect($this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
			}
			else
			{
				$_SESSION['something_wrong_message'] = 'Sorry!! something went wrong, try again';
				$this->response->redirect($this->url->link('material_received/purchase_order/insert_receive_order', 'token=' . $this->session->data['token'] . $url, true));
			}
				
		}
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/
	/*--------------------Insert material_received Indent starts heres-------------------------------------------------*/
	
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
			$data['action'] = $this->url->link('material_received/purchase_order/insert_purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$this->load->model('material_received/supplier');
			$data['suppliers'] = $this->model_material_received_supplier->get_total_suppliers();
		                //stores
                $this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();  
			$this->load->model('catalog/option');
			$data['options'] = $this->model_catalog_option->getOptions();
			$this->response->setOutput($this->load->view('material_received/purchase_order_form.tpl', $data));
			//$this->response->redirect($this->url->link('material_received/purchase_order/add', 'token=' . $this->session->data['token'] . $url, true));
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
			
			$this->load->model('material_received/purchase_order');
			$order_id = $this->model_material_received_purchase_order->insert_purchase_order($data);
			
			
			
			if(isset($this->request->post['mail_bit']))
			{
                               
				$data['company_name'] = $this->config->get('config_name'); // store name
				$data['company_title'] = $this->config->get('config_title'); // store title
				$data['company_owner'] = $this->config->get('config_owner'); // store owner name
				$data['company_email'] = $this->config->get('config_email'); // store email
				$data['company_address'] = $this->config->get('config_address');//store address
				
				$this->load->model('material_received/purchase_order');
				$data['order_information'] = $this->model_material_received_purchase_order->view_order_details($order_id);
		
				
				$html = $this->load->view('material_received/mail_purchase_order.tpl',$data);
				
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
				$this->response->redirect($this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
			}
		}
	}
	
	/*--------------------Insert material_received order ends here----------------------------*/
    
        //\\###########################  Display Po Image created on 21-11-2019 By Vinay ###########################//\\
    public function dispaly_image(){
       //$this->load->model('materialrequest/purchase_order');
       $order_id = $this->request->post['order_id'];
    
         
       if ($order_id !='') {
            
            $sql = "SELECT pr_verification_image From oc_po_order where id = $order_id";
            $res = $this->db->query($sql)->row;
            if($res['pr_verification_image'] != '' && file_exists(DIR_UPLOAD."pr_varified_image/".$res['pr_verification_image'])){
                $res = '<img src="../system/upload/pr_varified_image/'.$res['pr_verification_image'].'" title="'.$order_id.' Linked PO" class"img_responsive" width="100%" height="100%" />';
            }else{
                $res = 'Linked PO Image not Found..';
}
            $msg = array('status' => 'success', 'responce' => $res);
        } else {
            $html = 'Order not found with specific detail.';
            $msg = array('status' => 'error', 'responce' => $html);
        }
        echo json_encode($msg);

    }
    public function download_purchase_order() {

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $order_id = $this->request->get['invoice_id'];
        $data['transaction_id'] = $this->request->get['o_id'];

        //print_r($transaction_id);  
        $this->load->model('material_received/purchase_order');
        // $data['order_list'] = $this->model_material_received_purchase_order->getList($filter_data);
        // echo "<pre>"; 	print_r($data['order_list']); echo "<pre>";exit;

        $data['order_info1'] = $this->model_material_received_purchase_order->view_order_details_for_created_invoice_b2b($order_id);
        $data['order_information'] = $this->model_material_received_purchase_order->view_order_details($order_id);
        //echo "<pre>"; 	print_r( $data['order_information']); echo "<pre>";exit;
        //$id_prefix=str_replace("/","_",$data['order_information']['order_info']['id_prefix']);
        $id_prefix = str_replace("_", "/", $data['order_info1']['order_info']['id_prefix']);
        // print_r($id_prefix); exit;
        $data['store_to_data'] = $this->model_material_received_purchase_order->get_to_store_data($data['order_information']['order_info']['store_id']);
        //echo "<pre>"; 	print_r($data['store_to_data']);echo "<pre>";exit;
        $data['created_po'] = $data['order_information']['order_info']['order_date'];
        $data['invoice_id'] = $order_id;
        $this->response->setOutput($this->load->view('material_received/purchase_order_print.tpl', $data));

        $html = $this->load->view('material_received/purchase_order_print.tpl', $data);
        require_once(DIR_SYSTEM . '/library/mpdf/mpdf.php');
        $mpdf = new mPDF('c', 'A3', '', '', 0, 0, 25, 10, 5, 7);

        $header = '<br/><div class="header" style="margin-top: 20px;">
        <div class="logo">
        <img style="float: right;margin-right: 40px;height: 30px;" src="../image/catalog/logo.png"  />
        </div>
        
        <img src="../image/letterhead_topline.png" style="height: 10px; width: 120% !important;;" />
    </div>';

        $header = '<div class="header" style="">
                  
<div class="logo" style="width: 100%;" >
<div style="padding-left: 50px;">
<img src="../image/letterhead_text.png" style="height: 40px; width: 121px;margin-top: 20px;marging-left: 100px;" />
<img src="../image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />
</div>
                         </div>
<img src="../image/letterhead_topline.png" style="height: 10px; width: 120% !important;" />

</div>';
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->SetHTMLHeader($header, 'O', false);

        $footer = '<div class="footer">
                       
                        <img src="../image/letterhead_bottomline.png" style="height: 10px; width: 150% !important;" />
                        <div class="address"><img src="../image/letterhead_footertext.png" style="width: 120% !important;" /> </div>'
                . '</div>';

        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->SetHTMLFooter($footer);

        $mpdf->SetDisplayMode('fullpage');

        $mpdf->list_indent_first_level = 0;

        $mpdf->WriteHTML($html);
        $supplier_name = str_replace('&', '-', $data['order_info1']['order_info']['first_name'] . '_' . $data['order_info1']['order_info']['last_name']);
        $filename = $supplier_name . '_' . $id_prefix . $order_id . '.pdf';

        $mpdf->Output($filename, 'D');
    }

}

?>