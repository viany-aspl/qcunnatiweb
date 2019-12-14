<?php
	class ControllerPurchaseChart extends Controller {
		//sale chart
		public function index() {
			$this->load->language('purchase/sale_chart');
			$data['sale_chart_text'] = $this->language->get('sale_chart_text');
			$data['start_date_text'] = $this->language->get('start_date_text');
			$data['end_date_text'] = $this->language->get('end_date_text');
			
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			$url = '';
			$data['filter'] = $this->url->link('purchase/chart/filter', 'token=' . $this->session->data['token'] . $url, true);
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('sale_chart_text'),
				'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
			);
			$this->load->model('purchase/chart');
			$data['chart_data'] = $this->model_purchase_chart->getChartData();
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('purchase/sale_chart.tpl',$data));
		}
		//sale chart filter
		public function filter()
		{
			$this->load->language('purchase/sale_chart');
			$data['sale_chart_text'] = $this->language->get('sale_chart_text');
			$data['start_date_text'] = $this->language->get('start_date_text');
			$data['end_date_text'] = $this->language->get('end_date_text');
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			$url = '';
			$data['filter'] = $this->url->link('purchase/chart/filter', 'token=' . $this->session->data['token'] . $url, true);
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('sale_chart_text'),
				'href' => $this->url->link('purchase/chart', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['date_start'] = $this->request->post['date_start'];
			$data['date_end'] = $this->request->post['date_end'];
			$this->load->model('purchase/chart');
			$data['chart_data'] = $this->model_purchase_chart->getChartFilterData($data);
			if(!$data['chart_data'])
			{
				$data['chart_data'] = $this->model_purchase_chart->getChartData();
				$data['date_start'] = '';
				$data['date_end'] = '';
			}
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('purchase/sale_chart.tpl',$data));
		}
		
		public function purchase_chart()
		{
			$this->load->language('purchase/sale_chart');
			$data['purchase_chart_text'] = $this->language->get('purchase_chart_text');
			$data['start_date_text'] = $this->language->get('start_date_text');
			$data['end_date_text'] = $this->language->get('end_date_text');
			
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			$url = '';
			$data['filter'] = $this->url->link('purchase/chart/filter_purchase', 'token=' . $this->session->data['token'] . $url, true);
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('purchase_chart_text'),
				'href' => $this->url->link('purchase/chart/purchase_chart', 'token=' . $this->session->data['token'] . $url, true)
			);
			$this->load->model('purchase/chart');
			
			$data['chart_data'] = $this->model_purchase_chart->getPurchaseChartData();
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('purchase/purchase_chart.tpl',$data));
		}
		
		public function filter_purchase()
		{
			$this->load->language('purchase/sale_chart');
			$data['purchase_chart_text'] = $this->language->get('purchase_chart_text');
			$data['start_date_text'] = $this->language->get('start_date_text');
			$data['end_date_text'] = $this->language->get('end_date_text');
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			$url = '';
			$data['filter'] = $this->url->link('purchase/chart/filter_purchase', 'token=' . $this->session->data['token'] . $url, true);
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('sale_chart_text'),
				'href' => $this->url->link('purchase/chart', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['date_start'] = $this->request->post['date_start'];
			$data['date_end'] = $this->request->post['date_end'];
			
			$this->load->model('purchase/chart');
			$data['chart_data'] = $this->model_purchase_chart->getPurchaseChartFilterData($data);
			if(!$data['chart_data'])
			{
				$data['chart_data'] = $this->model_purchase_chart->getPurchaseChartData();
				$data['date_start'] = '';
				$data['date_end'] = '';
			}
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('purchase/purchase_chart.tpl',$data));
		}
		public function dead_chart()
		{
			$this->load->language('purchase/sale_chart');
			
			//entry
			
			$data['entry_date_start'] = $this->language->get('entry_date_start');
			$data['entry_date_end'] = $this->language->get('entry_date_end');
			$data['entry_dead_limit'] = $this->language->get('entry_dead_limit');
			$data['dead_chart_text'] = $this->language->get('dead_chart_text');
			
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			$url = '';
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $data['dead_chart_text'],
				'href' => $this->url->link('purchase/stock_report', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			
			$this->load->model('purchase/stock_report');
			if(isset($this->request->post['filter_bit']))
			{
				$filter['date_start'] = $this->request->post['date_start'];
				$filter['date_end'] = $this->request->post['date_end'];
				
				if(($filter['date_start'] != '' || $filter['date_end'] != '') && $this->request->post['dead_limit'] != '')
				{
					$data['dead_details'] = $this->model_purchase_stock_report->getDeadProducts($filter);
					$data['date_start'] = $filter['date_start'];
					$data['date_end'] = $filter['date_end'];
					if($this->request->post['dead_limit']!='')
					{
						$data['dead_limit'] = $this->request->post['dead_limit'];
					}
					
				}
				elseif($this->request->post['dead_limit'] != '')
				{
					$data['dead_details'] = $this->model_purchase_stock_report->getDeadProducts();
					$data['date_start'] = '';
					$data['date_end'] = '';
					if($this->request->post['dead_limit']!='')
					{
						$data['dead_limit'] = $this->request->post['dead_limit'];
					}
				}
			}
			
			$data['filter_chart'] = $this->url->link('purchase/chart/dead_chart', 'token=' . $this->session->data['token'] . $url, true);
			
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('purchase/dead_products_chart.tpl',$data));
		
		}
	}
?>