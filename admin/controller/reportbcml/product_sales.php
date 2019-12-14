<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(1);
ini_set('max_execution_time', 600); //600 seconds = 10 minutes

class ControllerReportbcmlProductSales extends Controller {
	public function index() {
		$this->load->language('report/product_sale');

		$this->document->setTitle($this->language->get('heading_title'));

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
                if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			//$filter_name = '';
		}
                if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
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


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('reportbcml/sale_order', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/product_sale');
                        $this->load->model('setting/store');
		$data['orders'] = array();

		$filter_data = array(
                                          'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_name'           => $filter_name_id,
			'filter_company' => '2',
                        
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
             // print_r($filter_data);
		//$order_total = $this->model_report_product_sale->getTotalOrders($filter_data);
                            
		$t1=$this->model_report_product_sale->getTotalOrdersCompanyWise($filter_data);//print_r($t1);
		$order_total = $t1["total"];
		//$data["total_sales_all"]=$t1["Total_sales"]; 
		//$data["Total_tax_all"]=$t1["Total_tax"];
		//$data["Total_all"]=$t1["Total_sales"]+$t1["Total_tax"];
		$results = $this->model_report_product_sale->getOrdersCompanyWise($filter_data);
                            
//print_r($results); 
//exit;
		foreach ($results as $result) { //print_r($result);
		$tax_title='No-Tax';
			
			$tax_percente=round(($result['Total_tax']*100)/$result['price']);
			if($tax_percente!=0)
			{
				$tax_title='GST@'.$tax_percente.'%';
			}
			$data['orders'][] = array(
				'dats' => date($this->language->get('date_format_short'), strtotime($result['dats'])),
				'name'          => $result['name'],
				'store_name'    => $result['store_name'],
                                                        'store_id'    => $result['store_id'],
                                'No_of_orders'  => $result['No_of_orders'],
                                'Total_sales'   => $result['Total_sales'],
                                'Total_tax'     => $result['Total_tax'],
                                'Total'         => $result['Total'],
                                'tax_title'    => $tax_title,
                                'qnty'          => $result["qnty"]
				
			);
		}
 
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_tax'] = $this->language->get('column_tax');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
               
		

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


		//echo $url;
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/product_sales', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_name'] = $filter_name;
                $data['filter_name_id'] = $filter_name_id;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/product_sales.tpl', $data));
	}
        public function download_excel() {
        
        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
		}
                if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			//$filter_name = '';
		}
                if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} 
		


		$this->load->model('report/product_sale');
                
		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
                        'filter_company'   => '2',
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_name'           => $filter_name_id
		);

		
$file_name="Product_sales_report_".date('dMy').'.xls';
 header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
 header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
 header("Expires: 0");
 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 header("Cache-Control: private",false);

/*
header('Content-type: application/excel');
$filename = 'filename.xls';
header('Content-Disposition: attachment; filename='.$filename);
*/
        $results = $this->model_report_product_sale->getOrdersCompanyWise($filter_data);
        echo '<table id="example2" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
	      <th>Sale Date</th>
                    <th>Store Name</th>
                    <th>Store ID</th>
                    
                    <th>Product Name</th>
                    <th>Quantity</th>
 	      <th>Rate(without tax)</th>
	      <th>Tax title</th>
	      <th>Tax rate</th>
                    <th>Total (Sales + Tax)</th>
                    <th>Order ID</th>
                </tr>
                </thead>
                <tbody>';
$tblbody=" ";
foreach($results as $data)
{
	$tax_title='No-Tax';
			
			$tax_percente=round(($data['Total_tax']*100)/$data['price']);
			if($tax_percente!=0)
			{
				$tax_title='GST@'.$tax_percente.'%';
			}

                    echo  '<tr> 
	      <td>'.date('Y-m-d',strtotime($data['dats'])).'</td>
                    <td>'.$data['store_name'].'</td>
                    <td>'.$data['store_id'].'</td>
                    <td>'.$data['name'].'</td>
	      <td>'.$data['qnty'].'</td>
                    <td>'.number_format((float)($data['Total_sales']/$data['qnty']), 2, '.', '').'</td>
	      <td>'.$tax_title.'</td>
                    <td>'.number_format((float)$data['Total_tax'], 2, '.', '').'</td>
	      <td>'.number_format((float)($data['qnty']*(($data['Total_sales']/$data['qnty'])+$data['Total_tax'])), 2, '.', '').'</td>
                    <td>'.$data['order_id'].'</td>
                   </tr>';


}


echo '</tbody>
          </table>';

 

exit;
                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Sale Date ',
        'Store Name',
        'Store ID',
        'Product Name',
        'Quantity',
        'Rate(without tax)',
        'Tax title',
        'Tax rate',
        'Total (Sales + Tax)'
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
        //$Total=$data['Total_sales']+$data['Total_tax'];
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date('Y-m-d',strtotime($data['dats'])));
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_id']);
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['name']);
        
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['No_of_orders']);
      //  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['qnty']);
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, number_format((float)($data['Total_sales']/$data['qnty']), 2, '.', ''));
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['tax_title']);
       // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, number_format((float)$data['Total_tax'], 2, '.', ''));
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, number_format((float)($data['qnty']*(($data['Total_sales']/$data['qnty'])+$data['Total_tax'])), 2, '.', ''));
        
        
            
        

        $row++;
    }
	//print_r($results);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Product_sales_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
        public function email_excel() {
           
    		
		$data['orders'] = array();

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
		}
                           if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			//$filter_name = '';
		}
                	if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} 
		
		$this->load->model('report/product_sale');
		
		$filter_data = array(
                                         'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_name'           => $filter_name_id
		);

		//$order_total = $this->model_report_product_sale->getTotalOrders($filter_data);

		$results = $this->model_report_product_sale->getOrders($filter_data);
      //  exit;
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Sale Date ',
        'Store Name',
        'Store ID',
        'Product Name',
        'Quantity',
        'Rate(without tax)',
        'Tax title',
        'Tax rate',
        'Total (Sales + Tax)'
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
        $Total=$data['Total_sales']+$data['Total_tax'];
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date('Y-m-d',strtotime($data['dats'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['name']);
        
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['No_of_orders']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, number_format((float)($data['Total_sales']/$data['qnty']), 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['tax_title']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, number_format((float)$data['Total_tax'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, number_format((float)($data['qnty']*(($data['Total_sales']/$data['qnty'])+$data['Total_tax'])), 2, '.', ''));
        
        
            
        

        $row++;
    }


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='product_sales_report_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    //
    $mail             = new PHPMailer();

                $body = "<p>Akshamaala Solution Pvt. Ltd.</p>";
                
                $mail->IsSMTP();
                $mail->Host       = "mail.akshamaala.in";
                                                           
                $mail->SMTPAuth   = false;                 
                $mail->SMTPSecure = "";                 
                $mail->Host       = "mail.akshamaala.in";      
                $mail->Port       = 25;                  
                $mail->Username   = "mis@akshamaala.in";  
                $mail->Password   = "mismis";            

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Product Sales Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('chetan.singh@akshamaala.com', "Chetan Singh");
                $mail->AddCC('subhash.jha@unnati.world', "Subhash Jha");
                $mail->AddBCC('vipin.kumar@aspltech.com', "Vipin");

                $mail->AddAttachment(DIR_UPLOAD.$filename);
                
                if(!$mail->Send())
                {
                  echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                {
                  if(!unlink(DIR_UPLOAD.$filename))
                  {
                      echo ("Error deleting ");
                  }
                  else
                  {
                     echo ("Deleted ");
                  }
                                  
                }
        }
}