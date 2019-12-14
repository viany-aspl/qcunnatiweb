<?php
class ControllerReportCardhistory extends Controller {
	public function index() 
	{
		$this->load->language('report/product_purchased');

		$this->document->setTitle("Card's Order History");

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m').'-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_unit_id'])) {
			$filter_unit_id = $this->request->get['filter_unit_id'];
		} else {
			$filter_unit_id = null;
		}
		if (isset($this->request->get['filter_card_number'])) {
			$filter_card_number = $this->request->get['filter_card_number'];
		} else {
			$filter_card_number = null;
		}
		if (isset($this->request->get['filter_grower_id'])) {
			$filter_grower_id = $this->request->get['filter_grower_id'];
		} else {
			$filter_grower_id = null;
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

		if (isset($this->request->get['filter_unit_id'])) {
			$url .= '&filter_unit_id=' . $this->request->get['filter_unit_id'];
		}
		
		if (isset($this->request->get['filter_card_number'])) {
			$url .= '&filter_card_number=' . $this->request->get['filter_card_number'];
		}
		if (isset($this->request->get['filter_grower_id'])) {
			$url .= '&filter_grower_id=' . $this->request->get['filter_grower_id'];
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
			'text' => "Card's Order History",
			'href' => $this->url->link('report/cardhistory', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
        $this->load->model('report/card');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_unit_id' => $filter_unit_id,
			'filter_card_number' => $filter_card_number,
			'filter_grower_id' => $filter_grower_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$product_total = $this->model_report_card->getcardhistortyTotal($filter_data);

		$data['products']=$results = $this->model_report_card->getcardtrans($filter_data);
		//print_r($results);
		/*
		foreach ($results as $result) { //print_r($results);
		if($result['tr_type']=='DR')
		{
			$result['tr_type']='DB';
		}
			$data['products'][] = array(
			'storename'       => $result['storename'],
			'user'       => $result['user'],
				'amount'       => $result['amount'],
				'order_id'      => $result['order_id'],
				'tr_type'   => $result['tr_type'],
				'payment_method'      => $result['payment_method'],
                            'create_time'      => $result['create_time'],
                            
		'updated_cash'=>$result['updated_cash'],
		'remarks'=>$result['remarks']
                                
			);
		}*/

		$data['heading_title'] = "Card's Order History";
		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/inventory_ledger');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');

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
		
		$data['order_units'] = $this->model_report_card->getUnits();
		
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_unit_id'])) {
			$url .= '&filter_unit_id=' . $this->request->get['filter_unit_id'];
		}
		
		if (isset($this->request->get['filter_card_number'])) {
			$url .= '&filter_card_number=' . $this->request->get['filter_card_number'];
		}
		if (isset($this->request->get['filter_grower_id'])) {
			$url .= '&filter_grower_id=' . $this->request->get['filter_grower_id'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/cardhistory', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_unit_id'] = $filter_unit_id;
		$data['filter_card_number'] = $filter_card_number;
		$data['filter_grower_id'] = $filter_grower_id;
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/card/cardhistory.tpl', $data)); 
	}
	public function download_excel() 
	{
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m').'-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_unit_id'])) {
			$filter_unit_id = $this->request->get['filter_unit_id'];
		} else {
			$filter_unit_id = null;
		}
		if (isset($this->request->get['filter_card_number'])) {
			$filter_card_number = $this->request->get['filter_card_number'];
		} else {
			$filter_card_number = null;
		}
		if (isset($this->request->get['filter_grower_id'])) {
			$filter_grower_id = $this->request->get['filter_grower_id'];
		} else {
			$filter_grower_id = null;
		}
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_unit_id' => $filter_unit_id,
			'filter_card_number' => $filter_card_number,
			'filter_grower_id' => $filter_grower_id
		);

		$this->load->model('report/card');

		$data['products']=$results = $this->model_report_card->getcardtrans($filter_data);
		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Card Number',
        'Grower ID',
        'Unit',
        'Store Name',
		'Date',
		'Invoice No',
		'Payment Method',
		'Order Total',
		'Tagged Amount',
		'Cash Amount ',
		'Subsidy Amount '
    );
	$fileIO = fopen('php://memory', 'w+');
	fputcsv($fileIO, $fields,',');
	foreach($results as $data)
    {   
		$fdata=array(
                            $data['card_serial_no'],
                            $data['grower_id'],
                            $data['unit_name'],
                            $data['storename'],
                            $data['datea'],
                            $data['order_id'],
                            $data['payment_method'],
                            $data['total'],
                            $data['tagged'],
                            $data['cash'],
							$data['subsidy']
                            );
			 fputcsv($fileIO,  $fdata,",");
	}
	fseek($fileIO, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment;filename="Card_order_history'.date('dMy').'.csv"');
    header('Cache-Control: max-age=0');
    fpassthru($fileIO);  
    fclose($fileIO);
	/*
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    $row = 2;
    
    foreach($results as $data)
    {       
		if($data['tr_type']=='DR')
		{
			$data['tr_type']='DB';
		}
		$col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['card_serial_no']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['grower_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['unit_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['storename']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['datea']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['order_id']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['payment_method']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['total']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['tagged']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['cash']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['subsidy']);
        $row++;
    }

    

    
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Card_order_history'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	*/
	}
	
	
	public function OrderDetail()
	{
		$oid=$this->request->get['oid'];
		$this->load->model('report/cash');
		//echo "nhjfbv";
		$results= $this->model_report_cash->getorder_summarydetail($oid);
		$results2= $this->model_report_cash->getorder_totaldetail($oid);
		foreach ($results as $result) {//print_r($result); 
	    $data['orders'][] = array(
				'name' => $result['name'],
				'quantity'   => $result['quantity'],
				'price'      => $result['price'],
				'tax'     => $result['tax'],  
				'total'      => $result['total']
			);
		}
		$data['order_total']=$results2['total'];
		$data['order_cash']=$results2['cash'];
		$data['order_tagged']=$results2['tagged'];
		$data['order_subsidy']=$results2['subsidy'];
		//print_r( $data);
       $this->response->setOutput(json_encode($data));
	}
}