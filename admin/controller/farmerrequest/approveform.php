<?php
class ControllerFarmerrequestApproveform extends Controller {
	private $error = array();

	public function index() {
		$this->document->setTitle("Approval Form");

		$this->load->model('farmerrequest/farmerrequest');
		
		$this->approveForm();
	}

        
        public function approvedata()
        {                         
                 
                   $data=array();
                        
                  $this->load->model('farmerrequest/farmerrequest');
                 $this->load->model('unit/unit');
                  $StatusId='3';
                  $StatusName='CARD APPROVED';
                  $gid=$this->request->get['gid'];
                  $card_number=$this->request->get['cardid'];
                  $unit=$this->request->get['unitid'];
		          $log=new Log("CARD-STATUS-".date('Y-m-d').".log"); 
                    $log->write($this->request->get);
					$unitdata= $this->model_unit_unit->getUnitByID($unit);
					$log->write($unitdata);
                   $mcrypt=new MCrypt();
				   /*$card_data='<?xml version="1.0" encoding="UTF-8"?>  . '<CardData '  />*/ 
                    $card_data=''                       
                        . 'AC="01" '
                        . 'UN="'.($unit).'" '
                        . 'CI="'.($unitdata['company_id']).'" '
                        . 'GC="'.$mcrypt->encrypt($gid).'" '
                        . 'CSN="'.$mcrypt->encrypt($card_number).'"';
						$log->write($card_data);
						//$mcrypt->encrypt
                    $card_data= strtoupper(($card_data));
                   /*
                    
                   $card_data='<?xml version="1.0" encoding="UTF-8"?> '
                        . '<CardData '
                        . 'AC="01" '
                        . 'UN="'.($unit).'" '
                        . 'CI="'.(COMPANY_ID).'" '
                        . 'GC="'.($gid).'" '
                        . 'CSN="'.($card_number).'" />';
                    $card_data= strtoupper(($card_data));
						*/
                    //echo $card_data;
                  
                    $this->load->library('phpqrcode/qrlib');
                    $folder_name='qrimages';
                    $dir=DIR_UPLOAD.$folder_name;
                    if ( !file_exists($dir) ) {
                    $oldmask = umask(0);  // helpful when used in linux server  
                    mkdir ($dir, 0744);
                    }
//echo "here" ;  
                    $card_qr_img=$dir.'/'.$card_number.'.png';
                    define('IMAGE_WIDTH',120);
                    define('IMAGE_HEIGHT',120);
                    QRcode::png($card_data, $card_qr_img);
                    $card_image=$card_number.'.png';
	$this->request->post['CARD_STATUS']="3";
				   $this->request->post['CARD_STATUS_DESC']= $StatusName;
				  $this->request->post['CARD_SERIAL_NUMBER']= $card_number;
				  $this->request->post['CARD_GROWER_ID']=  $gid;
				  $this->request->post['CARD_UNIT']= $unit;
				  $this->request->post['CARD_QR_SRTING']=$card_data;
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
						$this->load->model('pos/'.$company);
						$datares = $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
						
						}
			$log->write($datares);
			if($datares=="1"){
                   $retval = $this->model_farmerrequest_farmerrequest->updateApprovedStatus($gid,$StatusId,$StatusName,$card_data);
                 
                   $this->session->data['success'] ="Card Approved Sucessfully !";
                  
	          $this->response->addHeader('Content-Type: application/json');
                 
                  $this->response->setOutput(json_encode($retval));}
                  
                  
        }
        
        protected function approveForm() {
	
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
			'text' => 'Approve Form',
			'href' => $this->url->link('farmerrequest/approveform', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['orders'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin'),
			'filter_growerid' => $filter_growerid,
			'filter_unit' => $filter_unit1,
			'filter_village' => $filter_village,
			'filter_company' => $filter_company
		);
		
		$results = $this->model_farmerrequest_farmerrequest->getapprovedtl($filter_data);
	        $order_total=$revieTotal = $this->model_farmerrequest_farmerrequest->getapproveToatal($filter_data);
		foreach ($results as $result) {
			$data['orders'][] = array(
                            'GROWER_ID' => $result['GROWER_ID'],
				'GROWER_NAME' => $result['GROWER_NAME'],
				'UNIT_ID'     => $result['UNIT_ID'],
                'CARD_SERIAL_NUMBER' => $result['CARD_SERIAL_NUMBER'],
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
		$pagination->url = $this->url->link('farmerrequest/approveform', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
       $data['companys'] = $this->model_farmerrequest_farmerrequest->getComapny();
        $data['units'] = $this->model_farmerrequest_farmerrequest->getUnit();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('farmerrequest/approveform.tpl', $data));
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
    public function approveAll()
    {
		$log=new Log("CARDSTATUS-Approve-All-".date('Y-m-d').".log"); 
        //print_r($this->request->get['checkbox_grower']);
		$this->load->model('unit/unit');
		$this->load->model('farmerrequest/farmerrequest');
        foreach($this->request->get['checkbox_grower'] as $grower_id)
        {
            $log->write($grower_id);
                   $data=array();
                       $StatusId='3';
                  $StatusName='CARD APPROVED';                  
                 // $card_number=$this->request->get['cardid'];
                 // $unit=$this->request->get['unitid'];                                         
                  $StatusId='3';
                  $StatusName='CARD APPROVED';
                  $gid=$grower_id;
				  $getdatar = $this->model_farmerrequest_farmerrequest->getcardserialno($grower_id);
				  $log->write($getdatar);
					$unit =$getdatar['UNIT_ID'];
					$card_number=$getdatar['CARD_SERIAL_NUMBER'];
					$unitdata= $this->model_unit_unit->getUnitByID($unit);
					
					//card data
					 $mcrypt=new MCrypt();
                    $card_data='<?xml version="1.0" encoding="UTF-8"?> '
                        . '<CardData '
                        . 'AC="01" '
                        . 'UN="'.($unit).'" '
                        . 'CI="'.($unitdata['company_id']).'" '
                        . 'GC="'.$mcrypt->encrypt($gid).'" '
                        . 'CSN="'.$mcrypt->encrypt($card_number).'" />';
                    $card_data= strtoupper($mcrypt->encrypt($card_data));
                   /*
                    
                   $card_data='<?xml version="1.0" encoding="UTF-8"?> '
                        . '<CardData '
                        . 'AC="01" '
                        . 'UN="'.($unit).'" '
                        . 'CI="'.(COMPANY_ID).'" '
                        . 'GC="'.($gid).'" '
                        . 'CSN="'.($card_number).'" />';
                    $card_data= strtoupper(($card_data));
						*/
                    //echo $card_data;
                  
                    $this->load->library('phpqrcode/qrlib');
                    $folder_name='qrimages';
                    $dir=DIR_UPLOAD.$folder_name;
                    if ( !file_exists($dir) ) {
                    $oldmask = umask(0);  // helpful when used in linux server  
                    mkdir ($dir, 0744);
                    }
//echo "here" ;  
                    $card_qr_img=$dir.'/'.$card_number.'.png';
                    define('IMAGE_WIDTH',120);
                    define('IMAGE_HEIGHT',120);
                    QRcode::png($card_data, $card_qr_img);
                    $card_image=$card_number.'.png';
					//end data
					
					
				  $this->request->post['CARD_STATUS']="3";
				   $this->request->post['CARD_STATUS_DESC']= $StatusName;
				  $this->request->post['CARD_SERIAL_NUMBER']= $card_number;
				  $this->request->post['CARD_GROWER_ID']=  $gid;
				  $this->request->post['CARD_UNIT']= $unit;
				  $this->request->post['CARD_QR_SRTING']=$card_data;
				  $this->request->post['USER']=$this->user->getId();
						//	$this->load->library('soapcurl');
						//$soapcurl = new soapcurl($this->registry);
						//$datares= $soapcurl->CardStatus('CardStatus',$this->request->post,0); 
						 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->load->model('pos/'.$company);
						$datares = $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
						
						}
			$log->write($datares);
			if($datares=="1"){
		  $retval = $this->model_farmerrequest_farmerrequest->updateApprovedStatus($gid,$StatusId,$StatusName,$card_data);//updateApprovedStatus($gid,$StatusId,$StatusName);
                 }
                 
        }
		
		 $this->session->data['success'] ="Card(s) Approved Sucessfully !";
                  
	         $this->response->addHeader('Content-Type: application/json');
                 
                 $this->response->setOutput(json_encode($retval));
        //print_r($this->request->post);
        //return;
    }
}
