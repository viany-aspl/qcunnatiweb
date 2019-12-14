<?php
class ControllerFarmerrequestReissuecard extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle("Reissued Card Form");

		$this->load->model('farmerrequest/farmerrequest');
                
                if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                         $StatusId='12';
                         $StatusName='CARD REISSUE';
						
						  $this->load->model('unit/unit');
						 $unitdata= $this->model_unit_unit->getUnitByID($this->request->post['unitno']);
						 if(!empty($unitdata['company_name']))
						 {
						 
						 $company=strtolower($unitdata['company_name']);
						 $this->load->model('pos/'.$company);
						 $this->request->post['COMPANY_ID']=$unitdata['company_id'];
						 $this->request->post['CARD_SERIAL_NUMBER']="0";
						 $this->request->post['CARD_KYC_DOCUMENT']="0";
						 $this->request->post['CARD_ISSUE_DATE']=date('Y-M-d');
						 $this->request->post['CARD_VALIDITY_DATE']=date('Y-M-d');
						 $this->request->post['CARD_CREATE_DATE']=date('Y-M-d');
						 $this->request->post['CARD_QR_SRTING']="0";
						 
						 $this->request->post['CARD_STATUS']=$StatusId;
						$this->request->post['CARD_STATUS_DESC']= $StatusName;
				  
				  $this->request->post['CARD_GROWER_ID']=$this->request->post['growerid'];
				  $this->request->post['CARD_UNIT']= $this->request->post['unitno'];
						 $this->request->post['USER']=$this->user->getId();
						 $log=new Log($company."-Soapcurl-CardReissue-".date('Y-m-d').".log"); 
						 $log->write($this->request->post);	
						 //change status
						 if($this->request->post['growerid']!='0' && $this->request->post['unitno']!='0')
						 {
						 $datastatus = $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,$unitdata['unit_id']); 
							$log->write($datastatus);						 
						   if($datastatus=="1")
							{
							$dataresult = $this->{'model_pos_' . $company}->CardReissue('CardReissue',$this->request->get,$unitdata['unit_id']); 	
							}
							$log->write($dataresult);
						 
						 if($dataresult=="1")
                {					
			$category_id = $this->model_farmerrequest_farmerrequest->reissuedCard($this->request->post,$StatusId,$StatusName);						

			$this->session->data['success'] ="Card Re issued Sucessfully !";
                }
				else{
			$this->session->data['error_warning'] ="Some error occour try again !";
			}
						}else{
			$this->session->data['error_warning'] ="Some error occour try again !";
			} 
						}else{
			$this->session->data['error_warning'] ="Some error occour try again !";
			}
			    
			$this->response->redirect($this->url->link('farmerrequest/reissuecard', 'token=' . $this->session->data['token'], 'SSL'));
		        
                
				
                  
                }
               		
		$this->reissueForm();
	}
            
	protected function reissueForm() {
                error_reporting(0);
		$url = '';
		if (isset($this->request->get['filter_growerid'])) {
			$url .= '&filter_growerid=' . $this->request->get['filter_growerid'];
		}
		if (isset($this->request->get['filter_growerid'])) {
			$data['filter_growerid']=$filter_growerid=$this->request->get['filter_growerid'];
		}
		else
		{
			$filter_growerid='';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['page'])) {
			$page=$this->request->get['page'];
		}
		else
		{
			$page=1;
		}
		$data['breadcrumbs'] = array();

		

		$data['breadcrumbs'][] = array(
			'text' => 'Review Form',
			'href' => $this->url->link('farmerrequest/reissuecard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['orders'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin'),
			'filter_growerid' => $filter_growerid
		);
		
		$results = $this->model_farmerrequest_farmerrequest->getreviewdtl($filter_data);
	        $order_total=$revieTotal = $this->model_farmerrequest_farmerrequest->getreviewdtlToatal($filter_data);
		foreach ($results as $result) {
			$data['orders'][] = array(
                            'GROWER_ID' => $result['GROWER_ID'],
				'GROWER_NAME' => $result['GROWER_NAME'],
				'UNIT_ID'     => $result['UNIT_ID'],
                            'SID'     => $result['SID']
                                
				
			);
		}
		
		//$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = 'Please Select Grower Id';
		

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		

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
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('farmerrequest/reviewform', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('farmerrequest/reissuecard.tpl', $data));
	}
       public function check()
        {                         
                 
                   $data=array();
                                    
                  $this->load->model('farmerrequest/farmerrequest');
                 
                   //check sql
                  $data11= $this->model_farmerrequest_farmerrequest->getdetail($this->request->get);
                 if(!empty($data11))
                 {
     $data1=$data11[0];
     $data=array(
                      'GROWER_NAME'=>$data1['GROWER_NAME'],
                      'FATHER_NAME'=>$data1['FTH_HUS_NAME'],
                      'GROWER_ID'=>$data1['GROWER_ID'],
                      'UNIT_ID'=>$data1['UNIT_ID'],
					  'UNIT_NAME'=>$data1['unit_name'],
                      'MOB'=>$data1['MOB'],
                      'VILLAGE_CODE'=>$data1['VILLAGE_CODE'],
					  'VILLAGE_NAME'=>$data1['VILLAGE_NAME'],
                      'CARD_STATUS_DESC'=>$data1['CARD_STATUS_DESC'],
                      'SID'=>$data1['SID'],
                      'CARD_SERIAL_NUMBER'=>$data1['CARD_SERIAL_NUMBER'],
                      'SITE'=>'MY'
                      );
                 }
                  $this->response->addHeader('Content-Type: application/json');
                  $this->response->setOutput(json_encode($data));
        } 
    
         public function reissuecarddata()
        {                         
                 
                   $data=array();
                           
                  $this->load->model('farmerrequest/farmerrequest');
                 
                  $StatusId='2';
                  $StatusName='CARD VERIFIED';
                  $gid=$this->request->get['gid'];
                 // $card_sid=$this->request->get['card_sid'];
                  $rand_n=abs( crc32( uniqid() ) );
                  $card_serail_number="11"."11".substr($rand_n, 0,8) ;
                  
		  $retval = $this->model_farmerrequest_farmerrequest->updateReviewStatus($gid,$StatusId,$StatusName,$card_serail_number);
                 
                  $this->session->data['success'] ="Card Reviewed Sucessfully !";
                  
	         $this->response->addHeader('Content-Type: application/json');
                 
                 $this->response->setOutput(json_encode($retval));
                  
                  
        }
      
    
}
