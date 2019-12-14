<?php
class ControllerB2bpartnerB2bpartner extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('B2B Partner');

		$this->load->model('b2bpartner/b2bpartner');

		$this->getList();
	}
       	public function add() {
		
		$this->document->setTitle("Add B2B Partner");

		$this->load->model('b2bpartner/b2bpartner');

		
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
			'text' => 'B2B Partner List',
			'href' => $this->url->link('b2bpartner/b2bpartner', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['b2b'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$order_total= $this->model_b2bpartner_b2bpartner->getTotalb2bpartner($filter_data);

		$results = $this->model_b2bpartner_b2bpartner->getb2bpartner($filter_data);

		foreach ($results as $result) {
			$data['b2b'][] = array(
				'name' => $result['name'],
				'email'     => $result['email'],
                                'telephone' => $result['telephone'],
                                'pan_card' => $result['pan_card'],
                                'gstn' => $result['gstn'],
                                'address' => $result['address']
				
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
		$data['redirect']=$this->url->link('b2bpartner/b2bpartner/add', 'token=' . $this->session->data['token']);
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('b2bpartner/b2bpartner', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('b2bpartner/b2bpartner_list.tpl', $data));
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
			'text' => 'B2B Partner Add',
			'href' => $this->url->link('b2bpartner/b2bpartner', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
	        $data['cancel']=$this->url->link('b2bpartner/b2bpartner', 'token=' . $this->session->data['token'], 'SSL');
                if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                    if($this->request->post['name'] !="")
                    {
			$category_id = $this->model_b2bpartner_b2bpartner->addb2bpartner($this->request->post);

			$this->load->model('b2bpartner/b2bpartner');			

			$this->session->data['success'] ="B2B Partner Added Sucessfully !";

			$this->response->redirect($this->url->link('b2bpartner/b2bpartner', 'token=' . $this->session->data['token'], 'SSL'));
		        
                        
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
		$store_total = $this->model_b2bpartner_b2bpartner->getb2bpartner();

		$results = $this->model_b2bpartner_b2bpartner->getTotalb2bpartner();

		

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

		$this->response->setOutput($this->load->view('b2bpartner/b2bpartner_add.tpl', $data));
	}
      
        public function getstorebyunit()
        {
            
            $unit_id=$this->request->get['unitid'];
            $this->load->model('b2bpartner/b2bpartner');
            $result = $this->model_b2bpartner_b2bpartner->getstorebyunitid($unit_id);
           // print_r($result);
              $store= count($result);
                echo ' <option value=""> Select Store</option> ';
                for($n=0;$n<$store;$n++)
                { //echo $n;
                     echo '<option value="'.$result[$n]['store_id'].'">'.$result[$n]['name'].'</option>';
                }

        } 
}