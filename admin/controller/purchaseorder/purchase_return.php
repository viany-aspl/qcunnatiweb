 <?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerPurchaseorderPurchaseReturn extends Controller
{
    public function index()
    {
		$this->document->setTitle("Purchase Return/Credit Note");
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$url = '';
        if (isset($this->request->get['filter_supplier'])) 
        {
			$url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
		}

		if (isset($this->request->get['filter_date_start'])) 
		{
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
        if (isset($this->request->get['filter_date_end'])) 
		{
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_status'])) 
		{
            $url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) 
		{
			$url .= '&page=' . $this->request->get['page'];
		}
                        
        $data['breadcrumbs'] = array();

                $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                );
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Return/Credit Note",
			'href' => $this->url->link('purchaseorder/purchase_return', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('purchaseorder/purchase_return/purchase_return_add', 'token=' . $this->session->data['token'] . $url, true);
		
		$this->load->model('purchaseorder/purchase_return');
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
		if (isset($this->request->get['filter_status'])) 
		{
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
		
		
		$data['order_list'] = $this->model_purchaseorder_purchase_return->getList($filter_data);
		
		$total_orders = $this->model_purchaseorder_purchase_return->getTotalOrders($filter_data);
		
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
		$pagination->url = $this->url->link('purchaseorder/purchase_return', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
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
		
		$data['user_group_id']=$user_info['user_group_id'];  
		$data['my_custom_text'] = "This is purchase order page.";
		$this->response->setOutput($this->load->view('purchaseorder/purchase_return_list.tpl', $data));
	} 
	public function download_excel()
    {
       
		$this->load->model('purchaseorder/purchase_return');
		
           
        if (isset($this->request->get['filter_supplier'])) 
		{
                            $filter_supplier =  $this->request->get['filter_supplier'];
		}

		if (isset($this->request->get['filter_date_start'])) 
		{
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
		
		
		$data1['order_list'] = $this->model_purchaseorder_purchase_return->getList($filter_data);
		
		header("Content-Type: application/vnd.ms-excel");
 header("Content-Disposition: attachment; filename=purchase_return".date("ymd").".xls");
$html= '<table class="table table-bordered table-hover">
              <thead>
                <tr>
					<th class="text-left">Invoice Date</th>	
                    <th class="text-left">Supplier Name</th>
					<th class="text-left">Supplier GSTN</th>
						
					<th class="text-left">Product Name</th>
					<th class="text-left">Quantity</th>
					<th class="text-left">Unit</th>
					<th class="text-left">Rate (without tax)</th>
					<th class="text-left">Sub Total</th>
					
					<th class="text-left">Discount</th>
					<th class="text-left">Tax title</th>
					<th class="text-left">Tax rate</th>
					<th class="text-left">Total Tax</th>
					<th class="text-left">Rebate & Discount / Freight Charge</th>
					<th class="text-left">Invoice Amount</th>
					<th class="text-left">Invoice Number</th>
					<th class="text-left">Warehouse</th>  
                    <th class="text-left">Create Date</th>
                </tr>
              </thead>
              <tbody>';
			  
			  foreach($data1['order_list'] as $order)
                    {
		
                    $html.='<tr>
						<td class="text-left">'.$order['valid_date'].'</td>
						<td class="text-left">'.$order['supplier'].'</td>
						<td class="text-left">'.$order['supplier_gstn'].'</td>
						<td class="text-left">'.$order['product_name'].'</td>
						<td class="text-left">'.$order['quantity'].'</td>
						<td class="text-left">'.$order['unit'].'</td>
						<td class="text-left">'.$order['rate'].'</td>
						<td class="text-left">'.$order['amount'].'</td>
						<td class="text-left">'.$order['discount'].'</td>
						<td class="text-left">'.$order['tax_type'].'</td>
						<td class="text-left">'.$order['tax_rate'].'</td>
						<td class="text-left">'.($order['cgst_value']+$order['sgst_value']).'</td>
						<td class="text-left">'.$order['rebate'].'</td>
						<td class="text-left">'.$order['grand_total'].'</td>
						<td class="text-left">'.$order['invoice_no'].'</td>
						<td class="text-left">'.$order['warehouse'].'</td>
						<td class="text-left">'.date('Y-m-d',strtotime($order['create_time'])).'</td>	
					</tr>';
		
                    }
					$html.='
			  </tbody>
			  </table>';
					echo $html;
			  
		/*
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Supplier Name',
        'Create Date',
        'Invoice Number',
        'Invoice Date',
		'Invoice Date',
		'Warehouse',
		'Product Name',
		'Rate',
		'Quantity',
		'Unit',
		'Discount',
		'Amount',
		'Rebate Amount',
		'Total'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    $row = 2;
    
    foreach($data1['order_list'] as $data)
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['supplier']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('Y-m-d',strtotime($data['create_time'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['invoice_no']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['valid_date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['warehouse']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['product_name']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['rate']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['quantity']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['unit']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['discount']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['amount']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['rebate']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['grand_total']);
        $row++;
    }

    

   //exit; 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Purchase_return_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	*/
	} 
	  
   public function purchase_return_add() 
    {
                $this->document->setTitle("Purchase Return/Credit Note"); 
                $order_id = $this->request->get['pono'];
                $data['column_left'] = $this->load->controller('common/column_left');
                $this->load->model('purchaseorder/purchase_order');
                
                $this->load->model('setting/store');               
                $data['stores'] = $this->model_setting_store->getStores();
                $this->load->model('purchaseorder/purchase_return');   
				$this->load->model('purchaseorder/purchase_order'); 				
                $data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
                
             
                if($this->request->server['REQUEST_METHOD'] == 'POST')
                {
                    $this->load->model('purchaseorder/purchase_order');                 
                  
					//print_r($this->request->post); 
					//exit; 
                    $submit_d=$this->model_purchaseorder_purchase_return->submit_purchase_return($this->request->post);
					if($submit_d>0)
					{
						$this->session->data['success']='Purchase Return added  Successfully';
                 
						$this->response->redirect($this->url->link('purchaseorder/purchase_return', 'token=' . $this->session->data['token'] . $url, true));
					}
					else
					{
						$this->session->data['error_warning']='Please check the Inventory at Warehouse !';
                 
						$this->response->redirect($this->url->link('purchaseorder/purchase_return', 'token=' . $this->session->data['token'] . $url, true));
					}
				}
               
                $data['ware_house']=$this->request->get['ware_house'];
                
                $data['footer'] = $this->load->controller('common/footer');
                $data['header'] = $this->load->controller('common/header');
                $data['breadcrumbs'] = array();

                $data['breadcrumbs'][] = array(
                                'text' => "Home",
                                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                                );
           
                
                $data['token']=$this->session->data['token'];
                $data['cancel'] = $this->url->link('purchaseorder/purchase_return', 'token=' . $this->session->data['token'] . $url, true);
               
                $created_po=$this->model_purchaseorder_purchase_order->check_po_invoice($order_id);
                
				$this->load->model('setting/store');               
                $data['stores'] = $this->model_setting_store->getWarehouses();

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
              
                $this->response->setOutput($this->load->view('purchaseorder/purchase_return_add.tpl',$data));
       
    }
   
	public function get_prn_list()
    {
	$store_id=$this->request->get['store_id'];
	$product_id=$this->request->get['product_id'];
	$this->load->model('purchaseorder/purchase_return');
	$get_prn_list_array=$this->model_purchaseorder_purchase_return->get_prn_list(array('store_id'=>$store_id,'product_id'=>$product_id));
	
	echo '<option value=""> SELECT PURCHASE INVOICE NUMBER</option>';
	foreach($get_prn_list_array as $get_prn_list2)
	{
		echo '<option value="',$get_prn_list2['po_id'].'">'.$get_prn_list2['invoice_no'].'-('.$get_prn_list2['product_name'].'-'.$get_prn_list2['quantity'].')-'.date('d M Y',strtotime($get_prn_list2['order_date'])).'</option>';
	}
	
    }
}

?>