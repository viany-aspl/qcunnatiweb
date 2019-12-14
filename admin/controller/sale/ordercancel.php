<?php
class ControllerSaleOrdercancel extends Controller {
	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('Order Cancel');

		$this->getform();
	}
       	
        protected function getform()  
        { 
		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => 'Order Cancel',
			'href' => $this->url->link('sale/ordercancel', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
	    
		$data['heading_title'] = 'Order Cancel';
		
		

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
	        		
		
		$data=array();
		//$this->load->library('user');
		$data['user_id']=$this->user->getId();
		
		$data['token']=$this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/ordercancel.tpl', $data));
	}
	public function search_order() 
	{
		$order_id=$this->request->get['order_id'];
		$this->load->model('sale/ordercancel');
		$data=array();
		$mcrypt=new MCrypt();
		$data=$this->model_sale_ordercancel->getOrder($order_id);
		if(!empty($data))
		{
			$data['order_id_go']=$mcrypt->encrypt($order_id);
		}
		else
		{
			$data['order_id_go']='0';
		}
		$data['products']=$this->model_sale_ordercancel->getOrderProducts($data['order_id']);
		//print_r($data);
		$this->response->setOutput(json_encode($data));
	}
	public function cancel_order() 
	{
		$mcrypt=new MCrypt();
		$order_id=$mcrypt->decrypt($this->request->get['order_id']);
		$this->load->model('sale/ordercancel');
		$data['user_id']=$this->user->getId();
		echo $data=$this->model_sale_ordercancel->cancelOrder($order_id,$data['user_id']);
		exit;
		//$this->response->setOutput(json_encode($data));
	}
}