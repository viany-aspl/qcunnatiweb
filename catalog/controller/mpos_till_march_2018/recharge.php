<?php

class Controllermposrecharge extends Controller{

   public $client_key="s924phas9zvofx76ich2iwc0nvlsnqds";
   public $user_id="10325";
   public $outlet_id="10019567";

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
public function rechargetest() {
                        $mcrypt=new MCrypt();                           
                        $log=new Log("recharge-".date('Y-m-d').".log");
	          $log->write('rechargetest called');		
                        $log->write($this->request->get);
                        $log->write($this->request->post);
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id']; 		
			} 
		else if (isset($this->request->post['order_id'])) {
				$order_id = $this->request->post['order_id'];  		
			} 
                        else
                        {
                            $order_id='';
                        }
                        if (isset($this->request->get['store_id'])) {
				$store_id = $this->request->get['store_id']; 		
			}
		else if (isset($this->request->post['store_id'])) {
				$store_id = $this->request->post['store_id']; 		
			}
                        else
                        {
                            $store_id='';
                        }
                        if (isset($this->request->get['scheme_id'])) {
				$scheme_id = $this->request->get['scheme_id']; 		
                        }
                        else
                        {
                            $scheme_id=0;
                        }
                        if (isset($this->request->get['muid'])) {
				$imei = $this->request->get['muid']; 		
                        }
                        else
                        {
                            $imei='';
                        }
                        
                        if (isset($this->request->get['recharge_amount'])) {
				$recharge_amount = $this->request->get['recharge_amount']; 		
                        }
                        else
                        {
                            $recharge_amount=0;
                        }
			if (isset($this->request->get['mobile'])) {
				$mobile = $this->request->get['mobile']; 
			} 
			if (isset($this->request->post['products'])) 
			{
				//products
				$prdstr =$this->request->post['products']; 
				$prds=json_decode( $mcrypt->decrypt($prdstr),true);
			}
                        else
                        {
                             $prds=array(array('product_id' => 0,'product_quantity'=>0));
                        }
                        
                        $this->load->library('recharge');
                        $recharge=new recharge($this->registry);
                        $recharge_amount=$recharge->get_scheme_amount($scheme_id);
			
                       // $recharge_amount=$recharge_amount["recharge_amount"];
                        $log->write($recharge_amount);
                        //$recharge->rechargemain($mobile,"7",$prds); 
                        //$recharge->rechargemain($mobile,$recharge_amount,$order_id,$store_id,$product_id,$product_quantity,$scheme_id);
                        if(($mobile!="") && ($recharge_amount>0))
                        {
                            $log->write('send for recharge');
                            echo $recharge->rechargemain($mobile,$recharge_amount,$order_id,$store_id,'','',$scheme_id,$imei);
                        }
                        else {
                             $log->write('scheme is not available');
                        }
                         
                        
}
public function recharge() {
                            $mcrypt=new MCrypt();                           
                            $log=new Log("recharge-func-".date('Y-m-d').".log");		
                            $log->write($this->request->get);
   $log->write($this->request->post);
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id']; 		
			} 
                        if (isset($this->request->get['store_id'])) {
				$store_id = $this->request->get['store_id']; 		
			}
			if (isset($this->request->get['mobile'])) {
				$mobile = $this->request->get['mobile']; 
			} 
			if (isset($this->request->post['products'])) 
			{
				//products
				$prdstr =$this->request->post['products']; 
				$prds=json_decode( $mcrypt->decrypt($prdstr),true);
			}
                         else
                            {
                             $prds=array(array('product_id' => 0,'product_quantity'=>0));
                            }                           
                            
                         
		   $log->write($prds);

		$this->adminmodel('recharge/recharge');

		$recharge_amount=0;

                            $get_products=$this->model_recharge_recharge->get_master_products($store_id);
		foreach($get_products as $get_product)
                            {
                               $eligible_product[]=$get_product["product_id"];
                            }
                            

                            foreach($prds as $prd)
	              {	
		  $log->write($prd['product_id']);
		  $log->write($prd['product_quantity']);
                              $product_id=$prd['product_id'];
                              //$prd['product_quantity'];
                              if (in_array($prd['product_id'], $eligible_product))
		  {
		   
                                 $get_product_recharge_quantity=$this->model_recharge_recharge->get_product_recharge_quantity($product_id,$store_id,date('Y-m-d'));
 		$log->write($get_product_recharge_quantity);
	                   if(($prd['product_quantity']>=$get_product_recharge_quantity["product_quantity"]) && (count($get_product_recharge_quantity)>0)) /////////// quantity is greater then or equal eligible quantity
		     {
                                         $log->write( "quantity is greater then or equal eligible quantity so go to next");
                                         $get_recharge_scheme_status=$this->model_recharge_recharge->get_recharge_scheme_status($get_product_recharge_quantity["scheme_id"],$mobile);
                                         if(count($get_recharge_scheme_status)>0)
                                         {
                                           $log->write( "already get the recharge for this scheme : ".$get_product_recharge_quantity["scheme_name"]." for mobile : ".$mobile);
                                         }
                                         else
                                         {
                                         $log->write( "first recharge for this scheme so go to next");
                                         $recharge_amount=$get_product_recharge_quantity["recharge_amount"];
                                         $product_id=$prd['product_id'];
                                         $product_quantity=$prd['product_quantity'];
                                         $scheme_id=$get_product_recharge_quantity["scheme_id"];
                                         }
                                 }
                                 else//////quantity is less the eligible quantity
                                 {
                                     $log->write( "quantity is less then eligible quantity or scheme ends");
                                  }
		  }
                              else//////////this product is not register for the recharge in master table
                              {
                                
                                $log->write( "this product is not register for the recharge in master table");
                              }
		}

                           ////////////if all condition are true the send for recharge///////////////////////
	              $success_status=0;
		if($recharge_amount>0) //////////then call the recharge api
		{
                                 $opertor=$this->GetOperator($mobile);
                                 $opertor_json=json_decode($opertor);
                                 $op_error_code=$opertor_json->errorCode;
                                 if($op_error_code=="")
                                 {
			$op_error_code=$opertor_json->ErrorCode;
                                 }
		     $error_code=array(1,13);
                                 if(!in_array($op_error_code, $error_code))/////////////////if there is no error in getting the operator code
                                 {
                                   $operator_name=json_decode(@$opertor)[0]->operator_name;
                                   $operator_code=json_decode(@$opertor)[0]->operator_code;
                                   $log->write( $operator_code."-".$operator_name); 
                                 
                                  if($operator_code=="28") ///////////send to airtel prepaid
                                  {
                                          
                                          $Rec_Res=$this->PreRechargeAir($mobile,$operator_code,$recharge_amount);
			
			$RecRes=json_decode($Rec_Res);
			$ResErr=$RecRes->errorCode; //12 Try after 30 Mins
			$ResErrMsg=$RecRes->message;
			$ResAmt=$RecRes->charged_amount;
			$ResCom=$RecRes->commission;
			$ResDT=$RecRes->datetime; //Recharge Datetime
			$ResNum=$RecRes->msisdn; //Recharge Number
			$ResNwOc=$RecRes->operator_code; //Recharge Nw Opt Code
			$ResNw=$RecRes->operator_name; //Recharge Nw Opt Name
			$ResNwTransID=$RecRes->opr_transid; //Recharge Nw Opt TransID
			$ResRecCode=$RecRes->response_code; //Recharge Rocket Response Code
			$ResRocTransID=$RecRes->rocket_trans_id;//Recharge Rocket TransID
			$ResSevTyp=$RecRes->service_family; //Recharge Type Prepaid/Postpaid
			$ResSerSts=$RecRes->status;
			//Recharge Status Success Pending Review Refund Archived
			$ResTransAmt=$RecRes->trans_amount; //Recharge Request Amount
			$rocket_tbl_id=$this->model_recharge_recharge->insertIntoRechargeTransRocket($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt);
                        if($ResErr==229)
			{
			   //$success_status=2;
			   //$ResSerSts=$ResErrMsg;
                        $Rec_Res=$this->PostRecharge($mobile,$operator_code,$recharge_amount);
			//echo $Rec_Res=$this->test();exit;
                        $RecRes=json_decode($Rec_Res);
			$ResErr229=$RecRes->errorCode; //12 Try after 30 Mins
			$ResErrMsg=$RecRes->message;
			$ResAmt=$RecRes->charged_amount;
			$ResCom=$RecRes->commission;
			$ResDT=$RecRes->datetime; //Recharge Datetime
			$ResNum=$RecRes->msisdn; //Recharge Number
			$ResNwOc=$RecRes->operator_code; //Recharge Nw Opt Code
			$ResNw=$RecRes->operator_name; //Recharge Nw Opt Name
			$ResNwTransID=$RecRes->opr_transid; //Recharge Nw Opt TransID
			$ResRecCode=$RecRes->response_code; //Recharge Rocket Response Code
			$ResRocTransID=$RecRes->rocket_trans_id;//Recharge Rocket TransID
			$ResSevTyp=$RecRes->service_family; //Recharge Type Prepaid/Postpaid
			$ResSerSts=$RecRes->status;
			//Recharge Status Success Pending Review Refund Archived
			$ResTransAmt=$RecRes->trans_amount; //Recharge Request Amount
                        if($ResErr229!="")
                        {
                           $success_status=2;
			   $ResSerSts=$ResErrMsg;
                        }
                        
			}
                        if($ResErr==233)
			{
			   $success_status=2;
			   $ResSerSts=$ResErrMsg;
			}
                                          if($ResErr==235)
			{
			   $success_status=2;
                                             $ResSerSts=$ResErrMsg;
			}
                                          if($ResErr!='')
			{
			   $success_status=2;
                                             $ResSerSts=$ResErrMsg;
			}
                                  }
                                  
                                  else ///////////for all others////////////////
                                  { 
                                          $Rec_Res=$this->PreRecharge($mobile,$operator_code,$recharge_amount);
			//echo $Rec_Res=$this->test();exit;
                                          $RecRes=json_decode($Rec_Res);
			$ResErr=$RecRes->errorCode; //12 Try after 30 Mins
			$ResErrMsg=$RecRes->message;
			$ResAmt=$RecRes->charged_amount;
			$ResCom=$RecRes->commission;
			$ResDT=$RecRes->datetime; //Recharge Datetime
			$ResNum=$RecRes->msisdn; //Recharge Number
			$ResNwOc=$RecRes->operator_code; //Recharge Nw Opt Code
			$ResNw=$RecRes->operator_name; //Recharge Nw Opt Name
			$ResNwTransID=$RecRes->opr_transid; //Recharge Nw Opt TransID
			$ResRecCode=$RecRes->response_code; //Recharge Rocket Response Code
			$ResRocTransID=$RecRes->rocket_trans_id;//Recharge Rocket TransID
			$ResSevTyp=$RecRes->service_family; //Recharge Type Prepaid/Postpaid
			$ResSerSts=$RecRes->status;
			//Recharge Status Success Pending Review Refund Archived
			$ResTransAmt=$RecRes->trans_amount; //Recharge Request Amount
                                          
			if($ResErr==229)/////////if postpaid
			{
			   //$success_status=2;
			   //$ResSerSts=$ResErrMsg;
                        $Rec_Res=$this->PostRecharge($mobile,$operator_code,$recharge_amount);
			//echo $Rec_Res=$this->test();exit;
                        $RecRes=json_decode($Rec_Res);
			$ResErr229=$RecRes->errorCode; //12 Try after 30 Mins
			$ResErrMsg=$RecRes->message;
			$ResAmt=$RecRes->charged_amount;
			$ResCom=$RecRes->commission;
			$ResDT=$RecRes->datetime; //Recharge Datetime
			$ResNum=$RecRes->msisdn; //Recharge Number
			$ResNwOc=$RecRes->operator_code; //Recharge Nw Opt Code
			$ResNw=$RecRes->operator_name; //Recharge Nw Opt Name
			$ResNwTransID=$RecRes->opr_transid; //Recharge Nw Opt TransID
			$ResRecCode=$RecRes->response_code; //Recharge Rocket Response Code
			$ResRocTransID=$RecRes->rocket_trans_id;//Recharge Rocket TransID
			$ResSevTyp=$RecRes->service_family; //Recharge Type Prepaid/Postpaid
			$ResSerSts=$RecRes->status;
			//Recharge Status Success Pending Review Refund Archived
			$ResTransAmt=$RecRes->trans_amount; //Recharge Request Amount
                        if($ResErr229!="")
                        {
                           $success_status=2;
			   $ResSerSts=$ResErrMsg;
                        }
                        
                        
			}
                        if($ResErr==233)
			{
			   $success_status=2;
			   $ResSerSts=$ResErrMsg;
			}
                                          if($ResErr==235)
			{
			   $success_status=2;
                                             $ResSerSts=$ResErrMsg;
			}
                                          if($ResErr!='')
			{
			   $success_status=2;
                                             $ResSerSts=$ResErrMsg;
			}
                        $rocket_tbl_id=$this->model_recharge_recharge->insertIntoRechargeTransRocket($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt);
                                  }
                                  $post_data=array(
                              	 'order_id'=>$order_id,
                               	 'store_id'=>$store_id,
                               	 'mobile'	     => $mobile,
                              	 'product_id' => $product_id,
                              	 'product_quantity' => $product_quantity,
                               	 'recharge_amount' => $recharge_amount,
			 'scheme_id' => $scheme_id,
                                           'status' => $success_status,
                                           'rocket_trans_id' => $ResRocTransID,
                                           'rocket_err_code' =>$ResErr,
                                           'rocket_tbl_id' => $rocket_tbl_id 
		      );
                                  $recharge_id=$this->model_recharge_recharge->insertIntoRechargeTrans($post_data,$operator_name,$operator_code);
                	      $log->write($recharge_id);
		      if($success_status!=2)///////////if status is pending or success then send sms
		      {
			  
                                            $log->write("if status is pending or success then send sms");
		                //send sms
                                           $this->load->library('sms');
                                           $sms=new sms($this->registry);
			 $sms->sendsms($mobile,"7",$prds);   
			 //exit;
                                            
			  echo $recharge_id;
		      }
		   }
                               else/////////////// when operator not found for the mobile
                               {
                                  $post_data=array(
                              	 'order_id'=>$order_id,
                               	 'store_id'=>$store_id,
                               	 'mobile'	     => $mobile,
                              	 'product_id' => $product_id,
                              	 'product_quantity' => $product_quantity,
                               	 'recharge_amount' => $recharge_amount,
			 'scheme_id' => $scheme_id,
                                           'status' => 2,
                                           'rocket_trans_id' => '',
                                           'rocket_err_code' =>$ResErr,
			 'rocket_tbl_id' => $rocket_tbl_id
		      );
                                  $recharge_id=$this->model_recharge_recharge->insertIntoRechargeTrans($post_data,$operator_name,$operator_code);
                	      $log->write($recharge_id);
                               }
                                    

                            }
		
			
                            
		

		//$this->response->setOutput(json_encode($recharge_id));
	}
        function recharge_re_hit_get()
        {
            $log=new Log("recharge-re-hit-".date('Y-m-d').".log");	
            $log->write('come in the recharge_re_hit_get');
            $log->write($this->request->get);

            $mobile=$this->request->get['mobile'];
            $operator_code=$this->request->get['operator_code'];
            $recharge_amount=$this->request->get['recharge_amount'];
            $transtblid=$this->request->get['transtblid'];
            $rockettranstblid=$this->request->get['rockettranstblid'];
            $success_status=$this->request->get['success_status'];
            $pre_post=$this->request->get['pre_post'];
            echo $this->recharge_re_hit($mobile,$operator_code,$recharge_amount,$transtblid,$rockettranstblid,$success_status,$pre_post);   
        }
/////////////////////////////////recharge re-hit start here////////////////////////////////////////////////////
function recharge_re_hit($mobile,$operator_code,$recharge_amount,$transtblid,$rockettranstblid,$success_status,$pre_post)
{
    
    $mcrypt=new MCrypt();   
    
                            $log=new Log("recharge-re-hit-".date('Y-m-d').".log");		
                            $log->write($mobile.",".$operator_code.",".$recharge_amount.",".$transtblid.",".$rockettranstblid);
$this->adminmodel('recharge/recharge');
$this->adminmodel('recharge/rechargereport');
                            ///////checking current recharge status/////////
$ResRocTransIDo=$rockettranstblid;
$transid=$transtblid;

///////////////curl/////////////////////
$surl="https://ws.rocketinpocket.com/recharge/v1/recharge/".$ResRocTransIDo."?client_id=".$this->user_id."&client_key=".$this->client_key;
//echo $surl;

$log->write($surl);



    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$surl);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl_handle,CURLOPT_HTTPHEADER,$headers);
  	$buffer = curl_exec($curl_handle);
	if($buffer === false)
	{
		echo 'Curl error: ' . curl_error($curl_handle);
                            $log->write(curl_error($curl_handle));
	}
	else
	{
		$log->write($buffer);
                $buffer=json_decode($buffer);
                $log->write($buffer);
		$resstatus=$buffer->status;
		//print_r($resstatus);
                           
	}

curl_close($curl_handle);

///////////////curl end//////////////////////
//print_r($ResRocTransID);
//$resstatus="Pending";
if(($resstatus=="Pending") || ($resstatus=="Success"))
{
$log->write('if res is pending or success');
$update_status=$this->model_recharge_rechargereport->update_recharge_status_data($ResRocTransIDo,$transid,$resstatus);
echo $resstatus;

} 
elseif($buffer->errorCode!="")
{
    $log->write('come in else if errorcode is not empty');
    //print_r($buffer);
    echo $buffer->message;
}

else
{
    $log->write('come in the else of pending and succes and if errorcode is empty');
                $ResSerSts="Pending";   
                $lod->write($pre_post);
                
                if($pre_post=="postpaid") 
                {       $log->write('come in the postpaid for re-recharge');
                        $Rec_Res=$this->PostRecharge($mobile,$operator_code,$recharge_amount);
			//echo $Rec_Res=$this->test();exit;
                        $RecRes=json_decode($Rec_Res);
			$ResErr=$RecRes->errorCode; //12 Try after 30 Mins
			$ResErrMsg=$RecRes->message;
			$ResAmt=$RecRes->charged_amount;
			$ResCom=$RecRes->commission;
			$ResDT=$RecRes->datetime; //Recharge Datetime
			$ResNum=$RecRes->msisdn; //Recharge Number
			$ResNwOc=$RecRes->operator_code; //Recharge Nw Opt Code
			$ResNw=$RecRes->operator_name; //Recharge Nw Opt Name
			$ResNwTransID=$RecRes->opr_transid; //Recharge Nw Opt TransID
			$ResRecCode=$RecRes->response_code; //Recharge Rocket Response Code
			$ResRocTransID=$RecRes->rocket_trans_id;//Recharge Rocket TransID
			$ResSevTyp=$RecRes->service_family; //Recharge Type Prepaid/Postpaid
			$ResSerSts=$RecRes->status;
			//Recharge Status Success Pending Review Refund Archived
			$ResTransAmt=$RecRes->trans_amount; //Recharge Request Amount
                        
                        if($ResErr==233)
			{
			   $success_status=2;
			   $ResSerSts=$ResErrMsg;
			}
                                          if($ResErr==235)
			{
			   $success_status=2;
                                             $ResSerSts=$ResErrMsg;
			}
                        if($ResErr!='')
			{
			   $success_status=2;
                           $ResSerSts=$ResErrMsg;
			} 
                }
                else///////////for prepaid
                {
                    $lod->write($pre_post);
                if($operator_code=="28") ///////////send to airtel prepaid
                {
                        $log->write('come in the airtel');                      
                        $Rec_Res=$this->PreRechargeAir($mobile,$operator_code,$recharge_amount);
			$log->write('come in the airtel 2'); 
			$RecRes=json_decode($Rec_Res);
                                          $log->write($RecRes);
			$ResErr=$RecRes->errorCode; //12 Try after 30 Mins
			$ResErrMsg=$RecRes->message;
			$ResAmt=$RecRes->charged_amount;
			$ResCom=$RecRes->commission;
			$ResDT=$RecRes->datetime; //Recharge Datetime
			$ResNum=$RecRes->msisdn; //Recharge Number
			$ResNwOc=$RecRes->operator_code; //Recharge Nw Opt Code
			$ResNw=$RecRes->operator_name; //Recharge Nw Opt Name
			$ResNwTransID=$RecRes->opr_transid; //Recharge Nw Opt TransID
			$ResRecCode=$RecRes->response_code; //Recharge Rocket Response Code
			$ResRocTransID=$RecRes->rocket_trans_id;//Recharge Rocket TransID
			$ResSevTyp=$RecRes->service_family; //Recharge Type Prepaid/Postpaid
			$ResSerSts=$RecRes->status;
			//Recharge Status Success Pending Review Refund Archived
			$ResTransAmt=$RecRes->trans_amount; //Recharge Request Amount
			$rocket_tbl_id=$this->model_recharge_recharge->insertIntoRechargeTransRocketReHit($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt,$transtblid,$rockettranstblid);
                        		if($ResErr==229)
			{
			   //$success_status=2;
			   //$ResSerSts=$ResErrMsg;
                        $Rec_Res=$this->PostRecharge($mobile,$operator_code,$recharge_amount);
			//echo $Rec_Res=$this->test();exit;
                        $RecRes=json_decode($Rec_Res);
			$ResErr229=$RecRes->errorCode; //12 Try after 30 Mins
			$ResErrMsg=$RecRes->message;
			$ResAmt=$RecRes->charged_amount;
			$ResCom=$RecRes->commission;
			$ResDT=$RecRes->datetime; //Recharge Datetime
			$ResNum=$RecRes->msisdn; //Recharge Number
			$ResNwOc=$RecRes->operator_code; //Recharge Nw Opt Code
			$ResNw=$RecRes->operator_name; //Recharge Nw Opt Name
			$ResNwTransID=$RecRes->opr_transid; //Recharge Nw Opt TransID
			$ResRecCode=$RecRes->response_code; //Recharge Rocket Response Code
			$ResRocTransID=$RecRes->rocket_trans_id;//Recharge Rocket TransID
			$ResSevTyp=$RecRes->service_family; //Recharge Type Prepaid/Postpaid
			$ResSerSts=$RecRes->status;
			//Recharge Status Success Pending Review Refund Archived
			$ResTransAmt=$RecRes->trans_amount; //Recharge Request Amount
                        if($ResErr229!="")
                        {
                           $success_status=2;
			   $ResSerSts=$ResErrMsg;
                        }
                        
			}
                                         if($ResErr==233)
			{
			   $success_status=2;
			   $ResSerSts=$ResErrMsg;
			}
                                          if($ResErr==235)
			{
			   $success_status=2;
                                             $ResSerSts=$ResErrMsg;
			}
                                          if($ResErr!='')
			{
			   $success_status=2;
                                             $ResSerSts=$ResErrMsg;
			}
                    }
                                  
                    else ///////////for all others////////////////
                    {   $log->write('come in others'); 
                        $Rec_Res=$this->PreRecharge($mobile,$operator_code,$recharge_amount);
			//echo $Rec_Res=$this->test();exit;
                        $log->write('come in others 2'); 
                        $RecRes=json_decode($Rec_Res);
                        $log->write($RecRes);
			$ResErr=$RecRes->errorCode; //12 Try after 30 Mins
			$ResErrMsg=$RecRes->message;
			$ResAmt=$RecRes->charged_amount;
			$ResCom=$RecRes->commission;
			$ResDT=$RecRes->datetime; //Recharge Datetime
			$ResNum=$RecRes->msisdn; //Recharge Number
			$ResNwOc=$RecRes->operator_code; //Recharge Nw Opt Code
			$ResNw=$RecRes->operator_name; //Recharge Nw Opt Name
			$ResNwTransID=$RecRes->opr_transid; //Recharge Nw Opt TransID
			$ResRecCode=$RecRes->response_code; //Recharge Rocket Response Code
			$ResRocTransID=$RecRes->rocket_trans_id;//Recharge Rocket TransID
			$ResSevTyp=$RecRes->service_family; //Recharge Type Prepaid/Postpaid
			$ResSerSts=$RecRes->status;
			//Recharge Status Success Pending Review Refund Archived
			$ResTransAmt=$RecRes->trans_amount; //Recharge Request Amount
                        $rocket_tbl_id=$this->model_recharge_recharge->insertIntoRechargeTransRocketReHit($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt,$transtblid,$rockettranstblid);
			if($ResErr==229)
			{
			   //$success_status=2;
			   //$ResSerSts=$ResErrMsg;
                                          $Rec_Res=$this->PostRecharge($mobile,$operator_code,$recharge_amount);
			//echo $Rec_Res=$this->test();exit;
                                          $RecRes=json_decode($Rec_Res);
                                          $log->write($RecRes);
			$ResErr229=$RecRes->errorCode; //12 Try after 30 Mins
			$ResErrMsg=$RecRes->message;
			$ResAmt=$RecRes->charged_amount;
			$ResCom=$RecRes->commission;
			$ResDT=$RecRes->datetime; //Recharge Datetime
			$ResNum=$RecRes->msisdn; //Recharge Number
			$ResNwOc=$RecRes->operator_code; //Recharge Nw Opt Code
			$ResNw=$RecRes->operator_name; //Recharge Nw Opt Name
			$ResNwTransID=$RecRes->opr_transid; //Recharge Nw Opt TransID
			$ResRecCode=$RecRes->response_code; //Recharge Rocket Response Code
			$ResRocTransID=$RecRes->rocket_trans_id;//Recharge Rocket TransID
			$ResSevTyp=$RecRes->service_family; //Recharge Type Prepaid/Postpaid
			$ResSerSts=$RecRes->status;
			//Recharge Status Success Pending Review Refund Archived
			$ResTransAmt=$RecRes->trans_amount; //Recharge Request Amount
                        if($ResErr229!="")
                        {
                           $success_status=2;
			   $ResSerSts=$ResErrMsg;
                        }
                        
			}
                        if($ResErr==233)
			{
			   $success_status=2;
			   $ResSerSts=$ResErrMsg;
			}
                                          if($ResErr==235)
			{
			   $success_status=2;
                                             $ResSerSts=$ResErrMsg;
			}
                                          if($ResErr!='')
			{
			   $success_status=2;
                                             $ResSerSts=$ResErrMsg;
			}
                    }
                }
     if($success_status!="2")
     {
$update_status=$this->model_recharge_rechargereport->update_recharge_status_data_re_hit($ResNwTransIDo,$transid,$ResSerSts);
echo $ResSerSts; 
     } 
 else 
 {
    echo "There is some techincial error. please try after some time.";     
 }
                    
}

}
////////////////////////////////////recharge re-hit end here////////
public function recharge_status() {
                                     
 $log=new Log("recharge-status-by-call-center-".date('Y-m-d').".log");  		
                            
		
$ResRocTransID=$this->request->get['ResRocTransID'];
$transid=$this->request->get['transid'];

$log->write($ResRocTransID.",".$transid);

$this->adminmodel('recharge/rechargereport');
///////////////curl/////////////////////
$surl="https://ws.rocketinpocket.com/recharge/v1/recharge/".$ResRocTransID."?client_id=".$this->user_id."&client_key=".$this->client_key;
//echo $surl;

$log->write($surl);



    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$surl);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl_handle,CURLOPT_HTTPHEADER,$headers);
  	$buffer = curl_exec($curl_handle);
	if($buffer === false)
	{
		echo 'Curl error: ' . curl_error($curl_handle);
                            $log->write(curl_error($curl_handle));
	}
	else
	{
		$log->write($buffer->Success);
                            $buffer=json_decode($buffer);
		//$resstatus=$buffer->status;
                            $log->write($buffer);
		if($buffer->errorCode!="")
                	{
                               $resstatus='Pending';
                	}
               	 else
                	{
                                   $resstatus=$buffer->status;
	                     $update_status=$this->model_recharge_rechargereport->update_recharge_status_data($ResRocTransID,$transid,$resstatus);
                	}
                echo $resstatus;           
	}

curl_close($curl_handle);

exit;

}
///////////////recharge auto check status start here/////////////////////////////////////

public function recharge_checkstatus() {
                                     
                            $log=new Log("recharge-check_status-".date('Y-m-d').".log");		
                            
		$this->adminmodel('recharge/rechargereport');
		$pendingresults=$this->model_recharge_rechargereport->get_pending_data();
		$log->write($pendingresults);
foreach($pendingresults as $pendingresult)
{

$ResRocTransID=$pendingresult["ResRocTransID"];
$transid=$pendingresult["transid"];

///////////////curl/////////////////////
$surl="https://ws.rocketinpocket.com/recharge/v1/recharge/".$ResRocTransID."?client_id=".$this->user_id."&client_key=".$this->client_key;
//echo $surl;

$log->write($surl);



    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$surl);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl_handle,CURLOPT_HTTPHEADER,$headers);
  	$buffer = curl_exec($curl_handle);
	if($buffer === false)
	{
		echo 'Curl error: ' . curl_error($curl_handle);
                            $log->write(curl_error($curl_handle));
	}
	else
	{
		$log->write($buffer->Success);
                            $buffer=json_decode($buffer);
		$resstatus=$buffer->status;
		//print_r($resstatus);
                           
	}

curl_close($curl_handle);

///////////////curl end//////////////////////
//print_r($ResRocTransID);
//$resstatus="Pending";

$update_status=$this->model_recharge_rechargereport->update_recharge_status_data($ResRocTransID,$transid,$resstatus);

}
exit;

}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function GetOperator($sender)
{
$log=new Log("recharge-".date('Y-m-d').".log");
$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/json';

$surl="https://ws.rocketinpocket.com/recharge/v1/operators/mobile/+91".$sender."?client_id=".$this->user_id."&client_key=".$this->client_key;

$log->write($surl);

    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$surl);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl_handle,CURLOPT_HTTPHEADER,$headers);
  	$buffer = curl_exec($curl_handle);
	if($buffer === false)
	{
		return 'Curl error: ' . curl_error($curl_handle);
                            $log->write(curl_error($curl_handle));
	}
	else
	{
		$log->write($buffer);
		return $buffer;
                           
	}

curl_close($curl_handle);
}
// GetOperator($sender);
//************************************MSG  FUNCTION END*****************************************//
//*************************** REQUEST POSTPAID RECHARGE FUNCTION ******************************//
function PostRecharge($sender,$code,$amt)
{
$log=new Log("recharge-".date('Y-m-d').".log");
$log->write("Postpaid");

$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/json';
$surl="https://ws.rocketinpocket.com/recharge/v1/recharge/mobile/bill?client_id=".$this->user_id."&client_key=".$this->client_key."&msisdn=".$sender."&operator_code=".$code."&amount=".$amt."&live=true";
$log->write($surl);


$curl_handle=curl_init();
curl_setopt($curl_handle,CURLOPT_URL,$surl);
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl_handle,CURLOPT_HTTPHEADER,$headers);
$buffer = curl_exec($curl_handle);
if($buffer === false)
{
echo 'Curl error: ' . curl_error($curl_handle);
$log->write(curl_error($curl_handle));
}
else
{
$log->write($buffer);
return $buffer;

}
curl_close($curl_handle);

}
//*************************** REQUEST POSTPAID RECHARGE FUNCTION END ***************************//
//****************************** REQUEST PREPAID RECHARGE FUNCTION *****************************//
function PreRecharge($sender,$code,$amt)
{

$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/json';
$surl="https://ws.rocketinpocket.com/recharge/v1/recharge/mobile?client_id=".$this->user_id."&client_key=".$this->client_key."&msisdn=".$sender."&operator_code=".$code."&amount=".$amt."&live=true";
$log=new Log("recharge-".date('Y-m-d').".log");
$log->write("Non-Airtel: ".$surl);

    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$surl);
	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl_handle,CURLOPT_HTTPHEADER,$headers);
    $buffer = curl_exec($curl_handle);
	if($buffer === false)
	{
		echo 'Curl error: ' . curl_error($curl_handle);
                $log->write(curl_error($curl_handle));
	}
	else
	{
		$log->write($buffer);
		return $buffer;
	}	
curl_close($curl_handle);

}
//*************************** REQUEST PREPAID RECHARGE FUNCTION END ***************************//
//*************************** REQUEST PREPAID RECHARGE FUNCTION AIR*****************************//
function PreRechargeAir($sender,$code,$amt)
{
//echo "come";
$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/json';
$surl="https://ws.rocketinpocket.com/recharge/v1/recharge/mobile?outlet_id=".$this->outlet_id."&client_id=".$this->user_id."&client_key=".$this->client_key."&msisdn=".$sender."&operator_code=".$code."&amount=".$amt."&live=true";

$log=new Log("recharge-".date('Y-m-d').".log");
$log->write("Airtel: ".$surl);

    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$surl);
	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl_handle,CURLOPT_HTTPHEADER,$headers);
    $buffer = curl_exec($curl_handle);
	if($buffer === false)
	{
		echo 'Curl error: ' . curl_error($curl_handle);
                $log->write(curl_error($curl_handle));
	}
	else
	{             $log->write($buffer);
		return $buffer;
	}	
curl_close($curl_handle);

}
//*************************** REQUEST PREPAID RECHARGE FUNCTION END ***************************//


             
}
?>