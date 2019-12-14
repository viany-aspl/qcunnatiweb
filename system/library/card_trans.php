<?php
class card_trans {
public function __construct($registry) {
          $this->config = $registry->get('config');
	  $this->db = $registry->get('db');
                
}
public function addtrans($CARD_SERIAL_NO,$CARD_STATUS_ID,$DATE,$TRANSACTION_BY,$OLD_CARD_NO,$CARD_ISSUE_SID)
{
			$log= new Log('card-trans-'.date('Y-m-d').'.log');
                        $CARD_STATUS_NAME=$this->getstatusname($CARD_STATUS_ID);
			$p_sql = " insert into oc_card_lcm_history set CARD_SERIAL_NO='".$CARD_SERIAL_NO."',CARD_STATUS_ID ='".$CARD_STATUS_ID."',CARD_STATUS_NAME='".$CARD_STATUS_NAME."',DATE='".$DATE."',TRANSACTION_BY='".$TRANSACTION_BY."',OLD_CARD_NO='".$OLD_CARD_NO."',CARD_ISSUE_SID='".$CARD_ISSUE_SID."'  ";
			$log->write($p_sql);
			$query = $this->db->query($p_sql);
			
			
}
public function getstatusname($CARD_STATUS_ID)
{
			
			$p_sql = " select * from oc_card_lcm_master where SID='".$CARD_STATUS_ID."'  ";
			
			$query = $this->db->query($p_sql);
			return $query->row['STATUS_NAME'];
			
}

}
