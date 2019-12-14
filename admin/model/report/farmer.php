<?php
class ModelReportFarmer extends Model {
	
public function getFarmers($data = array()) {
	 $sql="SELECT * from oc_customer  where scheme!='' ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if ($data['filter_scheme']!='') {
			$sql .= " AND scheme = '" . $this->db->escape($data['filter_scheme']) . "'";
		}
                            
            $sql.=" Order by customer_id desc ";
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

	public function getTotalFarmers($data = array()) {
		
             $sql="SELECT count(*) as total  from oc_customer  where scheme!='' ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if ($data['filter_scheme']!='') {
			$sql .= " AND scheme = '" . $this->db->escape($data['filter_scheme']) . "'";
		}
                            
            $sql.=" Order by customer_id desc ";
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
                

		return $query->row['total'];
	}

////////////////////////////
	public function getCash_report($data = array()) {
	   
//SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt RIGHT JOIN oc_store on oc_store.store_id=obt.store_id  WHERE DATE(obt.date_added) >= '2016-10-30' AND DATE(obt.date_added) <= '2017-01-18'
            $sql="SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,obt.mpesa_trans_id,oc_store.name,oc_user.firstname,oc_user.lastname,obt.status FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id left join oc_user on oc_user.user_id=obt.accept_by where obt.bank_name!='' ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
                            if ($data['filter_status']!='') {
			$sql .= " AND obt.status= '" . $this->db->escape($data['filter_status']) . "'";
		}
                            if (!empty($data['filter_user'])) {
			$sql .= " AND obt.accept_by= '" . $this->db->escape($data['filter_user']) . "'";
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
                            if ($data['filter_status']!='') {
			$sql .= " AND obt.status= '" . $this->db->escape($data['filter_status']) . "'";
		}
                            if (!empty($data['filter_user'])) {
			$sql .= " AND obt.accept_by= '" . $this->db->escape($data['filter_user']) . "'";
		}
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

              public function get_bank_sum_cash($data=array())
	{
                     $sql="select max(case when bank_name='HDFC' then  bank_amount else 00.00 end)as HDFC,
max(case when bank_name='State Bank of India' then  bank_amount else 00.00 end)as State_Bank_of_India,
max(case when bank_name='ICICI' then  bank_amount else 00.00 end)as ICICI,
max(case when bank_name='TAGGED BILLS' then  bank_amount else 00.00 end)as TAGGED_BILLS

from (

SELECT obt.bank_name,sum(obt.amount)as bank_amount FROM `oc_bank_transaction` as obt
 LEFT JOIN oc_store on oc_store.store_id=obt.store_id left join
 oc_user on oc_user.user_id=obt.accept_by where obt.bank_name!='' and obt.status='1' 
  ";
            
            if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
                            if ($data['filter_status']!='') {
			$sql .= " AND obt.status= '" . $this->db->escape($data['filter_status']) . "'";
		}
                            if (!empty($data['filter_user'])) {
			$sql .= " AND obt.accept_by= '" . $this->db->escape($data['filter_user']) . "'";
		}
                $sql.=" group by obt.bank_name Order by obt.transid  )as a ";
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}

public function getTotalCash_transationRunnerbank($data = array()) {
		
            $sql="
select max(case when bank_name='HDFC' then  bank_amount else 00.00 end)as HDFC,
max(case when bank_name='State Bank of India' then  bank_amount else 00.00 end)as State_Bank_of_India,
max(case when bank_name='ICICI' then  bank_amount else 00.00 end)as ICICI,
max(case when bank_name='TAGGED BILLS' then  bank_amount else 00.00 end)as TAGGED_BILLS

from (

SELECT obt.bank as bank_name,sum(obt.amount)as bank_amount FROM `oc_bank_deposit_runner` as obt
  left join
 oc_user on oc_user.user_id=obt.user_id where obt.bank!='' and obt.status='1' 
";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(obt.submit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.submit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if ($data['filter_user']!='') {
			$sql .= " AND obt.user_id = '" . $this->db->escape($data['filter_user']) . "'";
		}
                            if ($data['filter_status']!='') {
			$sql .= " AND obt.status = '" . $this->db->escape($data['filter_status']) . "'"; 
		}
            $sql.=" group by obt.bank ) as aa"; 
                            //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
	public function getCash_position($data = array()) {
	 $sql="SELECT oc_user.firstname firstname,oc_user.lastname as lastname,oc_user.username,oc_user.store_id as store_id,oc_store.name as store_name,oc_user.cash as amount,oc_user.audit_status,oc_user.audit_date FROM shop.oc_user join oc_store on oc_user.store_id=oc_store.store_id where oc_user.user_group_id='11' and oc_user.status='1'
and oc_user.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in (1,2) )
 "; 
  if (!empty($data['filter_store'])) {
			$sql .= " and oc_user.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		} 
$sql.=" Order by oc_user.firstname asc  ";
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

	public function get_own_stores_total_eod($data = array())
	{
	$log=new Log("cash-sql".date('Y-m-d').".log");
	$sql="SELECT count(*) as total,sum(amount) as total_amount FROM (SELECT oc_user.firstname,oc_user.lastname,oc_user.username,oc_user.store_id,oc_store.name as store_name,oc_user.cash as amount FROM shop.oc_user join oc_store on oc_user.store_id=oc_store.store_id where oc_user.user_group_id='11' and oc_user.status='1' and oc_user.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in (1,2))  "; 

  if (!empty($data['filter_store'])) {
			$sql .= " and oc_user.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		} 

                $sql.=" ) as aa";
		$log->write($sql);

		$query = $this->db->query($sql);

		return $query->row;
	}
	public function getTotalCash_position($data = array()) {

$log=new Log("cash-sql".date('Y-m-d').".log");
	
$sql="SELECT count(*) as total,sum(amount) as total_amount FROM (SELECT oc_user.firstname,oc_user.lastname,oc_user.username,oc_user.store_id,oc_store.name as store_name,oc_user.cash as amount FROM shop.oc_user join oc_store on oc_user.store_id=oc_store.store_id where oc_user.user_group_id='11' and oc_user.status='1' 
and oc_user.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in (1,2) )
 "; 

  if (!empty($data['filter_store'])) {
			$sql .= " and oc_user.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		} 

                $sql.=" ) as aa";
		$log->write($sql);

		$query = $this->db->query($sql);

		return $query->row;
	}

	      ////////////////////////////
	public function getCash_report_CompanyWise($data = array()) {
	   
//SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt RIGHT JOIN oc_store on oc_store.store_id=obt.store_id  WHERE DATE(obt.date_added) >= '2016-10-30' AND DATE(obt.date_added) <= '2017-01-18'
            $sql="SELECT 
    obt.transid,
    obt.bank_name,
    obt.amount,
    obt.date_added,
    obt.bank_id,
    obt.store_id,
    obt.mpesa_trans_id,
    oc_store.name,
    oc_user.firstname,
    oc_user.lastname,
    obt.status
  
FROM
    `oc_bank_transaction` AS obt
        LEFT JOIN
    oc_store ON oc_store.store_id = obt.store_id
        LEFT JOIN
    oc_user ON oc_user.user_id = obt.accept_by
   
    where obt.bank_name!='' ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
                            if ($data['filter_status']!='') {
			$sql .= " AND obt.status= '" . $this->db->escape($data['filter_status']) . "'";
		}
                            if (!empty($data['filter_user'])) {
			$sql .= " AND obt.accept_by= '" . $this->db->escape($data['filter_user']) . "'";
		}
                
                $sql .=" And oc_store.company_id='".$data['filter_company']."' ";
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
                       //    echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}
        
    public function getTotalCash_transation_CompanyWise($data = array()) {
		
            $sql="SELECT count(obt.transid) as total FROM `oc_bank_transaction` as obt left join oc_store as os on os.store_id=obt.store_id where obt.bank_name!='' ";
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
                            if ($data['filter_status']!='') {
			$sql .= " AND obt.status= '" . $this->db->escape($data['filter_status']) . "'";
		}
                            if (!empty($data['filter_user'])) {
			$sql .= " AND obt.accept_by= '" . $this->db->escape($data['filter_user']) . "'";
		}
                $sql .=" And os.company_id='".$data['filter_company']."' ";
               // echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        
        public function getCash_reportRunner_CompanyWise($data = array()) {
	   

            $sql="SELECT
    oc_bank_deposit_runner.*,
    oc_user.firstname,
    oc_user.lastname,
    os.company_id,
oc_bank.bank as bank_name
FROM
    oc_bank_deposit_runner
        LEFT JOIN
    oc_user ON oc_user.user_id = oc_bank_deposit_runner.user_id
        LEFT JOIN
    oc_store AS os ON os.store_id = oc_user.store_id
    left join oc_bank on oc_bank_deposit_runner.bank=oc_bank.bank_id
                    where oc_bank_deposit_runner.bank!='' ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(oc_bank_deposit_runner.submit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_bank_deposit_runner.submit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if ($data['filter_user']!='') {
			$sql .= " AND oc_bank_deposit_runner.user_id = '" . $this->db->escape($data['filter_user']) . "'";
		}
                            if ($data['filter_status']!='') {
			$sql .= " AND oc_bank_deposit_runner.status = '" . $this->db->escape($data['filter_status']) . "'";
		}
                
                $sql .=" AND os.company_id='".$data['filter_company']."' ";
            $sql.=" Order by oc_bank_deposit_runner.SID desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                          // echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}
public function getTotalCash_transationRunner_CompanyWise($data = array()) {
		
            $sql="SELECT
    COUNT(*) AS total
FROM
    (SELECT
        oc_bank_deposit_runner.*,
            oc_user.firstname,
            oc_user.lastname,
            os.company_id
    FROM
        oc_bank_deposit_runner
    LEFT JOIN oc_user ON oc_user.user_id = oc_bank_deposit_runner.user_id
    left join oc_store as os on os.store_id = oc_user.store_id"
                    . " where oc_bank_deposit_runner.bank!='' ";
            
               $sql .=" AND os.company_id='".$data['filter_company']."' ";    
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(oc_bank_deposit_runner.submit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_bank_deposit_runner.submit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if ($data['filter_user']!='') {
			$sql .= " AND oc_bank_deposit_runner.user_id = '" . $this->db->escape($data['filter_user']) . "'";
		}
                            if ($data['filter_status']!='') {
			$sql .= " AND oc_bank_deposit_runner.status = '" . $this->db->escape($data['filter_status']) . "'"; 
		}
                
              
            $sql.=" Order by oc_bank_deposit_runner.SID desc ) as aa"; 
              // echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	  
    public function getCash_position_CompanyWise($data = array()) {
	 $sql="SELECT oc_user.firstname firstname,oc_user.lastname as lastname,"
                 . "oc_user.username,oc_user.store_id as store_id,os.name as store_name,"
                 . "oc_user.cash as amount,oc_user.audit_status,oc_user.audit_date 
                 FROM shop.oc_user 
                  
                 left join oc_store as os on os.store_id = oc_user.store_id
                 where oc_user.user_group_id='11' and oc_user.status='1' "; 
 
         if (!empty($data['filter_store'])) {
			$sql .= " and oc_user.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		} 
                $sql .=" and os.company_id='".$data['filter_company']."'  ";
               
$sql.=" Order by oc_user.firstname asc  ";
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
                             $log=new Log("ce-".date('Y-m-d').".log"); 
                             $log->write($sql);
		 $query = $this->db->query($sql);
                             $log->write($query->rows);
		 return $query->rows;
	}

	public function getTotalCash_position_CompanyWise($data = array()) {

$log=new Log("cashsql.log");
	
$sql="SELECT count(*) as total,sum(amount) as total_amount FROM (SELECT oc_user.firstname,oc_user.lastname,oc_user.username,oc_user.store_id,os.name as store_name,oc_user.cash as amount FROM "
        . "shop.oc_user 
          left join oc_store as os on os.store_id = oc_user.store_id
        where oc_user.user_group_id='11' and oc_user.status='1' "; 

  if (!empty($data['filter_store'])) {
			$sql .= " and oc_user.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		} 
/*	
            $sql="SELECT count(*) as total,sum(amount) as total_amount FROM (SELECT * FROM `oc_cash_store_position` ";
                  
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
*/
                
                $sql .=" and os.company_id='".$data['filter_company']."'  ";
                $sql.=" ) as aa";
               // echo $sql;
		$log->write($sql);

		$query = $this->db->query($sql);

		return $query->row;
	}
        
public function get_bank_sum_cash_companywise($data=array())
	{
                     $sql="select max(case when bank_name='HDFC' then  bank_amount else 00.00 end)as HDFC,
max(case when bank_name='State Bank of India' then  bank_amount else 00.00 end)as State_Bank_of_India,
max(case when bank_name='ICICI' then  bank_amount else 00.00 end)as ICICI,
max(case when bank_name='TAGGED BILLS' then  bank_amount else 00.00 end)as TAGGED_BILLS

from (

SELECT obt.bank_name,sum(obt.amount)as bank_amount FROM `oc_bank_transaction` as obt
 LEFT JOIN oc_store on oc_store.store_id=obt.store_id 

left join

oc_user on oc_user.user_id=obt.accept_by where obt.bank_name!='' and obt.status='1' 
  ";
            
            if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
                            if ($data['filter_status']!='') {
			$sql .= " AND obt.status= '" . $this->db->escape($data['filter_status']) . "'";
		}
                            if (!empty($data['filter_user'])) {
			$sql .= " AND obt.accept_by= '" . $this->db->escape($data['filter_user']) . "'";
		}
                
                $sql .=" and oc_store.company_id='".$data['filter_company']."'  ";
                $sql.=" group by obt.bank_name Order by obt.transid  )as a ";
               // echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
     
        public function getTotalCash_transationRunnerbank_companywise($data = array()) {
		
            $sql="
select max(case when bank_name='HDFC' then  bank_amount else 00.00 end)as HDFC,
max(case when bank_name='State Bank of India' then  bank_amount else 00.00 end)as State_Bank_of_India,
max(case when bank_name='ICICI' then  bank_amount else 00.00 end)as ICICI,
max(case when bank_name='TAGGED BILLS' then  bank_amount else 00.00 end)as TAGGED_BILLS

from (

SELECT obt.bank as bank_name,sum(obt.amount)as bank_amount FROM `oc_bank_deposit_runner` as obt
  left join
 oc_user on oc_user.user_id=obt.user_id where obt.bank!='' and obt.status='1' 
   left join oc_store as os on os.store_id = oc_user.store_id
";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(obt.submit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.submit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if ($data['filter_user']!='') {
			$sql .= " AND obt.user_id = '" . $this->db->escape($data['filter_user']) . "'";
		}
                            if ($data['filter_status']!='') {
			$sql .= " AND obt.status = '" . $this->db->escape($data['filter_status']) . "'"; 
		}
                
                 $sql .=" and os.company_id='".$data['filter_company']."'  ";
            $sql.=" group by obt.bank ) as aa"; 
                          //  echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
        
 
}