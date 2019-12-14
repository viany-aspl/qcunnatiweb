<?php
	class ControllerPartnerCashAdjustment extends Controller {
		public function index() {
			
			$this->load->model('purchase/return_orders');
			$this->document->setTitle('Partner Cash In-Hand adjustment');
			$data['heading_title'] = 'Partner Cash In-Hand adjustment';
			$data['text_list'] = 'Cash In-Hand adjustment';
			
			
			$data['token'] = $this->session->data['token'];
			$this->load->model('partner/bank_payment');
            $this->load->model('setting/store');
            			
            $data["store"] = $this->model_setting_store->getStores();//getFranchiseStores
              
			$url ='';
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Cash In-Hand adjustment',
				'href' => $this->url->link('partner/cash_adjustment', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['cancel']=$_SERVER['HTTP_REFERER'];//$this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
			
			$data['cash_adjustment_form']=$this->url->link('partner/cash_adjustment/cash_adjustment_submit', 'token=' . $this->session->data['token'], 'SSL');
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
			$data['header'] = $this->load->controller('common/header');
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('partner/cash_adjustment_form.tpl', $data));
			
		}
		
		public function getstoreuser()
		{
			$this->load->model('partner/bank_payment');
			
			if(isset($this->request->get['store_id']))
			{
				echo '<option value="">Select User</option>';
				$returndata=$this->model_partner_bank_payment->getstoreuser($this->request->get['store_id']);
				foreach($returndata as $returndata2)
				{
					echo '<option value="'.$returndata2['user_id'].'">'.$returndata2['firstname'].' '.$returndata2['lastname'].'</option>';
				}
			}
			
			
		}
		
		public function getinhandcash()
		{
			$this->load->model('partner/bank_payment');
			
			if(isset($this->request->get['store_id']))
			{
				
				$returndata=$this->model_partner_bank_payment->getinhandcash($this->request->get['store_id'],$this->request->get['user_id']);
			}
			echo ($returndata);
			
		}
		

		public function cash_adjustment_submit()
		{
			$this->load->model('partner/bank_payment');
			if ($this->request->server['REQUEST_METHOD'] == 'POST')
            {
				$current_cash= $this->model_partner_bank_payment->getinhandcash($this->request->post['store'],$this->request->post['user']);
                if($current_cash<$this->request->post['amount'])
                {
                    $this->session->data['error'] = 'Error: Amount can not be greater then Cash in Hand.';                        
                    $this->response->redirect($this->url->link('partner/cash_adjustment', 'token=' . $this->session->data['token'] , 'SSL'));                      
                }
				else
				{
					$data['ce_id']='Cash In-Hand adjustment';
					$data['bank_id']='0';
					$data['bank_name']='Sales Adjustment';
					$data['amount']=$this->request->post['amount'];
					$data['user_id']=$this->request->post['user'];
					$data['store_id']=$this->request->post['store'];

					
					$data['ce_name']='Cash In-Hand adjustment';
					$data['remarks']=$this->request->post['remarks'];
					$current_cash= $this->model_partner_bank_payment->addbankTrans($data); 
					$this->session->data['success'] = 'Cash In-Hand adjusted successfully';                         
                    $this->response->redirect($this->url->link('partner/cash_adjustment', 'token=' . $this->session->data['token'] , 'SSL'));
				}
				
            }
                
		}
		
	}
?>