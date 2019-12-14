<?php
class ControllerCompanyCompany extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('Company');

		$this->load->model('company/company');

		$this->getList();
	}
       	public function add() {
		
		$this->document->setTitle("Add Company");

		$this->load->model('company/company');

		
		$this->getform();
	}



	protected function getList() {
		$url = '';

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
			'text' => 'Company List',
			'href' => $this->url->link('company/company', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['company'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$order_total= $this->model_company_company->getTotalcompany($filter_data);

		$results = $this->model_company_company->getcompany($filter_data);

		foreach ($results as $result) { //print_r($result);
			$data['company'][] = array(
				'company_id' => $result['company_id'],
				'company_name'     => $result['company_name'],
				'edit'=>$this->url->link('company/company/edit', 'token=' . $this->session->data['token'].'&id='.$result['company_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		

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
		$data['redirect']=$this->url->link('company/company/add', 'token=' . $this->session->data['token']);
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('company/company', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('company/company_list.tpl', $data));
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
			'text' => 'Unit Add',
			'href' => $this->url->link('company/company', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
	        $data['cancel']=$this->url->link('company/company', 'token=' . $this->session->data['token'], 'SSL');
                if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                    if($this->request->post['company_name'] !="")
                    {
			$category_id = $this->model_company_company->addcompany($this->request->post);

			$this->load->model('company/company');			

			$this->session->data['success'] ="Company Added Sucessfully !";

			$this->response->redirect($this->url->link('company/company', 'token=' . $this->session->data['token'], 'SSL'));
		        
                        
                    }
                }


		$data['company'] = array();

		/*$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);
*/
		$store_total = $this->model_company_company->getTotalcompany();

		$results = $this->model_company_company->getcompany();

		foreach ($results as $result) {
			$data['company'][] = array(
				'company_id' => $result['company_id'],
				'company_name'     => $result['company_name'],
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

		$this->response->setOutput($this->load->view('company/company_add.tpl', $data));
	}
      public function edit() {
$this->load->language('catalog/option');

$this->document->setTitle('Company Update');

$this->load->model('company/company');

if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
print_r($this->request->post['id']);
if($this->request->post['id']!="")
{
$company_id = $this->model_company_company->UpdateCompany($this->request->post);

$this->session->data['success'] ="Company Updated Sucessfully !";

$this->response->redirect($this->url->link('company/company', 'token=' . $this->session->data['token'], 'SSL'));


}
}

$this->editForm();
}
protected function editForm() {
$data['heading_title'] = 'Company Update';

if (isset($this->error['warning'])) {
$data['error_warning'] = $this->error['warning'];
} else {
$data['error_warning'] = '';
}

if (isset($this->error['name'])) {
$data['error_name'] = $this->error['name'];
} else {
$data['error_name'] = array();
}

if (isset($this->error['option_value'])) {
$data['error_option_value'] = $this->error['option_value'];
} else {
$data['error_option_value'] = array();
}

$url = '';

if (isset($this->request->get['sort'])) {
$url .= '&sort=' . $this->request->get['sort'];
}

if (isset($this->request->get['order'])) {
$url .= '&order=' . $this->request->get['order'];
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
'text' => $this->language->get('heading_title'),
'href' => $this->url->link('company/company', 'token=' . $this->session->data['token'] . $url, 'SSL')
);

if (!isset($this->request->get['id'])) {
$data['action'] = $this->url->link('company/company/edit', 'token=' . $this->session->data['token'] . $url, 'SSL');
} else {
$data['action'] = $this->url->link('company/company', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . '&filter_company=' . $this->request->get['id']. $url, 'SSL');
}

$data['cancel'] = $this->url->link('company/company', 'token=' . $this->session->data['token'] . $url, 'SSL');

if (isset($this->request->get['sid']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
$option_info = $this->model_company_company->getcompany($this->request->get['id']);
}

$data['token'] = $this->session->data['token'];

$this->load->model('localisation/language');

$data['languages'] = $this->model_localisation_language->getLanguages();


if (isset($this->request->get['id'])) {
$company_values = $this->model_company_company->getCompanyValue($this->request->get['id']);
}
$this->load->model('tool/image');

$data['company_values'] = array();

foreach ($company_values as $company_value) { //print_r($company_value);

$data['company_values'][] = array(
'company_id' => $company_value['company_id'],
'company_name'=> $company_value['company_name'],

);
}
$data['filter_company']=$this->request->get['filter_company'];
$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');

$this->response->setOutput($this->load->view('company/company_form.tpl', $data));
}
}