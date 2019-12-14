<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerInvoiceAdjustment extends Controller{
       
        public function get_to_store_data()
        {
            $store_id = $this->request->get['store_id'];
            $this->load->model('partner/purchase_order');
            echo $data=$this->model_partner_purchase_order->get_to_store_data($store_id);
            
        }
        public function index()
	{
			$this->document->setTitle("Partner Invoice Adjustment");
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			$url = '';

			
			if (isset($this->request->get['filter_store'])) {
				$url .= '&filter_store=' . $this->request->get['filter_store'];
			}
                        if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . $this->request->get['filter_type'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
                       
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
			$data['breadcrumbs'][] = array(
			'text' => "Partner Invoice Adjustment",
			'href' => $this->url->link('invoice/adjustment', 'token=' . $this->session->data['token'] . $url, true)
		);

		$this->load->model('invoice/adjustment');
		//print_r($this->request->get);exit;
                        if (isset($this->request->get['page'])) {
                                $page = $this->request->get['page'];
                        } 
                        else {
                                $page = 1;
                        }
                        if (isset($this->request->get['filter_store'])) {
				$filter_store =  $this->request->get['filter_store'];
			}
                        if (isset($this->request->get['filter_type'])) {
				$filter_type =  $this->request->get['filter_type'];
			}
                        
                        $filter_data=array(
                            'filter_store'=>$filter_store,
                            'filter_type'=>$filter_type,
                            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                            'limit'   => $this->config->get('config_limit_admin')
                        );
		if($filter_store!="")
                {
		$data['order_list'] = $this->model_invoice_adjustment->getList($filter_data);
		
		$total_orders = $this->model_invoice_adjustment->getTotalOrders($filter_data);
                }
                $data['partner_info'] = $this->model_invoice_adjustment->getPartnerInfo($filter_data);
                
                
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		/*pagination*/
		$pagination = new Pagination();
		$pagination->total = $total_orders;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('invoice/adjustment', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin')));
                $this->load->model('setting/store');
                $data['stores']=$this->model_setting_store->getFranchiseStores();
		$data['filter_store']=$filter_store;
		$data['filter_type']=$filter_type;
                $data['token']=$this->request->get['token'];
                $data['adjust_invoice']=$this->url->link('invoice/adjustment/adjust_invoice', 'token=' . $this->session->data['token'] . $url, true);
		$data['my_custom_text'] = "This is purchase order page.";
		$this->response->setOutput($this->load->view('invoice/invoice_adjustment_list.tpl', $data));
	}
	
        public function adjust_invoice()
        {
           
                $invoice_id=$this->request->get['invoice_id'];
            
                $url = '';
                if (isset($this->request->get['filter_store'])) {
				$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . $this->request->get['filter_type'];
		}
		if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
		}
                        
               $this->load->model('invoice/adjustment');
               $return_data=$this->model_invoice_adjustment->adjust_invoice($invoice_id);
               
               if($return_data=="")
               {
                   $this->session->data['success']="Invoice Amount Adjusted Successfully";
                   $this->response->redirect($this->url->link('invoice/adjustment', 'token=' . $this->session->data['token'] . $url, true));
               }
               else
               {
                   $this->session->data['error_warning']=$return_data;
                   $this->response->redirect($this->url->link('invoice/adjustment', 'token=' . $this->session->data['token'] . $url, true));
               }
              
            
            
        }
        
	
}

?>