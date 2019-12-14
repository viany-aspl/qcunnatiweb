<?php
class ControllerReversalReversal extends Controller {
	
public function index()
        {

            $this->load->language('report/Inventory_report');

           $this->document->setTitle('Quantity reversal');
	$this->load->model('reversal/reversal');  
           if ($this->request->server['REQUEST_METHOD'] == 'POST')
             { 
	//print_r($this->request->post);
	$current_quantity=$this->model_reversal_reversal->check_current_quantity($this->request->post['filter_store'],$this->request->post['filter_name_id']);
              //exit;
	if($current_quantity<$this->request->post['filter_quantity'])
	{
	$this->model_reversal_reversal->insert_into_trans($this->request->post,'0');	
	  $this->session->data['error'] = 'Not enogh quantity for '.$this->request->post['filter_name'].' at store';
              $this->response->redirect($this->url->link('reversal/reversal', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	else
	{
                 $this->model_reversal_reversal->reverse_quantity($this->request->post);

                 $this->model_reversal_reversal->insert_into_trans($this->request->post,'1');
                 $this->session->data['success'] = 'Quantity reverse successfully';
                $this->response->redirect($this->url->link('reversal/reversal', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
             }
             //exit;
             $this->getForm();

		
        }
       public function getForm()
       {
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('Quantity reversal'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => 'Quantity reversal',
            'href' => $this->url->link('reversal/reversal', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

                  
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

        $data['heading_title'] = 'Quantity reversal';
        
        $data['text_list'] = 'Quantity reversal';
      
        $data['button_filter'] = $this->language->get('button_filter');

        $data['token'] = $this->session->data['token'];
        $data["action"]="index.php?route=reversal/reversal&token=" . $this->session->data['token'];
        $this->load->model('user/user');
        $data["logged_user"] = $this->user->getId();
	$this->load->model('setting/store');  
        $data['stores'] = $this->model_setting_store->getStores();       
       $data['cancel']=$this->url->link('common/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('reversal/reversal_form.tpl', $data));

       }
        public function get_store_product_data()
        {
           
            $this->load->model('reversal/reversal');
            $getstoresdata = $this->model_reversal_reversal->check_current_quantity($_REQUEST["store_id"],$_REQUEST["product_id"]);
            echo $getstoresdata;

        }

public function trans() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Reversal transactions');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
		if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = '';
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
                
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
		}
                
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Reversal transactions',
			'href' => $this->url->link('reversal/reversal/trans', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                            $this->load->model('reversal/reversal');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store,
			'filter_name_id'	     => $filter_name_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_reversal_reversal->getTotal_transation($filter_data);

		$data['orders'] = array();

		$results = $this->model_reversal_reversal->getTrans_report($filter_data);
                
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				'SIID' => $result['transid'],
				'store_name'   => $result['store_name'],
				'product_id'      => $result['product_id'],
				'product_name'     => $result['product_name'],
                                                        'status'     => $result['status'],
				'quantity'     => $result['quantity'],
				'remarks'     => $result['remarks'],
                                                        'date_added'=>  date($this->language->get('date_format_short'), strtotime($result['date_time'])),   
				'bank_name'      => $result['bank_name']
			);
		}

		$data['heading_title'] = 'Reversal transactions';
		
		

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reversal/reversal/trans', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$this->load->model('setting/store');  
                            $data['stores'] = $this->model_setting_store->getStores();
		$data['filter_store'] = $filter_store;
		$data['filter_name'] = $filter_name;
		$data['filter_name_id'] = $filter_name_id;
		$data['redirect']=$this->url->link('reversal/reversal', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('reversal/trans_report.tpl', $data));
	}
public function trans_download() {
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
		if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = '';
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
                
		
		
                            $this->load->model('reversal/reversal');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store,
			'filter_name_id'	     => $filter_name_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		

		$data['orders'] = array();

		$results = $this->model_reversal_reversal->getTrans_report($filter_data);
                
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Store Name  ',
        'Product ID',
	'Product Name',
        'Date',
	'Quantity',
        'Remarks',
	'Status'
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
    if($data["status"]=="0")
{
$status="Failure";
}
else if($data["status"]=="1")
{
$status="Success";
}
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['product_id']);
	 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['product_name']);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date('Y-m-d',strtotime($data['date_time'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['quantity']);
         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['remarks']);
         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $status);
        
            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="quantity_reversal_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}

public function addition()
        {

            $this->load->language('report/Inventory_report');

           $this->document->setTitle('Quantity Addition');
	$this->load->model('reversal/reversal');  
           if ($this->request->server['REQUEST_METHOD'] == 'POST')
             { 
		print_r($this->request->post);
                 $this->model_reversal_reversal->add_quantity($this->request->post);

                 $this->model_reversal_reversal->insert_into_trans_addition($this->request->post,'1');
		//exit;
                 $this->session->data['success'] = 'Quantity Addition successfully';
                $this->response->redirect($this->url->link('reversal/reversal/addition', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	
             }
             
             $this->getAdditionForm();

		
        }
       public function getAdditionForm()
       {
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('Quantity Addition'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => 'Quantity Addition',
            'href' => $this->url->link('reversal/reversal/addition', 'token=' . $this->session->data['token'] . $url, 'SSL') 
        );

                  
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

        $data['heading_title'] = 'Quantity Addition';
        
        $data['text_list'] = 'Quantity Addition';
      
        $data['button_filter'] = $this->language->get('button_filter');

        $data['token'] = $this->session->data['token'];
        $data["action"]="index.php?route=reversal/reversal/addition&token=" . $this->session->data['token'];
        $this->load->model('user/user');
        $data["logged_user"] = $this->user->getId();
	$this->load->model('setting/store');  
        $data['stores'] = $this->model_setting_store->getStores();       
       $data['cancel']=$this->url->link('common/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('reversal/addition_form.tpl', $data));

       }

}