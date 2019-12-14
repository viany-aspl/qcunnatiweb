 <?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerPurchaseorderPurchaseOrder extends Controller{
	public function supplier_outstanding_download_excel(){
        if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = '';
        }
       
        $this->load->model('report/Inventory');
        $data['orders'] = array();

        $filter_data = array(
            
            'filter_store' => $filter_store
            
        );

        

        $data['orders'] = array();
 
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Store Name',
        'Product ID',
        'Product Name',
        'Qnty'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    $results = $this->model_report_Inventory->getInventory_linked_product($filter_data);
		
    $row = 2;
    
    foreach($results as $data)
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Qnty']);
        
        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Inventory_linked_product_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
	public function supplier_outstanding_download_excel2()
	{
    
		$this->load->model('purchaseorder/purchase_order');
        if (isset($this->request->get['filter_supplier'])) 
		{
            $filter_supplier =  $this->request->get['filter_supplier'];
		}
		$filter_data=array(
                            'filter_supplier'=>$filter_supplier
				);
		
		include_once '../system/library/PHPExcel.php';
		include_once '../system/library/PHPExcel/IOFactory.php';
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->createSheet();
		
		$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

		$objPHPExcel->setActiveSheetIndex(0);

		// Field names in the first row
		$fields = array(
       
			'Supplier ID',
			'Supplier Name',
			'Email ID',
			'OutStanding'
		);
   
		$col = 0;
		foreach ($fields as $field)
		{
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
			$col++;
		}
    
		$row = 2;
		$get_all_supplier=$this->model_purchaseorder_purchase_order->get_all_supplier($filter_data);
	
		foreach($get_all_supplier as $get_all_supplier2)
		{     
			$outstanding=$this->model_purchaseorder_purchase_order->gettotaloutstanding(array('filter_supplier'=>$get_all_supplier2['id']))['total_outstanding'];
			
			$col = 0;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $get_all_supplier2['id']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $get_all_supplier2['first_name']." ".$get_all_supplier2['last_name']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $get_all_supplier2['email']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, number_format($outstanding, 2, '.', ''));
        
			$row++;
		}

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		// Sending headers to force the user to download the file
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Supplier_outstanding'.date('dMy').'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
        
    }
	public function approve_po_action()
    {
      
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
		//print_r($user_info);
		$data['user_group_id']=$user_info['user_group_id'];
		if(($user_info['user_group_id']==1) || ($user_info['user_group_id']==30) || ($user_info['user_group_id']==32))
			{
			}
			else
			{
				$this->session->data['error_warning']="You don't have access to approve page";
                $this->response->redirect($this->url->link('purchaseorder/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
		
			}
		if (isset($this->request->get['sid'])) 
		{
                           $data['sid']= $sid =  $this->request->get['sid'];
		}
		
		$this->load->model('purchaseorder/purchase_order');
		$data['order_list'] = $this->model_purchaseorder_purchase_order->approve_reject_po(array('sid'=>$sid,'approved_by'=>$this->user->getId(),'status'=>1));
		 
		if (isset($this->request->get['supplier_id'])) 
		{
            $data['supplier_id']= $supplier_id =  $this->request->get['supplier_id'];
		}
		if($this->request->get['email_send']=='save_email')
		{
			$file_path=$this->create_pdf_order($sid);
			$this->send_email($file_path,$supplier_id,$sid);
		}
        //print_r($this->request->post['filter_supplier']); exit;
		$this->session->data['success']="PO Approved Successfully";
        $this->response->redirect($this->url->link('purchaseorder/purchase_order/approve_po', 'token=' . $this->session->data['token'] . $url, true));
	}
	public function reject_po()
    {
      
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
		//print_r($user_info);
		$data['user_group_id']=$user_info['user_group_id'];
		if(($user_info['user_group_id']==1) || ($user_info['user_group_id']==30) || ($user_info['user_group_id']==32))
			{
			}
			else
			{
				$this->session->data['error_warning']="You don't have access to approve page";
                $this->response->redirect($this->url->link('purchaseorder/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
		
			}
		if (isset($this->request->get['sid'])) 
		{
                           $data['sid']= $sid =  $this->request->get['sid'];
		}
		if (isset($this->request->get['supplier_id'])) 
		{
                           $data['supplier_id']= $supplier_id =  $this->request->get['supplier_id'];
		}
		
		$this->load->model('purchaseorder/purchase_order');
		$data['order_list'] = $this->model_purchaseorder_purchase_order->approve_reject_po(array('sid'=>$sid,'approved_by'=>$this->user->getId(),'status'=>2));
		$this->session->data['success']="PO Rejected Successfully";
        $this->response->redirect($this->url->link('purchaseorder/purchase_order/approve_po', 'token=' . $this->session->data['token'] . $url, true));
	}
	public function approve_po()
    {
      
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
		//print_r($user_info);
		$data['user_group_id']=$user_info['user_group_id'];
		if(($user_info['user_group_id']==1) || ($user_info['user_group_id']==30) || ($user_info['user_group_id']==32))
			{
			}
			else
			{
				$this->session->data['error_warning']="You don't have access to approve page";
                $this->response->redirect($this->url->link('purchaseorder/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
		
			}
	$this->document->setTitle("Approve PO");
	$data['column_left'] = $this->load->controller('common/column_left');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
	$url = '';
        if (isset($this->request->get['filter_supplier'])) 
        {
	    $url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
	}
	if (isset($this->request->get['filter_po'])) 
        {
	    $url .= '&filter_po=' . $this->request->get['filter_po'];
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
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                );
			
			$data['breadcrumbs'][] = array(
			'text' => "Approve PO",
			'href' => $this->url->link('invoice/purchase_order/approve', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['approve'] = $this->url->link('purchaseorder/purchase_order/approve_po_action', 'token=' . $this->session->data['token'] . $url, true);
		$data['reject'] = $this->url->link('purchaseorder/purchase_order/reject_po', 'token=' . $this->session->data['token'] . $url, true);
		
		$this->load->model('purchaseorder/purchase_order');
		//print_r($this->request->get);exit;
                if (isset($this->request->get['page'])) {
                            $page = $this->request->get['page'];
                } 
                else {
                            $page = 1;
                }
                if (isset($this->request->get['filter_supplier'])) {
                            $filter_supplier =  $this->request->get['filter_supplier'];
		}
		if (isset($this->request->get['filter_po'])) {
                           $data['filter_po']= $filter_po =  $this->request->get['filter_po'];
		}
		if (isset($this->request->get['filter_date_start'])) {
                            $filter_date_start=$this->request->get['filter_date_start'];
		}
                else {
                            $filter_date_start=date('Y-m')."-01";
                }
                if (isset($this->request->get['filter_date_end'])) {
                            $filter_date_end=$this->request->get['filter_date_end'];
		}
                else {
                            $filter_date_end=date('Y-m-d');
                }
	if (isset($this->request->get['filter_status'])) {
                            $filter_status =  $this->request->get['filter_status'];
		}		

			
                $filter_data=array(
                            'filter_po'=>$filter_po,
                            'filter_date_start'=>$filter_date_start,
                            'filter_date_end'=>$filter_date_end,
                            'filter_supplier'=>$filter_supplier,
		'filter_status'=>$filter_status,
                            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                            'limit'   => $this->config->get('config_limit_admin')
                );
		
		
		$data['order_list'] = $this->model_purchaseorder_purchase_order->getList_for_approve($filter_data);
		
		$total_orders = $this->model_purchaseorder_purchase_order->getTotalOrders_for_approve($filter_data);
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		/*pagination*/
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchaseorder/purchase_order/approve_po', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		$data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
                
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
                
		$data['filter_id']=$filter_id ;
		$data['filter_date_start']=$filter_date_start ;
                	$data['filter_date_end']=$filter_date_end ;
                	$data['filter_supplier']=$filter_supplier ;
		$data['filter_status']=$filter_status;
                	$data['token']=$this->request->get['token'];


		
		$data['my_custom_text'] = "This is purchase order page.";
		$this->response->setOutput($this->load->view('purchaseorder/purchase_order_list_for_approvel.tpl', $data));
	}
    public function index()
    {
                
	$this->document->setTitle("Create PO");
	$data['column_left'] = $this->load->controller('common/column_left');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
	$url = '';
        if (isset($this->request->get['filter_supplier'])) 
        {
	    $url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
	}
if (isset($this->request->get['filter_po'])) 
        {
	    $url .= '&filter_po=' . $this->request->get['filter_po'];
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
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                );
			
			$data['breadcrumbs'][] = array(
			'text' => "Create PO",
			'href' => $this->url->link('invoice/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('purchaseorder/purchase_order/purchase_add', 'token=' . $this->session->data['token'] . $url, true);
		
		$this->load->model('purchaseorder/purchase_order');
		//print_r($this->request->get);exit;
                if (isset($this->request->get['page'])) {
                            $page = $this->request->get['page'];
                } 
                else {
                            $page = 1;
                }
                if (isset($this->request->get['filter_supplier'])) {
                            $filter_supplier =  $this->request->get['filter_supplier'];
		}
		if (isset($this->request->get['filter_po'])) {
                            $data['filter_po']=$filter_po =  $this->request->get['filter_po'];
		}

		if (isset($this->request->get['filter_date_start'])) {
                            $filter_date_start=$this->request->get['filter_date_start'];
		}
                else {
                            $filter_date_start=date('Y-m')."-01";
                }
                if (isset($this->request->get['filter_date_end'])) {
                            $filter_date_end=$this->request->get['filter_date_end'];
		}
                else {
                            $filter_date_end=date('Y-m-d');
                }
	if (isset($this->request->get['filter_status'])) {
                            $filter_status =  $this->request->get['filter_status'];
		}		

			
                $filter_data=array(
                            'filter_po'=>$filter_po,
                            'filter_date_start'=>$filter_date_start,
                            'filter_date_end'=>$filter_date_end,
                            'filter_supplier'=>$filter_supplier,
		'filter_status'=>$filter_status,
                            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                            'limit'   => $this->config->get('config_limit_admin')
                );
		
		
		$data['order_list'] = $this->model_purchaseorder_purchase_order->getList($filter_data);
		
		$total_orders = $this->model_purchaseorder_purchase_order->getTotalOrders($filter_data);
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		/*pagination*/
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchaseorder/purchase_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		$data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
                
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
                
		$data['filter_id']=$filter_id ;
		$data['filter_date_start']=$filter_date_start ;
                	$data['filter_date_end']=$filter_date_end ;
                	$data['filter_supplier']=$filter_supplier ;
		$data['filter_status']=$filter_status;
                	$data['token']=$this->request->get['token'];


		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($this->user->getId());
		//print_r($user_info);
		$data['user_group_id']=$user_info['user_group_id'];
		$data['user_id']=$this->user->getId();
		$data['my_custom_text'] = "This is purchase order page.";
		$this->response->setOutput($this->load->view('purchaseorder/purchase_order_list.tpl', $data));
	}
        public function purchase_invoice()
    {
                
	$this->document->setTitle("Invoice Update");
	$data['column_left'] = $this->load->controller('common/column_left');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
	$url = '';
        if (isset($this->request->get['filter_supplier'])) 
        {
	    $url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
	}
if (isset($this->request->get['filter_po'])) 
        {
	    $url .= '&filter_po=' . $this->request->get['filter_po'];
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
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                );
			
			$data['breadcrumbs'][] = array(
			'text' => "Invoice Update",
			'href' => $this->url->link('invoice/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('purchaseorder/purchase_order/purchase_add', 'token=' . $this->session->data['token'] . $url, true);
		
		$this->load->model('purchaseorder/purchase_order');
		//print_r($this->request->get);exit;
                if (isset($this->request->get['page'])) {
                            $page = $this->request->get['page'];
                } 
                else {
                            $page = 1;
                }
                if (isset($this->request->get['filter_supplier'])) {
                            $filter_supplier =  $this->request->get['filter_supplier'];
		}
		    if (isset($this->request->get['filter_po'])) {
                            $data['filter_po']=$filter_po =  $this->request->get['filter_po'];
		}

		if (isset($this->request->get['filter_date_start'])) {
                            $filter_date_start=$this->request->get['filter_date_start'];
		}
                else {
                            $filter_date_start=date('Y-m')."-01";
                }
                if (isset($this->request->get['filter_date_end'])) {
                            $filter_date_end=$this->request->get['filter_date_end'];
		}
                else {
                            $filter_date_end=date('Y-m-d');
                }
			

			
                $filter_data=array(
                            'filter_po'=>$filter_po,
                            'filter_date_start'=>$filter_date_start,
                            'filter_date_end'=>$filter_date_end,
                            'filter_supplier'=>$filter_supplier,
                            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                            'limit'   => $this->config->get('config_limit_admin')
                );
		
		
		$data['order_list'] = $this->model_purchaseorder_purchase_order->getinvoiceList($filter_data);
		
		$total_orders = $this->model_purchaseorder_purchase_order->getinvoiceTotalOrders($filter_data);
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		/*pagination*/
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchaseorder/purchase_order/purchase_invoice', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		$data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
                
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
                
		$data['filter_id']=$filter_id ;
		$data['filter_date_start']=$filter_date_start ;
                	$data['filter_date_end']=$filter_date_end ;
                	$data['filter_supplier']=$filter_supplier ;
                	$data['token']=$this->request->get['token'];
		$data['my_custom_text'] = "This is purchase order page.";
		$this->response->setOutput($this->load->view('purchaseorder/purchase_invoice_list.tpl', $data));
	}
	 
        public function purchase_payment()
    {
                
	$this->document->setTitle("Purchase Payment");
	$data['column_left'] = $this->load->controller('common/column_left');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
	$url = '';
        if (isset($this->request->get['filter_supplier'])) 
        {
	    $url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
	}
	   if (isset($this->request->get['filter_in_no'])) 
        {
	    $url .= '&filter_in_no=' . $this->request->get['filter_in_no'];
	}
	if (isset($this->request->get['filter_po'])) 
        {
	    $url .= '&filter_po=' . $this->request->get['filter_po'];
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
                'text' => 'Purchase Payment',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                );
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Payment",
			'href' => $this->url->link('purchase/purchase_order/purchase_payment', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('purchaseorder/purchase_order/purchase_add', 'token=' . $this->session->data['token'] . $url, true);
		
		$this->load->model('purchaseorder/purchase_order');
		//print_r($this->request->get);exit;
                if (isset($this->request->get['page'])) {
                            $page = $this->request->get['page'];
                } 
                else {
                            $page = 1;
                }
                if (isset($this->request->get['filter_supplier'])) {
                            $filter_supplier =  $this->request->get['filter_supplier'];
		}
		if (isset($this->request->get['filter_in_no'])) {
                            $filter_in_no =  $this->request->get['filter_in_no'];
		}
		if (isset($this->request->get['filter_po'])) {
                            $data['filter_po']=$filter_po =  $this->request->get['filter_po'];
		}
		
		if (isset($this->request->get['filter_date_start'])) {
                            $filter_date_start=$this->request->get['filter_date_start'];
		}
                else {
                            $filter_date_start=date('Y-m')."-01";
                }
                if (isset($this->request->get['filter_date_end'])) {
                            $filter_date_end=$this->request->get['filter_date_end'];
		}
                else {
                            $filter_date_end=date('Y-m-d');
                }
			

			
                $filter_data=array(
				'filter_po'=>$filter_po,
                            'filter_date_start'=>$filter_date_start,
                            'filter_date_end'=>$filter_date_end,
                            'filter_supplier'=>$filter_supplier,
							'filter_in_no'=>$filter_in_no,
                            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                            'limit'   => $this->config->get('config_limit_admin')
                );
		$data['noresult']='No Result Found';
		if((!empty($filter_supplier)) || (!empty($filter_in_no)))
		{
		$data['order_list'] = $this->model_purchaseorder_purchase_order->getpaymentList($filter_data);
		
		$total_orders11 = $this->model_purchaseorder_purchase_order->getpaymentTotalOrders($filter_data);
		$total_orders=$total_orders11['total_orders'];
		//echo $total_orders11['total_outstanding']; ;
		$data['total_outstanding']=$this->model_purchaseorder_purchase_order->gettotaloutstanding($filter_data)['total_outstanding'];
		$supplier_data=$this->model_purchaseorder_purchase_order->get_to_supplier_data($filter_supplier);
		$supplier_data2=explode('---',$supplier_data);
		//print_r($data['order_list']);
		$data['supplier_wallet_balance'] = $supplier_data2[7];
		}
		else
		{
			$data['noresult']='Please Select Supplier';
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success']; 

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}
		/*pagination*/
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchaseorder/purchase_order/purchase_payment', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		$data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
                
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
                
		$data['filter_id']=$filter_id ;
		$data['filter_in_no']=$filter_in_no ;
                	$data['filter_date_end']=$filter_date_end ;
                	$data['filter_supplier']=$filter_supplier ;
                	$data['token']=$this->request->get['token'];
		$data['my_custom_text'] = "This is purchase order page.";
		$this->response->setOutput($this->load->view('purchaseorder/purchase_payment_list.tpl', $data));
	}
	protected function msort($array, $key, $sort_flags = SORT_REGULAR) {
    if (is_array($array) && count($array) > 0) {
        if (!empty($key)) {
            $mapping = array();
            foreach ($array as $k => $v) {
                $sort_key = '';
                if (!is_array($key)) {
                    $sort_key = $v[$key];
                } else {
                    // @TODO This should be fixed, now it will be sorted as string
                    foreach ($key as $key_key) {
                        $sort_key .= $v[$key_key];
                    }
                    $sort_flags = SORT_STRING;
                }
                $mapping[$k] = $sort_key;
            }
            arsort($mapping, $sort_flags);
            $sorted = array();
            foreach ($mapping as $k => $v) {
                $sorted[] = $array[$k];
            }
            return $sorted;
        }
    }
    return $array;
}
	public function supplier_outstanding()
    {
                
	$this->document->setTitle("Supplier Outstanding");
	$data['column_left'] = $this->load->controller('common/column_left');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
	$url = '';
        if (isset($this->request->get['filter_supplier'])) 
        {
	    $url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
	}
	   if (isset($this->request->get['filter_in_no'])) 
        {
	    $url .= '&filter_in_no=' . $this->request->get['filter_in_no'];
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
                'text' => 'Purchase Payment',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                );
			
			$data['breadcrumbs'][] = array(
			'text' => "Supplier Outstanding",
			'href' => $this->url->link('purchase/purchase_order/supplier_outstanding', 'token=' . $this->session->data['token'] . $url, true)
		);

		$this->load->model('purchaseorder/purchase_order');
                if (isset($this->request->get['page'])) {
                            $page = $this->request->get['page'];
                } 
                else {
                            $page = 1;
                }
                if (isset($this->request->get['filter_supplier'])) {
                            $filter_supplier =  $this->request->get['filter_supplier'];
		}
		if (isset($this->request->get['filter_in_no'])) {
                            $filter_in_no =  $this->request->get['filter_in_no'];
		}
		if (isset($this->request->get['filter_date_start'])) {
                            $filter_date_start=$this->request->get['filter_date_start'];
		}
                else {
                            $filter_date_start=date('Y-m')."-01";
                }
                if (isset($this->request->get['filter_date_end'])) {
                            $filter_date_end=$this->request->get['filter_date_end'];
		}
                else {
                            $filter_date_end=date('Y-m-d');
                }
			

			
                $filter_data=array(
                            
                            'filter_date_start'=>$filter_date_start,
                            'filter_date_end'=>$filter_date_end,
                            'filter_supplier'=>$filter_supplier,
							'filter_in_no'=>$filter_in_no,
                            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                            'limit'   => $this->config->get('config_limit_admin')
                );
		$data['noresult']='No Result Found';
		
		$getallSupplier=$this->model_purchaseorder_purchase_order->getallSupplier($filter_data);
		$total_orders=count($getallSupplier);
		$data['wallet_balance']=$this->model_purchaseorder_purchase_order->getallSupplierWallet_Balance($filter_data);
		
		$a=0;
		foreach($getallSupplier as $getallSupplier2)
		{
			$get_outstanding=$this->model_purchaseorder_purchase_order->getoutstanding(array('filter_supplier'=>$getallSupplier2['supllier_id']));
			
			$actual_outstanding=$get_outstanding['total_outstanding']-$getallSupplier2['wallet_balance'];
			if($actual_outstanding!=0)
			{
			$data['order_list'][]=array(
							'id'=> $getallSupplier2['supllier_id'],
							'name'=> $getallSupplier2['supplier'],
							'outstanding'=> $get_outstanding['total_outstanding'],
							'wallet_balance'=>$getallSupplier2['wallet_balance'],
							'actual_outstanding'=>$actual_outstanding,
					);
			
			}
			else
			{
				$a++;
			}
			
		}
		
		$data['order_list'] = $this->msort($data['order_list'], array('actual_outstanding','outstanding','wallet_balance'));
		$data['order_list'] = $this->msort($data['order_list'], array('actual_outstanding'));
		//$data['order_list']=array_slice($data['order_list'], ($page - 1) * $this->config->get('config_limit_admin'), $this->config->get('config_limit_admin'),true);
		
		$total_orders=$total_orders-$a;
		
		$total=$this->model_purchaseorder_purchase_order->get_all_supplier_count($filter_data);
		
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		/*pagination*/
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchaseorder/purchase_order/supplier_outstanding', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		$data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
                
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
                
		$data['filter_id']=$filter_id ;
		$data['filter_in_no']=$filter_in_no ;
        $data['filter_date_end']=$filter_date_end ;
        $data['filter_supplier']=$filter_supplier ;
        $data['token']=$this->request->get['token'];
		
		$data['total_outstanding']=$total['total_outstanding'];
		
		$data['my_custom_text'] = "This is purchase order page.";
		$this->response->setOutput($this->load->view('purchaseorder/supplier_outstanding_list.tpl', $data));
	}
	
        public function getreamrks() { 
            
            $pono = $this->request->get['po_id']; 
           
            $this->load->model('purchaseorder/purchase_order');
			
            echo $submit_d=$this->model_purchaseorder_purchase_order->getremarks($pono);         
	
        }
        public function adjustpayment() { 
            
            $pono = $this->request->get['pono']; 
            $amount = $this->request->get['amount'];
            $this->load->model('purchaseorder/purchase_order');
			 $data['logged_user_data'] = $this->user->getId();
			
            $submit_d=$this->model_purchaseorder_purchase_order->adjustpayment($pono,$amount,'adjustment',$data['logged_user_data']);     
			if($submit_d>0)
			{
			$file_path=$this->create_pdf_for_payment($this->request->get['pono']);
			$this->send_payment_email($file_path,'',$this->request->get['pono']); 
	         
			//exit;
            $this->session->data['success']='Payment Adjustment is done Successfully';   
			}
			else
			{
				$this->session->data['error']='This entry is already adjusted';  
			}
            //$this->response->redirect($this->url->link('purchaseorder/purchase_order/purchase_payment_list', 'token=' . $this->session->data['token'] . $url, true));
                
            
        }
        public function create_pdf_for_payment($order_id)
        {
            
             //$data['column_left'] = $this->load->controller('common/column_left');
            // $data['footer'] = $this->load->controller('common/footer');
             //$data['header'] = $this->load->controller('common/header');
               
             
             $this->load->model('purchaseorder/purchase_order');
             $data['order_information'] = $this->model_purchaseorder_purchase_order->view_order_details_for_created_invoice_b2b($order_id);

             $data['store_to_data']=$this->model_purchaseorder_purchase_order->get_to_store_data($data['order_information']['order_info']['store_id']);
             $data['created_po']=$data['order_information']['order_info']['po_invoice_n'];
             $data['invoice_id']=$order_id;
	
	$data['supplier_name']=$data['order_information']['order_info']['first_name'].' '.$data['order_information']['order_info']['last_name'];
	$data['supplier_address']=$data['order_information']['order_info']['ADDRESS'];
	$data['supplier_ac']=$data['order_information']['order_info']['ACC_ID'];
	$data['supplier_ifsc']=$data['order_information']['order_info']['IFSC_CODE'];
	$data['amount']=$this->request->get['amount'];
	$data['tr_number']='NA'; 

	$data['invoice_amount']= $data['order_information']['order_info']['invoice_amount'];
              $data['invoice_number']= $data['order_information']['order_info']['invoice_no'];
	$data['invoice_date']= $data['order_information']['order_info']['invoice_date'];
	$data['bank_name']=$this->request->post['payment_bank'];
	$data['payment_method']=$this->request->post['payment_method'];

             //print_r($data['order_information']['order_info']);
             //$this->response->setOutput($this->load->view('purchaseorder/purchase_order_payment_print.tpl',$data));
            //exit;

             $html=$this->load->view('purchaseorder/purchase_order_payment_print.tpl',$data);
             require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             $mpdf = new mPDF('c','A3','','' , 0 , 0 , 25 , 10 , 5 , 7);
            
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
               $supplier_name=str_replace('&','-',$data['supplier_name']);
	 $supplier_name=str_replace(' ','-',$supplier_name);
                $filename=DIR_UPLOAD.'Supplier/'.$supplier_name. '_po_payment_'.$order_id.'.pdf';
               
                $mpdf->Output($filename,'F');
                return $filename;
             
           
               
        }
        public function send_payment_email($file_path,$supplier_id,$order_id)
        {
			$this->load->model('purchaseorder/purchase_order');
			
             			$data['order_information'] = $this->model_purchaseorder_purchase_order->view_order_details_for_created_invoice_b2b($order_id);
			//print_r($data['order_information']['order_info']['invoice_no']);
			
			$dbdata=$this->model_purchaseorder_purchase_order->get_to_supplier_data($data['order_information']['order_info']['supplier_id']);
                                	$dbdata2=explode('---',$dbdata);
			//print_r($dbdata2);exit;
                                	$mail = new PHPMailer();

				 $body = "<p>Dear ".$dbdata2[1].",
					<br/><br/>
					We would like to inform you that the following payment has been released .
					
					<br/><br/>
					Invoice number  : ".$data['order_information']['order_info']['invoice_no']."
					<br/><br/>
					Amount : ".$this->request->get['amount']."
					
					<br/><br/>
					Thanking you for your support and we look forward towards your continued support.
					<br/><br/>
					<strong>
					With Warm Regards,
					<br/>
					Account & Billing
					</strong>
					
					
				</p>";
				
				$mail->IsSMTP(); 
				$mail->Host       = "mail.akshamaala.in"; 
														   
				$mail->SMTPAuth   = false;                 
				$mail->SMTPSecure = "";                 
				$mail->Host       = "mail.akshamaala.in";      
				$mail->Port       = 25;                  
				$mail->Username   = "mis@akshamaala.in";  
				$mail->Password   = "mismis";            

				$mail->SetFrom('accounts@unnati.world', 'Account');

				$mail->AddReplyTo('accounts@unnati.world', 'Account');

				$mail->Subject    = "Payment update Mail";

				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

				$mail->MsgHTML($body);
				
				//to get the email of supplier
                                		
                                		$address = $dbdata2[4];
                                		//$address = 'vipin.kumar@aspltech.com';
                               		 //echo $file_path;
				//exit;
				$mail->AddAddress($address, $dbdata2[1]);
				$mail->AddBCC('pragya.singh@aspltech.com','pragya singh');
				$mail->AddCC('ravi.ranjan@unnati.world','Ravi Ranjan');
				$mail->AddCC('ashok.prasad@akshamaala.com','Ashok Prasad');
				$mail->AddBCC('subhash.jha@unnati.world','Subhash Jha'); 
				//$mail->AddAddress('vipin.kumar@aspltech.com', $dbdata2[1]);
				$file_to_attach = $file_path;

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

        
        
        
	 public function check_ware_house_quantity()
        {
            //print_r($this->request->post);exit;
            $total_product=count($this->request->post['product_id']);
            $ware_house=$this->request->post['ware_house'];
            $ship_to=$this->request->post['ship_to'];
            $grand_total=$this->request->post['grand_total'];
            for($a=0;$a<$total_product;$a++)
            {
               $product_id=$this->request->post['product_id'][$a];
               $product_name=$this->request->post['product_name'][$a];
               $p_qnty=$this->request->post['p_qnty'][$a];
               $p_price=$this->request->post['p_price'][$a];
               
               $this->load->model('partner/purchase_order');
               $data_qnty=$this->model_partner_purchase_order->check_ware_house_quantity($ware_house,$product_id,$p_qnty);
               $data_price=$this->model_partner_purchase_order->check_ware_house_price($ware_house,$product_id,$p_price);
               if($data_qnty=="0")
               {
                   echo 'There is not sufficent quantity of '.$product_name.' at ware house';
                   return;
               }
               if($data_price=="0")
               {
                   echo 'You can not enter the price less then the base price for '.$product_name;
                   return;
               }
               
            }
            $data_credit=$this->model_partner_purchase_order->check_ship_to_credit($ship_to,$grand_total);
            if($data_credit=="0")
            {
                   echo 'Amount exceed from allowed credit limit ';
                   return;
            }
            //product_id
            //$store_id = $this->request->get['store_id'];
            //$this->load->model('partner/purchase_order');
            //echo $data=$this->model_partner_purchase_order->get_to_store_data($store_id);
            
        }
        public function get_to_store_data()
        {
            $store_id = $this->request->get['store_id'];
            $this->load->model('purchaseorder/purchase_order');
            echo $data=$this->model_purchaseorder_purchase_order->get_to_store_data($store_id);
            
        }
         public function get_to_supplier_data()
        {
            $supplier_id = $this->request->get['supplier_id'];
            $this->load->model('purchaseorder/purchase_order');
           echo $data=$this->model_purchaseorder_purchase_order->get_to_supplier_data($supplier_id);
            
        }
	/*----------------------------order_invoice function starts here------------*/
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('partner/purchase_order');
			

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

			$results = $this->model_partner_purchase_order->getProducts($filter_data);

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
        
	public function user_autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('purchaseorder/purchase_order');
			

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			if (isset($this->request->get['store_id'])) {
				$filter_store = $this->request->get['store_id'];
			} else {
				$filter_store = '';
			}
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_name,
				'filter_store' => $filter_store,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_purchaseorder_purchase_order->getUsers($filter_data);

			foreach ($results as $result) { //print_r($result);
				
				$json[] = array(
					
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'mobile_number'      => $result['mobile_number']
                                        
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        
    public function purchase_add()
    {
                $this->document->setTitle("Purchase Order");
                $order_id = $this->request->get['order_id'];
                $data['column_left'] = $this->load->controller('common/column_left');
                $this->load->model('purchaseorder/purchase_order');
                
                $this->load->model('setting/store');               
                $data['stores'] = $this->model_setting_store->getStores();
                $this->load->model('purchaseorder/purchase_order');                
                $data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
                
             
                if($this->request->server['REQUEST_METHOD'] == 'POST')
                {
                    $this->load->model('purchaseorder/purchase_order');                 
					$this->load->model('user/user');                    
                    $this->request->post['create_by']=$this->user->getId();
					
                    $submit_d=$this->model_purchaseorder_purchase_order->submit_purchase_order($this->request->post);
		//print_r($this->request->post);exit;
                    //$po_number=$submit_d['po_no'];
                    $order_id=$submit_d;
                    $log=new Log('supplier-'.date('Y-m-d').'.log');
		$log->write($this->request->post);
                    if(($this->request->post['buttonvalue']=='save_email') && (isset($this->request->post['buttonvalue'])))
                    {
                       //$file_path=$this->create_pdf_order($order_id); 
                       //$this->send_email($file_path,$this->request->post['filter_supplier'],$order_id);
                        //print_r($this->request->post['filter_supplier']); exit;
                    }
                    
                    $this->session->data['success']='Purchase Order  Successfully PO Number : ASPL/BB/'.$submit_d;
                 
                    $this->response->redirect($this->url->link('purchaseorder/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
                }
               
                $data['ware_house']=$this->request->get['ware_house'];
                //$data['store_to']=$this->request->get['store_to'];
               
                $data['footer'] = $this->load->controller('common/footer');
                $data['header'] = $this->load->controller('common/header');
                $data['breadcrumbs'] = array();

                $data['breadcrumbs'][] = array(
                                'text' => "Home",
                                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                                );
           
                $data['breadcrumbs'][] = array(
                                'text' => "B2B Invoice",
                                'href' => $this->url->link('invoice/purchase_order/b2b', 'token=' . $this->session->data['token'] . $url, true)
                                );
                $data['token']=$this->session->data['token'];
                $data['cancel'] = $this->url->link('purchaseorder/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
               
                $created_po=$this->model_purchaseorder_purchase_order->check_po_invoice($order_id);
                $created_po="";
                
              //  $data['order_information'] = $this->model_purchaseorder_purchase_order->view_order_details_for_created_invoice($order_id);
                   //print_r($data['order_information']['products']);
               // $data['store_to_data']=$this->model_purchaseorder_purchase_order->get_to_store_data($data['order_information']['order_info']['store_id']);
               
                
                $data['order_id']=$order_id;
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
              
       
                $this->response->setOutput($this->load->view('purchaseorder/purchase_order_add.tpl',$data));
       
    }

    public function update_po_qnty()
    {
	$po_id=$this->request->get['po_id'];
	$old_qnty=$this->request->get['old_qnty'];
	$new_qnty=$this->request->get['new_qnty'];
	$rate=$this->request->get['rate'];
	$remarks=$this->request->get['remarks'];
	$amount=$rate*$new_qnty;

	$this->load->model('user/user');
	$user_info = $this->model_user_user->getUser($this->user->getId());
	//print_r($user_info );
	$data['user_group_id']=$user_info['user_group_id'];
	$this->load->model('purchaseorder/purchase_order');
	$this->model_purchaseorder_purchase_order->update_po_qnty($po_id,$old_qnty,$new_qnty,$user_info['user_id'],$amount,$remarks);
	echo '1';
    }
public function get_prn_list()
    {
	$store_id=$this->request->get['store_id'];
	$product_id=$this->request->get['product_id'];
	if($product_id==561)
	{
		//$product_id=357;
	}
	$this->load->model('purchaseorder/purchase_order');
	$get_prn_list_array=$this->model_purchaseorder_purchase_order->get_prn_list(array('store_id'=>$store_id,'product_id'=>$product_id));
	
	echo '<option value=""> SELECT PURCHASE REQUEST NUMBER</option>';
	foreach($get_prn_list_array as $get_prn_list2)
	{
		echo '<option value="',$get_prn_list2['po_id'].'">'.$get_prn_list2['po_id'].'-('.$get_prn_list2['product_name'].'-'.$get_prn_list2['quantity'].')-'.date('d M Y',strtotime($get_prn_list2['order_date'])).'</option>';
	}
	
    }
   
   public function purchase_invoice_add()
    {
                $this->document->setTitle("Purchase Invoice");
                $order_id = $this->request->get['pono'];
                $data['column_left'] = $this->load->controller('common/column_left');
                $this->load->model('purchaseorder/purchase_order');
                
                $this->load->model('setting/store');               
                $data['stores'] = $this->model_setting_store->getStores();
                $this->load->model('purchaseorder/purchase_order');                
                $data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
                
             
                if($this->request->server['REQUEST_METHOD'] == 'POST')
                {
					
                    $this->load->model('purchaseorder/purchase_order');    
					
					$toBeComparedDate = $this->request->post['filter_date'];
					$today = (new DateTime())->format('Y-m-d'); 
					$expiry = (new DateTime($toBeComparedDate))->format('Y-m-d');
			
					if(strtotime($today) >= strtotime($expiry))//strtotime($dataAuthentication['VDATE']) > time())
					{
						  
                        $submit_d=$this->model_purchaseorder_purchase_order->submit_purchase_invoice($order_id,$this->request->post);
						//print_r($this->request->post);exit; 
						if($submit_d==0)
						{
							$this->session->data['error']='Oops! Some error occur,Please try again.';
                 
							$this->response->redirect($this->url->link('purchaseorder/purchase_order/purchase_invoice', 'token=' . $this->session->data['token'] . $url, true));
							exit;
						}
						$supplier_id=$this->model_purchaseorder_purchase_order->getPODetailsbyID($order_id) ;			
						$this->send_invoice_email('',$supplier_id);	 
                        //exit;		
						$path = "../system/upload/Supplier/"; 
                        
                        $file_extensions= array("pdf","doc","docx","zip","rar",'jpeg','jpg','png');
                 
                         $file_name = @$_FILES['snapshot']['name'];
  
                        
                        if($file_name!="")
                        {
							$file_size =@$_FILES['snapshot']['size'];
                        	$file_tmp =@$_FILES['snapshot']['tmp_name'];
                        	$file_type=@$_FILES['snapshot']['type'];
                        	$arrrr=explode('.',$file_name); 
                        	$exttt=end($arrrr);
                        	$file_ext= strtolower($exttt);
                            if(in_array($file_ext, $file_extensions)) 
                            { 
                    
                                if(is_writable($path))
                                {
                                    //echo "yes";exit;
                                }
                                else 
                                {
                                    
                                }
								$new_file_name='invoice'.$submit_d.date('dmy')."_".date('his').".".$file_ext;
								$file_path=$path.$new_file_name;
								$move= move_uploaded_file($file_tmp,$file_path);
								if($move)
								{
                            
									$this->model_purchaseorder_purchase_order->update_file($submit_d,$new_file_name);
                               
								}
                      
							}
						}
						//exit;
						//$po_number=$submit_d['po_no'];
						$order_id=$submit_d;
                    
						if($this->request->post['buttonvalue']=='save_email')
						{
							//$file_path=$this->create_pdf_order($order_id);
							//$this->send_email($file_path,$this->request->post['filter_supplier']);
							//print_r($this->request->post['filter_supplier']); exit;
						}
						$this->session->data['success']='Purchase Invoice  Successfully';
                 
						$this->response->redirect($this->url->link('purchaseorder/purchase_order/purchase_invoice', 'token=' . $this->session->data['token'] . $url, true));
					}
					else
					{
						$this->session->data['error_warning']='Invoice date should be less then or equal today';
                 
						$this->response->redirect($this->url->link('purchaseorder/purchase_order/purchase_invoice', 'token=' . $this->session->data['token'] . $url, true));
					}
                    
                }
               
                $data['ware_house']=$this->request->get['ware_house'];
                //$data['store_to']=$this->request->get['store_to'];
               
                $data['footer'] = $this->load->controller('common/footer');
                $data['header'] = $this->load->controller('common/header');
                $data['breadcrumbs'] = array();

                $data['breadcrumbs'][] = array(
                                'text' => "Home",
                                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                                );
           
                $data['breadcrumbs'][] = array(
                                'text' => "B2B Invoice",
                                'href' => $this->url->link('invoice/purchase_order/b2b', 'token=' . $this->session->data['token'] . $url, true)
                                );
                $data['token']=$this->session->data['token'];
                $data['cancel'] = $this->url->link('purchaseorder/purchase_order/purchase_invoice', 'token=' . $this->session->data['token'] . $url, true);
               
                $created_po=$this->model_purchaseorder_purchase_order->check_po_invoice($order_id);
                $created_po="";
                //print_r($order_id);
                $data['order_information'] = $this->model_purchaseorder_purchase_order->view_order_details_for_created_invoice_b2b($order_id);
                  //print_r($data['order_information']);
                $data['store_to_data']=$this->model_purchaseorder_purchase_order->get_to_store_data($data['order_information']['order_info']['store_id']);
               
                $this->load->model('setting/store');               
                $data['stores'] = $this->model_setting_store->getOwnStores();//getStores();//

                $data['order_id']=$order_id;
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
              
       
                $this->response->setOutput($this->load->view('purchaseorder/purchase_invoice_add.tpl',$data));
       
    }
   
    /*----------------------------order_invoice function ends here--------------*/
   
        public function send_email($file_path,$supplier_id,$order_id)
        {
		$this->load->model('purchaseorder/purchase_order');
		$data['order_information'] = $this->model_purchaseorder_purchase_order->view_order_details_for_created_invoice_b2b($order_id);
		if(empty($this->request->post['product_id']))
		{
			$product_name=$data['order_information']['order_info']['product'];
		}
		else
		{
			$product_name=$this->model_purchaseorder_purchase_order->getProduct($this->request->post['product_id']);
		}
		if(empty($this->request->post['p_qnty']))
		{
			$this->request->post['p_qnty']=$data['order_information']['order_info']['Quantity'];
		}
		
		
		//print_r($data['order_information']['order_info']['invoice_no']);
			
		$dbdata=$this->model_purchaseorder_purchase_order->get_to_supplier_data($data['order_information']['order_info']['supplier_id']);
                            $dbdata2=explode('---',$dbdata);

		$mail_subject='ASPL PO : '.$dbdata2[1].'_ASPL/PO/'.$data['order_information']['order_info']['sid'].'_'.$product_name.'_'.$data['order_information']['order_info']['create_date']; 
                                $mail = new PHPMailer();

				$body = "<p>Dear Sir,
					<br/><br/>
					We are pleased to share the purchase order for the following material. 
					
					<br/><br/>
					Name of Product  : ".$product_name."
					<br/><br/>
					Quantity : ".$this->request->post['p_qnty']."
					<br/><br/>
					We look forward towards your acknowledgement and supply of the material as per the terms and conditions agreed.
					<br/><br/>
					<strong>
					With Warm Regards,
					<br/>
					Account & Billing
					</strong>
					<br/><br/>
					<span style='font-size:10px;'><i>
						This is an auto generated mail and please do not reply to this mail. In case of clarification please call accounts / billing team.
					</i></span>
					
				</p>";
				
				$mail->IsSMTP(); 
				$mail->Host       = "mail.akshamaala.in"; 
														   
				$mail->SMTPAuth   = false;                 
				$mail->SMTPSecure = "";                 
				$mail->Host       = "mail.akshamaala.in";      
				$mail->Port       = 25;                  
				$mail->Username   = "mis@akshamaala.in";  
				$mail->Password   = "mismis";            

				$mail->SetFrom(SET_FROM_ACCOUNT_EMAIL, SET_FROM_ACCOUNT_NAME);

				$mail->AddReplyTo(REPLY_TO_ACCOUNT_EMAIL, REPLY_TO_ACCOUNT_NAME);

				$mail->Subject    = $mail_subject;//"Po Raised Mail";

				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

				$mail->MsgHTML($body);
				
				//to get the email of supplier
                                		$this->load->model('purchaseorder/purchase_order');
				$dbdata=$this->model_purchaseorder_purchase_order->get_to_supplier_data($supplier_id);
                                		$dbdata2=explode('---',$dbdata);
                                		$address = $dbdata2[4];
                                		//$address = 'vipin.kumar@aspltech.com';
                               		 //echo $file_path;
				//exit;$dbdata2[1]
				$mail->AddAddress($address, $address);
				
				

				if(!empty(CC_EMAIL))
				{
					$ccemail = CC_EMAIL;
					$ccemail= explode(',', $ccemail);
					foreach ($ccemail as $value) {
						if(!empty($value)){				    
						$mail->AddCC($value,$value);}
					}
				}
					
				if(!empty(BCC_EMAIL))
				{
					$bccemail = BCC_EMAIL;
					$bccemail= explode(',', $bccemail);
					foreach ($bccemail  as $value) {
						if(!empty($value)){				    
						$mail->AddBCC($value,$value);}
					}
				}								
				
				$file_to_attach = $file_path;

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
        public function send_invoice_email($file_path,$supplier_id)
        {
		//print_r($this->request->post);
		//$this->request->post['filter_date']."
                                $mail = new PHPMailer();
				//$this->request->post['filter_date']
				$body = "<p>Dear Sir,
					<br/><br/>
					Please be updated that the invoice has been accepted. The same shall be processed as per agreed terms and conditions.
					
					<br/><br/>
					Invoice number  : ".$this->request->post['invoiceno']."
					<br/><br/>
					Amount : ".$this->request->post['grand_total']."
					<br/><br/>
					Date of Receipt : ".DATE('d-m-Y')."
					<br/><br/>
					We look forward towards your support on the subject.
					<br/><br/>
					<strong>
					With Warm Regards,
					<br/>
					Account & Billing
					</strong>
					<br/><br/>
					<span style='font-size:10px;'><i>
						This is an auto generated mail and please do not reply to this mail. In case of clarification please call accounts / billing team.
					</i></span>
					
				</p>";
				
				$mail->IsSMTP(); 
				$mail->Host       = "mail.akshamaala.in"; 
														   
				$mail->SMTPAuth   = false;                 
				$mail->SMTPSecure = "";                 
				$mail->Host       = "mail.akshamaala.in";      
				$mail->Port       = 25;                  
				$mail->Username   = "mis@akshamaala.in";  
				$mail->Password   = "mismis";            

				$mail->SetFrom('accounts@unnati.world', 'Account');

				$mail->AddReplyTo('accounts@unnati.world', 'Account');

				$mail->Subject    = "Invoice update Mail";

				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

				$mail->MsgHTML($body);
				
				//to get the email of supplier
                                		$this->load->model('purchaseorder/purchase_order');
				$dbdata=$this->model_purchaseorder_purchase_order->get_to_supplier_data($supplier_id);
                                		$dbdata2=explode('---',$dbdata);
                                		$address = $dbdata2[4];
                                		//$address = 'vipin.kumar@aspltech.com';
                               		 //echo $file_path;
				//exit;
				$mail->AddAddress($address, $address);
				$mail->AddBCC('pragya.singh@aspltech.com','pragya singh');
				$mail->AddCC('ravi.ranjan@unnati.world','Ravi Ranjan');
				$mail->AddCC('ashok.prasad@akshamaala.com','Ashok Prasad');
				$mail->AddBCC('subhash.jha@unnati.world','Subhash Jha');
				
				//$dbdata2[1]
				//$file_to_attach = $file_path;

				//$mail->AddAttachment($file_to_attach);
				
				if(!$mail->Send()) 
				{
				  echo "Mailer Error: " . $mail->ErrorInfo;
				} 
				else
				{
				/*
				  if(!unlink($file_to_attach))
				  {
					  echo ("Error deleting $file_to_attach");
				  }
				  else
				  {
					 echo ("Deleted $file_to_attach");
				  }
				*/
				}
        }
        
        public function create_pdf_order($order_id)
        {
            
             $data['column_left'] = $this->load->controller('common/column_left');
             $data['footer'] = $this->load->controller('common/footer');
             $data['header'] = $this->load->controller('common/header');
               
             
             $this->load->model('purchaseorder/purchase_order');
             $data['order_information'] = $this->model_purchaseorder_purchase_order->view_order_details_for_created_invoice_b2b($order_id);
			 $id_prefix=str_replace("/","_",$data['order_information']['order_info']['id_prefix']);
             $data['store_to_data']=$this->model_purchaseorder_purchase_order->get_to_store_data($data['order_information']['order_info']['store_id']);
             $data['created_po']=$data['order_information']['order_info']['po_invoice_n'];
             $data['invoice_id']=$order_id;
             //exit;
             //$this->response->setOutput($this->load->view('purchaseorder/purchase_order_print.tpl',$data));
            
             $html=$this->load->view('purchaseorder/purchase_order_print.tpl',$data);
             require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             $mpdf = new mPDF('c','A3','','' , 0 , 0 , 25 , 10 , 5 , 7);
            
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
               $supplier_name=str_replace('&','-',$data['order_information']['order_info']['first_name'].'_'.$data['order_information']['order_info']['last_name']);
	$supplier_name=str_replace(' ','-',$supplier_name);
                $filename=DIR_UPLOAD.'Supplier/'.$supplier_name. '_PO_'.$id_prefix.$order_id.'.pdf';
               
                $mpdf->Output($filename,'F');
                return $filename;
             
           
               
        }
        public function download_purchase_order()
        {
            
             $data['column_left'] = $this->load->controller('common/column_left');
             $data['footer'] = $this->load->controller('common/footer');
             $data['header'] = $this->load->controller('common/header');
               
             $order_id = $this->request->get['invoice_id'];
             $this->load->model('purchaseorder/purchase_order');
             $data['order_information'] = $this->model_purchaseorder_purchase_order->view_order_details_for_created_invoice_b2b($order_id);
	//print_r($data['order_information']['order_info']['id_prefix']);exit;
	$id_prefix=str_replace("/","_",$data['order_information']['order_info']['id_prefix']);
             $data['store_to_data']=$this->model_purchaseorder_purchase_order->get_to_store_data($data['order_information']['order_info']['store_id']);
             $data['created_po']=$data['order_information']['order_info']['po_invoice_n'];
             $data['invoice_id']=$order_id;
             $this->response->setOutput($this->load->view('purchaseorder/purchase_order_print.tpl',$data));
            
             $html=$this->load->view('purchaseorder/purchase_order_print.tpl',$data);
             require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             $mpdf = new mPDF('c','A3','','' , 0 , 0 , 25 , 10 , 5 , 7);
            
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
               $supplier_name=str_replace('&','-',$data['order_information']['order_info']['first_name'].'_'.$data['order_information']['order_info']['last_name']);
                 $filename=$supplier_name. '_PO_'.$id_prefix.$order_id.'.pdf';
               
                $mpdf->Output($filename,'D');
          
           
               
        } 

}

?>