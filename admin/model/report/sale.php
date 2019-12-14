<?php
class ModelReportSale extends Model {
	// Sales
	public function getTotalSales($data = array()) {
		$sql = "SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '5'";

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
		
	// Map
	public function getTotalOrdersByCountry() {
		$query = $this->db->query("SELECT COUNT(*) AS total, SUM(o.total) AS amount, c.iso_code_2 FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "country` c ON (o.payment_country_id = c.country_id) WHERE o.order_status_id = '5' GROUP BY o.payment_country_id");

		return $query->rows;
	}
		
	// Orders
	public function getTotalOrdersByDay($data) {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}
		
		$order_data = array();

		for ($i = 0; $i < 24; $i++) {
			$order_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}
		$sql="SELECT COUNT(order_id) AS total, HOUR(date_added) AS hour FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) = DATE(NOW()) ";	
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
		$sql.=" GROUP BY HOUR(date_added) ORDER BY date_added ASC ";
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByWeek($data) {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}		
		
		$order_data = array();

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));

			$order_data[date('w', strtotime($date))] = array(
				'day'   => date('D', strtotime($date)),
				'total' => 0
			);
		}
		$sql="SELECT COUNT(order_id) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") ";
		
		$sql.=" AND DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') ";
		
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
		$sql.=" GROUP BY DAYNAME(date_added) ";
		//echo $sql;
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$order_data[date('w', strtotime($result['date_added']))] = array(
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByMonth($data) {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}
				
		$order_data = array();

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$order_data[date('j', strtotime($date))] = array(
				'day'   => date('d', strtotime($date)),
				'total' => 0
			);
		}
		$sql="SELECT COUNT(order_id) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND (date_added) >= CAST('" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . " 00:00:00:00' as datetime) ";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
		
		$sql.="  GROUP BY DATE(date_added) ";
		//echo $sql;
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$order_data[date('j', strtotime($result['date_added']))] = array(
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		//$log=new Log('testquery.log');
		//$log->write($sql);
		return $order_data;
	}

	public function getTotalOrdersByYear($data) {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}
				
		$order_data = array();

		for ($i = 1; $i <= 12; $i++) {
			$order_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			);
		}
		$sql="SELECT COUNT(order_id) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND YEAR(date_added) = YEAR(NOW()) ";
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
		$sql.=" GROUP BY MONTH(date_added)";
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$order_data[date('n', strtotime($result['date_added']))] = array(
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}
	
	public function getOrders($data = array()) {
		//$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`, (SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS products, (SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id) AS tax, SUM(o.total) AS `total` FROM `" . DB_PREFIX . "order` o";


               $sql = "SELECT oc_store.name as store_name,MIN(o.date_added) AS date_start, "
                    . "MAX(o.date_added) AS date_end, COUNT(*) AS `orders`,"
                    . " (SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op "
                    . "WHERE op.order_id = o.order_id GROUP BY op.order_id) AS products, "
                    . "(SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot "
                    . "WHERE ot.order_id = o.order_id AND ot.code = 'tax' "
                    . "GROUP BY ot.order_id) AS tax,(SELECT SUM(ot.value) FROM `oc_order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total' GROUP BY ot.order_id) AS sub_total, SUM(o.total) AS `total` "
                    . "FROM `" . DB_PREFIX . "order` o join oc_store on o.store_id=oc_store.store_id";


		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
		}
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
                 
		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY DATE(o.date_added),o.store_id";//YEAR(o.date_added), WEEK(o.date_added)";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added)";
				break;
		}

		$sql .= " ORDER BY o.date_added DESC";

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

	public function getTotalOrders($data = array()) {

                $sql = "select count(*) as total,sum(total) as amount_total,sum(orders) as orders from (SELECT oc_store.name as store_name,MIN(o.date_added) AS date_start, "
                    . "MAX(o.date_added) AS date_end, COUNT(*) AS `orders`,"
                    . " (SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op "
                    . "WHERE op.order_id = o.order_id GROUP BY op.order_id) AS products, "
                    . "(SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot "
                    . "WHERE ot.order_id = o.order_id AND ot.code = 'tax' "
                    . "GROUP BY ot.order_id) AS tax,(SELECT SUM(ot.value) FROM `oc_order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total' GROUP BY ot.order_id) AS sub_total, SUM(o.total) AS `total` "
                    . "FROM `" . DB_PREFIX . "order` o join oc_store on o.store_id=oc_store.store_id";


		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
                 
		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY DATE(o.date_added),o.store_id";//YEAR(o.date_added), WEEK(o.date_added)";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added)";
				break;
		}

		$sql .= " ORDER BY o.date_added DESC) as aaa";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getTaxes($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (ot.order_id = o.order_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
		}

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

	public function getTotalTaxes($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getShipping($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
		}

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

	public function getTotalShipping($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
         //////////////////sale graph start here////////////////////
 public function getTotalSaleByDay($data) {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}
		
		$order_data = array();

		for ($i = 0; $i < 24; $i++) {
			$order_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}
		$sql="SELECT SUM(total) AS total, HOUR(date_added) AS hour FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) = DATE(NOW()) ";	
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
		$sql.=" GROUP BY HOUR(date_added) ORDER BY date_added ASC";
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}

		return $order_data;
	}
    public function getTotalSaleByWeek($data) {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}		
		
		$order_data = array();

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));

			$order_data[date('w', strtotime($date))] = array(
				'day'   => date('D', strtotime($date)),
				'total' => 0
			);
		}
		$sql="SELECT SUM(total) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') ";
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
		$sql.=" GROUP BY DAYNAME(date_added) ";
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$order_data[date('w', strtotime($result['date_added']))] = array(
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}
    public function getTotalSaleByMonth($data) {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}
				
		$order_data = array();

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$order_data[date('j', strtotime($date))] = array(
				'day'   => date('d', strtotime($date)),
				'total' => 0
			);
		}
		$sql="SELECT SUM(total) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id ='5' AND (date_added) >=CAST('" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . " 00:00:00' as datetime) ";
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
		$sql.=" GROUP BY DATE(date_added)";
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$order_data[date('j', strtotime($result['date_added']))] = array(
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}
 public function getTotalSaleByYear($data) {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}
				
		$order_data = array();

		for ($i = 1; $i <= 12; $i++) {
			$order_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			);
		}
		$sql="SELECT SUM(total) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id ='5' AND YEAR(date_added) = YEAR(NOW()) ";
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
		$sql.=" GROUP BY MONTH(date_added)";
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$order_data[date('n', strtotime($result['date_added']))] = array(
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}            

//company wise
public function getOrdersCompanywise($data = array()) {
		//$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`, (SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS products, (SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id) AS tax, SUM(o.total) AS `total` FROM `" . DB_PREFIX . "order` o";


               $sql = "SELECT oc_store.name as store_name,MIN(o.date_added) AS date_start, "
                    . "MAX(o.date_added) AS date_end, COUNT(*) AS `orders`,"
                    . " (SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op "
                    . "WHERE op.order_id = o.order_id GROUP BY op.order_id) AS products, "
                    . "(SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot "
                    . "WHERE ot.order_id = o.order_id AND ot.code = 'tax' "
                    . "GROUP BY ot.order_id) AS tax,(SELECT SUM(ot.value) FROM `oc_order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total' GROUP BY ot.order_id) AS sub_total, SUM(o.total) AS `total` "
                    . "FROM `" . DB_PREFIX . "order` o join oc_store on o.store_id=oc_store.store_id
                       
                      where oc_store.company_id='".$data['filter_company']."' ";


		if (!empty($data['filter_order_status_id'])) {
			$sql .= " and o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= "and o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
            
                 
		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY DATE(o.date_added),o.store_id";//YEAR(o.date_added), WEEK(o.date_added)";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added)";
				break;
		}
                
		$sql .= " ORDER BY o.date_added DESC";

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

	public function getTotalOrdersCompanywise($data = array()) {

                $sql = "select count(*) as total,sum(total) as amount_total,sum(orders) as orders from (SELECT oc_store.name as store_name,MIN(o.date_added) AS date_start, "
                    . "MAX(o.date_added) AS date_end, COUNT(*) AS `orders`,"
                    . " (SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op "
                    . "WHERE op.order_id = o.order_id GROUP BY op.order_id) AS products, "
                    . "(SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot "
                    . "WHERE ot.order_id = o.order_id AND ot.code = 'tax' "
                    . "GROUP BY ot.order_id) AS tax,(SELECT SUM(ot.value) FROM `oc_order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total' GROUP BY ot.order_id) AS sub_total, SUM(o.total) AS `total` "
                    . "FROM `" . DB_PREFIX . "order` o join oc_store on o.store_id=oc_store.store_id 
                        
                       where oc_store.company_id='".$data['filter_company']."' ";


		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
                 
		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY DATE(o.date_added),o.store_id";//YEAR(o.date_added), WEEK(o.date_added)";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added)";
				break;
		}

		$sql .= " ORDER BY o.date_added DESC) as aaa";

		$query = $this->db->query($sql);

		return $query->row;
	}


}