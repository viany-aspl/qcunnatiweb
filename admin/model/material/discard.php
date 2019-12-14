<?php

class ModelMaterialDiscard extends Model {

    public function adddiscard($data) {
      
	$log=new Log('material_discard'.date('Y-m-d').'.log');

	$sql1 = "SELECT * FROM oc_product WHERE product_id = '".$data["product"]."'  ";

	 $query1 = $this->db->query($sql1);
      	
        	$row=$query1->row;
 	
	$product_price=$row['price_tax'];
	//print_r($row);
	//exit;

              $sql2="insert into  oc_material_discard set store_id='".$data["filter_store"]."',product_id='".$data["product"]."',reason='".$this->db->escape($data["reason"])."',debit_credit='".$data["debit_credit"]."',remarks='".$this->db->escape($data["remarks"])."',quantity='".$data["quantity"]."',product_price='".$product_price."'  ";
	$log->write($sql2);
	$query2 = $this->db->query($sql2);
	$insert_id=$this->db->getLastId();
	$this->load->library('trans');
              $trans=new trans($this->registry); 

	if($data["debit_credit"]=="Debit")
	{
		$sql="update oc_product_to_store  set quantity=quantity-".$data["quantity"]." where store_id='".$data["filter_store"]."' and product_id='".$data["product"]."'  ";
		$trans->addproducttrans($data["filter_store"],$data["product"],$data["quantity"],$insert_id,'DB',$data["reason"]);  
		if($data["reason"]!="Season End Material Discard")
		{
			$sql22="update oc_product_to_store  set quantity=quantity+".$data["quantity"]." where store_id='55' and product_id='".$data["product"]."'  ";
			$trans->addproducttrans('55',$data["product"],$data["quantity"],$insert_id,'CR',$data["reason"]);  
		}
	}
	if($data["debit_credit"]=="Credit")
	{
		$sql="update oc_product_to_store  set quantity=quantity+".$data["quantity"]." where store_id='".$data["filter_store"]."' and product_id='".$data["product"]."'  ";
		$trans->addproducttrans($data["filter_store"],$data["product"],$data["quantity"],$insert_id,'CR',$data["reason"]); 
		if($data["reason"]!="Season End Material Discard")
		{
			$sql22="update oc_product_to_store  set quantity=quantity-".$data["quantity"]." where store_id='55' and product_id='".$data["product"]."'  ";
			$trans->addproducttrans('55',$data["product"],$data["quantity"],$insert_id,'DB',$data["reason"]);  
		}
	}
	$log->write($sql);
	$log->write($sql22); 
	$query = $this->db->query($sql);
	$query = $this->db->query($sql22);
              
	//print_r($data);exit;
    }


    public function getdiscardlist($data = array()) {

        $sql = "SELECT oc_material_discard.*,oc_store.name as store_name,oc_product.model as product_name FROM  
		oc_material_discard left join oc_store on oc_material_discard.store_id=oc_store.store_id 
		left join oc_product on oc_material_discard.product_id=oc_product.product_id 
		where oc_material_discard.store_id!=''  ";

	if($data['filter_store'])
	{
		$sql.=" and oc_material_discard.store_id='".$data['filter_store']."' ";
	}
	if($data['filter_product'])
	{
		$sql.=" and oc_material_discard.product_id='".$data['filter_product']."' ";
	}
	if($data['filter_reason'])
	{
		$sql.=" and oc_material_discard.reason='".$data['filter_reason']."' ";
	}
	if($data['filter_date_start'])
	{
		$sql.=" and date(oc_material_discard.create_time)>='".$data['filter_date_start']."' ";
	}
	if($data['filter_date_end'])
	{
		$sql.=" and date(oc_material_discard.create_time)<='".$data['filter_date_end']."' ";
	}
	$sql.=" order by sid desc ";
	//echo $sql;
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
       //  echo $sql;//exit;
        return $query->rows;
    }
    
    

    public function getdiscardlisttotal($data = array()) {
	
          $sql = "select count(*) as total from ( SELECT oc_material_discard.* FROM  oc_material_discard where oc_material_discard.store_id!=''  ";

	if($data['filter_store'])
	{
		$sql.=" and oc_material_discard.store_id='".$data['filter_store']."' ";
	}
	if($data['filter_product'])
	{
		$sql.=" and oc_material_discard.product_id='".$data['filter_product']."' ";
	}
	if($data['filter_reason'])
	{
		$sql.=" and oc_material_discard.reason='".$data['filter_reason']."' ";
	}
	if($data['filter_date_start'])
	{
		$sql.=" and date(oc_material_discard.create_time)>='".$data['filter_date_start']."' ";
	}
	if($data['filter_date_end'])
	{
		$sql.=" and date(oc_material_discard.create_time)<='".$data['filter_date_end']."' ";
	}
	$sql.=" ) as aa ";
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
    


}
