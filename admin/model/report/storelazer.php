<?php
class ModelReportStorelazer extends Model {

public function getTransaction_Franchise($data = array()) {

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
 where osct.store_id<>0
and osct.payment_method not in ('Cash','Tagged Cash','Tagged','Subsidy','CD','SCR') 
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

		$sql .= "  group by osct.store_id,osct.order_id,osct.payment_method order by osct.create_time desc ";

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
and osct.payment_method not in ('Cash','Tagged Cash','Tagged','Subsidy','SCR','CD') 
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

		$sql .= "  group by osct.store_id,osct.order_id,osct.payment_method order by osct.create_time desc ) as aa order by `Date` desc ";
		$query = $this->db->query($sql);
		//echo $sql;
		return $query->row;
	}
//////////////////////////////////////////////////////////////////////////////////////
public function getTransaction_Franchise_payment_received($data = array()) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];

/*
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
 where osct.store_id<>0
and osct.payment_method not in ('Cash','Tagged Cash','Tagged','Subsidy','PO','WOFF','ST','EXPWOFF') 
and osct.tr_type='CR'
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

		$sql .= "  group by osct.store_id,osct.order_id,osct.payment_method order by osct.create_time desc ";
*/
$sql="
	select date(aa.create_date) as Date,
	aa.store_id as store_id,
	aa.payment_method as Mode,
	aa.transid as order_id,
	aa.amount as Deposite,
	oc_store.name as store_name,
	aa.transaction_type as transaction_type
	from (
	
	SELECT order_total as amount,
	create_date as create_date,
	sid as transid,
	'Sale Return' as transaction_type,
	'Sale Return' as payment_method,
	store_from as store_id 
	FROM `oc_sale_return` 
	where store_from='".$data['filter_stores_id']."' 
	and date(create_date)>='" . $this->db->escape($data['filter_date_start']) . "' 
	and  date(create_date)<='" . $this->db->escape($data['filter_date_end']) . "'
	AND date(create_date) >= '" . $this->db->escape($reg_date) . "'
	
	UNION ALL 
	
	SELECT amount as amount,
	create_date as create_date,
	sid as transid,
	transaction_type as transaction_type,
	payment_method as payment_method,
	store_id as store_id 
	FROM `oc_payout_dtl` 
	where store_id='".$data['filter_stores_id']."' 
	and date(create_date)>='" . $this->db->escape($data['filter_date_start']) . "'
	and date(create_date)<='" . $this->db->escape($data['filter_date_end']) . "'
	AND date(create_date) >= '" . $this->db->escape($reg_date) . "'
	
	) as aa left join oc_store on aa.store_id=oc_store.store_id
	order by aa.create_date desc
	";

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

public function getTotalTransaction_Franchise_payment_received($data) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];


		$sql="
	select count(*) as total 
	from (
	
	SELECT order_total as amount,
	create_date as create_date,
	sid as transid,
	'Sale Return' as transaction_type,
	'Sale Return' as payment_method,
	store_from as store_id 
	FROM `oc_sale_return` 
	where store_from='".$data['filter_stores_id']."' 
	and date(create_date)>='" . $this->db->escape($data['filter_date_start']) . "' 
	and  date(create_date)<='" . $this->db->escape($data['filter_date_end']) . "'
	AND date(create_date) >= '" . $this->db->escape($reg_date) . "'
	
	UNION ALL 
	
	SELECT amount as amount,
	create_date as create_date,
	sid as transid,
	transaction_type as transaction_type,
	payment_method as payment_method,
	store_id as store_id 
	FROM `oc_payout_dtl` 
	where store_id='".$data['filter_stores_id']."' 
	and date(create_date)>='" . $this->db->escape($data['filter_date_start']) . "'
	and date(create_date)<='" . $this->db->escape($data['filter_date_end']) . "'
	AND date(create_date) >= '" . $this->db->escape($reg_date) . "'
	
	) as aa 
	";
		$query = $this->db->query($sql);
		//echo $sql;
		return $query->row;
	}
///////////////////////////////////////////////////////////////////////////////////
public function getTransaction_Franchise_partner_billing($data = array()) {

$query1 = $this->db->query("SELECT * from oc_setting WHERE `key`='config_registration_date' and store_id='".$data['filter_stores_id']."'  limit 1 ");

$reg_date=$query1->row['value'];


$sql="select osct.store_id,os.name as store_name,osct.user_id,
concat(u.firstname,' ',u.lastname)as user_Name,
osct.create_time as Date,
osct.payment_method as Mode,osct.order_id,
sum(case when osct.tr_type='DB' or osct.tr_type='DR'
 then osct.amount end)as Withdrawals,
(select `value` from oc_setting where `key`='config_firmname' and oc_setting.store_id=osct.store_id) as party_name,
(select `paid_status` from oc_po_invoice where  oc_po_invoice.po_order_id=osct.order_id) as paid_status,
sum(case when osct.tr_type='CR' then osct.amount end)as Deposite,

osct.updated_credit as Credit_Balance,osct.updated_cash as Cash_Balance,
osct.remarks,opip.product_name as product_name,opip.p_qnty as p_qnty,opip.p_price as p_price,opip.p_amount as p_amount
 from oc_store_cash_trans as  osct
left join oc_po_invoice_product as opip on osct.order_id=opip.po_order_id
 left join oc_user as u on osct.user_id = u.user_id
 left join oc_store as os on os.store_id = osct.store_id
 where osct.store_id<>0 and osct.payment_method in ('PO','ST')
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

public function getTotalTransaction_Franchise_partner_billing($data) {

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
 where osct.store_id<>0  and osct.payment_method in ('PO','ST')
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

		$sql .= "  group by osct.store_id,osct.order_id,osct.payment_method order by osct.create_time desc ";

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

		$sql .= "  group by osct.store_id,osct.order_id,osct.payment_method order by osct.create_time desc ) as aa order by `Date` desc ";
		$query = $this->db->query($sql);
		//echo $sql;
		return $query->row;
	}
	public function getStoreName($data) {  
		$query = $this->db->query("SELECT * FROM `oc_store`  where store_id='".$data['filter_stores_id']."'  limit 1 ");

		return $query->row['name'];
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
}