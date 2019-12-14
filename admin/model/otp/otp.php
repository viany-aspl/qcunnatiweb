<?php
class ModelOtpOtp extends Model {
	public function insertotp($data) 
	{
		$this->db->query("INSERT INTO `" . DB_PREFIX . "otp_trans` SET otp = '" . $this->db->escape($data['otp']) . "', `mobile` = '" . $this->db->escape($data['fm_mobile']) . "', system_trans_id = '" .$this->db->escape($data['transaction_id']) . "',name = '" .$this->db->escape($data['fm_name']) . "', date_time = NOW()");
		return $this->db->getLastId();
	}

	


	public function getotpTransId($data) 
	{
		$log=new Log("optgen-".date('Y-m-d').".log");
		 $log->write("datas--");  
		 $log->write($data['transaction_id']);  
		 $sql="SELECT * FROM oc_otp_trans where `system_trans_id`='".$this->db->escape($data['transaction_id'])."' ";
		 $log->write($sql);  
		$query = $this->db->query($sql);
		$log->write($query);  
		return $query->row;
	}

	
}