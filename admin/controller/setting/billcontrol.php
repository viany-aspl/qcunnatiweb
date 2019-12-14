<?php
class ControllerSettingBillcontrol extends Controller {
    private $error = array();

    public function index() {
        $this->document->setTitle("Bill Controls");

        $this->load->model('setting/setting');
       $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
	$data['token']=$this->session->data['token'];
	
	$data['currentstatus']=$this->model_setting_setting->getBillingStatus('billing');
        $this->response->setOutput($this->load->view('setting/bill_control_form.tpl', $data));
    }
public function updatestatus() {
        
        	$this->load->model('setting/setting');
      	$this->request->get['currentstatus'];
	echo $this->model_setting_setting->updateBillingStatus('billing',$this->request->get['currentstatus']);
    }
      
       
}