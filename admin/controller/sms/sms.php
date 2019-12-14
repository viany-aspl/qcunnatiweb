<?php


class Controllersmssms extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sms/smsuser');
		$this->document->setTitle($this->language->get('User'));
		$this->load->model('sms/sms');
		$this->getform();
	}
        
         public function getform() {
		//$url = '';
                $data['action'] = $this->url->link('sms/sms/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
                $data['cancel'] = $this->url->link('sms/smslist', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => 'Add SMS',
			'href' => $this->url->link('sms/sms', 'token=' . $this->session->data['token'], 'SSL')
		);
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
                
                
                
		$data['token'] = $this->session->data['token'];
		$data['heading_title'] = $this->language->get('heading_title');
                $data['text_form'] = $this->language->get('text_form');
                $data['entry_username'] = $this->language->get('entry_username');
                $data['entry_password'] = $this->language->get('entry_password');
                $data['h_name'] = $this->language->get('h_name');
                $data['d_name'] = $this->language->get('d_name');
                $data['operator'] = $this->language->get('operator');
                $data['q_type'] = $this->language->get('q_type');
                $data['entry_name'] = $this->language->get('entry_name');
                $data['entry_value'] = $this->language->get('entry_value');
                $data['entry_phone'] = $this->language->get('entry_phone');
                
                
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                
		$this->response->setOutput($this->load->view('sms/sms_form.tpl', $data));
	}
        
      public function add() {
       //print_r($this->request->post);
        $this->load->model('sms/sms');
        $json = array();
        $this->load->model('sms/sms');
        if(isset($this->request->post['username']) && !is_null($this->request->post['name'])){
        $data=$this->model_sms_sms->AddSMS($this->request->post);
        if($data==1)
        {
        $this->session->data['success'] ='User Registered Successfully';
        }
        else if($data==2){
        $this->session->data['error'] ='Sorry ! Some Error Occured';   
        } 
        $this->response->redirect($this->url->link('sms/sms','token=' . $this->session->data['token']));
       }
       //$this->getForm();
	}
	
}
