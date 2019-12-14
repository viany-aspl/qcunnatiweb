<?php
class ModelReportProductreconciliation extends Model {
	
	
   public function getOrdersReceived($data = array()) 
   {
       if($data["filter_name_id"]=="")
       {
         $filter_name_id="null";
       }
       else
       {
            $filter_name_id= $data["filter_name_id"];
       }
       if($data["filter_date_start"]=="")
       {
         $filter_date_start="null";
       }
       else
       {
            $filter_date_start="'".$data["filter_date_start"]."'";
       }
       if($data["filter_date_end"]=="")
       {
         $filter_date_end="null";
       }
       else
       {
            $filter_date_end="'".$data["filter_date_end"]."'";
       }

       $sql="  select ocs.name as store_name,podr.store_id, podr.product_id, pop.name as product_name, podr.quantity,poo.order_date,poo.order_sup_send as recive_date, 'Akshamaala' as store_transfer from oc_po_receive_details as podr left join oc_po_order as poo on podr.order_id = poo.id left join oc_store as ocs on ocs.store_id = podr.store_id left join oc_store as ocs1 on ocs1.store_id = podr.supplier_id left join oc_po_product as pop on pop.product_id = podr.product_id where poo.receive_bit=1 and poo.order_sup_send between ifnull(".$filter_date_start.",poo.order_sup_send) and ifnull(".$filter_date_end.",poo.order_sup_send) and podr.product_id=ifnull(".$filter_name_id.", podr.product_id) GROUP BY podr.order_id ORDER BY `poo`.`order_date` DESC  ";
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
                            //echo $sql;
		return $query->rows;
   }

    public function getTotalOrdersReceived($data = array()) 
   {
       if($data["filter_name_id"]=="")
       {
         $filter_name_id="null";
       }
       else
       {
            $filter_name_id= $data["filter_name_id"];
       }
       if($data["filter_date_start"]=="")
       {
         $filter_date_start="null";
       }
       else
       {
            $filter_date_start="'".$data["filter_date_start"]."'";
       }
       if($data["filter_date_end"]=="")
       {
         $filter_date_end="null";
       }
       else
       {
            $filter_date_end="'".$data["filter_date_end"]."'";
       }

       $sql=" select count(*) as total,sum(quantity) as total_quantity from ( select ocs.name as store_name,podr.store_id, podr.product_id, pop.name as product_name, podr.quantity,poo.order_date,poo.order_sup_send as recive_date, 'Akshamaala' as store_transfer from oc_po_receive_details as podr left join oc_po_order as poo on podr.order_id = poo.id left join oc_store as ocs on ocs.store_id = podr.store_id left join oc_store as ocs1 on ocs1.store_id = podr.supplier_id left join oc_po_product as pop on pop.product_id = podr.product_id where poo.receive_bit=1 and poo.order_sup_send between ifnull(".$filter_date_start.",poo.order_sup_send) and ifnull(".$filter_date_end.",poo.order_sup_send) and podr.product_id=ifnull(".$filter_name_id.", podr.product_id) GROUP BY podr.order_id  ";
        

			$sql .= "   ) as aa";
		
                
		$query = $this->db->query($sql);
                //echo $sql;
		return $query->row;
   }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function getOrders($data = array()) 
   {
       if($data["filter_name_id"]=="")
       {
         $filter_name_id="null";
       }
       else
       {
            $filter_name_id= $data["filter_name_id"];
       }
       if($data["filter_date_start"]=="")
       {
         $filter_date_start="null";
       }
       else
       {
            $filter_date_start="'".$data["filter_date_start"]."'";
       }
       if($data["filter_date_end"]=="")
       {
         $filter_date_end="null";
       }
       else
       {
            $filter_date_end="'".$data["filter_date_end"]."'";
       }

       $sql="  select sum(tt.quantity) as quantity, tt.name,tt.product_id,tt.store_name,ord_date from ( select sum(p.quantity) as quantity, (p.tax*p.quantity)as ttax,o.store_name,p.product_id,p.name,date(p.ORD_DATE)as ord_date from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id GROUP by p.product_id,o.store_id,p.order_id ) as tt where ord_date between ifnull(".$filter_date_start.",ord_date)and ifnull(".$filter_date_end.",ord_date) and product_id=ifnull(".$filter_name_id.",product_id) GROUP by tt.product_id,tt.store_name,ord_date ORDER BY ord_date DESC   ";
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
                	//echo $sql;
		return $query->rows;
   }

    public function getTotalOrders($data = array()) 
   {
       if($data["filter_name_id"]=="")
       {
         $filter_name_id="null";
       }
       else
       {
            $filter_name_id= $data["filter_name_id"];
       }
       if($data["filter_date_start"]=="")
       {
         $filter_date_start="null";
       }
       else
       {
            $filter_date_start="'".$data["filter_date_start"]."'";
       }
       if($data["filter_date_end"]=="")
       {
         $filter_date_end="null";
       }
       else
       {
            $filter_date_end="'".$data["filter_date_end"]."'";
       }

       $sql=" select count(*) as total,sum(quantity) as total_quantity from ( select sum(tt.quantity) as quantity, tt.name,tt.product_id,tt.store_name,ord_date from ( select sum(p.quantity) as quantity, (p.tax*p.quantity)as ttax,o.store_name,p.product_id,p.name,date(p.ORD_DATE)as ord_date from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id GROUP by p.product_id,o.store_id,p.order_id ) as tt where ord_date between ifnull(".$filter_date_start.",ord_date)and ifnull(".$filter_date_end.",ord_date) and product_id=ifnull(".$filter_name_id.",product_id) GROUP by tt.product_id,tt.store_name,ord_date   ";
        

			$sql .= "   ) as aa";
		
                
		$query = $this->db->query($sql);
                //echo $sql;
		return $query->row;
   }
    public function getproductreconciliation($data = array()) 
   {
       
	   $sql="SELECT a.product_id, a.store_id, 
SUM(CASE WHEN Transaction_Type = 'Material Received' AND Current_status = 'Recived' THEN a.quantity END) AS po_received_qnty,
SUM(CASE WHEN Transaction_Type = 'stock recived' AND Current_status = 'Recived' THEN a.quantity END) AS stock_received_qnty, 
SUM(CASE WHEN Transaction_Type = 'stock recived' AND Current_status = 'pending' THEN a.quantity END) AS stock_transit, 
SUM(CASE WHEN Transaction_Type = 'Material Received' AND Current_status = 'pending' THEN a.quantity END) AS po_transit ,
 SUM(op.quantity)   as saleqnty
FROM ( 
    SELECT ocs.name AS store_name, podr.order_id,
 pop.store_id, pop.product_id, pop.name AS product_name, 
 pop.quantity, pop.received_products, poo.order_date, 
 poo.order_sup_send AS recive_date, 'Material Received' AS Transaction_Type, 
 bb.store_transfer,
 (CASE WHEN poo.receive_bit = 1 THEN 'Recived' WHEN poo.receive_bit = 0 THEN 'pending' END) AS Current_status , '0' as saleqnty
FROM oc_po_receive_details AS podr 
LEFT JOIN oc_po_order AS poo ON poo.id = podr.order_id 
LEFT JOIN oc_store AS ocs1 ON ocs1.store_id = podr.supplier_id 
LEFT JOIN oc_po_product AS pop ON pop.order_id = podr.order_id 
LEFT JOIN oc_store AS ocs ON ocs.store_id = pop.store_id 
LEFT JOIN (SELECT product_id, store_id 
FROM (SELECT p.product_id, p2s.product_id AS pid, p2s.store_id, p.model, 
SUM(p2s.quantity) AS qnty FROM oc_product p LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id) 
WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.quantity > 0 
AND p2s.store_id = IFNULL(8, p2s.store_id) 
GROUP BY p.product_id , p2s.store_id 
ORDER BY p.sort_order ASC) AS a) AS b ON b.product_id = podr.product_id 
AND b.store_id = podr.store_id 
LEFT JOIN (SELECT order_id, store_id, product_id, 
MAX(CASE WHEN val = 1 OR val = 2 AND keyy = 'config_storetype' THEN store_name WHEN val = 3 OR val = 4 AND keyy = 'config_storetype' THEN delar_name ELSE delar_name END) AS store_transfer
 FROM (SELECT podr.order_id, a.keyy, a.val, a.store_id, podr.product_id, 
 CONCAT(ops.first_name, ' ', ops.last_name) AS delar_name, os.name AS store_name 
 FROM oc_po_receive_details AS podr 
 LEFT JOIN (SELECT store_id, `key` AS keyy, `value` AS val FROM oc_setting 
 WHERE `key` = 'config_storetype') AS a 
 ON a.store_id = podr.supplier_id 
 LEFT JOIN oc_po_supplier AS ops ON ops.id = podr.supplier_id 
 LEFT JOIN oc_store AS os ON os.store_id = a.store_id 
 LEFT JOIN oc_po_order AS poo ON podr.order_id = poo.id 
 LEFT JOIN oc_po_product AS pop ON pop.product_id = podr.product_id 
 WHERE pop.store_id = IFNULL(8, pop.store_id) 
 AND pop.product_id = IFNULL(NULL, pop.product_id) 
 AND poo.order_sup_send BETWEEN IFNULL('2017-05-01', poo.order_sup_send) 
 AND IFNULL('2018-05-23', poo.order_sup_send) 
 GROUP BY podr.order_id , a.store_id , podr.product_id) AS a 
 GROUP BY order_id) AS bb ON bb.order_id = podr.order_id 
 WHERE poo.order_sup_send BETWEEN '2017-05-01' AND '2018-05-23' 
 AND pop.store_id = IFNULL(8, pop.store_id) 
 AND pop.product_id = IFNULL(NULL, pop.product_id) GROUP BY product_id , order_id , store_id         
 UNION ALL 
 SELECT os.name AS store_name, osp.order_id, osrd.store_id, osrd.product_id, osp.name AS product_name, osp.quantity, osp.received_products, oso.order_date, oso.order_sup_send AS recive_date, 'stock recived' AS Transaction_Type, os1.name AS store_Transfer, (CASE WHEN oso.receive_bit = 1 THEN 'Recived' WHEN oso.receive_bit = 0 THEN 'pending' END) AS Current_status , '0' as saleqnty
 FROM oc_stock_receive_details AS osrd 
 LEFT JOIN oc_stock_product AS osp ON osp.id = osrd.id 
 LEFT JOIN oc_stock_order AS oso ON oso.id = osrd.order_id 
 LEFT JOIN oc_store AS os ON os.store_id = osrd.store_id 
 LEFT JOIN oc_store AS os1 ON os1.store_id = osrd.supplier_id 
 LEFT JOIN (SELECT product_id, store_id FROM 
 (SELECT p.product_id, p2s.product_id AS pid, p2s.store_id, p.model, 
 SUM(p2s.quantity) AS qnty FROM oc_product p 
 LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id) 
 WHERE p.status = '1' AND p.date_available <= NOW() 
 AND p2s.quantity > 0 AND p2s.store_id = IFNULL(8, p2s.store_id) 
 GROUP BY p.product_id , p2s.store_id 
 ORDER BY p.sort_order ASC) AS a) AS b 
 ON b.product_id = osrd.product_id AND b.store_id = osrd.store_id 
 WHERE osrd.store_id = IFNULL(8, osrd.store_id) 
 AND osp.product_id = IFNULL(NULL, osp.product_id) 
 AND oso.order_date BETWEEN IFNULL('2017-05-01', oso.order_date) 
 AND IFNULL('2018-05-23', oso.order_date) 
 GROUP BY osp.order_id , osrd.store_id , osrd.product_id)  a 
 left join oc_order_product op on a.product_id=op.product_id
left join oc_order ord on op.order_id=ord.order_id where ord.order_status_id='5' 
and op.product_id='339' and ord.store_id='8'
GROUP BY a.store_id , a.product_id ";
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
        //echo $sql;  
		//print_r($query->rows);
		return $query->rows;
		/*
		,
    (SELECT 
            SUM(quantity)
        FROM
            oc_order_product
        WHERE
            order_id IN (SELECT 
                    order_id
                FROM
                    oc_order
                WHERE
                    order_status_id = '5'
                        AND store_id = a.store_id
                        AND DATE(date_added) BETWEEN '2017-05-01' AND '2018-05-23')
                AND product_id = a.product_id) AS 'sale_qunty'
		*/
   }

    public function getTotalproductreconciliation($data = array()) 
   {
       
       //$sql=" select count(*) as total,sum(quantity) as total_quantity from ( select sum(tt.quantity) as quantity, tt.name,tt.product_id,tt.store_name,ord_date from ( select sum(p.quantity) as quantity, (p.tax*p.quantity)as ttax,o.store_name,p.product_id,p.name,date(p.ORD_DATE)as ord_date from oc_order_product p left JOIN oc_order o on o.order_id=p.order_id GROUP by p.product_id,o.store_id,p.order_id ) as tt where ord_date between ifnull(".$filter_date_start.",ord_date)and ifnull(".$filter_date_end.",ord_date) and product_id=ifnull(".$filter_name_id.",product_id) GROUP by tt.product_id,tt.store_name,ord_date   ";
        //$query = $this->db->query($sql);
                //echo $sql;
		//return $query->row;
   }
}
