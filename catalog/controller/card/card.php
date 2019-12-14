<?php
	//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
	class ControllerCardCard extends Controller {

public function adminmodel($model) {
      
      $admin_dir = DIR_SYSTEM;
      $admin_dir = str_replace('system/','admin/',$admin_dir);
      $file = $admin_dir . 'model/' . $model . '.php';      
      //$file  = DIR_APPLICATION . 'model/' . $model . '.php';
      $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
      
      if (file_exists($file)) {
         include_once($file);
         
         $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
      } else {
         trigger_error('Error: Could not load model ' . $model . '!');
         exit();               
      }
   }


		public function index() {
			$url = '';
			
			$this->load->model('card/card');
			$this->document->setTitle('Card List');
			$data['heading_title'] = 'Card List';
			$data['text_list'] = 'Card List';
			
			
			
			$data['button_filter'] = $this->language->get('button_filter');
			$data['button_clear'] = $this->language->get('button_clear');
			
			$data['token'] = $this->session->data['token'];
			
			if (isset($this->request->get['page'])) {
                            $page = $this->request->get['page'];
                        } else {
                            $page = 1;
                        }

                        $url = '';

                        

                        if (isset($this->request->get['page'])) {
                            $url .= '&page=' . $this->request->get['page'];
                        }
			
                        $filter_data = array(
                                        'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                                        'limit' => $this->config->get('config_limit_admin')
                        );

                        $product_total = $this->model_card_card->getCardListTotal($filter_data);

                        $results = $this->model_card_card->getCardList($filter_data);

                        foreach ($results as $result) {
                                $data['payout'][] = array(
                                'amount' => $result['amount'],
                                'transaction_type' => $result['transaction_type'],
                                'create_date' => $result['create_date'],
                                'payment_method' => $result['payment_method']


                                );
                        }
                        
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Card List',
				'href' => $this->url->link('card/card', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			//$data['header'] = $this->load->controller('common/header');
			//$data['column_left'] = $this->load->controller('common/column_left');
			//$data['footer'] = $this->load->controller('common/footer');
			//$this->response->setOutput($this->load->view('card/cardlist.tpl', $data));
			 if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/supplier/supplier.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/supplier/supplier.tpl', $data));
			}
			
		}
        
	}
?>