<?php


class Controllersmssmslist extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sms/smsuser');

		$this->document->setTitle($this->language->get('User'));

		$this->load->model('sms/sms');

		$this->getList();
	}
        
         public function getform() {
		//$url = '';
                
                $data['action'] = $this->url->link('sms/smslist/getlist', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['breadcrumbs'] = array();

		

		$data['breadcrumbs'][] = array(
			'text' => 'Company List',
			'href' => $this->url->link('sms/smslist', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		

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
		//$data['redirect']=$this->url->link('testuser/testuser', 'token=' . $this->session->data['token']);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		//$this->response->setOutput($this->load->view('sms/sms_list.tpl', $data));
	}
        
        
        	protected function getList() {
                 if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}   
                    
		$url = '';

		

		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sms/smslist', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('sms/sms', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('sms/smslist/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

                
                $filter_data = array(
			
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);
                
                
		$data['customers'] = array();
		$data['heading_title'] = $this->language->get('heading_title');
                $data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_list'] = $this->language->get('text_list');
		$data['column_name'] = $this->language->get('column_name');
                
                $data['tsms'] = $this->language->get('tsms');
                 $data['entry_mobile'] = $this->language->get('entry_mobile');
                  $data['entry_message'] = $this->language->get('entry_message');
                
		$data['column_operator'] = $this->language->get('column_operator');
		$data['qtype'] = $this->language->get('qtype');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_login'] = $this->language->get('button_login');
		$data['button_unlock'] = $this->language->get('button_unlock');
		$data['token'] = $this->session->data['token'];
                    
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
                
                if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$url = '';
                
                
                 $this->load->model('sms/sms');
		$data['getsms'] = $this->model_sms_sms->GETSMS($filter_data);
                
                
                
                $customer_total = $this->model_sms_sms->getTotalSms($filter_data);
                
		$pagination = new Pagination();
		$pagination->total =$customer_total;
		$pagination->page = ($page - 1) * $this->config->get('config_limit_admin');
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sms/smslist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
                
		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));
                
                
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sms/sms_list.tpl', $data));
	}
        
        
        public function updatestatus(){
         
           //echo $this->request->get['sid'];
            
            
            if ($this->request->get['sid'] != '')
            {
                $this->request->get['sid']=$this->request->get['sid'];
                $this->load->model('sms/sms');
                $updateBill = $this->model_sms_sms->statusupdate($this->request->get['sid']);
                
                if($updateBill)
                {
                $this->session->data['success'] = 'Status Updated Successfully';
                
                }
                else 
                {
                  $this->session->data['error_warning'] = "Some Error Occured";
                 
                }
            }
            else
            {
                $this->session->data['error_warning'] = "Please Select the service to change the status";
                
                
            }
            
        }
        
        
        protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sms/smslist')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
        public function delete() {
		$this->load->language('sms/smsuser');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sms/sms');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $SID) {
				$this->model_sms_sms->deleteCustomer($SID);
			}

			$this->session->data['success'] = $this->language->get('text_delete');

			$url = '';

			$this->response->redirect($this->url->link('sms/smslist', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		   }

		$this->getList();
               
	}
        
        
        
         public function smsdetails() {
            
             $mobile=$this->request->post['mobile_number'];
             $message=$this->request->post['message'];
             $this->request->post['sid'];
             $this->load->model('sms/sms');
             $operator_info= $this->model_sms_sms->selectdetails($this->request->post);
             
            function array_flatten($array) {
                if (!is_array($array)) {
                return FALSE;
                }
                $result = array();
                foreach ($array as $key => $value) {
                if (is_array($value)) {
                $result = array_merge($result, array_flatten($value));
                }
                else {
                $result[$key] = $value;
                }
                }
                return $result;
            }//$response=array_flatten($api_info);
              //print_r($operator_info);
             $api_info=unserialize($operator_info['SEND_PARAMETER']);
            
             $mcount=0;
             foreach($api_info as $api){
                 $key=array_search('mnumber', $api);
                 if(!empty($key)){
                     
                    unset($api[$key]);
                    unset($api_info[$mcount]);
                    $api[$key]=$mobile;                
                    $api_info[]=$api;
                 }
                 $mcount++;
             }
             
             $rcount=0;
             foreach($api_info as $api){
                 $key=array_search('message', $api);
                if(!empty($key)){                                             
                    unset($api[$key]);
                    unset($api_info[$rcount+1]);
                    $api[$key]=$message;                
                    $api_info[]=$api;
                 }
                 $rcount++;
             }
             $response=array_flatten($api_info);
             $post_url=http_build_query($response);
             $curl = curl_init();
              // Set SSL if required
                if (substr(trim($operator_info['HOSTNAME']), 0, 5) == 'https') {
                    curl_setopt($curl, CURLOPT_PORT, 443);
                }
	        
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, trim($operator_info['QUERY_TYPE']));
                curl_setopt($curl, CURLOPT_USERAGENT, "unnati");
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                if(trim($operator_info['QUERY_TYPE'])=='GET'){
                    curl_setopt($curl, CURLOPT_URL, trim($operator_info['HOSTNAME'])."?".$post_url);
                }else{
		    curl_setopt($curl, CURLOPT_URL, trim($operator_info['HOSTNAME']));
	        }
                curl_setopt($curl, CURLOPT_POST, true);
                
                curl_setopt($curl, CURLOPT_POSTFIELDS,$post_url);
		           
				$json=0;
				if(!empty($mobile))
				{
                                  echo $json = curl_exec($curl);
                                }
          
         }

}