<?php
class ModelReportEstorelazer extends Model {

public function getTransaction_Franchise($data = array()) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];


$sql="select date(osct.create_time)as datee,osct.store_id,
sum(case when osct.payment_method ='CASHDEPOSIT' then osct.amount  end)as amt_paid,

sum(case when osct.payment_method ='PO' then osct.amount end)as goods_worth,
(case when osct.payment_method='CASHDEPOSIT' then 'CASHDEPOSIT' 
when osct.payment_method='PO' then 'PO' end)as Bill_type

from oc_store_cash_trans as osct
left join oc_store as os on os.store_id = osct.store_id
group by date(osct.create_time),osct.store_id 
 ";
		if (!empty($data['filter_stores_id'])) {
			$sql .= "  and osct.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($reg_date)) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($reg_date) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(osct.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= "  group by osct.store_id,osct.order_id order by osct.create_time desc ";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		///echo  $sql;
		$query = $this->db->query($sql);

		return $query->rows;
	}

public function getTotalTransaction_Franchise($data) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];


		$sql="select count(*) as total,Credit_Balance,Cash_Balance from ( select osct.store_id,os.name as store_name,osct.user_id,
concat(u.firstname,' ',u.lastname)as user_Name,
osct.create_time as Date,
osct.payment_method as Mode,osct.order_id,
sum(case when osct.tr_type='DB' or osct.tr_type='DR'
 then osct.amount end)as Withdrawals,
sum(case when osct.tr_type='CR' then osct.amount end)as Deposite,

osct.updated_credit as Credit_Balance,osct.updated_cash as Cash_Balance,
osct.remarks
 from oc_store_cash_trans as  osct
 left join oc_user as u on osct.user_id = u.user_id
 left join oc_store as os on os.store_id = osct.store_id
 where osct.store_id<>0 
and osct.payment_method not in ('Cash','Tagged Cash','Tagged','Subsidy') 
";
		if (!empty($data['filter_stores_id'])) {
			$sql .= "  and osct.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($reg_date)) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($reg_date) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(osct.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= "  group by osct.store_id,osct.order_id order by osct.create_time desc ) as aa order by `Date` desc ";
		$query = $this->db->query($sql);
		//echo $sql;
		return $query->row;
	}


////////////////////////////////////////////////////	
	public function getTransaction_Own($data = array()) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];


$sql="select * from (
select date(osct.create_time)as datee,osct.store_id,
(case when osct.payment_method ='CASHDEPOSIT' then osct.amount end)as amt_paid,

(case when osct.payment_method ='PO' then osct.amount end)as goods_worth,
(case when osct.payment_method='CASHDEPOSIT' then 'CASHDEPOSIT'
when osct.payment_method='PO' then 'PO' end)as Bill_type

from oc_store_cash_trans as osct
left join oc_store as os on os.store_id = osct.store_id)as a where amt_paid is not null or goods_worth is not null";
		if (!empty($data['filter_stores_id'])) {
			$sql .= "  and osct.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($reg_date)) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($reg_date) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(osct.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= "  group by osct.store_id,osct.order_id order by osct.create_time desc ";

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

	public function getTotalTransaction_Own($data) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];


		$sql="select date(osct.create_time)as datee,osct.store_id,
sum(case when osct.payment_method ='CASHDEPOSIT' then osct.amount  end)as amt_paid,

sum(case when osct.payment_method ='PO' then osct.amount end)as goods_worth,
(case when osct.payment_method='CASHDEPOSIT' then 'CASHDEPOSIT' 
when osct.payment_method='PO' then 'PO' end)as Bill_type

from oc_store_cash_trans as osct
left join oc_store as os on os.store_id = osct.store_id
group by date(osct.create_time),osct.store_id";
		if (!empty($data['filter_stores_id'])) {
			$sql .= "  and osct.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($reg_date)) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($reg_date) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(osct.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= "  group by osct.store_id,osct.order_id order by osct.create_time desc ) as aa order by `Date` desc ";
		$query = $this->db->query($sql);
		//echo $sql;
		return $query->row;
	}

	public function getStoreaddress($data) {
		$query = $this->db->query("SELECT * from oc_setting WHERE `key`='config_address' and store_id='".$data['filter_stores_id']."'  limit 1 ");

		return $query->row['value'];
	}
	public function getStoreType($data) {
		$query = $this->db->query("SELECT  oc_store_type.type_name as type_name FROM `oc_store_type` join `oc_setting` on `oc_store_type`.`sid`=`oc_setting`.`value` WHERE `oc_setting`.`store_id` = '".$data['filter_stores_id']."' and `oc_setting`.`key`='config_storetype'  limit 1 ");

		return $query->row['type_name'];
	}
	public function getStoreType_id($data) {
		$query = $this->db->query("SELECT * from oc_setting WHERE `key`='config_storetype' and store_id='".$data['filter_stores_id']."'  limit 1 ");

		return $query->row['value'];
	}
	public function getStoreInCharge($data) {
		$query = $this->db->query("SELECT * from oc_user WHERE `user_group_id`='11' and store_id='".$data['filter_stores_id']."'  limit 1 ");

		return $query->row['firstname']." ".$query->row['lastname'];
	}
	public function getStoreGstn($data) {
		$query = $this->db->query("SELECT * from oc_setting WHERE `key`='config_gstn' and store_id='".$data['filter_stores_id']."'  limit 1 ");

		return $query->row['value'];
	}
	public function getStorecash_companywise($data = array()) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];


$sql="select osct.store_id,os.name as store_name,osct.user_id,
concat(u.firstname,' ',u.lastname)as user_Name,
osct.create_time as Date,
osct.payment_method as Mode,osct.order_id,
sum(case when osct.tr_type='DB' or osct.tr_type='DR'
 then osct.amount end)as Withdrawals,
sum(case when osct.tr_type='CR' then osct.amount end)as Deposite,

osct.updated_credit as Credit_Balance,osct.updated_cash as Cash_Balance,
osct.remarks
 from oc_store_cash_trans as  osct
 left join oc_user as u on osct.user_id = u.user_id
 left join oc_store as os on os.store_id = osct.store_id
 where osct.store_id<>0 ";
		if (!empty($data['filter_stores_id'])) {
			$sql .= "  and osct.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($reg_date)) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($reg_date) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(osct.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

                $sql .= " and os.company_id='".$data['filter_company']."' "; 
		$sql .= "  group by osct.store_id,osct.order_id order by osct.create_time desc ";

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
        
    public function getTotalPurchased_companywise($data) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];


		$sql="select count(*) as total,Credit_Balance,Cash_Balance from ( select osct.store_id,os.name as store_name,osct.user_id,
concat(u.firstname,' ',u.lastname)as user_Name,
osct.create_time as Date,
osct.payment_method as Mode,osct.order_id,
sum(case when osct.tr_type='DB' or osct.tr_type='DR'
 then osct.amount end)as Withdrawals,
sum(case when osct.tr_type='CR' then osct.amount end)as Deposite,

osct.updated_credit as Credit_Balance,osct.updated_cash as Cash_Balance,
osct.remarks
 from oc_store_cash_trans as  osct
 left join oc_user as u on osct.user_id = u.user_id
 left join oc_store as os on os.store_id = osct.store_id
 where osct.store_id<>0 ";
		if (!empty($data['filter_stores_id'])) {
			$sql .= "  and osct.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($reg_date)) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($reg_date) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(osct.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                $sql .= " and os.company_id='".$data['filter_company']."' ";
		$sql .= "  group by osct.store_id,osct.order_id order by osct.create_time desc ) as aa order by `Date` desc ";
		$query = $this->db->query($sql);
		//echo $sql;
		return $query->row;
	}
        
    public function getStoreledger($data = array()) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];


$sql="select * from (
select date(osct.create_time)as datee,osct.store_id,
(case when osct.payment_method ='CASHDEPOSIT' or osct.payment_method ='EXPWOFF'  or osct.payment_method ='WOFF'  then osct.amount end)as amt_paid,

(case when osct.payment_method ='PO'  then osct.amount  end)as goods_worth,
(case when osct.payment_method='CASHDEPOSIT' then 'CASH DEPOSIT'
when osct.payment_method='PO' then 'PO' when osct.payment_method ='EXPWOFF' then 'EXP WAIVER OFF' when  osct.payment_method ='WOFF' then 'WAIVER OFF'end)as Bill_type

from oc_store_cash_trans as osct
left join oc_store as os on os.store_id = osct.store_id
where osct.store_id!='' 
 ";
	if (!empty($data['filter_stores_id'])) {
			$sql .= "  and osct.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($reg_date)) {
			//$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($reg_date) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(osct.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                $sql.=" )as a where amt_paid is not null or goods_worth is not null ";
		

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
        public function getTotalStoreledger($data = array()) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];


$sql="select count(*) as total from ( select * from (
select date(osct.create_time)as datee,osct.store_id,
(case when osct.payment_method ='CASHDEPOSIT' then osct.amount end)as amt_paid,

(case when osct.payment_method ='PO' or osct.payment_method ='EXPWOFF'  or osct.payment_method ='WOFF'  then osct.amount end)as goods_worth,
(case when osct.payment_method='CASHDEPOSIT' then 'CASHDEPOSIT'
when osct.payment_method='PO' then 'PO' when osct.payment_method ='EXPWOFF' then 'EXP WAIVER OFF' when  osct.payment_method ='WOFF' then 'WAIVER OFF' end)as Bill_type

from oc_store_cash_trans as osct
left join oc_store as os on os.store_id = osct.store_id  where osct.store_id!=''  ";
	if (!empty($data['filter_stores_id'])) {
			$sql .= "  and osct.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} 

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($reg_date)) {
			//$sql .= " AND date(osct.create_time) >= '" . $this->db->escape($reg_date) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(osct.create_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                $sql.=" )as a where amt_paid is not null or goods_worth is not null ";
		

		$sql.=") as aaa";
		//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}    
}