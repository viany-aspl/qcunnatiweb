<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerAseAse extends Controller {

         public function create_village() {
             
             $this->document->setTitle('Create Village');
             
             
             $this->load->model('ase/ase');
             
             if ($this->request->server['REQUEST_METHOD'] == 'POST')
             { 
                 $this->model_ase_ase->create_village($this->request->post);
                 
                 $this->session->data['success'] = 'Village created Successfully';
                 $this->response->redirect($this->url->link('ase/ase/create_village', 'token=' . $this->session->data['token'] . $url, 'SSL'));
             }
		

		
                
             $this->getForm();
         }
         protected function getForm() {
           
                            $this->load->model('user/user');
                            $this->load->model('setting/store');
                            $data['stores'] = $this->model_setting_store->getStores();

                            $logged_user_data = $this->user->getId();
                
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Create Village',
			'href' => $this->url->link('ase/ase/create_village', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['cancel'] = $this->url->link('ase/ase/create_village', 'token=' . $this->session->data['token'] . $url, 'SSL');
                
                
                           $data['logged_user'] = $logged_user_data;
                           if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
	
                            $data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                            $data['action'] = $this->url->link('ase/ase/create_village', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->response->setOutput($this->load->view('ase/create_village.tpl', $data));
	}
       
}