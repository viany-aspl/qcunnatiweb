<?php
class ControllerSecuiritySecuirity extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('Security Deposit');

		$this->load->model('secuirity/secuirity');

		$this->getList();
	}
       	public function add() {
		
		$this->document->setTitle("Security Deposit Add");

		$this->load->model('secuirity/secuirity');

		
		$this->getform();
	}
       
	
	protected function getList() {
		$url = '';
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_store'])) {
			$data['filter_store']=$filter_store=$this->request->get['filter_store'];
		}
		else
		{
			$filter_store='';
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['page'])) {
			$page=$this->request->get['page'];
		}
		else
		{
			$page=1;
		}
		$data['breadcrumbs'] = array();

		

		$data['breadcrumbs'][] = array(
			'text' => 'Security Deposit List',
			'href' => $this->url->link('secuirity/secuirity', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['secuirity'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin'),
                        'filter_store' => $filter_store
			
		);
		
		$order_total= $this->model_secuirity_secuirity->getTotalsecuirity($filter_data);

		$results = $this->model_secuirity_secuirity->getsecuirity($filter_data);
		
		foreach ($results as $result) {
			$data['secuirity'][] = array(
                            'store_name' => $result['store_name'],
				'bank_name' => $result['bank_name'],
				'ifsc_code'     => $result['ifsc_code'],
                                'checkno'       => $result['check_no'],
                            'amount'       => $result['amount'],
                            'chequeissuedate'       => $result['cheque_issue_date'],
		'remarks'       => $result['remarks']
				
			);
		}
		//$data['companies'] = $this->model_secuirity_secuirity->getCompanies();
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = 'No Security Deposit Data';
		

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		

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
                $data['stores'] = $this->model_secuirity_secuirity->getStores();
		$data['redirect']=$this->url->link('secuirity/secuirity/add', 'token=' . $this->session->data['token']);
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('secuirity/secuirity', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('secuirity/secuirity_list.tpl', $data));
	}
        protected function getform() {
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
			'text' => 'Security Deposit Add',
			'href' => $this->url->link('secuirity/secuirity', 'token=' . $this->session->data['token'], 'SSL')
		);
		$this->load->model('unit/unit');
		$data['token'] = $this->session->data['token'];
	        $data['cancel']=$this->url->link('secuirity/secuirity', 'token=' . $this->session->data['token'], 'SSL');
               	 if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                    if($this->request->post['filter_store'] !="" && $this->request->post['filter_bank'] !=""  && $this->request->post['chequeno'] !="" && $this->request->post['amount'] !="" && $this->request->post['dateadded'] !="")
                    {
			$category_id = $this->model_secuirity_secuirity->addsecuirity($this->request->post);

						

			$this->session->data['success'] ="Security Deposit Added Sucessfully !";

			$this->response->redirect($this->url->link('secuirity/secuirity', 'token=' . $this->session->data['token'], 'SSL'));
		        
                        
                    }
                }


		$data['unit'] = array();

		
		$data['stores'] = $this->model_secuirity_secuirity->getStores();
                $data['banks'] = $this->model_secuirity_secuirity->getBankList();

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

		$this->response->setOutput($this->load->view('secuirity/secuirity_add.tpl', $data));
	}



}