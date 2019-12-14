<?php
class ModelReportProductCatsales extends Model {
	public function getSubCategories()
	{
		$sql="SELECT * FROM `oc_product_sub_catogry` ";

               


		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getSales($data = array()) {

		//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
		
                $sql="select sum(tt.quantity) as quantity,(sum(tt.total)+sum(tt.ttax)) as total,tt.name,tt.store_name,tt.product_id,tt.sub_cat_name from 
                ( select sum(p.quantity) as quantity,sum(p.total) as total,(p.tax) as tax,(p.tax*p.quantity)as ttax,o.store_name,p.product_id,p.name,opsc.name as sub_cat_name 
               from oc_order_product p 
			   left JOIN oc_order o on o.order_id=p.order_id 
			   left join oc_product_to_sub_category as optsc on optsc.product_id=p.product_id 
			   left join oc_product_sub_catogry as opsc on optsc.sub_category_id=opsc.sid
			   
			   ";

               $sql.="  where (o.date_added)<= CAST('".$data["filter_date_end"]."' as DATETIME) ";
       
               if (!empty($data['filter_date_start'])) {
			$sql .= " and (o.date_added)>=CAST('".$data["filter_date_start"]."' as DATETIME) ";
		}

		
                if (!empty($data['filter_name_id'])) {
			$sql .= " and p.product_id='".$data["filter_name_id"]."' ";
		}
		if (!empty($data['filter_category'])) {
			$sql .= " and opsc.sid='".$data["filter_category"]."' ";
		}
		if (!empty($data['filter_store'])) {
                        if (!empty($data['filter_name_id'])) {
                            $sql .= " and o.store_id='".$data["filter_store"]."' ";
                        }
                        else
                        {
                            $sql .= " and  o.store_id='".$data["filter_store"]."' ";
                        }
			
		}

		


                $sql.=" GROUP by p.product_id,o.store_id,p.order_id ) as tt GROUP by tt.product_id,tt.store_name ";



if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
              // 	echo $sql;
		$query = $this->db->query($sql);

		return $query->rows;
	}



	public function getTotalsales($data) {

	//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
$sql="select count(*) as total,sum(total) as total_amount from ( select sum(tt.quantity) as quantity,(sum(tt.total)+sum(tt.ttax)) as total,tt.name,tt.store_name,tt.product_id from 
                ( select sum(p.quantity) as quantity,sum(p.total) as total,(p.tax) as tax,(p.tax*p.quantity)as ttax,o.store_name,p.product_id,p.name 
               from oc_order_product p 
			   left JOIN oc_order o on o.order_id=p.order_id
				left join oc_product_to_sub_category as optsc on optsc.product_id=p.product_id 
			   left join oc_product_sub_catogry as opsc on optsc.sub_category_id=opsc.sid
			   ";

               $sql.="  where (o.date_added)<= CAST('".$data["filter_date_end"]."' as DATETIME) ";
       
               if (!empty($data['filter_date_start'])) {
			$sql .= " and (o.date_added)>=CAST('".$data["filter_date_start"]."' as DATETIME) ";
		}

		
                if (!empty($data['filter_name_id'])) {
			$sql .= " and p.product_id='".$data["filter_name_id"]."' ";
		}
		if (!empty($data['filter_category'])) {
			$sql .= " and opsc.sid='".$data["filter_category"]."' ";
		}
		if (!empty($data['filter_store'])) {
                        if (!empty($data['filter_name_id'])) {
                            $sql .= " and o.store_id='".$data["filter_store"]."' ";
                        }
                        else
                        {
                            $sql .= " and  o.store_id='".$data["filter_store"]."' ";
                        }
			
		}

		


                $sql.=" GROUP by p.product_id,o.store_id,p.order_id ) as tt GROUP by tt.product_id,tt.store_name ) as aa ";

		$query = $this->db->query($sql);
                            //echo $sql;
		return $query->row;

                            //return 0;
	}

public function getSalescount($data = array()) {
//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}

		
                $sql="select sum(case when o.payment_method='Cash' then p.quantity else 0 end)as qnty_of_cash, sum(case when o.payment_method='Tagged' then p.quantity else 0 end)qnty_of_tagged,
 sum(case when o.payment_method='Tagged Cash' then p.quantity else 0 end)as qnty_of_tagged_cash,
  sum(case when o.payment_method='Subsidy' then p.quantity else 0 end)as qnty_of_Subsidy,o.store_name,p.product_id, p.name from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id ";

               $sql.="  where (p.ORD_DATE)<= CAST('".$data["filter_date_end"]."' as DATETIME) ";
       
               if (!empty($data['filter_date_start'])) {
			$sql .= " and (p.ORD_DATE)>=CAST('".$data["filter_date_start"]."' as DATETIME) ";
		}

		
                if (!empty($data['filter_name_id'])) {
			$sql .= " and p.product_id='".$data["filter_name_id"]."' ";
		}
		if (!empty($data['filter_store'])) {
                        if (!empty($data['filter_name_id'])) {
                            $sql .= " and o.store_id='".$data["filter_store"]."' ";
                        }
                        else
                        {
                            $sql .= " and  o.store_id='".$data["filter_store"]."' ";
                        }
			
		}

		


                $sql.=" GROUP by p.product_id,o.store_id ";//,p.order_id



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



	public function getTotalsalescount($data) {
//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
$sql="select count(*) as total from ( select o.store_name from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id ";

               $sql.="  where (p.ORD_DATE)<= CAST('".$data["filter_date_end"]."' as DATETIME) ";
       
               if (!empty($data['filter_date_start'])) {
			$sql .= " and (p.ORD_DATE)>=CAST('".$data["filter_date_start"]."' as DATETIME) ";
		}

		
                if (!empty($data['filter_name_id'])) {
			$sql .= " and p.product_id='".$data["filter_name_id"]."' ";
		}
		if (!empty($data['filter_store'])) {
                        if (!empty($data['filter_name_id'])) {
                            $sql .= " and o.store_id='".$data["filter_store"]."' ";
                        }
                        else
                        {
                            $sql .= " and  o.store_id='".$data["filter_store"]."' ";
                        }
			
		}

		


                $sql.=" GROUP by p.product_id,o.store_id) as aa ";

		$query = $this->db->query($sql);
                            //echo $sql;
		return $query->row;

                            //return 0;
	}

	public function getSalesCompanyWise($data = array()) {

//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
        
                $sql="select sum(tt.quantity) as quantity,(sum(tt.total)+sum(tt.ttax)) as total,tt.name,tt.store_name,tt.product_id from
                ( select sum(p.quantity) as quantity,sum(p.total) as total,(p.tax) as tax,(p.tax*p.quantity)as ttax,o.store_name,p.product_id,p.name
               from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id
                LEFT JOIN oc_store as os on os.store_id = o.store_id ";

               $sql.="  where (p.ORD_DATE)<= CAST('".$data["filter_date_end"]."' as DATETIME) ";
                $sql .= " AND os.company_id='".$data['filter_company']."' ";
       
               if (!empty($data['filter_date_start'])) {
            $sql .= " and (p.ORD_DATE)>=CAST('".$data["filter_date_start"]."' as DATETIME) ";
        }

        
                if (!empty($data['filter_name_id'])) {
            $sql .= " and p.product_id='".$data["filter_name_id"]."' ";
        }
        if (!empty($data['filter_store'])) {
                        if (!empty($data['filter_name_id'])) {
                            $sql .= " and o.store_id='".$data["filter_store"]."' ";
                        }
                        else
                        {
                            $sql .= " and  o.store_id='".$data["filter_store"]."' ";
                        }
            
        }

        


                $sql.=" GROUP by p.product_id,o.store_id,p.order_id ) as tt GROUP by tt.product_id,tt.store_name ";



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

        
    public function getTotalsalesCompanyWise($data) {
//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
$sql="select count(*) as total,sum(total) as total_amount from ( select sum(tt.quantity) as quantity,(sum(tt.total)+sum(tt.ttax)) as total,tt.name,tt.store_name,tt.product_id from
                ( select sum(p.quantity) as quantity,sum(p.total) as total,(p.tax) as tax,(p.tax*p.quantity)as ttax,o.store_name,p.product_id,p.name
               from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id
                 LEFT JOIN oc_store as os on os.store_id = o.store_id";

               $sql.="  where (p.ORD_DATE)<= CAST('".$data["filter_date_end"]."' as DATETIME) ";
               $sql .= " AND os.company_id='".$data['filter_company']."' ";
               
               if (!empty($data['filter_date_start'])) {
            $sql .= " and (p.ORD_DATE)>=CAST('".$data["filter_date_start"]."' as DATETIME) ";
        }

        
                if (!empty($data['filter_name_id'])) {
            $sql .= " and p.product_id='".$data["filter_name_id"]."' ";
        }
        if (!empty($data['filter_store'])) {
                        if (!empty($data['filter_name_id'])) {
                            $sql .= " and o.store_id='".$data["filter_store"]."' ";
                        }
                        else
                        {
                            $sql .= " and  o.store_id='".$data["filter_store"]."' ";
                        }
            
        }

        


                $sql.=" GROUP by p.product_id,o.store_id,p.order_id ) as tt GROUP by tt.product_id,tt.store_name ) as aa ";

        $query = $this->db->query($sql);
                            //echo $sql;
        return $query->row;

                            //return 0;
    }

	  public function getSalescountCompanyWise($data = array()) {

//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
		
                $sql="select sum(case when o.payment_method='Cash' then p.quantity else 0 end)as qnty_of_cash, sum(case when o.payment_method='Tagged' then p.quantity else 0 end)qnty_of_tagged,
 sum(case when o.payment_method='Tagged Cash' then p.quantity else 0 end)as qnty_of_tagged_cash,
  sum(case when o.payment_method='Subsidy' then p.quantity else 0 end)as qnty_of_Subsidy,o.store_name,p.product_id, p.name from oc_order_product p
  left JOIN oc_order o on o.order_id=p.order_id 
      left join oc_store as os on os.store_id = o.store_id";

               $sql.="  where (p.ORD_DATE)<= CAST('".$data["filter_date_end"]."' as DATETIME) ";
              
               if (!empty($data['filter_date_start'])) {
			$sql .= " and (p.ORD_DATE)>=CAST('".$data["filter_date_start"]."' as DATETIME) ";
		}

		
                if (!empty($data['filter_name_id'])) {
			$sql .= " and p.product_id='".$data["filter_name_id"]."' ";
		}
		if (!empty($data['filter_store'])) {
                        if (!empty($data['filter_name_id'])) {
                            $sql .= " and o.store_id='".$data["filter_store"]."' ";
                        }
                        else
                        {
                            $sql .= " and  o.store_id='".$data["filter_store"]."' ";
                        }
			
		}
                

		
                $sql .= " AND os.company_id='".$data['filter_company']."' ";

                $sql.=" GROUP by p.product_id,o.store_id ";//,p.order_id
 


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



	public function getTotalsalescountCompanyWise($data) {
//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
$sql="select count(*) as total from ( select o.store_name from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id 
        left join oc_store as os on os.store_id = o.store_id";

               $sql.="  where (p.ORD_DATE)<= CAST('".$data["filter_date_end"]."' as DATETIME) ";
                $sql .= " AND os.company_id='".$data['filter_company']."' ";
               if (!empty($data['filter_date_start'])) {
			$sql .= " and (p.ORD_DATE)>=CAST('".$data["filter_date_start"]."' as DATETIME) ";
		}

		
                if (!empty($data['filter_name_id'])) {
			$sql .= " and p.product_id='".$data["filter_name_id"]."' ";
		}
		if (!empty($data['filter_store'])) {
                        if (!empty($data['filter_name_id'])) {
                            $sql .= " and o.store_id='".$data["filter_store"]."' ";
                        }
                        else
                        {
                            $sql .= " and  o.store_id='".$data["filter_store"]."' ";
                        }
			
		}

		


                $sql.=" GROUP by p.product_id,o.store_id) as aa ";

		$query = $this->db->query($sql);
                           // echo $sql;
		return $query->row;

                            //return 0; 
	}
}
