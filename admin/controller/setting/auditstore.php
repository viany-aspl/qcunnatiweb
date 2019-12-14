<?php
class ControllerSettingAuditstore extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('Stores');

		$this->load->model('setting/store');

		$this->getList();
	}
public function audit() {
		$this->load->language('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		
                $this->load->model('setting/auditstore');
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			//print_r($this->request->post);exit;
                    $this->load->model('user/user');
                
                    $data['logged_user_data'] = $this->user->getId();
                    $this->model_setting_auditstore->editStore($this->request->get['store_id'], $this->request->post,$data['logged_user_data']);

			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('setting/auditstore', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL'));
		}
                
                $getdata=$this->model_setting_auditstore->getcurrentamount($this->request->get['store_id']);
		
                $data["cash"]=$getdata["cash"];
                //$data["card"]=$getdata["card"];
                //$data["firstname"]=$getdata["firstname"];
                //$data["lastname"]=$getdata["lastname"];
                $data["storename"]=$getdata["storename"]; 
                $data["cancel"]=$_SERVER["HTTP_REFERER"];   
                                                
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/auditstoreedit.tpl', $data));
	}


	protected function getList() {
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
			'text' => 'Stores for Audit Amount',
			'href' => $this->url->link('setting/auditstore', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
		

		$data['stores'] = array();

		/*$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);
*/
		$store_total = $this->model_setting_store->getTotalStores();

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], 'SSL')
			);
		}

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

		$this->response->setOutput($this->load->view('setting/store_audit_list.tpl', $data));
	}

	}