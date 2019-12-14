<?php
class ControllerReportbcmlUpdatefm extends Controller {
	private $error = array();  

	public function index() 
	{
		$this->load->language('setting/store');
		$this->document->setTitle('Update Fm with indent number');
		$this->load->model('company/company');
                $this->getform();
	}
  public function updatefmcode() {
         $this->load->model('localisation/order_status');
         $this->load->model('sale/order');
         $this->load->model('setting/store');
        $this->document->setTitle("FM code update");

        $url = '';


        if (isset($this->request->get['filter_invoice'])) {
            $filter_invoice = $this->request->get['filter_invoice'];
            $url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
        } else {
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
            'text' => "FM Code Update",
            'href' => $this->url->link('reportbcml/updatefm/updatefmcode', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        

        $data['orders'] = array();
     //   $filter_fm_name = trim($filter_fm_name);
        $filter_data = array(
            //'filter_store'	     => $filter_store,
            //'filter_date_start'	     => $filter_date_start,
           // 'filter_fm_name'	     => $filter_fm_name,
            'filter_invoice' => $filter_invoice,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );
        
     $inv =  $filter_data['filter_invoice'];
    

        if (!empty($filter_invoice)) {
            $t1 = $this->model_sale_order->getFMTotalOrdersCompanywise($filter_data);
            $order_total = $t1["total"];
            $total_tagged_amount_all = $t1["total_tagged_amount"];
            $total_tagged_amount = 0;
            $results = $this->model_sale_order->getFMOrdersCompanywise($filter_data);
            if (!empty($results)) {
                foreach ($results as $result) { //print_r($result);
                    $total_tagged_amount = $total_tagged_amount + $result['tagged'];

                    $grower_info = $result['payment_address_1'];
                    $farmer_info = explode('-', $grower_info);

                    $grower_id = @$farmer_info[0];
                    $farmer_name = ucwords(strtolower(@$farmer_info[1]));
                    $father_name = ucwords(strtolower(@$farmer_info[2]));
                    if (empty($grower_id)) {
                        $grower_id = $result['shipping_firstname'];
                    }
                    if (empty($farmer_name)) {
                        $farmer_name = $result['o_payment_address_1'];
                    }
                    $inv_no = $result['requisition_id']; //ucwords(strtolower(@$farmer_info[3]));
                    if (empty($result['company'])) {
                        $unit_name = $result['unit_name'];
                    } else {
                        $unit_name = $result['company'];
                    }
                    $data['orders'][] = array(
                        'date' => date($this->language->get('date_format_short'), strtotime($result['dat'])),
                        'dateorder' => date($this->language->get('date_format_short'), strtotime($result['dateorder'])),
                        'inv_no' => $result['invoice_no'],
                        'store_name' => $result['store_name'],
                        'fmname' => $result['fmname'],
                        'total' => $result['total'],
                        'tagged' => $result['tagged'],
                        'grower_id' => $result['grower_id'],
                        'farmer_name' => $result['grower_name'],
                        'telephone' => $result['telephone'],
                        'status' => $result['delivery_status'],
                        'fmcode'=>$result['fmcode']
                    );
                }
            }
        }
        $this->load->model('pos/pos');
        if (!empty($filter_store)) {
            $data['dunit'] = $this->model_pos_pos->getunitidandcompanyid(array('storeid' => $filter_store)); //$filter_store
            //print_r($data['dunit']);
        }
        $this->load->model('pos/bcml'); 
       // $this->load->model('updatefm/fmdelivery'); 
       
        if (!empty($filter_unit)) {
            $data['dfm'] = $this->model_pos_bcml->getFM("GetFM", array('unitid' => $filter_unit), 0);
           // print_r($data['dfm']);
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

       
        $data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
        
       
        // echo "<pre>"; print_r($data); echo "<pre>"; exit;
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
        $pagination->url = $this->url->link('reportbcml/updatefm/updatefmcode', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

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
        $data['total_tagged_amount'] = $total_tagged_amount;
        $data['total_tagged_amount_All'] = $total_tagged_amount_all;
//echo "<pre>"; print_r($data); echo "<pre>"; exit;  
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
           
        $this->response->setOutput($this->load->view('reportbcml/updatefm.tpl', $data));
    }
  
  public function updatefmname() {
      
          $fm_name_validation=  $this->request->post['fm_name'];
          $fm_code_validation=  $this->request->post['fm_code'];
          $inv=  $this->request->post['invoice_no'];
          $chk_error = false;
          $chk_data = array();
               
         
        if ($this->request->post['fm_name'] == '') {
            $chk_data[] = 'Please Fill name';
            $chk_error = true;
        }elseif (!preg_match("/^[a-zA-Z\s]+$/", $fm_name_validation)) {
            $chk_data[] = "FM name should be alphabet only";
            $chk_error = true;
        }
        
        if ($this->request->post['fm_code'] == '') {
            $chk_data[] = 'Please Fill code';
            $chk_error = true;
        }elseif (!preg_match("/^[0-9]/" ,$fm_code_validation)) {
            $chk_data[] = "FM code should be numeric only";
            $chk_error = true;
        }
        
        
        if($chk_error == false){

            $this->load->model('tagpos/fmdelivery');
           $fm_code = $this->request->post['fm_code'];
           $fm_name = $this->request->post['fm_name'];
           

            $data = $this->model_tagpos_fmdelivery->updatefmname($fm_code, $fm_name, $inv);
        }else{
            $data = array('status'=>'error','responce'=>$chk_data);
        }
        echo json_encode($data);	
       
    }

    protected function getform() 
        { 
		$url = '';
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => 'Update Fm Code',
			'href' => $this->url->link('reportbcml/updatefm', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
	    
		$data['heading_title'] = 'Tag POS';
		
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
	        		
		$this->load->model('pos/pos');
	
		$data=array();
		$strid=$this->user->getStoreId();
		//print_r($this->config->get("config_gstn")); 
		 
		$data['store_address']=$this->config->get("config_address"); 
		$data['store_op_name']=$this->user->getUserNameShow();
		$data['store_name']=$this->config->get("config_name");
		$data['store_gstn']=$this->config->get("config_gstn");

		$data['user_id']=$this->user->getId();
		if(!empty($strid))
		{
		$data1['storeid']=$strid;		
		$companydata=$this->model_pos_pos->getunitidandcompanyid($data1); 
		
		
		if(!empty($companydata))
		{
			$data1['unitid']=$companydata[0]['unit_id'];
		
			$company=strtolower($companydata[0]['company_name']);
			if($company=="bcml")
			{
			$this->load->model('pos/'.$company);
			$results = $this->{'model_pos_' . $company}->getFM("GetFM",$data1,0);
			
			if(!empty($results))
			{					
		
				foreach ($results as $ids) {		
						$data['fmlist'][] = array(
                        'id' => $ids['FM_CODE'],
                        'name'       =>$ids['FM_NAME'],
                        );
					}
			}
			}
			print_r($data['fmlist']);
                	}
		}
		$data['token']=$this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('report/updatefm.tpl', $data));
	}
        public function seachinvoice(){
            $sql = "SELECT * FROM shop.oc_order_delivery where invoice_no='831686'";
           $query=  $this->db->query($sql);
           $order_info = $query->row;
           print_r($order_info);
        }
//        public function order_now_by_excel()
//	{
//		$log=new Log("Requisition-web-excel-".date('Y-m-d').".log");
//		$log->write($this->request->get);
//		unset($this->session->data['transid']);
//		$indent_number=$this->request->get['indent_number'];
//		$data_from_search=$this->search_indent_excel();
//		$data_from_search2=(json_decode($data_from_search,true));		
//		if(!empty($data_from_search2['error']))
//		{
//			$data['error']=$data_from_search2['error'];
//			$this->response->setOutput(json_encode($data));
//			return;
//		}
//		else
//		{
//			$this->submit_order();
//		}
//	}	
//	private function search_indent_excel()
//	{
//		$log=new Log("Requisition-web-excel-".date('Y-m-d').".log");
//		$log->write($this->request->get);
//		unset($this->session->data['transid']);
//		$indent_number=$this->request->get['indent_number'];
//		$strid=$this->user->getStoreId();
//		$username=$this->user->getId();
//		$getGroupId=$this->user->getGroupId();
//		if($getGroupId!='11')
//		{
//			$log->write('You are not Autorized user for this transaction');
//			$data['error']='You are not Autorized user for this transaction'; 
//			$this->response->setOutput(json_encode($data));
//			return;
//		}
//		$data['tblheader']=$this->getRequisition($indent_number,$strid);
//		
//		$log->write('return by getRequisition');
//		$log->write($data['tblheader']);
//		if(empty($data['tblheader']['error']))
//		{
//			$log->write('no error : '.$data['tblheader']['error']);
//			$data['detail']=$this->getRequisitiondtl($strid,$indent_number,$username);
//			$log->write($data['detail']);
//			if(!empty($data['detail']['error']))
//			{
//				$log->write('some error at getRequisitiondtl');
//				$data['error']=$data['detail']['error']; 
//				return (json_encode($data));
//				
//			}
//			else
//			{
//			$this->session->data['transid']=uniqid();
//			$log->write('transid : '.$this->session->data['transid']); 
//			$log->write('return data');
//			$log->write($data);
//			return (json_encode($data));
//			}
//		}
//		else
//		{
//			$log->write('some error at getreq');
//			return (json_encode($data['tblheader']));
//		}
//	}

//	public function search_indent() 
//	{
//		
//		$log=new Log("Requisition-web-".date('Y-m-d').".log");
//		$log->write($this->request->get);
//		unset($this->session->data['transid']);
//		$indent_number=$this->request->get['indent_number'];
//		$strid=$this->user->getStoreId();
//		$username=$this->user->getId();
//		$getGroupId=$this->user->getGroupId();
////		if($getGroupId!='11')
////		{
////			$log->write('You are not Autorized user for this transaction');
////			$data['error']='You are not Autorized user for this transaction'; 
////			$this->response->setOutput(json_encode($data));
////			//return;
////		}
//		$data['tblheader']=$this->getRequisition($indent_number,$strid);
//		
//		$log->write('return by getRequisition');
//		$log->write($data['tblheader']);
//		if(empty($data['tblheader']['error']))
//		{
//			$log->write('no error : '.$data['tblheader']['error']);
//			$data['detail']=$this->getRequisitiondtl($strid,$indent_number,$username);
//			$log->write($data['detail']);
//			if(!empty($data['detail']['error']))
//			{
//				$log->write('some error at getRequisitiondtl');
//				$data['error']=$data['detail']['error']; 
//				$this->response->setOutput(json_encode($data));
//				return;
//			}
//			else
//			{
//			$this->session->data['transid']=uniqid();
//			$log->write('transid : '.$this->session->data['transid']); 
//			$log->write('return data');
//			$log->write($data);
//			$this->response->setOutput(json_encode($data));
//			}
//		}
//		else
//		{
//			$log->write('some error at getreq');
//			$this->response->setOutput(json_encode($data['tblheader']));
//		}
//		//print_r($data['detail']);
//		
//	}
//	public function submit_order() 
//	{
//		$log=new Log("Requisition-web-submit-".date('Y-m-d').".log");
//		$log->write($this->request->get);
//		$log->write($this->request->post);		
//		$strid=$this->user->getStoreId();
//		$username=$this->user->getId();
//		$order_total=urldecode($this->request->post['order_total']);
//		$glimit=urldecode($this->request->post['glimit']);
//		$mcrypt=new MCrypt();		
//		$request = "http://qc.akshapp.com/index.php?route=mpos/order/addorder";
//		$log->write($request);
//		$acash=$this->request->post['cash_total'];
//		$amtcash=$mcrypt->encrypt('0');
//		$fields_string .= 'prddtl'.'='.$mcrypt->encrypt(urldecode($this->request->post['prd_dtl'])).'&'; 
//		if(!empty($this->request->post['subsidy_total']) && empty($this->request->post['cash_total']))
//		{
//		$log->write("Tagged Subsidy");
//			$fields_string .= 'payment_method'.'='.$mcrypt->encrypt('Tagged Subsidy').'&'; 
//			$fields_string .= 'sub'.'='.$mcrypt->encrypt($this->request->post['subsidy_total']).'&'; 
//		}
//		else if(!empty($this->request->post['subsidy_total']) && !empty($this->request->post['cash_total']) && empty($this->request->post['glimit']))
//		{
//		$log->write("Cash Subsidy");
//			//
//			$fields_string .= 'payment_method'.'='.$mcrypt->encrypt('Cash Subsidy').'&'; 
//			$fields_string .= 'sub'.'='.$mcrypt->encrypt($this->request->post['subsidy_total']).'&'; 
//			$amtcash=$mcrypt->encrypt($this->request->post['cash_total']); 
//		}
//		else if(!empty($this->request->post['subsidy_total']) && !empty($this->request->post['cash_total']) && !empty($this->request->post['glimit']))
//		{
//		$log->write("Tagged Cash Subsidy");
//		
//			$fields_string .= 'payment_method'.'='.$mcrypt->encrypt('Tagged Cash Subsidy').'&'; 
//			$fields_string .= 'sub'.'='.$mcrypt->encrypt($this->request->post['subsidy_total']).'&'; 
//			$amtcash=$mcrypt->encrypt($this->request->post['cash_total']); 
//		}
//		else
//		{
//			$fields_string .= 'payment_method'.'='.$mcrypt->encrypt('Tagged').'&'; 
//		}
//		//$fields_string .= 'customer_id'.'='.$mcrypt->encrypt().'&'; 
//		$fields_string .= 'customer_mob'.'='.$mcrypt->encrypt($this->request->post['customer_mob']).'&'; 
//		$fields_string .= 'store_id'.'='.$mcrypt->encrypt($strid).'&'; 
//		$fields_string .= 'user_id'.'='.$mcrypt->encrypt($username).'&'; 
//		$fields_string .= 'affiliate_id'.'='.$mcrypt->encrypt('0').'&'; 
//		$fields_string .= 'utype'.'='.$mcrypt->encrypt('11').'&'; 
//		$fields_string .= 'eid'.'='.$mcrypt->encrypt('1111').'&'; 
//
//		$fields_string .= 'fname'.'='.$mcrypt->encrypt(urldecode($this->request->post['farmer_name'])).'&'; 
//		$fields_string .= 'lname'.'='.$mcrypt->encrypt(urldecode($this->request->post['father_name'])).'&'; 
//		$fields_string .= 'stname'.'='.$mcrypt->encrypt('store').'&'; 
//		$fields_string .= 'vname'.'='.$mcrypt->encrypt(urldecode($this->request->post['village_name'])).'&'; 
//		$fields_string .= 'cde'.'='.$mcrypt->encrypt(urldecode($this->request->post['totp'])).'&'; 
//		$fields_string .= 'cid'.'='.$mcrypt->encrypt(urldecode($this->request->post['G_Code'])).'&'; 
//		$fields_string .= 'comment'.'='.$mcrypt->encrypt(urldecode($this->request->post['comment'])).'&'; 
//		$fields_string .= 'fmcode'.'='.$mcrypt->encrypt(urldecode($this->request->post['fmcode'])).'&'; 
//		$fields_string .= 'fmname'.'='.$mcrypt->encrypt(urldecode($this->request->post['fmname'])).'&'; 
//		$fields_string .= 'req_order_id'.'='.$mcrypt->encrypt(urldecode($this->request->post['comment'])).'&'; 
//		
//		
//		$fields_string .= 'chkepos'.'='.$mcrypt->encrypt('0').'&'; 
//		$fields_string .= 'docs'.'='.$mcrypt->encrypt('0').'&'; 
//		$fields_string .= 'doc_number'.'='.$mcrypt->encrypt('0').'&'; 
//		
//		
//		$fields_string .= 'chkcash'.'='.$mcrypt->encrypt('0').'&'; 
//		$fields_string .= 'amtcash'.'='.$amtcash.'&'; 
//		
//		$fields_string .= 'transid'.'='.$mcrypt->encrypt($this->session->data['transid']).'&'; 
//		$fields_string .= 'deliveryreceipt'.'='.$mcrypt->encrypt('yes').'&'; 
//		$fields_string .= 'deliverymode'.'='.$mcrypt->encrypt('2').'&'; 
//		
//		$fields_string .= 'approvaltype'.'='.$mcrypt->encrypt('1').'&'; 
//		//$fields_string .= 'stock_fm'.'='.$mcrypt->encrypt('0').'&'; 
//		$fields_string .= 'card_no'.'='.$mcrypt->encrypt('0').'&'; 
//		
//		
//		rtrim($fields_string, '&');
//
//		$log->write($fields_string);
//		//exit;
//		$log->write('transid : '.$this->session->data['transid']);
//		$ch = curl_init();
//		curl_setopt($ch,CURLOPT_USERAGENT,'akshamaala'); 
//		curl_setopt($ch, CURLOPT_URL, $request);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//		curl_setopt($ch, CURLOPT_POST, true);
//		curl_setopt($ch,CURLOPT_POST, 1);
//		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
//            		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
//		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
//		$json =curl_exec($ch);
//		if(empty($json))
//			{
//				$log->write(curl_error($ch));	
//   		 
//			}
//		curl_close($ch); 
//		$log->write('data from webserivice');
//		$log->write($json);
//		$return_val=json_decode($json,TRUE);
//		$log->write($return_val);
//		if((empty($return_val['error']))  && (!empty($return_val['success']))) ////&& ($return_val['error']=='Warning: Please enter a coupon code!') 
//		{
//			$log->write('no error');
//
//			$Updaterequisition2=$this->Updaterequisition($strid,urldecode($this->request->post['comment']),$username,$order_total,$acash,urldecode($this->request->post['totp']),$return_val['order_id'],urldecode($this->request->post['prd_dtl']),urldecode($this->request->post['fmcode']),$glimit);
//
//			$log->write('Updaterequisition2 ret value- '.$Updaterequisition2);
//			if($Updaterequisition2==1)
//			{
//				$updateorder2=$this->updateorder(urldecode($this->request->post['comment']),$return_val['order_id'],$username);
//				$log->write('updateorder2 ret value- '.$updateorder2); 
//			}
//			else
//			{
//				$log->write('some error in Updaterequisition: - '.$Updaterequisition2);
//				$data['error']='Order Submission is failed. Please Contact Respective IT Department. Error is : '.$Updaterequisition2;
//				$data['success']='';
//				$data['order_id']='';
//				$data['invoice_no']='';
//				$log->write($data);
//				$this->response->setOutput(json_encode($data));
//				return;
//			}
//			if(($Updaterequisition2==1) && ($updateorder2==1))		
//			{
//			$data['success']=$return_val['success'];
//			$data['order_id']=$return_val['order_id'] ;
//			$data['invoice_no']=$return_val['invoice_no'] ;
//			$data['orddate']=$return_val['orddate']; 
//			$data['gtax']=$mcrypt->decrypt($return_val['gtax']);  
//			$ttax=json_decode($data['gtax'],TRUE);
//			$log->write($ttax);
//			$tax_return='';
//			foreach ($ttax as $key => $value) 
//			{
//			    $log->write($value['title']); 
//			    $log->write($value['value']); 
//			    $finaltax = round(($value['value'] /  2), 2);
//			    $log->write( $value['title'].'  '.$finaltax); 
//			    if (strpos($value['title'], '18') !== false) 
//			    {	
//				$tax_return.="CGST @9% ".$finaltax."</br>" ;
//				$tax_return.="SGST @9% ".$finaltax."</br>" ;
//			    }
//  			    if (strpos($value['title'], '12') !== false) 
//			    {
//
//				$tax_return.="CGST @6% ".$finaltax."</br>" ;
//				$tax_return.="SGST @6% ".$finaltax."</br>" ;
//			    }
//			    if (strpos($value['title'], '5') !== false) 
//			    {
//
//			      	$tax_return.="CGST @2.5% ".$finaltax."</br>" ;
//				$tax_return.="SGST @2.5% ".$finaltax."</br>" ;
//			    }
//			    if (strpos($value['title'], '28') !== false) 
//			    {	
//			  	$tax_return.="CGST @14% ".$finaltax."</br>" ;
//				$tax_return.="SGST @14% ".$finaltax."</br>" ;
//			     }
//
//			  }///////foreach end here
//			   $data['gtax']=$tax_return;
//
//			  $data['error']='';
//			  }////////if of Updaterequisition2==1 and updateorder2==1 end here
//			  else
//			  {
//				if($return_val['error']!='')
//				{
//					$log->write('some error in addorder : - '.$return_val['error']);
//					$data['error']='Order Submission is failed. Please Contact your Reporting Manager';
//				}
//				if($Updaterequisition2!='1')
//				{
//					$log->write('some error in Updaterequisition: - '.$Updaterequisition2);
//					$data['error']='Order Submission is failed. Please Contact Respective IT Department. Error is : '.$Updaterequisition2;
//				}
//				if($updateorder2!='1')
//				{
//					$log->write('some error in updateorder: - '.$updateorder2);
//					if($updateorder2!='0')
//					{
//						$data['error']='Order Submission is failed. Please Contact Respective IT Department. Error is : '.$updateorder2;
//				
//					}
//				}
//			$data['success']='';
//			$data['order_id']='';
//			$data['invoice_no']='';
//			
//			}////////////////////////////else of Updaterequisition2!=1 and updateorder2!=1 end here
//
//		}///////else of if no error in addorder is end here
//		else if((!empty($return_val['error'])) && ($return_val['success']=='-1'))
//		{
//			$log->write('some error in addorder : - '.$return_val['error']);
//			$data['success']='';
//			$data['order_id']='';
//			$data['invoice_no']='';
//			$data['error']=$return_val['error'];
//		}
//		else///////// any other error 
//		{
//			$log->write('some error : - '.$return_val['error']);
//			$data['success']='';
//			$data['order_id']='';
//			$data['invoice_no']='';
//			$data['error']=$return_val['error'];
//		}
//		//print_r($data['detail']); 
//		$log->write($data);
//		$this->response->setOutput(json_encode($data));
//		
//	}
//	private function getRequisition($indentno,$storeid)
//	{
//		$log=new Log("Requisition-web-".date('Y-m-d').".log");
//		$mcrypt=new MCrypt();
//		
//		$this->load->model('pos/pos');
//		$log->write("getRequisition called in web search indent");
//		$data=array();
//		$data['storeid']=$storeid;		
//		$data['indentno']=$indentno;
//		$companydata=$this->model_pos_pos->getunitidandcompanyid($data); 
//		if(!empty($companydata)){
//		$data['unitid']=$companydata[0]['unit_id'];
//		$log->write($companydata);
//		//$log->write($data);
//		$company=strtolower($companydata[0]['company_name']);
//		$log->write($company);
//		$this->load->model('pos/'.$company);
//		$response= $this->{'model_pos_' . $company}->getDataFromServer($data);
//		$log->write($response);
//		$responsett=str_replace("'",'"',$response);
//		$responsett=json_decode($responsett,TRUE);
//		//$log->write($responsett);
//		if(empty($responsett['error']))
//		{
//		$response=str_replace('{products:[','',$response);
//		$response=str_replace(']}','',$response);
//		$temp = json_decode($response, TRUE);
//		//$log->write($temp);
//		
//		$data_final=array();		
//			
//			//$log->write($key.":".$this->decrypt($temp['order_id']));
//			$val['order_id']=$this->decrypt($temp['order_id']);
//			$val['IndentDate']=$this->decrypt($temp['IndentDate']);					 				          
//			$val['telephone']=$this->decrypt($temp['telephone']);
//			$val['lastname']=$this->decrypt($temp['lastname']);
//			$val['VillageName']=$this->decrypt($temp['VillageName']);
//			$val['total']=round($this->decrypt($temp['total']),2);
//			$data_final[]=($val);	
//		
//		//$log->write($data_final);
//		return $data_final;
//		}
//		else
//		{
//			$log->write($this->decrypt($responsett['error']));
//			return array('error'=>$this->decrypt($responsett['error']));
//		}
//		}else{	
//			$responsett2['error']='Company not Defined';
//		$log->write($responsett2);		
//		$log->write("getRequisition called end here in web search indent");
//		return array('error'=>$responsett2['error']);
//		
//		}		
//	}
//	private function getRequisitiondtl($storeid,$indentno,$username)
//	{
//
//		$log=new Log("Requisition-web-getRequisitiondtl".date('Y-m-d').".log");
//		$mcrypt=new MCrypt();
//		$log->write('getRequisitiondtl is called in web search indent');
//		$this->load->model('pos/pos');
//		$data=array();
//		$data['storeid']=$storeid;		
//		$data['indentno']=$this->encrypt($indentno);
//		$data['userid']=$username;		
//		$companydata=$this->model_pos_pos->getunitidandcompanyid($data); 
//		
//		//$log->write($data);
//		$company=strtolower($companydata[0]['company_name']);
//		$data['unitid']=$companydata[0]['unit_id'];
//		//$log->write($company);
//		$this->load->model('pos/'.$company);
//		//$log->write("model");		
//		$response = $this->{'model_pos_' . $company}->getDataDetailFromServer($data);//$this->model_pos_bcml->getDataFromServer($data);
//		$log->write('data from server');
//		//print_r($response);
//		$log->write($response);
//		$response=str_replace('products','"products"',$response);
//		$response=str_replace(',total:',',"total":',$response);
//		$response=str_replace(',tax:',',"tax":',$response);
//		$response=str_replace(',subtotal:',',"subtotal":',$response);
//		$response=str_replace(',bkacc:',',"bkacc":',$response);
//		$response=str_replace(',idt:',',"idt":',$response);
//		$response=str_replace(',totp:',',"totp":',$response);
//		$response=str_replace(',ext:',',"ext":',$response);
//		$response=str_replace(',motp:',',"motp":',$response);
//		$response=str_replace(',success:',',"success":',$response);
//		$response=str_replace(',stname:',',"stname":',$response);
//		$response=str_replace(',cid:',',"cid":',$response);
//		$response=str_replace(',fname:',',"fname":',$response);
//		$response=str_replace(',lname:',',"lname":',$response);
//		$response=str_replace(',vname:',',"vname":',$response);
//		$response=str_replace(',tname:',',"tname":',$response);
//		$response=str_replace(',baccount:',',"baccount":',$response);
//		$response=str_replace(',receivelimit:',',"receivelimit":',$response);
//		$response=str_replace(',subsidytotal:',',"subsidytotal":',$response);
//		$response=str_replace(',cashtotal:',',"cashtotal":',$response);		
//		$response=str_replace("'",'"',$response);
//		//$log->write($response);
//		
//		if(!empty($response))
//		{
//		$temp2=$response;
//		$temp1 = json_decode($response);
//		
//		$data['indentno']=$this->decrypt($data['indentno']);
//		
//		$data['total']=round($this->decrypt($temp1->{'total'}),2);
//		$data['tax']=round($this->decrypt($temp1->{'tax'}),2);
//		$data['subtotal']=round($this->decrypt($temp1->{'subtotal'}),2); ///number_format((float), 2, '.', '')
//		$subtotall=$temp1->{'subtotal'};
//		$log->write('subtotal: ');
//		$log->write($data['subtotal']);
//		$log->write(round($data['subtotal'], 2));
//		$data['bkacc']=$this->decrypt($temp1->{'bkacc'});
//		$data['idt']=$this->decrypt($temp1->{'idt'});
//		$data['totp']=$this->decrypt($temp1->{'totp'});
//		$data['ext']=$this->decrypt($temp1->{'ext'});
//		$data['motp']=$this->decrypt($temp1->{'motp'});
//		$data['success']=$this->decrypt($temp1->{'success'});
//		$data['stname']=$this->decrypt($temp1->{'stname'});
//		$data['cid']=$this->decrypt($temp1->{'cid'});
//		$data['fname']=$this->decrypt($temp1->{'fname'});
//		$log->write('fname before decrypt : '.$temp1->{'fname'});
//		$log->write('fname after decrypt : '.$data['fname']);
//		$data['lname']=$this->decrypt($temp1->{'lname'});
//		$data['vname']=$this->decrypt($temp1->{'vname'});
//		$data['tname']=$this->decrypt($temp1->{'tname'});
//		$data['baccount']=$this->decrypt($temp1->{'baccount'});
//		$data['subsidytotal']=$this->decrypt($temp1->{'subsidytotal'});
//		$data['cashtotal']=$this->decrypt($temp1->{'cashtotal'});
//		$data['receivelimit']=round($this->decrypt($temp1->{'receivelimit'}),2);
//		
//		
//		//$temp = json_decode($response, TRUE);
//		//$log->write($data);
//		//$obj = new ArrayObject($temp1->{'products'});
//		//$it = $obj->getIterator();	
//		$data_final=array();
//		$all_product_tax=0;	
//		$totalSubsidy=0;
//		foreach ($temp1->{'products'} as $val)
//		{	//print_r($val->order_id);
//			//$log->write($this->decrypt($val->order_id));
//			$val1['order_id']=$this->decrypt($val->order_id);
//			$val1['VillageCode']=$this->decrypt($val->VillageCode);					 				          
//			$val1['VillageName']=$this->decrypt($val->VillageName);
//			$val1['G_Code']=$this->decrypt($val->G_Code);
//			$val1['GrowerName']=$this->decrypt($val->GrowerName);
//			$val1['FatherName']=$this->decrypt($val->FatherName);
//			$val1['MobileNo']=$this->decrypt($val->MobileNo);
//			$val1['product_id']=$this->decrypt($val->product_id);
//			$val1['SubSidyPer']=$this->decrypt($val->SubSidyPer);
//			$val1['Subsidytotal']=$this->decrypt($val->Subsidytotal);
//			$val1['Acttotal']=$this->decrypt($val->Acttotal);
//			$val1['BCMLCODE']=$this->decrypt($val->BCMLCODE);
//			$val1['S_CODE']=$this->decrypt($val->S_CODE);
//			$val1['S_DESC']=$this->decrypt($val->S_DESC);
//			$val1['ActRate']=$this->decrypt($val->Actprice);
//			$val1['SubRate']=round($this->decrypt($val->SubRate),2);
//			$val1['name']=$this->decrypt($val->name);
//			$val1['tax']=round($this->decrypt($val->tax),2);
//			$val1['price']=round($this->decrypt($val->price),2);
//			$val1['quantity']=round($this->decrypt($val->quantity),2);
//			$val1['total']=round($this->decrypt($val->total),2);
//			$val1['totp']=$this->decrypt($val->totp);
//			$val1['totpValidTill']=$this->decrypt($val->totpValidTill);
//			$val1['BankAccountNo']=$this->decrypt($val->BankAccountNo);
//			$val1['GrowerLimit']=round($this->decrypt($val->GrowerLimit),2); 
//			$val1['AI_Status']=$this->decrypt($val->AI_Status);
//			$val1['hstn']=$this->decrypt($val->hstn);	  
//			$val1['product_total']=	round(($this->decrypt($val->tax)*$this->decrypt($val->quantity)) + ($this->decrypt($val->price)*$this->decrypt($val->quantity)),2);	
//			$data_final[]=($val1);	
//			$val1['product_tax']=round(($this->decrypt($val->tax)*$this->decrypt($val->quantity)),3);
//			
//			$all_product_tax=$all_product_tax+$val1['product_tax'];
//			
//		}
//		
//		$data['products']=$data_final; 
//		$data['all_product_tax']=number_format((float)$all_product_tax, 2, '.', '');
//		
//		//$log->write($data);
//		$log->write('temp2');
//		$log->write($temp2);
//		$temp2=str_replace("'",'"',$temp2);
//		$log->write($temp2);
//		$temp2=str_replace("error",'"error"',$temp2);
//		$log->write($temp2);
//		//$temp2=str_replace("success",'"success"',$temp2);
//		//$log->write($temp2);
//		$temp3=json_decode($temp2,true);
//		$log->write($temp3);
//		$log->write($temp3['error']);
//		if(!empty($temp3['error']))
//		{
//			return array('error'=>$this->decrypt($temp3['error']));
//		}
//		else
//		{
//		return $data;
//		}
//                }		
//	}
//	private function Updaterequisition($storeid,$oid,$username,$invoicevalue,$cash,$otp,$invoiceno,$prddtl,$fmcode,$glimit)
//	{
//
//		$log=new Log("Requisition-web-submit-Updaterequisition-".date('Y-m-d').".log");
//		$mcrypt=new MCrypt();
//		$log->write(' Updaterequisition called');
//		$log->write($prddtl);
//		$this->load->model('pos/pos');
//		$data=array();
//		$data['storeid']=$storeid;		
//		$data['indentno']=$this->encrypt($oid);
//		$data['userid']=$username;
//		
//		$data['otp']=$otp;
//		if(strlen($data['otp'])>4)
//		{
//			$data['otp']="0";
//		}
//		$data['billno']=$invoiceno;
//		$data['prddtl']=$prddtl;
//		//$log->write($data['prddtl']);
//		//product base price to set
//		$data['prddtl']= json_decode($data['prddtl'],TRUE);
//		//$log->write($data['prddtl']);
//		$log->write('before  ArrayObject');
//        $obj = new ArrayObject($data['prddtl']);
//		$it = $obj->getIterator();	
//		$data_final=array();	
//		$order_total_value=0;	
//		$SubsidyAmount=0;
//		
//		foreach ($it as $key=>$val)
//		{	
//			$val['actamount']= $val['ActAmount'];
//			$val['actrate']= $val['ActRate'];
//			$val['subsidyper']= $val['SubSidyPer'];
//			$val['subsidyamount']= $val['SubsidyAmount'];
//			
//			$val['subrate']= $val['SubRate'];
//			
//			unset($val['ActAmount']);
//			unset($val['ActRate']);
//			unset($val['SubSidyPer']);
//			unset($val['SubsidyAmount']);
//			unset($val['SubRate']);
//			$log->write('in loop');
//			$log->write('product_name:'.$val['product_name']);
//			$log->write('product_price:'.$val['product_price']);
//			$log->write('product_tax:'.$val['product_tax']);
//			$log->write('product_id:'.$val['product_id']);
//			//$log->write($val);						
//			//$val['product_price']=$this->model_pos_pos->getproductprice($data['storeid'],$val['product_id']);
//			$store_price=$this->model_pos_pos->getproductprice($data['storeid'],$val['product_id']);
//			$log->write('store_price:'.$store_price);
//			if((!empty($store_price)) && ($store_price!='0.0000')&& ($store_price!='0.000')&& ($store_price!='0.00')&& ($store_price!='0.0'))
//			{
//				$val['product_price']=$store_price;
//			}
//			$log->write('product_price:'.$val['product_price']);
//			$val['product_price']=number_format((float)$val['product_price'],2,'.','');
//			$val['product_tax']=number_format((float)$val['product_tax'],2,'.','');
//			
//			$order_total_value=$order_total_value+(($val['product_price']+$val['product_tax'])*$val['product_quantity']);
//			//change
//			$data_final[]=($val);
//				if(empty($val['product_price'])) 
//				{
//					return 0;
//				}
//			$SubsidyAmount=$SubsidyAmount+$val['subsidyamount'];			
//			
//		}  
//		$log->write('order_total_value by calculation '.$order_total_value);
//		$log->write('SubsidyAmount by calculation '.$SubsidyAmount);
//		
//		$log->write('after  ArrayObject loop');		
//		$data['prddtl']= json_encode($data_final);
//		
//		$invoicevalue=$order_total_value-$SubsidyAmount;
//		
//		$log->write('invoicevalue by calculation '.$invoicevalue);
//		
//		$data['ordervalue']=$invoicevalue-$cash;
//		if(!empty($SubsidyAmount))
//		{
//			$data['subsidy']=$SubsidyAmount;
//		}
//		$data['cash']=$cash;
//		$data['invoicevalue']=$order_total_value;//$invoicevalue;
//		
//		//end price
//		$data['FmCode']=$fmcode;
//		$data['DeliveryMode']=2;
//		$data['DeliveryReceipt']='Y';
//		$data['ApprovalType']='O';
//		$data['glimit']=$glimit;
//		
//		$this->model_pos_pos->UpdateOrderTagged($data['billno'],$data['ordervalue']);//+$SubsidyAmount 
//		
//		$log->write('before call to getunitidandcompanyid');
//		$companydata=$this->model_pos_pos->getunitidandcompanyid($data); 
//		
//		
//		$company=strtolower($companydata[0]['company_name']);
//		$data['unitid']=$companydata[0]['unit_id'];
//		$log->write($company);
//		$this->load->model('pos/'.$company);
//		$log->write("model");	 
//		
//		$log->write($data);
//			
//		$results = $this->{'model_pos_' . $company}->setOrderDataToServer($data);
//		//$results=1;
//	
//		
//		$log->write('setOrderDataToServer results : '.$results);
//		if($results!=1) 
//		{
//			$log->write("in if");
//			$results=$results;//"0";		
//		}
//		try{	
//			
//			
//			
//		} catch (Exception $e) {
//                                $log->write($e);
//                            }
//		return $results;
//				
//	}
//	private function updateorder($oid,$billno,$username)
//	{
//		$log=new Log("Requisition-web-submit-".date('Y-m-d').".log");
//		$log->write(" updateorder is called ".$oid.",".$billno.",".$username);
//		$mcrypt=new MCrypt();
//		$this->request->post['oid']=$oid;
//		$this->request->post['billno']=$billno;
//		$this->request->post['username']=$username;
//		$this->request->post['order_id']=$billno;
//		$this-> load->model('pos/pos');
//        		$this->model_pos_pos->UpdateOrderStatusLeads($this->request->post['oid'],'5');
//		try
//		{
//			$log->write("come in try to send data to RequisitionToBill".$this->request->post["billno"]);
//			$this->model_pos_pos->RequisitionToBill($this->request->post['oid'],$this->request->post['billno']);
//		} 
//		catch (Exception $e) 
//		{
//            			$log->write("come in catch to send data to RequisitionToBill".$this->request->post["billno"]);
//
//        		}
//		$order_details=$this->model_pos_pos->getOrder($this->request->post["billno"]);
//		$log->write("sending for update inventory for ".$this->request->post["billno"]);
//		$this->request->post['store_id']=$order_details['store_id'];
//		$this->request->post['web_app']='web';
//		$this->model_pos_pos->updateinventory($this->request->post);   
//		
//			$companydata1=$this->model_pos_pos->getunitidandcompanyid(array('storeid'=>$order_details['store_id'])); 
//			$log->write($companydata1);
//			if(!empty($companydata1))
//                    		{
//                        		$data1['unitid']=$companydata1[0]['unit_id'];
//                       	 		
//                        			$company1=strtolower($companydata1[0]['company_name']);
//									
//                        			$log->write($company1);
//									if($company1=='bcml')
//									{
//										$this->load->model('pos/'.$company1);
//										$retbcml=$this->{'model_pos_' .$company1}->GetIndentByInvoiceNo('GetIndentByInvoiceNo',array('unitid'=>$data1['unitid'],'invoiceno'=>$this->request->post["billno"],'store_id'=>$order_details['store_id']),true); 
//										$log->write("data by retbcml : ");
//										$log->write($retbcml);
//										//return ("1");	
//										if($retbcml[0]['InvoiceNo']==$this->request->post["billno"])
//										{ /////////////success
//											$log->write('success');
//											return ("1");	
//										}
//										else
//										{ ///////// invoice number not matched
//											$log->write('invoice number not matched');
//											return ("0");	
//										}
//				
//									}
//									else
//									{  //////////////company is not bcml
//										$log->write('company is not bcml');
//										return ("1");	
//									}
//							}	
//							else
//							{ ////////////company details not found
//								$log->write('company details not found');
//								return ("1");
//							}
//							
//						
//
//	}
//	private function encrypt($encrypted)
//	{
//		$iv = '!QAZ2WSX#EDC4RFV'; #Same as in C#.NET
//		$key = '5TGB&YHN7UJM(IK<'; #Same as in C#.NET	
//		//PHP strips "+" and replaces with " ", but we need "+" so add it back in...
//		//$encrypted = str_replace(' ', '+', $encrypted);
//		//get all the bits
//		$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC);
//		$pad = $blockSize - (strlen($encrypted) % $blockSize);
//		$rtn = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted.str_repeat(chr($pad), $pad), MCRYPT_MODE_CBC, $iv);
//		$rtn = base64_encode($rtn);
//		return($rtn);
//	}	
//	private function decrypt($encrypted)
//	{
//		$iv = '!QAZ2WSX#EDC4RFV'; #Same as in C#.NET
//		$key = '5TGB&YHN7UJM(IK<'; #Same as in C#.NET	
//		//PHP strips "+" and replaces with " ", but we need "+" so add it back in...
//		$encrypted = str_replace(' ', '+', $encrypted);
//		//get all the bits
//		$encrypted = base64_decode($encrypted);
//		$rtn = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted, MCRYPT_MODE_CBC, $iv);
//		$rtn = $this->unpad($rtn);
//		return($rtn);
//	}
//	private function pkcs7pad($plaintext, $blocksize)
//	{
//		$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC);
//		$padsize = $blocksize - (strlen($plaintext) % $blocksize);
//		return $plaintext . str_repeat(chr($padsize), $padsize);
//	}
//	//removes PKCS7 padding
//	function unpad($value)
//	{
//		$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
//		$packing = ord($value[strlen($value) - 1]);
//		if($packing && $packing < $blockSize)
//		{
//			for($P = strlen($value) - 1; $P >= strlen($value) - $packing; $P--)
//			{
//				if(ord($value{$P}) != $packing)
//				{
//					$packing = 0;
//				}
//			}
//		}
//
//		return substr($value, 0, strlen($value) - $packing); 
//	}  
        
}