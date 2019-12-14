<?php
class Controllermposqrstring extends Controller {


    public function adminmodel($model) 
	{
      
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

	 public function generateqrString()
    {
		$log=new Log("CardgenerateQrString-".date('Y-m-d').".log"); 			
		  $log->write("generateqrString call");	
		$this->adminmodel('farmerrequest/farmerrequest');
		//echo "ndsjv";
		
		$cardserial= $this->model_farmerrequest_farmerrequest->getcardno($cid);
		$co=COUNT($cardserial);
///print_r($cardserial);		exit;
		
		foreach($cardserial as $cid2)
		{	
		
		$cadata= $this->model_farmerrequest_farmerrequest->getqrstring($cid2['card_no']);
	//	print_r($cadata); exit;
		$mcrypt=new MCrypt();
			$card_data="AC='01'"
                        . "UN='".($cadata["UNIT_ID"])."' "
                        . "CI='".($cadata["COMPANY_ID"].$cadata["COMPANY_ID"])."'"
                        . "GC='".($mcrypt->encrypt($cadata["GROWER_ID"]))."' "
                        . "CSN='".($mcrypt->encrypt($cadata["CARD_SERIAL_NUMBER"]))."";
						//echo $card_data;
						if((!empty($cadata["CARD_SERIAL_NUMBER"])) && (!empty($cadata["GROWER_ID"])))
						{
							$this->model_farmerrequest_farmerrequest->updateqrstring($cid2['card_no'],$card_data,$cadata["UNIT_ID"]);
						}
	    }	

	
	}

}