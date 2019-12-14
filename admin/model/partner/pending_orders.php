<?php
	class ModelPartnerPendingOrders extends Model {
		public function get_all_pending_orders()
		{
			/*$query = $this->db->query("SELECT
			oc_po_order.order_date
			, oc_po_product.quantity
			, oc_po_order.id
			FROM
				oc_po_order
					INNER JOIN oc_po_product 
						ON (oc_po_order.id = oc_po_product.order_id) WHERE delete_bit = 1 AND pending_bit = 1;");
			
			$pending_orders = $query->rows;
			$total_quantity = 0;
			for($i=0; $i<count($pending_orders); $i++)
			{
				if($pending_orders[$i] != "")
				{
					for($j=0; $j<count($pending_orders); $j++)
					{
						if($pending_orders[$j] != "")
						{
							if($pending_orders[$i]['id'] == $pending_orders[$j]['id'])
							{
								$total_quantity += $pending_orders[$j]['quantity'];
								if($i != $j)
								{
									$pending_orders[$j] = "";
								}
							}
						}
					}
				}
				if($total_quantity != 0)
				{
					$pending_orders[$i]['total_quantity'] = $total_quantity;
					$total_quantity = 0;
				}
			}
			$pending_orders = array_values(array_filter($pending_orders));
			return $pending_orders;

                    SELECT
			`oc_po_supplier`.`first_name`
			, `oc_po_supplier`.`last_name`
			, SUM(`oc_po_product`.`quantity`) as total_quantity
			, `oc_po_order`.`order_date`
			, `oc_po_order`.`id`
			, oc_po_order.`pre_supplier_bit`
			FROM
			`oc_po_product`
			INNER JOIN `oc_po_receive_details` 
				ON (`oc_po_product`.`id` = `oc_po_receive_details`.`product_id`)
			INNER JOIN `oc_po_order` 
				ON (`oc_po_order`.`id` = `oc_po_product`.`order_id`)
			INNER JOIN `oc_po_supplier` 
				ON (`oc_po_supplier`.`id` = `oc_po_receive_details`.`supplier_id`) WHERE oc_po_order.`pending_bit` = 1 AND `oc_po_order`.`delete_bit` = 1 GROUP BY (oc_po_order.`id`) ORDER BY (oc_po_order.id) DESC;



*/
			$query = "select p.order_id, o.order_date,o.pre_supplier_bit, a.total_quantity,b.total_product,
s.first_name,s.last_name
 from oc_po_product as p
left join oc_po_order as o on o.id = p.order_id
left JOIN oc_po_receive_details as dtl on dtl.product_id = p.id
left JOIN oc_po_supplier as s on s.id = dtl.supplier_id
left join
(select p.order_id,sum(p.quantity)as total_quantity from oc_po_product as p
 group by p.order_id)as a on a.order_id = p.order_id
 left join
  (select p.order_id,count(p.order_id)as total_product from oc_po_product as p
 group by p.order_id)as b  on b.order_id = p.order_id
 where
 o.delete_bit=1
 and o.pending_bit=1
group by p.order_id";
			$query = $this->db->query($query);
			
			return $query->rows;
		}
	}
?>