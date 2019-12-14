<?php
class ModelReportCash extends Model {
	
public function getCash_reportRunner($data = array()) {
	   

            $sql="SELECT oc_bank_deposit_runner.*,oc_user.firstname,oc_user.lastname,oc_bank.bank as bank_name  from oc_bank_deposit_runner left join oc_user on oc_user.user_id=oc_bank_deposit_runner.user_id left join oc_bank on oc_bank_deposit_runner.bank=oc_bank.bank_id where oc_bank_deposit_runner.bank!='' ";
                  
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
                           //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

	public function getTotalCash_transationRunner($data = array()) {
		
            $sql="select count(*) as total from (SELECT oc_bank_deposit_runner.*,oc_user.firstname,oc_user.lastname  from oc_bank_deposit_runner left join oc_user on oc_user.user_id=oc_bank_deposit_runner.user_id where oc_bank_deposit_runner.bank!='' ";
                  
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

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

////////////////////////////
	public function getCash_report($data = array()) {
	   

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
		if (($data['filter_status']=='0') || ($data['filter_status']=='2')) {
			$sql .= " AND obt.status in  (0,2) ";
		}
                            else if (($data['filter_status']=='1') || ($data['filter_status']=='3'))  {
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
                             if (($data['filter_status']=='0') || ($data['filter_status']=='2')) {
			$sql .= " AND obt.status in  (0,2) ";
		}
                            else if (($data['filter_status']=='1') || ($data['filter_status']=='3'))  {
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
					 max(case when bank_name='Sales Adjustment' then  bank_amount else 00.00 end)as Sales_Adjustment,
max(case when bank_name='State Bank of India' then  bank_amount else 00.00 end)as State_Bank_of_India,
max(case when bank_name in ('ICICI - Del Pandarwan','ICICI') then  bank_amount else 00.00 end)as ICICI,

max(case when bank_name='TAGGED BILLS' then  bank_amount else 00.00 end)as TAGGED_BILLS

from (

SELECT obt.bank_name,sum(obt.amount)as bank_amount FROM `oc_bank_transaction` as obt
 LEFT JOIN oc_store on oc_store.store_id=obt.store_id left join
 oc_user on oc_user.user_id=obt.accept_by where obt.bank_name!='' 
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
                           if (($data['filter_status']=='0') || ($data['filter_status']=='2')) {
			$sql .= " AND obt.status in  (0,2) ";
		}
                            else if (($data['filter_status']=='1') || ($data['filter_status']=='3'))  {
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
	 $sql="SELECT oc_user.firstname firstname,oc_user.lastname as lastname,oc_user.username,oc_user.email,oc_user.store_id as store_id,oc_store.name as store_name,oc_user.cash as amount,oc_user.audit_status,oc_user.audit_date,oc_user.status as user_status,oc_unit.unit_name as unit_name

 FROM oc_user join oc_store on oc_user.store_id=oc_store.store_id 
left join oc_store_to_unit as ostu on oc_user.store_id=ostu.store_id
	 left join oc_unit on oc_unit.unit_id=ostu.unit_id
where oc_user.user_group_id='11' and oc_user.status in ('1')
and oc_user.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in (1,2) )
and oc_user.store_id not in (14,52)
 "; 
//and oc_user.status=1
  if (!empty($data['filter_store'])) {
			$sql .= " and oc_user.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}  
$sql.=" Order  by unit_name,amount desc  ";
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

	public function get_own_stores_total_eod($data = array())
	{
	$log=new Log("cash-sql".date('Y-m-d').".log");
	$sql="SELECT count(*) as total,sum(amount) as total_amount FROM (SELECT oc_user.firstname,oc_user.lastname,oc_user.username,oc_user.store_id,oc_store.name as store_name,oc_user.cash as amount FROM shop.oc_user join oc_store on oc_user.store_id=oc_store.store_id where oc_user.user_group_id='11' and oc_user.status in ('1','0') and oc_user.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in (1,2))  
and oc_user.status=1
and oc_user.store_id not in (14,52)
"; 

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
	
$sql="SELECT count(*) as total,sum(amount) as total_amount FROM (SELECT oc_user.firstname,oc_user.lastname,oc_user.username,oc_user.store_id,oc_store.name as store_name,oc_user.cash as amount FROM shop.oc_user join oc_store on oc_user.store_id=oc_store.store_id where oc_user.user_group_id='11' and oc_user.status in ('1','0') 
and oc_user.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in (1,2) )
and oc_user.status=1
and oc_user.store_id not in (14,52)
 "; 

  if (!empty($data['filter_store'])) {
			$sql .= " and oc_user.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		} 

                $sql.=" ) as aa";
		$log->write($sql);
		//echo $sql;
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
                 . "oc_user.cash as amount,oc_user.audit_status,oc_user.audit_date,oc_user.status as user_status
	, oc_unit.unit_name as unit_name 
                 FROM shop.oc_user 
                  
                 left join oc_store as os on os.store_id = oc_user.store_id
	left join oc_store_to_unit as ostu on oc_user.store_id=ostu.store_id
	 left join oc_unit on oc_unit.unit_id=ostu.unit_id
                 where oc_user.user_group_id='11' and oc_user.status in ('1')  
 and  oc_user.store_id in (select ot.store_id from oc_setting ot left JOIN oc_store os on os.store_id=ot.store_id where `key`='config_storetype' and `value` in (1,2))
 and oc_user.store_id not in (14,52)
 ";
         if (!empty($data['filter_store'])) {
			$sql .= " and oc_user.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		} 
                $sql .=" and os.company_id='".$data['filter_company']."'  ";
               
$sql.=" Order by unit_name,amount desc  ";
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
	public function get_own_stores_total_eod_company($data = array())
	{
	$log=new Log("cash-sql".date('Y-m-d').".log");
	$sql="SELECT count(*) as total,sum(amount) as total_amount FROM (SELECT oc_user.firstname,oc_user.lastname,oc_user.username,oc_user.store_id,oc_store.name as store_name,oc_user.cash as amount FROM oc_user join oc_store on oc_user.store_id=oc_store.store_id where oc_user.user_group_id='11' and oc_user.status in ('1') and oc_user.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in (1,2))  
and oc_user.status=1
and oc_user.store_id not in (14,52)
"; 

  if (!empty($data['filter_store'])) {
			$sql .= " and oc_user.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		} 
 $sql .=" and oc_store.company_id='".$data['filter_company']."'  ";
                $sql.=" ) as aa";
		$log->write($sql);

		$query = $this->db->query($sql);

		return $query->row;
	}
	public function getTotalCash_position_CompanyWise($data = array()) {

$log=new Log("cashsql.log");
	
$sql="SELECT count(*) as total,sum(amount) as total_amount FROM (SELECT oc_user.firstname,oc_user.lastname,oc_user.username,oc_user.store_id,os.name as store_name,oc_user.cash as amount FROM "
        . "oc_user 
          left join oc_store as os on os.store_id = oc_user.store_id
        where oc_user.user_group_id='11' and oc_user.status in ('1')
and oc_user.store_id in (select ot.store_id from oc_setting ot left JOIN oc_store os on os.store_id=ot.store_id where `key`='config_storetype' and `value` in (1,2))
and oc_user.store_id not in (14,52)
";

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
max(case when bank_name='ICICI - Del Pandarwan' then  bank_amount else 00.00 end)as ICICI,
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
	public function getStoreCash_position($store_id) 
	{
		$sql=" select sum(cash)as total  from oc_user where status=1  and store_id= '" . $this->db->escape($store_id) . "'";
		
		 //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	} 
    public function setCashPosition_report_daily_sms($filename,$date)
	{
		$sql=" insert into oc_store_cash_position_daily_report set filename ='".$filename."',report_date='".$date."' ";
                  
		$query = $this->db->query($sql); 
	}
		public function getcashtrans($data = array()) {
		$sql = "SELECT op.amount,op.order_id,op.tr_type,op.payment_method,op.updated_cash as updated_cash,
		op.create_time as create_time ,concat(users.firstname,' ',users.lastname) as user,
		store.name  as storename,op.remarks FROM `oc_store_cash_trans` as op 
		LEFT JOIN oc_store as store on store.store_id=op.store_id 
		LEFT JOIN oc_user as users on users.user_id=op.user_id where op.payment_method not in ('PO','ST','SR') and users.user_group_id in (11,36) ";

		if (!empty($data['filter_stores_id'])) {
			$sql .= " and op.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} else {
			$sql .= " and op.store_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(op.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(op.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND op.user_id= '" . $this->db->escape($data['filter_user_id']) . "'";
		}
		$sql .= " ORDER BY sid DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getcashtransTotal($data = array()) {
		$sql = "select count(*) as total from (
		SELECT op.amount,op.order_id,op.tr_type,op.payment_method,op.updated_cash as updated_cash,
		op.create_time as create_time ,concat(users.firstname,' ',users.lastname) as user,
		store.name as storename,op.remarks FROM `oc_store_cash_trans` as op 
		LEFT JOIN oc_store as store on store.store_id=op.store_id 
		LEFT JOIN oc_user as users on users.user_id=op.user_id where op.payment_method not in ('PO','ST','SR') and users.user_group_id in (11,36)
		";

		if (!empty($data['filter_stores_id'])) {
			$sql .= " and op.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} else {
			$sql .= " and op.store_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(op.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(op.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND op.user_id= '" . $this->db->escape($data['filter_user_id']) . "'";
		}
		
		$sql .= " ) as aa";
//echo $sql;
		
//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function get_store_users($store_id) {


$sql="select user_id,concat(firstname,' ',lastname) as name from oc_user where user_group_id  in (11,36) and store_id='".$store_id."' "; 
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function get_store_users_all() {


$sql="select user_id,concat(firstname,' ',lastname) as name from oc_user where user_group_id  in (11,36)  "; 
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	//////////runner cash trans///////////////
	public function getrunnercashtrans($data = array()) {
		$sql = "SELECT op.amount,op.transid as order_id,op.trans_type as tr_type,op.current_balance as updated_cash,
		op.date_time as create_time ,concat(users.firstname,' ',users.lastname) as user,op.runner_id,users.username as username
		 FROM `oc_runner_cash_transactions` as op 
		
		LEFT JOIN oc_user as users on users.user_id=op.runner_id where  users.user_group_id=22 and users.status=1 ";  


		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(op.date_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(op.date_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND op.runner_id= '" . $this->db->escape($data['filter_user_id']) . "'";
		}
		if (!empty($data['filter_tr_type'])) {
			$sql .= " AND op.trans_type= '" . $this->db->escape($data['filter_tr_type']) . "'";
		}
		$sql .= " ORDER BY sid DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getrunnercashtransTotal($data = array()) {
		$sql = "select count(*) as total from (
		SELECT op.amount,op.transid as order_id,op.trans_type as tr_type,op.current_balance as updated_cash,
		op.date_time as create_time ,concat(users.firstname,' ',users.lastname) as user
		 FROM `oc_runner_cash_transactions` as op 
		
		LEFT JOIN oc_user as users on users.user_id=op.runner_id where  users.user_group_id=22 and users.status=1
		";

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(op.date_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(op.date_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND op.runner_id= '" . $this->db->escape($data['filter_user_id']) . "'";
		}
		if (!empty($data['filter_tr_type'])) {
			$sql .= " AND op.trans_type= '" . $this->db->escape($data['filter_tr_type']) . "'";
		}
		$sql .= " ) as aa";
		//echo $sql;
		
//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getAllRunners() {


$sql="select user_id,concat(firstname,' ',lastname) as name from oc_user where user_group_id=22 and status=1 "; 
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getrunnerTotalCR($data = array()) 
	{
		$sql = " SELECT sum(op.amount) as total 
		 FROM `oc_runner_cash_transactions` as op 
		
		LEFT JOIN oc_user as users on users.user_id=op.runner_id where  users.user_group_id=22 and users.status=1
		";

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(op.date_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(op.date_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND op.runner_id= '" . $this->db->escape($data['filter_user_id']) . "'";
		}
		$sql .= " AND op.trans_type= 'CR'";		
		//echo $sql;
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getrunnerTotalDB($data = array()) 
	{
		$sql = " SELECT sum(op.amount) as total 
		 FROM `oc_runner_cash_transactions` as op 
		
		LEFT JOIN oc_user as users on users.user_id=op.runner_id where  users.user_group_id=22 and users.status=1
		";

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(op.date_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(op.date_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND op.runner_id= '" . $this->db->escape($data['filter_user_id']) . "'";
		}
		$sql .= " AND op.trans_type= 'DB'";		
		//echo $sql;
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getrunnerTotalEXPENSE($data = array()) 
	{
		$sql = " SELECT sum(op.amount) as total 
		 FROM `oc_runner_cash_transactions` as op 
		
		LEFT JOIN oc_user as users on users.user_id=op.runner_id where  users.user_group_id=22 and users.status=1
		";

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(op.date_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(op.date_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND op.runner_id= '" . $this->db->escape($data['filter_user_id']) . "'";
		}
		$sql .= " AND op.trans_type= 'EXPENSE'";		
		//echo $sql;
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function get_user_type($oid) 
	{
		$sql = " SELECT  users.user_group_id as user_group_id
		 FROM `oc_store_cash_trans` as op 
		
		LEFT JOIN oc_user as users on users.user_id=op.user_id 
		
			 where op.order_id= '" . $this->db->escape($oid) . "'";
		
		
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row['user_group_id'];
	}
	public function get_cash_deposit_by_store_incharge($oid) 
	{
		$sql = " SELECT op.amount,concat(users.firstname,' ',users.lastname) as store_incharge,
		concat(users2.firstname,' ',users2.lastname) as ce_name,op.updated_cash as updated_cash
		 FROM `oc_store_cash_trans` as op 
		left join oc_bank_transaction as ob on op.order_id=ob.transid
		
		LEFT JOIN oc_user as users on users.user_id=op.user_id 
		LEFT JOIN oc_user as users2 on users2.user_id=ob.accept_by 
		
			 where  op.order_id= '" . $this->db->escape($oid) . "'  ";
		
		
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
	public function get_cash_deposit_by_sub_user($oid) 
	{
		$sql = " SELECT op.amount,concat(users.firstname,' ',users.lastname) as store_incharge,
		concat(users2.firstname,' ',users2.lastname) as ce_name,op.updated_cash as updated_cash
		 FROM `oc_store_cash_trans` as op 
		left join oc_bank_transaction as ob on op.order_id=ob.transid
		
		LEFT JOIN oc_user as users on users.user_id=op.user_id 
		LEFT JOIN oc_user as users2 on users2.user_id=ob.accept_by 
		
			 where  op.order_id= '" . $this->db->escape($oid) . "'";
		
		
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
	public function cash_deposit_by_sub_user($oid) 
	{
		$sql = " SELECT op.amount,concat(users.firstname,' ',users.lastname) as store_incharge,
		concat(users2.firstname,' ',users2.lastname) as sub_user,op.updated_cash as updated_cash
		 FROM `oc_store_cash_trans` as op 
		left join oc_bank_transaction as ob on op.order_id=ob.transid
		
		LEFT JOIN oc_user as users on users.user_id=op.user_id 
		LEFT JOIN oc_user as users2 on users2.user_id=ob.user_id 
		
			 where  op.order_id= '" . $this->db->escape($oid) . "' and payment_method='SCR'";
		
		
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
	public function getorder_summarydetail($data)
	{

		
		$sql="SELECT name,quantity,price,tax,total FROM oc_order_product where order_id='".$data."' group by product_id";
		$query = $this->db->query($sql);  
        return $query->rows;   
		
	}
	public function getorder_totaldetail($data)
	{

		
		$sql="SELECT total,cash,tagged,subsidy from oc_order where order_id='".$data."' ";
		$query = $this->db->query($sql);  
        return $query->row;   
		
	}
	public function getuserTotalCR($data = array()) 
	{
		$sql = " SELECT sum(op.amount) as total 
		 FROM `oc_store_cash_trans` as op 
		
		LEFT JOIN oc_user as users on users.user_id=op.user_id where op.payment_method not in ('PO','ST','SR') and  users.user_group_id in(11,36) and users.status=1
		";

		

		if (!empty($data['filter_date_start'])) 
		{
			$sql .= " AND DATE(op.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(op.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND op.user_id= '" . $this->db->escape($data['filter_user_id']) . "'";
		}
		$sql .= " AND op.tr_type= 'CR'";		
		if (!empty($data['filter_stores_id'])) 
		{
			$sql .= " and op.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 
		else 
		{
			$sql .= " and op.store_id > '0'";
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getuserTotalDB($data = array()) 
	{
		$sql = " SELECT sum(op.amount) as total 
		 FROM `oc_runner_cash_transactions` as op 
		
		LEFT JOIN oc_user as users on users.user_id=op.user_id where op.payment_method not in ('PO','ST','SR') and  users.user_group_id in(11,36) and users.status=1
		";

		

		if (!empty($data['filter_date_start'])) 
		{
			$sql .= " AND DATE(op.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(op.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user_id'])) {
			$sql .= " AND op.user_id= '" . $this->db->escape($data['filter_user_id']) . "'";
		}
		if (!empty($data['filter_stores_id'])) 
		{
			$sql .= " and op.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 
		else 
		{
			$sql .= " and op.store_id > '0'";
		}
		$sql .= " AND op.tr_type= 'DB'";		
		//echo $sql;
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}