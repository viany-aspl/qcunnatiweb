<?php
class ControllerReportProductCatsales extends Controller {



	public function index() {
		$this->load->language('report/product_store_wise_sales');

		$this->document->setTitle('Product Sales Quantity (Category)');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
       
		if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = '';
		}
		if (isset($this->request->get['filter_category'])) {
			$filter_category = $this->request->get['filter_category'];
		} else {
			$filter_category = '';
		}
                if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
		        $filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
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


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_name_id'])) {
			$url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Product Sales Quantity (Category)',
			'href' => $this->url->link('report/product_cat_sales', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/product_catsales');
                            $this->load->model('setting/store');
		$data['products'] = array();
		
		$filter_data = array(
			'filter_name'	     => $filter_name,
			'filter_name_id'     => $filter_name_id,
			'filter_store'       => $filter_store,
                        'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_category'=>$filter_category,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		if((!empty($filter_category)) )//&& (!empty($filter_store))
		{
		$t1=$this->model_report_product_catsales->getTotalsales($filter_data);
		$product_total = $t1["total"];
		$data["total_amount_all"]= $t1["total_amount"];
		$total_amount=0;
		$results = $this->model_report_product_catsales->getSales($filter_data);
		}
		foreach ($results as $result) { $total_amount=$total_amount+$result['total'];
			$data['products'][] = array(
				'name'       => $result['name'],
				'product_id'      => $result['product_id'],
                                'store_name'      => $result['store_name'],
				'quantity'   => $result['quantity'],
				'sub_cat_name'   => $result['sub_cat_name'],
				'total'      => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		}
		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('product_storewisesales');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['total_amount'] = $total_amount;
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		

		$url = '';

		
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

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/product_cat_sales', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		
		$data['filter_name'] = $filter_name;
                $data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
                $data['filter_name_id'] = $filter_name_id;
                $data['filter_store'] = $filter_store;
	$data['filter_category']=$filter_category;
                $data['stores'] = $this->model_setting_store->getStores();
	$data['sub_categories'] = $this->model_report_product_catsales->getSubCategories();
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		if(empty($filter_category))
		{
			$data['text_no_results'] = 'Please Select Sub Category';
		}
		if(empty($filter_store))
		{
			//$data['text_no_results'] = 'Please Select  Store';
		}
		if((empty($filter_category)) && (empty($filter_store)))
		{
			//$data['text_no_results'] = 'Please Select Sub Category and Store';
		}
		$this->response->setOutput($this->load->view('report/product_cat_sales.tpl', $data));
	}


        public function download_excel(){
        
               if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = '';
		}

		if (isset($this->request->get['filter_store'])) {
		        $filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
		if (isset($this->request->get['filter_category'])) {
		        $filter_category = $this->request->get['filter_category'];
		} else {
			$filter_category = '';
		}

               if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

 
                $this->load->model('report/product_catsales');
                
		$data['products'] = array();

		$filter_data = array(
			'filter_name'	     => $filter_name,
			'filter_name_id'     => $filter_name_id,
                        'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'       => $filter_store,
			'filter_category'	     => $filter_category
		);

		

		$results = $this->model_report_product_catsales->getSales($filter_data);

		
   

    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name',
        'Product Name',
	'Product Sub Category',
        'Sale Quantity',
        'Total'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    // Fetching the table data
    //$this->load->model('report/searchattendance');
    //$results = $this->model_report_searchattendance->getmdoattendance($filter_data);
    
    $row = 2;
    
    foreach($results as $data)
    {         $col = 0;
        
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['name']);  
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['sub_cat_name']);  
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['quantity']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['total']);
        
        
            
        

        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Product_wise_sales_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
  

}