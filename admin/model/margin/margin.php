<?php
class ModelMarginMargin extends Model 
{
	public function getstore() 
    {
        $store_data = array();
		$sql="select oc_store.*,
			(select value from oc_setting where oc_setting.store_id=oc_store.store_id and `key`='config_storestatus' limit 1) as store_status,
			(SELECT ost.type_name as config_storetype FROM oc_setting 
			left join oc_store_type as ost on ost.sid=oc_setting.value where store_id=oc_store.store_id and `key`='config_storetype' limit 1) as storetype 
			from oc_store ORDER BY oc_store.name asc";
		$query = $this->db->query($sql);
        foreach ($query->rows as $storedb) 
		{
			if($storedb['store_status']!="0")
			{
                         $store_datan =array(
								'store_id' => $storedb['store_id'],
								'name'     => $storedb['name'],
								'url'      => $storedb['url'],
                        		'config_storestatus'=>$storedb['store_status'],
                        		'config_storetype'=>$storedb['storetype']
			
		);
		array_push($store_data,  $store_datan);  
		}
		}
		return $store_data;
	}
	
	public function addmargin($data) 
    {           
        $store=(explode(",",$data['store_id']));
        $month=(explode(",",$data['month_id']));
          
		$this->db->query("INSERT INTO " . DB_PREFIX . "margin SET store_id = '" . $this->db->escape((int)$store[0]) . "',
				`month_id`='" . $this->db->escape((int)$month[0]) . "',
				`store_name` = '" . $this->db->escape($store[1]) . "',
				`month_name` = '" . $this->db->escape($month[1]) . "',
				`upload_margin` = '" . $this->db->escape($data['upload_margin']) . "'");

		
	}
    public function editmargin($margin_id,$data) 
    {      
        $store=(explode(",",$data['store_id']));
        $month=(explode(",",$data['month_id']));
        $sql="UPDATE " . DB_PREFIX . "margin SET store_id = '" . $this->db->escape((int)$store[0]) . "',
				`month_id`='" . $this->db->escape((int)$month[0]) . "',
				`store_name` = '" . $this->db->escape($store[1]) . "',
				`month_name` = '" . $this->db->escape($month[1]) . "',
				`upload_margin` = '" . $this->db->escape($data['upload_margin']) . "' where margin_id=".(int)$margin_id;
		$this->db->query($sql);
		//exit;
	}
	public function deletemargin($margin_id) 
    {   
		$sql="delete from " . DB_PREFIX . "margin  where margin_id=".$margin_id;
		
		$this->db->query($sql);//exit;
	}
    public function getstores($data = array())
    { 
        $sql="select * from ".DB_PREFIX . "margin where margin_id!='' "; 
		if (!empty($data['filter_store'])) 
        {
            $sql.=" and store_id=".$data['filter_store'];
		}

		if (!empty($data['filter_month'])) 
        {
            $sql.=" and month_id=".$data['filter_month'];
		}
		if (isset($data['start']) || isset($data['limit'])) 
		{
			if ($data['start'] < 0) 
			{
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) 
			{
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
		
		return $query->rows;
    }
	public function getstorestotal($data = array())
    { 
        $sql="select * from ".DB_PREFIX . "margin where margin_id!='' "; 
		if (!empty($data['filter_store'])) 
        {
            $sql.=" and store_id=".$data['filter_store'];
		}

		if (!empty($data['filter_month'])) 
        {
            $sql.=" and month_id=".$data['filter_month'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
    }
	public function getmargin($margin_id)
    { 
        $sql="select * from ".DB_PREFIX . "margin where margin_id='".$margin_id."' "; 
		
		$query = $this->db->query($sql);
		
		return $query->row;
    }
	public function addsetmargin($data) 
    {           
        //$store=(explode(",",$data['store_id']));
        //$month=(explode(",",$data['month_id']));
          
		  //exit;
		$sql="INSERT INTO " . DB_PREFIX . "product_margin SET product_name = '" . $this->db->escape($data['product_name']) . "',
		        `product_id`='" . $this->db->escape($data['product_id']) . "',
				`margin`='" . $this->db->escape($data['margin']) . "',
				`month_year` = '" . $this->db->escape($data['month']) . "',
				`create_date` = Now(),
				`user_id` = '" .$this->user->getId(). "'";
		   
		    $query = $this->db->query($sql);
            return $query;
		
	}
	
	
	public function deletemarginproduct($margin_id) 
    {   
		$sql="delete from " . DB_PREFIX . "product_margin  where margin_id=".$margin_id;
		
		$this->db->query($sql);//exit;
	}
	 public function getmarginlist($data = array())
    { 
        $sql="select * from ".DB_PREFIX . "product_margin where margin_id!='' "; 
		if (!empty($data['filter_name'])) 
        {
            $sql.=" and product_name='".$data['filter_name']."'";
		}

		if (!empty($data['filter_month'])) 
        {
            $sql.=" and month_year='".$data['filter_month']."'";
		}
		if (isset($data['start']) || isset($data['limit'])) 
		{
			if ($data['start'] < 0) 
			{
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) 
			{
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		//echo $sql;
		$query = $this->db->query($sql);
		
		return $query->rows;
    }
	public function getmargintotal($data = array())
    { 
        $sql="select * from ".DB_PREFIX . "product_margin where margin_id!='' "; 
		if (!empty($data['filter_name'])) 
        {
            $sql.=" and product_name=".$data['filter_name'];
		}

		if (!empty($data['filter_month'])) 
        {
            $sql.=" and month_year=".$data['filter_month'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
    }
	
	
	
    public function editmarginproduct($margin_id,$data) 
    {     
		//print_r($data);	
        
       $sql="UPDATE " . DB_PREFIX . "product_margin SET
				`product_name`='" . $this->db->escape($data['product_name']) . "',
				`product_id` = '" . $this->db->escape($data['product_id']) . "',
				`margin` = '" . $this->db->escape($data['margin']) . "' where margin_id=".(int)$margin_id;
		$this->db->query($sql);
		//exit;
	}
	
	
	public function getmarginlistbyid($margin_id)
    { 
        $sql="select * from ".DB_PREFIX . "product_margin where margin_id='".$margin_id."'"; 
		$query = $this->db->query($sql);
		
		return $query->row;
    }
}
