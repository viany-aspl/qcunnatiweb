<?php
class ControllerCommonTrend extends Controller {
	public function index() {
		$this->load->language('common/dashboard');



		$this->load->model('user/user');
	
			$this->load->model('tool/image');
	

			$user_info = $this->model_user_user->getUser($this->user->getId());
	
			if ($user_info) {
					 if($user_info['user_group']=='Tagged')
						{
							//data
							$this->response->redirect($this->url->link('tag/order', 'token=' . $this->session->data['token'], 'SSL'));
						}
 if($user_info['user_group']=='Unit Office')
{
			$this->response->redirect($this->url->link('report/stock/transfer', 'token=' . $this->session->data['token'], 'SSL'));
}
if($user_info['user_group']=='Territory Manager')
{
			$this->response->redirect($this->url->link('report/sale_summary', 'token=' . $this->session->data['token'], 'SSL'));
}

				}



		$this->document->setTitle('Trend ');

		$data['heading_title'] = 'Trend';

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
		$this->load->model('setting/store');
                            $data['stores'] = array();//$this->model_setting_store->getStores();//print_r($data['stores'] );
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		//$data['order'] = $this->load->controller('dashboard/order');
		//$data['sale'] = $this->load->controller('dashboard/sale');
		//$data['customer'] = $this->load->controller('dashboard/customer');
		//$data['online'] = $this->load->controller('dashboard/online');
		//$data['map'] = $this->load->controller('dashboard/map');
		$data['chart'] = $this->load->controller('trend/chart');
		//$data['store_chart'] = $this->load->controller('trend/storechart');
		//$data['recent'] = $this->load->controller('dashboard/recent');
		$data['footer'] = $this->load->controller('common/footer');
		$data['group'] = $this->user->getGroupId();
		// Run currency update
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->refresh();
		}
			
		$this->response->setOutput($this->load->view('common/trend.tpl', $data));
	}
}
