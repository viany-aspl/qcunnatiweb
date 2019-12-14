<?php
class ControllerReportbcmlProductSummarySubsidy extends Controller {

public function index() {
	    $this->load->language('report/prosumsub');
        $this->document->setTitle('Product Summary Subsidy');
		
		
		
	if (isset($this->request->get['filter_category'])) {
			$filter_category = $this->request->get['filter_category'];
		} else {
			$filter_category = '';
		}
        if (isset($this->request->get['filter_start_date'])) {
	    $filter_start_date = $this->request->get['filter_start_date'];
		} else {
			$filter_start_date = '';//date('Y-m-d');
		}

		if (isset($this->request->get['filter_end_date'])) {
			$filter_end_date = $this->request->get['filter_end_date'];
		} else {
			$filter_end_date = '';//date('Y-m-d');
		}

		if (isset($this->request->get['filter_store'])) {
		        $filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
 
         $url = '';
		 
		 if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}

		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}
                           if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
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
			'text' => 'Product Summary Subsidy',
			'href' => $this->url->link('reportbcml/productsummarysubsidy', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$this->load->model('report/product_summary_subsidy');
		
		$data['category'] = array();

		$filter_data = array(
			'filter_category'      => $filter_category,
             'filter_store'      => $filter_store,
			'filter_start_date'	   => $filter_start_date,
			'filter_end_date'  => $filter_end_date,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);
		
		
		$totalsubsidy = $this->model_report_product_summary_subsidy->getTotalsubsidy($filter_data);
		
		$data['subsidies'] = array();
	    $results= $this->model_report_product_summary_subsidy->getproductsummarysubsidy($filter_data);
		
		foreach ($results as $result) { 
                $data['subsidies'][] = array(
					'quantity'       => $result['quantity'],
					'order_id'       => $result['order_id'],
					'name'    	 	   => $result['name'],
					'price'    		   => $result['price'],
					'BCMLCODE'   		   => $result['BCMLCODE'],
					'SubSidyPer'		   => $result['SubSidyPer'],
					'store_name'		   => $result['store_name'],
					
				);
					   
					   
		}
		//print_r($data['subsidies']);

         if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . urlencode(html_entity_decode($this->request->get['filter_store'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}

		if (isset($this->filter_end_date->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		        
 		 
		$data['categories']=$this->model_report_product_summary_subsidy->getcategoryprosubsidy();
		
        $this->load->model('setting/store');
	    $data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
		
		$pagination = new Pagination();
		$pagination->total = $totalsubsidy;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/productsummarysubsidy', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($totalsubsidy) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($totalsubsidy - $this->config->get('config_limit_admin'))) ? $totalsubsidy : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $totalsubsidy, ceil($totalsubsidy / $this->config->get('config_limit_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_potential'] = $filter_date_potential;
        $data['filter_store'] = $filter_store;
		$data['sort'] = $sort;
		$data['order'] = $order;
		
	    $data['token'] = $this->session->data['token'];
		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/product_summary_subsidy_list.tpl', $data));
	}


  

}