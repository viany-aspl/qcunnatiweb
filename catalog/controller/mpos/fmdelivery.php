<?php
class ControllermposFmdelivery extends Controller 
{
	
	public function adminmodel($model) 
	{
      
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
	public function sendotp()
	{
	    $log=new Log("optgen-".date('Y-m-d').".log");
        $log->write("optgen-");  
	    $mcrypt=new MCrypt();
		$this->adminmodel('sale/order'); 
        $this->adminmodel('otp/otp'); 
		$this->adminmodel('user/user');
		$this->adminmodel('pos/bcml');
		$this->adminmodel('pos/pos');
		$log->write($this->request->post);
		$log->write('data');
		//$log->write($mcrypt->decrypt($this->request->post));
		$keys = array(
            'transaction_id',
			'tr_type',
			'user_id',
			'batch_no',
			'fm_code'
           );
		
		foreach ($keys as $key) 
        {
          $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
        }
		$log->write($this->request->post);
		$this->request->post['fm_code']=(int)$this->request->post['fm_code'];
		
		$user_info=$this->model_user_user->getUser($this->request->post['user_id']);
		$this->request->post['storeid']=$user_info['store_id'];
		
		$companydata=$this->model_pos_pos->getunitidandcompanyid($this->request->post); 
		
		$fmlist=$this->model_pos_bcml->getFM("GetFM",array('unitid'=>$companydata[0]['unit_id']),0);
		
		$this->request->post['fm_name']='';
		foreach($fmlist as $fm)
		{
			if($fm['FM_CODE']==$this->request->post['fm_code'])
			{
				$this->request->post['fm_mobile']=$fm['FM_MOBILE'];
				$this->request->post['fm_name']=$fm['FM_NAME'];
			}
		}
		$this->request->post['fm_mobile']='9911427348';
		$log->write($this->request->post);
		if(empty($this->request->post['fm_mobile']))
		{
			$json['status']="0";
            $json['msg'] = ('FM Mobile is not defined');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode( $json));
			return;
		}
		$log->write(strlen($this->request->post['fm_mobile']));
		if(strlen($this->request->post['fm_mobile'])!=10)
		{
			$json['status']="0";
            $json['msg'] = ('FM Mobile is not correct');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode( $json));
			return;
		}
	    $this->request->post['otp']=mt_rand(1000,9999);
		$log->write('check');
		$log->write($this->request->post);
		
		
		$checkRepeatTrans=$this->model_otp_otp->getotpTransId($this->request->post);
	
		$log->write('checkRepeatTrans');
		
		if(empty($checkRepeatTrans))
        {
            $query_return=$this->model_otp_otp->insertotp($this->request->post);
            $log->write($query_return);
        }
        else
        {
            $datatrans['otp']=$checkRepeatTrans["otp"];
            $query_return=$checkRepeatTrans["sid"];
        }
		$log->write('query_return');	
		$log->write($query_return);	
		if(!empty($query_return))
        {
			    $mobile=$this->request->post['fm_mobile'];
                $log->write($mobile);
                //SMS LIB
                $this->load->library('sms');    
                $sms=new sms($this->registry);            
                $data=array();
                $data['otp']=$this->request->post['otp'];
				$log->write($data);	
                $sms->sendsms($mobile,"27",$data);
				
            $json['status']="1";
            $json['msg'] = 'Success: OTP sent to mobile number ';    
            $json['sid'] =$query_return;    			
            $log->write($json);
			
        }
        else
        {
            $json['status']="0";
            $json['msg'] = ('Error in submission');
        }   
            
		
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode( $json));
	   
	}
	
	
	
	
	
	
	
	//////////////////////////////////START////////////////////////////////////////////
	public function fm_loan_inventory()
    {
        $log=new Log("loan-inventory-".date('Y-m-d').".log");
		$mcrypt=new MCrypt(); 
		$log->write($this->request->post);
		$sid=$mcrypt->decrypt($this->request->post['store_id']);
		$this->adminmodel('tagpos/fmdelivery');
		$data['fmproduct']=$this->model_tagpos_fmdelivery->getproductname($sid);
		$log->write($data['product']);
		$this->response->setOutput(json_encode($data));
    }


        public function fm_loan_inventory_report()
    {
        $log=new Log("loan-report-inventory-".date('Y-m-d').".log");
		$mcrypt=new MCrypt(); 
		$log->write($this->request->post);
		
		if (isset($this->request->post['filter_date_start'])&& ($mcrypt->decrypt($this->request->post['filter_date_start'])!='From')) {
			$filter_date_start = $mcrypt->decrypt($this->request->post['filter_date_start']); 		
		} 
		else if($this->request->post['filter_date_start']=='From')
		{
			
			$filter_date_start =date('Y-m-01');
			
		}
		
		else {
			$filter_date_start = date('Y-m-01');
		}

		if (isset($this->request->post['filter_date_end']) && ($mcrypt->decrypt($this->request->post['filter_date_end'])!='To')) {
			$filter_date_end = $mcrypt->decrypt($this->request->post['filter_date_end']); 
		} 
		
		else if($this->request->post['filter_date_end']=='To'){

        $filter_date_end =date('Y-m-d');
		}
		else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->post['filter_fm'])) {
			$filter_fm = $mcrypt->decrypt($this->request->post['filter_fm']); 
		} else {
			$filter_fm = '';
		}
                if (isset($this->request->post['filter_prdduct'])) {
			$filter_product = $mcrypt->decrypt($this->request->post['filter_prdduct']); 
		} else {
			$filter_product = '';
		}
		
		if (isset($this->request->post['start'])) 
	{
		$start = $mcrypt->decrypt($this->request->post['start']);
	} 
	else 
	{
		$start = 0;
	}
	/*	if($this->request->post['filter_date_start']='From'){
			 $filter_date_start =date('Y-m-01');
		}
		
		
		if($this->request->post['filter_date_end']=='To'){
			 $filter_date_start =date('Y-m-d');
		}*/
		
		$this->adminmodel('tagpos/fmdelivery');
		
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_product'	     => $filter_product,
			'filter_fm'	     => $filter_fm,
			'start' => $start,
		     'limit' => 20
			
		);
        $log->write($filter_data);             
		
		
		$results = $this->model_tagpos_fmdelivery->reportfmloaninventory($filter_data);
		
		foreach ($results as $result) { //print_r($result);
		
		    $product_id=$result['product_id'];
			$fm_code=$result['fm_id'];
			$store_id=$result['store_id'];
		    $billed = $this->model_tagpos_fmdelivery->get_billed_qty($filter_data,$product_id,$fm_code,$store_id);
			$data['results'][] = array(
				'product_id'        => $mcrypt->encrypt($result['product_id']),
				'fm_id'             => $mcrypt->encrypt($result['fm_id']),
				'store_id'          => $mcrypt->encrypt($result['store_id']),
				'fm_name'           => $mcrypt->encrypt($result['fm_name']),
				'product_name'      => $mcrypt->encrypt($result['model']),
				'quantity'          => $mcrypt->encrypt($result['issuequantity']),
				'billed'            => $mcrypt->encrypt($billed),
				'balance'           => $mcrypt->encrypt($result['issuequantity']-$billed)
			);
		}
 
		$log->write($data);
		$this->response->setOutput(json_encode($data));
    }
        	
	 public function fm_loan_issue_trans()
    {
        $log=new Log("loan-report-inventory-".date('Y-m-d').".log");
		$mcrypt=new MCrypt(); 
		$log->write($this->request->post);
		
		
		
		
		if (isset($this->request->post['filter_date_start']) && ($mcrypt->decrypt($this->request->post['filter_date_start'])!='From')) 
		{
			$filter_date_start = $mcrypt->decrypt($this->request->post['filter_date_start']); 		
		} 
		else if($this->request->post['filter_date_start']=='From')
		{
			
			$filter_date_start =date('Y-m-01');
			
		}else {
			  $filter_date_start =date('Y-m-01');
		}

		if (isset($this->request->post['filter_date_end']) && ($mcrypt->decrypt($this->request->post['filter_date_end'])!='To')) 
		{
			$filter_date_end = $mcrypt->decrypt($this->request->post['filter_date_end']); 
		}
		else if($this->request->post['filter_date_end']=='To'){

        $filter_date_end =date('Y-m-d');
		}else {
			 $filter_date_end =date('Y-m-d');
		}
		if (isset($this->request->post['filter_fm'])) {
			$filter_fm = $mcrypt->decrypt($this->request->post['filter_fm']); 
		} else {
			$filter_fm = '';
		}
        if (isset($this->request->post['filter_prdduct'])) {
			$filter_product = $mcrypt->decrypt($this->request->post['filter_prdduct']); 
		} else {
			$filter_product = '';
		}
		
		
		if (isset($this->request->post['start'])) 
	{
		$start = $mcrypt->decrypt($this->request->post['start']);
	} 
	else 
	{
		$start = 0;
	}
		
		/*if($this->request->post['filter_date_start']='From'){
			 $filter_date_start =date('Y-m-01');
		}
		
		
		if($this->request->post['filter_date_end']=='To'){
			 $filter_date_end =date('Y-m-d');
		}
		
		*/
		$this->adminmodel('tagpos/fmdelivery');
		
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_product'	     => $filter_product,
			'filter_fm'	     =>         $filter_fm,
			'start' => $start,
		     'limit' => 20
			
		);
        $log->write($filter_data);             
		
		
		$results = $this->model_tagpos_fmdelivery->issuefmloaninventorytrans($filter_data);
		
		foreach ($results as $result) { //print_r($result);
		
		
			$data['results'][] = array(
			'id'        => $mcrypt->encrypt($result['id']),
				'product_id'        => $mcrypt->encrypt($result['product_id']),
				'fm_id'             => $mcrypt->encrypt($result['fm_id']),
				'store_id'          => $mcrypt->encrypt($result['store_id']),
				'fm_name'           => $mcrypt->encrypt($result['fm_name']),
				'product_name'      => $mcrypt->encrypt($result['model']),
				'quantity'      => $mcrypt->encrypt($result['quantity']),
				'issue_date'      => $mcrypt->encrypt(date('Y-m-d',strtotime($result['issue_date']))),
				
			);
		}
 
		$log->write($data);
		$this->response->setOutput(json_encode($data));
    }
	
	
	public function aad_fm_loan_inventory()
    {
		$log=new Log("aad_loan-inventory-".date('Y-m-d').".log");
		$mcrypt=new MCrypt(); 
		$log->write('data');
		$log->write($this->request->post);
		$this->adminmodel('tagpos/fmdelivery');
		
		$keys = array(
            'filter_fmlist',
			'user_id',
			'store_id',
			'product_id',
			'filter_date_start',
			'qty'
			
           );
	    foreach ($keys as $key) 
        {
          $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
        }
		
		$log->write($this->request->post);
		$this->request->post['product_list']=json_decode($this->request->post['product_id'],true);
		//$log->write($this->request->post);
		unset($this->request->post['product_id']);
		//$log->write($this->request->post);
		$product_id=array();
		$issue_date=array();
		$quntity=array();
		//$store_id=array();
		
		
		foreach($this->request->post['product_list'] as $prd)
		{
			//if(!empty($prd['product_id']))
			{
				$product_id[]=$prd['product_id'];
				$issue_date[]=$prd['issue_date'];
				$quntity[]=$prd['quntity'];
				//$store_id[]=$prd['store_id'];
				
			}
		}
		$this->request->post['product_id']=$product_id;
		$this->request->post['filter_date_start']=$issue_date;
		$this->request->post['qty']=$quntity;
		//$this->request->post['store_id']=$store_id;
		
		unset($this->request->post['product_list']);
		$log->write($this->request->post);
		if(!empty($this->request->post['product_id']))
        {
		$addfm=$this->model_tagpos_fmdelivery->AddFm($this->request->post);
	    if(!empty($addfm))
        {
          $data=array('status'=>'1','message'=>'Fm Loan submite successfully');
        }
		}
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
	}
	
	
	public function qtyupdate(){
		$log=new Log("update_qty_loan-inventory-".date('Y-m-d').".log");
		$mcrypt=new MCrypt(); 
		$log->write('data');
		$log->write($this->request->post);
		$this->adminmodel('tagpos/fmdelivery');
		$keys = array(
            'id',
			'quantity'
           );
		   
		   
		foreach ($keys as $key) 
        {
          $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
        }
		
		$log->write($this->request->post);
		$updateqty=$this->model_tagpos_fmdelivery->updatequantity($this->request->post);
	    if(!empty($updateqty))
        {
          $data=array('status'=>'1','message'=>'Quantity Update successfully');
        }
		
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
	}
		
//////////////////////////END/////////////////////////////////////////////////////////////
	
	
	
	
	
	
	public function batch_update()
	{
		$log=new Log("batch_update-".date('Y-m-d').".log");
        $log->write("batch_update-called");  
	    $mcrypt=new MCrypt();
        $this->adminmodel('otp/otp'); 
		$this->adminmodel('user/user');
		$log->write($this->request->post);
		$log->write('data');
		//$log->write($mcrypt->decrypt($this->request->post));
		$keys = array(
            'sid',
			'user_id',
			'batch_no',
			'otp',
			'fm_id'
           );
		
		foreach ($keys as $key) 
        {
          $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
        }
		
		$log->write($this->request->post);
		
		$checkOTP=$this->model_otp_otp->getotpbysid($this->request->post);
	
		$log->write('checkOTP');
		$log->write($checkOTP);
		if(empty($checkOTP))
        {
			$log->write('OTP not Found for this transaction');
            $json['status']="0";
            $json['msg'] = ('OTP not Found for this transaction');
        }
        else
        {
			if($checkOTP['otp']==$this->request->post['otp'])
			{
				$log->write('OTP Matched');
				$this->adminmodel('sale/order');
			
				$results=$this->model_sale_order->batch_update($this->request->post);
				
				$this->model_sale_order->batch_fm_set($this->request->post);
				$json['status']="1";
				$json['msg'] = 'Delivered Successfully ';    
				 
			}
			else
			{
				$log->write('OTP not Matched');
				$json['status']="0";
				$json['msg'] = ('OTP not Matched');
			}
        }
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode( $json));
	}
	public function batch_view()  
	{
		$log=new Log("fm_batch_view-".date('Y-m-d').".log");
		$log->write("batch_view called");
		$log->write($this->request->post);
		$log->write($this->request->get);
		$mcrypt=new MCrypt();
		if(!empty($mcrypt->decrypt($this->request->post['start_date'])))
		{
			$start_date=$mcrypt->decrypt($this->request->post['start_date']);
		}
		else
		{
			$start_date=date('Y-m-d');
		}
		//$start_date='2018-02-01';
		if(!empty($mcrypt->decrypt($this->request->post['end_date'])))
		{
			$end_date=$mcrypt->decrypt($this->request->post['end_date']);
		}
		else
		{
			$end_date=date('Y-m-d');
		}
		//$end_date='2018-03-01';
		if(!empty($mcrypt->decrypt($this->request->post['fm_code'])))
		{
			$fm_code=$mcrypt->decrypt($this->request->post['fm_code']);
		}
		
		else
		{
			$fm_code=0;
		}
		if(!empty($mcrypt->decrypt($this->request->post['store_id'])))
		{
			$store_id=$mcrypt->decrypt($this->request->post['store_id']);
		}
		
		else
		{
			$store_id=0;
		}
		if(!empty($mcrypt->decrypt($this->request->post['start'])))
		{
			$start=$mcrypt->decrypt($this->request->post['start']);
		}
		
		else
		{
			$start=0;
		}
		$filter_data=array(
					'start_date'=>$start_date,
					'end_date'=>$end_date,
					'fm_code'=>$fm_code,
					'store_id'=>$store_id,
					'start'=>$start,
					'limit'=>20
					);
		$log->write($filter_data);
		$data['products'] = array();
		$results=array();
		$this->adminmodel('sale/order');
		if(!empty($fm_code))
		{ 
			$results=$this->model_sale_order->batch_view($filter_data);
						
		}
		//$log->write($results); 
		foreach($results as $result)//$mcrypt->encrypt(
		{
			
			$data['products'][]=array(
							'batch_no'=>$mcrypt->encrypt($result['sid']),
							'status'=>$mcrypt->encrypt($result['status']),
							'order_date'=>$mcrypt->encrypt(date('Y-m-d',strtotime($result['filter_date']))),
							);
		}
		
		$log->write($data);
		$this->response->setOutput(json_encode($data));
		
	}
	public function fm_item_wise_summary()  
	{

		$log=new Log("fm_item_wise_summary-".date('Y-m-d').".log");
		$log->write("fm_item_wise_summary called");
		$log->write($this->request->post);
		$log->write($this->request->get);
		$mcrypt=new MCrypt();
		if(!empty($mcrypt->decrypt($this->request->post['start_date'])))
		{
			$start_date=$mcrypt->decrypt($this->request->post['start_date']);
		}
		else
		{
			$start_date=date('Y-m-d');
		}
		//$start_date='2018-02-01';
		if(!empty($mcrypt->decrypt($this->request->post['end_date'])))
		{
			$end_date=$mcrypt->decrypt($this->request->post['end_date']);
		}
		else
		{
			$end_date=date('Y-m-d');
		}
		//$end_date='2018-03-01';
		if(!empty($mcrypt->decrypt($this->request->post['fm_code'])))
		{
			$fm_code=$mcrypt->decrypt($this->request->post['fm_code']);
		}
		
		else
		{
			$fm_code=0;
		}
		if(!empty($mcrypt->decrypt($this->request->post['store_id'])))
		{
			$store_id=$mcrypt->decrypt($this->request->post['store_id']);
		}
		
		else
		{
			$store_id=0;
		}
		if(!empty($mcrypt->decrypt($this->request->post['start'])))
		{
			$start=$mcrypt->decrypt($this->request->post['start']);
		}
		
		else
		{
			$start=0;
		}
		$filter_data=array(
					'start_date'=>$start_date,
					'end_date'=>$end_date,
					'fm_code'=>$fm_code,
					'store_id'=>$store_id,
					'start'=>$start,
					'limit'=>20
					);
		$log->write($filter_data);
		$data['products'] = array();
		$results=array();
		$this->adminmodel('sale/order');
		if(!empty($fm_code))
		{ 
			$results=$this->model_sale_order->fm_item_wise_summary($filter_data);
						
		}
		$total=0;
		foreach($results as $result)//$mcrypt->encrypt(
		{
			$total=$total+($result['quantity']*$result['price'])+($result['quantity']*$result['tax']);
			$data['products'][]=array(
							'product_name'=>$mcrypt->encrypt($result['name']),
							'product_id'=>$mcrypt->encrypt($result['product_id']),
							'quantity'=>$mcrypt->encrypt($result['quantity']),
							'issued'=>$mcrypt->encrypt($result['quantity']),
							'date'=>$mcrypt->encrypt(date('Y-m-d',strtotime($result['ORD_DATE']))),
							'price'=>$mcrypt->encrypt($result['price']),
							'tax'=>$mcrypt->encrypt($result['tax']),
							'product_total'=>$mcrypt->encrypt(($result['quantity']*$result['price'])+($result['quantity']*$result['tax']))
							);
		}
		$data['total']=$total;
		//$log->write($data);
		$this->response->setOutput(json_encode($data));
		
	}

public function bill()
	{
			
			$log=new Log("printallinvoice-".date('Y-m-d').".log");
			$mcrypt=new MCrypt();
			$log->write("print All invoice");
		    	$this->adminmodel('tagpos/fmdelivery');
			$batch=$mcrypt->decrypt($this->request->post['batch']);
			if(empty($batch))
			{
			$batch=$this->request->get['batch'];
			}
			$start=$mcrypt->decrypt($this->request->post['start']);
			if(empty($start))
			{
			$start=0;
			}
			$log->write("print All invoice");
			$finaldata=array();
			$filter_data=array('batchno'=>$batch,'start'=>$start,'limit'=>20);
			if(!empty($batch))
			{
							
		        	$binvcount=$this->model_tagpos_fmdelivery->getbatchinvoicelist($filter_data); 
				$log->write($binvcount);
				foreach ($binvcount as $value) {
				$log->write($value['invoice_no']);
				$finaldata['orders'][]=array('inv_no'=>$mcrypt->encrypt($value['invoice_no']) ,'inv_html'=>$this->OrderProducts($value['invoice_no']));				
				}
						
			}
			$log->write('finaldatan return by bill');
			$log->write($finaldata);
		$this->response->setOutput(json_encode($finaldata));
		
	}

	
	public function OrderProducts($filter_order_id ) 
		{

		$log=new Log("printallinvoice-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		 $this->request->get['order_id']=$filter_order_id ;
		$data['products'] = array();
		$log->write("hist");
                            $filter_order_id =$this->request->get['order_id'];                                                                                   			
				$log->write("hist2");
			$this->adminmodel('sale/order');
			$this->adminmodel('setting/setting');
			$this->adminmodel('user/user');
		$resord=$this->model_sale_order->getOrderUser($filter_order_id);
		$results = $this->model_sale_order->getOrderProducts($filter_order_id);
						$log->write("hist3");	
		$store_id=$this->model_sale_order->getOrderStoreId(($this->request->get['order_id']));
						$log->write("hist4");
		$store_add=$this->model_setting_setting->getSettingbykey('config','config_address', $store_id);

		foreach ($results as $result) {

		//$log->write($result);
			$data['products'][] = array(
				'order_product_id'      =>$mcrypt->encrypt( $result['order_product_id']),
				'product_id'      =>$mcrypt->encrypt( $result['product_id']),
				'subsidy'	=> $mcrypt->encrypt(empty($this->model_sale_order->getProductSubsidy($result['product_id'],$store_id))?0:$this->model_sale_order->getProductSubsidy($result['product_id'],$store_id)),
				'name'        =>$mcrypt->encrypt( $result['name']),
				'model'         => $mcrypt->encrypt($result['model']),
				'quantity'    =>$mcrypt->encrypt( $result['quantity']),
				'price' => $mcrypt->encrypt( number_format((float)(($result['price']))+(round($result['tax'])*$result['quantity']), 2, '.', '') ),
				'hstn' => $mcrypt->encrypt( ($result['HSTN']) ),
				'total' => $mcrypt->encrypt(number_format((float)($result['total'])+(round($result['tax'])*$result['quantity']), 2, '.', '')),
				'tax'	=> $mcrypt->encrypt(number_format((float)($result['tax'])+(round($result['tax'])*$result['quantity']), 2, '.', '')),
			
			);
		
		}

		//$log->write($this->model_sale_order->getorderSubTotalvalue($filter_order_id));

		$data['total']=$mcrypt->encrypt(number_format((float)($this->model_sale_order->getorderTotalvalue($filter_order_id)), 2, '.', ''));
		$data['tax']=$mcrypt->encrypt(number_format((float)($this->model_sale_order->getorderTaxvalue($filter_order_id)), 2, '.', ''));
		$data['subtotal']=$mcrypt->encrypt(number_format((float)($this->model_sale_order->getorderSubTotalvalue($filter_order_id)), 2, '.', ''));
		$data['subsidy']=$mcrypt->encrypt(number_format((float)($this->model_sale_order->getOrderSubsidy(($filter_order_id))), 2, '.', ''));
		$data['cash']=$mcrypt->encrypt(number_format((float)($this->model_sale_order->getOrdercash(($filter_order_id))), 2, '.', ''));
		$data['gstn']=$mcrypt->encrypt(number_format((float)($this->model_setting_setting->getSettingbykey('config','config_gstn',$store_id)), 2, '.', ''));		
			
		try{
				$log->write("hist6");
			$orderDetails=$this->model_sale_order->getOrderInfo($filter_order_id);
			$log->write("hist7");
			$log->write("in data");
			//$log->write($orderDetails);
			$this->adminmodel('lead/orderleads');
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
			$data['cus_id']=$mcrypt->encrypt($orderDetails['shipping_firstname']);
			}
			else
			{
			$data['cus_id']=$mcrypt->encrypt($orderDetails['customer_id']);
			}
		}
		$data['far_name']=$mcrypt->encrypt($orderlead[0]['payment_firstname']);
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
			$data['far_name']=$mcrypt->encrypt($farm_father_array[0]);
		}
		$data['fath_name']=$mcrypt->encrypt($orderlead[0]['payment_lastname']);
		if(empty($orderlead[0]['payment_lastname']))
		{
			$data['fath_name']=$mcrypt->encrypt($farm_father_array[1]);
		}
		$data['vill_name']=$mcrypt->encrypt($orderlead[0]['shipping_firstname']);
		if(empty($orderlead[0]['shipping_firstname']))
		{
			$data['vill_name']=$mcrypt->encrypt($orderDetails['payment_address_1']);
		}
		$data['stor_name']=($orderlead[0]['store_name']);
		if(empty($orderlead[0]['store_name']))
		{
			$data['stor_name']=$mcrypt->encrypt($orderDetails['store_name']);
		}

		$data['cid']=$mcrypt->encrypt($orderlead[0]['payment_address_1']);
		if(empty($orderlead[0]['payment_address_1']))
		{
			if(!empty($orderDetails['shipping_firstname']))
			{
			$data['cid']=$mcrypt->encrypt($orderDetails['shipping_firstname']);
			}
			else
			{
			$data['cid']=$mcrypt->encrypt($orderDetails['customer_id']);
			}
		}
		$data['fname']=($orderlead[0]['payment_firstname']);
		if(empty($orderlead[0]['payment_firstname']))
		{
			$data['fname']=$mcrypt->encrypt($farm_father_array[0]);
		}
		$data['lname']=($orderlead[0]['payment_lastname']);
		if(empty($orderlead[0]['payment_lastname']))
		{
			$data['lname']=$mcrypt->encrypt($farm_father_array[1]);
		}
		$data['vname']=($orderlead[0]['shipping_firstname']);
		if(empty($orderlead[0]['shipping_firstname']))
		{
			$data['vname']=$mcrypt->encrypt($orderDetails['payment_address_1']);
		}
		$data['stname']=($orderlead[0]['store_name']);
		if(empty($orderlead[0]['store_name']))
		{
			$data['stname']=$mcrypt->encrypt($orderDetails['store_name']);
		}
		
		$delm=	$this->model_sale_order->getOrderdelivery($filter_order_id);
		$log->write($delm);
		$data['deliverymode']=$mcrypt->encrypt("Tagged");//$mcrypt->encrypt("test");
		$data['fmname']=$mcrypt->encrypt($delm['fmname']);  
		
		$log->write($resord);
		$usernames=$this->model_user_user->getUser($resord);
		$log->write($usernames);
		$data['opname']=$mcrypt->encrypt($usernames["firstname"]." ".$usernames["lastname"]);
		$data['stadd']=$mcrypt->encrypt($store_add);
		$data['date'] =$mcrypt->encrypt($orderDetails['date_added']);		  
		$data['invoiceno'] = $mcrypt->encrypt($orderDetails['invoice_prefix'].'-'.$orderDetails['invoice_no']) ;
		$data['refrenseno'] = $mcrypt->encrypt($orderDetails['order_id']) ;
		$data['order_id'] =$mcrypt->encrypt($orderDetails['comment']) ;
		$data['tagged']=$mcrypt->encrypt($orderDetails['tagged']);
		$data['customer_mobile']=$mcrypt->encrypt($orderDetails['telephone']);
		}catch(Exception $el){
			$log->write($el);
			}
				//$log->write($data);
		$this->adminmodel('tagpos/fmdelivery');                
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
		$data['gtax']=$mcrypt->encrypt($tax_return);
				  //end tax
$log->write("final end".$filter_order_id);
		return ($data);

		} 



}