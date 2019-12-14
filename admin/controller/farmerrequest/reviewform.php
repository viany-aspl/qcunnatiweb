<?php
class ControllerFarmerrequestReviewform extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle("Review Form");

		$this->load->model('farmerrequest/farmerrequest');
               		
		$this->reviewForm();
	}
            
	protected function reviewForm() {
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
		if (isset($this->request->get['filter_unit'])) {
 $data['filter_unit']=$filter_unit1=$this->request->get['filter_unit'];
 }
 else
 {
 $filter_unit1='';
 }
 if (isset($this->request->get['filter_company'])) {
 $data['filter_company']=$filter_company=$this->request->get['filter_company'];
 $this->load->model('farmerrequest/farmerrequest');
 $cid=$this->request->get['filter_company'];
 $data['units2']= $this->model_farmerrequest_farmerrequest->getunitbycompany($cid);
 }
 else
 {
 $filter_company='';
 }
 if (isset($this->request->get['filter_village'])) {
		$filter_village=$this->request->get['filter_village'];
		}
		else
		{
			$filter_village='';
		}
			if (isset($this->request->get['filter_village'])) {
			$url .= '&filter_village=' . $this->request->get['filter_village'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . $this->request->get['filter_company'];
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
			'href' => $this->url->link('farmerrequest/reviewform', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['orders'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin'),
			'filter_growerid' => $filter_growerid,
			'filter_unit' => $filter_unit1,
			'filter_company' => $filter_company
		);
		//print_r($filter_data);
		
		$results = $this->model_farmerrequest_farmerrequest->getreviewdtl($filter_data);
	        $order_total=$revieTotal = $this->model_farmerrequest_farmerrequest->getreviewdtlToatal($filter_data);
		foreach ($results as $result) {
			$data['orders'][] = array(
                            'GROWER_ID' => $result['GROWER_ID'],
				'GROWER_NAME' => $result['GROWER_NAME'],
				'VILLAGE_ID'     => $result['VILLAGE_ID'],
				'UNIT_ID'     => $result['UNIT_ID'],
                'SID'     => $result['SID'],
				 'VILLAGE_NAME'     => $result['VILLAGE_NAME'],
				  'unit_name'     => $result['unit_name']
							
                                
				
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
        $data['companys'] = $this->model_farmerrequest_farmerrequest->getComapny();
        $data['units'] = $this->model_farmerrequest_farmerrequest->getUnit();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('farmerrequest/reviewform.tpl', $data));
	}
	public function getUnitbyCompany(){


 $this->load->model('farmerrequest/farmerrequest');

$cid=$this->request->post['companyid'];
 if (isset($this->request->post['companyid']))

{

$dunit= $this->model_farmerrequest_farmerrequest->getunitbycompany($cid);

$dpunit= count($dunit);
 echo $dpunit;
 echo ' <option value=""> SELECT UNIT</option> ';
 for($n=0;$n<$dpunit;$n++)
 {
 echo '<option value="'.$dunit[$n]['unit_id'].'">'.$dunit[$n]['unit_name'].'</option>';
 }

}

}
        
    public function reviewAll()
    {
        //print_r($this->request->get['checkbox_grower']);
		$log=new Log("CARDSTATUS-All-".date('Y-m-d').".log"); 
		$this->load->model('unit/unit');
                 
        foreach($this->request->get['checkbox_grower'] as $grower_id)
        {
            
                   $data=array();
                           
                  $this->load->model('farmerrequest/farmerrequest');
                 $log->write($grower_id);
				 $unit_grower = explode("-", $grower_id);
				 $grower_id=$unit_grower[0];
                  $StatusId='2';
                  $StatusName='CARD VERIFIED';
                  //$gid=$grower_id;
				  //$grower_id=explode("-",$grower_id);
					$gid=$grower_id;
					
					//$getdatar = $this->model_farmerrequest_farmerrequest->getcardserialno($grower_id);
					//$log->write($getdatar);
					$unit =$unit_grower[1];//$getdatar['UNIT_ID'];
					$unitdata= $this->model_unit_unit->getUnitByID($unit);
					$log->write($unitdata);
                  $rand_n=abs( crc32( uniqid() ) );
                  $card_serail_number=$unitdata['company_id'].$unitdata['company_id'].$unit.substr($rand_n, 0,8) ;
                  //$card_sid=$this->request->get['sid'];
				    $this->request->post['CARD_STATUS']="2";
				   $this->request->post['CARD_STATUS_DESC']= $StatusName;
				  $this->request->post['CARD_SERIAL_NUMBER']= $card_serail_number;
				  $this->request->post['CARD_GROWER_ID']=  $gid;
				  $this->request->post['CARD_UNIT']= $unit;
				  $this->request->post['CARD_QR_SRTING']="0";
				  $this->request->post['USER']=$this->user->getId();
				  
							//$this->load->library('soapcurl');
						//$soapcurl = new soapcurl($this->registry);
						 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->load->model('pos/'.$company);
						$datares = $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
						$log->write($datares);
						}
						//$datares= $soapcurl->CardStatus('CardStatus',$this->request->post,0); 
			$log->write($datares);
			
			if($datares=="1"){
				$retval = $this->model_farmerrequest_farmerrequest->updateReviewStatus($gid,$StatusId,$StatusName,$card_serail_number);
                 
				 }
                  
        }
		$this->session->data['success'] ="Card(s) Reviewed Sucessfully !";
                  
	         $this->response->addHeader('Content-Type: application/json');
                 
                 $this->response->setOutput(json_encode($retval));
        //print_r($this->request->post);
        //return;
    }
     public function reviewmodaldata()
        {                         
                 $growerid=$this->request->get['growerid'];
                   $data=array();
                                      
                  $this->load->model('farmerrequest/farmerrequest');
                 
                   //check sql
                  $data= $this->model_farmerrequest_farmerrequest->getreviwmodaldtl($growerid);
                  //$this->session->data['success'] ="Card Reviewed Sucessfully !";
                  $this->response->addHeader('Content-Type: application/json');
                  $this->response->setOutput(json_encode($data));
        }
         public function reviewdata()
        {                         
                 
                   $data=array();
                     $log=new Log("CARDSTATUS-".date('Y-m-d').".log"); 
                    $log->write($this->request->get);       
                  $this->load->model('farmerrequest/farmerrequest');
                 //echo "here 1";
                  $StatusId='2';
                  $StatusName='CARD VERIFIED';
                  $gid=$this->request->get['gid'];
				  $unit=$this->request->get['unit'];
				  $this->load->model('unit/unit');
                  $unitdata= $this->model_unit_unit->getUnitByID($unit);
				 //print_r($unitdata);
				 //echo "here 3";
					$log->write($unitdata);
                 // $card_sid=$this->request->get['card_sid'];
                  $rand_n=abs( crc32( uniqid() ) );
                  $card_serail_number=$unitdata['company_id'].$unitdata['company_id'].$unit.substr($rand_n, 0,8) ;
				  $this->request->post['CARD_STATUS']="2";
				   $this->request->post['CARD_STATUS_DESC']= $StatusName;
				  $this->request->post['CARD_SERIAL_NUMBER']= $card_serail_number;
				  $this->request->post['CARD_GROWER_ID']=  $gid;
				  $this->request->post['CARD_UNIT']= $unit;
				  $this->request->post['CARD_QR_SRTING']="0";
				  $this->request->post['USER']=$this->user->getId();
				 /* if($unitdata['company_id']=="4"){
							$this->load->library('soapcurl');
						$soapcurl = new soapcurl($this->registry);
						$datares= $soapcurl->CardStatus('CardStatus',$this->request->post,0); 
						}
						else if($unitdata['company_id']=="2"){
						$company="bcml";
						//$this->load->model('pos/'.$company);
						//$datares = $this->{'model_pos_' . $company}->getDataFromServer($data);
						$datares="1";
						}*/
						if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$log->write("company detail ".$company);
						$this->load->model('pos/'.$company);
						$log->write("company model load detail ".$company);
						$datares = $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
						
						}else{
						$log->write("company detail not found of unit".$unit);
						}
						$log->write('response from the model is : ');
						$log->write($datares);
						//echo $datares;
						if($datares=="1")
						{
							$log->write('response from the model is success: ');
							$retval = $this->model_farmerrequest_farmerrequest->updateReviewStatus($gid,$StatusId,$StatusName,$card_serail_number);
                 
							$this->session->data['success'] ="Card Reviewed Sucessfully !";
							$log->write(json_encode($retval));
							$this->response->addHeader('Content-Type: application/json');
                 
							$this->response->setOutput(json_encode($retval));
						}
						else
						{
							$log->write('response from the model is failure: ');
							$this->session->data['error'] ="Oops ! Some error occur. Please try again !";
							$log->write(json_encode('0'));
							$this->response->addHeader('Content-Type: application/json');
                 
							$this->response->setOutput(json_encode('0'));
						}
                  
        }
         public function reviewrejectdata()
        {                         
                 
                   $data=array();
                            $log=new Log("CARD-REJECT-".date('Y-m-d').".log"); 
                    $log->write($this->request->get); 
					$log->write($this->request->post); 
                  $this->load->model('farmerrequest/farmerrequest');
                 $this->load->model('unit/unit');
                  $StatusId='4';
                  $StatusName='CARD REQUEST REJECTED';				  
                  $gid=$this->request->get['gid'];
				  $unit=$this->user->getUnitId();
				  $log->write($unit); 
				  $card_serail_number="0";
				  $this->request->post['CARD_STATUS']="4";
				   $this->request->post['CARD_STATUS_DESC']= $StatusName;
				  $this->request->post['CARD_SERIAL_NUMBER']= $card_serail_number;
				  $this->request->post['CARD_GROWER_ID']=  $gid;
				  $this->request->post['CARD_UNIT']= $unit;
				  $this->request->post['CARD_QR_SRTING']="0";
				  $this->request->post['USER']=$this->user->getId();
                 $unitdata= $this->model_unit_unit->getUnitByID($unit);
				 $log->write($unitdata); 
				 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->load->model('pos/'.$company);
						$datares = $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 						
						}
			$log->write($datares);			
			if($datares=="1"){
					$retval = $this->model_farmerrequest_farmerrequest->updateRejectStatus($gid,$StatusId,$StatusName);                 
					$this->session->data['success'] ="Card Rejected Sucessfully !";                  
					$this->response->addHeader('Content-Type: application/json');                 
					$this->response->setOutput(json_encode($retval));
				 }
                  
                  
        }
    
}
