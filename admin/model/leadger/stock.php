<?php
class ModelLeadgerStock extends Model {
	
	
	public function getOrders($data = array()) {
	
	$sql=" 
select store_name,store_id,'0' as supplier_id,price,tax,
 Total,'0' as order_id, product_id,product_name,quantity,
 'Null' as To_be_Recived,
 order_date,
 recive_date,'Null' as order_sup_send,Transaction_Type,store_transfer,Current_status
 from (

select * from ( select ocs.name as store_name,podr.store_id,
round(ifnull(b.price,'00.00'),2)as price, round(ifnull(b.tax,'00.00'),2)as tax,
 round(ifnull(((b.price+ifnull((b.tax),'00.00'))*podr.quantity),'00.00'),2)as Total,
 podr.product_id, pop.name as product_name, podr.quantity,poo.order_date,
 poo.order_sup_send as recive_date,
 'Material Received' Transaction_Type, 'Akshamaala' as store_transfer
 ,(case when poo.receive_bit=1 then 'Recived'
 when poo.receive_bit=0 then 'pending' end)as Current_status
 from oc_po_receive_details as podr
 left join oc_po_order as poo on podr.order_id = poo.id
 left join oc_store as ocs on ocs.store_id = podr.store_id
 left join oc_store as ocs1 on ocs1.store_id = podr.supplier_id
 left join oc_po_product as pop on pop.product_id = podr.product_id
 left join (select product_id,store_id,price,tax
 from ( SELECT p.product_id,p2s.product_id as pid,p2s.store_id,
 p.model,sum(p2s.quantity) as qnty,
 sum(CASE WHEN p2s.store_price='0.0000' THEN p.price ELSE p2s.store_price END) as price,
 (sum(p2s.quantity) * (price)) as total,
 ( ( SELECT (CASE WHEN type='F' then rate WHEN type='p' then (price *(rate/100))
 else rate end) as rate FROM `oc_tax_rule` as rl
 LEFT JOIN oc_tax_rate on oc_tax_rate.tax_rate_id=rl.tax_rate_id
 WHERE `tax_class_id`=p.tax_class_id ) )as tax FROM oc_product p
 LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
 WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.quantity > 0
 AND p2s.store_id=ifnull(".$data["filter_store"].",p2s.store_id) GROUP BY p.product_id,p2s.store_id
 ORDER BY p.sort_order ASC )as a)as b on b.product_id = podr.product_id and
 b.store_id = podr.store_id where podr.store_id=ifnull(".$data["filter_store"].",podr.store_id)
 and pop.product_id=ifnull(null,pop.product_id) and poo.order_sup_send
 between ifnull('".$data["filter_start_date"]."',poo.order_sup_send)and ifnull('".$data["filter_end_date"]."',poo.order_sup_send)
 group by podr.order_id,podr.product_id,podr.store_id
 
 union all
 
 select os.name as store_name,osrd.store_id, round(ifnull(b.price,'00.00'),2)as price ,
 round(ifnull(b.tax,'00.00'),2)as tax,
 round(ifnull(((b.price+ifnull((b.tax),'00.00'))*osp.quantity),'00.00'),2)as Total,
 osrd.product_id,osp.name as product_name,osp.quantity,
 oso.order_date,oso.order_sup_send as recive_date, 'stock recived' as Transaction_Type,
 os1.name as store_Transfer ,
 (case when oso.receive_bit=1 then 'Recived' when
 oso.receive_bit=0 then 'pending' end)as Current_status
 from oc_stock_receive_details as osrd
 left join oc_stock_product as osp on osp.id = osrd.id
 left join oc_stock_order as oso on oso.id = osrd.order_id
 left join oc_store as os on os.store_id = osrd.store_id
 left join oc_store as os1 on os1.store_id = osrd.supplier_id
 left join (select product_id,store_id,price,tax from
 ( SELECT p.product_id,p2s.product_id as pid,p2s.store_id,p.model,
 sum(p2s.quantity) as qnty, sum(CASE WHEN p2s.store_price='0.0000'
 THEN p.price ELSE p2s.store_price END) as price, (sum(p2s.quantity) * (price)) as total,
 ( ( SELECT (CASE WHEN type='F' then rate WHEN type='p'
 then (price *(rate/100)) else rate end) as rate FROM `oc_tax_rule` as rl
 LEFT JOIN oc_tax_rate on oc_tax_rate.tax_rate_id=rl.tax_rate_id
 WHERE `tax_class_id`=p.tax_class_id ) )as tax FROM oc_product p
 LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
 WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.quantity > 0
 AND p2s.store_id=ifnull(".$data["filter_store"].",p2s.store_id) GROUP BY p.product_id,p2s.store_id
 ORDER BY p.sort_order ASC )as a)as b on b.product_id = osrd.product_id and
 b.store_id = osrd.store_id where osrd.store_id=ifnull(".$data["filter_store"].",osrd.store_id)
 and osp.product_id=ifnull(null,osp.product_id) and oso.order_date between
 ifnull('".$data["filter_start_date"]."',oso.order_date)and ifnull('".$data["filter_end_date"]."',oso.order_date) )as a
 )as b
 
 
 union all 
 
 select os.name as store_name,osrd.store_id, osrd.supplier_id,
 round(ifnull(b.price,'00.00'),2)as price,round(ifnull(b.tax,'00.00'),2)as tax,
 round(((ifnull(b.price,'00.00')+ifnull(b.tax,'00.00'))*osrd.quantity),2)as Total,
 
  osrd.order_id,osrd.product_id,osp.name as product_name,
 osrd.quantity,osp.quantity as To_be_Recived, oso.order_date,
 
 oso.receive_date,oso.order_sup_send ,
 'Stock Transfer' Transaction_Type,os1.name as store_transfer,
 (case when oso.receive_bit=1 then 'Recived' when
 oso.receive_bit=0 then 'Pending' end)as Current_status
 from oc_stock_receive_details as osrd
 left join oc_stock_product as osp on osp.id = osrd.id
 left join oc_stock_order as oso on oso.id = osrd.order_id
 left join oc_store as os on os.store_id = osrd.store_id
 left join oc_store as os1 on os1.store_id = osrd.supplier_id
 left join (select product_id,store_id,price,tax
 from ( SELECT p.product_id,p2s.product_id as pid,p2s.store_id,p.model,
 sum(p2s.quantity) as qnty, sum(CASE WHEN p2s.store_price='0.0000' THEN
 p.price ELSE p2s.store_price END) as price, (sum(p2s.quantity) * (price)) as total,
 ( ( SELECT (CASE WHEN type='F' then
 rate WHEN type='p' then (price *(rate/100)) else rate end) as rate
 FROM `oc_tax_rule` as rl
 LEFT JOIN oc_tax_rate on oc_tax_rate.tax_rate_id=rl.tax_rate_id
 WHERE `tax_class_id`=p.tax_class_id ) )as tax FROM oc_product p
 LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
 WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.quantity > 0
 AND p2s.store_id=ifnull(".$data["filter_store"].",p2s.store_id) GROUP BY p.product_id,p2s.store_id
 ORDER BY p.sort_order ASC )as a)as b on b.product_id = osrd.product_id
 and b.store_id = osrd.supplier_id where osrd.supplier_id = ifnull(".$data["filter_store"].",osrd.supplier_id)
 and oso.order_date between ifnull('".$data["filter_start_date"]."',oso.order_date)and
 ifnull('".$data["filter_end_date"]."',oso.order_date) and osp.product_id= ifnull(null,osp.product_id)
  "; 
 
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
              // echo $sql; 
		return $query->rows;
	}

	//////////////////////////////////////////////////
public function getOrdersTransit($data = array()) {
	
	$sql=" select osrd.store_id,os.name as store_name, osrd.supplier_id,
 round(ifnull(b.price,'00.00'),2)as price,round(ifnull(b.tax,'00.00'),2)as tax,
 round(((ifnull(b.price,'00.00')+ifnull(b.tax,'00.00'))*osrd.quantity),2)as Total,
 os1.name as store_transfer, osrd.order_id,osrd.product_id,osp.name as product_name,
 oso.receive_date,oso.order_sup_send, osrd.quantity ,osp.quantity as To_be_Recived,oso.order_date,
 'Stock Transfer' Transaction_Type,
 (case when oso.receive_bit=1 then 'Recived'
 when oso.receive_bit=0 then 'Pending' end)as Current_status
 from oc_stock_receive_details as osrd
 left join oc_stock_product as osp on osp.id = osrd.id
 left join oc_stock_order as oso on oso.id = osrd.order_id
 left join oc_store as os on os.store_id = osrd.store_id
 left join oc_store as os1 on os1.store_id = osrd.supplier_id
 left join (select product_id,store_id,price,tax
 from ( SELECT p.product_id,p2s.product_id as pid,p2s.store_id,p.model,
 sum(p2s.quantity) as qnty, sum(CASE WHEN p2s.store_price='0.0000' THEN p.price
 ELSE p2s.store_price END) as price, (sum(p2s.quantity) * (price)) as total,
 ( ( SELECT (CASE WHEN type='F' then rate WHEN type='p' then (price *(rate/100))
 else rate end) as rate FROM `oc_tax_rule` as rl
 LEFT JOIN oc_tax_rate on oc_tax_rate.tax_rate_id=rl.tax_rate_id
 WHERE `tax_class_id`=p.tax_class_id ) )as tax FROM oc_product p
 LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
 WHERE p.status = '1' AND 
 p.date_available <= NOW() AND p2s.quantity > 0
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 where osrd.supplier_id = ifnull(".$data["filter_store"].",osrd.supplier_id)
 and oso.order_date between ifnull('".$data["filter_date_start"]."',oso.order_date)and
 ifnull('".$data["filter_date_end"]."',oso.order_date) and oso.receive_bit=0 
and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id)
  "; 
 
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

	
}