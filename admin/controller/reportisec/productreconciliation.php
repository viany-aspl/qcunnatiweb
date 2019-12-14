<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of createVillage
 *
 * @author agent
 */
class ControllerReportisecProductreconciliation extends Controller {
    public function  index(){
        
        $this->load->language('geo/searchgeo');
        

           
        $data['heading_title'] = $this->language->get('heading_title');
        
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
       
        $data['token'] = $this->session->data['token'];
        $this->document->setTitle('Product Reconcilation');
       
            if($this->request->get['tab']=="1")
            {
                $data['tab1'] ="active";
                $data['tab2'] ="";
                
            }
           else if($this->request->get['tab']=="2")
           {
                $data['tab1'] ="";
                $data['tab2'] ="active";
                
          }
             
          else
          {
                $data['tab1'] ="active";
                $data['tab2'] ="";
               
          }
        
        
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
			$filter_store = null;
		}
                           if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
                           if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = null;
		}
   
                if (isset($this->request->get['unit_id'])) {
			$unit_id = $this->request->get['unit_id'];
		} else {
			$unit_id = null;
		}
  
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
		 $page = 1;
		}
                          
		if (isset($this->request->get['page2'])) {
			$page2 = $this->request->get['page2'];
		} else {
		 $page2 = 1;
		}
		
		
                            

		

		$filter_data = array(
                                          'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_name'	             => $filter_name,
                        'filter_company'	     => '2',
                                          'filter_name_id'	     => $filter_name_id,
                        'unit_id'                    => $unit_id,
			'start'                      => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data2 = array(
                                          'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                         'filter_company'	     => '2',
			'filter_name'	             => $filter_name,
                                          'filter_name_id'	     => $filter_name_id,
                        'unit_id'                    => $unit_id,
			'start'                      => ($page2 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		
            // print_r($filter_data2);
                
/***********************************   Material received ***********************************************/ 
                
                            $this->load->model('unit/unit');
                            $data['unit'] = $this->model_unit_unit->getunit();
                           // print_r($data['unit']);
                            
                              $this->load->model('report/productreconciliation');
		  
                              if($filter_name_id!="")
                              {
                                $t = $this->model_report_productreconciliation->getTotalOrdersReceivedCompanyWise($filter_data);
                                $order_total=$t["total"];
                                $data["total_received"]=$t["total_quantity"];
		    $results = $this->model_report_productreconciliation->getOrdersReceivedCompanyWise($filter_data);
                              }
                            
		foreach ($results as $result) { //print_r($result);
                        
			$data['orders'][] = array(
                            
                            'unit_name'   => $result["unit_name"],
		     'store_name'   => $result["store_name"],
                                'product_name'  => $result["product_name"],    
                                'product_id'     => $result['product_id'],
                                'quantity'   => $result['quantity'],
		    'order_date'   => date($this->language->get('date_format_short'), strtotime($result['order_date'])),
		    'recive_date'  => date($this->language->get('date_format_short'), strtotime($result['recive_date'])),
                                'store_transfer' => $result['store_transfer']
                        );     
		}

          
                  
/*********************************  end  Material received ****************************************/ 
                  
               
                
 /*****************************************SALE**********************************************/               
                            if($filter_name_id!="")
                            {
                               $t2 = $this->model_report_productreconciliation->getTotalOrdersCompanyWise($filter_data2);
                               $order_total2=$t2["total"];
                               $data["total_sold"]=$t2["total_quantity"];
		   $results2 = $this->model_report_productreconciliation->getOrdersCompanyWise($filter_data2);
                         
                   }
                  
                           $data['orders2'] = array();
	          foreach ($results2 as $result) { //print_r($result);
                      
                
			$data['orders2'][] = array(
				
				'order_date'   => date($this->language->get('date_format_short'), strtotime($result['ord_date'])),
				
                            'quantity'     => $result['quantity'],
                                        'product_name'  => $result["name"],
                                        'product_id'   => $result['product_id'],
                                    
                                        'unit_name'   => $result["unit_name"],
                                        'store_name' => $result['store_name']
                        );
		}
           
/********************************************END SALE*********************************************/                  
  
		$this->load->model('setting/store');       
		$url = '';
                            if (isset($this->request->get['filter_store'])) {
			//$url .= '&filter_store=' . $this->request->get['filter_store'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                           if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
                           if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
		} 
                
                  if (isset($this->request->get['unit_id'])) {
			$url .= '&unit_id=' . $this->request->get['unit_id'];
		} 
                           if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
     
		if (isset($this->request->get['page2'])) {
			$url .= '&page2=' . $this->request->get['page2'];
		}
		
      
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/productreconciliation', 'token=' . $this->session->data['token'] . $url . '&page={page}&tab=1', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
           
            
          
		$pagination2 = new Pagination();
		$pagination2->total = $order_total2;
		$pagination2->page = $page2;
		$pagination2->limit = $this->config->get('config_limit_admin');
		$pagination2->url = $this->url->link('reportbcml/productreconciliation', 'token=' . $this->session->data['token'] . $url . '&page2={page}&tab=2', 'SSL');

		$data['pagination2'] = $pagination2->render(); 

		$data['results2'] = sprintf($this->language->get('text_pagination'), ($order_total2) ? (($page2 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page2 - 1) * $this->config->get('config_limit_admin')) > ($order_total2 - $this->config->get('config_limit_admin'))) ? $order_total2 : ((($page2 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total2, ceil($order_total2 / $this->config->get('config_limit_admin')));
           
            
           
                           
     		 $this->load->model('setting/store');
      		 $data['stores'] = $this->model_setting_store->getStoresCompanyWise('2'); //CompanyWise('2')
      		 $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Product Reconciliation',
			'href' => $this->url->link('reportbcml/productreconciliation', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

                            $data['entry_date_start'] = 'Start date';
		$data['entry_date_end'] = 'End date';
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
                $data['unit_id'] = $unit_id;
               
                            $data['filter_name'] = $filter_name;
                            
                            $data['filter_name_id'] = $filter_name_id;
                            if($filter_name_id=="")
                            {
                            $data["text_no_results"]=" Please select a product ";
                            }
                            else
                            {
                             $data["text_no_results"]=" No result found for this product ";
                            }
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                            $this->response->setOutput($this->load->view('reportbcml/productreconciliation.tpl', $data)); 

        

    } 
    public function download_recived(){

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
			$filter_store = null;
		}
                           if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
                           if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = null;
		}
                  if (isset($this->request->get['unit_id'])) {
			$unit_id = $this->request->get['unit_id'];
		} else {
			$unit_id = null;
		}
   
		$filter_data = array(
                                          'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_company'            => '2',
			'filter_name'	             => $filter_name,
                        'unit_id'	             => $unit_id,
                                          'filter_name_id'	     => $filter_name_id
		);
		
		 

    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Unit Name',
        'Store Name (Receiver)',
        'Order date',
        'Received date',
        'Product Name',
        'Product ID',
        'Qnty'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
   $this->load->model('report/productreconciliation');
		
   $results =array();
                              if($filter_name_id!="")
                              {
                                
		    $results = $this->model_report_productreconciliation->getOrdersReceivedCompanyWise($filter_data);
                              }
                           
		

		
    $row = 2;
    
    foreach($results as $data)
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['unit_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date($this->language->get('date_format_short'), strtotime($data['order_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date($this->language->get('date_format_short'), strtotime($data['recive_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['quantity']);
        
        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Material_received_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
public function download_sold(){
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
			$filter_store = null;
		}
                           if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
                           if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = null;
		}
                  if (isset($this->request->get['unit_id'])) {
			$unit_id = $this->request->get['unit_id'];
		} else {
			$unit_id = null;
		}
   
		$filter_data = array(
                                          'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_company'            => '2',
			'filter_name'	             => $filter_name,
                        'unit_id'	             => $unit_id,
                                          'filter_name_id'	     => $filter_name_id
			
		);
		
		 

    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Unit Name',
        'Store Name',
        'Order date',
       
        'Product Name',
        'Product ID',
        'Qnty'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
   $this->load->model('report/productreconciliation');
		
   $results =array();
                              if($filter_name_id!="")
                            {
                               

		   $results = $this->model_report_productreconciliation->getOrdersCompanyWise($filter_data);
                            }
                        	
    $row = 2;
    
    foreach($results as $data)
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['unit_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date($this->language->get('date_format_short'), strtotime($data['ord_date'])));
       
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['quantity']);
        
        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Material_sold_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
        
    }

}   