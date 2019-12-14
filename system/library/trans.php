<?php
class trans {
public function __construct($registry) {
                $this->config = $registry->get('config');
	  $this->db = $registry->get('db');
                
}
public function addproducttrans($store_id,$product_id,$quantity,$order_id,$cr_db,$trans_type,$web_app='app')
{
			$log= new Log('product-trans-'.date('Y-m-d').'.log');
			if(empty($web_app))
			{
				$web_app='app';
			}
			$log->write($web_app);
			$getsql="select * from oc_product_to_store WHERE product_id = '" .$product_id . "' and store_id = '" .$store_id . "' ";
    			
    			$getsqlres=$this->db->query($getsql);
    			$product_row=$getsqlres->row;
			$current_quantity=$product_row['quantity'];
			$p_sql = " insert into oc_product_trans set billing_type='".$web_app."',store_id='".$store_id."',product_id ='".$product_id."',quantity='".$quantity."',order_id='".$order_id."',cr_db='".$cr_db."',trans_type='".$trans_type."',current_quantity='".$current_quantity."'  ";
			$log->write($p_sql);
			$query = $this->db->query($p_sql);
			
			
}
public function addstoretrans($cash,$store,$user_id,$tr_type,$order_id,$trans_method,$total_amount,$remarks='',$order_status_id='0',$create_time='')
{
			$log= new Log('store-trans-'.date('Y-m-d').'.log');
			if(empty($create_time))
			{
				$create_time=date('Y-m-d h:i:s');
			}
                        $up_credit_sql="select currentcredit,wallet_balance from oc_store where store_id='".$store."' limit 1 ";
						$log->write($up_credit_sql);
                        $query=$this->db->query($up_credit_sql);
                        $updated_credit=$query->row['currentcredit'];
                        $updated_wallet_balance=$query->row['wallet_balance'];
						
						
                        $up_cash_sql="select cash from oc_user where store_id='".$store."' ";
			
                        if($user_id!="")
                        {
                        $up_cash_sql.=" and user_id='".$user_id."' ";
                        }
                        $up_cash_sql.=" and user_group_id in ('11','36') limit 1 ";
						$log->write($up_cash_sql);
						$query2=$this->db->query($up_cash_sql);
                        $updated_cash=$query2->row['cash'];
						////////////////////only in case of po//////////////////reason is that amount is not coming right from the calling of trans////////////////////
						
                        if($trans_method=='PO')
						{
							$st_sql="select value as storetype from oc_setting WHERE `key`='config_storetype' and store_id=".$store;
							$log->write($st_sql);
							$st_query=$this->db->query($st_sql);
							$storetype=$st_query->row['storetype'];
							$log->write($storetype);
							///////////first we undo the last addition or deduction
							if(($storetype==3) || ($storetype==4))
							{
								$updated_credit=$updated_credit+$cash;
							}
							else
							{
								$updated_credit=$updated_credit-$cash;
							}
							/////////////////////
							
							$sql1="select order_total from oc_po_invoice where po_order_id='".$order_id."' and  po_store_id='".$store."' limit 1 ";
							$log->write($sql1);
							$query11=$this->db->query($sql1);
							
							$cash=$query->row['order_total'];
							$total_amount=$query->row['order_total']; 
							///////////second we do addition or deduction
							if(($storetype==3) || ($storetype==4))
							{
								$updated_credit=$updated_credit-$cash;
							}
							else
							{
								$updated_credit=$updated_credit+$cash;
							}
							/////////////////////
						}
                        /////////////////////only in case of po/////////////////////////////////
			$p_sql = "insert into oc_store_cash_trans set amount =  ".$cash.", store_id =  ".$store.",user_id = '".$user_id."',tr_type='".$this->db->escape($tr_type)."',`order_id`='".$order_id."',`payment_method`='".$this->db->escape($trans_method)."',`total_amount`='".$total_amount."',`updated_credit`='".$updated_credit."',`updated_cash`='".$updated_cash."',`remarks`='".$this->db->escape($remarks)."',`updated_wallet_balance`='".$updated_wallet_balance."',`order_status`='".$order_status_id."',create_time='".$create_time."' "; 
			$log->write($p_sql);
			$query = $this->db->query($p_sql);
			
			
}
public function addstoretranslog($cash,$store,$user_id,$tr_type,$order_id,$trans_method,$total_amount,$remarks='',$order_status_id='0')
{
			$log= new Log('store-trans-log-'.date('Y-m-d').'.log');
                        
                        $up_credit_sql="select currentcredit,wallet_balance from oc_store where store_id='".$store."' limit 1 ";
						$log->write($up_credit_sql);
                        $query=$this->db->query($up_credit_sql);
                        $updated_credit=$query->row['currentcredit'];
                        $updated_wallet_balance=$query->row['wallet_balance'];
						
						
                        $up_cash_sql="select cash from oc_user where store_id='".$store."' ";
			
                        if($user_id!="")
                        {
                        $up_cash_sql.=" and user_id='".$user_id."' ";
                        }
                        $up_cash_sql.=" and user_group_id in ('11','36') limit 1 ";
						$log->write($up_cash_sql);
						$query2=$this->db->query($up_cash_sql);
                        $updated_cash=$query2->row['cash'];
						////////////////////only in case of po//////////////////reason is that amount is not coming right from the calling of trans////////////////////
						
                        if($trans_method=='PO')
						{
							$st_sql="select value as storetype from oc_setting WHERE `key`='config_storetype' and store_id=".$store;
							$log->write($st_sql);
							$st_query=$this->db->query($st_sql);
							$storetype=$st_query->row['storetype'];
							$log->write($storetype);
							///////////first we undo the last addition or deduction
							if(($storetype==3) || ($storetype==4))
							{
								$updated_credit=$updated_credit+$cash;
							}
							else
							{
								$updated_credit=$updated_credit-$cash;
							}
							/////////////////////
							
							$sql1="select order_total from oc_po_invoice where po_order_id='".$order_id."' and  po_store_id='".$store."' limit 1 ";
							$log->write($sql1);
							$query11=$this->db->query($sql1);
							
							$cash=$query->row['order_total'];
							$total_amount=$query->row['order_total']; 
							///////////second we do addition or deduction
							if(($storetype==3) || ($storetype==4))
							{
								$updated_credit=$updated_credit-$cash;
							}
							else
							{
								$updated_credit=$updated_credit+$cash;
							}
							/////////////////////
						}
                        /////////////////////only in case of po/////////////////////////////////
			$p_sql = "insert into oc_store_cash_trans_log set amount =  ".$cash.", store_id =  ".$store.",user_id = '".$user_id."',tr_type='".$tr_type."',`order_id`='".$order_id."',`payment_method`='".$trans_method."',`total_amount`='".$total_amount."',`updated_credit`='".$updated_credit."',`updated_cash`='".$updated_cash."',`remarks`='".$remarks."',`updated_wallet_balance`='".$updated_wallet_balance."',`order_status`='".$order_status_id."' "; 
			$log->write($p_sql);
			$query = $this->db->query($p_sql);
			
			
}
public function addattendencetrans($user_id,$location_lat,$location_long,$attendence_type)
{
			$log= new Log('attendance-trans-'.date('Y-m-d').'.log');
			$p_sql = " insert into oc_attendence_trans set user_id='".$user_id."',location_lat ='".$location_lat."',location_long='".$location_long."',attendence_type='".$attendence_type."' ";
			$log->write($p_sql);
			$query = $this->db->query($p_sql);
			
			
}

public function addwalletcredit($store_id,$transaction_type,$payment_type,$amount,$invoice_number,$remarks='') 
{
			$log= new Log('wallet-trans-'.date('Y-m-d').'.log');
                        
                        
	          $up_credit_sql="select wallet_balance from oc_store where store_id='".$store_id."' limit 1 ";
	          $log->write($up_credit_sql);
                        $query=$this->db->query($up_credit_sql);
                        $old_balance=$query->row['wallet_balance'];
                        $new_balance=$old_balance+$amount;
                        
	          $p_sql = "update oc_store set wallet_balance =  wallet_balance+".$amount." where store_id =  ".$store_id;
	          $log->write($p_sql);
	          $query = $this->db->query($p_sql);
	

	          $p_sql = "insert into oc_partner_invoice_adjustment set amount =  ".$amount.", store_id =  ".$store_id.",transaction_type = '".$transaction_type."',payment_type='".$payment_type."',`invoice_number`='".$invoice_number."',`remarks`='".$remarks."',wallet_balance='".$new_balance."',cr_date='".date('Y-m-d')."' ";
	          $log->write($p_sql);
	          $query = $this->db->query($p_sql);	
			
}

public function addwalletdebit($store_id,$transaction_type,$payment_type,$amount,$invoice_number,$remarks='')
{
			$log= new Log('wallet-trans-'.date('Y-m-d').'.log');
                        
                        
	          $up_credit_sql="select wallet_balance,currentcredit from oc_store where store_id='".$store_id."' limit 1 ";
	          $log->write($up_credit_sql);
                        $query=$this->db->query($up_credit_sql);
                        $old_balance=$query->row['wallet_balance'];
		
                        $new_balance=$old_balance-$amount;
                        
	          $p_sql = "update oc_store set wallet_balance =  wallet_balance-".$amount." where store_id =  ".$store_id;
	          $log->write($p_sql);
	          $query = $this->db->query($p_sql);
	
				if($transaction_type!=3) 
				{
					$p_sql = "insert into oc_partner_invoice_adjustment set invoice_amount =  ".$amount.", store_id =  ".$store_id.",transaction_type = '".$transaction_type."',payment_type='".$payment_type."',`invoice_number`='".$invoice_number."',`remarks`='".$remarks."',wallet_balance='".$new_balance."',cr_date='".date('Y-m-d')."' ";
					$log->write($p_sql);
					$query = $this->db->query($p_sql);	
				}

	
			
}

public function storewalletcredit($store_id,$amount,$remarks='')
{
			$log= new Log('wallet-trans-'.date('Y-m-d').'.log');
                        
                        
	          $up_credit_sql="select wallet_balance from oc_store where store_id='".$store_id."' limit 1 ";
	          $log->write($up_credit_sql);
                        $query=$this->db->query($up_credit_sql);
                        $old_balance=$query->row['wallet_balance'];
                        $new_balance=$old_balance+$amount;
                        
	          $p_sql = "update oc_store set wallet_balance =  wallet_balance+".$amount." where store_id =  ".$store_id;
	          $log->write($p_sql);
	          $query = $this->db->query($p_sql);		          
			
}


public function storecurrentcreditdebit($store_id,$amount,$remarks='')
{
			$log= new Log('wallet-trans-'.date('Y-m-d').'.log');
                        
                        
	          $up_credit_sql="select currentcredit from oc_store where store_id='".$store_id."' limit 1 ";
	          $log->write($up_credit_sql);
                        $query=$this->db->query($up_credit_sql);
                        $old_balance=$query->row['currentcredit'];
                        $new_balance=$old_balance+$amount;
                        
	          $p_sql = "update oc_store set currentcredit =  currentcredit-".$amount." where store_id =  ".$store_id;
	          $log->write($p_sql);
	          $query = $this->db->query($p_sql);		          
			
}
public function storecurrentcreditcredit($store_id,$amount,$remarks='')
{
			$log= new Log('wallet-trans-'.date('Y-m-d').'.log');
                        
                        
	          $up_credit_sql="select currentcredit from oc_store where store_id='".$store_id."' limit 1 ";
	          $log->write($up_credit_sql);
                        $query=$this->db->query($up_credit_sql);
                        $old_balance=$query->row['currentcredit'];
                        $new_balance=$old_balance+$amount;
                        
	          $p_sql = "update oc_store set currentcredit =  currentcredit+".$amount." where store_id =  ".$store_id;
	          $log->write($p_sql);
	          $query = $this->db->query($p_sql);	 	          
			
}
public function addsuppliertrans($supplier_id, $amount,$cr_db,$order_id,$tr_type,$payment_method) 
        {
                $log=new Log('supplier-'.date('Y-m-d').'.log');
                $sql1="select * from oc_po_supplier where id='".$supplier_id."' limit 1 ";
                $log->write($sql1);
                $query1 = $this->db->query($sql1);
                $row=$query1->row; 
                $current_balance=$row['wallet_balance']; 
                $log->write($query1->row);
                $log->write($current_balance);
        	  $sql="insert into oc_supplier_wallet_trans set supplier_id='".$supplier_id."',amount='".$amount."',cr_db='".$cr_db."',order_id='".$order_id."',tr_type='".$tr_type."',payment_method='".$payment_method."',current_balance='".$current_balance."' ";
                $query = $this->db->query($sql);
                $log->write($sql);
                
                
        } 

}
