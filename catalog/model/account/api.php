<?php
class ModelAccountApi extends Model 
{
	public function login($username, $password) 
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape($password) . "' AND status = '1'");

		return $query->row;
	}

	public function loginm($username, $password) 
	{
		$sql="SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'";
		$log=new Log("login-".date('Y-m-d').".log");
		$log->write($sql);
		$query = $this->db->query($sql);
		$log->write($query->row);
		return $query->row;

	}
	public function UserAuthorization($username) 
	{   $log=new Log("user-".date('Y-m-d').".log");
		$sql="SELECT * FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $this->db->escape($username) . "'  AND status = '1'";
                $log->write($sql);
		$query = $this->db->query($sql);
		return $query->row;
                
	} 

}