<?php
class ControllerFarmerrequestSendpin extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle("Send Pin");

		$this->load->model('farmerrequest/farmerrequest');
               		
		$this->sendpinForm();
	}
            
	protected function sendpinForm() {
                
		$data['breadcrumbs'] = array();

		

		$data['breadcrumbs'][] = array(
			'text' => 'Send Pin Form',
			'href' => $this->url->link('farmerrequest/reviewform', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['orders'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = 'Please Select Grower Id';
		

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
		
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('farmerrequest/sendpin.tpl', $data));
	}
         public function sendpintomobile()
        {                         
                 
                   $data=array();
                                    
                  $this->load->model('farmerrequest/farmerrequest');
                 
                   //check sql
                  $data11= $this->model_farmerrequest_farmerrequest->getcardmobileno($this->request->get);
                 if(!empty($data11))
                 {
                $data1=$data11[0];
				$data=array(
                      
                      'MOB'=>$data1['MOB'],
                      'CARD_PIN'=>$data1['CARD_PIN']
                      );
                 }
                  $this->response->addHeader('Content-Type: application/json');
                  $this->response->setOutput(json_encode($data));
        } 
        
        
   
}
