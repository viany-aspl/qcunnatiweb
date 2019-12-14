<?php
	class ControllerPartnerBankpayment extends Controller 
	{
		public function payment_form() 
		{
			
			$this->load->model('purchase/return_orders');
			$this->document->setTitle('Credit Posting (Partner)');
			$data['heading_title'] = 'Credit Posting (Partner)';
			$data['text_list'] = 'Credit Posting (Partner)';
			
			
			$data['token'] = $this->session->data['token'];
			$this->load->model('partner/bank_payment');
            			$this->load->model('setting/store');
            			$data["units"] = $this->model_partner_bank_payment->getAllUnits();
            			$data["store"] = $this->model_setting_store->getFranchiseStores();
              
			$url ='';
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Credit Posting (Partner)',
				'href' => $this->url->link('partner/bank_payment/payment_form', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['cancel']=$this->url->link('partner/bank_payment/getlist', 'token=' . $this->session->data['token'], 'SSL');
			
			$data['bank_payment_form']=$this->url->link('partner/bank_payment/add_bank_payment', 'token=' . $this->session->data['token'], 'SSL');
			//$data['tagged_form']=$this->url->link('partner/bank_payment/add_tagged_payment', 'token=' . $this->session->data['token'], 'SSL');
			//$data['subsidy_form']=$this->url->link('partner/bank_payment/add_subsidy_payment', 'token=' . $this->session->data['token'], 'SSL');

			if($this->request->get['tab']=='tagged')			
			{
				$tagged_filter=array();
				$data['filter_tagged_date_start']=$filter_tagged_date_start=$this->request->get['filter_tagged_date_start'];	
				$data['filter_tagged_date_end']=$filter_tagged_date_end=$this->request->get['filter_tagged_date_end'];
				$data['taggedstore']=$taggedstore=$this->request->get['taggedstore'];
			
				$data['store_activation_date_tagged']=$store_activation_date=$this->model_partner_bank_payment->getStoreActiveationDate($data['taggedstore']);

				$data['active_tab']='tagged';
				$tagged_filter=array(
							'filter_tagged_date_start'=>$filter_tagged_date_start,
							'filter_tagged_date_end'=>$filter_tagged_date_end,
							'taggedstore'=>$taggedstore,
							'store_activation_date'=>$store_activation_date
						     );
				$tagged_results=$this->model_partner_bank_payment->getTaggedOrdersByDate($tagged_filter);
				$tagged_orders=array();
				foreach($tagged_results as $tagged_result)
				{
					$tagged_payment_status=$this->model_partner_bank_payment->checkTaggedPaymentStatusByDateStore($tagged_result['sale_date'],$tagged_result['store_id']);
					$tagged_orders[]=array(
								'store_id'=>$tagged_result['store_id'],
								'store_name'=>$tagged_result['store_name'],
								'totaltaggedamount'=>number_format((float)$tagged_result['totaltaggedamount'],2,'.',''),
								'sale_date'=>$tagged_result['sale_date'],
								'tagged_payment_status'=>$tagged_payment_status
								);
				}
				$data['tagged_orders']=$tagged_orders;
				//print_r($tagged_orders);
			}

			if($this->request->get['tab']=='subsidy')			
			{
				$subsidy_filter=array();
				$data['filter_subsidy_date_start']=$filter_subsidy_date_start=$this->request->get['filter_subsidy_date_start'];	
				$data['filter_subsidy_date_end']=$filter_subsidy_date_end=$this->request->get['filter_subsidy_date_end'];
				$data['subsidystore']=$subsidystore=$this->request->get['subsidystore'];
			
				$data['store_activation_date_subsidy']=$store_activation_date=$this->model_partner_bank_payment->getStoreActiveationDate($data['subsidystore']);

				$data['active_tab']='subsidy';
				$subsidy_filter=array(
							'filter_subsidy_date_start'=>$filter_subsidy_date_start,
							'filter_subsidy_date_end'=>$filter_subsidy_date_end,
							'subsidystore'=>$subsidystore,
							'store_activation_date'=>$store_activation_date
						     );
				$subsidy_results=$this->model_partner_bank_payment->getSubsidyOrdersByDate($subsidy_filter);
				$subsidy_orders=array();
				foreach($subsidy_results as $subsidy_result)
				{
					$subsidy_payment_status=$this->model_partner_bank_payment->checkSubsidyPaymentStatusByDateStore($subsidy_result['sale_date'],$subsidy_result['store_id']);
					$subsidy_orders[]=array(
								'store_id'=>$subsidy_result['store_id'],
								'store_name'=>$subsidy_result['store_name'],
								'totalsubsidyamount'=>number_format((float)$subsidy_result['totalsubsidyamount'],2,'.',''),
								'sale_date'=>$subsidy_result['sale_date'],
								'subsidy_payment_status'=>$subsidy_payment_status
								);
				}
				$data['subsidy_orders']=$subsidy_orders;
				
			}

			$data['header'] = $this->load->controller('common/header');
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('partner/bank_payment_form_by_month.tpl', $data));
			
		}
		
		public function add_bank_payment()
		{
			if ($this->request->server['REQUEST_METHOD'] == 'POST')
            {
			//print_r($this->request->post);exit;
            $this->load->model('partner/bank_payment');
            $data['logged_user_data'] = $this->user->getId();
            $this->model_partner_bank_payment->insrtPayoutdtl($this->request->post,$data['logged_user_data']);
			$this->session->data['success'] = 'Credit Posting done successfully';
            $this->response->redirect($this->url->link('partner/bank_payment/getlist', 'token=' . $this->session->data['token'] , 'SSL'));
            }
                
		}
		public function submit_tagged_payment()
		{
			$this->request->post['store']=$this->request->get['taggedstore'];
			$this->request->post['amount']=$this->request->get['tagged_value'];
			$this->request->post['filter_tagged_date']=$this->request->get['filter_tagged_date'];
			$this->request->post['transaction_type']='Credit Posting';
			$this->request->post['payment_method']='Tagged Payment';
			$this->request->post['tr_number']='NA';
			$this->request->post['unit']='0';
			
			
           			$this->load->model('partner/bank_payment');
           			$data['logged_user_data'] = $this->user->getId();
            			$ret_by_model=$this->model_partner_bank_payment->insertTaggedPayment($this->request->post,$data['logged_user_data']);
			//print_r($this->request->get);
			//exit;
			if($ret_by_model==0)
			{
				$this->session->data['error'] = 'There is already Credit Posting (Tagged) Done for the Same date and Store';
            				$this->response->redirect($this->url->link('partner/bank_payment/payment_form&tab=tagged&filter_tagged_date_start='.$this->request->get['filter_tagged_date_start'].'&filter_tagged_date_end='.$this->request->get['filter_tagged_date_end'].'&taggedstore='.$this->request->get['taggedstore'], 'token=' . $this->session->data['token'] , 'SSL'));
			}
			else
			{
				$this->session->data['success'] = 'Credit Posting done successfully';
            				$this->response->redirect($this->url->link('partner/bank_payment/payment_form&tab=tagged&filter_tagged_date_start='.$this->request->get['filter_tagged_date_start'].'&filter_tagged_date_end='.$this->request->get['filter_tagged_date_end'].'&taggedstore='.$this->request->get['taggedstore'], 'token=' . $this->session->data['token'] , 'SSL'));
			}
            
                
		}
		public function submit_subsidy_payment()
		{
			$this->request->post['store']=$this->request->get['subsidystore'];
			$this->request->post['amount']=$this->request->get['subsidy_value'];
			$this->request->post['filter_subsidy_date']=$this->request->get['filter_subsidy_date'];
			$this->request->post['transaction_type']='Credit Posting';
			$this->request->post['payment_method']='Subsidy Payment';
			$this->request->post['tr_number']='NA';
			$this->request->post['unit']='0';

           			$this->load->model('partner/bank_payment');
           			$data['logged_user_data'] = $this->user->getId();
            			$ret_by_model=$this->model_partner_bank_payment->insertSubsidyPayment($this->request->post,$data['logged_user_data']);
			if($ret_by_model==0)
			{
				$this->session->data['error'] = 'There is already Credit Posting (Subsidy) Done for the Same date and Store';
            				$this->response->redirect($this->url->link('partner/bank_payment/payment_form&tab=subsidy&filter_subsidy_date_start='.$this->request->get['filter_subsidy_date_start'].'&filter_subsidy_date_end='.$this->request->get['filter_subsidy_date_end'].'&subsidystore='.$this->request->get['subsidystore'], 'token=' . $this->session->data['token'] , 'SSL'));
			}
			else
			{
				$this->session->data['success'] = 'Credit Posting done successfully';
            				$this->response->redirect($this->url->link('partner/bank_payment/payment_form&tab=subsidy&filter_subsidy_date_start='.$this->request->get['filter_subsidy_date_start'].'&filter_subsidy_date_end='.$this->request->get['filter_subsidy_date_end'].'&subsidystore='.$this->request->get['subsidystore'], 'token=' . $this->session->data['token'] , 'SSL'));
			}
            
                
		}
		
        
public function downloadlist()
	{
		$this->load->model('partner/bank_payment');

		if (isset($this->request->get['filter_date_start'])) 
		{
			$filter_date_start = $this->request->get['filter_date_start'];
		} 
		else 
		{
			$data['filter_date_start']=$filter_date_start = date('Y-m').'-01';
		}
		if (isset($this->request->get['filter_date_end'])) 
		{
			$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
		} 
		else 
		{
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_stores_id'])) 
		{
			$filter_stores_id = $this->request->get['filter_stores_id'];
		} 
		else 
		{
			$filter_stores_id = 0;
		}

		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;
		$data['filter_stores_id']=$filter_stores_id;

		$data['payout'] = array();

		$filter_data = array(
					'filter_date_start' => $filter_date_start,
					'filter_date_end' => $filter_date_end,
					'filter_stores_id' => $filter_stores_id
					);
		$results = $this->model_partner_bank_payment->getPayoutList($filter_data);

	
	$file_name="partner_payment_".date('dMy').'.xls';
 	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
 	header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
 	header("Expires: 0");
 	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 	header("Cache-Control: private",false);
	
	echo '<table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Store Name</td>
                <td class="text-right">Amount</td>
                <td class="text-right">Transaction Type</td>  
	<td class="text-right">Transaction Number</td>                
                <td class="text-right">Payment Method</td>
                <td class="text-right">Processed Date</td>
				<td class="text-right">Tagged/Subsidy Date</td>
              </tr>
            </thead>
            <tbody>';
    if ($results) 
	{
        foreach ($results as $pay) 
		{
           echo '<tr>
                <td class="text-left">'.$pay['name'].'</td>
                <td class="text-right">'.$pay['amount'].'</td>
                <td class="text-right">'.$pay['transaction_type'].'</td>
	<td class="text-right">'.$pay['tr_number'].'</td>
                <td class="text-right">'.$pay['payment_method'].'</td>
                <td class="text-right">'.$pay['create_date'].'</td>
				<td class="text-right">'.$pay['tagged_subsidy_bill_date'].'</td>
              </tr>';
        } 
    } 
	else 
	{
		echo '<tr>
            <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
        </tr>';
    } 
       echo ' </tbody>
    </table>';
	
	
}
public function getlist()
{
	$this->load->language('report/product_purchased');
	$this->document->setTitle("Credit Posting List");
	$this->load->model('partner/bank_payment');

	if (isset($this->request->get['filter_date_start'])) 
	{
		$filter_date_start = $this->request->get['filter_date_start'];
	} 
	else 
	{
		$data['filter_date_start']=$filter_date_start = date('Y-m').'-01';
	}
	if (isset($this->request->get['filter_date_end'])) 
	{
		$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
	} 
	else 
	{
		$filter_date_end = date('Y-m-d');
	}
	if (isset($this->request->get['filter_stores_id'])) 
	{
		$filter_stores_id = $this->request->get['filter_stores_id'];
	} 
	else 
	{
		$filter_stores_id = 0;
	}
	$data['filter_date_start']=$filter_date_start;
	$data['filter_date_end']=$filter_date_end;
	$data['filter_stores_id']=$filter_stores_id;

	if (isset($this->request->get['page'])) 
	{
		$page = $this->request->get['page'];
	} 
	else 
	{
		$page = 1;
	}

	$url = '';

	if (isset($this->request->get['filter_date_start'])) 
	{
		$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
	}

	if (isset($this->request->get['filter_date_end'])) 
	{
		$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
	}

	if (isset($this->request->get['filter_stores_id'])) 
	{
		$url .= '&filter_stores_id=' . $this->request->get['filter_stores_id'];
	}

	if (isset($this->request->get['page'])) 
	{
		$url .= '&page=' . $this->request->get['page'];
	}

	$data['breadcrumbs'] = array();

	$data['breadcrumbs'][] = array(
		'text' => 'Dashboard',
		'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

	$data['breadcrumbs'][] = array(
		'text' => $this->language->get('heading_title'),
		'href' => $this->url->link('partner/bank_payment/getlist', 'token=' . $this->session->data['token'], 'SSL')
		);
	$data['cancel']=$this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');

	$data['payout'] = array();

	$filter_data = array(
		'filter_date_start' => $filter_date_start,
		'filter_date_end' => $filter_date_end,
		'filter_stores_id' => $filter_stores_id,
		'start' => ($page - 1) * $this->config->get('config_limit_admin'),
		'limit' => $this->config->get('config_limit_admin')
		);
	
	$product_total = $this->model_partner_bank_payment->getTotalPayoutList($filter_data);

	$results = $this->model_partner_bank_payment->getPayoutList($filter_data);
	//print_r($results);
	foreach ($results as $result) 
	{
		$data['payout'][] = array(
				'amount' => $result['amount'],
				'transaction_type' => $result['transaction_type'],
				'create_date' => $result['create_date'],
				'payment_method' => $result['payment_method'],
				'name' => $result['name'],
				'firstname' => $result['firstname'],
				'lastname' => $result['lastname'],
				'tagged_subsidy_bill_date'=>$result['tagged_subsidy_bill_date'],
				'tr_number'=>$result['tr_number']
			);
	}
	$data['heading_title'] = $this->language->get('heading_title');

	$data['text_list'] = $this->language->get('text_list');
	$data['text_no_results'] = $this->language->get('text_no_results');

	$data['column_name'] = $this->language->get('column_name');
	$data['column_model'] = $this->language->get('column_model');
	$data['column_quantity'] = $this->language->get('column_quantity');
	$data['column_total'] = $this->language->get('column_total');

	$data['entry_date_start'] = $this->language->get('entry_date_start');
	$data['entry_date_end'] = $this->language->get('entry_date_end');
	$data['entry_status'] = $this->language->get('entry_status');
	if (isset($this->session->data['error'])) 
	{
		$data['error'] = $this->session->data['error'];
		unset($this->session->data['error']);
	} 
	else 
	{
		$data['error'] = '';
	}
	if (isset($this->session->data['success'])) 
	{
		$data['success'] = $this->session->data['success'];
		unset($this->session->data['success']);
	} 
	else 
	{
		$data['success'] = '';
	}

	$data['button_filter'] = $this->language->get('button_filter');

	$data['token'] = $this->session->data['token'];
	$this->load->model('setting/store');
	$data['order_stores'] = $this->model_setting_store->getFranchiseStores();
	$url = '';

	if (isset($this->request->get['filter_date_start'])) {
		$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
	}

	if (isset($this->request->get['filter_date_end'])) {
		$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
	}

	if (isset($this->request->get['filter_stores_id'])) {
		$url .= '&filter_stores_id=' . $this->request->get['filter_stores_id'];
	}

	$pagination = new Pagination();
	$pagination->total = $product_total;
	$pagination->page = $page;
	$pagination->limit = $this->config->get('config_limit_admin');
	$pagination->url = $this->url->link('partner/bank_payment/getlist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

	$data['pagination'] = $pagination->render();
	$data['filter_stores_id']=$filter_stores_id;
	$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

	$data['filter_date_start'] = $filter_date_start;
	$data['filter_date_end'] = $filter_date_end;
	$data['filter_order_status_id'] = $filter_order_status_id;

	$data['header'] = $this->load->controller('common/header');
	$data['column_left'] = $this->load->controller('common/column_left');
	$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('partner/bank_payment_list.tpl', $data));
}
public function index() {
			
			
			$this->load->model('purchase/return_orders');
			$this->document->setTitle('Bank Payment (Partner)');
			$data['heading_title'] = 'Credit Posting (Partner)';
			$data['text_list'] = 'Credit Posting (Partner)';
			
			
			$data['token'] = $this->session->data['token'];
			$this->load->model('partner/bank_payment');
            $this->load->model('setting/store');
            $data["units"] = $this->model_partner_bank_payment->getAllUnits();
            $data["store"] = $this->model_setting_store->getFranchiseStores();
              
			$url ='';
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Credit Posting (Partner)',
				'href' => $this->url->link('partner/bank_payment', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['cancel']=$this->url->link('partner/bank_payment/getlist', 'token=' . $this->session->data['token'], 'SSL');
			
			$data['bank_payment_form']=$this->url->link('partner/bank_payment/add_bank_payment', 'token=' . $this->session->data['token'], 'SSL');
			$data['tagged_form']=$this->url->link('partner/bank_payment/add_tagged_payment', 'token=' . $this->session->data['token'], 'SSL');
			$data['subsidy_form']=$this->url->link('partner/bank_payment/add_subsidy_payment', 'token=' . $this->session->data['token'], 'SSL');
			
			$data['header'] = $this->load->controller('common/header');
			//$data['stores'] = $this->model_setting_store->getStores();
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('partner/bank_payment_form.tpl', $data));
			
		}
		public function getTaggedvaluebyStoreDate()
		{
			$this->load->model('partner/bank_payment');
			
			$returndata=array();
			if(isset($this->request->get['storeid']))
			{
				$data['store_id']=$this->request->get['storeid'];
				$data['date']=$this->request->get['date'];
				$taggedamount=$this->model_partner_bank_payment->gettaggedvaluebyStoreDate($data);
				if(!empty($taggedamount))
				{
				$returndata['taggedamount']=number_format((float)$taggedamount,2,'.','');
				}
				else
				{
					$returndata['taggedamount']='0';
				}
				$returndata['getOrdersCountByStoreDate']=$this->model_partner_bank_payment->getOrdersCountByStoreDate($data,'tagged');
			}
			$this->response->setOutput(json_encode($returndata));
			return;
		}
		public function getSubsidyvaluebyStoreDate()
		{
			$this->load->model('partner/bank_payment');
			
			$returndata=array();
			if(isset($this->request->get['storeid']))
			{
				$data['store_id']=$this->request->get['storeid'];
				$data['date']=$this->request->get['date'];
				$taggedamount=$this->model_partner_bank_payment->getSubsidyvaluebyStoreDate($data);
				if(!empty($taggedamount))
				{
				$returndata['taggedamount']=number_format((float)$taggedamount,2,'.','');
				}
				else
				{
					$returndata['taggedamount']='0';
				}
				$returndata['getOrdersCountByStoreDate']=$this->model_partner_bank_payment->getOrdersCountByStoreDate($data,'subsidy');
			}
			$this->response->setOutput(json_encode($returndata));
			return;
		}

		public function add_tagged_payment()
		{
			if ($this->request->server['REQUEST_METHOD'] == 'POST')
            {
			

	$this->request->post['store']=$this->request->post['tagged_store'];
	$this->request->post['amount']=$this->request->post['tagged_value'];
	$this->request->post['transaction_type']='Credit Posting';
	$this->request->post['payment_method']='Tagged Payment';
	$this->request->post['tr_number']='NA';
	$this->request->post['unit']='0';
	//print_r($this->request->post);exit;
           	$this->load->model('partner/bank_payment');
           	$data['logged_user_data'] = $this->user->getId();
            	$ret_by_model=$this->model_partner_bank_payment->insertTaggedPayment($this->request->post,$data['logged_user_data']);
	if($ret_by_model==0)
	{
		$this->session->data['error'] = 'There is already Credit Posting (Tagged) Done for the Same date and Store';
            		$this->response->redirect($this->url->link('partner/bank_payment/getlist', 'token=' . $this->session->data['token'] , 'SSL'));
	}
	else
	{
		$this->session->data['success'] = 'Credit Posting done successfully';
            		$this->response->redirect($this->url->link('partner/bank_payment/getlist', 'token=' . $this->session->data['token'] , 'SSL'));
	}
            }
                
		}
		public function add_subsidy_payment()
		{
			if ($this->request->server['REQUEST_METHOD'] == 'POST')
            {
			

	$this->request->post['store']=$this->request->post['subsidy_store'];
	$this->request->post['amount']=$this->request->post['subsidy_value'];
	$this->request->post['transaction_type']='Credit Posting';
	$this->request->post['payment_method']='Subsidy Payment';
	$this->request->post['tr_number']='NA';
	$this->request->post['unit']='0';
	//print_r($this->request->post);exit;
           	$this->load->model('partner/bank_payment');
           	$data['logged_user_data'] = $this->user->getId();
            	$ret_by_model=$this->model_partner_bank_payment->insertSubsidyPayment($this->request->post,$data['logged_user_data']);
	if($ret_by_model==0)
	{
		$this->session->data['error'] = 'There is already Credit Posting (Subsidy) Done for the Same date and Store';
            		$this->response->redirect($this->url->link('partner/bank_payment/getlist', 'token=' . $this->session->data['token'] , 'SSL'));
	}
	else
	{
		$this->session->data['success'] = 'Credit Posting done successfully';
            		$this->response->redirect($this->url->link('partner/bank_payment/getlist', 'token=' . $this->session->data['token'] , 'SSL'));
	}
            }
                
		}
	}
?>