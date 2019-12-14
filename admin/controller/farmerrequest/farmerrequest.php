<?php
class ControllerFarmerrequestFarmerrequest extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('marketing/coupon');

		$this->document->setTitle("Card Request");

		$this->load->model('farmerrequest/farmerrequest');
                
		
                if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                    $log=new Log("CardRequest-".date('Y-m-d').".log"); 
                    $log->write($this->request->post);
                  // print_r($this->request->post);
                    //exit;
					$this->load->model('unit/unit');
                 $unitdata= $this->model_unit_unit->getUnitByID($this->user->getUnitId());
                         $StatusId='1';
                         $StatusName='CARD REQUEST';
			$log->write($unitdata);
$this->request->post['COMPANY_ID']=$unitdata['company_id'];
$this->request->post['CARD_SERIAL_NUMBER']="0";
$this->request->post['CARD_KYC_DOCUMENT']="0";
$this->request->post['CARD_ISSUE_DATE']=date('Y-M-d');
$this->request->post['CARD_VALIDITY_DATE']=date('Y-M-d',strtotime(date("Y-M-d", mktime()) . " + 365 day"));
$this->request->post['CARD_CREATE_DATE']=date('Y-M-d');
$this->request->post['CARD_QR_SRTING']="0";
$this->request->post['USER']=$this->user->getId();
$datares="";
		/*if($unitdata['company_id']=="4"){
							$this->load->library('soapcurl');
						$soapcurl = new soapcurl($this->registry);
						$datares= $soapcurl->CardRequest('CardRequest',$this->request->post,0); 
						}*/
						 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->load->model('pos/'.$company);
						$datares = $this->{'model_pos_' . $company}->CardRequest('CardRequest',$this->request->post,0); 
						
						}
			$log->write($datares);
			
			if($datares=="1"){
						$category_id = $this->model_farmerrequest_farmerrequest->addCarddetail($this->request->post,$StatusId,$StatusName);
$log->write($category_id);
			$this->session->data['success'] ="Card Detail Updated Sucessfully !";
			}else{
			$this->session->data['error_warning'] ="Some error occour try again !";
			}

			$this->response->redirect($this->url->link('farmerrequest/farmerrequest', 'token=' . $this->session->data['token'], 'SSL'));		                                
                  
                }

		$this->getForm();
	}
public function rejectstatusremove()
        {                         
                 
                   $data=array();
                                   
                  $this->load->model('farmerrequest/farmerrequest');
                 
                  $StatusId='1';
                  $StatusName='CARD REQUEST';
                  //print_r($this->request->get);
		  $retval = $this->model_farmerrequest_farmerrequest->Updaterejectstatusremove($this->request->get,$StatusId,$StatusName);
                  if($retval>0)
                  {
                  $this->session->data['success'] ="Card Rejected Sucessfully !";
                  }
                    else {
                            $this->session->data['error_warning'] ="Some error occour try again !";
                    }
	         $this->response->addHeader('Content-Type: application/json');
                 
                 $this->response->setOutput(json_encode($retval));
                  
                

		 //$this->response->redirect($this->url->link('farmerrequest/farmerrequest', 'token=' . $this->session->data['token'], 'SSL'));
		        
        }
        
       
           public function check()
        {                         
                 
                   $data=array();
				   $log=new Log("Soapcurl-GetGrower-".date('Y-m-d').".log"); 
                   ///Chek grower id exist or not thorugh service 
                   // $this->load->library('soapcurl');
                   //$soapcurl = new soapcurl($this->registry);
                   //$data= $soapcurl->call('getGrowerCardStatus',$this->request->get,0);                   
                  $this->load->model('farmerrequest/farmerrequest');
				   $this->load->model('unit/unit');
				   $log->write($this->user->getUnitId());
                 $unitdata= $this->model_unit_unit->getUnitByID($this->user->getUnitId());
				 
				  $log->write($unitdata);
                   //check sql
                  $data11= $this->model_farmerrequest_farmerrequest->getdetail($this->request->get);
                  //print_r($data1);
                  
                  if(empty($data11))
                  {
                      //$data12= $this->model_farmerrequest_farmerrequest->getdetail2($this->request->get);
					  //check at client end
					  ///Chek grower id exist or not thorugh service 
					 /* if($unitdata['company_id']=="4"){
						$this->load->library('soapcurl');
						$soapcurl = new soapcurl($this->registry);
						$data12= $soapcurl->GetGrower('GetGrowerByIDNUM',$this->request->get,0); 
						}*/
						 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->load->model('pos/'.$company);
						$data12 = $this->{'model_pos_' . $company}->GetGrower('GetGrowerByID',$this->request->get,$unitdata['unit_id']); 	
							$log=new Log($company."-Soapcurl-GetGrower-".date('Y-m-d').".log"); 
	$log->write($data12);
						}
                      //print_r($data12);
					 
                      if(!empty($data12))
                      {
                      $data1=$data12;
                      $data=array(
                     'GROWER_NAME'=>$data1['GROWER_NAME'],
					 'FATHER_NAME'=>$data1['FATHER_NAME'],
					 'GROWER_ID'=>$data1['GROWER_ID'],
					 'UNIT_ID'=>$this->user->getUnitId(),
					  'UNIT_NAME'=>$unitdata['unit_name'],
					 'MOB'=>$data1['MOB'],
					 'VILLAGE_CODE'=>$data1['VILLAGE_CODE'],
					 'VILLAGE_NAME'=>$data1['VILLAGE_NAME'],
					 'CARD_STATUS_DESC'=>'',
					 'SID'=>$data1['SID'],
					 'CARD_SERIAL_NUMBER'=>$data1['CARD_SERIAL_NUMBER'],
					 'SITE'=>'CLIENT'
                      );
                      }
                  }
 else {
     $data1=$data11[0];
     $data=array(
                      'GROWER_NAME'=>$data1['GROWER_NAME'],
                      'FATHER_NAME'=>$data1['FTH_HUS_NAME'],
                      'GROWER_ID'=>$data1['GROWER_ID'],
                      'UNIT_ID'=>$data1['UNIT_ID'],
					   'UNIT_NAME'=>$data1['unit_name'],
                      'MOB'=>$data1['MOB'],
                      'VILLAGE_CODE'=> $data1['VILLAGE_CODE'],
					  'VILLAGE_NAME'=> $data1['VILLAGE_NAME'],
                      'CARD_STATUS_DESC'=>$data1['CARD_STATUS_DESC'],
                      'SID'=>$data1['SID'],
                      'CARD_SERIAL_NUMBER'=>$data1['CARD_SERIAL_NUMBER'],
                      'SITE'=>'MY'
                      );
 }
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
		$data['heading_title'] ='----';
		
	

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


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('farmerrequest/managecard.tpl', $data));
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
        
       
}
