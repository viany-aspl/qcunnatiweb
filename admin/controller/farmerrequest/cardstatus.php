<?php
class ControllerFarmerrequestCardstatus extends Controller {
	private $error = array();

	public function index() {
		
		$this->document->setTitle("Card Status");

		$this->load->model('farmerrequest/farmerrequest');
        
		$this->getForm();
	}
        public function cardviewprint() {
		
		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['farmer_name'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		//farmer_name=RAMRANI&father_name=RAM SAHAY&qimage=../system/upload/qrimages/1101579924340.png&cname=view/image/DSCL.png&Village_level=PARSEHARA&Unit_level=AJBAPUR&Grower_Code_level=732100281&Card_Serial_Number_level=1101 5799 2434 0&qr_img=../system/upload/qrimages/1101579924340.png

		$data['farmer_name']=$this->request->get['farmer_name'];
		$data['father_name']=$this->request->get['father_name'];
		$data['qimage']=$this->request->get['qimage'];
		$data['cname']=$this->request->get['cname'];
		$data['Village_level']=$this->request->get['Village_level'];
		$data['Unit_level']=$this->request->get['Unit_level'];
		$data['Grower_Code_level']=$this->request->get['Grower_Code_level'];
		$data['Card_Serial_Number_level']=$this->request->get['Card_Serial_Number_level'];
		$data['qr_img']=$this->request->get['qr_img'];
		

		//$this->response->setOutput($this->load->view('farmerrequest/cardstatus_print.tpl', $data)); 
		 echo $html=$this->load->view('farmerrequest/cardstatus_print.tpl',$data);
		exit;
             require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             $mpdf = new mPDF('c','A3','','' , 0 , 0 , 25 , 10 , 5 , 7);
            
               $header='';
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->SetHTMLHeader($header, 'O', false);
                 
                $footer = '';

                $mpdf->setAutoBottomMargin = 'stretch';            
                $mpdf->SetHTMLFooter($footer);
                   
                $mpdf->SetDisplayMode('fullpage');
   
                $mpdf->list_indent_first_level = 0;
   
                $mpdf->WriteHTML($html);
               
                $filename=$data['Card_Serial_Number_level'].'.pdf';
               
                $mpdf->Output($filename,'D');
	}
       
           public function check()
        {                         
                  
                  $this->load->model('farmerrequest/farmerrequest');
				  
				 // print_r($this->request->get); exit;
                   
                  $data11= $this->model_farmerrequest_farmerrequest->getcardstatusdetail($this->request->get);
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
                      'VILLAGE_CODE'=> $data1['VILLAGE_CODE'],
					  'VILLAGE_NAME'=> $data1['VILLAGE_NAME'],
                      'CARD_STATUS_DESC'=>$data1['CARD_STATUS_DESC'],
                      'SID'=>$data1['SID'],
                      'CARD_SERIAL_NUMBER'=>$data1['CARD_SERIAL_NUMBER'],
                      'SITE'=>'MY'
                      );
 
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

		$this->response->setOutput($this->load->view('farmerrequest/cardstatus.tpl', $data));
	}
	
	
	public function getcardprint()
	{
		
    $data['heading_title'] ='Card Print';
	  $data['card_number']=$this->request->get['card_number'];
	  $data['CARD_QR_IMG']=$this->request->get['CARD_QR_IMG'];
	  $data['farmer_name']=$this->request->get['farmer_name'];
	  $data['father_name']=$this->request->get['father_name'];
	  $data['village']=$this->request->get['village'];
	  $data['unit']=$this->request->get['unit'];
	  $data['grower_id']=$this->request->get['grower_id'];
	  
	
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
	$this->response->setOutput($this->load->view('farmerrequest/cardprint.tpl', $data));	
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
	
       public function generateqr()
    {
		$log=new Log("CardgenerateQr-".date('Y-m-d').".log"); 
		$cid=$this->request->get['CardSerialNo']; 
		
		$this->load->model('farmerrequest/farmerrequest');
		$cadata= $this->model_farmerrequest_farmerrequest->getqrstring($cid);
		$log->write($cadata);
		$mcrypt=new MCrypt();
        $card_data="AC='01'"
                        . "UN='".($cadata["UNIT_ID"])."' "
                        . "CI='".($cadata["COMPANY_ID"])."'"
                        . "GC='".($mcrypt->encrypt($cadata["GROWER_ID"]))."' "
                        . "CSN='".($mcrypt->encrypt($cadata["CARD_SERIAL_NUMBER"]))."";
		
			
		$this->load->library('phpqrcode/qrlib');
                    $folder_name='qrimages';
                    $dir=DIR_UPLOAD.$folder_name;
                    if ( !file_exists($dir) ) {
                    $oldmask = umask(0);  // helpful when used in linux server  
                    mkdir ($dir, 0744);
                    }
	
                    $card_qr_img=$dir.'/'.$cid.'.png';
					$log->write($card_qr_img);

                    define('IMAGE_WIDTH',220);
                    define('IMAGE_HEIGHT',220);
                    QRcode::png(($card_data), $card_qr_img);
                    $card_image=$cid.'.png';
					$log->write($card_qr_img);
					echo '../system/upload/qrimages/'.$cid.'.png';
					
    }
	public function deleteqr()
	{
		$cid=$this->request->get['CardSerialNo']; 
		unlink($cid);
	} 
	public function getCardStatusFromDscl()
{
	
	$company="dscl";
	$this->request->post['CARD_SERIAL_NUMBER']= $this->request->get["card_number"];
	$this->request->post['CARD_GROWER_ID']=  $this->request->get["grower_id"];
	$this->request->post['CARD_UNIT']= $this->request->get["unit"];
	$this->request->post['USER']="0";
    $this->load->model('pos/'.$company);
	
	$datares = $this->{'model_pos_' . $company}->GetCardStatus('GetCardStatus',$this->request->post,0); 
	$this->response->setOutput(json_encode($datares));
}
       
}
