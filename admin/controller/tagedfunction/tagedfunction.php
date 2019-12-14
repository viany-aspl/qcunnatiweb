<?php
class ControllerTagedfunctionTagedfunction extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('Tagged Function');

		$this->load->model('tagedfunction/tagedfunction');

		$this->getList();
	}
       	public function add() {
		
		$this->document->setTitle("Add Tagged Function");

		$this->load->model('tagedfunction/tagedfunction');

		
		$this->getform();
	}



	protected function getList() {
		$url = '';

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
			'text' => 'Tagged Function List',
			'href' => $this->url->link('tagedfunction/tagedfunction', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['tagged'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$order_total= $this->model_tagedfunction_tagedfunction->getTotaltagedfunction($filter_data);

		$results = $this->model_tagedfunction_tagedfunction->gettagedfunction($filter_data);

		foreach ($results as $result) {
			$data['tagged'][] = array(
				'funname' => $result['funname'],
				'functypeid'     => $result['functypeid'],
                                'companyid' => $result['companyid']
				
			);
		}

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
		$data['redirect']=$this->url->link('tagedfunction/tagedfunction/add', 'token=' . $this->session->data['token']);
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tagedfunction/tagedfunction', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tagedfunction/tagedfunction_list.tpl', $data));
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
			'text' => 'Tagged Function Add',
			'href' => $this->url->link('tagedfunction/tagedfunction', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
	        $data['cancel']=$this->url->link('tagedfunction/tagedfunction', 'token=' . $this->session->data['token'], 'SSL');
                if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                    if($this->request->post['f_name'] !="")
                    {
			$category_id = $this->model_tagedfunction_tagedfunction->addtaggedfunction($this->request->post);

			$this->load->model('tagedfunction/tagedfunction');			

			$this->session->data['success'] ="Tagged Function Added Sucessfully !";

			$this->response->redirect($this->url->link('tagedfunction/tagedfunction', 'token=' . $this->session->data['token'], 'SSL'));
		        
                        
                    }
                }


		$data['unit'] = array();

		/*$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);
*/           
		$store_total = $this->model_tagedfunction_tagedfunction->gettagedfunction();

		$results = $this->model_tagedfunction_tagedfunction->getTotaltagedfunction();

		foreach ($results as $result) {
			$data['unit'][] = array(
				'unit_id' => $result['unit_id'],
				'unit_name'     => $result['unit_name'],
				'url'      => $result['url'],
				'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
                $data['company'] = $this->model_tagedfunction_tagedfunction->getcompany();
                $data['functype'] = $this->model_tagedfunction_tagedfunction->getfunctype();
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

		$this->response->setOutput($this->load->view('tagedfunction/tagedfunction_add.tpl', $data));
	}
      
        public function getstorebyunit()
        {
            
            $unit_id=$this->request->get['unitid'];
            $this->load->model('tagedfunction/tagedfunction');
            $result = $this->model_tagedfunction_tagedfunction->getstorebyunitid($unit_id);
           // print_r($result);
              $store= count($result);
                echo ' <option value=""> Select Store</option> ';
                for($n=0;$n<$store;$n++)
                { //echo $n;
                     echo '<option value="'.$result[$n]['store_id'].'">'.$result[$n]['name'].'</option>';
                }

        } 
}