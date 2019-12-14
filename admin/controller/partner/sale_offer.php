<?php
	class ControllerPartnerSaleOffer extends Controller {
		//sale chart
		public function index() {
			$this->load->language('purchase/sale_offer');
			
			$this->load->model('catalog/category');
			$data['categories'] = $this->model_catalog_category->getCategories($data = array());
			
			$this->load->model('catalog/option');
			$data['options'] = $this->model_catalog_option->getOptions();
			
			$data['sale_chart_heading_text'] = $this->language->get('sale_chart_heading_text');
			//filter
			$data['all_products_text'] = $this->language->get('all_products_text');
			$data['select_categor_text'] = $this->language->get('select_categor_text');
			$data['select_option_text'] = $this->language->get('select_option_text');
			$data['select_option_value_text'] = $this->language->get('select_option_value_text');
			$data['sold_less_text'] = $this->language->get('sold_less_text');
			
			//column
			$data['column_product_name'] = $this->language->get('column_product_name');
			$data['column_category'] = $this->language->get('column_category');
			$data['column_option_value'] = $this->language->get('column_option_value');
			$data['column_stock'] = $this->language->get('column_stock');
			$data['column_sold'] = $this->language->get('column_sold');
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			$data['token'] = $this->session->data['token'];
			
			$url = '';
			$data['filter'] = $this->url->link('purchase/sale_offer/filter', 'token=' . $this->session->data['token'] . $url, true);
			$data['offer_sale'] = $this->url->link('purchase/sale_offer/offer_sale', 'token=' . $this->session->data['token'] . $url, true);
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('sale_chart_heading_text'),
				'href' => $this->url->link('purchase/sale_offer', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			//for loading all products
			$this->load->model('purchase/sale_offer');
			$data['products'] = $this->model_purchase_sale_offer->allProducts();
			//for loading all products
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('purchase/sale_offer.tpl',$data));
		}
		
		public function filter()
		{
			$this->load->language('purchase/sale_offer');
			
			$this->load->model('catalog/category');
			$data['categories'] = $this->model_catalog_category->getCategories($data = array());
			//print_r($data['categories']);
			$this->load->model('catalog/option');
			$data['options'] = $this->model_catalog_option->getOptions();
			
			$data['sale_chart_heading_text'] = $this->language->get('sale_chart_heading_text');
			//filter
			$data['all_products_text'] = $this->language->get('all_products_text');
			$data['select_categor_text'] = $this->language->get('select_categor_text');
			$data['select_option_text'] = $this->language->get('select_option_text');
			$data['select_option_value_text'] = $this->language->get('select_option_value_text');
			$data['sold_less_text'] = $this->language->get('sold_less_text');
			
			//column
			$data['column_product_name'] = $this->language->get('column_product_name');
			$data['column_category'] = $this->language->get('column_category');
			$data['column_option_value'] = $this->language->get('column_option_value');
			$data['column_stock'] = $this->language->get('column_stock');
			$data['column_sold'] = $this->language->get('column_sold');
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			$data['token'] = $this->session->data['token'];
			
			$url = '';
			$data['filter'] = $this->url->link('purchase/sale_offer/filter', 'token=' . $this->session->data['token'] . $url, true);
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('sale_chart_heading_text'),
				'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			$data['offer_sale'] = $this->url->link('purchase/sale_offer/offer_sale', 'token=' . $this->session->data['token'] . $url, true);
			
			
			
			
			if(isset($this->request->post['filter_category']))
			{
				$filter['filter_category'] = $this->request->post['filter_category'];
			}
			else
			{
				$filter['filter_category'] = '';
			}
			
			if(isset($this->request->post['filter_option_value']))
			{
				$filter['filter_option_value'] = $this->request->post['filter_option_value'];
			}
			else{
				$filter['filter_option_value'] = '';
			}
			
			if(isset($this->request->post['filter_all_products']))
			{
				$filter['filter_all_products'] = $this->request->post['filter_all_products'];
			}
			else{
				$filter['filter_all_products'] = '';
			}
			
			$this->load->model('purchase/sale_offer');
			
			$data['products'] = $this->model_purchase_sale_offer->filter($filter);
			
			if(count($data['products']) > 0)
			{
				if(isset($this->request->post['filter_category']))
				{
					$filter['filter_category'] = htmlentities($filter['filter_category'], null, 'utf-8');
					$data['filter_category'] = str_replace('&amp;','&',$filter['filter_category']);
				}
				
				if(isset($this->request->post['filter_option_value']))
				{
					$data['filter_option_value'] = $filter['filter_option_value'];
				}
				
				if(isset($this->request->post['filter_option']))
				{
					$data['filter_option'] = $this->request->post['filter_option'];
					$data['option_values']   = $this->model_catalog_option->getOptionValues($data['filter_option']);
				}
				
				if(isset($this->request->post['filter_sold_less']) && $this->request->post['filter_sold_less'] != '')
				{
					$data['filter_sold_less'] = $this->request->post['filter_sold_less'];
				}
				
			}
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('purchase/sale_offer.tpl',$data));

		}
		
		public function offer_sale()
		{
			if(!isset($this->request->post['selected']))
			{
				$_SESSION['offer_unsccess'] = "Please!! before applying offer, select the products";
				$this->index();
			}
			else
			{
				$discount = $this->request->post['discount'];
				$product_ids = $this->request->post['selected'];
				
				$this->load->model('purchase/sale_offer');
				$applied = $this->model_purchase_sale_offer->applyDiscount($product_ids,$discount);
				if($applied)
				{
					$_SESSION['applied_sccess'] = "Offer Applied";
					$this->index();
				}
			}
			
			
		}
	}
?>