<?php

class ControllerReportRechargereport extends Controller {
    public function index(){
         
	$this->document->setTitle("Recharge Report");
     
	$this->load->model('recharge/rechargereport');
      
        $data['heading_title'] = "Recharge Report";
        $data['text_list'] = $this->language->get('text_list');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_price'] = $this->language->get('entry price');
        
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
    
    protected function getList() {
        
        if (isset($this->request->get['filter_from_date'])) {
            $filter_from_date = $this->request->get['filter_from_date'];
        } else {
            $filter_from_date = date('Y-m')."-01";
        }

        if (isset($this->request->get['filter_to_date'])) {
            $filter_to_date = $this->request->get['filter_to_date'];
        } else {
            $filter_to_date = date('Y-m-d');
        }
        
        if (isset($this->request->get['filter_mobile'])) {
            $mobile = $this->request->get['filter_mobile'];
        } else {
            $mobile = null;
        }
       if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = null;
        }
        

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_from_date'])) {
            $url .= '&filter_from_date=' . $this->request->get['filter_from_date'];
        }

        if (isset($this->request->get['filter_to_date'])) {
            $url .= '&filter_to_date=' . urlencode(html_entity_decode($this->request->get['filter_to_date'], ENT_QUOTES, 'UTF-8'));
        }
       if (isset($this->request->get['filter_mobile'])) {
            $url .= '&filter_mobile=' . urlencode(html_entity_decode($this->request->get['filter_mobile'], ENT_QUOTES, 'UTF-8'));
        }
       if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . urlencode(html_entity_decode($this->request->get['filter_store'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

       
        $data['orders'] = array();

        $filter_data = array(
            'filter_from_date'      => $filter_from_date,
            'filter_to_date'       => $filter_to_date, 
            'filter_store' => $filter_store,
            'filter_mobile'         => $mobile,
         
            'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                => $this->config->get('config_limit_admin')
        );
       $filter_data2 = array(
            'filter_from_date'      => $filter_from_date,
            'filter_to_date'       => $filter_to_date, 
            'filter_store' => $filter_store,
            'filter_mobile'         => $mobile
        );
        
        $data['lastfromdate']=$filter_from_date;
        $data['lasttodate']=$filter_to_date;
        $data['lastmobile']=$mobile;
        $data['filter_store']=$filter_store;

        $this->load->model('setting/store');
        
       $results = $this->model_recharge_rechargereport->getrechargeData($filter_data);
       $order_total = $this->model_recharge_rechargereport->getrechargeDatacount($filter_data);
       //$order_total=count($results);
       $data["countresults"] = $this->model_recharge_rechargereport->getrechargeDatacountnumber($filter_data2);
       //print_r($countresults);
        foreach ($results as $result) { 
            $data['geo'][] = array(
                'mobile'      => $result['mobile'],
                'recharge_amount'      => $result['recharge_amount'],
                'order_id'        => $result['order_id'],
                'product_name'    => $result['product_name'],
                'product_quantity'    => $result['product_quantity'],
                'product_id'    => $result['product_id'],
                'operator_name'    => $result['operator_name'],
                'ResSerSts'=> ucfirst($result["ResSerSts"]),
	'store_name'=> ucfirst($result["store_name"]),
	'scheme_name'=> ucfirst($result["scheme_name"]),
	'recharge_date'=> date('d/m/Y',strtotime($result["create_date"])),
	'recharge_time'=> date('H:i:s',strtotime($result["create_date"]))
                
            );
        }

        $data['heading_title'] = "Recharge Report";
        $this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/rechargereport');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');
        $data['text_list'] = 'Recharge Report';
        $data['text_no_results'] = $this->language->get('text_no_results');
        
        $data['button_filter'] = $this->language->get('button_filter');
       
        $data['token'] = $this->session->data['token'];
        
        $url = '';

        if (isset($this->request->get['filter_from_date'])) {
            $url .= '&filter_from_date=' . $this->request->get['filter_from_date'];
        }

        if (isset($this->request->get['filter_to_date'])) {
            $url .= '&filter_to_date=' . urlencode(html_entity_decode($this->request->get['filter_to_date'], ENT_QUOTES, 'UTF-8'));
        }
       if (isset($this->request->get['filter_mobile'])) {
            $url .= '&filter_mobile=' . urlencode(html_entity_decode($this->request->get['filter_mobile'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . urlencode(html_entity_decode($this->request->get['filter_store'], ENT_QUOTES, 'UTF-8'));
        }
       if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }


        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/rechargereport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => "Recharge Report",
			'href' => $this->url->link('report/rechargereport', 'token=' . $this->session->data['token'], 'SSL')
		);
                           $data['stores'] = $this->model_setting_store->getStores();//print_r($data['stores'] );
		
                            $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
               
                            $this->response->setOutput($this->load->view('report/rechargereport.tpl', $data)); 

        
    }
   
     public function test(){
         
	$this->document->setTitle("Recharge Report"); 
     
	$this->load->model('recharge/rechargereport');
      
        $data['heading_title'] = "Recharge Report";
        $data['text_list'] = $this->language->get('text_list');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_price'] = $this->language->get('entry price');
        
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
                
              
               $this->testgetList();
    }
    
    protected function testgetList() {
        
        if (isset($this->request->get['filter_from_date'])) {
            $filter_from_date = $this->request->get['filter_from_date'];
        } else {
            $filter_from_date = date('Y-m')."-01";
        }

        if (isset($this->request->get['filter_to_date'])) {
            $filter_to_date = $this->request->get['filter_to_date'];
        } else {
            $filter_to_date = date('Y-m-d');
        }
        
        if (isset($this->request->get['filter_mobile'])) {
            $mobile = $this->request->get['filter_mobile'];
        } else {
            $mobile = null;
        }
       if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = null;
        }
        

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_from_date'])) {
            $url .= '&filter_from_date=' . $this->request->get['filter_from_date'];
        }

        if (isset($this->request->get['filter_to_date'])) {
            $url .= '&filter_to_date=' . urlencode(html_entity_decode($this->request->get['filter_to_date'], ENT_QUOTES, 'UTF-8'));
        }
       if (isset($this->request->get['filter_mobile'])) {
            $url .= '&filter_mobile=' . urlencode(html_entity_decode($this->request->get['filter_mobile'], ENT_QUOTES, 'UTF-8'));
        }
       if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . urlencode(html_entity_decode($this->request->get['filter_store'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

       
        $data['orders'] = array();

        $filter_data = array(
            'filter_from_date'      => $filter_from_date,
            'filter_to_date'       => $filter_to_date, 
            'filter_store' => $filter_store,
            'filter_mobile'         => $mobile,
         
            'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                => $this->config->get('config_limit_admin')
        );
$filter_data2 = array(
            'filter_from_date'      => $filter_from_date,
            'filter_to_date'       => $filter_to_date, 
            'filter_store' => $filter_store,
            'filter_mobile'         => $mobile
        );
        
        $data['lastfromdate']=$filter_from_date;
        $data['lasttodate']=$filter_to_date;
        $data['lastmobile']=$mobile;
        $data['filter_store']=$filter_store;

        $this->load->model('setting/store');
        
       $results = $this->model_recharge_rechargereport->getrechargeData($filter_data);
       $order_total = $this->model_recharge_rechargereport->getrechargeDatacount($filter_data);
       //$order_total=count($results);
       $data["countresults"] = $this->model_recharge_rechargereport->getrechargeDatacountnumber($filter_data2);
       //print_r($countresults);
        foreach ($results as $result) { 
            $data['geo'][] = array(
                'mobile'      => $result['mobile'],
                'recharge_amount'      => $result['recharge_amount'],
                'order_id'        => $result['order_id'],
                'product_name'    => $result['product_name'],
                'product_quantity'    => $result['product_quantity'],
                'product_id'    => $result['product_id'],
                'operator_name'    => $result['operator_name'],
                'ResSerSts'=> ucfirst($result["ResSerSts"]),
	'store_name'=> ucfirst($result["store_name"]),
	'scheme_name'=> ucfirst($result["scheme_name"]),
	'recharge_date'=> date('d/m/Y',strtotime($result["create_date"])),
	'recharge_time'=> date('H:i:s',strtotime($result["create_date"]))
                
            );
        }

        $data['heading_title'] = "Recharge Report";
        
        $data['text_list'] = 'Recharge Report';
        $data['text_no_results'] = $this->language->get('text_no_results');
        
        $data['button_filter'] = $this->language->get('button_filter');
       
        $data['token'] = $this->session->data['token'];
        
        $url = '';

        if (isset($this->request->get['filter_from_date'])) {
            $url .= '&filter_from_date=' . $this->request->get['filter_from_date'];
        }

        if (isset($this->request->get['filter_to_date'])) {
            $url .= '&filter_to_date=' . urlencode(html_entity_decode($this->request->get['filter_to_date'], ENT_QUOTES, 'UTF-8'));
        }
       if (isset($this->request->get['filter_mobile'])) {
            $url .= '&filter_mobile=' . urlencode(html_entity_decode($this->request->get['filter_mobile'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_store'])) {
            $url .= '&filter_store=' . urlencode(html_entity_decode($this->request->get['filter_store'], ENT_QUOTES, 'UTF-8'));
        }
       if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }


        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/rechargereport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => "Recharge Report",
			'href' => $this->url->link('report/rechargereport', 'token=' . $this->session->data['token'], 'SSL')
		);
                           $data['stores'] = $this->model_setting_store->getStores();//print_r($data['stores'] );
		
                            $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
               
                            $this->response->setOutput($this->load->view('report/rechargereporttest.tpl', $data)); 

        
    }
   
    public function downloadexcel(){
        
	$this->load->model('recharge/rechargereport');
      if (isset($this->request->get['filter_from_date'])) {
            $filter_from_date = $this->request->get['filter_from_date'];
        } else {
            $filter_from_date = date('Y-m')."-01";
        }

        if (isset($this->request->get['filter_to_date'])) {
            $filter_to_date = $this->request->get['filter_to_date'];
        } else {
            $filter_to_date = date('Y-m-d');
        }
        
        if (isset($this->request->get['filter_mobile'])) {
            $mobile = $this->request->get['filter_mobile'];
        } else {
            $mobile = null;
        }
       if (isset($this->request->get['filter_store'])) {
            $filter_store = $this->request->get['filter_store'];
        } else {
            $filter_store = null;
        }
       
        $data['orders'] = array();

        $filter_data = array(
            'filter_from_date'      => $filter_from_date,
            'filter_to_date'       => $filter_to_date, 
            'filter_mobile'         => $mobile,
            'filter_store'         => $filter_store
        );

       $results = $this->model_recharge_rechargereport->getrechargeData($filter_data);
      
       
    // Starting the PHPExcel library
    $this->load->library('PHPExcel');
    $this->load->library('PHPExcel/IOFactory');

    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Mobile',
        'Recharge Amount',
        'Order Id',
        'Operator Name',
        'Product name',
        'Product quantity',
	 'Recharge status',
	 'Store name',
	 'Scheme name',
	 'Recharge date',
	'Recharge time'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    // Fetching the table data
    $row = 2;
    
    foreach($results as $data)
    {
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['mobile']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['recharge_amount']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['operator_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['product_quantity']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, ucfirst($data["ResSerSts"]));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['store_name']);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['scheme_name']);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, date('d/m/Y',strtotime($data["create_date"])));
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, date('H:i:s',strtotime($data["create_date"])));
            
        

        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="recharge_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
    
    
}
