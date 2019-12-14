<?php
class ControllerCommonDashboard extends Controller {
	public function index() {
		$this->load->language('common/dashboard');



		$this->load->model('user/user'); 
	
			$this->load->model('tool/image');
	

			$user_info = $this->model_user_user->getUser($this->user->getId());
	
			if ($user_info) 
			{
				if($user_info['user_group']=='DSCL Reports')
				{
				           $this->response->redirect($this->url->link('reportdscl/report', 'token=' . $this->session->data['token'], 'SSL'));
				}
 				if($user_info['user_group']=='Unit Office')
				{
					$this->response->redirect($this->url->link('report/stock/transfer', 'token=' . $this->session->data['token'], 'SSL'));
				}
				if($user_info['user_group']=='Territory Manager')
				{
					$this->response->redirect($this->url->link('report/sale_summary/sale_summary', 'token=' . $this->session->data['token'], 'SSL'));
				}
                                                        if($user_info['user_group']=='Customer_care')
				{
					$this->response->redirect($this->url->link('ccare/ccare', 'token=' . $this->session->data['token'], 'SSL'));
				}
				if($user_info['user_group']=='BCML Reports')
				{
					$this->response->redirect($this->url->link('reportbcml/sale_summary/sale_summary', 'token=' . $this->session->data['token'], 'SSL'));
				}
				if($user_info['user_group']=='ISEC Admin')
				{
					$this->response->redirect($this->url->link('isec/purchase_order', 'token=' . $this->session->data['token'], 'SSL'));
				}
				if($user_info['user_group_id']=='11')
				{
					$this->response->redirect($this->url->link('tagpos/tagpos', 'token=' . $this->session->data['token'], 'SSL'));
				}
				if($user_info['user_group_id']=='1'&& !isset($_GET['a']) )
				{
					$this->response->redirect($this->url->link('report/sale_summary/sale_summary', 'token=' . $this->session->data['token'], 'SSL'));
				}

				if($user_info['user_group_id']=='13')
				{
					$this->response->redirect($this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'], 'SSL'));
				}

				if($user_info['user_group_id']=='37')  
				{
					$this->response->redirect($this->url->link('report/sale_summary/sale_summary', 'token=' . $this->session->data['token'], 'SSL'));
				}
				if($user_info['user_group_id']=='34')  
				{
					$this->response->redirect($this->url->link('farmerrequest/cardstatus', 'token=' . $this->session->data['token'], 'SSL'));
				}
				if($user_info['user_group']=='Runner')
				{
					//$data['error_warning'] = 'Please login into the Mobile app using the same username & password.';
					//$data['error_warning'] = 'Please login into the Mobile app using the same username & password.';
                                                                      //$this->response->redirect($this->url->link('common/login', 'SSL'));
                                                                       //$data['header'] = $this->load->controller('common/header');
				               //$data['footer'] = $this->load->controller('common/footer');
					//$this->response->setOutput($this->load->view('common/login.tpl', $data));
					//exit;
					$this->response->redirect($this->url->link('report/sale_summary/sale_summary', 'token=' . $this->session->data['token'], 'SSL'));
					
				}
				if($user_info['user_group_id']=='32')  
				{
					$this->response->redirect($this->url->link('report/sale_summary/sale_summary', 'token=' . $this->session->data['token'], 'SSL'));
				}


			}



		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_map'] = $this->language->get('text_map');
		$data['text_activity'] = $this->language->get('text_activity');
		$data['text_recent'] = $this->language->get('text_recent');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		$data['token'] = $this->session->data['token'];
		
		
		
		if(empty($this->request->get['start_date']))
		{
			$this->request->get['start_date']=date('Y-m').'-01';
		}
		
		
		if(!empty($this->request->get['end_date']))
		{
			$this->request->get['end_date']=date('Y-m-d',strtotime($this->request->get['end_date'] . "+1 days"));
		}
		else
		{
			$this->request->get['end_date']=date('Y-m-d',strtotime(date('Y-m-d')."+1 days"));//date('Y-m-d');
		}
		//echo date('Y',strtotime($this->request->get['start_date']));
		if(date('Y',strtotime($this->request->get['start_date']))!='2016')
		{
			$datediff = strtotime($this->request->get['end_date']) - strtotime($this->request->get['start_date']);

			$btwndays=round($datediff / (60 * 60 * 24));
			if($btwndays>365)
			{
				$this->request->get['end_date']=date('Y-m-d',strtotime($this->request->get['start_date'] . "+365 days"));
			}
		}
		$data['start_date']=$this->request->get['start_date'];
		$data['end_date']=date('Y-m-d',strtotime($this->request->get['end_date'] . "-1 days"));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['order'] = $this->load->controller('dashboard/order');
		$data['sale'] = $this->load->controller('dashboard/sale');
		$data['customer'] = $this->load->controller('dashboard/customer');
		
		//$data['online'] = $this->load->controller('dashboard/online');
		//$data['map'] = $this->load->controller('dashboard/map');
		
		$data['chart'] = $this->load->controller('dashboard/chart');
		$data['activity'] = $this->load->controller('dashboard/activity');
		$data['recent'] = $this->load->controller('dashboard/recent');
		$data['footer'] = $this->load->controller('common/footer');
		$data['group'] = $this->user->getGroupId();
		// Run currency update
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->refresh();
		}
			
		$this->response->setOutput($this->load->view('common/dashboard.tpl', $data));
	}
}
