<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(1);
ini_set('max_execution_time', 600); //600 seconds = 10 minutes

global $reg;
class ControllerReportbcmlReport extends Controller {
	
	 public function adminmodel($model) {
      
      $admin_dir = DIR_SYSTEM; 
      $admin_dir = str_replace('system/','admin/',$admin_dir);
      $file = $admin_dir . 'model/' . $model . '.php';      
      //$file  = DIR_APPLICATION . 'model/' . $model . '.php';
      $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
      
      if (file_exists($file)) {
         include_once($file);
         
         $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
      } else {
         trigger_error('Error: Could not load model ' . $model . '!');
         exit();               
      }
   }
	
	
	public function index() {
		$this->load->language('report/product_sale');

		$this->document->setTitle('BCML Sales Report');

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = date('Y-m-d');
		}
  
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
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
			'text' => 'BCML Sales Report',
			'href' => $this->url->link('reportbcml/report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/bcml_input_sales');
        
		$data['orders'] = array();

		$filter_data = array(
                               
			'filter_date'	     => $filter_date,
			
			'filter_company' => '2',
                        
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
        $get_stores=$this->model_report_bcml_input_sales->getstores($filter_data);
       
		$order_total = $this->model_report_bcml_input_sales->getstoresTotal();
		
		foreach($get_stores as $stores)
		{  
       
       $today_cash=$this->model_report_bcml_input_sales->getcash($stores['store_id'],$filter_date);
       $today_tagged=$this->model_report_bcml_input_sales->gettagged($stores['store_id'],$filter_date);
       
       
       $till_date_cash=$this->model_report_bcml_input_sales->getcashtilldate($stores['store_id'],$filter_date);
       $till_date_tagged=$this->model_report_bcml_input_sales->gettaggedtilldate($stores['store_id'],$filter_date);
       
	   
	   $data['orders'][] = array(
				
								'store_name'          => $stores['name'],
								'unit_name'    => $stores['unit_name'],
								'store_id'    => $stores['store_id'],
                                'today_cash'  => $today_cash['Cash'],
                                'today_tagged'   => $today_tagged['Tagged'],
                                'till_date_cash'     => $till_date_cash['Cash'],
                                'till_date_tagged'         => $till_date_tagged['Tagged']
			);
		}
		
		$data['heading_title'] = 'BCML Sales Report';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];


		$url = '';

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date'] = $filter_date;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/bcml_sales_report.tpl', $data)); 
	}
        public function download_bcml_sales_report() {
        
        if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = date('Y-m-d');
		}
             
		

			
        $this->load->model('report/bcml_input_sales');
        
		$data['orders'] = array();

		$filter_data = array(
                               
			'filter_date'	     => $filter_date,
			
			'filter_company' => '2'
		);
        $get_stores=$this->model_report_bcml_input_sales->getstores($filter_data);
       
		//$order_total = $this->model_report_bcml_input_sales->getstoresTotal();
		
		
		
	$file_name="BCML_sales_report_".$filter_date.'.xls';
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

        echo '<table id="example2" class="table table-striped table-bordered table-hover">
                <thead>
	<tr>
	   <td class="text-left" colspan="2"></td>
                <td class="text-center" colspan="2">'.$filter_date.'</td>
                <td class="text-center" colspan="2">To Date </td>
              </tr>
              <tr>
                <td class="text-left">Factory Unit</td>
                <td class="text-left">Store Name</td>
                <td class="text-left">Cash</td>
                <td class="text-left">Tagging</td>
          
                <td class="text-left">Cash</td>
                <td class="text-left">Tagging</td>
                
                
              </tr>
            </thead>
                <tbody>';
$tblbody=" ";

foreach($get_stores as $stores)
		{  
       
       $today_cash=$this->model_report_bcml_input_sales->getcash($stores['store_id'],$filter_date);
       $today_tagged=$this->model_report_bcml_input_sales->gettagged($stores['store_id'],$filter_date);
       
       
       $till_date_cash=$this->model_report_bcml_input_sales->getcashtilldate($stores['store_id'],$filter_date);
       $till_date_tagged=$this->model_report_bcml_input_sales->gettaggedtilldate($stores['store_id'],$filter_date);
      
			echo  '<tr>
                <td class="text-left">'.$stores['unit_name'].'</td>
                <td class="text-left">'.$stores['name'].'</td>
          
                <td class="text-left">'.number_format((float)$today_cash['Cash'], 2, '.', '').'</td>
	  <td class="text-left">'.number_format((float)$today_tagged['Tagged'], 2, '.', '').'</td>
                <td class="text-left">'.number_format((float)$till_date_cash['Cash'], 2, '.', '').'</td>
                <td class="text-left">'.number_format((float)$till_date_tagged['Tagged'], 2, '.', '').'</td>
                
              </tr>';

		}

echo '</tbody>
          </table>';

        
    }
        public function dscl_store_inventory() {
		$this->load->language('report/product_sale');

		$this->document->setTitle('DSCL Sales Report');

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = date('Y-m-d');
		}
  
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
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
			'text' => 'DSCL Sales Report',
			'href' => $this->url->link('reportdscl/report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/dscl_input_sales');
        
		$data['orders'] = array();

		$filter_data = array(
                               
			'filter_date'	     => $filter_date,
			
			'filter_company' => '1',
                        
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
        $get_stores=$this->model_report_dscl_input_sales->getstores($filter_data);
       
		$order_total = $this->model_report_dscl_input_sales->getstoresTotal();
		
		foreach($get_stores as $stores)
		{  
       
       $today_cash=$this->model_report_dscl_input_sales->getcash($stores['store_id'],$filter_date);
       $today_tagged=$this->model_report_dscl_input_sales->gettagged($stores['store_id'],$filter_date);
       
       
       $till_date_cash=$this->model_report_dscl_input_sales->getcashtilldate($stores['store_id'],$filter_date);
       $till_date_tagged=$this->model_report_dscl_input_sales->gettaggedtilldate($stores['store_id'],$filter_date);
       
	   
	   $data['orders'][] = array(
				
								'store_name'          => $stores['name'],
								'unit_name'    => $stores['unit_name'],
								'store_id'    => $stores['store_id'],
                                'today_cash'  => $today_cash['Cash'],
                                'today_tagged'   => $today_tagged['Tagged'],
                                'till_date_cash'     => $till_date_cash['Cash'],
                                'till_date_tagged'         => $till_date_tagged['Tagged']
			);
		}
		
		$data['heading_title'] = 'DSCL Sales Report';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];


		$url = '';

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportdscl/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date'] = $filter_date;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportdscl/dscl_sales_report.tpl', $data)); 
	}
        public function download_dscl_store_inventory() {
        
        if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = date('Y-m-d');
		}
             
		$filter_data = array(
                        'filter_store'	     => $filter_store,
                        'filter_company'   => '1',
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_name'           => $filter_name_id
		);

			
        $this->load->model('report/dscl_input_sales');
        
		$data['orders'] = array();

		$filter_data = array(
                               
			'filter_date'	     => $filter_date,
			
			'filter_company' => '1'
		);
        $get_stores=$this->model_report_dscl_input_sales->getstores($filter_data);
       
		$order_total = $this->model_report_dscl_input_sales->getstoresTotal();
		
		
		
	$file_name="DSCL_sales_report_".$filter_date.'.xls';
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

        echo '<table id="example2" class="table table-striped table-bordered table-hover">
                <thead>
	<tr>
	   <td class="text-left" colspan="2"></td>
                <td class="text-center" colspan="2">'.$filter_date.'</td>
                <td class="text-center" colspan="2">To Date (Jan, 1 '.date('Y').' till Date)</td>
              </tr>
              <tr>
                <td class="text-left">Factory Unit</td>
                <td class="text-left">Store Name</td>
                <td class="text-left">Cash</td>
                <td class="text-left">Tagging</td>
          
                <td class="text-left">Cash</td>
                <td class="text-left">Tagging</td>
                
                
              </tr>
            </thead>
                <tbody>';
$tblbody=" ";

foreach($get_stores as $stores)
		{  
       
       $today_cash=$this->model_report_dscl_input_sales->getcash($stores['store_id'],$filter_date);
       $today_tagged=$this->model_report_dscl_input_sales->gettagged($stores['store_id'],$filter_date);
       
       
       $till_date_cash=$this->model_report_dscl_input_sales->getcashtilldate($stores['store_id'],$filter_date);
       $till_date_tagged=$this->model_report_dscl_input_sales->gettaggedtilldate($stores['store_id'],$filter_date);
      
			echo  '<tr>
                <td class="text-left">'.$stores['unit_name'].'</td>
                <td class="text-left">'.$stores['name'].'</td>
          
                <td class="text-left">'.number_format((float)$today_cash['Cash'], 2, '.', '').'</td>
	  <td class="text-left">'.number_format((float)$today_tagged['Tagged'], 2, '.', '').'</td>
                <td class="text-left">'.number_format((float)$till_date_cash['Cash'], 2, '.', '').'</td>
                <td class="text-left">'.number_format((float)$till_date_tagged['Tagged'], 2, '.', '').'</td>
                
              </tr>';

		}

echo '</tbody>
          </table>';

        
    }

/////////////////////////////////////////////////////////////////
public function item_wise_product_sold() {
		$this->load->language('report/product_sale');

		$this->document->setTitle('DSCL Store - Item Wise Product Sold');

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = date('Y-m-d');
		}
  
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
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
			'text' => 'DSCL Store - Item Wise Product Sold',
			'href' => $this->url->link('reportdscl/report/item_wise_product_sold', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['token'] = $this->session->data['token'];
        
		$data['filter_date'] = $filter_date;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportdscl/dscl_item_wise_product_sold.tpl', $data)); 
	}
        public function download_dscl_Item_Wise_Product_Sold_on_date() 
		{
			
			if (isset($this->request->get['filter_date'])) 
			{
				$filter_date = $this->request->get['filter_date'];
			} 
			else 
			{
				echo $filter_date = date('Y-m-d');
			}
			
			$filter_data = array(
                               
			'filter_date'	     => $filter_date,
			
			'filter_company' => '1'
		);
	$this->load->model('report/dscl_input_sales');
    //echo 'kkk'; 
    $getproducts = $this->model_report_dscl_input_sales->getproducts();
    $get_stores=$this->model_report_dscl_input_sales->getstores($filter_data);
		
		
    include_once DIR_SYSTEM .'/library/PHPExcel.php';
    include_once DIR_SYSTEM .'/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();

		/////////////2nd sheet start from here///////////
    $objPHPExcel->createSheet();
    
    //$objPHPExcel->getProperties()->setTitle("Stock Position")->setDescription("Stock Position");

    //$objPHPExcel->setActiveSheetIndex(1); 
    //$objPHPExcel->getActiveSheet()->setTitle("Stock Position");
    
    $product_fields=array();
    foreach($getproducts as $products)
    { 
        array_push($product_fields,$products["model"]); 
    
    }
    
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Product Name');
	$col2=1;
   foreach($get_stores as $stores)
    {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col2, 1, $stores['name']);
        	$col2=$col2+1;
    }
	
	$row = 2;
	
	$aaa=1;
	foreach ($getproducts as $products)
    	{
			//echo $products["product_id"];
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $products["model"]);
		$coll222=1;
			//if($aaa==1)
			//{
		foreach($get_stores as $stores)
    		{
				
				$getproducts = $this->model_report_dscl_input_sales->getsaleQuantity_on_date($stores["store_id"],$products["product_id"],$filter_data);
				if(!empty($getproducts["quantity"]))
				{
					$sale_quanty=$getproducts["quantity"];
				}
				else	
				{
					$sale_quanty=0;
				}
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coll222, $row, $sale_quanty);
				$coll222++;
		}
			//}
		$coll222=1;
    	$row++;
		$aaa++;
	}
  
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	
    $filename='DSCL_Store_Item_Wise_Product_Sold_On_Date_'.$filter_date.'.xls';
    $objWriter->save(DIR_UPLOAD.'dsclreports/'.$filename );
	
	echo 'dsclreports/'.$filename;
	
    // Sending headers to force the user to download the file
   // header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="DSCL_Store_Item_Wise_Product_Sold_On_Date_'.$filter_date.'.xls"');
    //header('Cache-Control: max-age=0');

    //$objWriter->save('php://output');
			//$asyncOperation=new AsyncOperationSeasionalReport($filter_date,$reg,'download_dscl_Item_Wise_Product_Sold_on_date_2');			
			//$asyncOperation->start();
    
        
    }
	
	public function download_dscl_Item_Wise_Product_Sold_on_date_2($data)
	{
		 echo 'here'; 
		 session_start();
		 print_r($_SESSION['regg']);
		 exit;
        $data[1]->load->model('report/dscl_input_sales');
        
//echo 'here';
		$filter_data = array(
                               
			'filter_date'	     => $data[0],
			
			'filter_company' => '1'
		);
	
    
    $getproducts = $this->model_report_dscl_input_sales->getproducts();
    $get_stores=$this->model_report_dscl_input_sales->getstores($filter_data);
		
		
    include_once DIR_SYSTEM .'/library/PHPExcel.php';
    include_once DIR_SYSTEM .'/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();

		/////////////2nd sheet start from here///////////
    $objPHPExcel->createSheet();
    
    //$objPHPExcel->getProperties()->setTitle("Stock Position")->setDescription("Stock Position");

    //$objPHPExcel->setActiveSheetIndex(1); 
    //$objPHPExcel->getActiveSheet()->setTitle("Stock Position");
    
    $product_fields=array();
    foreach($getproducts as $products)
    { 
        array_push($product_fields,$products["model"]);
    
    }
    
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Product Name');
	$col2=1;
   foreach($get_stores as $stores)
    {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col2, 1, $stores['name']);
        	$col2=$col2+1;
    }
	
	$row = 2;
	
	$aaa=1;
	foreach ($getproducts as $products)
    	{
			//echo $products["product_id"];
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $products["model"]);
		$coll222=1;
			//if($aaa==1)
			//{
		foreach($get_stores as $stores)
    		{
				
				$getproducts = $this->model_report_dscl_input_sales->getsaleQuantity_on_date($stores["store_id"],$products["product_id"],$filter_data);
				if(!empty($getproducts["quantity"]))
				{
					$sale_quanty=$getproducts["quantity"];
				}
				else	
				{
					$sale_quanty=0;
				}
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coll222, $row, $sale_quanty);
				$coll222++;
		}
			//}
		$coll222=1;
    	$row++;
		$aaa++;
	}
  
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="DSCL_Store_Item_Wise_Product_Sold_On_Date_'.$filter_date.'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');

	}

public function download_dscl_Item_Wise_Product_Sold_till_date() {
        
        if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = date('Y-m-d');
		}
             
		

			
        $this->load->model('report/dscl_input_sales');
        
		$data['orders'] = array();

		$filter_data = array(
                               
			'filter_date'	     => $filter_date,
			
			'filter_company' => '1'
		);
	$this->load->model('report/product_report');
    $this->load->model('report/dscl_input_sales');
    $getproducts = $this->model_report_dscl_input_sales->getproducts();
    $get_stores=$this->model_report_dscl_input_sales->getstores($filter_data);
		
		
    include_once DIR_SYSTEM .'/library/PHPExcel.php';
    include_once DIR_SYSTEM .'/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();

		/////////////2nd sheet start from here///////////
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("Stock Position")->setDescription("Stock Position");

    $objPHPExcel->setActiveSheetIndex(1); 
    $objPHPExcel->getActiveSheet()->setTitle("Stock Position");
    
    
    $product_fields=array();
    foreach($getproducts as $products)
    { 
        array_push($product_fields,$products["model"]);
    
    }
    
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Product Name');
	$col2=1;
   foreach($get_stores as $stores)
    {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col2, 1, $stores['name']);
        	$col2=$col2+1;
    }
	
	$row = 2;
	
	$aaa=1;
	foreach ($getproducts as $products)
    	{
			//echo $products["product_id"];
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $products["model"]);
		$coll222=1;
			//if($aaa==1)
			//{
		foreach($get_stores as $stores)
    		{
				
				$getproducts = $this->model_report_dscl_input_sales->getsaleQuantity_till_date($stores["store_id"],$products["product_id"],$filter_data);
				if(!empty($getproducts["quantity"]))
				{
					$sale_quanty=$getproducts["quantity"];
				}
				else	
				{
					$sale_quanty=0;
				}
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coll222, $row, $sale_quanty);
				$coll222++;
		}
			//}
		$coll222=1;
    	$row++;
		$aaa++;
	}
  
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$filename='DSCL_Store_Item_Wise_Product_Sold_Till_Date_'.$filter_date.'.xls';
    $objWriter->save(DIR_UPLOAD.'dsclreports/'.$filename );
	
	echo 'dsclreports/'.$filename;
    // Sending headers to force the user to download the file
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="DSCL_Store_Item_Wise_Product_Sold_Till_Date_'.$filter_date.'.xls"');
    //header('Cache-Control: max-age=0');

    //$objWriter->save('php://output');

        
    }

}

//$asyncOperation=new AsyncOperationSeasionalReport($this->request->post['customer_mob'],$this->request->post['muid'],'7',$order_id,$this->request->post['store_id']);
  //              $log->write('now we will call the thread 3');
	//  $asyncOperation->start();
      //          $log->write('now we will call the thread 4');


class AsyncOperationSeasionalReport extends Thread {
	
	
    public function __construct($filter_date,$class_name,$function_name) {
		
        $this->filter_date = $filter_date;
       // $this->class_name = $class_name;
       $this->function_name = $function_name;

    }
	
    public function run() 
	{

	$log=new Log("dscl-sales-report-".date('Y-m-d').".log");
	
	$log->write('come in run at thread'); 
	$log->write($this->filter_date."&&".$this->function_name);
	//$this->adminmodel('report/dscl_input_sales');
	
	global $reg;
	//session_start();
	//print_r($_SESSION['regg']);
	print_r($reg);echo 'here';exit;  
	//print_r($registry);
	$ControllerReportdsclReport=new ControllerReportdsclReport();
	//call_user_func(array($ControllerReportdsclReport,$this->function_name),array($this->filter_date,$ControllerReportdsclReport));
	
	$ControllerReportdsclReport->{$this->function_name}($this->filter_date);
	//echo 'here';
		/*
		$request = "https://unnati.world/shop/index.php?route=mpos/recharge/rechargetest&mobile=".$this->mobile."&muid=".$this->muid."&scheme_id=".$this->scheme."&order_id=".$this->order_id."&store_id=".$this->store_id;
		$log->write($request);
		//$fields_string .= 'products'.'='.$mcrypt->encrypt(json_encode($this->products,true)).'&'; 
		rtrim($fields_string, '&');
		$log->write($fields_string);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);  
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);    
		$json =curl_exec($ch);
		curl_close($ch); 
		$log->write($json);
		*/
	
	
	$log->write('after call function');
    }
} 