 <?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerPurchaseorderReport extends Controller{
    public function  index(){
        
        $this->load->language('geo/searchgeo');
       
        $data['heading_title'] = 'Report PO';
        
        if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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
        $this->getList();      
    }
    
    protected function getList() 
	{
       
        	$data['token'] = $this->session->data['token'];
			$this->document->setTitle('Report PO');
      
        	$url="";
       
            $url .= '&page=' . $this->request->get['page'];
            if($this->request->get['tab']=="1")
            {
                $data['tab1'] ="active";
                $data['tab2'] ="";
                $data['tab3'] ="";
                $data['tab4'] ="";
               
            }
            else if($this->request->get['tab']=="2")
            {
                $data['tab1'] ="";
                $data['tab2'] ="active";
                $data['tab3'] ="";
                $data['tab4'] ="";
                
            }
            else if(trim($this->request->get['tab'])=="3")
            {
                $data['tab1'] ="";
                $data['tab2'] ="";
                $data['tab3'] ="active";
                $data['tab4'] ="";
                
            }
            else if($this->request->get['tab']=="4")
            {
                $data['tab1'] ="";
                $data['tab2'] ="";
                $data['tab3'] ="";
                $data['tab4'] ="active";
                
            }
       
			else
			{
             $data['tab1'] ="active";
                $data['tab2'] ="";
                $data['tab3'] ="";
                $data['tab4'] ="";
                
			}
        
        
			if (isset($this->request->get['filter_date_start'])) 
			{
				$filter_date_start = $this->request->get['filter_date_start'];
			} 
			else 
			{
				$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			}

			if (isset($this->request->get['filter_date_end'])) 
			{
				$filter_date_end = $this->request->get['filter_date_end'];
			} 
			else 
			{
				$filter_date_end = date('Y-m-d');
			}

			if (isset($this->request->get['filter_store'])) 
			{
				$filter_store = $this->request->get['filter_store'];
			} 
			else 
			{
				$filter_store = null;
			}
		
			if (isset($this->request->get['page'])) 
			{
				$page = $this->request->get['page'];
			} 
			else 
			{
		 		$page = 1;
			}
                          
			if (isset($this->request->get['page2'])) 
			{
				$page2 = $this->request->get['page2'];
			} 
			else 
			{
		 		$page2 = 1;
			}
		
			if (isset($this->request->get['page3'])) 
			{
				$page3 = $this->request->get['page3'];
			} 
			else 
			{
		 		$page3 = 1;
			}
			if (isset($this->request->get['page4'])) 
			{
				$page4 = $this->request->get['page4'];
			} 
			else 
			{
		 		$page4 = 1;
			}
		$filter_data = array(
                                          'filter_supplier'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_status'=>'0',
			'start'                      => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data2 = array(
         			'filter_supplier'	             => $filter_store,
			'filter_date_start'	     => '2010-01-01',
			'filter_date_end'	     => 'Y-m-d',
			
			'start'                      => ($page2 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data3 = array(
                                         'filter_supplier'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                  
			'start'                      => ($page3 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data4 = array(
                                         'filter_supplier'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                      
			'start'                      => ($page4 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		
		
        $this->load->model('purchaseorder/purchase_order'); 	
		/***********************************   Open PO ***********************************************/
		  

		  
                	  $data['order_list'] = $this->model_purchaseorder_purchase_order->getList($filter_data);
		
					  $order_total = $this->model_purchaseorder_purchase_order->getTotalOrders($filter_data);
		   
         
                  
/********************************* OPEN PO end  ****************************************/ 
   		if($this->request->get['filter_store']!="")
   		{       
                

/********************************* Pending Invoice Start  ****************************************/                                           

		$data['order_list2'] = $this->model_purchaseorder_purchase_order->getpaymentList($filter_data2);
		
		$order_total2_1 = $this->model_purchaseorder_purchase_order->getpaymentTotalOrders($filter_data2);
        $order_total2 = $order_total2_1['total_orders'];
        $data['total_pending_invoice_amount']=$order_total2_1['total_outstanding'];   

/********************************* Pending Invoice End  ****************************************/                     
 /*****************************************Paid Invoice Start**********************************************/               
        $data['order_list3'] = $this->model_purchaseorder_purchase_order->getpaidinvoiceList($filter_data3);
		$order_total3_1=$this->model_purchaseorder_purchase_order->getpaidinvoicelisttotal($filter_data3);
		$order_total3 = $order_total3_1['total_orders'];
        $data['total_payment_amount']=$order_total3_1['total_payment_amount'];

/********************************************Paid Invoice End*********************************************/                  
       
            
          
/***************************************************Ledger Start******************************/
        
            $data['order_list4'] = $this->model_purchaseorder_purchase_order->get_ledger($filter_data4);
			$t4=$this->model_purchaseorder_purchase_order->get_ledger_total($filter_data4);
			$order_total4 = $t4['total'];
			$data['total_debit'] = $t4['total_debit'];
			$data['total_credit'] = $t4['total_credit'];
			
			$ledger_opening_balance=$this->model_purchaseorder_purchase_order->get_ledger_opening_balance($filter_data4);
			//print_r($ledger_opening_balance);
			$data['opening_balance']=$ledger_opening_balance['total_debit']-$ledger_opening_balance['total_credit'];
/******************************************Ledger END******************************************/
	} 
		$url = '';
        if (isset($this->request->get['filter_store'])) 
		{
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		else
		{
			$data['text_no_results']='Please Select Supplier';
		}

		if (isset($this->request->get['filter_date_start'])) 
		{
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) 
		{
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
        if (isset($this->request->get['page'])) 
		{
			$url .= '&page=' . $this->request->get['page'];
		}
     
		if (isset($this->request->get['page2'])) 
		{
			$url .= '&page2=' . $this->request->get['page2'];
		}
		if (isset($this->request->get['page3'])) 
		{
			$url .= '&page3=' . $this->request->get['page3'];
		}
		if (isset($this->request->get['page4'])) 
		{
			$url .= '&page4=' . $this->request->get['page4'];
		}
		
     
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('purchaseorder/report', 'token=' . $this->session->data['token'] . $url . '&page={page}&tab=1', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
           
            
          //$order_total2=0;
		$pagination2 = new Pagination();
		$pagination2->total = $order_total2;
		$pagination2->page = $page2;
		$pagination2->limit = $this->config->get('config_limit_admin');
		$pagination2->url = $this->url->link('purchaseorder/report', 'token=' . $this->session->data['token'] . $url . '&page2={page}&tab=2', 'SSL');
		//print_r($pagination2->render());
		$data['pagination2'] = $pagination2->render(); 

		$data['results2'] = sprintf($this->language->get('text_pagination'), ($order_total2) ? (($page2 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page2 - 1) * $this->config->get('config_limit_admin')) > ($order_total2 - $this->config->get('config_limit_admin'))) ? $order_total2 : ((($page2 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total2, ceil($order_total2 / $this->config->get('config_limit_admin')));
            
            
           
		$pagination3 = new Pagination();
		$pagination3->total = $order_total3;
		$pagination3->page = $page3;
		$pagination3->limit = $this->config->get('config_limit_admin');
		$pagination3->url = $this->url->link('purchaseorder/report', 'token=' . $this->session->data['token'] . $url . '&page3={page}&tab=3', 'SSL');

		$data['pagination3'] = $pagination3->render();

		$data['results3'] = sprintf($this->language->get('text_pagination'), ($order_total3) ? (($page3 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page3 - 1) * $this->config->get('config_limit_admin')) > ($order_total3 - $this->config->get('config_limit_admin'))) ? $order_total3 : ((($page3 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total3, ceil($order_total3 / $this->config->get('config_limit_admin')));
         
            
          
		$pagination4 = new Pagination();
		$pagination4->total = $order_total4;
		$pagination4->page = $page4;
		$pagination4->limit = $this->config->get('config_limit_admin');
		$pagination4->url = $this->url->link('purchaseorder/report', 'token=' . $this->session->data['token'] . $url . '&page4={page}&tab=4', 'SSL');

		$data['pagination4'] = $pagination4->render(); 

		$data['results4'] = sprintf($this->language->get('text_pagination'), ($order_total4) ? (($page4 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page4 - 1) * $this->config->get('config_limit_admin')) > ($order_total4 - $this->config->get('config_limit_admin'))) ? $order_total4 : ((($page4 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total4, ceil($order_total4 / $this->config->get('config_limit_admin')));
         
            
            
                            
                     

      
      $data['stores'] = $this->model_purchaseorder_purchase_order->getSuppliers();
      $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Report PO',
			'href' => $this->url->link('purchaseorder/report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

                            $data['entry_date_start'] = 'Start date';
		$data['entry_date_end'] = 'End date';
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
          		$this->response->setOutput($this->load->view('purchaseorder/report.tpl', $data));

        

    }
    
       public function open_po_download()
	{
        		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 'null';
		}
	$filter_data = array(
                                          'filter_supplier'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_status'=>'0'
		);
        $data['orders'] = array();
 

	$file_name="open_po_".date('dMy').'.xls';
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

	$print_data='<table class="table table-bordered">
                <thead>
                    <tr>
                    <td class="text-left">Supplier Name</td>
		<td class="text-left">PO Date</td>
                    <td class="text-left">PO Number</td>
                    				  
                    
                     <td class="text-left">Product Name</td>
                      <td class="text-left">Quantity</td>
                    <td class="text-left">Delivery Address</td>
                    <td class="text-left">Status</td>
                    
                </tr>
                </thead>
            <tbody>';
             $this->load->model('purchaseorder/purchase_order');
    	$data['order_list'] = $this->model_purchaseorder_purchase_order->getList($filter_data);
              foreach($data['order_list'] as $order)
    	{
	if($order['status']=='0')
	{
		$status='PO Raised';
	}
	if($order['status']=='1')
	{
		$status='PO Invoiced';
	}
	if($order['status']=='2')
	{
		$status='Invoice Paid';
	}
              $print_data=$print_data.'<tr>
	        <td class="text-left">'.$order['supplier'].'</td>
		<td class="text-left">'.$order['create_date'].'</td>	
	          <td class="text-left">'.$order['id_prefix'].$order['sid'].'</td>
                        
                        
                        <td class="text-left">'.$order['product'].'</td>
                        <td class="text-left">'.$order['Quantity'].'</td>
                        <td class="text-left">'.$order['delivery_address'].'</td>
                        <td class="text-left">'.$status.'</td>
              </tr>';           
              }
           
            $print_data=$print_data.'</tbody>
          </table>';
           echo $print_data;
	
    }
public function pending_invoice_download()
	{
        		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 'null';
		}
	$filter_data2 = array(
         			'filter_supplier'	             => $filter_store,
			'filter_date_start'	     => '2010-01-01',
			'filter_date_end'	     => 'Y-m-d'
		);
        $data['orders'] = array();
		/*
		$this->load->model('purchaseorder/purchase_order');
    	$data['order_list'] = $this->model_purchaseorder_purchase_order->getpaymentList($filter_data2);
		$fields = array(
			 'Supplier Name',
       			 'PO Date',
			
        			 'Invoice Date',
					 'PO Number',
        			'Invoice Number',
       			 
			'Product Name',
			'Total Amount',
			'Delivery Address',
			'Status'
    		);
                $fileIO = fopen('php://memory', 'w+');
		fputcsv($fileIO, $fields,';');
		
        foreach($data['order_list'] as $order)
    	{
			if($order['status']=='0')
			{
				$status='PO Raised';
			}
			if($order['status']=='1')
			{
				$status='PO Invoiced';
			}
			if($order['status']=='2')
			{
				$status='Invoice Paid';
			}
                        
                        $fdata=array(
                            $order['supplier'],
                            date('d-m-Y',strtotime($order['po_date'])),
                            date('d-m-Y',strtotime($order['invoice_date'])),
                            $order['id_prefix'].$order['sid'],
                            $order['invoice_no'],
                            $order['payment_date'],
                            $order['payment_bank'],
                            $order['bank_tr_no'],
							$order['product'],
							$order['amount'],
							$order['delivery_address'],
							$status
                            );
			 fputcsv($fileIO,  $fdata,";");
			
                        
		}
		
           fseek($fileIO, 0);
             
           
    		header('Content-Type: application/csv');
			$file_name="pending_invoice_".date('dMy').'.xls';
    		header("Content-Disposition: attachment;filename=".$file_name);
    		header('Cache-Control: max-age=0');
                    fpassthru($fileIO);  
                    fclose($fileIO);
					*/
	
	$file_name="pending_invoice_".date('dMy').'.xls';
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

	$print_data='<table class="table table-bordered">
                <thead>
                    <tr>
	             <td class="text-left">Supplier Name</td>
	      <td class="text-left">PO Date</td>
	      <td class="text-left">Invoice Date</td>
                    <td class="text-left">PO Number</td>
                    <td class="text-left">Invoice Number</td>
                    				  
                    
                    <td class="text-left">Product Name</td>
                    
	      <td class="text-left">Total Amount</td>
                    <td class="text-left">Delivery Address</td>
                    <td class="text-left">Status</td>
                    </tr>  
                </thead>
            <tbody>';
             $this->load->model('purchaseorder/purchase_order');
    	$data['order_list'] = $this->model_purchaseorder_purchase_order->getpaymentList($filter_data2);
              foreach($data['order_list'] as $order)
    	{
	if($order['status']=='0')
	{
		$status='PO Raised';
	}
	if($order['status']=='1')
	{
		$status='PO Invoiced';
	}
	if($order['status']=='2')
	{
		$status='Invoice Paid';
	}
              $print_data=$print_data.'<tr>
	        <td class="text-left">'.$order['supplier'].'</td>
		<td class="text-left">'.date('d-m-Y',strtotime($order['po_date'])).'</td>
		<td class="text-left">'.date('d-m-Y',strtotime($order['invoice_date'])).'</td>	
		<td class="text-left">'.$order['id_prefix'].$order['sid'].'</td>
                        <td class="text-left">'.$order['invoice_no'].'</td>
                        
                        
                        <td class="text-left">'.$order['product'].'</td>
                       
	          <td class="text-left">'.$order['amount'].'</td>
                        <td class="text-left">'.$order['delivery_address'].'</td>
                        <td class="text-left">'.$status.'</td>
              </tr>';           
              }
           
            $print_data=$print_data.'</tbody>
          </table>';
           echo $print_data;
		   
	
    }
public function paid_invoice_download()
	{


        		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 'null';
		}
	$filter_data3 = array(
                                         'filter_supplier'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);
        $data['orders'] = array();
 
	$this->load->model('purchaseorder/purchase_order');
    	$data['order_list'] = $this->model_purchaseorder_purchase_order->getpaidinvoiceList($filter_data3);
	$fields = array(
			 'Supplier Name',
       			 'PO Date',
			
        			 'Invoice Date',
					 'PO Number',
        			'Invoice Number',
       			 'Paid Date',
			'Paid Bank',
			'Bank Tr. No.',
			'Product Name',
			'Total Amount',
			'Delivery Address',
			'Status'
    		);
                $fileIO = fopen('php://memory', 'w+');
		fputcsv($fileIO, $fields,',');
		
        foreach($data['order_list'] as $order)
    	{
			if($order['status']=='0')
			{
				$status='PO Raised';
			}
			if($order['status']=='1')
			{
				$status='PO Invoiced';
			}
			if($order['status']=='2')
			{
				$status='Invoice Paid';
			}
                        
                        $fdata=array(
                            $order['supplier'],
                            date('d-m-Y',strtotime($order['po_date'])),
                            date('d-m-Y',strtotime($order['invoice_date'])),
                            $order['id_prefix'].$order['sid'],
                            $order['invoice_no'],
                            $order['payment_date'],
                            $order['payment_bank'],
                            $order['bank_tr_no'],
							$order['product'],
							$order['amount'],
							$order['delivery_address'],
							$status
                            );
			 fputcsv($fileIO,  $fdata,",");
			
                        
		}
		
           fseek($fileIO, 0);
             
           
    		header('Content-Type: application/csv');
			$file_name="paid_invoice_".date('dMy').'.csv';
    		header("Content-Disposition: attachment;filename=".$file_name);
    		header('Cache-Control: max-age=0');
                    fpassthru($fileIO);  
                    fclose($fileIO);
	
	/*
	$print_data='<table class="table table-bordered">
                <thead>
                    <tr>
	             <td class="text-left">Supplier Name</td>
	      <td class="text-left">PO Date</td>
	      <td class="text-left">Invoice Date</td>
                    <td class="text-left">PO Number</td>
                    <td class="text-left">Invoice Number</td>
                     <td class="text-left">Paid Date</td>			  
                    <td class="text-left">Paid Bank</td>
	      <td class="text-left">Bank Tr. No.</td>				  
                    
                    <td class="text-left">Product Name</td>
                    
	      <td class="text-left">Total Amount</td>
                    <td class="text-left">Delivery Address</td>
                    <td class="text-left">Status</td>
                    </tr>  
                </thead>
            <tbody>';
             $this->load->model('purchaseorder/purchase_order');
    	$data['order_list'] = $this->model_purchaseorder_purchase_order->getpaidinvoiceList($filter_data3);
              foreach($data['order_list'] as $order)
    	{
	if($order['status']=='0')
	{
		$status='PO Raised';
	}
	if($order['status']=='1')
	{
		$status='PO Invoiced';
	}
	if($order['status']=='2')
	{
		$status='Invoice Paid';
	}
              $print_data=$print_data.'<tr>
	        <td class="text-left">'.$order['supplier'].'</td>
		<td class="text-left">'.date('d-m-Y',strtotime($order['po_date'])).'</td>
		<td class="text-left">'.date('d-m-Y',strtotime($order['invoice_date'])).'</td>	
		<td class="text-left">'.$order['id_prefix'].$order['sid'].'</td>
                        <td class="text-left">'.$order['invoice_no'].'</td>
                        <td class="text-left">'.$order['payment_date'].'</td>
                        <td class="text-left">'.$order['payment_bank'].'</td>
	          <td class="text-left">'.$order['bank_tr_no'].'</td>
                        
                        <td class="text-left">'.$order['product'].'</td>
                       
	          <td class="text-left">'.$order['amount'].'</td>
                        <td class="text-left">'.$order['delivery_address'].'</td>
                        <td class="text-left">'.$status.'</td>
              </tr>';           
              }
           
            $print_data=$print_data.'</tbody>
          </table>';
$file_name="paid_invoice_".date('dMy').'.xls';
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
           echo $print_data;
		   */
	
    }
	public function leadger_download_excel()
	{
        		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 'null';
		}
		$filter_data4 = array(
                                         'filter_supplier'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);
        $data['orders'] = array();
 
		$this->load->model('purchaseorder/purchase_order');
    	$data['order_list'] = $this->model_purchaseorder_purchase_order->get_ledger($filter_data4);
		$ledger_opening_balance=$this->model_purchaseorder_purchase_order->get_ledger_opening_balance($filter_data4);
		$opening_balance=$ledger_opening_balance['total_debit']-$ledger_opening_balance['total_credit'];
		$fields = array(
			 'Date',
			
        			 'Transaction Type',
					 'Tr Number/Invoice Number',
        			'Debit',
       			 'Credit',
			'Invoice Status'
    		);
                $fileIO = fopen('php://memory', 'w+');
		fputcsv($fileIO, $fields,',');
		
				if($opening_balance>0) 
				{ 
					
					$totaldebit=number_format((float)$opening_balance, 2, '.', ''); 
				}
				else 
				{ 
					$totaldebit='0.00'; 
				}
				if($opening_balance<0) 
				{ 
					
					$totalcredit=number_format((float)$opening_balance, 2, '.', ''); 
				}
				else 
				{ 
					$totalcredit='0.00'; 
				}
				$fdata1=array(
                        date('d-m-Y',strtotime($filter_date_start)),
                        'Opening Balance',
                        'NA',
                        number_format((float)$totaldebit, 2, '.', ''),
                        number_format((float)$totalcredit, 2, '.', ''),
                        ''
                            );
			fputcsv($fileIO,  $fdata1,";");
        foreach($data['order_list'] as $order)
    	{
	
			if($order['invoice_status']=='1')
			{
				$status='Un-Paid';
			}
			if($order['invoice_status']=='2')
			{
				$status='Paid';
			}
			if($order['tr_number']!='') { $tr_number=$order['tr_number']; } else { $tr_number='NA'; }           
                        $fdata=array(
                            date('d-m-Y',strtotime($order['tr_date'])),
                            $order['tr_type'],
                            $tr_number,
                            $order['total_debit'],
                            $order['total_credit'],
                            
							$status
                            );
			 fputcsv($fileIO,  $fdata,";");
			
            $totaldebit=$totaldebit+$order['total_debit'];
            $totalcredit=$totalcredit+$order['total_credit'];           
		}
			$fdata2=array(
                        '',
                        '',
                        'Total :',
                        number_format((float)$totaldebit, 2, '.', ''),
                        number_format((float)$totalcredit, 2, '.', ''),
                        ''
                            );
			fputcsv($fileIO,  $fdata2,",");
			 
			$fdata3=array(
                        '',
                        '',
                        'Liability :',
                        number_format((float)($totaldebit-$totalcredit), 2, '.', ''),
                        '',
                        ''
                            );
			fputcsv($fileIO,  $fdata3,";");
		
		
           fseek($fileIO, 0);
             
           
    		header('Content-Type: application/csv');
			$file_name="supplier_leadger_".date('dMy').'.csv';
    		header("Content-Disposition: attachment;filename=".$file_name);
    		header('Cache-Control: max-age=0');
                    fpassthru($fileIO);  
                    fclose($fileIO);

	/*
	$file_name="supplier_leadger_".date('dMy').'.xls';
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

	$print_data='<table class="table table-bordered">
                <thead>
                    <tr>
                <th class="text-left">Date</th>
                
                <th class="text-right">Transaction Type</th>
                <th class="text-right">Tr Number/Invoice Number</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
				<th class="text-right">Invoice Status</th>
              </tr> 
                </thead>
            <tbody>';
             $this->load->model('purchaseorder/purchase_order');
    	$data['order_list'] = $this->model_purchaseorder_purchase_order->get_ledger($filter_data4);
		$ledger_opening_balance=$this->model_purchaseorder_purchase_order->get_ledger_opening_balance($filter_data4);
		$opening_balance=$ledger_opening_balance['total_debit']-$ledger_opening_balance['total_credit'];
		$print_data=$print_data.'<tr>
                <td class="text-right">'.date('d-m-Y',strtotime($filter_date_start)).'</td>
                
                <td class="text-right">Opening Balance</td>
                <td class="text-right">NA</td>
                <td class="text-right">';
				if($opening_balance>0) 
				{ 
					$print_data=$print_data.number_format((float)$opening_balance, 2, '.', ''); 
					$totaldebit=$opening_balance; 
				}
				else 
				{ 
					$print_data=$print_data.'0.00'; 
				}
				$print_data=$print_data.'</td>
                <td class="text-right">';
				if($opening_balance<0) 
				{ 
					$print_data=$print_data.number_format((float)$opening_balance, 2, '.', ''); 
					$totalcredit=$opening_balance; 
				}
				else 
				{ 
					$print_data=$print_data.'0.00'; 
				}
				$print_data=$print_data.'</td>
				  <td class="text-right"></td>     
              </tr>';
		
              foreach($data['order_list'] as $order)
    	{
	
	if($order['invoice_status']=='1')
	{
		$status='Un-Paid';
	}
	if($order['invoice_status']=='2')
	{
		$status='Paid';
	}
	if($order['tr_number']!='') { $tr_number=$order['tr_number']; } else { $tr_number='NA'; }
              $print_data=$print_data.'<tr>
	        <td class="text-left">'.date('d-m-Y',strtotime($order['tr_date'])).'</td>
                
                <td class="text-right">'.$order['tr_type'].'</td>
                <td class="text-right">'.$tr_number.'</td>
                <td class="text-right">'.$order['total_debit'].'</td>
                <td class="text-right">'.$order['total_credit'].'</td>
	   <td class="text-right">'.$status.'</td>   
              </tr>';

				$totaldebit=$totaldebit+$order['total_debit'];
              	$totalcredit=$totalcredit+$order['total_credit'];
              }
           $print_data=$print_data.'<tr>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right" style="text-align: right;"><b>Total : </b></td>   
                <td class="text-right" style="text-align: right;">'.number_format((float)$totaldebit, 2, '.', '').'</td>
                <td class="text-right" style="text-align: right;">'.number_format((float)$totalcredit, 2, '.', '').'</td>
				<td class="text-right"></td>
              </tr>   

			  <tr>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right" style="text-align: right;"><b> Liability : </b></td>   
                <td class="text-right" style="text-align: right;">'.number_format((float)($totaldebit-$totalcredit), 2, '.', '').'</td>
                <td class="text-right" style="text-align: right;"></td>
				<td class="text-right"></td>
              </tr>   ';
            $print_data=$print_data.'</tbody>
          </table>';
           echo $print_data;
		   */
	
    }
	public function download_leadger_pdf()
	{
				if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 'null';
		}
		$filter_data4 = array(
                                         'filter_supplier'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);
		$this->load->model('purchaseorder/purchase_order');
		
		$data['store_to_data']=$this->model_purchaseorder_purchase_order->get_to_supplier_data($filter_store);
		
        $data['order_list4'] = $this->model_purchaseorder_purchase_order->get_ledger($filter_data4);
		$ledger_opening_balance=$this->model_purchaseorder_purchase_order->get_ledger_opening_balance($filter_data4);
			//print_r($ledger_opening_balance);
		$data['opening_balance']=$ledger_opening_balance['total_debit']-$ledger_opening_balance['total_credit'];
		
		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;
		
		$this->response->setOutput($this->load->view('purchaseorder/purchase_order_leadger_print.tpl', $data));
		
				$filename='supplier_leadger_'.date('dmy').'.pdf';
				require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
                
                $html = $this->load->view('purchaseorder/purchase_order_leadger_print.tpl',$data);
                
               
                $base_url = HTTP_CATALOG;
                
                
                $mpdf = new mPDF('c', 'A4', '', '', '5', '5', '25', '25', '5', '1', '');
                $header = '<div class="header" style="">
                   
<div class="logo" style="width: 100%;" >
<img src="../image/letterhead_text.png" style="height: 40px; width: 121px;" />
<img src="../image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />

                         </div>
<img src="../image/letterhead_topline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 

</div>';
   
                $mpdf->SetHTMLHeader($header, 'O', false);
                    
                $footer = '<div class="footer">
                        
                        <img src="../image/letterhead_bottomline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 
                        <div class="address"><img src="../image/letterhead_footertext.png" style="height: 10px; margin-left: -40px;width: 120% !important;" /> </div>'
                        . '</div>';

                
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->setAutoBottomMargin = 'stretch';
                $mpdf->WriteHTML($html);

                $mpdf->Output($filename,'D');
				
					
	}

	public function tax_report()
    {
                
	$this->document->setTitle("Tax Report");
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
			'text' => "Tax Report",
			'href' => $this->url->link('purchaseorder/report/tax_report', 'token=' . $this->session->data['token'] . $url, true)
		);

		
		
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
		
		
		$data['order_list'] = $this->model_purchaseorder_purchase_order->getTaxList($filter_data);
		
		$total_orders = $this->model_purchaseorder_purchase_order->getTotalTaxOrders($filter_data);
		
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
		$pagination->url = $this->url->link('purchaseorder/report/tax_report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
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
		$this->response->setOutput($this->load->view('purchaseorder/tax_report_list.tpl', $data));
	}
	public function download_tax_report()
	{
        		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_supplier'])) {
			$filter_supplier = $this->request->get['filter_supplier'];
		} else {
			$filter_supplier = null;
		}
		$filter_data = array(
                                         'filter_supplier'	             => $filter_supplier,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);
        	$data['orders'] = array();
 
	
	$file_name="supplier_tax_report_".date('dMy').'.xls';
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	$print_data='<table class="table table-bordered">
                <thead>
                    <tr>
                <td class="text-left">Invoice Date</td>
		      <td class="text-left">Supplier Name</td>
		      			  
                                  <td class="text-left">Supplier GSTN</td>	
									<td class="text-left">Delivery Address</td>
                                  <td class="text-left">Product Name</td>
		<td class="text-left">Quantity</td>
		<td class="text-left">Unit</td>
		<td class="text-left">Rate (without tax)</td>
		
		<td class="text-left">Sub Total</td>
		<td class="text-left">Discount</td>
		<td class="text-left">Tax title</td>
		<td class="text-left">Tax rate</td>
		<td class="text-left">Total Tax</td>
		<td class="text-left">Rebate & Discount / Freight Charge </td>
		<td class="text-left">Invoice Amount</td>
		<td class="text-left">Invoice Number</td>
		<td class="text-left">Purchase Order / Reference ID</td>
              </tr> 
                </thead>
            <tbody>';
             $this->load->model('purchaseorder/purchase_order');
    	$data['order_list'] = $this->model_purchaseorder_purchase_order->getTaxList($filter_data);
              foreach($data['order_list'] as $order)
    	{
	
				$tax_type1=explode('@',$order['tax_type']); 
				$tax_type2=explode('%',$tax_type1[1]); 
				$total_tax_rate=($tax_type2[0]*2);
	
              $print_data=$print_data.'<tr>
	        <td class="text-left">'.$order['invoice_date'].'</td>
				<td class="text-left">'.$order['supplier'].'</td>
				<td class="text-left">'.$order['supplier_gst'].'</td>
				<td class="text-left">'.$order['delivery_address'].'</td>  
				<td class="text-left">'.$order['product'].'</td>
				<td class="text-left">'.$order['Quantity'].'</td>
				<td class="text-left">'.$order['unit'].'</td>
				<td class="text-left">'.$order['rate'].'</td>
				
				<td class="text-left">'.$order['sub_total'].'</td>
				<td class="text-left">'.$order['discount'].'</td>
				<td class="text-left">'.'GST @'.$total_tax_rate.'%'.'</td>
				<td class="text-left">'.$total_tax_rate.'</td>
				<td class="text-left">'.($order['cgst']*2).'</td>
				<td class="text-left">'.$order['transport_charges'].'</td>
				<td class="text-left">'.$order['grand_total'].'</td>
				<td class="text-left">'.$order['invoice_no'].'</td>
				<td class="text-left">'.$order['id_prefix'].$order['po_no'].'</td>   
              </tr>';

				 
              }
           
            $print_data=$print_data.'</tbody>
          </table>';
           echo $print_data;
	
    }

}

?>