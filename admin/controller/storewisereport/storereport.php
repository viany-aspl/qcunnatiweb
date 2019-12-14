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
class ControllerStorewisereportStorereport extends Controller {
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
	$this->document->setTitle('Store  Report');
        //echo $this->request->get['tab'];
        $url="";
        //if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
            if($this->request->get['tab']=="1")
            {
                $data['tab1'] ="active";
                $data['tab2'] ="";
                $data['tab3'] ="";
                $data['tab4'] ="";
                $data['tab5'] ="";
                $data['tab6'] ="";
                $data['tab7'] ="";
	  $data['tab8'] ="";
            }
             else if($this->request->get['tab']=="2")
            {
                $data['tab1'] ="";
                $data['tab2'] ="active";
                $data['tab3'] ="";
                $data['tab4'] ="";
                $data['tab5'] ="";
                $data['tab6'] ="";
                $data['tab7'] ="";
	  $data['tab8'] ="";
            }
             else if(trim($this->request->get['tab'])=="3")
            {
                $data['tab1'] ="";
                $data['tab2'] ="";
                $data['tab3'] ="active";
                $data['tab4'] ="";
                $data['tab5'] ="";
                $data['tab6'] ="";
                $data['tab7'] ="";
	  $data['tab8'] ="";
            }
             else if($this->request->get['tab']=="4")
            {
                $data['tab1'] ="";
                $data['tab2'] ="";
                $data['tab3'] ="";
                $data['tab4'] ="active";
                $data['tab5'] ="";
                $data['tab6'] ="";
                $data['tab7'] ="";
	  $data['tab8'] ="";
            }
             else if($this->request->get['tab']=="5")
            {
                $data['tab1'] ="";
                $data['tab2'] ="";
                $data['tab3'] ="";
                $data['tab4'] ="";
                $data['tab5'] ="active";
                $data['tab6'] ="";
                $data['tab7'] ="";
	$data['tab8'] ="";
            }
             else if($this->request->get['tab']=="6")
            {
                $data['tab1'] ="";
                $data['tab2'] ="";
                $data['tab3'] ="";
                $data['tab4'] ="";
                $data['tab5'] ="";
                $data['tab6'] ="active";
                $data['tab7'] ="";
	  $data['tab8'] ="";
            }
           else  if($this->request->get['tab']=="7")
            {
                $data['tab1'] ="";
                $data['tab2'] ="";
                $data['tab3'] ="";
                $data['tab4'] ="";
                $data['tab5'] ="";
                $data['tab6'] ="";
                $data['tab7'] ="active";
	  $data['tab8'] ="";
            }
        else  if($this->request->get['tab']=="8")
            {
                $data['tab1'] ="";
                $data['tab2'] ="";
                $data['tab3'] ="";
                $data['tab4'] ="";
                $data['tab5'] ="";
                $data['tab6'] ="";
                $data['tab7'] ="";
	  $data['tab8'] ="active";
            }
        else{
             $data['tab1'] ="active";
                $data['tab2'] ="";
                $data['tab3'] ="";
                $data['tab4'] ="";
                $data['tab5'] ="";
                $data['tab6'] ="";
                $data['tab7'] ="";
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
			$filter_store = 'null';
		}
                 if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = 'null';
		}
                if (isset($this->request->get['filter_name_id'])) {
			$filter_name_id = $this->request->get['filter_name_id'];
		} else {
			$filter_name_id = 'null';
		}
   if($this->request->get['filter_store']!="")
   {     

if($this->request->get['tab']=="3" || $this->request->get['tab']=="4"){
       
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
          
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
		
		if (isset($this->request->get['page3'])) {
			$page3 = $this->request->get['page3'];
		} else {
		 $page3 = 1;
		}
		if (isset($this->request->get['page4'])) {
			$page4 = $this->request->get['page4'];
		} else {
		 $page4 = 1;
		}
		if (isset($this->request->get['page5'])) {
			$page5 = $this->request->get['page5'];
		} else {
		 $page5 = 1;
		}
		if (isset($this->request->get['page6'])) {
			$page6 = $this->request->get['page6'];
		} else {
		 $page6 = 1;
		}
                            if (isset($this->request->get['page7'])) {
			$page7 = $this->request->get['page7'];
		} else {
		 $page7 = 1;
		}
		 if (isset($this->request->get['page8'])) {
			$page8 = $this->request->get['page8'];
		} else {
		 $page8 = 1;
		}
		
		$this->load->model('report/stock');
                            

		$data['orders'] = array();

		$filter_data = array(
                                          'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_name'	             => $filter_name,
                                          'filter_name_id'	     => $filter_name_id,
			'start'                      => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data2 = array(
         'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_name'	             => $filter_name,
                                          'filter_name_id'	     => $filter_name_id,
			'start'                      => ($page2 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data3 = array(
                                         'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'start'                      => ($page3 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data4 = array(
                                         'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'start'                      => ($page4 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data5 = array(
                                         'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'start'                      => ($page5 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data6 = array(
                                           'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'start'                      => ($page6 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data7 = array(
         'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'start'                      => ($page7 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
		$filter_data8 = array(
                                          'filter_store'	             => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'start'                      => ($page8 - 1) * $this->config->get('config_limit_admin'),
			'limit'                      => $this->config->get('config_limit_admin')
		);
             
                
/***********************************   Material received ***********************************************/ 
                
              // if($this->request->get['tab']=="1"){
                   
		  $order_total = $this->model_report_stock->getTotalOrdersReceived($filter_data);

		  $results = $this->model_report_stock->getOrdersReceived($filter_data);
                
		foreach ($results as $result) { //print_r($result);
                        if($result['store_name']!="")
                        { $store_rec=$result['store_name']; } else { $store_rec=$result["store_id"]; }
                        $arrray1=explode('Rs.',$result['price']);
                        if($arrray1[1]!="")
                        {
                        $total=$arrray1[1]+$result['tax'];
                        }
                        else
                        {
                          $total=$arrray1[0]+$result['tax'];  
                        }
                        if($arrray1[1]!="")
                        {
                          $price=$arrray1[1];
                        }
                        else
                        {
                          $price=$arrray1[0];  
                        }
			$data['orders'][] = array(
				'store_name'   => $store_rec,
				'order_date'   => date($this->language->get('date_format_short'), strtotime($result['order_date'])),
				'recive_date'  => date($this->language->get('date_format_short'), strtotime($result['recive_date'])),
                                'product_id'     => $result['product_id'],
				'price'   => number_format((float)$price, 2, '.', ''),
                                'tax'   => number_format((float)$result['tax'], 2, '.', ''),
                                'quantity'   => $result['quantity'],
                                'Transaction_Type'   => $result['Transaction_Type'],
                                'total' => number_format((float)$total, 2, '.', ''),
                                'store_transfer' => $result['store_transfer'],
                                'product_name'  => $result["product_name"],
			        'Current_status'  => $result["Current_status"]
                        );     
		}

           // }
                  
/*********************************  end  Material received ****************************************/ 
                  
                //if($this->request->get['tab']=="2"){
                            $order_total2 = $this->model_report_stock->getTotalOrders($filter_data2);

		$results2 = $this->model_report_stock->getOrders($filter_data2);

		foreach ($results2 as $result) { //print_r($result);
                        if($result['store_name']!="")
                        { $store_rec=$result['store_name']; } else { $store_rec=$result["store_id"]; }
                        //$arrray1=explode('Rs.',$result['price']);
                        //$total=$arrray1[1]+$result['tax'];
                
			$data['orders2'][] = array(
				'store_name1'   => $store_rec,
				'order_date1'   => date($this->language->get('date_format_short'), strtotime($result['order_date'])),
				'product_id1'     => $result['product_id'],
				'price1'   => number_format((float)$result['price'], 2, '.', ''),
                                'tax1'   => number_format((float)$result['tax'], 2, '.', ''),
                                'quantity1'   => $result['quantity'],
                                'Transaction_Type1'   => $result['Transaction_Type'],
                                'total1' => number_format((float)$result['Total'], 2, '.', ''),
                                'store_transfer1' => $result['store_transfer'],
                                'product_name1'  => $result["product_name"],
		                'Current_status1' => $result["Current_status"],
		                'To_be_Recived1' => $result["To_be_Recived"]
                        );
		}
              //  }
                
 /*****************************************SALE**********************************************/               
                //if($this->request->get['tab']=="3"){
                
                   $this->load->model('setting/store');
                   $this->load->model('report/sale_summary');  
                   //$t3=$this->model_report_sale_summary->getTotalSale_category($filter_data3);
		   $order_total3 =1;

		   $data['orders3'] = array();

		   //$results3 = $this->model_report_sale_summary->getSale_summary_category($filter_data3);
                               $results3 = $this->model_report_sale_summary->getSale_summary_subsidy_cash($filter_data3);

                   //print_r($results);die;
                	
                       foreach ($results3 as $result) {      //print_r($result);
			$total_cash=$total_cash+$result['Cash'];
                                          $total_tagged=$total_tagged+$result['Tagged'];
                                          $total_subsidy=$total_subsidy+$result['Subsidy'];
			$total_Cash_Tagged=$total_Cash_Tagged+$result['Cash_Tagged'];
                                          $total_Cash_subsidy=$total_Cash_subsidy+$result['Cash_subsidy'];

			         $data['orders3'][] = array(
                             		'store_id' => $result['store_id'],
				'store_name' => $result['store_name'],
				'cash_order' => $result['cash_order'],
				'tagged_order' => $result['tagged_order'],
				'subsidy_order' => $result['Subsidy_order'],
				'Cash_tagged_order' => $result['Cash_tagged_order'],
				'cash'		=>$this->currency->format($result['Cash']),	
				'tagged'	=>$this->currency->format($result['Tagged']),	
				'subsidy'	=>$this->currency->format($result['Subsidy']),
				'Cash_Tagged'	=>$this->currency->format($result['Cash_Tagged']),
                                                        'Cash_subsidy'	=>$this->currency->format($result['Cash_subsidy']),
                               		'creditlimit'	=>$this->currency->format($result['creditlimit']),
                                		'currentcredit'	=>$this->currency->format($result['currentcredit']),
				'total'   => $this->currency->format(($result['Cash']+$result['Tagged']+$result['Subsidy']+$result['Cash_Tagged']+$result['Cash_subsidy']))
			);
		}
           // }
           //print_r($data['orders3']);

	          $data['total_cash_all']=$total_cash;
                        $data['total_tagged_all']=$total_tagged;
                        $data['total_subsidy_all']=$total_Cash_subsidy;
                        $data['total_cash_tagged_all']=$total_Cash_Tagged;
	          $data['total_cash_subsidy_all']=$total_Cash_subsidy;

                  //print_r($data['orders3']);
/********************************************END SALE*********************************************/                  
       
            
          
/***************************************************AMOUNT DEPOSIT********************************/
        
    //if($this->request->get['tab']=="4"){
        
            $this->load->model('report/cash');
            $order_total4 = $this->model_report_cash->getTotalCash_transation($filter_data4);
	    $bank_totals = $this->model_report_cash->get_bank_sum_cash($filter_data4);
	    $data['orders4'] = array();

	     $results4 = $this->model_report_cash->getCash_report($filter_data4);
                
		foreach ($results4 as $result) { //print_r($result);
			         $data['orders4'][] = array(
				'SIID3' => $result['transid'],
				'amount3'   => $result['amount'],
				'store_id3'      => $result['store_id'],
				'name3'     => $result['name'],
                                'date_added3'=>  date($this->language->get('date_format_short'), strtotime($result['date_added'])),   
				'bank_name3'      => $result['bank_name'],
				'status3'      => $result['status'],
				'accepted_by3'      => $result['firstname']." ".$result['lastname']
			);
		}
         
         
    //}
/******************************************END AMOUNT DEPOSIT*******************************************/


/*************************product sales count start here for tab 5 in result 4*******************************/
		$this->load->model('report/product_storewisesales');  

		$t5=$this->model_report_product_storewisesales->getTotalsales($filter_data5);
		$order_total5 = $t5["total"];
		$data["total_amount_all_product_sales"]= $t5["total_amount"];
		$total_amount5=0;
		$results5 = $this->model_report_product_storewisesales->getSales($filter_data5);

		foreach ($results5 as $result) { $total_amount5=$total_amount5+$result['total'];
			$data['orders5'][] = array(
				'name'       => $result['name'],
				'product_id'      => $result['product_id'],
                                'store_name'      => $result['store_name'],
				'quantity'   => $result['quantity'],
				
				'total'      => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		}
$data["total_amount4"]=$total_amount4;
/*=========================Product sales end here for tab 5 in result4===================*/       

    
    
/***********************************current inventory************************************************/
              	//if($this->request->get['tab']=="6"){
    
                  $this->load->model('report/Inventory');
                  
                  $data['orders6'] = array();
                   if ($this->request->get['filter_store']!="") {
                      $order_total6 = $this->model_report_Inventory->getTotalInventory($filter_data6);
                       $results6 = $this->model_report_Inventory->getInventory_report($filter_data6);
 
                    }
		
                              

		foreach ($results6 as $result) { 
			         $data['orders6'][] = array(
                                'product_id5' => $result['product_id'],
				'product_name5' => $result['Product_name'],
                                'store_id5'      => $result['store_id'],
                                'store_name5'      => $result['store_name'],
				'qnty5'   => $result['Qnty'],
				'Amount5'      => $result['Amount'],
				'price5'     => $result['price'],
                               
			);
		}
    
                //}
    /***********************************end current inventory************************************************/

/***************************************** DATE WISE SALE**********************************************/               
                //if($this->request->get['tab']=="7"){
                
                   
                   $this->load->model('report/sale_summary');  
                   $t7=$this->model_report_sale_summary->getTotalSale_category_date_wise($filter_data7);
		   $order_total7 =$t7["total"] ;

		   $data['orders7'] = array();

		   $results7 = $this->model_report_sale_summary->getSale_summary_category_date_wise($filter_data7);

                   //print_r($results);die;
                	$data['total_cash_all7']=$t7["Cash"];
                        $data['total_tagged_all7']=$t7["Tagged"];
                        $data['total_subsidy_all7']=$t7["Subsidy"];
		        $data['total_cash_tagged_all7']=$t7["Cash_Tagged"];
                        //print_r($total_cash_all);
		foreach ($results7 as $result) {  
                    
	          $total_cash7=$total_cash7+$result['Cash'];
                        $total_tagged7=$total_tagged7+$result['Tagged'];
                        $total_subsidy7=$total_subsidy7+$result['Subsidy'];
	          $total_Cash_Tagged7=$total_Cash_Tagged7+$result['Cash_Tagged'];

			$data['orders7'][] = array(
                             	'store_id2' => $result['store_id'],
				'store_name2' => $result['store_name'],
				'cash_order2' => $result['cash_order'],
				'tagged_order2' => $result['tagged_order'],
				'subsidy_order2' => $result['Subsidy_order'],
                                                        'date_added7' => $result['date_added'],
				'Cash_tagged_order2' => $result['Cash_tagged_order'],
				'cash2'		=>$this->currency->format($result['Cash']),	
				'tagged2'	=>$this->currency->format($result['Tagged']),	
				'subsidy2'	=>$this->currency->format($result['Subsidy']),
				'Cash_Tagged2'	=>$this->currency->format($result['Cash_Tagged']),
                               	               'creditlimit2'	=>$this->currency->format($result['creditlimit']),
                                                        'currentcredit2'	=>$this->currency->format($result['currentcredit']),
				'total2'   => $this->currency->format(($result['Cash']+$result['Tagged']+$result['Subsidy']+$result['Cash_Tagged']))
			);
		}  

                        $data['total_cash7']=$total_cash7;
                        $data['total_tagged7']=$total_tagged7;
                        $data['total_subsidy7']=$total_subsidy7;
	          $data['total_Cash_Tagged7']=$total_Cash_Tagged7; 
           // }
                  
/********************************************END DATE WISE SALE*********************************************/         
/***************************************** STORE EXPENSE START**********************************************/               
                //if($this->request->get['tab']=="8"){
                
                   
                   $this->load->model('hr/hr');  
                   $order_total8 = $this->model_hr_hr->getTotalbill($filter_data);
	 
		$results8 = $this->model_hr_hr->getBills($filter_data);

                   
		foreach ($results8 as $result) {  
                    
	          $data['orders8'][] = array(
					'start_date'  => $result['start_date'],
					'end_date'             => $result['end_date'],
					'store_name'       => $result['store_name'],
					'submitby'             => $result['submitby'],
					'approvedby'       => $result['approvedby'],
					'amount'           => $result["amount"],
					'filter_month'         => $result['filter_month'],
					'filter_year'         => $result['filter_year'],
					'uploded_file'     => $result['file'],
					'remarks'	   => $result['remarks'],
					'status'   => $result['status'],
					'SID'              => $result["SID"]
					
				);
		}  

                       
           // }
                  
/********************************************STORE EXPENSE END*********************************************/  
$this->load->model('setting/store');       
		$url = '';
                            if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                           if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
     
		if (isset($this->request->get['page2'])) {
			$url .= '&page2=' . $this->request->get['page2'];
		}
		if (isset($this->request->get['page3'])) {
			$url .= '&page3=' . $this->request->get['page3'];
		}
		if (isset($this->request->get['page4'])) {
			$url .= '&page4=' . $this->request->get['page4'];
		}
		if (isset($this->request->get['page5'])) {
			$url .= '&page5=' . $this->request->get['page5'];
		}
		if (isset($this->request->get['page6'])) {
			$url .= '&page6=' . $this->request->get['page6'];
		}      
                            if (isset($this->request->get['page7'])) {
			$url .= '&page7=' . $this->request->get['page7'];
		}  
		 if (isset($this->request->get['page8'])) {
			$url .= '&page8=' . $this->request->get['page8'];
		}  
     
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('storewisereport/storereport', 'token=' . $this->session->data['token'] . $url . '&page={page}&tab=1', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
           
            
          
		$pagination2 = new Pagination();
		$pagination2->total = $order_total2;
		$pagination2->page = $page2;
		$pagination2->limit = $this->config->get('config_limit_admin');
		$pagination2->url = $this->url->link('storewisereport/storereport', 'token=' . $this->session->data['token'] . $url . '&page2={page}&tab=2', 'SSL');

		$data['pagination2'] = $pagination2->render(); 

		$data['results2'] = sprintf($this->language->get('text_pagination'), ($order_total2) ? (($page2 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page2 - 1) * $this->config->get('config_limit_admin')) > ($order_total2 - $this->config->get('config_limit_admin'))) ? $order_total2 : ((($page2 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total2, ceil($order_total2 / $this->config->get('config_limit_admin')));
            
            
           
		$pagination3 = new Pagination();
		$pagination3->total = $order_total3;
		$pagination3->page = $page3;
		$pagination3->limit = $this->config->get('config_limit_admin');
		$pagination3->url = $this->url->link('storewisereport/storereport', 'token=' . $this->session->data['token'] . $url . '&page3={page}&tab=3', 'SSL');

		$data['pagination3'] = $pagination3->render();

		$data['results3'] = sprintf($this->language->get('text_pagination'), ($order_total3) ? (($page3 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page3 - 1) * $this->config->get('config_limit_admin')) > ($order_total3 - $this->config->get('config_limit_admin'))) ? $order_total3 : ((($page3 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total3, ceil($order_total3 / $this->config->get('config_limit_admin')));
         
            
          
		$pagination4 = new Pagination();
		$pagination4->total = $order_total4;
		$pagination4->page = $page4;
		$pagination4->limit = $this->config->get('config_limit_admin');
		$pagination4->url = $this->url->link('storewisereport/storereport', 'token=' . $this->session->data['token'] . $url . '&page4={page}&tab=4', 'SSL');

		$data['pagination4'] = $pagination4->render();

		$data['results4'] = sprintf($this->language->get('text_pagination'), ($order_total4) ? (($page4 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page4 - 1) * $this->config->get('config_limit_admin')) > ($order_total4 - $this->config->get('config_limit_admin'))) ? $order_total4 : ((($page4 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total4, ceil($order_total4 / $this->config->get('config_limit_admin')));
         


            
		$pagination5 = new Pagination();
		$pagination5->total = $order_total5;
		$pagination5->page = $page5;
		$pagination5->limit = $this->config->get('config_limit_admin');
		$pagination5->url = $this->url->link('storewisereport/storereport', 'token=' . $this->session->data['token'] . $url . '&page5={page}&tab=5', 'SSL');

		$data['pagination5'] = $pagination5->render();

		$data['results5'] = sprintf($this->language->get('text_pagination'), ($order_total5) ? (($page5 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page5 - 1) * $this->config->get('config_limit_admin')) > ($order_total5 - $this->config->get('config_limit_admin'))) ? $order_total5 : ((($page5 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total5, ceil($order_total5 / $this->config->get('config_limit_admin')));

                            $order_total6=$order_total6['total'];                  

		$pagination6 = new Pagination();
		$pagination6->total = $order_total5;
		$pagination6->page = $page6;
		$pagination6->limit = $this->config->get('config_limit_admin');
		$pagination6->url = $this->url->link('storewisereport/storereport', 'token=' . $this->session->data['token'] . $url . '&page6={page}&tab=6', 'SSL');

		$data['pagination6'] = $pagination6->render();
                            //print_r($order_total6);print_r($filter_data);
		$data['results6'] = sprintf($this->language->get('text_pagination'), ($order_total6) ? (($page6 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page6 - 1) * $this->config->get('config_limit_admin')) > ($order_total6 - $this->config->get('config_limit_admin'))) ? $order_total6 : ((($page6 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total6, ceil($order_total6 / $this->config->get('config_limit_admin')));
       
            
           
		$pagination7 = new Pagination();
		$pagination7->total = $order_total7;
		$pagination7->page = $page7;
		$pagination7->limit = $this->config->get('config_limit_admin');
		$pagination7->url = $this->url->link('storewisereport/storereport', 'token=' . $this->session->data['token'] . $url . '&page7={page}&tab=7', 'SSL');

		$data['pagination7'] = $pagination7->render();

		$data['results7'] = sprintf($this->language->get('text_pagination'), ($order_total7) ? (($page7 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page7 - 1) * $this->config->get('config_limit_admin')) > ($order_total7 - $this->config->get('config_limit_admin'))) ? $order_total7 : ((($page7 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total7, ceil($order_total7 / $this->config->get('config_limit_admin')));
            
		$pagination8 = new Pagination();
		$pagination8->total = $order_total8;
		$pagination8->page = $page8;
		$pagination8->limit = $this->config->get('config_limit_admin');
		$pagination8->url = $this->url->link('storewisereport/storereport', 'token=' . $this->session->data['token'] . $url . '&page8={page}&tab=8', 'SSL');

		$data['pagination8'] = $pagination8->render();

		$data['results8'] = sprintf($this->language->get('text_pagination'), ($order_total8) ? (($page8 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page8 - 1) * $this->config->get('config_limit_admin')) > ($order_total8 - $this->config->get('config_limit_admin'))) ? $order_total8 : ((($page8 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total8, ceil($order_total8 / $this->config->get('config_limit_admin')));
            
            
                            //print_r($bank_totals);
                            $data["hdfc_total"]=$bank_totals["HDFC"];
                            $data["ICICI_total"]=$bank_totals["ICICI"];
                            $data["State_Bank_of_India_total"]=$bank_totals["State_Bank_of_India"];
                            $data["TAGGED_BILLS_total"]=$bank_totals["TAGGED_BILLS"];
}
      $this->load->model('setting/store');
      $data['stores'] = $this->model_setting_store->getStores();
      $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Store  Report',
			'href' => $this->url->link('storewisereport/storereport', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

                            $data['entry_date_start'] = 'Start date';
		$data['entry_date_end'] = 'End date';
		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('storewisereport/storereport');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                            $this->response->setOutput($this->load->view('storewisereport/storereport.tpl', $data));

        

    }
    
   
    
 
}



            