<?php
class recharge {
public function __construct($registry) {
          $this->config = $registry->get('config');
	  $this->db = $registry->get('db');
                
}
public $client_key="s924phas9zvofx76ich2iwc0nvlsnqds";
public $user_id="10325";
public $outlet_id="10019567";

public function rechargemain($mobile,$recharge_amount,$order_id,$store_id,$product_id,$product_quantity,$scheme_id,$imei)
{
    $log=new Log("recharge-".date('Y-m-d').".log");	
    
    $allready_get_recharge=$this->get_recharge_scheme_status($scheme_id,$mobile,$imei);
    if($allready_get_recharge>0)
    {
        $log->write('This number or imei allready get the recharge for same scheme');
        return $allready_get_recharge;
        exit;
    }
    
    $opertor=$this->GetOperator($mobile); 
    $opertor_json=json_decode($opertor);
    $log->write($opertor_json);
    
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
     //$log->write( $operator_code."-".$operator_name); 
     
     if($operator_code=="28") ///////////send to airtel prepaid
     {
                        $log->write('come in the airtel');                      
                        $Rec_Res=$this->PreRechargeAir($mobile,$operator_code,$recharge_amount);
			//$RecRes= explode('--',$Rec_Res);
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
                        $rocket_tbl_id=$this->insertIntoRechargeTransRocket($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt);
			//$rocket_tbl_id=$RecRes->$rocket_tbl_id;//$this->model_recharge_recharge->insertIntoRechargeTransRocketReHit($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt,$transtblid,$rockettranstblid);
                        if($ResErr=='229')
			{
			   
                        $Rec_Res=$this->PostRecharge($mobile,$operator_code,$recharge_amount);
			
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
                        $rocket_tbl_id=$this->insertIntoRechargeTransRocket($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt);
                        //$rocket_tbl_id=$RecRes->$rocket_tbl_id;
                        if($ResErr229!="")
                        {
                           $success_status=2;
			   $ResSerSts=$ResErrMsg;
                        }
                        if($ResErr229=="229")
                        {
                           $success_status=2;
			   $ResSerSts=$ResErrMsg;
                        }
			}
                        if($ResErr=='233')
			{
			   $success_status=2;
			   $ResSerSts=$ResErrMsg;
			}
                        if($ResErr=='235')
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
                        //print_r($RecRes);//exit;
                        $log->write($RecRes);
			echo $ResErr=$RecRes->errorCode; //12 Try after 30 Mins
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
                        $rocket_tbl_id=$this->insertIntoRechargeTransRocket($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt);
                        //$rocket_tbl_id=$RecRes->$rocket_tbl_id;//$rocket_tbl_id=$this->model_recharge_recharge->insertIntoRechargeTransRocketReHit($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt,$transtblid,$rockettranstblid);
			//echo $ResErr;
                        if($ResErr=='229')
			{
			  
                        $Rec_Res=$this->PostRecharge($mobile,$operator_code,$recharge_amount);
			
                        $RecRes=json_decode($Rec_Res);
                        $log->write($RecRes);
			echo $ResErr229=$RecRes->errorCode; //12 Try after 30 Mins
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
                        $rocket_tbl_id=$this->insertIntoRechargeTransRocket($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt);
                        //$rocket_tbl_id=$RecRes->$rocket_tbl_id;
                        if($ResErr229!="")
                        {
                           $success_status=2;
			   $ResSerSts=$ResErrMsg;
                        }
                        if($ResErr229=="229")
                        {
                           $success_status=2;
			   $ResSerSts=$ResErrMsg;
                        }
			}
                        if($ResErr=='233')
			{
			   $success_status=2;
			   $ResSerSts=$ResErrMsg;
			}
                        if($ResErr=='235')
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
                    $post_data=array(
                              	 'order_id'=>@$order_id,
                               	 'store_id'=>@$store_id,
                               	 'mobile'  => @$mobile,
                              	 'product_id' => @$product_id,
                              	 'product_quantity' => @$product_quantity,
                               	 'recharge_amount' => @$recharge_amount,
			         'scheme_id' => @$scheme_id,
                                 'status' => @$success_status,
                                 'rocket_trans_id' => @$ResRocTransID,
                                 'rocket_err_code' =>@$ResErr,
                                 'rocket_tbl_id' => @$rocket_tbl_id 
		      );
                    $recharge_id=$this->insertIntoRechargeTrans($post_data,$operator_name,$operator_code,$imei);
                    $log->write($recharge_id);
                              
                    
    }
    else/////////////// when operator not found for the mobile
    {
            $post_data=array(
                    'order_id'=>@$order_id,
                    'store_id'=>@$store_id,
                    'mobile'  =>@$mobile,
                    'product_id' => @$product_id,
                    'product_quantity' => @$product_quantity,
                    'recharge_amount' => @$recharge_amount,
		    'scheme_id' => @$scheme_id,
                    'status' => 2,
                    'rocket_trans_id' => '',
                    'rocket_err_code' =>@$ResErr,
		    'rocket_tbl_id' => @$rocket_tbl_id
		    );
                    $recharge_id=$this->insertIntoRechargeTrans($post_data,$operator_name,$operator_code);
                    $log->write($recharge_id);
    }
    return $recharge_id;
    
}



public function GetOperator($sender)
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
//****************************** REQUEST PREPAID RECHARGE FUNCTION *****************************//
public function PreRecharge($sender,$code,$amt)
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
                        
		        return $buffer;//$ResErr."--".$ResErrMsg."--".$rocket_tbl_id;//$buffer;
                        //
		        //return $ResErr."--".$ResErrMsg."--".$rocket_tbl_id;//$buffer;
	}	
curl_close($curl_handle);

}
//*************************** REQUEST PREPAID RECHARGE FUNCTION END ***************************//
//*************************** REQUEST PREPAID RECHARGE FUNCTION AIR*****************************//
public function PreRechargeAir($sender,$code,$amt)
{
//echo "come";
$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/json';
$surl="https://ws.rocketinpocket.com/recharge/v1/recharge/mobile?outlet_id=10019619&client_id=".$this->user_id."&client_key=".$this->client_key."&msisdn=".$sender."&operator_code=".$code."&amount=".$amt."&live=true";

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
	{
                        
		        return $buffer;//$ResErr."--".$ResErrMsg."--".$rocket_tbl_id;//$buffer;
	}	
curl_close($curl_handle);

}
//*************************** REQUEST PREPAID RECHARGE FUNCTION END ***************************//
//*************************** REQUEST POSTPAID RECHARGE FUNCTION ******************************//
public function PostRecharge($sender,$code,$amt)
{
$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/json';
$surl="https://ws.rocketinpocket.com/recharge/v1/recharge/mobile/bill?client_id=".$this->user_id."&client_key=".$this->client_key."&msisdn=".$sender."&operator_code=".$code."&amount=".$amt."&live=true";

$log=new Log("recharge-".date('Y-m-d').".log");
$log->write("Postpaid: ".$surl);

$log->write(curl_error($curl_handle));
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
	}
	else
	{
                        
		        return $buffer;//$ResErr."--".$ResErrMsg."--".$rocket_tbl_id;//$buffer;
	}	
curl_close($curl_handle);

}
//*************************** REQUEST POSTPAID RECHARGE FUNCTION END ***************************//
public function insertIntoRechargeTransRocket($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt)
{
$sql="insert into `oc_recharge_transactions_rocket` set `ResErr`='".$ResErr."',`ResErrMsg`='".$ResErrMsg."',`ResAmt`='".$ResAmt."',`ResCom`='".$ResCom."',`ResDT`='".$ResDT."',`ResNum`='".$ResNum."',`ResNwOc`='".$ResNwOc."',`ResNw`='".$ResNw."',`ResNwTransID`='".$ResNwTransID."',`ResRecCode`='".$ResRecCode."',`ResRocTransID`='".$ResRocTransID."',`ResSevTyp`='".$ResSevTyp."',`ResSerSts`='".$ResSerSts."',`ResTransAmt`='".$ResTransAmt."'  "; 
            $query = $this->db->query($sql);
            $log=new Log("recharge-".date('Y-m-d').".log");
            $log->write($sql);
            $recharge_id = $this->db->getLastId(); 
            
            $log->write($recharge_id);
            return $recharge_id;
}


public function insertIntoRechargeTrans($data=array(),$operator_name,$operator_code,$imei)
{
            
            $sql="insert into `oc_recharge_transactions` set `mobile`='".$data["mobile"]."',`recharge_amount`='".$data["recharge_amount"]."',`order_id`='".$data["order_id"]."',`store_id`='".$data["store_id"]."',`product_id`='".$data["product_id"]."',`product_quantity`='".$data["product_quantity"]."',`operator_name`='".$operator_name."',`operator_code`='".$operator_code."',`scheme_id`='".$data["scheme_id"]."',`status`='".$data["status"]."',`rocket_trans_id`='".$data["rocket_trans_id"]."',`rocket_err_code`='".$data["rocket_err_code"]."',`rocket_tbl_id`='".$data["rocket_tbl_id"]."',imei='".$imei."'  "; 
            $query = $this->db->query($sql);
            $log=new Log("recharge-".date('Y-m-d').".log");
            $log->write($sql);
            $recharge_id = $this->db->getLastId(); 
            $log->write($recharge_id);
            return $recharge_id;
            
}
public function get_recharge_scheme_status($scheme_id,$mobile,$imei) 
{
		if($scheme_id=="7")
		{
			$sql=" SELECT * FROM `oc_recharge_transactions`   where scheme_id='".$scheme_id."' and (mobile='".$mobile."')  ";
		}
		else
		{
	       		 $sql=" SELECT * FROM `oc_recharge_transactions`   where scheme_id='".$scheme_id."' and (mobile='".$mobile."' or imei='".$imei."')  ";
                	}
		$query = $this->db->query($sql);
                $log=new Log("recharge-".date('Y-m-d').".log");
                $log->write($sql);
                $log->write($query->rows);
		return $query->num_rows;
           
}
        public function get_scheme_amount($scheme_id) {
                $today=date('Y-m-d');
	        $sql=" SELECT recharge_amount FROM `oc_recharge_products`   where scheme_id='".$scheme_id."' and date(start_date)<='".$today."' and date(end_date)>='".$today."'  ";
                
		$query = $this->db->query($sql);
                $log=new Log("recharge-".date('Y-m-d').".log");
                $log->write($sql);
                $log->write($query->row);
		return $query->row["recharge_amount"]; 
           
	}
}
