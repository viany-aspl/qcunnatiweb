<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');  
error_reporting(0);
ini_set('max_execution_time', 30000);  //3000 seconds = 50 minutes

class ControllerTagposLoanInventory extends Controller {
	public function index() {
		

		$this->document->setTitle("Loan Inventory");

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
		$this->load->model('pos/pos');
	
		$data=array();
		$strid=$this->user->getStoreId();
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
			//print_r($results);
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
			//print_r($data['fmlist']);
                	}
		}
		
		$data['products']=$this->model_tagpos_fmdelivery->getproductname($strid);
		//print_r($data['products']);
		
		
		
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

		$this->response->setOutput($this->load->view('tagpos/loan_inventory.tpl', $data));
	}
	
	
	public function getdropdownproduct(){
    $this->load->model('pos/pos');
	$this->load->model('tagpos/fmdelivery');
		$data=array();
		$strid=$this->user->getStoreId();
    $data=$data['products']=$this->model_tagpos_fmdelivery->getproductname($strid);
    $dropdown="";
   
        foreach($data as $value){
          //print_r($value);
            $dropdown.='<option value='.$value["product_id"].'>'.$value["model"].'</option>';
               
        }
     echo $dropdown;
    }
	
	
	public function addloaninventory(){
	$data['token'] = $this->session->data['token'];	
     $this->load->model('tagpos/fmdelivery');
	 
     if(isset($this->request->post['filter_fmlist'])){
		 
	
     $data=$this->model_tagpos_fmdelivery->AddFm($this->request->post);
    if($data==1)
   {
    $this->session->data['success'] ='Successfully Inserted';
   }else if($data==2){
       
     echo 'Sorry ! Some Error Occured';  
   }
   else{
   echo 'Sorry ! Some Error Occured';
   }
$this->response->redirect($this->url->link('tagpos/loan_inventory', 'token=' . $this->session->data['token']));
     }    
}
	
public function updatequantity(){
     //print_r($this->request->get);
	 $this->load->model('tagpos/fmdelivery');
     $data=$this->model_tagpos_fmdelivery->updatequantity($this->request->get);
	 //print_r($data);
    if($data==1)
    {
       $this->session->data['success'] ='Successfully Update';
	   echo $data;
	   return ;
     }
	 else if($data==2){
       
     echo 'Sorry ! Some Error Occured';  
    }
    else{
    echo 'Sorry ! Some Error Occured';
    }
     //$this->response->redirect($this->url->link('tagpos/loan_inventory/fm_loan_inventory_isseu_trans', 'token=' . $this->session->data['token']));
     }    


		
		
	



	////////////////////////////////////////////////////
	public function fm_loan_inventory() 
	{
		$this->document->setTitle("FM Loan Inventory");

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = '';
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_fm'])) {
			$filter_fm =trim( $this->request->get['filter_fm']);
		} else {
			$filter_fm = '';
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
                if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}
		

		if (isset($this->request->get['filter_fm'])) {
			$url .= '&filter_fm=' . trim($this->request->get['filter_fm']);
		}
		     

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => "Product Sale Fm Wise",  
			'href' => $this->url->link('tagpos/loan_inventory/fm_loan_inventory', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('tagpos/fmdelivery');
        $this->load->model('setting/store');

		$data['orderss'] = array();
	//	$filter_fm=trim($filter_fm);
		$filter_data = array(
            'filter_product'	     => $filter_product,
			'filter_date_start'	     => $filter_date_start,
			'filter_fm'	     => $filter_fm,
			
			'filter_date_end'	     => $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$data['orderss'] = array();
		//if(!empty($filter_store))
		//{
			$t1=$this->model_tagpos_fmdelivery->totalfmloaninventory($filter_data);
			$order_total = $t1["total"];
			//print_r($order_total);
			$results = $this->model_tagpos_fmdelivery->reportfmloaninventory($filter_data);
			//print_r($results);
		//}
		foreach ($results as $result) 
		{ 
			//echo $result['id'];
			$product_id=$result['product_id'];
			$fm_code=$result['fm_id'];
			$store_id=$result['store_id'];
		//print_r($result);
            $billed = $this->model_tagpos_fmdelivery->get_billed_qty($filter_data,$product_id,$fm_code,$store_id);
			if(empty($billed))
			{
				$billed=0;
			}
			$data['orderss'] []= array(
				
								
							
								'fm_name'     => $result['fm_name'],
                                'product_name' => $result['model'],
                                'quantity'   => $result['issuequantity'],
                                'billed'      => $billed,
                                'balance'     => ($result['issuequantity']-$billed),
                                'issue_date'  => $result['issue_date'],
                                
                                
				
			);
		}
		
		
		
		$this->load->model('pos/pos');
	
		//$data=array();
		$strid=$this->user->getStoreId();
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
			//print_r($results);
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
			//print_r($data['fmlist']);
                	}
		}
		
		$data['products']=$this->model_tagpos_fmdelivery->getproductname($strid);
		
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
        $data['filter_fm'] = $filter_fm;
		$data['filter_product'] = $filter_product;
		
			

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

		$this->response->setOutput($this->load->view('tagpos/fm_loan_inventory_report.tpl', $data));
	}
	
	
	
	
	public function fm_loan_inventory_isseu_trans() 
	{
		$this->document->setTitle("FM Loan Inventory");

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';//date('Y-m-d');
		}

		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = '';
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';//date('Y-m-d');
		}
		if (isset($this->request->get['filter_fm'])) {
			$filter_fm =( $this->request->get['filter_fm']);
		} else {
			$filter_fm = '';
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
                if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}
		

		if (isset($this->request->get['filter_fm'])) {
			$url .= '&filter_fm=' . trim($this->request->get['filter_fm']);
		}
		     

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => "Product Sale Fm Wise",  
			'href' => $this->url->link('tagpos/loan_inventory/fm_loan_inventory', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('tagpos/fmdelivery');
        $this->load->model('setting/store');

		$data['orderss'] = array();
	//	$filter_fm=trim($filter_fm);
		$filter_data = array(
            'filter_product'	     => $filter_product,
			'filter_date_start'	     => $filter_date_start,
			'filter_fm'	     => $filter_fm,
			
			'filter_date_end'	     => $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$data['orderss'] = array();
		//if(!empty($filter_store))
		//{
			$t1=$this->model_tagpos_fmdelivery->totalfmloaninventorytrans($filter_data);
			//print_r($t1);
			$order_total = $t1["total"];
			//print_r($order_total);
			$results = $this->model_tagpos_fmdelivery->issuefmloaninventorytrans($filter_data);
			//print_r($results);
		//}
		foreach ($results as $result) 
		{ 
			
			$data['orderss'] []= array(
				
								
							'id'     => $result['id'],
								'fm_name'     => $result['fm_name'],
                                'product_name' => $result['model'],
                                'quantity'   => $result['quantity'],
                                
                                'issue_date'  => $result['issue_date'],
                                
                                
				
			);
		}
		
		
		
		$this->load->model('pos/pos');
	
		//$data=array();
		$strid=$this->user->getStoreId();
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
			//print_r($results);
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
			//print_r($data['fmlist']);
                	}
		}
		
		$data['products']=$this->model_tagpos_fmdelivery->getproductname($strid);
		
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
        $data['filter_fm'] = $filter_fm;
		$data['filter_product'] = $filter_product;
		
			

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

		$this->response->setOutput($this->load->view('tagpos/fm_loan_inventory_transation.tpl', $data));
	}
	
	public function download_excelfmloanissue() {
		//echo 'here';exit;
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		/*if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}*/
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}
		

		if (isset($this->request->get['filter_fm'])) {
			$url .= '&filter_fm=' . trim($this->request->get['filter_fm']);
		}
		
		$this->load->model('tagpos/fmdelivery');
        
		$filter_data = array(
          
			'filter_date_start'	     => $filter_date_start,
			'filter_fm'	     => $filter_fm,
			'filter_product'=>$filter_product,
			'filter_date_end'	     => $filter_date_end
		);
		//if(!empty($filter_store))
		//{
			
			$results = $this->model_tagpos_fmdelivery->issuefmloaninventorytrans($filter_data);
		//}	
	include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'FM Name',
		'Product Name',
        'Quantity',
		'Issue Date',
       
       
    );
	
	$fileIO = fopen('php://memory', 'w+');
	fputcsv($fileIO, $fields,',');
	foreach($results as $data)
    { 
	
	
	
		$fdata=array(
                           
                            $data['fm_name'],
							$data['model'],
                            $data['quantity'],
                            $data['issue_date'],
							
                            );
			 fputcsv($fileIO,  $fdata,",");
	}		 
	fseek($fileIO, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment;filename="fm_loan_issue_trans'.date('dMy').'.csv"');
    header('Cache-Control: max-age=0');
    fpassthru($fileIO);  
    fclose($fileIO); 
  
	}
	
	
	public function download_excelfmloan() {
		//echo 'here';exit;
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
		}

		/*if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}*/
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}
		

		if (isset($this->request->get['filter_fm'])) {
			$url .= '&filter_fm=' . trim($this->request->get['filter_fm']);
		}
		
		$this->load->model('tagpos/fmdelivery');
        
		$filter_data = array(
          
			'filter_date_start'	     => $filter_date_start,
			'filter_fm'	     => $filter_fm,
			'filter_product'=>$filter_product,
			'filter_date_end'	     => $filter_date_end
		);
		//if(!empty($filter_store))
		//{
			
			$results = $this->model_tagpos_fmdelivery->reportfmloaninventory($filter_data);
		//}	
	include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'FM Name',
		'Product Name',
        'Issue Quantity',
		 'Billed',
		 'Balance'
       
       
    );
	
	$fileIO = fopen('php://memory', 'w+');
	fputcsv($fileIO, $fields,',');
	foreach($results as $data)
    { 
	
	 $product_id=$data['product_id'];
			$fm_code=$data['fm_id'];
			$store_id=$data['store_id'];
		//print_r($result);
            $billed = $this->model_tagpos_fmdelivery->get_billed_qty($filter_data,$product_id,$fm_code,$store_id);
			if(empty($billed))
			{
				$billed=0;
			}
	     
	
	
	
		$fdata=array(
                           
                            $data['fm_name'],
							$data['model'],
                            $data['issuequantity'],
							$billed,
                             $data['issuequantity']-$billed,
                            $data['issue_date'],
							
                            );
			 fputcsv($fileIO,  $fdata,",");
	}		 
	fseek($fileIO, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment;filename="fm_loan_inventory'.date('dMy').'.csv"');
    header('Cache-Control: max-age=0');
    fpassthru($fileIO);  
    fclose($fileIO); 
  
	}
	

}