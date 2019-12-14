<?php
class ControllerCategoryCategory extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('Category');

		$this->load->model('category/category');

		$this->getList();
	}
       	public function add() {
		
		$this->document->setTitle("Category Add");

		$this->load->model('category/category');

		
		$this->getform();
	}



	protected function getList() {
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
			'text' => 'Category List',
			'href' => $this->url->link('category/category', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
	        //if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                   
			$data['redirect']=$this->url->link('category/category/add', 'token=' . $this->session->data['token'], 'SSL');
                       
		//}

                

		$data['category'] = array();

		/*$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);
*/
		$store_total = $this->model_category_category->getTotalCategory();

		$results = $this->model_category_category->getCategory();

		foreach ($results as $result) {
			$data['category'][] = array(
				'category_id' => $result['category_id'],
				'category_name'     => $result['category_name'],
				'url'      => $result['url'],
				'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], 'SSL')
			);
		}

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

		$this->response->setOutput($this->load->view('category/category_list.tpl', $data));
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
			'text' => 'Category Add',
			'href' => $this->url->link('category/category', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
		$data['cancel']=$this->url->link('category/category', 'token=' . $this->session->data['token'], 'SSL');
                if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                    if($this->request->post['category_name'] !="")
                    {
			$category_id = $this->model_category_category->addcategory($this->request->post);

			$this->load->model('category/category');			

			$this->session->data['success'] ="Category Added Sucessfully !";

			$this->response->redirect($this->url->link('category/category', 'token=' . $this->session->data['token'], 'SSL'));
		         
                        
                    }
                }


		$data['category'] = array();

		/*$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);
*/
		$store_total = $this->model_category_category->getTotalCategory();

		$results = $this->model_category_category->getCategory();

		foreach ($results as $result) {
			$data['category'][] = array(
				'category_id' => $result['category_id'],
				'category_name'     => $result['category_name'],
				'url'      => $result['url'],
				'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], 'SSL')
			);
		}

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

		$this->response->setOutput($this->load->view('category/category_add.tpl', $data));
	}
      

}