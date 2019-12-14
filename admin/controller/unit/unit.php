<?php
class ControllerUnitUnit extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('Factory Units');

		$this->load->model('unit/unit');

		$this->getList();
	}
       	public function add() {
		
		$this->document->setTitle("Add Factory Unit");

		$this->load->model('unit/unit');

		
		$this->getform();
	}



	protected function getList() {
		$url = '';
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . $this->request->get['filter_company'];
		}
		if (isset($this->request->get['filter_company'])) {
			$data['filter_company']=$filter_company=$this->request->get['filter_company'];
		}
		else
		{
			$filter_company='';
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
			'text' => 'Unit List',
			'href' => $this->url->link('unit/unit', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['unit'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin'),
			'filter_company' => $filter_company
		);
		if($filter_company!="")
		{
		$order_total= $this->model_unit_unit->getTotalunit($filter_data);

		$results = $this->model_unit_unit->getunit($filter_data);
		}
		foreach ($results as $result) {
			$data['unit'][] = array(
				'unit_id' => $result['unit_id'],
				'unit_name'     => $result['unit_name'],
				'edit'=>$this->url->link('unit/unit/edit', 'token=' . $this->session->data['token'].'&id='.$result['sid'])
				
			);
		}
		$data['companies'] = $this->model_unit_unit->getCompanies();
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = 'Please Select Company';
		

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
		$data['redirect']=$this->url->link('unit/unit/add', 'token=' . $this->session->data['token']);
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('unit/unit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('unit/unit_list.tpl', $data));
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
			'href' => $this->url->link('unit/unit', 'token=' . $this->session->data['token'], 'SSL')
		);
		$this->load->model('unit/unit');
		$data['token'] = $this->session->data['token'];
	        	$data['cancel']=$this->url->link('unit/unit', 'token=' . $this->session->data['token'], 'SSL');
               	 if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                    if($this->request->post['unit_name'] !="")
                    {
			$category_id = $this->model_unit_unit->addunit($this->request->post);

						

			$this->session->data['success'] ="Unit Added Sucessfully !";

			$this->response->redirect($this->url->link('unit/unit', 'token=' . $this->session->data['token'], 'SSL'));
		        
                        
                    }
                }


		$data['unit'] = array();

		
		$data['companies'] = $this->model_unit_unit->getCompanies();


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

		$this->response->setOutput($this->load->view('unit/unit_add.tpl', $data));
	}
public function getstorebyunit()
{

$unit_id=$this->request->get['unitid'];
$this->load->model('unit/unit');
$result = $this->model_unit_unit->getstorebyunitid($unit_id);
// print_r($result);
$store= count($result);
echo ' <option value=""> Select Store</option> ';
for($n=0;$n<$store;$n++)
{ //echo $n;
echo '<option value="'.$result[$n]['store_id'].'">'.$result[$n]['name'].'</option>';
}

}

public function getunitsbycompany()
{

$company_id=$this->request->get['company_id'];
$this->load->model('unit/unit');
$result = $this->model_unit_unit->getunitsbycompany($company_id);
// print_r($result);
$store= count($result);
echo ' <option value=""> Select Units</option> ';
for($n=0;$n<$store;$n++)
{ //echo $n;
echo '<option value="'.$result[$n]['unit_id'].'">'.$result[$n]['unit_name'].'</option>';
}

}
public function edit() {
$this->load->language('catalog/option');

$this->document->setTitle('Update Unit');

$this->load->model('unit/unit');

if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {

if($this->request->post['id']!="")
{
$unit_id = $this->model_unit_unit->UpdateUnit($this->request->post);

$this->session->data['success'] ="Unit Updated Sucessfully !";

$this->response->redirect($this->url->link('unit/unit', 'token=' . $this->session->data['token'], 'SSL'));


}
}

$this->editForm();
}
protected function editForm() {
$data['heading_title'] = 'Update Unit';
$data['companies'] = $this->model_unit_unit->getCompanies();

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
'href' => $this->url->link('unit/unit', 'token=' . $this->session->data['token'] . $url, 'SSL')
);

if (!isset($this->request->get['id'])) {
$data['action'] = $this->url->link('unit/unit/edit', 'token=' . $this->session->data['token'] . $url, 'SSL');
} else {
$data['action'] = $this->url->link('unit/unit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . '&filter_company=' . $this->request->get['id']. $url, 'SSL');
}

$data['cancel'] = $this->url->link('unit/unit', 'token=' . $this->session->data['token'] . $url, 'SSL');

if (isset($this->request->get['sid']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
$option_info = $this->model_unit_unit->getUnit($this->request->get['sid']);
}

$data['token'] = $this->session->data['token'];

if (isset($this->request->get['id'])) {
$unit_values = $this->model_unit_unit->getUnitValue($this->request->get['id']);
}

$data['unit_values'] = array();

foreach ($unit_values as $unit_value) { //print_r($unit_value);

$data['unit_values'][] = array(
'unit_id' => $unit_value['unit_id'],
'unit_name' => $unit_value['unit_name'],
'company_id'=> $unit_value['company_id'],
'sid'=> $unit_value['sid']
);
}
$data['filter_company']=$data['unit_values'][0]['company_id'];
$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');

$this->response->setOutput($this->load->view('unit/unit_form.tpl', $data));
}
}