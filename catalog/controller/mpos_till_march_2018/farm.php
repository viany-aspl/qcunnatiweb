<?php

class Controllermposfarm extends Controller{

    public function adminmodel($model) {
      
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




function getImageText()
	{

		$log=new Log("calActivityfarm".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$fid=$mcrypt->decrypt($this->request->post['cropid']);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$results = $this->model_farm_farm->getImageText($fid,$mcrypt->decrypt($this->request->post['activityid']));
		$log->write($results);
		foreach ($results as $result) { 
			$data[] = array(
				'description'        => $result['description'],
                                'activityid'     => $mcrypt->encrypt($result['activityid']),
                                'activityname'    => $mcrypt->encrypt($result['activityname']),
				'cropcalendarname'         => $mcrypt->encrypt($result['cropcalendarname']),
				'cropcalendarid' => $mcrypt->encrypt($result['cropcalendarid']),
				'cropcalendaractivityid'	=> $mcrypt->encrypt($result['cropcalendaractivityid'])
				);
		}
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}
				
		}


function getActivityOnFarmID()
	{

		$log=new Log("calActivityfarm-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$fid=$mcrypt->decrypt($this->request->post['username']);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$results = $this->model_farm_farm->getActivityOnFarmID($fid);
		$log->write($results);
		foreach ($results as $result) { 
			$data[] = array(
				'farmid'      => $mcrypt->encrypt($result['farmid']),
				'farmerid'=> $mcrypt->encrypt($result['farmerid']),				
				'unit'        => $mcrypt->encrypt($result['unit']),
				'remarks'        => $mcrypt->encrypt($result['remarks']),
                               		 'activityid'     => $mcrypt->encrypt($result['activityid']),
                                		'appliedproduct'    => ($result['appliedproduct']),
				'dateconducted'         => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['dateconducted']))),
				'quantityapplied' => $mcrypt->encrypt($result['quantityapplied']),
				'farmactivityid'	=> $mcrypt->encrypt($result['farmactivityid'])
				);
		}
		
		$log->write($data);

			if(!empty($data))
			{

				$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));
			}
			else{
				$data[] = array(
				'farmid'      => $mcrypt->encrypt("0"),
				'farmerid'=> $mcrypt->encrypt("0"),				
				'unit'        => $mcrypt->encrypt("0"),
				'remarks'        => $mcrypt->encrypt("0"),
                               		 'activityid'     => $mcrypt->encrypt("0"),
                                		'appliedproduct'    => $mcrypt->encrypt("0"),
				'dateconducted'         => $mcrypt->encrypt("0"),
				'quantityapplied' => $mcrypt->encrypt("0"),
				'farmactivityid'	=> $mcrypt->encrypt("0")
				);
			$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));

			}
			
				
		}


function getFarmsCalendar()
	{

		$log=new Log("calfarm".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$fid=$mcrypt->decrypt($this->request->post['username']);
		$log->write("model=".$fid);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$results = $this->model_farm_farm->getFarmsCalendar($fid);
		$log->write($results);
		foreach ($results as $result) { 
			$data[] = array(
				'farmid'      => $mcrypt->encrypt($result['farmid']),
				'farmerid'=> $mcrypt->encrypt($result['farmerid']),
				'cropcalendarid'      => $mcrypt->encrypt($result['cropcalendarid']),
				'cropcalendarname'        => $mcrypt->encrypt($result['cropcalendarname']),
                                'activityid'     => $mcrypt->encrypt($result['activityid']),
                                'activityname'    => $mcrypt->encrypt($result['activityname']),
				'startdate'         => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['startdate']))),
				'enddate' => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['enddate']))),
				'farmcalendarid'	=> $mcrypt->encrypt($result['farmcalendarid'])
				);
		}
		$log->write($data); 
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}
				
		}

function currentfarm(){



		$log=new Log("farm".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$uid=$mcrypt->decrypt($this->request->post['username']);
		$sid=$mcrypt->decrypt($this->request->post['store_id']);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$seasonid="2";
		$results = $this->model_farm_farm->getCurrentFarms($uid,$seasonid);
		$log->write($results);
		foreach ($results as $result) { 
			$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($result['farmid']),
				'farmerid'=> $mcrypt->encrypt($result['farmerid']),
				'farmname'      => $mcrypt->encrypt($result['farmname']),
				'cropid'        => $mcrypt->encrypt($result['cropid']),
                                'seedsown'     => $mcrypt->encrypt($result['seedsown']),
                                'year'    => $mcrypt->encrypt($result['year']),
				'acreage'         => $mcrypt->encrypt($result['acreage']),
				'sowingdate' => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['sowingdate']))),
				'season'	=> $mcrypt->encrypt($result['season'])
				);
		}
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}		

}


function getseason(){

		$log=new Log("farm".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$seasonid="2";
		$year='2017';
		$results = $this->model_farm_farm->getseasons();
		$log->write($results);
		foreach ($results as $result) { 
			$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($result['id']),
				'farmerid'=> ($result['season'])
				);
		}		
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}		
}

function getseasonyear(){

		$log=new Log("farm".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$seasonid="2";
		$year='2017';
		$results = $this->model_farm_farm->getseasons();
		$log->write($results);
		
				foreach ($results as $result) { 
			$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($result['id']),
				'farmerid'=> $mcrypt->encrypt($result['season']),
				'date_added' =>$mcrypt->encrypt(date('Y'))
				);
		}
foreach ($results as $result) { 
			$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($result['id']),
				'farmerid'=> $mcrypt->encrypt($result['season']),
				'date_added' =>$mcrypt->encrypt(date('Y')-1)
				);
		}		
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}		

}

function getactivity()
{
	
$log=new Log("getactivity-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$seasonid="2";
		$year='2017';
		$results = $this->model_farm_farm->getactivity();
		$log->write($results);
		foreach ($results as $result) { 
			$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($result['activityid']),
				'farmerid'=> $result['activityname']
				);
		}
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}		

}

function getyear()
		{

		$log=new Log("farm".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$log->write("model");
		$seasonid="2";
		$year='2017';				
		$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($year-1),
				'farmerid'=> ($year-1)
				);

		$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($year),
				'farmerid'=> ($year)
				);

		
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}		
	}


function oldfarm(){



		$log=new Log("oldfarm-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$uid=$mcrypt->decrypt($this->request->post['username']);
		$sid=$mcrypt->decrypt($this->request->post['store_id']);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$seasonid=$mcrypt->decrypt($this->request->post['seasonid']);
		$year=$mcrypt->decrypt($this->request->post['year']);
		$log->write($year);
		$log->write($seasonid);
		$log->write($uid);

		$results = $this->model_farm_farm->getOlderFarms($uid,$seasonid,$year);
		$log->write($results);
		foreach ($results as $result) { 
			$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($result['farmid']),
				'farmerid'=> $mcrypt->encrypt($result['farmerid']),
				'farmname'      => $mcrypt->encrypt($result['farmname']),
				'cropid'        => $mcrypt->encrypt($result['cropid']),
                                'seedsown'     => $mcrypt->encrypt($result['seedsown']),
                                'year'    => $mcrypt->encrypt($result['year']),
				'acreage'         => $mcrypt->encrypt($result['acreage']),
				'sowingdate' => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['sowingdate']))),
				'season'	=> $mcrypt->encrypt($result['season'])
				);
		}
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}		

}

function CreateNewActivity()
{
		
		//create activity
		$log=new Log("newactivity-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$data=array();
		$data['activityid']=$mcrypt->decrypt($this->request->post['activity']);
		$data['dateconducted']=$mcrypt->decrypt($this->request->post['date']);
		$data['appliedproduct']=$this->request->post['product'];//$mcrypt->decrypt($this->request->post['product']);
		$data['quantityapplied']=$mcrypt->decrypt($this->request->post['quantity']);
		$data['unit']=$mcrypt->decrypt($this->request->post['unit']);
		$data['remarks']=$mcrypt->decrypt($this->request->post['comment']);
		$data['dateupdated']=date('Y-m-d');
		$data['farmerid']=$mcrypt->decrypt($this->request->post['farmerid']);
		$data['farmid']	=$mcrypt->decrypt($this->request->post['farmid']);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$farmid = $this->model_farm_farm->CreateNewFarmActivity($data);
		//$data['success']=$mcrypt->encrypt("Activity created successfully.");
		$data['success']="गतिविधि जोड़ी गयी ";
		$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($farmid),
				'farmerid'=> $mcrypt->encrypt($farmid)
				);		
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}			
}




function CreateNewFarm()
{
		$log=new Log("newfarm-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
$log->write('in cont');
		$log->write($this->request->post);
		$log->write($this->request->get);
		$data=array();
		$data['farmerid']=$mcrypt->decrypt($this->request->post['farmerid']);
		$data['farmname']=$mcrypt->decrypt($this->request->post['name']);
		$data['cropid']=$mcrypt->decrypt($this->request->post['crop']);
		$data['seedsown']=$mcrypt->decrypt($this->request->post['seed']);
		$data['year']=$mcrypt->decrypt($this->request->post['year']);
		$data['acreage']=$mcrypt->decrypt($this->request->post['acre']);
		$data['sowingdate']=$mcrypt->decrypt($this->request->post['sowingdate']);
		$data['season']=$mcrypt->decrypt($this->request->post['season']);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$farmid = $this->model_farm_farm->CreateNewFarm($data);
		if(!empty($farmid)){
		//$data['success']=$mcrypt->encrypt("Your farm  created with id ".$farmid);
		$data['success']='आपके खेत की जानकारी दर्ज हो गयी हैं ';

		$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($farmid),
				'farmerid'=> $mcrypt->encrypt($farmid)
				);
		}
		else{
			
		$data['success']=$mcrypt->encrypt("Sorry farm not created");

			}		
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}		
			
}



function CreateSoilTest()
{
		
		//create activity
		$log=new Log("newactivity-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$data=array();
		$data['farmid']=$mcrypt->decrypt($this->request->post['farmid']);
		$data['farmerid']=$mcrypt->decrypt($this->request->post['farmerid']);
		$data['texture'] =$mcrypt->decrypt($this->request->post['texture']);
		$data['ec']=$mcrypt->decrypt($this->request->post['ec']);
		$data['ocn']=$mcrypt->decrypt($this->request->post['ocn']);
		$data['p2o5'] =$mcrypt->decrypt($this->request->post['p2o5']);
		$data['k2o']=$mcrypt->decrypt($this->request->post['k2o']);
		$data['zinc']=$mcrypt->decrypt($this->request->post['zinc']);
		$data['ph']=$mcrypt->decrypt($this->request->post['ph']);
		$data['dateconducted']=$mcrypt->decrypt($this->request->post['dateconducted']);
		$data['datecollected'] =$mcrypt->decrypt($this->request->post['datecollected']);
		$this->adminmodel('farm/farm');
		$log->write("model");
		$farmid = $this->model_farm_farm->CreateSoilTest($data);
		$data['success']=$mcrypt->encrypt("Soil data submitted successfully.");
		$data['farms'][] = array(
				'farmid'      => $mcrypt->encrypt($farmid),
				'farmerid'=> $mcrypt->encrypt($farmid)
				);		
		if(!empty($data)){
		$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));}			
}
function getopportunity()
{		
		//create opportunity
		$log=new Log("opportunity-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
				$data=array();
		$data['Latt']=$mcrypt->decrypt($this->request->post['Latt']);
		$data['Long']=$mcrypt->decrypt($this->request->post['Long']);
		$data['CropID']=$mcrypt->decrypt($this->request->post['CropID']);
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->adminmodel('farm/farmop');
		if( !empty($data['Latt']) && !empty($data['Long']))
		{
			$results = $this->model_farm_farmop->getOpportunity($mcrypt->decrypt($this->request->post['username']),$data);

			$log->write($results);
			foreach ($results as $result) { 
				$data['ops'][] = array(
				'OpportunityID'	=>$mcrypt->encrypt($result['OpportunityID']),
				'PostedBy'      => $mcrypt->encrypt($result['PostedBy']),
				'PostedByName'=> $mcrypt->encrypt($result['PostedByName']),
				'CropID'      => $mcrypt->encrypt($result['CropID']),
				'Quantity'        => $mcrypt->encrypt($result['Quantity']),
                                                        'Unit'     => $mcrypt->encrypt($result['Unit']),
                                                        'Grade'    => $mcrypt->encrypt($result['Grade']),
				'ValidityDate'         => $mcrypt->encrypt($result['ValidityDate']),
				'PostedDate' => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['PostedDate']))),
				'Location'	=> $mcrypt->encrypt($result['Location']),
				'Longitude'	=> $mcrypt->encrypt($result['Longitude']),
				'Latitute'	=>$mcrypt->encrypt($result['Latitute']),
				'Status'	=>$mcrypt->encrypt($result['Status']),
				'Price'		=>$mcrypt->encrypt($result['Price']),
				'ImageCount'	=>$mcrypt->encrypt($result['imagecount']),
				'CropName'	=>$mcrypt->encrypt($result['name'])
				);
		}


}	

		if(!empty($data)){
		$this->response->setOutput(json_encode($data));}	


}
//op create
function Createopportunity()
{		
		//create opportunity
		$log=new Log("opportunity-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$data=array();
		$data['PostedBy']=$mcrypt->decrypt($this->request->post['PostedBy']);
		$data['PostedByName']=$mcrypt->decrypt($this->request->post['PostedByName']);
		$data['CropID']=$mcrypt->decrypt($this->request->post['CropID']);
		$data['Quantity']=$mcrypt->decrypt($this->request->post['Quantity']);
		$data['Unit']=$mcrypt->decrypt($this->request->post['Unit']);
		$data['Grade']=$mcrypt->decrypt($this->request->post['Grade']);
		$data['ValidityDate']=$mcrypt->decrypt($this->request->post['ValidityDate']);
		$data['PostedDate']=date('Y-m-d');
		$data['Location']=$mcrypt->decrypt($this->request->post['Location']);
		$data['Longitude']=$mcrypt->decrypt($this->request->post['Longitude']);
		$data['Latitute']=$mcrypt->decrypt($this->request->post['Latitute']);
		$data['Status']=$mcrypt->decrypt($this->request->post['Status']);
		$data['PercentComplete']=$mcrypt->decrypt($this->request->post['PercentComplete']);
		$data['Price']=$mcrypt->decrypt($this->request->post['Price']);
		$data['ImageCount']=$mcrypt->decrypt($this->request->post['ImageCount']);
							
		$this->adminmodel('farm/farmop');
		$log->write("model");
		if( !empty($data['Price']) && !empty($data['ValidityDate']))
{
		$farmid = $this->model_farm_farmop->CreateNewOpportunity($data);
		//$datas['success']=$mcrypt->encrypt("Farm data submitted successfully.");
		$datas['success']="आपकी जानकारी दर्ज हो गयी हैं";

		$datas['farms']=$mcrypt->encrypt($farmid);
}		else{
		$datas['success']=$mcrypt->encrypt("Farm data Error.");
}
		if(!empty($datas)){
		$this->response->setOutput(json_encode($datas));}			
}



}
?>