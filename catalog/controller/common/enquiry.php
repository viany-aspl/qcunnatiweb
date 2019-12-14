<?php
class ControllerCommonEnquiry extends Controller {
	public function index() {
		$ProdId=$this->request->post['ProdId'];
                $ProdName=$this->request->post['ProdName'];
                $QryMob=$this->request->post['QryMob'];
                $this->load->model('enquiry/enquiry');
                             
                $results = $this->model_enquiry_enquiry->postMessage($ProdId,$ProdName,$QryMob);
                if($results==1){
                    $this->load->library('sms');
                    $sms=new sms($this->registry);
                    $customer_info="";
                    //$this->model_common_enquiry->sendsms($QryMob,'',$customer_info);
                    //$this->sms->sendsms($QryMob,"3",$customer_info );
                    $sms->sendsms($QryMob,"3",$customer_info );
                    echo 'success';
                }
                else 
                {
                  echo 'info';
                }
                }
                
	}
        
        
