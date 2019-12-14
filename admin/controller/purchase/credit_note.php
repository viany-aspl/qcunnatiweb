<?php
class ControllerPurchaseCreditNote extends Controller 
{
	public function manual() 
	{
		$this->load->model('purchase/credit_node');
		if($this->request->server['REQUEST_METHOD'] == 'POST')
        {
			$this->request->post['supplier_id']=$this->request->post['filter_supplier'];
			$this->request->post['percentage']='NA';
			$this->request->post['sgst']=$this->request->post['span_sgst_1'];
			$this->request->post['cgst']=$this->request->post['span_cgst_1'];
			$this->request->post['sub_total']=($this->request->post['grand_total']-$this->request->post['sgst']-$this->request->post['cgst']);
			$this->request->post['total']=$this->request->post['grand_total'];
			
			unset($this->request->post['filter_supplier']);
			unset($this->request->post['span_sgst_1']);
			unset($this->request->post['span_cgst_1']);
			unset($this->request->post['p_tax_type']);
			unset($this->request->post['product_hsn']);
			unset($this->request->post['buttonvalue']);
			unset($this->request->post['p_tax_type']);
			unset($this->request->post['p_tax_rate']);
			unset($this->request->post['p_amount']);
			unset($this->request->post['product_name']);
			unset($this->request->post['p_price']);
			unset($this->request->post['p_discount']);
			unset($this->request->post['span_cgst_type_']);
			unset($this->request->post['span_sgst_type_1']);
			
			
			//print_r($this->request->post); 
			//exit; 	
			$submit_d=$this->model_purchase_credit_node->insert_data_manual($this->request->post);
					if($submit_d>0)
					{
						$this->session->data['success']='Credit Note added  Successfully';
                 
						$this->response->redirect($this->url->link('purchase/credit_note/getlist', 'token=' . $this->session->data['token'] . $url, true));
					}
					else
					{
						$this->session->data['error_warning']='Oops! Some error occur!!';
                 
						$this->response->redirect($this->url->link('purchase/credit_note/getlist', 'token=' . $this->session->data['token'] . $url, true));
					}
		}
		$this->load->language('setting/store');

		$this->document->setTitle('Create Credit Note (Manual)');
	
		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => 'Credit Note',
			'href' => $this->url->link('purchase/credit_node/manual', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
	    
		$data['heading_title'] = 'Create Credit Node (Manual)';

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
        
        
		$data['cancel'] = $this->url->link('purchase/credit_note/getlist', 'token=' . $this->session->data['token'] . $url, true);
		$data['user_id']=$this->user->getId();
		$this->load->model('purchaseorder/purchase_order');
		$data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
		$data['token']=$this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('purchase/add_credit_note_manual.tpl', $data));
	}
	public function getlist()
    {
                
	$this->document->setTitle("Credit Note");
	$data['column_left'] = $this->load->controller('common/column_left');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
	$url = '';
        if (isset($this->request->get['filter_supplier'])) 
        {
	    $url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
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
			'text' => "Credit Note",
			'href' => $this->url->link('purchase/credit_note/getlist', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('purchase/credit_note', 'token=' . $this->session->data['token'] . $url, true);
		$data['add_manual'] = $this->url->link('purchase/credit_note/manual', 'token=' . $this->session->data['token'] . $url, true);
		$this->load->model('purchaseorder/purchase_order');
		$this->load->model('purchase/credit_node');
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
                            
                            'filter_date_start'=>$filter_date_start,
                            'filter_date_end'=>$filter_date_end,
                            'filter_supplier'=>$filter_supplier,
		'filter_status'=>$filter_status,
                            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                            'limit'   => $this->config->get('config_limit_admin')
                );
		
		
		$data['order_list'] = $this->model_purchase_credit_node->getList($filter_data);
		//print_r($data['order_list']);
		$total_orders = $this->model_purchase_credit_node->getTotalOrders($filter_data);
		
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
		$pagination->url = $this->url->link('purchase/credit_note/getlist', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
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
		$data['my_custom_text'] = "This is purchase order page.";
		$this->response->setOutput($this->load->view('purchase/credit_note_list.tpl', $data));
	}
	public function getlistdownload()
    {
        $this->load->model('purchaseorder/purchase_order');
		$this->load->model('purchase/credit_node');
		if (isset($this->request->get['filter_supplier'])) {
                            $filter_supplier =  $this->request->get['filter_supplier'];
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
                            'filter_supplier'=>$filter_supplier
                );
		
		
		$results = $this->model_purchase_credit_node->getList($filter_data);
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Cn Date',
        'Cn Number',
        'Supplier Name',
        'Product',
		'Percentage',
		'Sub total',
		'CGST',
		'SGST',
		'Round off',
		'Total Amount',
		'',
		'Purchase Invoice Date',
		'Purchase Invoice number',
		'Purchase Sub total',
		'Purchase Total Amount'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    
		
    $row = 2;
    
    foreach($results as $data)
    {  
		$col = 0;
		if(empty($data['invoice_date']))
		{
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['cn_invoice_date']);
		}
		else
		{
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['invoice_date']);
		}
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['cn_no']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['supplier']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['product']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['percentage']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['sub_total']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['cgst']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['sgst']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['round_off']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['grand_total']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, '');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['invoice_date']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $data['invoice_num']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $data['inv_sub_total']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $data['inv_grand_total']);
        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Credit_note_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
	public function index() 
	{
		$this->load->language('setting/store');

		$this->document->setTitle('Create Credit Note');

		$this->getform();
	}
       	
        protected function getform()  
        { 
		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => 'Credit Node',
			'href' => $this->url->link('purchase/credit_node', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
	    
		$data['heading_title'] = 'Create Credit Node';
		
		

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
        $this->load->model('purchase/credit_node');
        
		$data['cancel'] = $this->url->link('purchase/credit_note/getlist', 'token=' . $this->session->data['token'] . $url, true);
		$data['user_id']=$this->user->getId();
		$this->load->model('purchaseorder/purchase_order');
		$data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
		$data['token']=$this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('purchase/add_credit_node.tpl', $data));
	}
	public function search_order() 
	{
        $invoice_number=$this->request->get['invoice_number'];
		$supplier_id=$this->request->get['supplier_id'];
		$this->load->model('purchase/credit_node');
		$data=$this->model_purchase_credit_node->getInvoices($invoice_number,$supplier_id);
		//print_r($data);
		if(!empty($data))
		{
			$html='';
			if(count($data)>1)
			{
				$a=1;
				foreach($data as $order)
				{
				if($order['cn_status']=='0')
				{
				$html=$html.'<tr>
							
							<td class="text-left">'.$order['invoice_no'].'</td>
							<td class="text-left">'.$order['invoice_date'].'</td>
							<td class="text-left">'.$order['po_no'].'</td>
							<td class="text-left">'.$order['product_name'].'</td>
							<td class="text-left">'.$order['supplier_name'].'</td>
							<td class="text-left">
							<img id="select_img'.$a.'" src="view/image/processing_image.gif" style="height: 48px;width: 48px;display: none" ></img>
							<a herf="#" id="select_btn'.$a.'" style="cursor: anchor;" class="btn btn-primary" onclick="return select_order(`'.$order['invoice_no'].'`,'.$a.','.$order['po_no'].','.$order['supplier_id'].');" >SELECT</a></td>
							</tr>
							';
							$a++;
				}
				}
				echo $html;
			}
			else
			{
				//echo $orderdata=$this->select_order($invoice_number);
				echo '';
			}
			
		}
		else
		{
			echo $msg='No data found for this invoice number';
			//$this->response->setOutput(json_encode(array('cn_status'=>$data['cn_status'],'msg'=>$msg)));
		}
			
		
		
	}
	public function select_order($invoice_number='') 
	{ 
		if(empty($invoice_number))
		{
			
			$invoice_number=$this->request->get['invoice_number'];
		}
		if(empty($po_number))
		{
			
			$po_number=$this->request->get['po_number'];
		}
		if(empty($supplier_id))
		{
			
			$supplier_id=$this->request->get['supplier_id'];
		}
		//echo $invoice_number;
		$this->load->model('purchase/credit_node');
		$data=$this->model_purchase_credit_node->getInvoice($invoice_number,$po_number,$supplier_id);
		if(empty($invoice_number))
		{
			$data['count']='0';
			
		}
		else
		{
			$data['count']='1';
		}
		//print_r($data);
		if($data['cn_status']=='0')
		{
			$data['sgst_type']=str_replace('C','S',$data['sgst_type']);
			$this->response->setOutput(json_encode($data));
		}
		else
		{
			if(!empty($data))
			{
				$msg='Credit Note is already added for this invoice';
				$this->response->setOutput(json_encode(array('cn_status'=>$data['cn_status'],'msg'=>$msg)));
			}
			else
			{
				$msg='No data found for this invoice number';
				$this->response->setOutput(json_encode(array('cn_status'=>$data['cn_status'],'msg'=>$msg)));
			}
			
		}
		
	}
	public function insert_data()
	{
		$this->load->model('purchase/credit_node');
		//print_r($this->request->post);
		$data=$this->model_purchase_credit_node->insert_data($this->request->post);
		return $data;
	}	   
        
     
}