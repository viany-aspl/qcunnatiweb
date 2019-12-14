<?php
date_default_timezone_set("Asia/Calcutta");
class ModelNewsNews extends Model {

public function submit_news($data = array())
        {
              $DatePublished=date('Y-m-d');
              $sql="insert into  `oc_news` set  `NewsHeader`='".$data["subject"]."',`NewsDetails`='".$data["message"]."',`NewsImage`='".$data["link"]."',`PublishedBy`='".$data["logged_user"]."',`IsActive`='1',`DatePublished`='".$DatePublished."',`Category`='".$data["category"]."'  ";
	$log=new Log("news-".date('Y-m-d').".log");
	$log->write($sql);
	$query = $this->db->query($sql);
              $insertid=$this->db->getLastId();
              $log->write("insertid - ".$insertid);
              return $insertid; 
        }

}