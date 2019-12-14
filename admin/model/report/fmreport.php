<?php
class ModelReportFmreport extends Model {
	
public function getRecords($data = array()) {
	   
	if($data['filter_report']=='ADVANCE')
	{
            $sql="select date(create_time) create_date,store_name,fmcode,fmname,product_id,model,sum(quantity)  qnty,( sum(op.total)+(op.tax* sum(quantity) ) ) ttotal ,COUNT(od.invoice_no) cnt from oc_order_delivery_advance od left join oc_order_product op on op.order_id=od.invoice_no left join oc_order oc on oc.order_id=op.order_id   where od.invoice_no<> '' and fmcode <> 0  ";
                  
            		if (!empty($data['filter_date'])) {
			$sql .= " and date(create_time)= '" . $this->db->escape($data['filter_date']) . "'";
		}

		if (!empty($data['filter_store'])) {
			$sql .= " and oc.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}
            		
            $sql.=" GROUP by fmcode,product_id order by date(create_time) desc ";
	}
	////////////////////////////////////////////////////////
	if($data['filter_report']=='INDENT')
	{
            $sql="select date(create_time) create_date,store_name,fmcode,fmname,product_id,model,sum(quantity)  qnty,( sum(op.total)+(op.tax* sum(quantity) ) ) ttotal ,COUNT(od.invoice_no) cnt from oc_order_delivery od left join oc_order_product op on op.order_id=od.invoice_no left join oc_order oc on oc.order_id=op.order_id   where od.invoice_no<> '' and fmcode <> 0    

	 ";
                  
            		if (!empty($data['filter_date'])) {
			$sql .= " and date(create_time)= '" . $this->db->escape($data['filter_date']) . "'";
		}

		if (!empty($data['filter_store'])) {
			$sql .= " and oc.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}
            		
            $sql.=" GROUP by fmcode,product_id order by date(create_time) desc ";
	}
	///////////////////////////////////////////////////////////
	if($data['filter_report']=='ALL')
	{
            $sql="select (create_time) as create_date,store_name,fmcode,fmname,product_id,model,sum( qnty) as qnty,sum( ttotal) as ttotal , sum(cnt) as cnt from 
	(
select date(create_time) create_time ,store_name,fmcode,fmname,product_id,model,sum(quantity)  qnty,( sum(op.total)+(op.tax* sum(quantity) ) ) ttotal ,COUNT(od.invoice_no) as cnt from oc_order_delivery od left join oc_order_product op on op.order_id=od.invoice_no left join oc_order oc on oc.order_id=op.order_id   where od.invoice_no<> '' and fmcode <> 0 and date(create_time)='" . $this->db->escape($data['filter_date']) . "' and oc.store_id='" . $this->db->escape($data['filter_store']) . "' GROUP by fmcode,product_id 

union all

select date(create_time) create_time,store_name,fmcode,fmname,product_id,model,sum(quantity)  qnty,( sum(op.total)+(op.tax* sum(quantity) ) ) ttotal ,COUNT(od.invoice_no) as cnt from oc_order_delivery_advance od left join oc_order_product op on op.order_id=od.invoice_no left join oc_order oc on oc.order_id=op.order_id   where od.invoice_no<> '' and fmcode <> 0 and date(create_time)='" . $this->db->escape($data['filter_date']) . "' and oc.store_id='" . $this->db->escape($data['filter_store']) . "' GROUP by fmcode,product_id 
) a GROUP by fmcode,product_id     

	 ";
                  
            		
            		
            $sql.="  order by date(create_time) desc ";
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
                           //echo $sql;//exit;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

	public function getTotalRecords($data = array()) {
	
	if($data['filter_report']=='ADVANCE')
	{
            $sql="select count(*) as total from (select date(create_time) create_date from oc_order_delivery_advance od left join oc_order_product op on op.order_id=od.invoice_no left join oc_order oc on oc.order_id=op.order_id   where od.invoice_no<> '' and fmcode <> 0    

	 ";
                  
            		if (!empty($data['filter_date'])) {
			$sql .= " and date(create_time)= '" . $this->db->escape($data['filter_date']) . "'";
		}

		if (!empty($data['filter_store'])) {
			$sql .= " and oc.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}
            		
            $sql.=" GROUP by fmcode,product_id  ";
	$sql.=" ) as aa";
            }
	if($data['filter_report']=='INDENT')
	{
            $sql="select count(*) as total from (select date(create_time) create_date from oc_order_delivery od left join oc_order_product op on op.order_id=od.invoice_no left join oc_order oc on oc.order_id=op.order_id   where od.invoice_no<> '' and fmcode <> 0    

	 ";
                  
            		if (!empty($data['filter_date'])) {
			$sql .= " and date(create_time)= '" . $this->db->escape($data['filter_date']) . "'";
		}

		if (!empty($data['filter_store'])) {
			$sql .= " and oc.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}
            		
            $sql.=" GROUP by fmcode,product_id  ";
	$sql.=" ) as aa";
            }

	if($data['filter_report']=='ALL')
	{
            $sql="select count(*) as total from ( select (create_time) as create_date,fmcode from (
select date(create_time) create_time,fmcode  from oc_order_delivery od left join oc_order_product op on op.order_id=od.invoice_no left join oc_order oc on oc.order_id=op.order_id   where od.invoice_no<> '' and fmcode <> 0 and date(create_time)='" . $this->db->escape($data['filter_date']) . "' and oc.store_id='" . $this->db->escape($data['filter_store']) . "' GROUP by fmcode,product_id 
union all
select date(create_time) create_time,fmcode from oc_order_delivery_advance od left join oc_order_product op on op.order_id=od.invoice_no left join oc_order oc on oc.order_id=op.order_id   where od.invoice_no<> '' and fmcode <> 0 and date(create_time)='" . $this->db->escape($data['filter_date']) . "' and oc.store_id='" . $this->db->escape($data['filter_store']) . "' GROUP by fmcode ,product_id
) a GROUP by fmcode,product_id    ) as aaa 

	 ";
              
            }
	
                           //echo $sql;
		
		$query = $this->db->query($sql);
                

		return $query->row['total'];
	}
	public function getOrdersCompanywise($data = array()) {

	$sql = "SELECT count(indent_no) as indent_no,oc.store_id,oc.store_name,'Advance' as 
                ordertype,ad.fmcode as fmcode,ad.fmname as fmname FROM `oc_order_delivery_advance` 
                    ad left join oc_order oc on oc.order_id=ad.invoice_no 
                    left join oc_store_to_unit on oc_store_to_unit.store_id=oc.store_id
                    left join oc_store on oc_store.store_id=oc.store_id
                    where otp_verified='1' ";
                    if (!empty($data['filter_company'])) 
                    {             
                        $sql.=" and oc_store.company_id='".$data['filter_company']."' ";
                    }
                    if (!empty($data['filter_store'])) 
                    {             
                        $sql.=" and oc.store_id='".$data['filter_store']."' ";
                    }
                    if (!empty($data['filter_date_start'])) 
                    {             
                        $sql.=" and date(oc.date_added)>='".$data['filter_date_start']."' ";
                    }
                    if (!empty($data['filter_date_end'])) 
                    {             
                        $sql.=" and date(oc.date_added)<='".$data['filter_date_end']."' ";
                    }
                    if (!empty($data['filter_fm_name'])) 
                    {             
                        $sql.=" and ad.fmcode='".$data['filter_fm_name']."' ";
                    }
                    if (!empty($data['filter_unit'])) 
                    {             
                        $sql.=" and oc_store_to_unit.unit_id='".$data['filter_unit']."' ";
                    }
                    
            $sql.=" GROUP by oc.store_id,ad.fmcode ";
                    
            $sql.=" UNION ALL 

                    SELECT count(indent_no) as indent_no,oc.store_id,oc.store_name,
                    'Indent' as ordertype,ad.fmcode as fmcode,ad.fmname as fmname FROM `oc_order_delivery` ad
                    left join oc_order oc on oc.order_id=ad.invoice_no 
                    left join oc_store_to_unit on oc_store_to_unit.store_id=oc.store_id
                    left join oc_store on oc_store.store_id=oc.store_id
                    where otp_verified='1' ";
                    if (!empty($data['filter_company'])) 
                    {             
                        $sql.=" and oc_store.company_id='".$data['filter_company']."' ";
                    }
                    if (!empty($data['filter_store'])) 
                    {             
                        $sql.=" and oc.store_id='".$data['filter_store']."' ";
                    }
                    if (!empty($data['filter_date_start'])) 
                    {             
                        $sql.=" and date(oc.date_added)>='".$data['filter_date_start']."' ";
                    }
                    if (!empty($data['filter_date_end'])) 
                    {             
                        $sql.=" and date(oc.date_added)<='".$data['filter_date_end']."' ";
                    }
                    if (!empty($data['filter_fm_name'])) 
                    {             
                        $sql.=" and ad.fmcode='".$data['filter_fm_name']."' ";
                    }
                    if (!empty($data['filter_unit'])) 
                    {             
                        $sql.=" and oc_store_to_unit.unit_id='".$data['filter_unit']."' ";
                    }
                    
                $sql.=" GROUP by oc.store_id,ad.fmcode ";
                $sql .=" ORDER BY fmname DESC";
        

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
        
        public function getTotalOrdersCompanywise($data = array()) {
	$sql = "select count(*) as total from ( SELECT count(indent_no) as indent_no,oc.store_id,oc.store_name,'Advance' as 
                ordertype,ad.fmcode as fmcode,ad.fmname as fmname FROM `oc_order_delivery_advance` 
                    ad left join oc_order oc on oc.order_id=ad.invoice_no 
                    left join oc_store_to_unit on oc_store_to_unit.store_id=oc.store_id
                    left join oc_store on oc_store.store_id=oc.store_id
                    where otp_verified='1' ";
                    if (!empty($data['filter_company'])) 
                    {             
                        $sql.=" and oc_store.company_id='".$data['filter_company']."' ";
                    }
                    if (!empty($data['filter_store'])) 
                    {             
                        $sql.=" and oc.store_id='".$data['filter_store']."' ";
                    }
                    if (!empty($data['filter_date_start'])) 
                    {             
                        $sql.=" and date(oc.date_added)>='".$data['filter_date_start']."' ";
                    }
                    if (!empty($data['filter_date_end'])) 
                    {             
                        $sql.=" and date(oc.date_added)<='".$data['filter_date_end']."' ";
                    }
                    if (!empty($data['filter_fm_name'])) 
                    {             
                        $sql.=" and ad.fmcode='".$data['filter_fm_name']."' ";
                    }
                    if (!empty($data['filter_unit'])) 
                    {             
                        $sql.=" and oc_store_to_unit.unit_id='".$data['filter_unit']."' ";
                    }
                    
            $sql.=" GROUP by oc.store_id,ad.fmcode ";
                    
            $sql.=" UNION ALL 

                    SELECT count(indent_no) as indent_no,oc.store_id,oc.store_name,
                    'Indent' as ordertype,ad.fmcode as fmcode,ad.fmname as fmname FROM `oc_order_delivery` ad
                    left join oc_order oc on oc.order_id=ad.invoice_no 
                    left join oc_store_to_unit on oc_store_to_unit.store_id=oc.store_id
                    left join oc_store on oc_store.store_id=oc.store_id
                    where otp_verified='1' ";
                    if (!empty($data['filter_company'])) 
                    {             
                        $sql.=" and oc_store.company_id='".$data['filter_company']."' ";
                    }
                    if (!empty($data['filter_store'])) 
                    {             
                        $sql.=" and oc.store_id='".$data['filter_store']."' ";
                    }
                    if (!empty($data['filter_date_start'])) 
                    {             
                        $sql.=" and date(oc.date_added)>='".$data['filter_date_start']."' ";
                    }
                    if (!empty($data['filter_date_end'])) 
                    {             
                        $sql.=" and date(oc.date_added)<='".$data['filter_date_end']."' ";
                    }
                    if (!empty($data['filter_fm_name'])) 
                    {             
                        $sql.=" and ad.fmcode='".$data['filter_fm_name']."' ";
                    }
                    if (!empty($data['filter_unit'])) 
                    {             
                        $sql.=" and oc_store_to_unit.unit_id='".$data['filter_unit']."' ";
                    }
                    
                $sql.=" GROUP by oc.store_id,ad.fmcode ";
                $sql .=" ) as aa";
		$query = $this->db->query($sql);

		return $query->row;
	}
    public function getfm() 
	{
     
        $sql="SELECT fmcode,fmname from oc_order_delivery ";

        $query = $this->db->query($sql);
        
        return $query->rows;
    }   



}