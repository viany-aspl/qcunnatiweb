<?php
class ModelPartnerBankPayment extends Model 
{ 
	public function getStoreActiveationDate($store_id)
	{
		$sql="select * from oc_setting where `key`='config_registration_date' and store_id='".$store_id."' limit 1 ";
		$query = $this->db->query($sql);
               	return $query->row['value'];
		
	}
	public function getTaggedOrdersByDate($data)
	{
		$sql="SELECT * from ( SELECT sum(tagged) as totaltaggedamount,store_id,date(date_added) as sale_date,store_name FROM oc_order where store_id='".$data['taggedstore']."' and date(date_added)>='".$data['filter_tagged_date_start']."' and date(date_added)<='".$data['filter_tagged_date_end']."' and date(date_added)>='".$data['store_activation_date']."' and order_status_id=5 and payment_method in ('Tagged','Tagged Cash','Tagged Subsidy') group by date(date_added) ) a where totaltaggedamount >0 ";
		$query = $this->db->query($sql);
               	return $query->rows;
		
	}

	public function checkTaggedPaymentStatusByDateStore($sale_date,$store_id)  
        	{
		
		$query = $this->db->query(" SELECT * FROM oc_payout_dtl  where tagged_subsidy_bill_date='".$sale_date."' and store_id='".$store_id."' and payment_method='Tagged Payment' ");
               	$get_rows=$query->rows;
		if(count($get_rows)>0)
		{
			return 'yes';
		}
		else
		{
			return 'no';
		}
	}
	public function getSubsidyOrdersByDate($data)
	{
		$sql="SELECT * from ( SELECT sum(subsidy) as totalsubsidyamount,store_id,date(date_added) as sale_date,store_name FROM oc_order where store_id='".$data['subsidystore']."' and date(date_added)>='".$data['filter_subsidy_date_start']."' and date(date_added)<='".$data['filter_subsidy_date_end']."' and date(date_added)>='".$data['store_activation_date']."' and order_status_id=5 and payment_method in ('Subsidy','Cash Subsidy','Tagged Subsidy')  group by date(date_added) ) a where totalsubsidyamount >0 ";
		$query = $this->db->query($sql);
		//echo $sql;
               	return $query->rows;
		
	}
	public function getstoreuser($store_id)
	{
		$sql="SELECT firstname,lastname,user_id FROM oc_user where store_id='".$store_id."' and  user_group_id =11 and status=1";
		$query = $this->db->query($sql);
		//echo $sql;
               	return $query->rows;
	}
	public function getinhandcash($store_id,$user_id)
	{
		$sql="SELECT sum(cash) as total FROM oc_user where store_id='".$store_id."' and  user_group_id =11 and status=1 and user_id='".$user_id."' ";
		$query = $this->db->query($sql);
		//echo $sql;
               	return $query->row['total'];
	}
	public function addbankTrans($data) 
	{
            $log=new Log("cash-new".date('Y-m-d').".log");
                $sql="INSERT INTO " . DB_PREFIX . "bank_transaction SET bank_id = '" . $data['bank_id'] . "', bank_name = '" . $data['bank_name'] . "', amount= '" . $this->db->escape($data['amount']) . "',user_id= '" . $this->db->escape($data['user_id']) . "',accept_by='".$data['ce_id']."',store_id= '" . $this->db->escape($data['store_id']) . "', date_added = NOW(),remarks='".$this->db->escape($data['remarks'])."' ";
		$this->db->query($sql);
                $log->write($sql);
		$tid = $this->db->getLastId();
		
		$sql12 = "update " . DB_PREFIX . "user set cash = cash - ".$data['amount']."  where user_id = ".$data['user_id']; 
                        $log->write($sql12);
                        $this->db->query($sql12); 
                       	try{
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($data['amount'],$data['store_id'],$data['user_id'],'DB',$tid,$data['bank_name'],$data['amount'],$data['remarks']);   
			
			$this->load->library('sms');	
			$sms=new sms($this->registry);
			//$sms->sendsms($this->request->post['customer_mob'],"2",$data);
                                 
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
		return $tid; 
	} 
	public function checkSubsidyPaymentStatusByDateStore($sale_date,$store_id)  
        	{
		
		$query = $this->db->query(" SELECT * FROM oc_payout_dtl  where tagged_subsidy_bill_date='".$sale_date."' and store_id='".$store_id."' and payment_method='Subsidy Payment' ");
               	$get_rows=$query->rows;
		if(count($get_rows)>0)
		{
			return 'yes';
		}
		else
		{
			return 'no';
		}
	}

        	public function getAllUnits()
	{
		$query = $this->db->query('SELECT * FROM oc_unit ');
               	return $query->rows;
		
	}
        	public function getStores()
	{
		$query = $this->db->query('SELECT store_id,name FROM oc_store');
		return $query->rows;
	}
        	public function insrtPayoutdtl($data,$updated_by)  
        	{
             		$sql="insert into  oc_payout_dtl set store_id='".$data["store"]."',user_id='".$updated_by."',amount='".$data['amount']."',transaction_type='".$data['transaction_type']."',payment_method='".$data['payment_method']."',unit_id='".$data['unit']."',tr_number='".$data['tr_number']."',remarks='".$data['remarks']."' ";
             		if(!empty($data['transaction_date']))
					{
						$sql.=",create_date='".$data['transaction_date'].' '.date('h:i:s')."' ";
					}
					$query = $this->db->query($sql);
             		$insert_id=$this->db->getLastId();
					if($data['transaction_type']=='Credit Posting' || $data['transaction_type']=='Waiver Subsidy' )
             		{
						$sql2="update  oc_store set currentcredit=currentcredit +'".$data["amount"]."' where store_id='".$data['store']."'";
						$query2 = $this->db->query($sql2);
						try
                        {
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addstoretrans($data["amount"],$data['store'],'','CR',$insert_id,$data['transaction_type'],$data["amount"],$data['transaction_type']);  
							$trans->addwalletcredit($data['store'],'1','Credit Posting',$data["amount"],$insert_id,'Credit Posting for the Store via web');       
						} 
						catch (Exception $ex) 
						{
                            $log->write($ex->getMessage());
                        }
             		}
             		if($data['transaction_type']=='Debit') 
					{
             			$sql2="update  oc_store set currentcredit=currentcredit -'".$data["amount"]."' where store_id='".$data['store']."'";
             			$query2 = $this->db->query($sql2);
						try
                        {
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
							$trans->addwalletdebit($data['store'],'3','Debit',$data["amount"],$insert_id,$data['remarks']);
                            $trans->addstoretrans('-'.$data["amount"],$data['store'],'','DB',$insert_id,$data['transaction_type'],'-'.$data["amount"],$data['remarks']);  
							         
						} 
						catch (Exception $ex) 
						{
                            $log->write($ex->getMessage());
                        }
             		}
        	}
	public function insertTaggedPayment($data,$updated_by)  
        	{
		$sql=" SELECT * FROM oc_payout_dtl  where tagged_subsidy_bill_date='".$data['filter_tagged_date']."' and store_id='".$data["store"]."' and payment_method='Tagged Payment' ";
		$query = $this->db->query($sql);
               	$get_rows=$query->rows;
		if(count($get_rows)>0)
		{
			return 0;
		}
		else
		{
		//print_r($data);
		//exit;
             		$sql="insert into  oc_payout_dtl set store_id='".$data["store"]."',user_id='".$updated_by."',amount='".$data['amount']."',transaction_type='".$data['transaction_type']."',payment_method='".$data['payment_method']."',unit_id='".$data['unit']."',tr_number='".$data['tr_number']."',tagged_subsidy_bill_date='".$data['filter_tagged_date']."' ";
             		$query = $this->db->query($sql);
             		$insert_id=$this->db->getLastId();
		
              		$sql2="update  oc_store set currentcredit=currentcredit +'".$data["amount"]."' where store_id='".$data['store']."'";
              		$query2 = $this->db->query($sql2);
			try
                        		{
                            		$this->load->library('trans');
                            		$trans=new trans($this->registry);
                            		
				$trans->addwalletcredit($data['store'],'1','Credit Posting',$data["amount"],$insert_id,'Tagged Payment for the Store via web');       
   $trans->addstoretrans($data["amount"],$data['store'],'','CR',$insert_id,$data['transaction_type'],$data["amount"],'Tagged Payment');  
                        		} 
			catch (Exception $ex) 
			{
                                		$log->write($ex->getMessage());
                        		}
             		
             			return $insert_id;
		}
        	}
	public function insertSubsidyPayment($data,$updated_by)  
        	{
		
		$query = $this->db->query(" SELECT * FROM oc_payout_dtl  where tagged_subsidy_bill_date='".$data['filter_subsidy_date']."' and store_id='".$data["store"]."' and payment_method='Subsidy Payment' ");
               	$get_rows=$query->rows;
		if(count($get_rows)>0)
		{
			return 0;
		}
		else
		{
		//print_r($data);
		//exit;
             		$sql="insert into  oc_payout_dtl set store_id='".$data["store"]."',user_id='".$updated_by."',amount='".$data['amount']."',transaction_type='".$data['transaction_type']."',payment_method='".$data['payment_method']."',unit_id='".$data['unit']."',tr_number='".$data['tr_number']."',tagged_subsidy_bill_date='".$data['filter_subsidy_date']."' ";
             		$query = $this->db->query($sql);
             		$insert_id=$this->db->getLastId();
		
              		$sql2="update  oc_store set currentcredit=currentcredit +'".$data["amount"]."' where store_id='".$data['store']."'";
              		$query2 = $this->db->query($sql2);
			try
                        		{
                            		$this->load->library('trans');
                            		$trans=new trans($this->registry);
                            		
				$trans->addwalletcredit($data['store'],'1','Credit Posting',$data["amount"],$insert_id,'Subsidy Payment for the Store via web');       
   $trans->addstoretrans($data["amount"],$data['store'],'','CR',$insert_id,$data['transaction_type'],$data["amount"],'Subsidy Payment');  
                        		} 
			catch (Exception $ex) 
			{
                                		$log->write($ex->getMessage());
                        		}
             		
             			return $insert_id;
		}
        	}
        	public function getPayoutList($data)
	{
		$sql='SELECT od.amount,od.transaction_type,od.tr_number,DATE(od.create_date) as create_date,od.payment_method,store.name,user.firstname,user.lastname,date(od.tagged_subsidy_bill_date) as tagged_subsidy_bill_date FROM `oc_payout_dtl` as od
		LEFT JOIN oc_store as store on store.store_id=od.store_id
		LEFT JOIN oc_user as user on user.user_id=od.user_id';

		if (!empty($data['filter_stores_id'])) 
		{
			$sql .= " WHERE od.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 
		else 
		{
			$sql .= " WHERE od.store_id > '0'";
		}
		if (!empty($data['filter_date_start'])) 
		{
			$sql .= " AND DATE(od.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) 
		{
			$sql .= " AND DATE(od.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		$sql.=" order by sid desc ";

		if (isset($data['start']) || isset($data['limit'])) 
		{
			if ($data['start'] < 0) 
			{
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) 
			{
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo $sql;
		$query= $this->db->query($sql);
		return $query->rows;
	}
	public function getTotalPayoutList($data)
	{
		$sql='select count(*) as total from (SELECT od.amount,od.transaction_type,DATE(od.create_date) as create_date,od.payment_method,store.name,user.firstname,user.lastname FROM `oc_payout_dtl` as od
		LEFT JOIN oc_store as store on store.store_id=od.store_id
		LEFT JOIN oc_user as user on user.user_id=od.user_id';
		if (!empty($data['filter_stores_id'])) 
		{
			$sql .= " WHERE od.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 
		else 
		{
			$sql .= " WHERE od.store_id > '0'";
		}
		if (!empty($data['filter_date_start'])) 
		{
			$sql .= " AND DATE(od.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) 
		{
			$sql .= " AND DATE(od.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		$sql.=" ) as aa";
		//echo $sql; 
		$query= $this->db->query($sql);
		return $query->row['total'];
	}
	public function gettaggedvaluebyStoreDate($data)
	{
		$sql="SELECT sum(tagged) as totaltaggedamount FROM oc_order where store_id='".$data['store_id']."' and date(date_added)='".$data['date']."' and order_status_id=5 ";
		$query = $this->db->query($sql);
               	return $query->row['totaltaggedamount'];
		
	}
	public function getSubsidyvaluebyStoreDate($data)
	{
		$sql="SELECT sum(subsidy) as totaltaggedamount FROM oc_order where store_id='".$data['store_id']."' and date(date_added)='".$data['date']."' and order_status_id=5 ";
		$query = $this->db->query($sql);
               	return $query->row['totaltaggedamount'];
		
	}
	public function getOrdersCountByStoreDate($data,$tagged_subsidy)
	{
		$sql="SELECT count(*) as total_orders,order_status_id FROM oc_order where  store_id='".$data['store_id']."' and date(date_added)='".$data['date']."' and order_status_id in (1,5) ";
		if($tagged_subsidy=='tagged')
		{
			$sql.=" and payment_method in ('Tagged','Tagged Cash','Tagged Subsidy') ";
		}
		else if($tagged_subsidy=='subsidy')
		{
			$sql.=" and payment_method in ('Subsidy','Cash Subsidy','Tagged Subsidy') ";
		}
		$sql.=" group by order_status_id "; 
		$query = $this->db->query($sql);
               	return $query->rows;
		
	}
        
} 
?>