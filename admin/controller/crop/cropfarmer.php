<?php
class ControllerCropCropfarmer extends Controller {

	public function index() {
		$this->load->language('catalog/product');

		$this->document->setTitle("Crop Farmer List");

		$this->load->model('crop/cropfarmer');

		$this->getList();
	}

	
	protected function getList() {
		if (isset($this->request->get['crop_id'])) {
			$filter_name = $this->request->get['crop_id'];
		} else {
			$filter_name = null;
		}
                if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m').'-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d'); 
		}
		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;

		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['crop_id'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['crop_id'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($data['filter_date_start'])) {
			$url .= '&filter_date_start=' . $data['filter_date_start'];
		}

		if (isset($data['filter_date_end'])) {
			$url .= '&filter_date_end=' . $data['filter_date_end'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('crop/cropfarmer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['customers'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('crop/cropfarmer');

		$banklisttotal = $this->model_crop_cropfarmer->getTotalbankdata($filter_data);
             
		$results = $this->model_crop_cropfarmer->getcropfarmerdata($filter_data);
                  	$data['finame'] = $this->model_crop_cropfarmer->getcropname();
		//print_r($results);
  		foreach ($results as $result) {
			$data['customers'][] = array(
                          
				'name' => $result['firstname']." ".$result['lastname'],			
				'telephone'       => $result['telephone'],
                                'crop1'       => $result['crop1'],
                                'acre1'       => $result['acre1'],
                                'crop2'       => $result['crop2'],
				'acre2'      => $result['acre2'],
				'date_added'      => $result['date_added'],			
                                'aadhar'      => $result['aadhar']
                          
                            
			);
		  }

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		
		$data['button_filter'] = $this->language->get('button_filter');

		$data['config_tax_included'] = $this->config->get('config_tax_included');

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

		

		$pagination = new Pagination();
		$pagination->total = $banklisttotal;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('crop/cropfarmer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($banklisttotal) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($banklisttotal - $this->config->get('config_limit_admin'))) ? $banklisttotal : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $banklisttotal, ceil($banklisttotal / $this->config->get('config_limit_admin')));

		//$data['filter_name'] = $this->model_bank_bankdtl->getbankname();
		$data['filter_model'] = $filter_model;
		$data['filter_price'] = $filter_price;
		$data['filter_quantity'] = $filter_quantity;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('crop/crop_farmer.tpl', $data));
	}

	

	
	
}