<?php
	class ControllerPurchaseChart extends Controller {
		public function index() {
			$this->load->language('purchase/sale_chart');
			$data['sale_chart_text'] = $this->language->get('sale_chart_text');
			$data['start_date_text'] = $this->language->get('start_date_text');
			$data['end_date_text'] = $this->language->get('end_date_text');
			
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			$url = '';
			$data['filter'] = $this->url->link('purchase/sale_chart/filter', 'token=' . $this->session->data['token'] . $url, true);
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('sale_chart_text'),
				'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
			);
			$this->load->model('purchase/sale_chart');
			$data['chart_data'] = $this->model_purchase_sale_chart->getChartData();
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('purchase/sale_chart.tpl',$data));
		}
		
		public function filter()
		{
			$this->load->language('purchase/sale_chart');
			$data['sale_chart_text'] = $this->language->get('sale_chart_text');
			$data['start_date_text'] = $this->language->get('start_date_text');
			$data['end_date_text'] = $this->language->get('end_date_text');
			//button
			
			$data['button_clear'] = $this->language->get('button_clear');
			
			$url = '';
			$data['filter'] = $this->url->link('purchase/sale_chart/filter', 'token=' . $this->session->data['token'] . $url, true);
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('sale_chart_text'),
				'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['date_start'] = $this->request->post['date_start'];
			$data['date_end'] = $this->request->post['date_end'];
			$this->load->model('purchase/sale_chart');
			$data['chart_data'] = $this->model_purchase_sale_chart->getChartFilterData($data);
			if(!$data['chart_data'])
			{
				$data['chart_data'] = $this->model_purchase_sale_chart->getChartData();
				$data['date_start'] = '';
				$data['date_end'] = '';
			}
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('purchase/sale_chart.tpl',$data));
		}
	}
?>