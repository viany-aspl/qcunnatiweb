<?php
class ModelTagFraud extends Model {
	public function getFraud($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_fraud_leads` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}
}