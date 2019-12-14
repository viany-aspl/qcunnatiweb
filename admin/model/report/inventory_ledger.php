<?php
class ModelReportInventoryLedger extends Model {
	
	public function getproducttrans($data = array()) {
		//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
		$sql = "SELECT op.quantity,op.order_id,op.cr_db,op.trans_type,
		op.current_quantity as current_quantity,op.trans_time as trans_time ,
		product.model,store.name,concat(oc_user.firstname,' ',oc_user.lastname) as sale_by,op.billing_type 
		FROM `oc_product_trans` as op
LEFT JOIN oc_store as store on store.store_id=op.store_id
left join oc_order on op.order_id=oc_order.order_id
left join oc_user on oc_user.user_id=oc_order.user_id
LEFT JOIN oc_product as product on product.product_id=op.product_id";

		if (!empty($data['filter_stores_id'])) {
			$sql .= " WHERE op.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} else {
			$sql .= " WHERE op.store_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (op.trans_time) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (op.trans_time) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
		if (!empty($data['filter_product_id'])) {
			$sql .= " AND op.product_id= '" . $this->db->escape($data['filter_product_id']) . "'";
		}
		$sql .= " ORDER BY SID DESC";

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
	public function getproducttransTotal($data = array()) {
		//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
		$sql = "select count(*) as total from (SELECT op.quantity,op.order_id,op.cr_db,op.trans_type,DATE(op.trans_time) as trans_time ,product.model,store.name FROM `oc_product_trans` as op
LEFT JOIN oc_store as store on store.store_id=op.store_id
LEFT JOIN oc_product as product on product.product_id=op.product_id";

		if (!empty($data['filter_stores_id'])) {
			$sql .= " WHERE op.store_id= '" . (int)$data['filter_stores_id'] . "'";
		} else {
			$sql .= " WHERE op.store_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (op.trans_time) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (op.trans_time) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
		if (!empty($data['filter_product_id'])) {
			$sql .= " AND op.product_id= '" . $this->db->escape($data['filter_product_id']) . "'";
		}
		$sql .= " ) as aa";
//echo $sql;
		
//echo  $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
}