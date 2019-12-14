<?php
//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerProcurementCm extends Controller{
    
    
        public function confirm_order_by_cm() {
                $this->load->language('report/Inventory_report');
		$data['column_left'] = $this->load->controller('common/column_left');
		$user_info = $this->model_user_user->getUser($this->user->getId());
                $user_group_id=$user_info['user_group_id'];
                
                $url = '';
              
                if ($this->request->get['filter_id']!="") {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
                if ($this->request->get['filter_status']!="") {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
                if ($this->request->get['filter_date_start']!="") {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
                if ($this->request->get['filter_date_end']!="") {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (!empty($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
                
                $order_total=$this->request->get['order_total'];
                $creditlimit=$this->request->get['creditlimit'];
                $currentcredit=$this->request->get['currentcredit'];
                if(($currentcredit+$order_total)>$creditlimit)
                {
                  if (isset($this->request->get['order_id'])) 
                  {
			$url .= '&order_id=' . $this->request->get['order_id'];
		  }
                  $this->session->data['warning'] = 'Order total is greater then Available credit Limit !';
                  $this->response->redirect($this->url->link('procurement/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, 'SSL'));  
                }
		else
                {
                $order_id = $this->request->get['order_id'];
                $from=$this->request->get['order_status_id'];
                $user_id=$this->request->get['user_id'];
                
                $this->load->model('procurement/purchase_order');
		$results = $this->model_procurement_purchase_order->confirm_order_by_cm($order_id);
		$this->model_procurement_purchase_order->add_po_trans($order_id,$from,'5',$user_id);
                $this->session->data['success'] = 'Order is approved successfully';
                $this->response->redirect($this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
        }
        public function cancel_order_by_cm() {
                $this->load->language('report/Inventory_report');
		$data['column_left'] = $this->load->controller('common/column_left');
		$user_info = $this->model_user_user->getUser($this->user->getId());
                $user_group_id=$user_info['user_group_id'];
                
		$url = '';
              
                if ($this->request->get['filter_id']!="") {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
                if ($this->request->get['filter_status']!="") {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
                if ($this->request->get['filter_date_start']!="") {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
                if ($this->request->get['filter_date_end']!="") {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (!empty($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
                $order_id = $this->request->get['order_id'];
                $reject_Message=$this->request->get['reject_Message'];
                $user_id=$this->request->get['user_id'];
                $from=$this->request->get['order_status_id'];
                $this->load->model('procurement/purchase_order');
		$results = $this->model_procurement_purchase_order->cancel_order_by_cm($order_id,$reject_Message,$user_id);
		$this->model_procurement_purchase_order->add_po_trans($order_id,$from,'4',$user_id);
                $this->session->data['success'] = 'Order is canceled successfully';
                $this->response->redirect($this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
   
	
}

?>