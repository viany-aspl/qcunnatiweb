<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');  
error_reporting(0);
ini_set('max_execution_time', 30000);  //3000 seconds = 50 minutes

class ControllerTagposFmdelivery extends Controller {
	public function index() {
		

		$this->document->setTitle("FM Delivery");

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
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
		if (isset($this->request->get['filter_fm_name'])) {
			$filter_fm_name =trim( $this->request->get['filter_fm_name']);
		} else {
			$filter_fm_name = 0;
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

		
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_fm_name'])) {
			$url .= '&filter_fm_name=' .trim( $this->request->get['filter_fm_name']);
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
			'text' => "FM Delivery",
			'href' => $this->url->link('tagpos/fmdelivery', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('tagpos/fmdelivery');

                		 $this->load->model('setting/store');

		$data['orders'] = array();
		$filter_fm_name=trim($filter_fm_name);
		$filter_data = array(
            'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_fm_name'	     => $filter_fm_name,
			'filter_unit'	     => $filter_unit,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		if(!empty($filter_store))
		{ 
		$t1=$this->model_tagpos_fmdelivery->getTotalOrdersCompanywise($filter_data);
		$order_total = $t1["total"];
		$total_tagged_amount_all=$t1["total_tagged_amount"];
		$total_tagged_amount=0;
		$results = $this->model_tagpos_fmdelivery->getOrdersCompanywise($filter_data);
		}

		foreach ($results as $result) { //print_r($result);
                        		$total_tagged_amount=$total_tagged_amount+$result['tagged'];

                        $grower_info = $result['payment_address_1'];
                        $farmer_info=explode('-', $grower_info);
      
                $grower_id=@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[1]));
                $father_name=ucwords(strtolower(@$farmer_info[2]));
	if(empty($grower_id))
	{
	$grower_id=$result['shipping_firstname'];
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
			$data['orders'][] = array(
				
								'date'       => date($this->language->get('date_format_short'), strtotime($result['dat'])),
								'dateorder'  => date($this->language->get('date_format_short'), strtotime($result['dateorder'])),
								'inv_no'     => $result['invoice_no'],
                                'store_name' => $result['store_name'],
                                'store_id'   => $result['store_id'],
                                'total'      => $result['total'],
                                'tagged'     => $result['tagged'],
                                'grower_id'  => $result['grower_id'],
                                'farmer_name'=> $result['fmname']
                                
				
			);
		}
		$this->load->model('pos/pos');
		if(!empty($filter_store))
		{
		$data['dunit']=$this->model_pos_pos->getunitidandcompanyid(array('storeid'=>$filter_store ));//$filter_store
		//print_r($data['dunit']);
		}
		$this->load->model('pos/bcml');
		//
		if(!empty($filter_unit))
		{
		$data['dfm']=$this->model_pos_bcml->getFM("GetFM",array('unitid'=>$filter_unit),0);
		//print_r($data['dfm']);
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
        	$data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');

		$data['fmname'] = array();//$this->model_tagpos_fmdelivery->getfm();

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

		if (isset($this->request->get['filter_fm_name'])) {
			$url .= '&filter_fm_name=' . trim($this->request->get['filter_fm_name']);
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tagpos/fmdelivery', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
        
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit=$this->request->get['filter_unit']; //exit;
		}
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
        	$data['filter_store'] = $filter_store;
		$data['filter_fm_name'] = $filter_fm_name; 
		$data['filter_unit'] = $filter_unit;
		$data['total_tagged_amount'] =$total_tagged_amount;
		$data['total_tagged_amount_All'] =$total_tagged_amount_all;

		if (isset($this->error['warning'])) {
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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tagpos/fmdelivery.tpl', $data));
	}
	

        public function download_pdf() 
		{ 
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit= 0;
		}
		if (isset($this->request->get['filter_fm_name'])) {
			$filter_fm_name = $this->request->get['filter_fm_name'];
		} else {
			$filter_fm_name= 0;
		}
		

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}


		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
		if (isset($this->request->get['filter_fm_name'])) {
			$url .= '&filter_fm_name=' . $this->request->get['filter_fm_name'];
		}
          
		$this->load->model('tagpos/fmdelivery');
                
		$data['orders'] = array();
       
		$filter_data = array(
            'filter_store'	     => $filter_store,
			'filter_fm_name'	     => $filter_fm_name,
          'filter_unit'	     => $filter_unit,
			'filter_date_start'	     => $filter_date_start
			
		);
		$this->load->library('Barcode39');
		
		/*
		$this->load->library('barcode');
        $fontSize = 10;   // GD1 in px ; GD2 in point

		$marge    = 10;   // between barcode and hri in pixel

		$x        = 125;  // barcode center

		$y        = 125;  // barcode center

		$height   = 50;   // barcode height in 1D ; module size in 2D

		$width    = 2;    // barcode height in 1D ; not use in 2D

		$angle    = 0;   // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation
		$code     = '123456789012'; // barcode, of course ;)

		$type     = 'ean13';  
		
		$im     = imagecreatetruecolor(300, 300);

		$black  = ImageColorAllocate($im,0x00,0x00,0x00);

		$white  = ImageColorAllocate($im,0xff,0xff,0xff);

		$red    = ImageColorAllocate($im,0xff,0x00,0x00);

		$blue   = ImageColorAllocate($im,0x00,0x00,0xff);

		imagefilledrectangle($im, 0, 0, 300, 300, $white);

		$barcodedata = Barcode::gd($im, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		imageline($im, $x, 0, $x, 250, $red);

		imageline($im, 0, $y, 250, $y, $red);
		*/
		
        $batch_no = $this->model_tagpos_fmdelivery->InsertFmdtl($filter_data);
		$data['batch_no']=$batch_no ;
		//$order_total = $this->model_tagpos_fmdelivery->getTotalOrdersCompanywise($filter_data);
		$batch_status = $this->model_tagpos_fmdelivery->getbatch_status($batch_no);
		
		
		$results = $this->model_tagpos_fmdelivery->getOrdersCompanywise($filter_data);

		foreach ($results as $result) { //print_r($result); exit;
			$orderproducts=array();
			$orderproducts=$this->model_tagpos_fmdelivery->getorderproducts($result['invoice_no']);
			$data['orders'][] = array(
				
				'date'       => date($this->language->get('date_format_short'), strtotime($result['dat'])),
				'inv_no'   => $result['invoice_no'],
                                				'store_name' => $result['store_name'],
                               				 'store_id'   => $result['store_id'],
                                				'total'      => $result['total'],
                                				'tagged'     => $result['tagged'],
				'village_name'     => $result['village_name'],
				'grower_name'     => $result['grower_name'],
				'tagged'     => $result['tagged'],
				'subsidy'     => $result['subsidy'],
                              				  'grower_id'  => $result['grower_id'],
                                				'farmer_name'=> $result['fmname'],
				'orderproducts'=>$orderproducts
                               
				
			);
			
			
			$resul = $this->model_tagpos_fmdelivery->insertbatchdtl($batch_no,$result['invoice_no']);
			
		}
		//echo "heloo"; exit;

		//$this->load->model('report/fmreport');
//echo "heloo"; exit;	
		$data['summaryorders']=$summaryresults=$this->model_tagpos_fmdelivery->getRecords2($filter_data);
		//print_r($data['summaryorders'][0]['fmname']); exit;
		//print_r($data);
		$html = $this->load->view('tagpos/fmdelivery_pd.tpl',$data);
		//exit;
			
				require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
                //$this->response->setOutput($this->load->view('tagpos/fmdelivery_pdf.tpl', $data));
               
                $base_url = HTTP_CATALOG;
                
                
                $mpdf = new mPDF('c', 'A4', '', '', '5', '5', '25', '25', '5', '1', '');
		$mpdf->simpleTables = true;
		$mpdf->shrink_tables_to_fit=1;
         if(!empty($batch_status))
		{
			$mpdf->SetWatermarkText('DUPLICATE','0.1'); 
			$mpdf->showWatermarkText = true;
		}
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
				//echo $html;
                $filename='FM_Delivery_'.date('Y-m-d')."_".str_replace(' ','',trim($data['summaryorders'][0]['fmname'])).'.pdf';
               // $mpdf->Output(DIR_UPLOAD.$filename,'F');
                $mpdf->Output($filename,'D');
                
                
		//$this->response->setOutput($this->load->view('tagpos/fmdelivery_pdf.tpl', $data));
		
		
        }
		public function getUnitbyStore()
		{
			$this->load->model('tagpos/fmdelivery');

			$sid=$this->request->post['storeid'];
			//print_r($sid); exit;
			if (isset($this->request->post['storeid']))
			{
				$this->load->model('pos/pos');
				$dunit=$this->model_pos_pos->getunitidandcompanyid(array('storeid'=>$sid ));//$filter_store
				
				$dpunit= count($dunit);
				echo $dpunit;
				echo ' <option value=""> Select Unit</option> ';
				for($n=0;$n<$dpunit;$n++)
				{
					echo '<option value="'.$dunit[$n]['unit_id'].'">'.$dunit[$n]['unit_name'].'</option>';

				}
			}
		}
		public function getfm()
		{
			$this->load->model('tagpos/fmdelivery');

			$uid=$this->request->post['unitid'];
			//print_r($sid); exit;
			if (isset($this->request->post['unitid']))
			{
				
				$this->load->model('pos/bcml');
		        $dunit=$this->model_pos_bcml->getFM("GetFM",array('unitid'=>$uid),0);
				$dpunit= count($dunit);
				echo $dpunit;
				echo ' <option value=""> Select FM</option> ';
				for($n=0;$n<$dpunit;$n++)
				{
					echo '<option value="'.$dunit[$n]['FM_CODE'].'">'.$dunit[$n]['FM_NAME'].'</option>';
				}
			}
		}
		
		public function getbatch() {
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Unit Add',
			'href' => $this->url->link('unit/unit', 'token=' . $this->session->data['token'], 'SSL')
		);
		$this->load->model('unit/unit');
		$data['token'] = $this->session->data['token'];
	        	$data['cancel']=$this->url->link('unit/unit', 'token=' . $this->session->data['token'], 'SSL');
               	 if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                    if($this->request->post['unit_name'] !="")
                    {
			$category_id = $this->model_unit_unit->addunit($this->request->post);

						

			$this->session->data['success'] ="Unit Added Sucessfully !";

			$this->response->redirect($this->url->link('unit/unit', 'token=' . $this->session->data['token'], 'SSL'));
		        
                        
                    }
                }


		$data['unit'] = array();

		
		$data['companies'] = $this->model_unit_unit->getCompanies();


		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_url'] = $this->language->get('column_url');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tagpos/fmbatch.tpl', $data));
	}
	public function getbatchinv()
		{
			$this->load->model('tagpos/fmdelivery');

			$batch=$this->request->post['batch'];
			//print_r($sid); exit;
			if (isset($this->request->post['batch']))
			{
				
				$this->load->model('tagpos/fmdelivery');
		        $binvcount=$this->model_tagpos_fmdelivery->getbatchinvcount($batch);				
				$this->response->setOutput( $binvcount);
				
			}
		}
		
		private function getbillhtml($data,$inv,$copy_type)
		{
			
			/*$("#prnt_stadd").html(data['stadd']);	
							$("#prnt_dt").html(data['date_added']);
							$("#prnt_invoice").html(data['invoiceno']);
							$("#prnt_oid").html(data['oid']);
							$("#prnt_ref_n").html(order_id);
							$("#prnt_retailop").html(data['opname']);
							$("#prnt_gstn").html(data['stadd']);
							$("#prnt_cmobile").html(data['telephone']);
							$("#prnt_cid").html(data['cid']);
							$("#prnt_fname").html(data['far_name']);
							$("#prnt_vname").html(data['vill_name']);
							$("#prnt_deltype").html(data['deliverymode']);
							$("#prnt_fm").html(data['fmname']);
							$("#prnt_pm").html("Tagged");
							
							$("#prnt_sb_total").html(data['subtotal']);
							$("#prnt_total_tax").html(data['tax']);
							$("#print_tax_desc").html(data['gtax']);
							$("#print_order_total").html(data['total']);
							$("#print_tagged_amount").html(data['tagged']);
							
							
							
							//assign product							
							for(var pcount=0;pcount<(data['products']).length;pcount++ )
							{
								var nextt = pcount + 1;
								var print_tbl_prd_var='';								
								print_tbl_prd_var='<tr><td class="text-left">'+nextt+'</td><td class="text-left">'+data['products'][pcount]['name']+' HSN - '+data['products'][pcount]['name']+'</td><td class="text-left">'+data['products'][pcount]['quantity']+'</td><td class="text-left">'+data['products'][pcount]['price']+'</td><td class="text-left">'+(data['products'][pcount]['price']*data['products'][pcount]['quantity'])+'</td></tr>';
								$("#print_tbl_prd").append(print_tbl_prd_var);									
							}*/
							$log=new Log("printallinvoice-".date('Y-m-d').".log");
							$print_tbl_prd_var='';
							$icount=1;
                        foreach ($data['products'] as $value) {
							$log->write($value);
							$print_tbl_prd_var.='<tr><td class="text-left">'.$icount.'</td><td class="text-left">'.$value['name'].' HSN - '.$value['name'].'</td><td class="text-left">'.$value['quantity'].'</td><td class="text-left">'.round($value['price'],2).'</td><td class="text-left">'.(round($value['price']*$value['quantity'],2)).'</td></tr>';
							$icount++;
							$log->write($print_tbl_prd_var);
						}
						if($copy_type=='1'){ $copy_type2="Center Copy";}
						if($copy_type=='2'){ $copy_type2="Mill Copy";}
			$html='<div id="" class="for_print"  >
  <div class="scrollbar_wrapper" id="scrollbar2" style="">  
              <div class="scrollbar">
                <div class="track">
                    <div class="thumb">
                        <div class="end"></div>
                    </div>
                </div>
              </div>
             
              <div class="viewport">
                  <div class="overview" style="padding: 0px 4px;">  
                    <div class="order_head">
			<div class="">
			<h1 style="text-align: center;">UNNATI</h1>
		<h4 style="text-align: center; margin:0px; padding:0px;line-height:0px;">'.$copy_type2.'</h4>
		<hr />
		
		<span id="prnt_stadd">'.$data['stadd'].'</span>

			</div>

                      
					  <hr />
					  Retail Invoice 
					  <hr />
					  <div class="order_id pull-left">
                          Date - <span id="prnt_dt">'.$data['date_added'].'</span>
                      </div>
					  <br />
					  <div class="order_id pull-left">
                          Invoice Number - <span id="prnt_invoice">'.$data['invoiceno'].'</span>
                      </div>
					   <br />
                      <div class="order_id pull-left">
                          Order ID - <span id="prnt_oid">'.$data['oid'].'</span>
                      </div>
		<br />
                      <div class="order_id pull-left">
                         Refrence Number - <span id="prnt_ref_n">'.$inv.'</span>
                      </div>

					   <br />
					  <div class="order_id pull-left">
                          Retail OP - <span id="prnt_retailop">'.$data['opname'].'</span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Office Name - <span id="prnt_officename">'.$data['stname'].'</span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          GSTN -  <span id="prnt_gstn">'.$data['gstn'].'</span>
                      </div>
					   <br /> 
					  <div class="order_id pull-left">
                          Customer Mobile -  <span id="prnt_cmobile">'.$data['telephone'].'</span>
                      </div>
					   <br/>
					  <div class="order_id pull-left">
                          Customer ID - <span id="prnt_cid">'.$data['cid'].'</span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Farmer Name - <span id="prnt_fname">'.$data['far_name'].'</span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Village Name -  <span id="prnt_vname">'.$data['vill_name'].'</span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Delivery Type -  <span id="prnt_deltype">Field Delivery</span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Field Motivator - <span id="prnt_fm">'.$data['fmname'].'</span>
                      </div>
					   <br />
					  <div class="order_id pull-left">
                          Payment Mode -  <span id="prnt_pm">Tagged</span>
                      </div>
					   <br />
                      <div class="clear"></div>
                      
                      <hr />
                    </div>  
                      <hr />
                    <table class="table table-bordered cart_table" style="font-size: 90%;">
                        <thead style="border: 2px solid black;">
                          <tr style="border: 2px solid black;">
								<th style="border-right: 2px solid black;">S.N. </th>
                            <th style="border-right: 2px solid black;">PRODUCT NAME  </th>
                            <th style="border-right: 2px solid black;">QTY  </th>
                            <th style="border-right: 2px solid black;">RATE  </th>
                            <th>AMOUNT </th>
                           
                          </tr>  
                        </thead>  
                        <tbody id="print_tbl_prd">'.
						
						$print_tbl_prd_var
                        .'</tbody>
							
                    </table>
					<hr/><br />
					<table class="table table-bordered cart_table" style="font-size: 90%;">
                        
                        <tbody>
                        
                       
							<tr>
								
                          
                            <td style="text-align: right;">Sub Total : <span id="prnt_sb_total">'.round($data['subtotal'],2).'</span></td>
                           
                           
                         </tr>
							<tr>
								
                          
                            <td style="text-align: right;">Tax : <span id="prnt_total_tax">'.round($data['tax'],2).'</span></td>
                           
                         </tr>
						  </tbody>
                    </table>
					<hr/><br />
					<div class="stor_logo pull-left"  >
                         
					Tax Description <br />
					<span id="print_tax_desc">'.$data['gtax'].'</span>
                  </div>
					  
					<br /><br /><br />
                  <div style="text-align: left;width: 100%;font-size: 17px;font-weight: bold;margin-left: 30px;"><br />
							TOTAL AMOUNT (Rs.) '.round($data['total'],2).'
					</div> 
					
			
                    
					<br />
					<div class="stor_logo pull-left">
                          
					* Tagged Amount : <span id="print_tagged_amount">'.round($data['tagged'],2).'</span><br />
					* Cash Amount : 0
					<br /><br />
                  </div>
					  <br />
					<div class="stor_logo pull-left"  >
                      <div style="text-align: center;">Disclaimer</div> 
					  
						* Goods once sold will not be returned.<br />
						* This invoice can`t be used for Tax Credit Input.
						<br /><br />
                  </div>
					<br /><br /><br />
					<div class="stor_logo pull-center" style="text-align: center;" >
                          
						(Akshamaala Solutions Pvt. Ltd.)<br />
						CIN: U72200DL2010PTC209266<br />
						For any queries call - 0120 4040160<br />
						Website - www.unnati.world<br />
						// Have a good Crop //
						<br />
                  </div>
               </div>
              </div>
            </div>
  
  </div>';
			
			return $html;
			
		}
		
	public function printallinvoice()
	{
		
		$log=new Log("printallinvoice-".date('Y-m-d').".log");
		    $this->load->model('tagpos/fmdelivery');
			$batch=$this->request->post['batch'];
			$copy_type=$this->request->get['copy_type'];
			$log->write("print All invoice");
			$log->write($copy_type);
			if (isset($this->request->post['batch']))
			{
				
				$this->load->model('tagpos/fmdelivery');
				$finaldata=array();
		        	$binvcount=$this->model_tagpos_fmdelivery->getbatchinvoice($batch);
				$log->write($binvcount);
				foreach ($binvcount as $value) {
					$log->write($value['invoice_no']);
				$finalhtml=$this->getbillhtml($this->OrderProducts($value['invoice_no'] ),$value['invoice_no'],$copy_type);
				$finaldata[]=array('inv_no'=>$value['invoice_no'] ,'inv_html'=>$finalhtml);				
				}
				$log->write($finaldata);
				$this->response->setOutput(json_encode($finaldata));//$binvcount;
				
			}
		
	}



	
	public function OrderProducts($filter_order_id ) 
		{

		$log=new Log("fmdeliveryorder-".date('Y-m-d').".log");
		 $this->request->get['order_id']=$filter_order_id ;
		$data['products'] = array();
		$log->write("hist");
                            $filter_order_id =$this->request->get['order_id'];                                                                                   			
				$log->write("hist2");
			$this->load->model('sale/order');
			$this->load->model('setting/setting');
			$this->load->model('user/user');
		$resord=$this->model_sale_order->getOrderUser($filter_order_id);
		$results = $this->model_sale_order->getOrderProducts($filter_order_id);
						$log->write("hist3");	
		$store_id=$this->model_sale_order->getOrderStoreId(($this->request->get['order_id']));
						$log->write("hist4");
		$store_add=$this->model_setting_setting->getSettingbykey('config','config_address', $store_id);

		foreach ($results as $result) {

		$log->write($result);
			$data['products'][] = array(
				'order_product_id'      =>( $result['order_product_id']),
				'product_id'      =>( $result['product_id']),
				'subsidy'	=> (empty($this->model_sale_order->getProductSubsidy($result['product_id'],$store_id))?0:$this->model_sale_order->getProductSubsidy($result['product_id'],$store_id)),
				'name'        =>( $result['name']),
				'model'         => ($result['model']),
				'quantity'    =>( $result['quantity']),
				'price' => ( ($result['price']) ),
				'total' => (($result['total'])+(round($result['tax'])*$result['quantity'])),
				'tax'	=> (($result['tax'])),
			
			);
		
		}

		//$log->write($this->model_sale_order->getorderSubTotalvalue($filter_order_id));

		$data['total']=($this->model_sale_order->getorderTotalvalue($filter_order_id));
		$data['tax']=($this->model_sale_order->getorderTaxvalue($filter_order_id));
		$data['subtotal']=($this->model_sale_order->getorderSubTotalvalue($filter_order_id));
		$data['subsidy']=($this->model_sale_order->getOrderSubsidy(($filter_order_id)));
		$data['cash']=($this->model_sale_order->getOrdercash(($filter_order_id)));
		$data['gstn']=($this->model_setting_setting->getSettingbykey('config','config_gstn',$store_id));		
		try{
				$log->write("hist6");
			$orderDetails=$this->model_sale_order->getOrderInfo($filter_order_id);
			$log->write("hist7");
			$log->write("in data");
			$log->write($orderDetails);
			$this->load->model('lead/orderleads');
			$log->write("in data y");	
		//getOrder
		//new addidtion
		$oid=$this->model_lead_orderleads->getbill_to_requisition($filter_order_id);
					$log->write("in data y ".$oid);
	
		$orderlead=$this->model_lead_orderleads->getOrderdtl($oid);
		$log->write("in data s ");
				$log->write($orderlead);
		$data['cus_id']=($orderlead[0]['payment_address_1']);
		if(empty($orderlead[0]['payment_address_1']))
		{
			if(!empty($orderDetails['shipping_firstname']))
			{
			$data['cus_id']=($orderDetails['shipping_firstname']);
			}
			else
			{
			$data['cus_id']=($orderDetails['customer_id']);
			}
		}
		$data['far_name']=($orderlead[0]['payment_firstname']);
		if(!empty($orderDetails['firstname']))
		{
		$farm_father_array=explode('-',$orderDetails['firstname']);
		}
		else
		{
		$farm_father_array=array($orderDetails['shipping_firstname'],'');
		}
		if(empty($orderlead[0]['payment_firstname']))
		{
			$data['far_name']=($farm_father_array[0]);
		}
		$data['fath_name']=($orderlead[0]['payment_lastname']);
		if(empty($orderlead[0]['payment_lastname']))
		{
			$data['fath_name']=($farm_father_array[1]);
		}
		$data['vill_name']=($orderlead[0]['shipping_firstname']);
		if(empty($orderlead[0]['shipping_firstname']))
		{
			$data['vill_name']=($orderDetails['payment_address_1']);
		}
		$data['stor_name']=($orderlead[0]['store_name']);
		if(empty($orderlead[0]['store_name']))
		{
			$data['stor_name']=($orderDetails['store_name']);
		}

		$data['cid']=($orderlead[0]['payment_address_1']);
		if(empty($orderlead[0]['payment_address_1']))
		{
			if(!empty($orderDetails['shipping_firstname']))
			{
			$data['cid']=($orderDetails['shipping_firstname']);
			}
			else
			{
			$data['cid']=($orderDetails['customer_id']);
			}
		}
		$data['fname']=($orderlead[0]['payment_firstname']);
		if(empty($orderlead[0]['payment_firstname']))
		{
			$data['fname']=($farm_father_array[0]);
		}
		$data['lname']=($orderlead[0]['payment_lastname']);
		if(empty($orderlead[0]['payment_lastname']))
		{
			$data['lname']=($farm_father_array[1]);
		}
		$data['vname']=($orderlead[0]['shipping_firstname']);
		if(empty($orderlead[0]['shipping_firstname']))
		{
			$data['vname']=($orderDetails['payment_address_1']);
		}
		$data['stname']=($orderlead[0]['store_name']);
		if(empty($orderlead[0]['store_name']))
		{
			$data['stname']=($orderDetails['store_name']);
		}
		
		$delm=	$this->model_sale_order->getOrderdelivery($filter_order_id);
		$log->write($delm);
		$data['deliverymode']=($delm['delivery_type_name']);//$mcrypt->encrypt("test");
		$data['fmname']=($delm['fmname']);  
		
		$log->write($resord);
		$usernames=$this->model_user_user->getUser($resord);
		$log->write($usernames);
		$data['opname']=($usernames["firstname"]." ".$usernames["lastname"]);
		$data['stadd']=($store_add);
		$data['date_added'] =($orderDetails['date_added']);		  
		$data['invoiceno'] = $orderDetails['invoice_prefix'].'-'.$orderDetails['invoice_no'] ;
		$data['oid'] =$orderDetails['comment'] ;
		$data['tagged']=$orderDetails['tagged'];
		$data['telephone']=$orderDetails['telephone'];
		}catch(Exception $el){
			$log->write($el);
			}
				$log->write($data);
		$this->load->model('tagpos/fmdelivery');
                
		$gtax=$this->model_tagpos_fmdelivery->getgtax($filter_order_id); 

	              $data['gtax']=(json_encode($gtax));
				  //tax
				  $ttax=json_decode($data['gtax'],TRUE);
			$log->write($ttax);
			$tax_return='';
			foreach ($ttax as $key => $value) {
			    $log->write($value['title']); 
			    $log->write($value['value']); 
			    $finaltax = round(($value['value'] /  2), 2);
			    $log->write( $value['title'].'  '.$finaltax); 
			if (strpos($value['title'], '18') !== false) {	
				$tax_return.="CGST @9% ".$finaltax."</br>" ;
				$tax_return.="SGST @9% ".$finaltax."</br>" ;
				}
  			if (strpos($value['title'], '12') !== false) {

				$tax_return.="CGST @6% ".$finaltax."</br>" ;
				$tax_return.="SGST @6% ".$finaltax."</br>" ;
			}
			if (strpos($value['title'], '5') !== false) {

			$tax_return.="CGST @2.5% ".$finaltax."</br>" ;
			$tax_return.="SGST @2.5% ".$finaltax."</br>" ;
			}
			if (strpos($value['title'], '28') !== false) {	
			$tax_return.="CGST @14% ".$finaltax."</br>" ;
			$tax_return.="SGST @14% ".$finaltax."</br>" ;
			}

			}
		$data['gtax']=$tax_return;
				  //end tax
$log->write("final end".$filter_order_id);
		return ($data);

		}
	////////////////////////////////////////////////////
	public function productsalefmwise() 
	{
		$this->document->setTitle("Product Sale Fm Wise");

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_fm'])) {
			$filter_fm =trim( $this->request->get['filter_fm']);
		} else {
			$filter_fm_name = '';
		}
		if (isset($this->request->get['filter_name'])) {
            $data['filter_name']=$filter_name = $this->request->get['filter_name'];
        } else
                {
            //$filter_name = '';
        }
                if (isset($this->request->get['filter_name_id'])) {
            $data['filter_name_id']=$filter_name_id = $this->request->get['filter_name_id'];
        }
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$url = '';
        if (isset($this->request->get['filter_date_start'])) 
		{
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		

		if (isset($this->request->get['filter_fm'])) {
			$url .= '&filter_fm=' . trim($this->request->get['filter_fm']);
		}
		      if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
                if (isset($this->request->get['filter_name_id'])) {
            $url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
        }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => "Product Sale Fm Wise",  
			'href' => $this->url->link('tagpos/fmdelivery/productsalefmwise', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('tagpos/fmdelivery');
        $this->load->model('setting/store');

		$data['orders'] = array();
		$filter_fm=trim($filter_fm);
		$filter_data = array(
            'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_fm'	     => $filter_fm,
			'filter_product'	     => $filter_name_id,
			'filter_date_end'	     => $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		if(!empty($filter_store))
		{
			$t1=$this->model_tagpos_fmdelivery->totalproductsalefmwise($filter_data);
			$order_total = $t1["total"];
			
			$results = $this->model_tagpos_fmdelivery->productsalefmwise($filter_data);
		}
		foreach ($results as $result) { //print_r($result);
                        		
			$data['orders'][] = array(
				
								'date'       => date($this->language->get('date_format_short'), strtotime($result['create_time'])),
							
								'store_name'     => $result['store_name'],
                                'fmcode' => $result['fmcode'],
                                'fmname'   => $result['fmname'],
                                'product_id'      => $result['product_id'],
                                'model'     => $result['model'],
                                'qnty'  => $result['qnty'],
                                'total'=> $result['total'],
								'cash'=> $result['cash'],
								'tagged'=> $result['tagged'],
								'subsidy'=> $result['subsidy'],
								'grower_id'=> $result['grower_id'],
								'payment_firstname'=> $result['payment_firstname'],
								'order_id'=> $result['order_id'],
								'req_id'=> $result['req_id'],
								'payment_method'=> $result['payment_method']
                                
				
			);
		}
		$this->load->model('pos/pos');
		if(!empty($filter_store))
		{
		$data['dunit']=$this->model_pos_pos->getunitidandcompanyid(array('storeid'=>$filter_store ));//$filter_store
		//print_r($data['dunit']);
		}
		$this->load->model('pos/bcml');
		
		
		$data['heading_title'] = 'Product Sale Fm Wise';
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];
        $data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
		
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tagpos/fmdelivery/productsalefmwise', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
        
		
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
        $data['filter_store'] = $filter_store;
		
			

		if (isset($this->error['warning'])) 
		{
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) 
		{
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} 
		else {
			$data['success'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tagpos/productsalefmwise.tpl', $data));
	}
	public function download_excelproductsalefmwise() {
		//echo 'here';exit;
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_fm'])) {
			$filter_fm =trim( $this->request->get['filter_fm']);
		} else {
			$filter_fm_name = '';
		}
		if (isset($this->request->get['filter_name'])) {
            $data['filter_name']=$filter_name = $this->request->get['filter_name'];
        } else
                {
            //$filter_name = '';
        }
                if (isset($this->request->get['filter_name_id'])) {
            $data['filter_name_id']=$filter_name_id = $this->request->get['filter_name_id'];
        }
		
		$this->load->model('tagpos/fmdelivery');
        
		$filter_data = array(
            'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_fm'	     => $filter_fm,
			'filter_product'=>$filter_name_id,
			'filter_date_end'	     => $filter_date_end
		);
		if(!empty($filter_store))
		{
			
			$results = $this->model_tagpos_fmdelivery->productsalefmwise($filter_data);
		}	
	include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name',
		'Order Date',
        'Inv no.',
        'Grower ID',
        'Grower Details',
        'Fm Name',
        'Product Name',
        'Quantity',
        'Payment Method ',
        'Order Total',
		'Tagged-Amount',
		'Cash-Amount',
		'Subsidy-Amount'
    );
	
	$fileIO = fopen('php://memory', 'w+');
	fputcsv($fileIO, $fields,',');
	foreach($results as $data)
    { 
		$fdata=array(
                           
                            $data['store_name'],
                            date($this->language->get('date_format_short'), strtotime($data['create_time'])),
                            $data['order_id'],
							$data['grower_id'],
							$data['payment_firstname'],
							$data['fmname'],
							$data['model'],
                            $data['qnty'],
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
    header('Content-Disposition: attachment;filename="Product_Sale_Fm_Wise'.date('dMy').'.csv"');
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
        $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date($this->language->get('date_format_short'), strtotime($data['create_time'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['grower_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['payment_firstname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['fmname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['model']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['payment_method']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['total']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['cash']);
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Product_Sale_Fm_Wise'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	*/
	}
	public function productsalefmwisecash() {
		

		$this->document->setTitle("Product Sale Fm Wise");

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_fm'])) {
			$filter_fm =trim( $this->request->get['filter_fm']);
		} else {
			$filter_fm_name = '';
		}
		if (isset($this->request->get['filter_name'])) {
            $data['filter_name']=$filter_name = $this->request->get['filter_name'];
        } else
                {
            //$filter_name = '';
        }
                if (isset($this->request->get['filter_name_id'])) {
            $data['filter_name_id']=$filter_name_id = $this->request->get['filter_name_id'];
        }
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$url = '';
        if (isset($this->request->get['filter_date_start'])) 
		{
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		

		if (isset($this->request->get['filter_fm'])) {
			$url .= '&filter_fm=' . trim($this->request->get['filter_fm']);
		}
		      if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
                if (isset($this->request->get['filter_name_id'])) {
            $url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
        }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => "Product Sale Fm Wise",  
			'href' => $this->url->link('tagpos/fmdelivery/productsalefmwise', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('tagpos/fmdelivery');
        $this->load->model('setting/store');

		$data['orders'] = array();
		$filter_fm=trim($filter_fm);
		$filter_data = array(
            'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_fm'	     => $filter_fm,
			'filter_product'	     => $filter_name_id,
			'filter_date_end'	     => $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		if(!empty($filter_store))
		{
			$t1=$this->model_tagpos_fmdelivery->totalproductsalefmwisecash($filter_data);
			$order_total = $t1["total"];
			
			$results = $this->model_tagpos_fmdelivery->productsalefmwisecash($filter_data);
		}
		foreach ($results as $result) { //print_r($result);
                        		
			$data['orders'][] = array(
				
								'date'       => date($this->language->get('date_format_short'), strtotime($result['create_time'])),
							
								'store_name'     => $result['store_name'],
                                'fmcode' => $result['fmcode'],
                                'fmname'   => $result['fmname'],
                                'product_id'      => $result['product_id'],
                                'model'     => $result['model'],
                                'qnty'  => $result['qnty'],
                                'total'=> $result['total'],
								'cash'=> $result['cash'],
								'tagged'=> $result['tagged'],
								'grower_id'=> $result['grower_id'],
								'payment_firstname'=> $result['payment_firstname'],
								'order_id'=> $result['order_id'],
								'req_id'=> $result['req_id'],
								'payment_method'=> $result['payment_method']
                                
				
			);
		}
		$this->load->model('pos/pos');
		if(!empty($filter_store))
		{
		$data['dunit']=$this->model_pos_pos->getunitidandcompanyid(array('storeid'=>$filter_store ));//$filter_store
		//print_r($data['dunit']);
		}
		$this->load->model('pos/bcml');
		
		
		$data['heading_title'] = 'Product Sale Fm Wise';
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];
        $data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
		
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tagpos/fmdelivery/productsalefmwise', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
        
		
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
        $data['filter_store'] = $filter_store;
		
			

		if (isset($this->error['warning'])) 
		{
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) 
		{
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} 
		else {
			$data['success'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tagpos/productsalefmwisecash.tpl', $data));
	}
	public function download_excelproductsalefmwisecash() {
		//echo 'here';exit;
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_fm'])) {
			$filter_fm =trim( $this->request->get['filter_fm']);
		} else {
			$filter_fm_name = '';
		}
		if (isset($this->request->get['filter_name'])) {
            $data['filter_name']=$filter_name = $this->request->get['filter_name'];
        } else
                {
            //$filter_name = '';
        }
                if (isset($this->request->get['filter_name_id'])) {
            $data['filter_name_id']=$filter_name_id = $this->request->get['filter_name_id'];
        }
		
		$this->load->model('tagpos/fmdelivery');
        
		$filter_data = array(
            'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_fm'	     => $filter_fm,
			'filter_product'=>$filter_name_id,
			'filter_date_end'	     => $filter_date_end
		);
		if(!empty($filter_store))
		{
			
			$results = $this->model_tagpos_fmdelivery->productsalefmwisecash($filter_data);
		}	
	include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name',
		'Order Date',
        'Inv no.',
        'Grower ID',
        'Grower Details',
        'Fm Code',
        'Product Name',
        'Quantity',
        'Payment Method ',
        'Order Total',
		'Tagged-Amount',
		'Cash-Amount'
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
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date($this->language->get('date_format_short'), strtotime($data['create_time'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['grower_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['payment_firstname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['fmcode']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['model']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['payment_method']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['total']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['cash']);
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Product_Sale_Fm_Wise_cash'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
        
        
//\\######################################## FM Order Delivery List Created ON 25th OCT 2019 #####################################\\//

    public function deliveryNotification() {

            $this->document->setTitle("BCML Material Delivery Notification");

            $url = '';

            if (isset($this->request->get['filter_date_start'])) {
                $filter_date_start = $this->request->get['filter_date_start'];
                $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
            }else {                    
                $filter_date_start = date('Y-m-d');
            } 

            if (isset($this->request->get['filter_store'])) {
                $filter_store = $this->request->get['filter_store'];
                $url .= '&filter_store=' . $this->request->get['filter_store'];
            }else {
                $filter_store = '';
            } 

            if (isset($this->request->get['filter_fm_name'])) {
                $filter_fm_name =trim( $this->request->get['filter_fm_name']);
                $url .= '&filter_fm_name=' . $this->request->get['filter_fm_name'];
            } else {
                $filter_fm_name = '';
            }

            if (isset($this->request->get['filter_invoice'])) {
                $filter_invoice = $this->request->get['filter_invoice'];
                $url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
            }else {
                $filter_invoice = '';
            } 

            if (isset($this->request->get['page'])) {
                    $page = $this->request->get['page'];
            } else {
                    $page = 1;
            }

            if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
            }

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                    'text' => 'Dashboard',
                    'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

            $data['breadcrumbs'][] = array(
                    'text' => "Material Delivery Notification",
                    'href' => $this->url->link('tagpos/fmdelivery/deliveryNotification', 'token=' . $this->session->data['token'] . $url, 'SSL')
            );

            $this->load->model('tagpos/fmdelivery');
                             $this->load->model('setting/store');

            $data['orders'] = array();
            $filter_fm_name=trim($filter_fm_name);
            $filter_data = array(
                    'filter_store'	     => $filter_store,
                    'filter_date_start'	     => $filter_date_start,
                    'filter_fm_name'	     => $filter_fm_name,
                    'filter_invoice'	     => $filter_invoice,
                    'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
                    'limit'                  => $this->config->get('config_limit_admin')
            );
            if(!empty($filter_store))
            { 
            $t1=$this->model_tagpos_fmdelivery->getTotalOrdersCompanywise($filter_data);
            $order_total = $t1["total"];
            $total_tagged_amount_all=$t1["total_tagged_amount"];
            $total_tagged_amount=0;
            $results = $this->model_tagpos_fmdelivery->getOrdersCompanywise($filter_data);
            }
            foreach ($results as $result) { //print_r($result);
                    $total_tagged_amount=$total_tagged_amount+$result['tagged'];

                    $grower_info = $result['payment_address_1'];
                    $farmer_info=explode('-', $grower_info);

                $grower_id=@$farmer_info[0];
                $farmer_name=ucwords(strtolower(@$farmer_info[1]));
                $father_name=ucwords(strtolower(@$farmer_info[2]));
                if(empty($grower_id))
                {
                $grower_id=$result['shipping_firstname'];
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
                    $data['orders'][] = array(

                            'date'       => date($this->language->get('date_format_short'), strtotime($result['dat'])),
                            'dateorder'  => date($this->language->get('date_format_short'), strtotime($result['dateorder'])),
                            'inv_no'     => $result['invoice_no'],
                            'store_name' => $result['store_name'],
                            'fmname'   => $result['fmname'],
                            'total'      => $result['total'],
                            'tagged'     => $result['tagged'],
                            'grower_id'  => $result['grower_id'],
                            'farmer_name'=> $result['grower_name'],
                            'telephone'=> $result['telephone'],
                            'status'=> $result['delivery_status']                                  

                    );
            }
            $this->load->model('pos/pos');
            if(!empty($filter_store))
            {
            $data['dunit']=$this->model_pos_pos->getunitidandcompanyid(array('storeid'=>$filter_store ));//$filter_store
            //print_r($data['dunit']);
            }
            $this->load->model('pos/bcml');
            if(!empty($filter_unit))
            {
            $data['dfm']=$this->model_pos_bcml->getFM("GetFM",array('unitid'=>$filter_unit),0);
            //print_r($data['dfm']);
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
            $data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
            $data['fmname'] = $this->model_tagpos_fmdelivery->getfm();
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
            if (isset($this->request->get['filter_invoice'])) {
                    $url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
            }

            if (isset($this->request->get['filter_fm_name'])) {
                    $url .= '&filter_fm_name=' . trim($this->request->get['filter_fm_name']);
            }

            $pagination = new Pagination();
            $pagination->total = $order_total;
            $pagination->page = $page;
            $pagination->limit = $this->config->get('config_limit_admin');
            $pagination->url = $this->url->link('tagpos/fmdelivery/deliveryNotification', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

//		if (isset($this->request->get['filter_unit'])) {
//			$filter_unit=$this->request->get['filter_unit']; //exit;
//		}
            $data['filter_date_start'] = $filter_date_start;
            $data['filter_date_end'] = $filter_date_end;
            $data['filter_store'] = $filter_store;
            $data['filter_fm_name'] = $filter_fm_name; 
            $data['filter_invoice'] = $filter_invoice;
            $data['total_tagged_amount'] =$total_tagged_amount;
            $data['total_tagged_amount_All'] =$total_tagged_amount_all;

            if (isset($this->error['warning'])) {
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

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('tagpos/fmdelivery_notification.tpl', $data));
    }

//\\######################################## Display Invoice Detail Created on 30th Oct 2019 #####################################\\//      
    public function invoiceDetail() { 
        $log = new Log('Fm Invoice Detail '.date('Y-m-d').'.log');
        $filter_date_start = date('Y-m-d');
        if (isset($this->request->post['filter_store'])) {
                $filter_store = $this->request->post['filter_store'];
        }
        if (isset($this->request->post['filter_invoice'])) {
                $filter_invoice = $this->request->post['filter_invoice'];
        }

        $this->load->model('tagpos/fmdelivery');

        $data['orders'] = array();

        $filter_data = array(
            'filter_store'	     => $filter_store,
            'filter_invoice'	     => $filter_invoice			
        );

        $log->write($filter_data);
        $results = $this->model_tagpos_fmdelivery->getOrdersCompanywise($filter_data);

        $log->write($results);
        if(!empty($results) && is_array($results)){

            foreach ($results as $result) { //print_r($result); exit;
                    $orderproducts=array();
                    $orderproducts=$this->model_tagpos_fmdelivery->getorderproducts($result['invoice_no']);
                    $data['orders'][] = array(
                        'date'       => date($this->language->get('date_format_short'), strtotime($result['dat'])),
                        'inv_no'   => $result['invoice_no'],
                        'store_name' => $result['store_name'],
                        'store_id'   => $result['store_id'],
                        'total'      => $result['total'],
                        'tagged'     => $result['tagged'],
                        'village_name'     => $result['village_name'],
                        'grower_name'     => $result['grower_name'],
                        'tagged'     => $result['tagged'],
                        'grower_id'  => $result['grower_id'],
                        'farmer_name'=> $result['fmname'],
                        'orderproducts'=>$orderproducts,
                        'telephone'=> $result['telephone']
                    );
            }
            $data['summaryorders']='';
            $customer_no=$results[0]['telephone'];
            //$data['summaryorders']=$summaryresults=$this->model_tagpos_fmdelivery->getRecords2($filter_data);

            $html = $this->load->view('tagpos/fmdelivery_pd.tpl',$data,true);

            $msg = array('status'=>'success','responce'=>$html,'customer_mobile'=>$customer_no);
        }else{
            $html = '<div class="form-control">Invoice not found</div>';
            $msg = array('status'=>'error','responce'=>$html);
        }
        echo json_encode($msg);	
    }
    
//\\######################################## Display Invoice Detail Created on 30th Oct 2019 #####################################\\//      
    public function SendInvoiceSMSNotification() { 
        $log = new Log('Fm Invoice SMS Notification Controller '.date('Y-m-d').'.log');
        
        if (isset($this->request->post['invoice_id'])) {
            $data['invoice_id'] = $this->request->post['invoice_id'];
        }  
        
        if (isset($this->request->post['customer_mob'])) {
            $mobile_no = $this->request->post['customer_mob'];
        }  
        $log->write($this->request->post);
        $log->write('Now initating delivery status updation.');
        
        $this->load->model('tagpos/fmdelivery');

        $results = $this->model_tagpos_fmdelivery->updateFMOrderStatus($data['invoice_id']);
        
        $log->write('Initiating sms library...');
        
	$this->load->library('sms');
        $sms = new sms($this->registry);
        $log->write('Initiate sending sms...');
        $sms->sendsms($mobile_no,"29",$data);
        
        $log->write('Sending sms completed...');
        
        $log->write($results);
        echo json_encode($results);	
    }
}