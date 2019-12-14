<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
ini_set('max_execution_time', 3000);  //3000 seconds = 50 minutes 

class ControllerGeneratePinPin extends Controller 
{
	public function index() 
	{
            
		//$this->load->language('generatepin/pin');
	      
		$this->document->setTitle('GeneratePin');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['username'])) {
			$username=$data['username'] = $this->request->get['username'];
		} else {
			$username = null;
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Generate Pin',
			'href' => $this->url->link('generatepin/pin', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
		$filter_data=array(
		'username'=>$username,
		'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
			);
		//$this->load->model('setting/store');
               $this->load->model('generatepin/pin');
		
                $data['users'] = $this->model_generatepin_pin->getuserbygroupid($filter_data);
					
		$order_total=$this->model_generatepin_pin->getuserbygroupidTotal($filter_data);
		$data['token'] = $this->session->data['token'];
                
		$url = '';
				
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('generatepin/pin', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
                $data['button_filter'] = $this->language->get('button_filter');
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		
		$data['total_cash'] = $total_cash;
                $data['total_tagged'] = $total_tagged;
                $data['total_subsidy'] = $total_subsidy;   
		$data['total_cash_all'] = $total_cash_all;
                $data['total_tagged_all'] = $total_tagged_all;
                $data['total_subsidy_all'] = $total_subsidy_all;


		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('generatepin/pin_list.tpl', $data));
	}

        public function insertpin()
        {
			//print_r($this->request->post);exit;
        $this->load->model('user/user');
        $pin = $this->model_user_user->generatempin($this->request->post);
        if($pin)
        {
			echo '1';
        $this->session->data['success'] = 'Pin Inserted Successfully';

        }
        else 
        {
			echo '0';
          $this->session->data['error_warning'] = "Oops ! Some error occur.Please try again.";

        }
       }
	
	
}