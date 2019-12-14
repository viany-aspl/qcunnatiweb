<?php
date_default_timezone_set("Asia/Calcutta");
class ModelRunnerCash extends Model {
	public function get_name_by_id($user_id)
	{
		$sql="SELECT concat(firstname,' ',lastname) as name FROM `oc_user`  where user_id=".$user_id;
                  	$query = $this->db->query($sql);
		return $query->row['name'];
	}
	public function get_store_name_by_id($store_id)
	{
		$sql="SELECT name as name FROM `oc_store`  where store_id=".$store_id;
                  	$query = $this->db->query($sql);
		return $query->row['name'];
	}
	
	
	public function get_not_accepted_entries($data = array()) {
	   
            $sql="SELECT oc_bank_deposit_runner.*,oc_user.firstname,oc_user.lastname,oc_bank.bank as bank_name "
                    . "FROM oc_bank_deposit_runner left join oc_bank on oc_bank.bank_id=oc_bank_deposit_runner.bank "
                    . " join oc_user on oc_user.user_id=oc_bank_deposit_runner.user_id where oc_bank_deposit_runner.bank!='' and DATE(oc_bank_deposit_runner.submit_date) >='2018-04-03' ";
                  
		if (!empty($data['filter_user'])) {
			$sql .= " AND oc_bank_deposit_runner.user_id = '" . $this->db->escape($data['filter_user']) . "'";
		}

			$sql .= " AND oc_bank_deposit_runner.status = '0'";
		
		
            		$sql.=" order by oc_bank_deposit_runner.SID desc ";
            
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
               // echo $sql;
		return $query->rows; 
	}
	
	public function getCash_report($data = array()) {
	   
//SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt RIGHT JOIN oc_store on oc_store.store_id=obt.store_id  WHERE DATE(obt.date_added) >= '2016-10-30' AND DATE(obt.date_added) <= '2017-01-18'
            $sql="SELECT obt.*,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id where obt.bank_name!='' ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
            $sql.=" Order by obt.transid desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                            //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}
             public function  getCash_reportPending($data = array()) {
	
            $sql="SELECT obt.*,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id where obt.bank_name!='' and status='0'  ";
  /*          
$sql="SELECT obt.*,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id where obt.bank_name!='' and status='0' and obt.store_id in (select store_id from oc_runner_to_store where user_id='".$data["username"]."') ";
            
*/
      
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
            $sql.=" Order by obt.transid desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                            //echo $sql;
		$query = $this->db->query($sql);
                $log=new Log("ce-".date('Y-m-d').".log"); 
                $log->write($sql);

		return $query->rows;
	}
              public function getCash_report_Accepted_Rejected_by_ce($data = array()) {
	
            $sql="SELECT obt.*,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id where obt.bank_name!='' and status!='0'  and obt.accept_by='".$this->db->escape($data['username'])."' ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
            $sql.=" Order by obt.transid desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                            //echo $sql;
                            $log=new Log("ce-".date('Y-m-d').".log");
                            $log->write($sql);
		$query = $this->db->query($sql);
                            $log->write($query->rows);
		return $query->rows;
	}
	public function getTotalCash_transation($data = array()) {
		
            $sql="SELECT count(obt.transid) as total FROM `oc_bank_transaction` as obt where obt.bank_name!='' ";
                    //. "WHERE DATE(obt.date_added) >= '2016-10-30' "
                    //. "AND DATE(obt.date_added) <= '2017-01-18'";
            
            if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getCash_position($data = array()) {
	   
            $sql="SELECT * FROM `oc_cash_store_position` ";
                  
            if (!empty($data['filter_store'])) {
			$sql .= " where store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}
                
                if (!empty($data['filter_date'])) {
                      if (!empty($data['filter_store'])) 
                      {
			$sql .= " and DATE(update_date)= '" . $this->db->escape($data['filter_date']) . "'";
		      }
                      else
                      {
                          $sql .= " where DATE(update_date)= '" . $this->db->escape($data['filter_date']) . "'";
                      }
			
		}

		
            $sql.=" Order by SID desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

public function accept_reject_cash_mpesa($tr_id,$logged_user,$status,$m_pesa_tr_number) {
	
	        $log=new Log("ce-accept-reject-".date('Y-m-d').".log");

                      $sql110="select status,bank_id from  `oc_bank_transaction`   where  `transid`='".$tr_id."' ";
                      $log->write($sql110);
                      $query110 = $this->db->query($sql110);
                      $old_status=$query110->row["status"];
                      $bank_id=$query110->row["bank_id"];
                      $log->write($query110->row);
                     if($old_status=="0")
                     {

                     $date_updated=date('Y-m-d h:i:s');
                     $sql="update `oc_bank_transaction` set `status`='".$status."',`accept_by`='".$logged_user."',`date_updated`='".$date_updated."',mpesa_trans_id='".$m_pesa_tr_number."'  where  `transid`='".$tr_id."' ";
                      
                      $log->write($sql);
                      $query = $this->db->query($sql);
                      if($status==1)
                      {
                      $sql11="select user_id,store_id,amount from  `oc_bank_transaction`   where  `transid`='".$tr_id."' ";
                      $log->write($sql11);
                      $query11 = $this->db->query($sql11);
                      $user_id=$query11->row["user_id"];
                      $store_id=$query11->row["store_id"];
                      $log->write($query11->row);
                      
                      $amount=$query11->row["amount"];
                      if(empty($amount))
                      {
                       $amount=$this->request->post['amount'];
                      }
                      if(empty($amount))
                      {
                        $amount=$this->request->get['amount'];
                      }
                       if($bank_id!="4")
                       {
                        $sql12 = "update " . DB_PREFIX . "user set cash = cash - ".$amount."  where user_id = ".$user_id; 
                        $log->write($sql12);
                        $this->db->query($sql12);
                        //$sql2 = "insert into oc_store_cash_trans set amount =  ".$amount.", store_id =  ".$store_id.",user_id = ".$user_id.",tr_type='DR' ";
                        //$log->write($sql2);
                        //$this->db->query($sql2);
			try{ 
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($amount,$store_id,$user_id,'DB',$tr_id,'CASHDEPOSIT',$amount,' Cash Deposited by store to Collection Executive via-MPESA');  
                                 
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
                        return $amount;
                        }
                        else
                        {
                        return 0;
                        }
                       
                       }
                       else ////////means status is already updated/////////
                       {
                           return 0;
                        }
                      }
		
	}
       
         
	public function deposit_cash($data=array())
	{
	//print_r($data);

	$date_updated=date('Y-m-d h:i:s');
            $sql="insert into  `oc_bank_deposit_runner` set `user_id`='".$data["logged_user"]."',`amount`='".$data["deposit_amount"]."',`bank`='".$data["filter_bank"]."',`deposit_date`='".$data["deposit_date"]."',`transaction_number`='".$data["transaction_number"]."',`branch`='".$data["branch"]."',`deposited_by`='".$data["deposit_by"]."',`remarks`='".$data["remarks"]."'  ";
                  
            //echo $sql;
		$query = $this->db->query($sql);
	}



	
public function getAllCe($data = array()) {
	   $sql=" SELECT ocu.firstname as firstname,ocu.lastname as lastname,ocu.user_id as user_id FROM `oc_user` as ocu 
	   
	   left join `oc_runner_to_store` as ors on ors.user_id=ocu.user_id 
	   left join oc_store as os on os.store_id = ocu.store_id 
	   where ocu.status=1 and ocu.user_group_id=22 ";
if($data["filter_store"]!="")
{
$sql.=" and ors.store_id='".$data["filter_store"]."'   ";
  }   
if($data["filter_company"]!="")
{
$sql.=" and os.company_id='".$data["filter_company"]."'   ";
  }
  
        $sql.=" group by ors.user_id ";    
           $log=new Log("ce-".date('Y-m-d').".log"); 
           $log->write($sql);
		$query = $this->db->query($sql);
        //  echo $sql;      
		return $query->rows;
           
	}

public function getCeByCompany($data = array()) {
	   $sql=" SELECT ocu.firstname as firstname,ocu.lastname as lastname,ocu.user_id as user_id FROM `oc_user` as ocu 
	   left join `oc_runner_to_store` as ors on ors.user_id=ocu.user_id 
	   left join oc_store on ors.store_id=oc_store.store_id 
	   
	   where oc_store.store_id!='' ";
if($data['filter_company']!="")
{
$sql.=" and oc_store.company_id='".$data['filter_company']."' ";
  }   
if($data["filter_store"]!="")
{
$sql.="and  ors.store_id='".$data["filter_store"]."'   ";
  }              
        $sql.=" group by ors.user_id ";    
           $log=new Log("ce-".date('Y-m-d').".log"); 
           $log->write($sql);
		$query = $this->db->query($sql);
                
		return $query->rows;
           
	}
        public function get_runner_accepted_Cash($username) {
	   
            $sql=" SELECT amount FROM `oc_runner_cash_position` as ocr where ocr.runner_id ='".$username."'  ";
                
                            
		$query = $this->db->query($sql);
                
		return $query->row["amount"];
	}
        public function cashsubmmision_update_file($data = array(),$file_name)
        {
            
              $sql="update `oc_bank_deposit_runner` set  `uploded_file`='$file_name'  where `SID`='".$data["billid"]."' "; 
	$log=new Log("ce-".date('Y-m-d').".log");
	$log->write($sql);
	$query = $this->db->query($sql);
               
        }
		public function get_runner_Cash_position($data = array()) {
	   
            $sql=" SELECT ocr.*,ocu.firstname as firstname,ocu.lastname as lastname,ocu.username as mobile_number,oc_unit.unit_name as unit_name 
			FROM `oc_runner_cash_position` as ocr left join `oc_user` as ocu on ocu.user_id=ocr.runner_id 
			
			left join oc_user_to_unit on ocr.runner_id=oc_user_to_unit.user_id
			left join oc_unit on oc_unit.unit_id=	oc_user_to_unit.unit_id		
			where ocr.runner_id!='' and ocu.status=1 and ocu.user_group_id=22
			
			";
            if($data["filter_user"]!="")
			{
				$sql.=" and ocr.runner_id='".$data["filter_user"]."'   ";
			} 
            $sql.=" Order by ABS(amount) desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
          //echo $sql;     
		$query = $this->db->query($sql);
                
		return $query->rows;
	}
          public function get_runner_total_Cash_position($data = array()) {
	   $sql=" select count(*) as total,sum(amount) as total_amount from ( SELECT ocr.*,ocu.firstname as firstname,ocu.lastname as lastname FROM `oc_runner_cash_position` as ocr join `oc_user` as ocu on ocu.user_id=ocr.runner_id  where ocr.runner_id!='' and ocu.status=1 and ocu.user_group_id=22 ";
                if($data["filter_user"]!="")
		{
			$sql.=" and ocr.runner_id='".$data["filter_user"]."'   ";
 		} 
            $sql.=" ) as aa ";
           
		$query = $this->db->query($sql);
                
		return $query->row;
           
	}
	public function get_runner_Cash_position_companywise($data = array()) {
	   
            $sql=" SELECT ocr.*,ocu.firstname as firstname,ocu.lastname as lastname,ocu.username as mobile_number,oc_unit.unit_name as unit_name FROM `oc_runner_cash_position` as ocr"
                    . " join `oc_user` as ocu on ocu.user_id=ocr.runner_id  "
                    . "  left join oc_user_to_unit on ocr.runner_id=oc_user_to_unit.user_id
			left join oc_unit on oc_unit.unit_id=	oc_user_to_unit.unit_id	 ";
                $sql .=" where ocu.company_id='".$data['filter_company']."' ";
				$sql .=" and ocu.status='1' and ocu.user_group_id=22 ";
				if($data["filter_user"]!="")
			{
				$sql.=" and ocr.runner_id='".$data["filter_user"]."'   ";
			} 
				
            $sql.=" Order by ABS(ocr.amount) desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
              //  echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}
          public function get_runner_total_Cash_position_companywise($data = array()) {
	   $sql=" select count(*) as total,sum(amount) as total_amount from ( SELECT ocr.*,ocu.firstname as firstname,ocu.lastname as lastname FROM `oc_runner_cash_position` as ocr 
	   left join `oc_user` as ocu on ocu.user_id=ocr.runner_id  
	   where ocr.runner_id!='' and ocu.status=1 and ocu.user_group_id=22 ";
         $sql .=" and ocu.company_id='".$data['filter_company']."' ";
		if($data["filter_user"]!="")
		{
			$sql.=" and ocr.runner_id='".$data["filter_user"]."'   ";
 		} 
            $sql.=" ) as aa ";
           
		$query = $this->db->query($sql);
                
		
         // echo $sql; 
		$query = $this->db->query($sql);
                
		return $query->row;
           
	}
        
         /*
public function accept_reject_cash($tr_id,$logged_user,$status) {
	
	        $log=new Log("ce-accept-reject-".date('Y-m-d').".log");

                      $sql110="select status,bank_id,accept_by from  `oc_bank_transaction`   where  `transid`='".$tr_id."' ";
                      $log->write($sql110);
                      $query110 = $this->db->query($sql110);
                      $old_status=$query110->row["status"];
					  $old_accept_by=$query110->row["accept_by"];
                      $bank_id=$query110->row["bank_id"];
                      $log->write($query110->row);
                      if(($old_status=="0") && (empty($old_status)) && (empty($old_accept_by)))
                     {

	       $date_updated=date('Y-m-d h:i:s');
                     $sql="update `oc_bank_transaction` set `status`='".$status."',`accept_by`='".$logged_user."',`date_updated`='".$date_updated."'  where  `transid`='".$tr_id."' ";
                      
                      $log->write($sql);
                      $query = $this->db->query($sql);
                      if($status==1)
                      {
                      $sql11="select user_id,store_id,amount,bank_name from  `oc_bank_transaction`   where  `transid`='".$tr_id."' ";
                      $log->write($sql11);
                      $query11 = $this->db->query($sql11);
                      $user_id=$query11->row["user_id"];
                      $store_id=$query11->row["store_id"];
	        $bank_name=$query11->row["bank_name"];
                      $log->write($query11->row);
                      
                      $amount=$query11->row["amount"];
                      if(empty($amount))
                      {
                       $amount=$this->request->post['amount'];
                      }
                      if(empty($amount))
                      {
                        $amount=$this->request->get['amount'];
                      }
					  $log->write($amount);
                       if($bank_id!="4")
                       {
                        $sql12 = "update " . DB_PREFIX . "user set cash = cash - ".$amount."  where user_id = ".$user_id; 
                        $log->write($sql12);
                        $this->db->query($sql12);
                        //$sql2 = "insert into oc_store_cash_trans set amount =  ".$amount.", store_id =  ".$store_id.",user_id = ".$user_id.",tr_type='DR' ";
                        //$log->write($sql2);
                        //$this->db->query($sql2);

	          $this->load->library('trans');
                        $trans=new trans($this->registry);
	          $trans->addstoretrans($amount,$store_id,$user_id,'DB',$tr_id,'CASHDEPOSIT',$amount,' Cash Deposited by store to Collection Executive via-'.$bank_name); 
                        return $amount;
                        }
                        else
                        {
		return 0;
                        }
                       
                       }
                       else ////////means status is already updated/////////
                       {
                           return 0;
                        }
                      }
		
	}
       
	public function add_to_runner_credit($logged_user,$amount) {
	
                //$date_updated=date('Y-m-d h:i:s');
                $sql="INSERT INTO `oc_runner_cash_position` (runner_id,amount) VALUES (".$logged_user.",".$amount.") ON DUPLICATE KEY UPDATE amount=amount+".$amount;
                $log=new Log("ce-".date('Y-m-d').".log");
                $log->write($sql);
                //echo $sql;
		$query = $this->db->query($sql);

		
	}
	public function add_to_runner_debit($logged_user,$amount) {
	
            //$date_updated=date('Y-m-d h:i:s');
            $sql="INSERT INTO `oc_runner_cash_position` (runner_id,amount) VALUES (".$logged_user.",".$amount.") ON DUPLICATE KEY UPDATE amount=amount-".$amount;
                  
            //echo $sql;
		$query = $this->db->query($sql);

		
	}
         
	public function add_to_trans_table($tr_id,$logged_user,$status,$amount) {
	
            $sql="INSERT INTO `oc_runner_cash_transactions`  (runner_id,trans_type,amount,transid) VALUES (".$logged_user.",'".$status."',".$amount.",".$tr_id.")";
            $query = $this->db->query($sql);
            $log=new Log("ce-".date('Y-m-d').".log");
            $log->write($sql);
		
	}
         */
        
        
	///////////////////////letter////////////
	public function  getTagReport($data = array()) {
	
            $sql="SELECT obt.*,obt.store_id,oc_store.name as store_name FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id where obt.bank_id='4' and obt.tagged_letter_number!=''   ";
  
	if ($data['type']=='pending') 
	{
      		$sql.=" and status='0' ";
	}
	else
	{
      		$sql.=" and status='1' ";
	}
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
		if (!empty($data['username'])) {
			$sql .= " AND obt.accept_by = '" . $this->db->escape($data['username']) . "'";
		}
            $sql.=" Order by obt.date_updated  desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                            //echo $sql;
		$query = $this->db->query($sql);
                	$log=new Log("runner-letter-".date('Y-m-d').".log"); 
                	$log->write($sql);

		return $query->rows;
	}
public function accept_tag_bill($tr_id,$logged_user,$status,$filename) {
	
	        $log=new Log("runner-letter-".date('Y-m-d').".log");

                      $sql110="select status,bank_id from  `oc_bank_transaction`   where  `transid`='".$tr_id."' ";
                      $log->write($sql110);
                      $query110 = $this->db->query($sql110);
                      $old_status=$query110->row["status"];
                      $bank_id=$query110->row["bank_id"];
                      $log->write($query110->row);
                      if($old_status=="0")
                      {
                        $date_updated=date('Y-m-d h:i:s');
                        $sql="update `oc_bank_transaction` set `status`='".$status."',`accept_by`='".$logged_user."',`date_updated`='".$date_updated."',`tag_slip`='".$filename."'  where  `transid`='".$tr_id."' "; 
                        $log->write($sql);
                        $query = $this->db->query($sql);
                        if($status==1)
                        {
                                return 1;
                        }
                        else ////////means status is already updated/////////
                        {
                           return 0;
                        }
                      }
		
	}
/////////////////cash/////////////////////
public function  getCash($data = array()) {
	
            $sql="SELECT obt.*,obt.store_id,oc_store.name as store_name FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id where obt.bank_name!=''  ";

	if ($data['type']=='pending') 
	{
      		$sql.=" and status='0' ";
	}
	else
	{
      		$sql.=" and status in ('1','2')  ";
	}  

            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
		if (!empty($data['filter_ce_id'])) {
			$sql .= " AND obt.accept_by = '" . $this->db->escape($data['filter_ce_id']) . "'";
		}
            $sql.=" Order by obt.transid desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20; 
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                            //echo $sql;
		$query = $this->db->query($sql);
                $log=new Log("cash-new".date('Y-m-d').".log");
		$log->write($sql);

		return $query->rows;
	}
///////////////////
        /*
public function accept_reject_cash($tr_id,$logged_user,$status,$filename) {
	
	        $log=new Log("cash-new".date('Y-m-d').".log");
		$log->write('accept_reject_cash by runner called');
                      $sql110="select status,bank_id from  `oc_bank_transaction`   where  `transid`='".$tr_id."' ";
                      $log->write($sql110);
                      $query110 = $this->db->query($sql110);
                      $old_status=$query110->row["status"];
                      $bank_id=$query110->row["bank_id"];
                      $log->write($query110->row);
                      if($old_status=="0")
                     {

	       $date_updated=date('Y-m-d h:i:s');
                     $sql="update `oc_bank_transaction` set `status`='".$status."',`accept_by`='".$logged_user."',`date_updated`='".$date_updated."',`cash_slip`='".$filename."'  where  `transid`='".$tr_id."' ";
                      
                      $log->write($sql);
                      $query = $this->db->query($sql);
                      if($status==1)
                      {
                      $sql11="select user_id,store_id,amount from  `oc_bank_transaction`   where  `transid`='".$tr_id."' ";
                      $log->write($sql11);
                      $query11 = $this->db->query($sql11);
                      $user_id=$query11->row["user_id"];
                      $store_id=$query11->row["store_id"];
                      $log->write($query11->row);
                      
                      $amount=$query11->row["amount"];
                      if(empty($amount))
                      {
                       $amount=$this->request->post['amount'];
                      }
                      if(empty($amount))
                      {
                        $amount=$this->request->get['amount'];
                      }
                       if($bank_id!="4")
                       {
					   
                        return $amount;
                        }
                        else
                        {
		return 0;
                        }
                       
                       }
                       else ////////means status is already updated/////////
                       {
                           return 0;
                        }
                      }
		
	}
	*/
	

	public function add_to_runner_credit($logged_user,$amount) {
	
                //$date_updated=date('Y-m-d h:i:s');
                $sql="INSERT INTO `oc_runner_cash_position` (runner_id,amount) VALUES (".$logged_user.",".$amount.") ON DUPLICATE KEY UPDATE amount=amount+".$amount;
                $log=new Log("cash-new".date('Y-m-d').".log");
                $log->write($sql);
                //echo $sql;
	 $query = $this->db->query($sql);

		
	}
	public function add_to_runner_debit($logged_user,$amount) {
	
            //$date_updated=date('Y-m-d h:i:s');
            $sql="INSERT INTO `oc_runner_cash_position` (runner_id,amount) VALUES (".$logged_user.",".$amount.") ON DUPLICATE KEY UPDATE amount=amount-".$amount;
                  
            //echo $sql;
		$query = $this->db->query($sql);
	$log=new Log("cash-new".date('Y-m-d').".log");
            $log->write($sql);
		
	}
	public function add_to_trans_table($tr_id,$logged_user,$status,$amount) 
	{
		$log=new Log("cash-new".date('Y-m-d').".log");
		$sql1="SELECT amount FROM `oc_runner_cash_position`  where runner_id='".$logged_user."' limit 1 ";

		$query1 = $this->db->query($sql1);
                
		$log->write($sql1);

		$current_balance=$query1->row['amount'];
		
        $sql="INSERT INTO `oc_runner_cash_transactions`  (runner_id,trans_type,amount,transid,current_balance) VALUES (".$logged_user.",'".$status."',".$amount.",".$tr_id.",".$current_balance.")";
        $query = $this->db->query($sql);
        $log->write($sql);
		
	}
public function  getCashDeposited($data = array()) {
	
            $sql="SELECT oc_bank_deposit_runner.*,oc_bank.bank as bank_name FROM `oc_bank_deposit_runner` left join oc_bank on  oc_bank_deposit_runner.bank=oc_bank.bank_id where   status in (0,1,2) and  date(submit_date)>='2018-04-01'  ";


            if (!empty($data['filter_date_start'])) {
			$sql .= " and date(submit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(submit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		
		if (!empty($data['filter_ce_id'])) {
			$sql .= " AND user_id = '" . $this->db->escape($data['filter_ce_id']) . "'";
		}
            $sql.=" Order by SID desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20; 
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                            //echo $sql;
		$query = $this->db->query($sql);
                $log=new Log("cash-new".date('Y-m-d').".log");
		$log->write($sql);

		return $query->rows;
	}
public function get_runner_deposited_Cash($username) {
	   
            $sql=" SELECT sum(amount) as amount FROM `oc_bank_deposit_runner`  where user_id ='".$username."' and status='0' and  date(submit_date)>='2018-04-01'   ";
                
                            
		$query = $this->db->query($sql);
                	 $log=new Log("cash-new".date('Y-m-d').".log");
		$log->write($sql);
		return $query->row["amount"];
	}
        public function accept_reject_cash($amount,$logged_user,$status,$filename,$bankId) {
	
	  $log=new Log("cash-new".date('Y-m-d').".log");
      
                $date_updated=date('Y-m-d');
                $sql="insert into  `oc_bank_deposit_runner`  set `user_id`='".$logged_user."',`amount`='".$amount."',`bank`='".$bankId."', `status`='0',`deposited_by`='".$logged_user."',`deposit_date`='".$date_updated."',`uploded_file`='".$filename."'   ";
                      
                $log->write($sql);
                return $query = $this->db->query($sql);
                      
                      
		
	}
        public function getrunnerunitlist($userid)
	{
		$sql="SELECT oc_unit.unit_id as unit_id,oc_unit.unit_name as unit_name from oc_unit left join oc_user on oc_unit.company_id=oc_user.company_id where oc_user.user_id='".$userid."'  ";
		$query = $this->db->query($sql);
                	$log=new Log("runner-letter-".date('Y-m-d').".log"); 
                	$log->write($sql);

		return $query->rows;
	}
        /////////////////////// letter wise/////////////
public function getTaggedBillWithLetter($data)
{
	if ($data['type']=="pending")
	{
		$sql = "SELECT oc_tagged_bill_trans.* FROM `oc_tagged_bill_trans`  ";
		$sql.="  where oc_tagged_bill_trans.date_start!=''  ";//".$data['filter_date_start']."

		if (!empty($data['filter_store']))
		{
			$sql .=" AND oc_tagged_bill_trans.store_id='".$data['filter_store']."'";
		}
		if (!empty($data['filter_unit']))
		{
			$sql .=" AND oc_tagged_bill_trans.unit_id='".$data['filter_unit']."'";
		}
		
	}

	if ($data['type']=="submitted")
	{
		$sql .=" AND o.user_agent!=''";
		
	}
	$sql.=" and oc_tagged_bill_trans.status='0' ";
		$sql .=" order by oc_tagged_bill_trans.sid ASC  ";
	if (isset($data['start']) || isset($data['limit'])) 
	{
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$log=new Log("runner-letter-".date('Y-m-d').".log");
		$log->write('getTaggedBillWithLetter');
		$log->write($sql);
//echo $sql;
$query = $this->db->query($sql);

return $query->rows;
}
public function insert_into_bank_transaction($tr_id,$logged_user,$status,$filename)
{
$log=new Log("runner-letter-".date('Y-m-d').".log");
 
 $sql110="select *  from  `oc_tagged_bill_trans`   where  `sid`='".$tr_id."' ";
                      $log->write($sql110);
                      $query110 = $this->db->query($sql110);
                      $row=$query110->row;

if(count($row)==0)
{

 $log->write('no data found for this letter number');
return '0';
}
$cr_date=date('Y-m-d');

$sql2 = "insert oc_bank_transaction set `user_id`='0',`store_id`='".$this->db->escape($row['store_id'])."',`bank_id`='".$this->db->escape('4')."',`bank_name`='".$this->db->escape('TAGGED BILLS')."',`amount`='".$this->db->escape($row['total_amount'])."',`status`='".$this->db->escape($status)."',`accept_by`='".$this->db->escape($logged_user)."',`tag_slip`='".$filename."',`tagged_letter_number`='".$tr_id."' ";

$sql="update  `oc_tagged_bill_trans` set `status`='".$status."',`accepted_user`='".$logged_user."',`accepted_date`='".$date_updated."',`submission_date`='".$date_updated."'   where  `sid`='".$tr_id."' "; 
                        $log->write($sql);
                        $query = $this->db->query($sql);
$log->write($sql2);

$query2 = $this->db->query($sql2);
return $this->db->getLastId();
}
	
}