<?php

class POS{
    public $api_id;
    public $api_name;
    public $api_store_id;
    public $api_group_id;
    public $api_cash;
    public $api_card;		
    public $success;	
	public $error;
    }
class ControllermposLogin extends Controller {
	public function index() {
		$this->load->language('api/login');

		// Delete old login so not to cause any issues if there is an error
		unset($this->session->data['api_id']);
		 $mcrypt=new MCrypt();
		$keys = array(
			'username',
			'password'
		);

$data=new POS();
foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }


$log=new Log("login.log");
$log->write($this->request->post);
		$json = array();

		$this->load->model('account/api');

		$api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['password']);



			if ($api_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $api_info['user_id'],
					'name'        => $api_info['firstname'] . ' ' . $api_info['lastname']
				);

				$this->model_account_activity->addActivity('login', $activity_data);
			}
$log->write($api_info);
		if ($api_info) {
$log->write("in");
			$data->api_id = $mcrypt->encrypt($api_info['user_id']);
$log->write("in");
                         $data->api_name =$mcrypt->encrypt($api_info['firstname']." ".$api_info['lastname']); 
$log->write("in");
                         $data->api_store_id =$mcrypt->encrypt($api_info['store_id']);
                         $data->api_group_id =$mcrypt->encrypt($api_info['user_group_id']);
                         $data->api_cash=$mcrypt->encrypt($api_info['cash']);
                         $data->api_card=$mcrypt->encrypt($api_info['card']);
			
                        			$data->success = $this->language->get('text_success');
                        			$data->error="0";
$log->write("out");
			
		} else {
			$data->error=$this->language->get('error_login');
			$json['error'] = $this->language->get('error_login');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
}