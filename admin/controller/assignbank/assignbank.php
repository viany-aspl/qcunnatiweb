<?php
class ControllerAssignbankAssignbank extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('Bank List');

		$this->load->model('assignbank/assignbank');

		$this->getList();
	}
       	public function add() {
		
		$this->document->setTitle("Add Bank");

		$this->load->model('assignbank/assignbank');

		
		$this->getform();
	}
       
	public function edit() {
		$this->load->language('catalog/option');

		$this->document->setTitle('Assign Bank');

		$this->load->model('assignbank/assignbank');

		     if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                  
                    if($this->request->post['id']!="")
                    { 		//print_r($this->request->post);exit;
			$unit_id = $this->model_assignbank_assignbank->UpdateAssignBank($this->request->post);

						

			$this->session->data['success'] ="Assign Bank Updated Sucessfully !";

			$this->response->redirect($this->url->link('assignbank/assignbank', 'token=' . $this->session->data['token'], 'SSL'));
		        
                        
                    }
                }

		$this->editForm();
	}
       protected function editForm() {
		$data['heading_title'] = 'Assign Bank Update';
	        $data['companies'] = $this->model_assignbank_assignbank->getCompanies();
           
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
			'href' => $this->url->link('assignbank/assignbank', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('assignbank/assignbank/edit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('assignbank/assignbank', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . '&filter_company=' . $this->request->get['id']. $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('assignbank/assignbank', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['sid']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$option_info = $this->model_assignbank_assignbank->getUnit($this->request->get['sid']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		
		if (isset($this->request->get['id'])) {
			$Bankdata = $this->model_assignbank_assignbank->getAssignbankValue(array('id'=>$this->request->get['id']));
		} 
		$this->load->model('tool/image');

		$data['Bank_values'] = array();

		foreach ($Bankdata as $Bankv) { //print_r($Bankv);
			
			$data['Bank_values'][] = array(
				'bank_id'   => $Bankv['bank_id'],
				'bank_name' => $Bankv['bank'],
				'bank_account_number' => $Bankv['bank_account_number'],
				'bank_account_type' => $Bankv['bank_account_type'],
				'bank_ifsc_code' => $Bankv['bank_ifsc_code'],
				'bank_branch' => $Bankv['bank_branch'],
				'bank_account_name'=>$Bankv['bank_account_name'],
				'IsActive'=>$Bankv['IsActive']
			);
		}
                	$data['filter_companies']=$this->model_assignbank_assignbank->getAssigncompanies($this->request->get['id']);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		//print_r($data['filter_companies']);
		$this->response->setOutput($this->load->view('assignbank/assignbank_form.tpl', $data));
	}

	protected function getList() {
		$url = '';
		if (isset($this->request->get['filter_bank'])) {
			$url .= '&filter_bank=' . $this->request->get['filter_bank'];
		}
		if (isset($this->request->get['filter_bank'])) {
			$data['filter_bank']=$filter_bank=$this->request->get['filter_bank'];
		}
		else
		{
			$filter_bank='';
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
			'text' => 'Assign Bank List',
			'href' => $this->url->link('assignbank/assignbank', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['bank'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin'),
			'filter_bank' => $filter_bank
		);
		
		$order_total= $this->model_assignbank_assignbank->getTotalBank($filter_data);

		$results = $this->model_assignbank_assignbank->getBank($filter_data);
		
		foreach ($results as $result) {//print_r($result);
			$data['bank'][] = array(
                                'bank_id' => $result['bank_id'],				
				'bank'     => $result['bank'],
                                'edit'       => $this->url->link('assignbank/assignbank/edit', 'token=' . $this->session->data['token'] . '&id=' . $result['bank_id'] . $url, 'SSL'),
				
			);
		}
		//$data['bankdropdown'] = $this->model_assignbank_assignbank->getBank();
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
		$data['redirect']=$this->url->link('assignbank/assignbank/add', 'token=' . $this->session->data['token']);
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('assignbank/assignbank', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['filter_bank']=$filter_bank;
		$this->response->setOutput($this->load->view('assignbank/assignbank_list.tpl', $data));
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
			'text' => 'Assign Bank Add',
			'href' => $this->url->link('assignbank/assignbank', 'token=' . $this->session->data['token'], 'SSL')
		);
		$this->load->model('assignbank/assignbank');
		$data['token'] = $this->session->data['token'];
	        	$data['cancel']=$this->url->link('assignbank/assignbank', 'token=' . $this->session->data['token'], 'SSL');
               	 if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                    if($this->request->post['bank_name'] !="")
                    {
			$category_id = $this->model_assignbank_assignbank->addBank($this->request->post);

						

			$this->session->data['success'] ="Assign Bank Added Sucessfully !";

			$this->response->redirect($this->url->link('assignbank/assignbank', 'token=' . $this->session->data['token'], 'SSL'));
		        
                        
                    }
                }


		$data['unit'] = array();

		
		$data['companies'] = $this->model_assignbank_assignbank->getCompanies();


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

		$this->response->setOutput($this->load->view('assignbank/assignbank_add.tpl', $data));
	}
public function getstorebyunit()
{

$unit_id=$this->request->get['unitid'];
$this->load->model('assignbank/assignbank');
$result = $this->model_assignbank_assignbank->getstorebyunitid($unit_id);
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
$this->load->model('assignbank/assignbank');
$result = $this->model_assignbank_assignbank->getunitsbycompany($company_id);
// print_r($result);
$store= count($result);
echo ' <option value=""> Select Units</option> ';
for($n=0;$n<$store;$n++)
{ //echo $n;
echo '<option value="'.$result[$n]['unit_id'].'">'.$result[$n]['unit_name'].'</option>';
}

}

}