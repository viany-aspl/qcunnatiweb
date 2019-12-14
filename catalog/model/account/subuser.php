<?php
class ModelAccountSubuser extends Model {
	
		public function getCashSales($uid)
		{

		$log=new Log("SubUserCashSales-".date('Y-m-d').".log");
		$log->write($uid);
		
			/*$sql="select 
				SUM(CASE WHEN payment_method ='Cash'  THEN total ELSE 0 END) AS Cash_Sales,
				SUM(CASE WHEN payment_method ='Tagged' THEN total ELSE 0 END) AS Tagged_Sales,
				SUM(CASE WHEN payment_method ='Tagged Cash'  THEN total ELSE 0 END) AS Cash_Tagged,
				SUM(CASE WHEN payment_method ='Cash Subsidy' THEN total ELSE 0 END) AS Cash_Subsidy,
				SUM(CASE WHEN payment_method ='Tagged Subsidy' THEN total ELSE 0 END) AS Tagged_Subsidy
				from oc_order  
                WHERE
                user_id ='".$uid."'                            
                AND order_status_id = '5'
				GROUP BY store_id";*/
				
				$sql="SELECT concat(oc_user.firstname,' ',oc_user.lastname) as subusername,
						oc_user.user_id,oc_order.store_name,
						sum(oc_order.cash) as Cash_Sales,
						sum(oc_order.tagged) as Tagged_Sales,
						sum(oc_order.subsidy) asCash_Subsidy,
						oc_user.cash as cash_inhand 
						FROM oc_order 
						left join oc_user on oc_user.user_id=oc_order.user_id
						where oc_order.user_id='".$uid."' and oc_order.order_status_id=5 ";
				$log->write($sql);
			    $query = $this->db->query($sql);
		
			
                    $log->write($query->rows);
					return $query->rows;
		
		}
		
		
		public function StoreInchargeSummary($sid)
		{

		$log=new Log("StoreInchargeCashSummary-".date('Y-m-d').".log");
		
		/*SUM(CASE WHEN payment_method ='Cash'  THEN Cash ELSE 0 END) AS Cash_Sales,
					SUM(CASE WHEN payment_method ='Tagged' THEN Tagged ELSE 0 END) AS Tagged_Sales,
					SUM(CASE WHEN payment_method ='Tagged Cash'  THEN Subsidy ELSE 0 END) AS Cash_Tagged,
					SUM(CASE WHEN payment_method ='Cash Subsidy' THEN total ELSE 0 END) AS Cash_Subsidy,
					SUM(CASE WHEN payment_method ='Tagged Subsidy' THEN total ELSE 0 END) AS Tagged_Subsidy*/
		
			/*$sql="select concat(oc_user.firstname,' ',oc_user.lastname) as subusername,oc_user.user_id,
					sum(oc_order.cash) AS Cash_Sales,sum(oc_order.tagged) AS Tagged_Sales,sum(oc_order.subsidy) AS Cash_Subsidy,oc_user.cash as cash_inhand
					from oc_order  
					left join oc_user on oc_user.user_id=oc_order.user_id
					WHERE
					oc_order.user_id in (SELECT user_id FROM oc_user where user_group_id='36' and  status='1' and store_id='".$sid."')
					AND oc_order.order_status_id = '5'
					GROUP BY oc_order.user_id";*/
					
				$sql="SELECT concat(oc_user.firstname,' ',oc_user.lastname) as subusername,
						oc_user.user_id,oc_order.store_name,
						sum(oc_order.cash) as Cash_Sales,
						sum(oc_order.tagged) as Tagged_Sales,
						sum(oc_order.subsidy) asCash_Subsidy,
						oc_user.cash as cash_inhand 
						FROM oc_order 
						left join oc_user on oc_user.user_id=oc_order.user_id
						where oc_order.user_id!='' and oc_order.store_id='".$sid."'  
						and oc_user.user_group_id='36' 
						and oc_order.order_status_id=5
						GROUP BY oc_order.user_id ";
					
					
				$log->write($sql);
				$query = $this->db->query($sql);
	
                $log->write($query->rows);
				return $query->rows;
		
		}
		
		
		
		
		
		public function getAllStoreIncharge($data = array()) {
	   $sql=" SELECT user_id,concat(firstname,' ',lastname) as name FROM oc_user where user_group_id='11' and  status='1'";
if($data["filter_store"]!="")
{
$sql.=" and store_id='".$data["filter_store"]."'   ";
  }   

           $log=new Log("getallstoreincharge-".date('Y-m-d').".log"); 
           $log->write($sql);
		$query = $this->db->query($sql);
        //  echo $sql;      
		return $query->rows;
           
	}
	
	
	public function getStoresubuserSummary($data = array()) {
		  $log=new Log("getStoresubuserSummary-".date('Y-m-d').".log"); 
		  $sql="SELECT user_id,concat(firstname,' ',lastname) as name,cash FROM oc_user where user_group_id='36' and  status='1'";
			if($data["filter_store"]!="")
			{
			$sql.=" and store_id='".$data["filter_store"]."'   ";
			  }   

         
            $log->write($sql);
		    $query = $this->db->query($sql);
        //  echo $sql;      
		return $query->rows;
           
	}
	public function getStoresubuserSummarydtl($data = array()) {
		  $log=new Log("getStoresubuserSummarydtl-".date('Y-m-d').".log");
		  
		  //$sql="SELECT amount as cash,DATE(date_added) as dat,transid FROM oc_bank_transaction ";
		  $sql="SELECT amount as cash,DATE(date_added) as dat,transid FROM oc_bank_transaction ";
			if($data["user_id"]!="")
			{
			$sql.=" where user_id='".$data["user_id"]."'   ";
			  }   
$sql.=" ORDER BY date_added desc";
         
            $log->write($sql);
		    $query = $this->db->query($sql);
        //  echo $sql;      
		return $query->rows;
           
	}
	
	
	
	public function insert_subuser_receive_products_otp_trans($data) 
        {
				
				$log=new Log("request_order_trans-".date('Y-m-d').".log");
				$sql="insert into oc_contractor_product_otp_trans  SET otp='".$data['otp']."',store_id='".$data['store_id']."',user_id='".$data['user_id']."',contractor_id='".$data['uid']."',trans_detail='".$data['products']."',system_trans_id = '" . $this->db->escape($data['system_trans_id']) . "',imei = '" . $this->db->escape($data['imei']) . "', cr_date=NOW()";               
                $log->write($sql);
                $ret_id=$this->db->query($sql); 
				$retid=$this->db->getLastId();
				$log->write("query return id");
				$log->write($retid);
				return $retid;
		}
		
		public function insert_subuser_receive_products($data) 
        {
				
				$log=new Log("subuser_request_order-".date('Y-m-d').".log");                                               
                $sql="insert into oc_contractor_product  SET product_id='".$data['product_id']."',name='".$data['product_name']."',quantity='".$data['quantity']."',order_id='0',price='".$data['price']."',tax='".$data['tax']."',contractor_id = '" .$data['id']. "', store_id = '" .$data['store_id']. "', motp = '" .$data['otp']. "',transfered_by='" .$data['user_id']. "', updatedate=NOW()";
                //$this->db->query($sql);
                $log->write($sql);
                $ret_id=$this->db->query($sql); 
				$retid=$this->db->getLastId();
				 $log->write("query return id");
				$log->write($ret_id);
				return $ret_id;
                        
		}
		public function getusermobile($userid)
		{ 
		$log=new Log("subuser_request_order-".date('Y-m-d').".log");
		$sql="SELECT username,email from oc_user where user_id='".$userid."' "; 
		$query = $this->db->query($sql);
		$log->write($query->row);
		return $query->row;
 
		}
		public function getproductbarred()
		{ 
		$log=new Log("getproductbarred-".date('Y-m-d').".log");
		$sql="SELECT product_id from oc_product_barred where  `status`='1'"; 
		$query = $this->db->query($sql);
		$log->write($query->rows);
		return $query->rows;
 
		}
		public function getSubUserlist($data)
		{ 
		$sql="SELECT user_id,concat(firstname,' ',lastname) as name 
		FROM oc_user where store_id='".$data['store_id']."' 
		and user_group_id='36' and  status='1'"; 

		$query = $this->db->query($sql);

		return $query->rows;
 
		}
		public function getstorename($store_id)
		{  
            $log=new Log("cash-new".date('Y-m-d').".log");
            $sql='SELECT name FROM oc_store WHERE store_id="'.$store_id.'"  ';
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->row); 
            return $query->row['name'];    
        } 
		
	public function cheksubuserotp($data) 
	{
       	$log=new Log("subuser_request_order-".date('Y-m-d').".log");
		$log->write($otp);
		$log->write($user_id);
        $sql="SELECT otp FROM `oc_contractor_product_otp_trans`  
		where store_id ='".$data['store_id']."'  
		and contractor_id='".$data['id']."' 
		and sid='".$data['last_order_id']."' 
		and system_trans_id='".$data['system_trans_id']."' 
		and imei='".$data['imei']."' order by sid desc  limit 1";
		$log->write($sql);
		//$query = $this->db->query($sql);
		$ret=$this->db->query($sql);
		$log->write($ret->row['otp']);
		return $ret->row['otp'];
    }	
	public function getBilledMaterial($contrator_id,$data=array())
		{  
		//$contracter_id='242';
            $log=new Log("getBilledMaterial-".date('Y-m-d').".log");
			 $log->write($contrator_id); 
            $sql="select  * from 
			(
					select product_id,name,sum(material_issue) as ms ,sum(biilled) as billed,(sum(material_issue) -sum(biilled)) as bal from 
					(
					SELECT product_id,name,sum(quantity) as material_issue ,'0' as biilled FROM oc_contractor_product 
					where contractor_id='".$contrator_id."'  ";
					if ($data['start_date']!='') 
						{
							$sql .= " AND date(updatedate) >= '" . $this->db->escape($data['start_date']) . "'";
						}
						if ($data['end_date']!='') 
						{
							$sql .= " AND date(updatedate) <= '" . $this->db->escape($data['end_date']) . "'";
						}
					
					$sql.=" group by product_id 
					
					union
					
					SELECT product_id,name,'0' as material_issue, sum(quantity) as biilled from oc_order_product 
					where order_id 
					in(
					SELECT order_id FROM oc_order where user_id='".$contrator_id."' and oc_order.order_status_id=5 ";
					if ($data['start_date']!='') 
						{
							$sql .= " AND date(oc_order.date_added) >= '" . $this->db->escape($data['start_date']) . "'";
						}
						if ($data['end_date']!='') 
						{
							$sql .= " AND date(oc_order.date_added) <= '" . $this->db->escape($data['end_date']) . "'";
						}
					$sql.=" )  group by product_id 
					) a  group by product_id order by name
					) b where ms >0 ";
            $query = $this->db->query($sql);  
            $log->write($sql);
            //$log->write($query->rows); 
			//echo $sql; exit;
            return $query->rows;    
        } 
		
		public function getBilledMaterialProductBased($contrator_id,$prd_id)
		{  
		//$contracter_id='242';
            $log=new Log("Order_getBilledMaterial-".date('Y-m-d').".log");
			 $log->write($contrator_id); 
            $sql="select  * from (
					select product_id,name,sum(material_issue) as ms ,sum(biilled) as billed,(sum(material_issue) -sum(biilled)) as bal from (
					SELECT product_id,name,sum(quantity) as material_issue ,'0' as biilled FROM oc_contractor_product where product_id='".$prd_id."' and contractor_id='".$contrator_id."'  group by product_id 
					union
					
					SELECT product_id,name,'0' as material_issue, sum(quantity) as biilled from oc_order_product 
					where product_id='".$prd_id."' and 
					order_id in(SELECT order_id FROM oc_order where user_id='".$contrator_id."' and oc_order.order_status_id=5 )  group by product_id 
					) a  group by product_id order by name
					) b where ms >0 ";
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->row);
			$retval=0;
				if(!empty($query->row['bal']) && ($query->row['bal']>0))
				{
					$retval=$query->row['bal'];
				}
			//echo $sql; exit;
            return $retval;    
        } 
		
		public function getBilledMaterialproductdtl($productid,$contractor_id,$data=array())
		{  
		//$contracter_id='242';
            $log=new Log("getBilledMaterialdtl-".date('Y-m-d').".log");
			 $log->write($contrator_id); 
			 $log->write($productid); 
            $sql="SELECT quantity ,DATE(updatedate) as dat,
			(select concat(firstname,' ',lastname) 
			from oc_user where user_id=transfered_by) as trans_by 
			FROM oc_contractor_product where product_id='".$productid."' and  contractor_id='".$contractor_id."'";
			if ($data['start_date']!='') 
						{
							$sql .= " AND date(updatedate) >= '" . $this->db->escape($data['start_date']) . "'";
						}
						if ($data['end_date']!='') 
						{
							$sql .= " AND date(updatedate) <= '" . $this->db->escape($data['end_date']) . "'";
						}
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->rows); 
			//echo $sql; exit;
            return $query->rows;    
        } 	

		public function getUserCash($user_id)
		{  
		//$contracter_id='242';
            $log=new Log("getUserCash-".date('Y-m-d').".log");
			 $log->write($contrator_id); 
            $sql="SELECT cash FROM oc_user where user_id='".$user_id."' ";
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->rows); 
			//echo $sql; exit;
            return $query->row['cash'];    
        } 
		
		
		
		public function getCashReport($uid,$sdate,$edate)
		{

		$log=new Log("SubUserTaggedSales".date('Y-m-d').".log");
		
		if(empty($sdate))
		{
			$sql="select product_id,name,sum(quantity) as quantity,(price) as price,
			tax  from  oc_order_product  
			where order_id in 
			( select order_id from oc_order 
				where user_id='".$uid."' 
				AND DATE(date_added)='".date('Y-m-d')."' 
				AND (payment_method ='Tagged' or payment_method ='Tagged Cash' ) 
				AND order_status_id='5') 
			group by product_id";
			$query = $this->db->query($sql);
		}else{
			$query = $this->db->query("select product_id,name,
			sum(quantity) as quantity,(price) as price,
			tax  from  oc_order_product  
			where order_id in 
			( select order_id from oc_order 
				where user_id='".$uid."' 
				AND DATE(date_added)='".$sdate."' 
				AND (payment_method ='Tagged' or payment_method ='Tagged Cash' ) 
				AND order_status_id='5') 
			group by product_id");

			}
					return $query->rows;
		
		}
		public function getCashTagged($uid,$sdate,$edate)
		{

		$log=new Log("SubUserCashTagged".date('Y-m-d').".log");
		$log->write("select product_id,name,sum(quantity) as quantity,(price+tax) as price  from  oc_order_product  
			where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND order_status_id='5') 
		
		group by product_id");
		if(empty($sdate))
		{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  
			oc_order_product  
				where order_id in ( select order_id from oc_order where user_id='".$uid."' 
				AND DATE(date_added)='".date('Y-m-d')."' 
				AND payment_method='Cash' AND order_status_id='5') 
			group by product_id");
		}
		else{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  
			where order_id in 
				( select order_id from oc_order 
				where user_id='".$uid."' 
				AND DATE(date_added)='".$sdate."' 
				AND payment_method='Cash' 
				AND order_status_id='5') 
			group by product_id");

			}
			if(empty($edate))
		{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  
			where order_id in 
				( select order_id from oc_order 
				where user_id='".$uid."' 
				AND DATE(date_added)='".date('Y-m-d')."' 
				AND payment_method='Cash' 
				AND order_status_id='5') 
			group by product_id");
		}
		else{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  
			where order_id in 
				( select order_id from oc_order 
				where user_id='".$uid."' 
				AND DATE(date_added)='".$edate."' 
				AND payment_method='Cash' 
				AND order_status_id='5') 
			group by product_id");

			}

					return $query->rows;
		
		}
		public function getCashSubsidy($uid,$sdate,$edate)
		{

		$log=new Log("SubUserCashSubsidy".date('Y-m-d').".log");
		$log->write("select product_id,name,sum(quantity) as quantity,(price+tax) as price  from  oc_order_product  
			where order_id in 
			( select order_id from oc_order 
			where user_id='".$uid."' 
			AND DATE(date_added)='".date('Y-m-d')."' 
			AND order_status_id='5') 
		group by product_id");
		if(empty($sdate))
		{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  
				where order_id in 
				( select order_id from oc_order 
				where user_id='".$uid."' 
				AND DATE(date_added)='".date('Y-m-d')."' 
				AND payment_method='Cash' 
				AND order_status_id='5')
			group by product_id");
		}
		else{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  
			where order_id in 
				( select order_id from oc_order 
				where user_id='".$uid."' 
				AND DATE(date_added)='".$sdate."' 
				AND payment_method='Cash' 
				AND order_status_id='5') 
			group by product_id");

			}
			if(empty($edate))
		{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  
			where order_id in 
				( select order_id from oc_order 
				where user_id='".$uid."' 
				AND DATE(date_added)='".date('Y-m-d')."' 
				AND payment_method='Cash' 
				AND order_status_id='5') 
			group by product_id");
		}
		else{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  
			where order_id in 
				( select order_id from oc_order
				where user_id='".$uid."' 
				AND DATE(date_added)='".$edate."' 
				AND payment_method='Cash' 
				AND order_status_id='5') 
			group by product_id");

			}

					return $query->rows;
		
		}
		public function getmaterial_summary($uid,$data)
		{
			if(empty($data['start_date']))
			{
				$data['start_date']=(date('Y')-1).'-10-01';
			}
		$log=new Log("meterial_summary-".date('Y-m-d').".log");
		$log->write($data);
		$sql="select  * from (
		
					select contractor_id,product_id,name,sum(material_issue) as ms ,sum(biilled) as billed,
					(sum(material_issue) -sum(biilled)) as bal from
					(
						SELECT contractor_id,product_id,name,sum(quantity) as material_issue ,'0' as biilled FROM oc_contractor_product 
						where contractor_id='".$data['subuser_id']."' ";

						if ($data['start_date']!='') 
						{
							$sql .= " AND date(updatedate) >= '" . $this->db->escape($data['start_date']) . "'";
						}
						if ($data['end_date']!='') 
						{
							$sql .= " AND date(updatedate) <= '" . $this->db->escape($data['end_date']) . "'";
						}
	
						$sql.=" group by product_id 
						
						
						union
						
						
						SELECT '0' as contractor_id,product_id,name,'0' as material_issue, sum(quantity) as biilled 
						from oc_order_product where order_id in
						(
						SELECT order_id FROM oc_order where user_id='".$data['subuser_id']."' and order_status_id=5 
						";
						if ($data['start_date']!='') 
						{
							$sql .= " AND date(oc_order.date_added) >= '" . $this->db->escape($data['start_date']) . "'";
						}
						if ($data['end_date']!='') 
						{
							$sql .= " AND date(oc_order.date_added) <= '" . $this->db->escape($data['end_date']) . "'";
						}
						
						$sql.=" )  
					group by product_id 
					) a  group by product_id order by name
					) b where ms >0 ";
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
            $log->write($sql);
            //$log->write($query->rows); 
			//echo $sql; exit;
            return $query->rows;   
		
		}
		public function getmaterial_detail($data)
		{  
		
            $log=new Log("getmaterial_detail-".date('Y-m-d').".log");
			 $log->write($data); 
		
            $sql="SELECT  oc_order.store_name,oc_order_product.product_id,oc_order_product.name,oc_order_product.quantity,
				DATE(oc_order_product.ORD_DATE) as dat
				  FROM oc_order 
				  left join oc_order_product on oc_order_product.order_id=oc_order.order_id
				  where user_id='".$data['subuserid']."' 
				  and product_id='".$data['productid']."'
				  and oc_order.order_status_id=5
				  ";
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->rows); 
			//echo $sql; exit;
            return $query->rows;    
        } 
		
		public function getmaterial_detail_con($data)
		{  
			//$contracter_id='242';
            $log=new Log("getmaterial_detail-".date('Y-m-d').".log");
			 $log->write($data); 
		
            $sql="SELECT oc_contractor_product.product_id,oc_contractor_product.name,oc_store.name as store_name,oc_contractor_product.quantity,
				DATE(oc_contractor_product.updatedate) as dat 
				FROM oc_contractor_product
				left join oc_store on oc_store.store_id=oc_contractor_product.store_id
				  where oc_contractor_product.contractor_id='".$data['subuserid']."' 
				  and oc_contractor_product.product_id='".$data['productid']."'
				  
				  ";
			if(!empty($data['start_date']))
			{
				$sql.=" and DATE(oc_contractor_product.updatedate) >='".$data['start_date']."' ";
			}
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->rows); 
			//echo $sql; exit;
            return $query->rows;    
        } 
		
		
		public function update_oc_contractor_product_otp_trans_status($data)
		{
			$log=new Log("request_order_trans-".date('Y-m-d').".log");                                               
                $sql="update  oc_contractor_product_otp_trans  SET otp_verified_status='1',material_accepted='1' where sid='" .$data['last_order_id']. "'";
                //$this->db->query($sql);
                $log->write($sql);
                $ret_id=$this->db->query($sql); 
				
				$log->write($ret_id);
				return $ret_id;
		}
		
		public function getdocument_upload_type($data = array())
		{
	         $sql=" SELECT sid,document_description FROM oc_document_upload where active='1'";

            $log=new Log("getdocument_upload_type-".date('Y-m-d').".log"); 
            $log->write($sql);
		    $query = $this->db->query($sql);
            //  echo $sql;      
		    return $query->rows;
            
		}
		public function insertdocument_upload_type($data) 
        {
				    $log=new Log("insertdocument_upload_type-".date('Y-m-d').".log"); 
				/********oc_document_upload insert**************************/
				$sql="insert into oc_document_upload_trans  SET document_id='".$data['document_id']."',user_id='".$data['user_id']."',store_id='".$data['store_id']."',remarks='".$this->db->escape($data['remarks'])."', cr_date=NOW()";               
                $log->write($sql);
                $ret_id=$this->db->query($sql); 
				$retid=$this->db->getLastId();
				$log->write($ret_id);						
				
				return $retid;
				
				
				
				/********oc_document_upload insert end**************************/
                        
		}	
		public function upload_document($data) 
        {
			
			$log=new Log("insertdocument_upload_type-".date('Y-m-d').".log"); 
			$sql2="update  oc_document_upload_trans  SET image_name='".$data['file']."' where sid='" .$data['tid']. "'";
			$log->write($sql2);
			return $this->db->query($sql2); 
		}
		public function getorder_summary($uid,$data)
		{
			if(empty($data['start_date']))
			{
				$data['start_date']=(date('Y')-1).'-10-01';
			}
			$log=new Log("order_summary-".date('Y-m-d').".log");
			$log->write($data);
			//$uid=242;
			$sql="SELECT order_id,concat(grower_id,'/',payment_firstname) as payment_firstname,cash,tagged,subsidy,DATE(date_added) as dat
					FROM oc_order where user_id='".$uid."' ";
					if ($data['start_date']!='') 
						{
							$sql .= " AND date(date_added) >= '" . $this->db->escape($data['start_date']) . "'";
						}
						if ($data['end_date']!='') 
						{
							$sql .= " AND date(date_added) <= '" . $this->db->escape($data['end_date']) . "'";
						}
					$sql.=" and oc_order.order_status_id=5
					group by order_id order by DATE(date_added) desc";

			$query = $this->db->query($sql); 
			$log->write($sql);
			$log->write($query->rows); 
			//echo $sql; exit;
			return $query->rows; 

		}
public function getorder_summarydetail($data)
{
$log=new Log("order_detail-".date('Y-m-d').".log"); 
$log->write($data);
$sql="SELECT name,quantity,price,tax,total FROM oc_order_product where order_id='".$data['order_id']."'";

$query = $this->db->query($sql); 
$log->write($sql);
$log->write($query->rows); 
//echo $sql; exit;
return $query->rows; 
}
	////////material return/////////////
		public function getStoreUserlist($data)
		{ 
		$sql="SELECT user_id,concat(firstname,' ',lastname) as name FROM oc_user 
		where store_id='".$data['store_id']."' and user_group_id='11' and  status='1'"; 

		$query = $this->db->query($sql);

		return $query->rows;
 
		}
		public function insert_subuser_return_products_otp_trans($data) 
        {
				
				$log=new Log("subuser_return_order-".date('Y-m-d').".log");
				$sql="insert into oc_contractor_product_otp_trans  SET otp='".$data['otp']."',store_id='".$data['store_id']."',user_id='".$data['user_id']."',contractor_id='".$data['uid']."',trans_detail='".$data['products']."',system_trans_id = '" . $this->db->escape($data['system_trans_id']) . "',imei = '" . $this->db->escape($data['imei']) . "', cr_date=NOW()";               
                $log->write($sql);
                $ret_id=$this->db->query($sql); 
				$retid=$this->db->getLastId();
				$log->write("query return id");
				$log->write($retid);
				return $retid;
		}
		
		public function insert_subuser_return_products($data) 
        {
				
				$log=new Log("subuser_return_order-".date('Y-m-d').".log");  

				
                $sql="insert into oc_contractor_product  SET product_id='".$data['product_id']."',name='".$data['product_name']."',quantity='-".$data['quantity']."',order_id='0',price='".$data['price']."',tax='".$data['tax']."',contractor_id = '" .$data['user_id']. "', store_id = '" .$data['store_id']. "', motp = '" .$data['otp']. "',transfered_by='" .$data['id']. "', updatedate=NOW()";
                //$this->db->query($sql);
                $log->write($sql);
                $ret_id=$this->db->query($sql); 
				 $log->write("query return id");
				$log->write($ret_id);
				return $ret_id; 
                        
		}
		public function check_current_qnty($data) 
        {
				
				$log=new Log("subuser_return_order-".date('Y-m-d').".log");
				$sql="SELECT 
    *
FROM
    (SELECT 
        contractor_id,
            product_id,
            name,
            SUM(material_issue) AS ms,
            SUM(biilled) AS billed,
            (SUM(material_issue) - SUM(biilled)) AS bal
    FROM
        (SELECT 
        contractor_id,
            product_id,
            name,
            SUM(quantity) AS material_issue,
            '0' AS biilled
    FROM
        oc_contractor_product
    WHERE
        contractor_id = '" .$data['user_id']. "'
    GROUP BY product_id UNION SELECT 
        '0' AS contractor_id,
            product_id,
            name,
            '0' AS material_issue,
            SUM(quantity) AS biilled
    FROM
        oc_order_product
    WHERE
        order_id IN (SELECT 
                order_id
            FROM
                oc_order
            WHERE
                user_id = '" .$data['user_id']. "' and oc_order.order_status_id=5 )
    GROUP BY product_id) a
    GROUP BY product_id
    ORDER BY name) b
WHERE
    product_id='" .$data['product_id']. "'
";               
                $log->write($sql);
                $query = $this->db->query($sql);

				return $query->row;
		}
			
}