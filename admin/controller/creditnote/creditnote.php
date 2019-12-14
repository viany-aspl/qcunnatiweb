<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
ini_set('max_execution_time', 3000);  //3000 seconds = 50 minutes

class ControllerCreditnoteCreditnote extends Controller {
	public function index() {
                       
            
		$this->load->language('report/reconciliation');

		$this->document->setTitle("Credit Note");

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
 $data['add'] = $this->url->link('creditnote/creditnote/view_creditnote_details', 'token=' . $this->session->data['token'] . $url, true);
		$data['breadcrumbs'] = array();
$data['token']=$this->session->data['token'];
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/reconciliation', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('creditnote/creditnote');
                		 $this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => $filter_store,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_unit'	     => $filter_unit,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$t1=$this->model_creditnote_creditnote->getOrderstotal($filter_data);
		$order_total = $t1;
		$total_tagged_amount_all=$t1["total_tagged_amount"];
		$total_tagged_amount=0;
		$results = $this->model_creditnote_creditnote->getOrders($filter_data);

		foreach ($results as $result) {
                     
			$data['orders'][] = array( 
                                'id' => $result['id'],
                                'credit_no'   => $result['credit_no'],
                                'store_name' => $result['store_name'],
                                'total_amount'   => $result['total_amount'],
		'total_amount_formated'=>$this->currency->format($result['total_amount']),
                                'cr_date'      => $result['cr_date']
                             				
			);
		}
		//$data['orders']=usort($data['orders'], "cmp");
		
		if(!empty($this->session->data['error_warning']))
		{
		$data['error_warning']=$this->session->data['error_warning'];
		}
		if(!empty($this->session->data['success']))
		{
		$data['success']=$this->session->data['success'];
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
		
               
		$data['button_filter'] = $this->language->get('button_filter');

                $data['stores'] = $this->model_setting_store->getStores();
		
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


		$data['redirect']=$this->url->link('creditnote/creditnote/view_creditnote_details', 'token=' . $this->session->data['token']);
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('creditnote/creditnote', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
                $data['filter_store'] = $filter_store;
		$data['filter_unit'] = $filter_unit;
		$data['total_tagged_amount'] =$total_tagged_amount;
		$data['total_tagged_amount_All'] =$total_tagged_amount_all;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
              
		$this->response->setOutput($this->load->view('creditnote/creditnote.tpl', $data));
	}
public function view_creditnote_details()
	{
                $data['token'] = $this->session->data['token'];  
				if(!empty($this->session->data['error_warning']))
		{
		$data['error_warning']=$this->session->data['error_warning'];
		}
                if($this->request->server['REQUEST_METHOD'] == 'POST')
                {               
                 // print_r($this->request->post); exit;
                  $this->load->model('creditnote/creditnote');
                    $total_activity=count($this->request->post['activity']);
                  // print_r($total_activity); exit;
                    $store=$this->request->post['store_id'];
					if(empty($store))
					{
					     $this->session->data['error_warning']='Please select store';
						     $this->response->redirect($this->url->link('creditnote/creditnote/view_creditnote_details', 'token=' . $this->session->data['token'] . $url, true));
							 exit;
					}
					else
					{
					$grand_total=$this->request->post['grand_total'];
                     $this->load->model('user/user');
                    $user_info = $this->model_user_user->getUser($this->user->getId());
                    $updated_by=$user_info['user_id'];
                    
                    $rem=$this->request->post['rem'];
                    
                             
                     $insert_d=$this->model_creditnote_creditnote->insert_credit_order($store,$grand_total);
                     
                     //print_r($insert_d); exit;
                    //print_r($updated_by); exit;
                    for($a=0;$a<$total_activity;$a++)
                    {
                 
                        $p_amount=$this->request->post['p_amount'][$a];
                        $rate=$this->request->post['p_price'][$a];
                        $qty=$this->request->post['p_qnty'][$a];
                        $activity=$this->request->post['activity'][$a];
                                                                       
                        $submit_d=$this->model_creditnote_creditnote->submit_creditnote_data($rem,$store,$p_amount,$rate,$qty,$activity,$updated_by,$insert_d);          
                     
                    }
					try {


$this->load->library('trans');
$trans=new trans($this->registry);
$trans->storewalletcredit($store,$grand_total,$rem);
$trans->addstoretrans($grand_total,$store,$updated_by,'CR',$insert_d,"Credit Note",$grand_total,$rem);


} catch (Exception $ex) {

}


					
                   // $update_d=$this->model_creditnote_creditnote->update_store_currentlimit($store,$grand_total);                 
                 
                    $this->session->data['success']='Credit Note Created Successfully with Credit Number : '.$invoice_number;
               
                    $this->response->redirect($this->url->link('creditnote/creditnote', 'token=' . $this->session->data['token'] . $url, true));
                }
				}
				
				
                
                        
                
                $this->load->model('creditnote/creditnote');
                $this->document->setTitle("View Order");
		$order_id = $this->request->get['order_id'];
		$data['column_left'] = $this->load->controller('common/column_left');
		/*$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');*/
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$url = '';
			
			if (isset($this->request->get['order_id'])) {
				$url .= '&order_id=' . urlencode(html_entity_decode($this->request->get['order_id'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_id'])) {
				$url .= '&filter_id=' . $this->request->get['filter_id'];
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
                        if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
			$data['breadcrumbs'][] = array(
			'text' => "Unnati Krishi Kendra Requisition",
			'href' => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
                $data['store_to_data']=$this->model_creditnote_creditnote->get_to_store_data_b2b($data['order_information']['order_info']['store_to']);
		$data['stores'] = $this->model_setting_store->getStores();
		$this->load->model('purchase/purchase_order');
		$data['order_information'] = $this->model_purchase_purchase_order->view_order_details($order_id);
		//print_r($data['order_information']);
                $data['cancel'] = $this->url->link('creditnote/creditnote', 'token=' . $this->session->data['token'] . $url, true);
		$data['pdf_export'] = $this->url->link('purchase/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, true);
//		
		$this->response->setOutput($this->load->view('creditnote/creditnoteview.tpl',$data));
//		
	}
          public function get_to_store_data_b2b()
        {
              
            $data['token'] = $this->session->data['token'];  
            $store_id = $this->request->get['store_id'];
            $this->load->model('creditnote/creditnote');
            $data=$this->model_creditnote_creditnote->get_to_store_data_b2b($store_id);
            $this->response->setOutput($data);
        }
	
	/*----------------------------view_order_details function ends here--------------*/

 public function download_creditnote()
        {
             $data['column_left'] = $this->load->controller('common/column_left');
             $data['footer'] = $this->load->controller('common/footer');
             $data['header'] = $this->load->controller('common/header');                
             $credit_no= $this->request->get['credit_no'];
             $this->load->model('creditnote/creditnote');
             $data['credit_information'] = $this->model_creditnote_creditnote->view_credit_details($credit_no); 
             $store_id=$data['credit_information'][0]['store_id'];
             $this->load->model('creditnote/creditnote');
             $data['store_to_data']=$this->model_creditnote_creditnote->get_to_store_data_b2b($store_id);
                    
             $html=$this->load->view('creditnote/creditnote_print.tpl',$data);
             require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             $mpdf = new mPDF('c','A4','','' , 0 , 0 , 25 , 10 , 5 , 7);
             //$stylesheet = file_get_contents('https://unnati.world/shop/admin/view/stylesheet/pos/bootstrap.min.css'); // external css
             //$mpdf->WriteHTML($stylesheet,1); 
             //$stylesheet = file_get_contents('https://unnati.world/shop/admin/view/stylesheet/sheet.css'); // external css
             //$mpdf->WriteHTML($stylesheet,1);
             
             //exit;
                $header = '<br/><div class="header" style="margin-top: 20px;">
		<div class="logo">
		<img style="float: right;margin-right: 40px;height: 30px;" src="https://unnati.world/shop/image/catalog/logo.png"  />
		</div>
 		
		<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;;" /> 
		</div>';
                
                $header = '<div class="header" style="">
                   
		<div class="logo" style="width: 100%;" >
		<div style="padding-left: 50px;">
		<img src="https://unnati.world/shop/image/letterhead_text.png" style="height: 40px; width: 121px;margin-top: 20px;marging-left: 100px;" />
		<img src="https://unnati.world/shop/image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />
		</div>
                         </div>
		<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;" /> 
		</div>';
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->SetHTMLHeader($header, 'O', false);
                  
                $footer = '<div class="footer">
                        
                        <img src="https://unnati.world/shop/image/letterhead_bottomline.png" style="height: 10px; width: 150% !important;" /> 
                        <div class="address"><img src="https://unnati.world/shop/image/letterhead_footertext.png" style="width: 120% !important;" /> </div>'
                        . '</div>';

                $mpdf->setAutoBottomMargin = 'stretch';       	 
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
    
                $mpdf->WriteHTML($html);
                
                $filename='Credit Note_'.$data['credit_information'][0]['creditno'].'.pdf';
                
                $mpdf->Output($filename,'D');
              
              
                
        }



}