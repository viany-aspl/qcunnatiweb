<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
ini_set('max_execution_time', 3000);  //3000 seconds = 50 minutes

class ControllerReportdsclReconciliation extends Controller {
	public function index() {
		$this->load->language('report/reconciliation');

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

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
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
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
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
			'href' => $this->url->link('reportdscl/reconciliation', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/reconciliation');
                		 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_company' => '1',
			'filter_unit'	     => $filter_unit,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$t1=$this->model_report_reconciliation->getTotalOrdersCompanywiseDscl($filter_data);
		$order_total = $t1["total"];
		$total_tagged_amount_all=$t1["total_tagged_amount"];
		$total_tagged_amount=0;
		$results = $this->model_report_reconciliation->getOrdersCompanywiseDscl($filter_data);

		foreach ($results as $result) {
			//print_r($result);
                        		$total_tagged_amount=$total_tagged_amount+$result['tagged'];

                        $grower_info = $result['o_payment_firstname'];//$result['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                        $farmer_info=explode('-', $grower_info);
      
                $grower_id='';//@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[0]));
                $father_name=ucwords(strtolower(@$farmer_info[1]));
	if(empty($grower_id))
	{
	$grower_id=$result['shipping_firstname'];
	}
	if(empty($grower_id))
	{
	$grower_id=$result['dscl_grower_id'];
	}
	if(empty($farmer_name))
	{
	$farmer_name=$result['o_payment_address_1'];
	}
	
	  $inv_no=$result['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
                if(empty($result['company']))
	 {
		$unit_name=$result['unit_name'];
	 }
	else
	{
		$unit_name=$result['company'];
	}
	$o_payment_address_1=$result['o_payment_address_1'];
					$o_payment_firstname=$result['o_payment_firstname'];
					
					if($result['bcml_tagged']!='0.00')
					{
						$taggedvalue=$result['bcml_tagged'];
					}
					else
					{
						$taggedvalue=$result['tagged'];
					}
			//if(strlen($inv_no)<10)
			{
			$data['orders'][] = array(
				
				'date'       => date($this->language->get('date_format_short'), strtotime($result['date'])),
		  'order_id'   => $inv_no ,
			'inv_no'   => $result['order_id'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => $result['total'],
                                'tagged'     => $taggedvalue,
                                'grower_id'  => $grower_id,
                                'farmer_name'=> $farmer_name,
                                'unit'       => $unit_name,
								'o_payment_address_1'=>$o_payment_address_1,
								'o_payment_firstname'=>$o_payment_firstname,
								'father_name'=>$father_name
				
			);
			}
		}
		//$data['orders']=usort($data['orders'], "cmp");
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
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('1');
		
		$url = '';
                if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}


		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportdscl/reconciliation', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
                $data['filter_store'] = $filter_store;
		$data['filter_unit'] = $filter_unit;
		$data['total_tagged_amount'] =$total_tagged_amount;
		$data['total_tagged_amount_All'] =$total_tagged_amount_all;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}
		
		$this->load->model('unit/unit');
		$data['units']=$this->model_unit_unit->getunit(array('filter_company'=>'1'));
		//print_r($data['units']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportdscl/reconciliation.tpl', $data));
	}
function cmp($a, $b)
{
    if ($a["order_id"] == $b["order_id"]) {
        return 0;
    }
    return ($a["order_id"] < $b["order_id"]) ? -1 : 1;
}

        public function download_pdf() { 
            $this->load->language('report/reconciliation');

		$this->document->setTitle($this->language->get('heading_title'));

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
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
		}
		$this->load->model('report/reconciliation'); 
                 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                                          'filter_store'	     => $filter_store,
			'filter_unit'	     => $filter_unit,
                        'filter_company' => '1',
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);
                $data['store']="";
                $data['unit']="";
		$order_total = $this->model_report_reconciliation->getTotalOrdersCompanywise($filter_data);

		$results = $this->model_report_reconciliation->getOrdersCompanywise($filter_data);

		foreach ($results as $result) {
                        /*
                        $grower_info = $this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                        $farmer_info=explode('-', $grower_info);
      
                $grower_id=@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[1]));
                $father_name=ucwords(strtolower(@$farmer_info[2]));
	$inv_no=ucwords(strtolower(@$farmer_info[3]));
*/
                $grower_info = $result['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                $farmer_info=explode('-', $grower_info);
      
                $grower_id=@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[1]));
                $father_name=ucwords(strtolower(@$farmer_info[2]));
	  $inv_no=$result['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
			$data['orders'][] = array(
				
				'date'       => date($this->language->get('date_format_short'), strtotime($result['date'])),
				'order_id'   => $inv_no ,
			'inv_no'   => $result['order_id'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => $result['total'],
                                'tagged'     => $result['tagged'],
                                'grower_id'  => $grower_id,
                                'farmer_name'=> $farmer_name,
                                'unit'       => $result['company']
				
			);
                        $data['store']=$result['store_name'];
                        $data['unit']=$result['company'];
		}
		if($filter_store==0)
                {
                    
                  $data['store']='';
                  $data['unit']='';  
                } 
		$data['start_date'] = date($this->language->get('date_format_short'), strtotime($filter_date_start));
                $data['end_date']  = date($this->language->get('date_format_short'), strtotime($filter_date_end));
		
                require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
                
                $html = $this->load->view('report/reconciliation_pdf.tpl',$data);
                //$this->response->setOutput($this->load->view('tag/order_invoice.tpl', $data));
               
                $base_url = HTTP_CATALOG;
                
                //$mpdf = new mPDF($mode, $format, $font_size, $font, $margin_left, $margin_right, $margin_top, $margin_bottom, $margin_header, $margin_footer, $orientation);
                $mpdf = new mPDF('c', 'A4', '', '', '5', '5', '25', '25', '5', '1', '');
                $header = '<div class="header" style="">
                   
<div class="logo" style="width: 100%;" >
<img src="../image/letterhead_text.png" style="height: 40px; width: 121px;" />
<img src="../image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />

                         </div>
<img src="../image/letterhead_topline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 

</div>';
    //$header='';
                $mpdf->SetHTMLHeader($header, 'O', false);
                    
                $footer = '<div class="footer">
                        
                        <img src="../image/letterhead_bottomline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 
                        <div class="address"><img src="../image/letterhead_footertext.png" style="height: 10px; margin-left: -40px;width: 120% !important;" /> </div>'
                        . '</div>';

                //$footer = '<div class="footer"><div class="address"><b>Akshamaala Solutions Pvt. Ltd. : </b>'.$data['company_address'].'</div><div class="pageno">{PAGENO}</div></div>';
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->setAutoBottomMargin = 'stretch';
                $mpdf->WriteHTML($html);
/*$mpdf->AddPage();

                $mpdf->WriteHTML('<div>
<br/><br/>
<b>Our Bank Details</b>
<br/><br/>
A/C Number - 03292020000923
<br/>
Bank Name - HDFC Bank/ Current Account
<br/>
IFSC Code - HDFC0000329
<br/><br/>
Checked and Submitted by : 


</div>
        ');
                */
                $filename='Reconciliation Report'.$order_id.'.pdf';
                //$mpdf->Output(DIR_UPLOAD.$filename,'F');
                $mpdf->Output($filename,'D');
                
                
		$this->response->setOutput($this->load->view('report/reconciliation_pdf.tpl', $data));
        }
public function download_excel() 
{
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
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
		}



		$this->load->model('report/reconciliation');
         
		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_company' => '1',
			'filter_unit'	     => $filter_unit
		);
		
		$results = $this->model_report_reconciliation->getOrdersCompanywiseDscl($filter_data);

    

    // Field names in the first row
    $fields = array(
        'Unit',
        'Store Name',
        'Store ID',
        'Order ID',
        'Inv no.',
        'Grower ID',
        'Name',
        'Date',
        'Amount',
        'Tagged',
		'Products'
    );
	$fileIO = fopen('php://memory', 'w+');
	fputcsv($fileIO, $fields,',');
	
	
	foreach ($results as $result) 
{
	$data=$result;
	$orderproductsstring='';
		$orderproducts=$this->model_report_reconciliation->getorderproducts($result['order_id']);
		//print_r($orderproducts);
		foreach($orderproducts as $orderproduct) 
		{
			
				$orderproductsstring=$orderproductsstring.$orderproduct['name'].' - ('.$orderproduct['quantity'].'),';
			
		}
		$orderproductsstring = rtrim($orderproductsstring, ','); 
$col = 0;
			//print_r($result);
                        		$total_tagged_amount=$total_tagged_amount+$result['tagged'];

                        $grower_info = $result['o_payment_firstname'];//$result['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                        $farmer_info=explode('-', $grower_info);
      
                $grower_id='';//@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[0]));
                $father_name=ucwords(strtolower(@$farmer_info[1]));
	if(empty($grower_id))
	{
	$grower_id=$result['shipping_firstname'];
	}
	if(empty($grower_id))
	{
	$grower_id=$result['dscl_grower_id'];
	}
	if(empty($farmer_name))
	{
	$farmer_name=$result['o_payment_address_1'];
	}
	
	  $inv_no=$result['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
                if(empty($result['company']))
	 {
		$unit_name=$result['unit_name'];
	 }
	else
	{
		$unit_name=$result['company'];
	}
	$o_payment_address_1=$result['o_payment_address_1'];
					$o_payment_firstname=$result['o_payment_firstname'];
					
					if($result['bcml_tagged']!='0.00')
					{
						$taggedvalue=$result['bcml_tagged'];
					}
					else
					{
						$taggedvalue=$result['tagged'];
					}
		
		$fdata=array(
                            $unit_name,
                            $data['store_name'],
                            $data['store_id'],
                            $inv_no,
							$data['order_id'],
							$grower_id,
							$farmer_name,
							
                            date('Y-m-d',strtotime($data['date'])),
							number_format((float)$result['total'], 2, '.', ''),
                            number_format((float)$taggedvalue, 2, '.', ''),
                            
							$orderproductsstring
                            );
			 fputcsv($fileIO,  $fdata,",");
		
		}

	
	fseek($fileIO, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment;filename="reconciliation_report_'.date('dMy').'.csv"');
    header('Cache-Control: max-age=0');
    fpassthru($fileIO);  
    fclose($fileIO);
	/*
	include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);
	
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
    
    $row = 2;
    

foreach ($results as $result) 
{
	$orderproductsstring='';
		$orderproducts=$this->model_report_reconciliation->getorderproducts($result['order_id']);
		//print_r($orderproducts);
		foreach($orderproducts as $orderproduct) 
		{
			
				$orderproductsstring=$orderproductsstring.$orderproduct['name'].' - ('.$orderproduct['quantity'].'),';
			
		}
		$orderproductsstring = rtrim($orderproductsstring, ','); 
$col = 0;
			//print_r($result);
                        		$total_tagged_amount=$total_tagged_amount+$result['tagged'];

                        $grower_info = $result['o_payment_firstname'];//$result['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                        $farmer_info=explode('-', $grower_info);
      
                $grower_id='';//@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[0]));
                $father_name=ucwords(strtolower(@$farmer_info[1]));
	if(empty($grower_id))
	{
	$grower_id=$result['shipping_firstname'];
	}
	if(empty($grower_id))
	{
	$grower_id=$result['dscl_grower_id'];
	}
	if(empty($farmer_name))
	{
	$farmer_name=$result['o_payment_address_1'];
	}
	
	  $inv_no=$result['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
                if(empty($result['company']))
	 {
		$unit_name=$result['unit_name'];
	 }
	else
	{
		$unit_name=$result['company'];
	}
	$o_payment_address_1=$result['o_payment_address_1'];
					$o_payment_firstname=$result['o_payment_firstname'];
					
					if($result['bcml_tagged']!='0.00')
					{
						$taggedvalue=$result['bcml_tagged'];
					}
					else
					{
						$taggedvalue=$result['tagged'];
					}
			
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, @$unit_name );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row,  $inv_no);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $grower_id);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $farmer_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, date('Y-m-d',strtotime($result['date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, number_format((float)$result['total'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format((float)$taggedvalue, 2, '.', ''));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $orderproductsstring);
		$row++;	
		}



    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reconciliation_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
    */
    }

//download item

public function download_item_excel() {
            
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
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
		}

		

		$this->load->model('report/reconciliation');
                

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
                        'filter_unit'	     => $filter_unit,
                        'filter_company' => '1',
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);

		$order_total = $this->model_report_reconciliation->getTotalOrdersCompanywise($filter_data);

		$results = $this->model_report_reconciliation->getOrdersCompanywise($filter_data);
    //exit;
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Unit',
        'Store Name',
        'Store ID',
        'Order ID',
	'Invoice_No',
        'Grower ID',
        'Name',
        'Date',
	'Product Name',
	'Quantity',
	'Price',
	'Tax',
	'Total (Cash+Tagged)'
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
   /*     
         $grower_info = $this->model_report_reconciliation->getGrowerDetails($data['order_id']);
         $farmer_info=explode('-', $grower_info);
         $grower_id=@$farmer_info[0];
         $farmer_name=ucwords(strtolower(@$farmer_info[1]));
         $father_name=ucwords(strtolower(@$farmer_info[2]));
		$inv_nos=ucwords(strtolower(@$farmer_info[3]));
*/
 $grower_info = $data['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                $farmer_info=explode('-', $grower_info);
      
                $grower_id=@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[1]));
                $father_name=ucwords(strtolower(@$farmer_info[2]));
	  $inv_nos=$data['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));

	//get product details row		                
	$orderinfos=$this->model_report_reconciliation->getOrder_detail($data['order_id']);
	foreach($orderinfos as $orderinfo){
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, @$data['company'] );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $inv_nos);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $grower_id);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $farmer_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, date('Y-m-d',strtotime($data['date'])));
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, number_format((float)$data['total'], 2, '.', ''));
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format((float)$data['tagged'], 2, '.', ''));           
	//new row           
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $orderinfo['name']);           
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $orderinfo['quantity']);           
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $orderinfo['price']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $orderinfo['tax']);           
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row,number_format((float)($orderinfo['total']+($orderinfo['tax']*$orderinfo['quantity'])),2,'.',''));           
          

        $row++;
	}

    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reconciliation_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
    
    }




     public function email_excel() {
        
        

				
		$this->load->model('report/sale');
                
		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => '',
			'filter_date_start'	     => date('Y-m-d'),
			'filter_date_end'	     => date('Y-m-d'),
			
			'filter_order_status_id' => '5'
		);

		

		$results = $this->model_report_sale->getOrders($filter_data);

		

        
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Date Start',
        'Date End',
        'No. Orders',
        'Store',
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
    { 
        $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date('Y-m-d',strtotime($data['date_start'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('Y-m-d',strtotime($data['date_end'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['orders']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['total']);            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="tagged_report_'.date('dMy').'.xls"');
    //header('Cache-Control: max-age=0');

    //$objWriter->save('php://output');
    $filename='sale_order_report_'.date('ymdhis').'.xls';
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
		//$mail->From = "mail.akshamaala.in";
		//$mail->FromName = "Support Team";
                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Sale orders Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('chetan.singh@akshamaala.com', "Chetan Singh");
                //$mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
                

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
///////////create bill start here//////////////
public function create_bill() { 
            $this->load->language('report/reconciliation');

		if (isset($this->request->post['filter_date'])) {
			$filter_date = $this->request->post['filter_date'];
		} else {
			$filter_date= date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		

		if (isset($this->request->post['filter_store_2'])) {
			$filter_store = $this->request->post['filter_store_2'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->post['filter_unit_2'])) {
			$filter_unit = $this->request->post['filter_unit_2'];
		} else {
			$filter_unit = 0;
		}
		$this->load->model('report/reconciliation');
                            $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                                          'filter_store'	     => $filter_store,
			'filter_unit'	     => $filter_unit,
			'filter_date'	     => $filter_date,
			'filter_company' => '1'
		);

                            $file_a_array=$this->model_report_reconciliation->get_file_data_tagged($filter_data);
                            $file_in_database=""; 
		$file_server= "";
		 if(count($file_a_array)>0)
		{
			$file_in_database="yes"; 
			$path = "../system/upload/tagged_pdf";
                                          $files = scandir($path);
                                          $file_server="";
                                          foreach ($files as &$value) 
                                          {
                                            
                                            if(trim($file_a_array[0]["file_name"])==trim($value))
                                           { 
                                               $file_server= "yes";
		
                                             }
                 
                                          }
			
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			    if(($file_in_database=="yes") && ($file_server=="yes"))
			    {
					
					$file_url = DIR_UPLOAD.'tagged_pdf/'.$file_a_array[0]["file_name"];
                                                                      header('Content-Type: application/pdf');
                                                                      header("Content-Transfer-Encoding: Binary");
                                                                      header("Content-disposition: attachment; filename=".$file_a_array[0]["file_name"]);
                                                                      readfile($file_url);
				              header('location: '.$_SERVER['HTTP_REFERER']);
				             exit;
			}
			else ///if file is already generated for same filter////////  
			{
                           $data['store']="";
                           $data['unit']="";
                
               
                 if(($file_a_array[0]["file_name"]!="") && ($file_server!="yes"))//////means data in database but file is not available at server////////////
                 {
                    $filename=$file_a_array[0]["file_name"];
	      $data["file_aspl"]=$file_a_array[0]["sid"];
                 } 
	   else
	   {
                      $filename='tagged_order_'.$filter_date.'_'.$filter_store.'_'.$filter_unit.'.pdf';  
	        $data["file_aspl"]=$this->model_report_reconciliation->add_file_data_tagged($filter_data,$filename);   
	   }
		//$results = $this->model_report_reconciliation->getOrders($filter_data);

		
		
		$total_tagged_amount_all=$t1["total_tagged_amount"];
		$total_tagged_amount=0;
		$this->load->model('pos/dscl');
		//$dscl_count=$this->model_pos_dscl->getorder_count($filter_data);    
		$cardsql="select  order_id  from oc_order_req_delivery where EMAIL!='tech@em3agri.com' and  UNIT_CODE='".$filter_unit."' and TO_CHAR(DATE_ADDED,'YYYY-MM-dd')='".$filter_date."' and ORDER_STATUS_ID='5' ";

		$filter_data_sql = array(
			
			'sql'=> $cardsql	
		);
		//echo "sdbhn";
		//print_r($filter_data);
		$dsclfinaldata=array();
		$akshfinaldata=array();
		$dscl_count = $this->model_pos_dscl->GetCardDataSql('GetCardDataSql',$filter_data_sql,0);
		foreach ($dscl_count as $dscl) 
		{
			 $dsclfinaldata[]= $dscl['ORDER_ID'];
		}
		$results = $this->model_report_reconciliation->getOrdersCompanywiseDscl($filter_data);
		//print_r($results);exit;
		
		foreach ($results as $result) 
		{
			 $akshfinaldata[]= $result['order_id'];
		}
		$error_order='';
		if(count($dscl_count) > count($results))
		{
			$array_diff=array_diff($dsclfinaldata,$akshfinaldata);
			if(count($array_diff)>0)
			{
				foreach($array_diff as $array_diff2)
				{
					$error_order=$error_order.$array_diff2.',';
				}
				$error_order=rtrim($error_order,',');
				$this->session->data['error']='Please check the  status for following orders : '.$error_order; 
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit;
			}
			
		}
		if(count($dscl_count) < count($results))
		{
			$array_diff=array_diff($akshfinaldata,$dsclfinaldata);
			if(count($array_diff)>0)
			{
				foreach($array_diff as $array_diff2)
				{
					$error_order=$error_order.$array_diff2.',';
				}
				$error_order=rtrim($error_order,',');
				$this->session->data['error']='Please check the order status for following orders : '.$error_order; 
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit;
			}
			
		}
		//print_r($array_diff);
		//echo '<br>';
		//print_r(($dsclfinaldata));
		//echo '<br>';
		//print_r ($akshfinaldata);
		//exit;
		
                            $total_amount=0;
	

foreach ($results as $result) {
$col = 0;
			//print_r($result);
                        		$total_tagged_amount=$total_tagged_amount+$result['tagged'];
			$orderproducts=array();
			$orderproducts=$this->model_report_reconciliation->getorderproducts($result['order_id']);

                        $grower_info = $result['o_payment_firstname'];//$result['payment_address_1'];//$this->model_report_reconciliation->getGrowerDetails($result['order_id']);
                        $farmer_info=explode('-', $grower_info);
      
                $grower_id='';//@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[0]));
                $father_name=ucwords(strtolower(@$farmer_info[1]));
	if(empty($grower_id))
	{
	$grower_id=$result['shipping_firstname'];
	}
	if(empty($grower_id))
	{
	$grower_id=$result['dscl_grower_id'];
	}
	if(empty($farmer_name))
	{
	$farmer_name=$result['o_payment_address_1'];
	}
	
	  $inv_no=$result['requisition_id'];//ucwords(strtolower(@$farmer_info[3]));
                if(empty($result['company']))
	 {
		$unit_name=$result['unit_name'];
	 }
	else
	{
		$unit_name=$result['company'];
	}
	$o_payment_address_1=$result['o_payment_address_1'];
					$o_payment_firstname=$result['o_payment_firstname'];
					
					if($result['bcml_tagged']!='0.00')
					{
						$taggedvalue=$result['bcml_tagged'];
					}
					else
					{
						$taggedvalue=$result['tagged'];
					}
			
			$data['orders'][] = array(
				
				'date'       => date($this->language->get('date_format_short'), strtotime($result['date'])),
		  'order_id'   => $inv_no ,
			'inv_no'   => $result['order_id'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => $result['total'],
		'cash'      => $result['cash'],
                                'tagged'     => $taggedvalue,
                                'grower_id'  => $grower_id,
                                'farmer_name'=> $farmer_name,
                                'unit'       => $unit_name,
								'o_payment_address_1'=>$o_payment_address_1,
								'o_payment_firstname'=>$o_payment_firstname,
								'father_name'=>$father_name,
		'o_csn'   => $result['o_csn'],
		'o_bill_no'=> $result['bill_no'],
		'orderproducts'=>$orderproducts
				
			);
		
		
                        $total_amount=$total_amount+$taggedvalue;
                        $data['store']=$result['store_name'];
                        $data['unit']=$unit_name;
		}
		if($filter_store==0)
                {
                    
                  $data['store']='';
                  //$data['unit']='';  
                } 
		$data['start_date'] = date($this->language->get('date_format_short'), strtotime($filter_date));
                $data['end_date']  = date($this->language->get('date_format_short'), strtotime($filter_date));
		
                require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
                
                $html = $this->load->view('reportdscl/reconciliation_pdf.tpl',$data);
                //exit;
               
                $base_url = HTTP_CATALOG;
                
                
                $mpdf = new mPDF('c', 'A4', '10', '', '5', '5', '25', '25', '5', '1', '');
	$mpdf->simpleTables = true;
				$mpdf->shrink_tables_to_fit=1;
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


                //$filename='tagged_order_'.$filter_date.'_'.$filter_store.'_'.$filter_unit.'.pdf';    
	  //$this->model_report_reconciliation->add_file_data_tagged($filter_data,$filename); 
               // $mpdf->Output(DIR_UPLOAD.'tagged_pdf/'.$filename,'F');  
	   $this->load->model('user/user'); 
	  $logged_user = $this->user->getId();
	  $update_total=$this->model_report_reconciliation->update_file_data_tagged($filter_data,$data["file_aspl"],$total_amount,$logged_user,$filename); 
                $mpdf->Output($filename,'D');
	  header('location: '.$_SERVER['HTTP_REFERER']);
	  echo "File created successfully"; 
           }
        }

/////////////create bill end here//////////////////

////////////////get bill start here/////////////////////////
public function get_bill()
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
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
		}
		$this->load->model('report/reconciliation');
                            $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                                          'filter_store'	     => $filter_store,
			'filter_unit'	     => $filter_unit,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);
                           $file_a_array=$this->model_report_reconciliation->get_file_data_tagged_within($filter_data);
		if(count($file_a_array)>0)
	              {
                              echo "1";
		}
		else
		{
		 echo "0";
		}

}
///////////////get bill end here///////////////
///////////////////////////create tag bill pdf in one click start here/////////////////////////////
    public function create_bill_one_click() {
		$this->load->language('tag/order');

		$data['title'] = $this->language->get('text_invoice');
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
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
                        $filter_data = array(
                                          'filter_store'	     => $filter_store,
			'filter_unit'	     => $filter_unit,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_invoice'] = $this->language->get('text_invoice');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_ship_to'] = $this->language->get('text_ship_to');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_comment'] = $this->language->get('column_comment');

		$this->load->model('tag/order');

		$this->load->model('setting/setting');

		$data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}
                $mcrypt=new MCrypt();    
               
                require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
				$this->load->model('report/reconciliation');
				//$file_a_array=$this->model_report_reconciliation->get_file_data_tagged($filter_data);
				$order_id_array=$this->model_report_reconciliation->getOrder_ids_tagged($filter_data);

				
               
                $data['orders'] = array();
                foreach ($order_id_array as $order_id)
                {
                        
                   $order_info = $this->model_tag_order->getOrder($order_id); 

                if ($order_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

				$product_data = array();

				$products = $this->model_tag_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_tag_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}
                                
                                $voucher_data = array();

				$vouchers = $this->model_tag_order->getOrderVouchers($order_id);

				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$total_data = array();

				$totals = $this->model_tag_order->getOrderTotals($order_id);

				foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
                       ///////////products from order_product_leads end here///////////////
                                $bill_id = $this->model_tag_order->getReqID($order_id);
                                $product_data_2 = array();
                         		 	$products_2 = $this->model_tag_order->getOrderProducts_2($bill_id);

				foreach ($products_2 as $product_2) {
					$option_data_2 = array();

					$options_2 = $this->model_tag_order->getOrderOptions_2($bill_id, $product_2['order_product_id']);

					foreach ($options_2 as $option_2) {
						if ($option_2['type'] != 'file') {
							$value_2 = $option_2['value'];
						} else {
							$upload_info_2 = $this->model_tool_upload->getUploadByCode($option_2['value']);

							if ($upload_info_2) {
								$value_2 = $upload_info_2['name'];
							} else {
								$value_2 = '';
							}
						}

						$option_data_2[] = array(
							'name_2'  => $option_2['name'],
							'value_2' => $value_2
						);
					}

					$product_data_2[] = array(
						'name_2'     => $product_2['name'],
						'model_2'    => $product_2['model'],
						'option_2'   => $option_data_2,
						'quantity_2' => $product_2['quantity'],
						'price_2'    => $this->currency->format($product_2['price'] + ($this->config->get('config_tax') ? $product_2['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total_2'    => $this->currency->format($product_2['total'] + ($this->config->get('config_tax') ? ($product_2['tax'] * $product_2['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}
                                
                                $voucher_data_2 = array();

				$vouchers_2 = $this->model_tag_order->getOrderVouchers_2($bill_id);

				foreach ($vouchers_2 as $voucher_2) {
					$voucher_data_2[] = array(
						'description_2' => $voucher_2['description'],
						'amount_2'      => $this->currency->format($voucher_2['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$total_data_2 = array();

				$totals_2 = $this->model_tag_order->getOrderTotals_2($bill_id);

				foreach ($totals_2 as $total_2) {
					$total_data_2[] = array(
						'title_2' => $total_2['title'],
						'text_2'  => $this->currency->format($total_2['value'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
                                
                                ///////////products from order_product end here///////////////
	           $data["farmer_info_string"]=$order_info["payment_address_1"];
                
                         $farmer_info=explode('-', $data["farmer_info_string"]);
               
	        $file_first_part=$bill_id."_".$farmer_info[0];
	        $path = "../system/upload";
                $files = scandir($path);
                $profile_pic="";
                foreach ($files as &$value) 
                {
                    $file_array=explode(".",$value);
                    $file_name=$mcrypt->decrypt($file_array[0]);
                    
                    if(trim($file_name)==trim($file_first_part))
                    { 
                     $profile_pic= $value;
		
                    }
                 
                }
             if($profile_pic=="")
	     {
	       foreach ($files as &$value) 
            {
                    $file_array=explode(".",$value);
                    $file_name=$mcrypt->decrypt($file_array[0]);
                   
	             $file_bill_id=explode("_",trim($file_name));
	            //print_r($file_array);echo $file_name.",".$file_bill_id[0]."<br/>"; 
                    if(trim($file_bill_id[0])==trim($bill_id))
                    { 
                     $profile_pic= $value;
		
                    }
               }
	      }  
                $oc_order_array=$this->model_tag_order->getOrderDetailsFromOrder($bill_id);
                $bank_num_1=explode('-',$oc_order_array["shipping_address_2"]); 
               
 	            $bank_num_2=explode('-',$oc_order_array["shipping_city"]); 
                
                // print_r($farmer_info);
				$data['orders'][] = array(
					'order_id'	         => $order_id,
					'invoice_no'         => $bill_id,
					'date_added'         => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'         => $order_info['store_name'],
					'store_url'          => rtrim($order_info['store_url'], '/'),
					'store_address'      => nl2br($store_address),
					'store_email'        => $store_email,
					'store_telephone'    => $store_telephone,
					'store_fax'          => $store_fax,
					'email'              => $order_info['email'],
					'telephone'          => $order_info['telephone'],
					'shipping_address'   => $shipping_address,
					'shipping_method'    => $order_info['shipping_method'],
					'payment_address'    => $payment_address,
					'payment_method'     => $order_info['payment_method'],
					'product'            => $product_data,
                                                                     'voucher'            => $voucher_data,
					'total'              => $total_data,
                                                                      'product_2'          => $product_data_2,
                                                                      'voucher_2'          => $voucher_data_2,
					'total_2'            => $total_data_2,
					'comment'            => nl2br($order_info['comment']),
					'grower_id'          => $farmer_info[0],
					'farmer_name'        => ucwords(strtolower($farmer_info[1])),
					'father_name'        => ucwords(strtolower($farmer_info[2])),
					'village_name'       => ucwords(strtolower($order_info["shipping_firstname"])),
                                                                      'bank_id_number'     => $bank_num_1[1],
                                                                     'identity_type'       => $bank_num_2[0],
                                                                     'identity_number'    => $bank_num_2[1],
					'profile_pic'        => $profile_pic
				);
			}


		
                  
                }
$html = $this->load->view('tag/order_invoice_all_pdf.tpl',$data);
//$this->response->setOutput($this->load->view('tag/order_invoice_all_pdf.tpl', $data));     
//print_r($data['orders']); 
                //  exit; 
                $base_url = HTTP_CATALOG;
                
                $mpdf = new mPDF('c','A4','','' , 5 , 5 , 25 , 10 , 5 , 7);
                //$mpdf = new mPDF($mode, $format, $font_size, $font, $margin_left, $margin_right, $margin_top, $margin_bottom, $margin_header, $margin_footer, $orientation);
                $header = '<div style="max-height: 1000px;min-height: 1000px;"><div class="header">
		<div class="logo">
		<img style="float: right;margin-right: 40px;height: 30px;" src="'.$base_url.'image/catalog/logo.png"  />
		</div>
 		
		<img src="../image/letterhead_topline.png" style="height: 10px; margin-left: -16px;width: 120%;" /> 
	        </div>';
    
                $mpdf->SetHTMLHeader($header, 'O', false);
                    
                $footer = '<div class="footer">
                        
                        <img src="../image/letterhead_bottomline.png" style="height: 10px; margin-left: -40px;width: 150% !important;" /> 
                        <div class="address"><img src="../image/letterhead_footertext.png" style="margin-left: -40px;width: 120% !important;" /> </div>'
                        . '</div>';

                       	 //$footer = '<div class="footer"><div class="address"><b>Akshamaala Solutions Pvt. Ltd. : </b>'.$data['company_address'].'</div><div class="pageno">{PAGENO}</div></div></div>';
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
    
                $mpdf->WriteHTML($html);
                
                $filename='tagged_order_'.$filter_date_start.'_'.$filter_date_end.'_'.$filter_store.'_'.$filter_unit.'.pdf';    
	  //$this->model_report_reconciliation->add_file_data_tagged($filter_data,$filename); 
                $mpdf->Output(DIR_UPLOAD.'tagged_pdf/'.$filename,'F');
	  
                //$mpdf->Output($filename,'D');
	  //$this->response->setOutput($this->load->view('tag/order_invoice.tpl', $data));
	  echo "File created successfully"; 
				
				
	}



///////////////////////////////////create tag bill in one click end here//////////////////////     
public function get_store_unit()
{
$store_id=$this->request->get['store_id'];
if($store_id=="20")
{
 echo "<option value='' selected='selected'>SELECT UNIT</option><option value='03'>HARIYAWAN</option><option value='04' >LONI</option>";
}
else
{
$this->load->model('report/reconciliation');
$get_data=$this->model_report_reconciliation->get_store_unit($store_id);
//print_r($get_data[0]);
echo "<option value='' >SELECT UNIT</option><option value='".$get_data[0]['unit_id']."' selected='selected'>".$get_data[0]["unit_name"]."</option>";
}

}



}