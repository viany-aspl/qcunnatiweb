<?php
class ControllerFarmerrequestCarddetail extends Controller {
	private $error = array();

	public function index() {
		
		$this->document->setTitle("Card Detail");

		$this->load->model('farmerrequest/farmerrequest');
        
		$this->getForm();
	}
        
       
           public function check()
        {                         
                  
                  $this->load->model('farmerrequest/farmerrequest');
				  
				 // print_r($this->request->get); exit;
                   
                  $data11= $this->model_farmerrequest_farmerrequest->getcarddetail($this->request->get);
                  $data1=$data11[0];
                   $data=array();
				         
               $data=array(
                      'GROWER_NAME'=>$data1['GROWER_NAME'],
                      'FATHER_NAME'=>$data1['FTH_HUS_NAME'],
                      'GROWER_ID'=>$data1['GROWER_ID'],
                      'UNIT_ID'=>$data1['UNIT_ID'],
					  'UNIT_NAME'=>$data1['unit_name'],
                      'MOB'=>$data1['MOB'],
					  'CARD_PIN'=>$data1['CARD_PIN'],
					  
					  'DELIVERY_DATE'=> $data1['DELIVERY_DATE'],
					  'DISPATCH_DATE'=> $data1['DISPATCH_DATE'],
		
		
                      'VILLAGE_CODE'=> $data1['VILLAGE_CODE'],
					  'VILLAGE_NAME'=> $data1['VILLAGE_NAME'],
                      'CARD_STATUS_DESC'=>$data1['CARD_STATUS_DESC'],
                      'SID'=>$data1['SID'],
                      'CARD_SERIAL_NUMBER'=>$data1['CARD_SERIAL_NUMBER'],
                      'SITE'=>'MY'
                      );
					  
				  $data['cardhistory']=$this->model_farmerrequest_farmerrequest->getstatusdatehistory($data1['CARD_SERIAL_NUMBER']);
				  $data['cardtranshistory']=$this->model_farmerrequest_farmerrequest->getcardorderhistory($data1['CARD_SERIAL_NUMBER']);
                  $this->response->addHeader('Content-Type: application/json');
                  $this->response->setOutput(json_encode($data));
        
}

        
           public function blocked()
        {                         
                 
                   $data=array();
                   ///Chek grower id exist or not thorugh service 
                   // $this->load->library('soapcurl');
                   //$soapcurl = new soapcurl($this->registry);
                   //$data= $soapcurl->call('getGrowerCardStatus',$this->request->get,0);                   
                  $this->load->model('farmerrequest/farmerrequest');
                 
                  $StatusId='11';
                  $StatusName='CARD DEACTIVATED';
                  //print_r($this->request->get);
		  $retval = $this->model_farmerrequest_farmerrequest->UpdateBolcked($this->request->get,$StatusId,$StatusName);
                  if($retval>0)
                  {
                  $this->session->data['success'] ="Card Blocked Sucessfully !";
                  }
                    else {
                            $this->session->data['error_warning'] ="Some error occour try again !";
                    }
	         $this->response->addHeader('Content-Type: application/json');
                 
                 $this->response->setOutput(json_encode($retval));
                  
                

		 //$this->response->redirect($this->url->link('farmerrequest/farmerrequest', 'token=' . $this->session->data['token'], 'SSL'));
		        
        }
       


       
	protected function getForm() {
		$data['heading_title'] ='Card Status';
		
	

		$data['token'] = $this->session->data['token'];

		
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
                if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('farmerrequest/farmerrequest', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
		$data['cancel'] = $this->url->link('farmerrequest/farmerrequest', 'token=' . $this->session->data['token'] . $url, 'SSL');

		
		if (isset($this->request->post['logged'])) {
			$data['logged'] = $this->request->post['logged'];
		} elseif (!empty($coupon_info)) {
			$data['logged'] = $coupon_info['logged'];
		} else {
			$data['logged'] = '';
		}

		$data['companys'] = $this->model_farmerrequest_farmerrequest->getComapny();
		  $data['units'] = $this->model_farmerrequest_farmerrequest->getUnit();
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('farmerrequest/carddetail.tpl', $data));
	}
 
        public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_growerid'])) {
			$this->load->model('farmerrequest/farmerrequest');
			
			if (isset($this->request->get['filter_growerid'])) {
				$filter_growerid = $this->request->get['filter_growerid'];
			} else {
				$filter_growerid = '';
			}

			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_growerid'  => $filter_growerid,
				
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_farmerrequest_farmerrequest->getgrowerid($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'GROWER_ID' => $result['GROWER_ID'],
                                    'GROWER_NAME' => $result['GROWER_NAME']
					
					
				);
				}

				
			
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
    public function productdtlbyorderid()
	{
		 $this->load->model('farmerrequest/farmerrequest');
		 $order_id=$this->request->get['orderid'];
		 $result = $this->model_farmerrequest_farmerrequest->orderproductdetails($order_id);
		 return $this->response->setOutput(json_encode($result));
		
	}
       
}
