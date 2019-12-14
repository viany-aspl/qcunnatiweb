<?php
class ModelSettingAuditstore extends Model {
	


	public function getcurrentamount($store_id) {
                $sql="SELECT oc_user.cash,oc_store.name as storename,firstname,card FROM oc_user join oc_store on oc_user.store_id=oc_store.store_id WHERE oc_user.store_id = '" . (int)$store_id . "' and user_group_id='11' ";
		$query = $this->db->query($sql);
//echo $sql;
		return $query->row;
	}
        public function editStore($store_id,$data,$updated_by) {
                date_default_timezone_set('Asia/Kolkata');
                $audit_date=date('Y-m-d H:i:s');
                $sql="update oc_user set cash='".$data["cash"]."',audit_amount='".$data["cash"]."',audit_date='".$audit_date."',audit_status='1' WHERE store_id = '" . (int)$store_id . "' and user_group_id='11' ";
                $sql2="insert into  oc_user_store_audit_trans set audit_amount='".$data["cash"]."',updatedby='".$updated_by."',datetime='".$audit_date."' ";
		$query2 = $this->db->query($sql2);
                return $query = $this->db->query($sql);

	}
	public function getdebitamount($store_id,$store_user_id) {
$sql="SELECT oc_user.cash,oc_store.name as storename,firstname,card,oc_user.user_id FROM oc_user join oc_store on oc_user.store_id=oc_store.store_id WHERE oc_user.store_id = '" . (int)$store_id . "' and user_group_id='11' and oc_user.user_id='".$store_user_id."' ";
$query = $this->db->query($sql);
//echo $sql;
return $query->row;
}
public function debitStore($store_id,$data,$updated_by) {
date_default_timezone_set('Asia/Kolkata');
$audit_date=date('Y-m-d H:i:s');

$sql1="update oc_user set cash= cash -'".$data["cash"]."',audit_amount='".$data["cash"]."',audit_date='".$audit_date."',audit_status='1' WHERE store_id = '" . (int)$store_id . "' and user_group_id='11' and user_id='".$data['store_user_id']."' ";
$query1 = $this->db->query($sql1);

$sql2="insert into oc_waive_exp set from_date='',to_date='',response='".$data['remarks']."',store_id='".$store_id."',user_id='".$updated_by."',cr_date='".$audit_date."',cash= '".$data["cash"]."',type='1',document_no='".$data['document_no']."',store_user_id='".$data['store_user_id']."' ";
//exit;
$query2 = $this->db->query($sql2);
$insert_id=$this->db->getLastId();

try
                        {
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addstoretrans($data["cash"],$store_id,$data['store_user_id'],'DB',$insert_id,'WOFF',$data["cash"],$data['remarks']);     
                        } catch (Exception $ex) {
                                $log->write($ex->getMessage());
                        }
return '1';

}

	public function getepfdata($store_id,$store_user_id1) 
	{
		$store_user_id_arr=explode('--',$store_user_id1);
		$group=$store_user_id_arr[1];
		$store_user_id=$store_user_id_arr[0];
		$sql="SELECT oc_user.cash,oc_store.name as storename,firstname,card,oc_user.user_id,oc_user.user_group_id,oc_runner_cash_position.amount as runner_cash FROM oc_user 
				left join oc_store on oc_user.store_id=oc_store.store_id 
				left join oc_runner_cash_position on oc_user.user_id=oc_runner_cash_position.runner_id
				WHERE oc_user.store_id = '" . (int)$store_id . "' and user_group_id='".$group."' 
				and oc_user.user_id='".$store_user_id."' ";
		$query = $this->db->query($sql);
		//echo $sql;
		return $query->row;
	}
	public function expStore($store_id,$data,$updated_by) 
	{
		//print_r($data['user_group_id']);exit;
		date_default_timezone_set('Asia/Kolkata');
		$exp_date=date('Y-m-d H:i:s');
		echo $sql11="select * from oc_waive_exp where from_date='".$data["filter_date_start"]."' 
		and to_date='".$data["filter_date_end"]."' and store_id='".$store_id."' 
		and user_id='363' and cash= '".$data["cash"]."' and status='1' ";
		
		$query11 = $this->db->query($sql11);
		if($query11->num_rows>0)
		{
			return '2';
			
		}
		else
		{
		
		$sql="insert into oc_waive_exp set from_date='".$data["filter_date_start"]."',to_date='".$data["filter_date_end"]."',response='".$data['remarks']."',store_id='".$store_id."',user_id='".$updated_by."',cr_date='".$exp_date."',cash= '".$data["cash"]."',store_user_id='".$data['store_user_id']."' ";

		$query = $this->db->query($sql);
		$insert_id=$this->db->getLastId();
		if($data['user_group_id']==11)
		{
			$sql2="update oc_user set cash= cash -'".$data["cash"]."',audit_date='".$exp_date."',audit_status='1' WHERE store_id = '" . (int)$store_id . "' and user_group_id='11' and user_id='".$data['store_user_id']."' ";
			//exit;
			$query = $this->db->query($sql2);
			try
			{
				$this->load->library('trans');
				$trans=new trans($this->registry);
				$trans->addstoretrans($data["cash"],$store_id,$data['store_user_id'],'DB',$insert_id,'EXPWOFF',$data["cash"],$data['remarks']);     
			}	 
			catch (Exception $ex) 
			{
				$log->write($ex->getMessage());
			}
		}
		if($data['user_group_id']==22)
		{
			$sql2="INSERT INTO `oc_runner_cash_position` (runner_id,amount) VALUES (".$data['store_user_id'].",".$data["cash"].") ON DUPLICATE KEY UPDATE amount=amount-".$data["cash"];
            
			
			$query = $this->db->query($sql2);
			try
			{
				$sql1="SELECT amount FROM `oc_runner_cash_position`  where runner_id='".$data['store_user_id']."' limit 1 ";
				$query1 = $this->db->query($sql1);
                $current_balance=$query1->row['amount'];
				$sql="INSERT INTO `oc_runner_cash_transactions`  (runner_id,trans_type,amount,transid,current_balance) VALUES (".$data['store_user_id'].",'EXPENSE',".$data["cash"].",".$insert_id.",".$current_balance.")";
				$query = $this->db->query($sql);  
			}	 
			catch (Exception $ex) 
			{
				$log->write($ex->getMessage());
			}
		}
		return '1';
		}
	}
	public function get_store_incharges($data)
	{
		$sql="SELECT firstname,lastname,user_id,status,user_group_id FROM oc_user  WHERE oc_user.store_id = '" . (int)$data['store_id'] . "' and user_group_id in (11,22)   ";
		$query = $this->db->query($sql);
		//echo $sql;
		return $query->rows;
	}

}