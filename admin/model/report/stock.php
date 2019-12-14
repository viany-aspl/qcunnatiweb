<?php
class ModelReportStock extends Model 
{
	public function getNamebyID($user_id) 
	{
		$query = $this->db->query("SELECT concat(firstname,' ',lastname) as name from oc_user WHERE user_id = '" . $user_id . "'");

		return $query->row['name'];
	}
	public function cancel_transit_order($order_id = '',$logged_user) 
	{
		$log=new Log("stock-order-cancel-".date('Y-m-d').".log");
		$log->write('order_id - '.$order_id);
		$log->write('SERVER_IP - '.$_SERVER['SERVER_ADDR']);
		
		$order_data1=$this->getOrdersTransit(array('filter_order_id'=>$order_id,'filter_store'=>'null'));
		//print_r($order_data);exit;
		foreach($order_data1 as $order_data)
		{
		if($order_data['Current_status']=='Pending')
		{
			$this->load->library('trans');
			$trans=new trans($this->registry);
		
			$store_id=$order_data['supplier_id'];
			$product_id=$order_data['product_id'];
			$quantity=$order_data['To_be_Recived'];
			$LoggedUserName=$this->getNamebyID($logged_user);
			
			
			$sql4="update oc_product_to_store set `quantity`=quantity+".$quantity." where `product_id`='".$product_id."' and `store_id`='".$store_id."' ";
			$query = $this->db->query($sql4);
			$log->write($sql4);
			
			$sql5="update oc_stock_product set `quantity`='0'  where `order_id`='".$order_id."' ";
			$query = $this->db->query($sql5);
			$log->write($sql5);
			
			$sql52="update oc_stock_order set receive_date='".date('Y-m-d')."',receive_bit=1,pending_bit=0  where `id`='".$order_id."' ";
			$query = $this->db->query($sql52);
			$log->write($sql52);
			
			$trans->addproducttrans($store_id,$product_id,$quantity,$order_id,'CR','ST-CANCEL','web');   
			
			$sql6="select * from oc_store_cash_trans where store_id='".$store_id."' and `order_id`='".$order_id."' and payment_method='ST' limit 1 ";
			$query6 = $this->db->query($sql6);
			$log->write($sql6);
			
			$order_amount=$query6->row['amount'];
			$order_user_id=$query6->row['user_id'];
			$trans->storecurrentcreditcredit($store_id,$order_amount,'ST-CANCEL'); 
			try
			{ 		
				$trans->addstoretrans($order_amount,$store_id,$order_user_id,'CR',$order_id,'ST-CANCEL',$order_amount,'STOCK TRANSFER ORDER CANCEL BY WEB FROM USER -'.$LoggedUserName);  
                                 
            } 
			catch (Exception $e)
			{
                $log->write($e->getMessage());
            }
		}
		}
	}
	public function getOrders($data = array()) {
	
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
 p.date_available <= NOW() 
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 where osrd.supplier_id = ifnull(".$data["filter_store"].",osrd.supplier_id) ";
 
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
/*
 and oso.order_date between ifnull('".$data["filter_date_start"]."',oso.order_date)and
 ifnull('".$data["filter_date_end"]."',oso.order_date) ";
 
 */
 
 if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id)"; 
 } 
 $sql.=" order by osp.order_id desc ";
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

	public function getTotalOrders($data = array()) {
                $sql="select count(*) as total from (select osrd.store_id,os.name as store_name, osrd.supplier_id,
 round(ifnull(b.price,'00.00'),2)as price,round(ifnull(b.tax,'00.00'),2)as tax,
 round(((ifnull(b.price,'00.00')+ifnull(b.tax,'00.00'))*osrd.quantity),2)as Total,
 os1.name as store_transfer, osrd.order_id,osrd.product_id,osp.name as product_name,
 oso.receive_date,oso.order_sup_send, osrd.quantity, oso.order_date,
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
 p.date_available <= NOW() 
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 where osrd.supplier_id = ifnull(".$data["filter_store"].",osrd.supplier_id) ";
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
		/*
 and oso.order_date between ifnull('".$data["filter_date_start"]."',oso.order_date)and
 ifnull('".$data["filter_date_end"]."',oso.order_date) ";
*/
 if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id)"; 
 } 
 $sql.=" order by osp.order_id desc ";
 $sql.=" ) as bb";
//echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
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
 p.date_available <= NOW() 
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 where osrd.supplier_id = ifnull(".$data["filter_store"].",osrd.supplier_id) ";
if(!empty($data["filter_receiver"]))
 {
 $sql.=" and osrd.store_id = '".$data["filter_receiver"]."' ";
 } 
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
$sql.=" and oso.receive_bit=0 ";
 
 if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id)"; 
 } 
 if((!empty($data["filter_order_id"])) && ($data["filter_order_id"]!='null'))
 {
	$sql.=" and oso.id= ".$data["filter_order_id"]; 
 }
 
 $sql.=" order by osp.order_id desc ";
 
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

	public function getTotalOrdersTransit($data = array()) {
                $sql="select count(*) as total from (select osrd.store_id,os.name as store_name, osrd.supplier_id,
 round(ifnull(b.price,'00.00'),2)as price,round(ifnull(b.tax,'00.00'),2)as tax,
 round(((ifnull(b.price,'00.00')+ifnull(b.tax,'00.00'))*osrd.quantity),2)as Total,
 os1.name as store_transfer, osrd.order_id,osrd.product_id,osp.name as product_name,
 oso.receive_date,oso.order_sup_send, osrd.quantity, oso.order_date,
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
 p.date_available <= NOW() 
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 where osrd.supplier_id = ifnull(".$data["filter_store"].",osrd.supplier_id)  and oso.receive_bit=0 ";
 
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
 
if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id)"; 
 } 
 $sql.=" order by osp.order_id desc ";

 $sql.=" ) as bb";
//echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

///////////////////////////////////
public function getOrdersReceived($data = array()) {
$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
$sql="select * from (

select * from (
 SELECT
        ocs.name AS store_name,
                                   podr.order_id,
            pop.store_id,
             ROUND(IFNULL(b.price, '00.00'), 2) AS price,
             ROUND(IFNULL(b.tax, '00.00'), 2) AS tax,
             ROUND(IFNULL(((b.price + IFNULL((b.tax), '00.00')) *
             podr.quantity), '00.00'), 2) AS Total,
            pop.product_id,
            pop.name AS product_name,
            pop.quantity,
            pop.received_products,
            poo.order_date,
            poo.order_sup_send AS recive_date,
            'Material Received' as Transaction_Type,
                        bb.store_transfer,
            (CASE
                WHEN poo.receive_bit = 1 THEN 'Recived'
                WHEN poo.receive_bit = 0 THEN 'pending'
            END) AS Current_status
            FROM
oc_po_receive_details AS podr
 LEFT JOIN  oc_po_order as poo on poo.id = podr.order_id
  LEFT JOIN oc_store AS ocs1 ON ocs1.store_id = podr.supplier_id
  LEFT JOIN oc_po_product AS pop ON pop.order_id =podr.order_id
    LEFT JOIN oc_store AS ocs ON ocs.store_id = pop.store_id
  LEFT JOIN
  (SELECT
        product_id, store_id, price, tax
    FROM
        (SELECT
        p.product_id,
            p2s.product_id AS pid,
            p2s.store_id,
            p.model,
            SUM(p2s.quantity) AS qnty,
            SUM(CASE
                WHEN p2s.store_price = '0.0000' THEN p.price
                ELSE p2s.store_price
            END) AS price,
            (SUM(p2s.quantity) * (price)) AS total,
            ((SELECT
                    (CASE
                            WHEN type = 'F' THEN rate
                            WHEN type = 'p' THEN (price * (rate / 100))
                            ELSE rate
                        END) AS rate
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id
                WHERE
                    `tax_class_id` = p.tax_class_id)) AS tax
    FROM
        oc_product p
    LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
    WHERE
        p.status = '1'
            AND p.date_available <= NOW()
            
            AND p2s.store_id = IFNULL(".$data["filter_store"].", p2s.store_id)
    GROUP BY p.product_id , p2s.store_id
    ORDER BY p.sort_order ASC) AS a) AS b ON b.product_id = podr.product_id
        AND b.store_id = podr.store_id
  LEFT JOIN
 
 (select order_id,store_id,product_id,
MAX(case when val=1 OR val=2 and keyy='config_storetype' then store_name
when val=3 OR val=4 and keyy='config_storetype' then delar_name else delar_name end)as store_transfer
from (

select podr.order_id,a.keyy,a.val,a.store_id,podr.product_id,
concat(ops.first_name,' ',ops.last_name)as delar_name,os.name as store_name
 FROM oc_po_receive_details AS podr
left join
(select store_id,`key` as keyy,`value` as val from oc_setting
where `key`='config_storetype')as a on a.store_id = podr.supplier_id
left join oc_po_supplier as ops on ops.id = podr.supplier_id
left join oc_store as os on os.store_id = a.store_id
LEFT JOIN oc_po_order AS poo ON podr.order_id = poo.id
LEFT JOIN oc_po_product AS pop ON pop.product_id = podr.product_id
  WHERE
pop.store_id = IFNULL(".$data["filter_store"].", pop.store_id)
AND pop.product_id = IFNULL(".$data["filter_name_id"].", pop.product_id) ";

/*
AND poo.order_sup_send BETWEEN
IFNULL('".$data["filter_date_start"]."', poo.order_sup_send) AND IFNULL('".$data["filter_date_end"]."', poo.order_sup_send)
*/
if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (poo.order_sup_send) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (poo.order_sup_send) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}

$sql.=" GROUP BY podr.order_id,a.store_id,podr.product_id
)as a GROUP BY order_id)as bb ON bb.order_id =  podr.order_id
        
        
 
 where
 
   pop.store_id = IFNULL(".$data["filter_store"].", pop.store_id)
AND pop.product_id = IFNULL(".$data["filter_name_id"].", pop.product_id) ";
if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (poo.order_sup_send) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (poo.order_sup_send) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
        
        
$sql.=" ) as ass

group by product_id,order_id,store_id
 
 union all
 
   SELECT
        os.name AS store_name,
            osp.order_id,
            osrd.store_id,
            ROUND(IFNULL(b.price, '00.00'), 2) AS price,
            ROUND(IFNULL(b.tax, '00.00'), 2) AS tax,
            ROUND(IFNULL(((b.price + IFNULL((b.tax), '00.00')) * osp.quantity), '00.00'), 2) AS Total,
            osrd.product_id,
            osp.name AS product_name,
            osp.quantity,
            osp.received_products,
            oso.order_date,
            oso.order_sup_send AS recive_date,
            'stock recived' AS Transaction_Type,
            os1.name AS store_Transfer,
            (CASE
                WHEN oso.receive_bit = 1 THEN 'Recived'
                WHEN oso.receive_bit = 0 THEN 'pending'
            END) AS Current_status
    FROM
        oc_stock_receive_details AS osrd
    LEFT JOIN oc_stock_product AS osp ON osp.id = osrd.id
    LEFT JOIN oc_stock_order AS oso ON oso.id = osrd.order_id
    LEFT JOIN oc_store AS os ON os.store_id = osrd.store_id
    LEFT JOIN oc_store AS os1 ON os1.store_id = osrd.supplier_id
    LEFT JOIN (SELECT
        product_id, store_id, price, tax
    FROM
        (SELECT
        p.product_id,
            p2s.product_id AS pid,
            p2s.store_id,
            p.model,
            SUM(p2s.quantity) AS qnty,
            SUM(CASE
                WHEN p2s.store_price = '0.0000' THEN p.price
                ELSE p2s.store_price
            END) AS price,
            (SUM(p2s.quantity) * (price)) AS total,
            ((SELECT
                    (CASE
                            WHEN type = 'F' THEN rate
                            WHEN type = 'p' THEN (price * (rate / 100))
                            ELSE rate
                        END) AS rate
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id
                WHERE
                    `tax_class_id` = p.tax_class_id)) AS tax
    FROM
        oc_product p
    LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
    WHERE
        p.status = '1'
            AND p.date_available <= NOW()
            
            AND p2s.store_id = IFNULL(".$data["filter_store"].", p2s.store_id)
    GROUP BY p.product_id , p2s.store_id
    ORDER BY p.sort_order ASC) AS a) AS b ON b.product_id = osrd.product_id
        AND b.store_id = osrd.store_id
    WHERE
        osrd.store_id = IFNULL(".$data["filter_store"].", osrd.store_id)
            AND osp.product_id = IFNULL(".$data["filter_name_id"].", osp.product_id) ";
			
			/*
            AND oso.order_date BETWEEN IFNULL('".$data["filter_date_start"]."', oso.order_date)
            AND IFNULL('".$data["filter_date_end"]."', oso.order_date)
			*/
			if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
        
			
            $sql.=" group by osp.order_id,osrd.store_id,osrd.product_id)as a ";
	if (isset($data['status'])) {
	$sql.="  where `Current_status`='".$data['status']."' ";
	}
	$sql.=" order by recive_date desc ";

	

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

	public function getTotalOrdersReceived($data = array()) {
              
$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
$sql="select count(*) as total from ( 

select * from (
SELECT  podr.order_id,podr.product_id,podr.store_id,
        (CASE
                WHEN poo.receive_bit = 1 THEN 'Recived'
                WHEN poo.receive_bit = 0 THEN 'pending'
            END) AS Current_status

 FROM
oc_po_receive_details AS podr
 LEFT JOIN  oc_po_order as poo on poo.id = podr.order_id
  LEFT JOIN oc_store AS ocs1 ON ocs1.store_id = podr.supplier_id
  LEFT JOIN oc_po_product AS pop ON pop.order_id =podr.order_id
    LEFT JOIN oc_store AS ocs ON ocs.store_id = pop.store_id
  LEFT JOIN
  (SELECT
        product_id, store_id
    FROM
        (SELECT
        p.product_id,
            
            p2s.store_id
            
    FROM
        oc_product p
    LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
    WHERE
        p.status = '1'
            AND p.date_available <= NOW()
           
            AND p2s.store_id = IFNULL(NULL, p2s.store_id)
    GROUP BY p.product_id , p2s.store_id
    ORDER BY p.sort_order ASC) AS a) AS b ON b.product_id = podr.product_id
        AND b.store_id = podr.store_id
  LEFT JOIN
 
 (select order_id,store_id,product_id,
MAX(case when val=1 OR val=2 and keyy='config_storetype' then store_name
when val=3 OR val=4 and keyy='config_storetype' then delar_name else delar_name end)as store_transfer
from (

select podr.order_id,a.keyy,a.val,a.store_id,podr.product_id,
concat(ops.first_name,' ',ops.last_name)as delar_name,os.name as store_name
 FROM oc_po_receive_details AS podr
left join
(select store_id,`key` as keyy,`value` as val from oc_setting
where `key`='config_storetype')as a on a.store_id = podr.supplier_id
left join oc_po_supplier as ops on ops.id = podr.supplier_id
left join oc_store as os on os.store_id = a.store_id
LEFT JOIN oc_po_order AS poo ON podr.order_id = poo.id
LEFT JOIN oc_po_product AS pop ON pop.product_id = podr.product_id
  WHERE
pop.store_id = IFNULL(".$data["filter_store"].", pop.store_id)
AND pop.product_id = IFNULL(".$data["filter_name_id"].", pop.product_id) ";

if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (poo.order_sup_send) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (poo.order_sup_send) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}

$sql.=" GROUP BY podr.order_id,a.store_id,podr.product_id
)as a GROUP BY order_id)as bb ON bb.order_id =  podr.order_id
        
        
 
 where
  
 pop.store_id = IFNULL(".$data["filter_store"].", pop.store_id)
AND pop.product_id = IFNULL(".$data["filter_name_id"].", pop.product_id) ";

 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (poo.order_sup_send) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (poo.order_sup_send) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}

 
$sql.=" ) as aas
group by order_id,product_id,store_id

 union all
 
   SELECT
        
            osp.order_id,osp.product_id,osp.store_id,
        (CASE
                WHEN oso.receive_bit = 1 THEN 'Recived'
                WHEN oso.receive_bit = 0 THEN 'pending'
            END) AS Current_status
    FROM
        oc_stock_receive_details AS osrd
    LEFT JOIN oc_stock_product AS osp ON osp.id = osrd.id
    LEFT JOIN oc_stock_order AS oso ON oso.id = osrd.order_id
    LEFT JOIN oc_store AS os ON os.store_id = osrd.store_id
    LEFT JOIN oc_store AS os1 ON os1.store_id = osrd.supplier_id
    LEFT JOIN (SELECT 
        product_id, store_id
    FROM
        (SELECT
        p.product_id,
            
            p2s.store_id
    FROM
        oc_product p
    LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
    WHERE
        p.status = '1'
            AND p.date_available <= NOW()
           
            AND p2s.store_id = IFNULL(NULL, p2s.store_id)
    GROUP BY p.product_id , p2s.store_id
    ORDER BY p.sort_order ASC) AS a) AS b ON b.product_id = osrd.product_id
        AND b.store_id = osrd.store_id
    WHERE
        osrd.store_id = IFNULL(".$data["filter_store"].", osrd.store_id)
            AND osp.product_id = IFNULL(".$data["filter_name_id"].", osp.product_id) ";
			
            
			if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
            $sql.=" group by osp.order_id,osrd.store_id,osrd.product_id)as a "; 
		if (isset($data['status'])) {
	$sql.="  where `Current_status`='".$data['status']."' ";
	}
		//echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
//////////////////////////////////////////////
	public function getTaxes($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (ot.order_id = o.order_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalTaxes($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getShipping($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalShipping($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	////////////////////////////////////////////
        public function getOrdersTransit_po($data = array()) {
    
    $sql=" SELECT
    osrd.store_id,
    os.name AS store_name,
    osrd.supplier_id,
    ROUND(IFNULL(b.price, '00.00'), 2) AS price,
    ROUND(IFNULL(b.tax, '00.00'), 2) AS tax,
    ROUND(((IFNULL(b.price, '00.00') + IFNULL(b.tax, '00.00')) * osrd.quantity),
            2) AS Total,
    concat(os1.first_name,' ',os1.last_name) AS store_transfer,
    osrd.order_id,
    osrd.product_id,
    osp.name AS product_name,
    oso.receive_date,
    oso.order_sup_send,
    osrd.quantity,
    osp.quantity AS To_be_Recived,
    oso.order_date,
    'Po Transfer' Transaction_Type,
    (CASE
        WHEN oso.receive_bit = 1 THEN 'Recived'
        WHEN oso.receive_bit = 0 THEN 'Pending'
    END) AS Current_status
FROM
    oc_po_receive_details AS osrd
        LEFT JOIN
    oc_po_product AS osp ON osp.order_id = osrd.order_id
        LEFT JOIN
    oc_po_order AS oso ON oso.id = osrd.order_id
        LEFT JOIN
    oc_store AS os ON os.store_id = osrd.store_id
        LEFT JOIN
    oc_po_supplier AS os1 ON os1.id = osrd.supplier_id
        
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
 p.date_available <= NOW() 
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 where osrd.store_id = ifnull(".$data["filter_store"].",osrd.store_id) ";
 
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
 
 if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id) "; 
 } 
 
$sql.=" and (osrd.quantity!=osp.quantity or oso.receive_date='0000-00-00') and oso.receive_bit = 1 order by osrd.order_id desc

  ";
 //$sql.=" order by osp.order_id desc ";
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

    public function getTotalOrdersTransit_po($data = array()) {
                $sql="select count(*) as total from (SELECT
    osrd.store_id,
    os.name AS store_name,
    osrd.supplier_id,
    ROUND(IFNULL(b.price, '00.00'), 2) AS price,
    ROUND(IFNULL(b.tax, '00.00'), 2) AS tax,
    ROUND(((IFNULL(b.price, '00.00') + IFNULL(b.tax, '00.00')) * osrd.quantity),
            2) AS Total,
    concat(os1.first_name,' ',os1.last_name) AS store_transfer,
    osrd.order_id,
    osrd.product_id,
    osp.name AS product_name,
    oso.receive_date,
    oso.order_sup_send,
    osrd.quantity,
    osp.quantity AS To_be_Recived,
    oso.order_date,
    'Po Transfer' Transaction_Type,
    (CASE
        WHEN oso.receive_bit = 1 THEN 'Recived'
        WHEN oso.receive_bit = 0 THEN 'Pending'
    END) AS Current_status
FROM
    oc_po_receive_details AS osrd
        LEFT JOIN
    oc_po_product AS osp ON osp.order_id = osrd.order_id
        LEFT JOIN
    oc_po_order AS oso ON oso.id = osrd.order_id
        LEFT JOIN
    oc_store AS os ON os.store_id = osrd.store_id
        LEFT JOIN
    oc_po_supplier AS os1 ON os1.id = osrd.supplier_id
        
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
 p.date_available <= NOW() 
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 where osrd.store_id = ifnull(".$data["filter_store"].",osrd.store_id) ";
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}

 if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id) "; 
 } 


$sql.=" and (osrd.quantity!=osp.quantity or oso.receive_date='0000-00-00') and oso.receive_bit = 1 order by osrd.order_id desc
 ) as bb";
//echo $sql;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
	

public function getOrders_companywise($data = array()) {
    
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
 p.date_available <= NOW() 
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 where osrd.supplier_id = ifnull(".$data["filter_store"].",osrd.supplier_id) ";

   
   if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id) "; 
 } 
  
 $sql .="and os1.company_id='".$data['filter_company']."' ";
 $sql.=" order by osp.order_id desc ";   
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
             //  echo $sql;
        return $query->rows;
    }

    public function getTotalOrders_companywise($data = array()) {
                $sql="select count(*) as total from (select osrd.store_id,os.name as store_name, osrd.supplier_id,
 round(ifnull(b.price,'00.00'),2)as price,round(ifnull(b.tax,'00.00'),2)as tax,
 round(((ifnull(b.price,'00.00')+ifnull(b.tax,'00.00'))*osrd.quantity),2)as Total,
 os1.name as store_transfer, osrd.order_id,osrd.product_id,osp.name as product_name,
 oso.receive_date,oso.order_sup_send, osrd.quantity, oso.order_date,
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
 p.date_available <= NOW() 
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 and os1.company_id='".$data['filter_company']."'
 where osrd.supplier_id = ifnull(".$data["filter_store"].",osrd.supplier_id) ";
 
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
 
 if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id) "; 
 } 
 
$sql .="and os1.company_id='".$data['filter_company']."' ";

$sql.="  ) as bb";
              
//echo $sql;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
        
        ///////////////////////////////////
public function getOrdersReceived_companywise($data = array()) {
$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
$sql="select * from (
 
 SELECT
        ocs.name AS store_name,
                                   podr.order_id,
            pop.store_id,
             ROUND(IFNULL(b.price, '00.00'), 2) AS price,
             ROUND(IFNULL(b.tax, '00.00'), 2) AS tax,
             ROUND(IFNULL(((b.price + IFNULL((b.tax), '00.00')) *
             podr.quantity), '00.00'), 2) AS Total,
            pop.product_id,
            pop.name AS product_name,
            pop.quantity,
            pop.received_products,
            poo.order_date,
            poo.order_sup_send AS recive_date,
            'Material Received' Transaction_Type,
                        bb.store_transfer,
            (CASE
                WHEN poo.receive_bit = 1 THEN 'Recived'
                WHEN poo.receive_bit = 0 THEN 'pending'
            END) AS Current_status
            FROM
oc_po_receive_details AS podr
 LEFT JOIN  oc_po_order as poo on poo.id = podr.order_id
  LEFT JOIN oc_store AS ocs1 ON ocs1.store_id = podr.supplier_id
  LEFT JOIN oc_po_product AS pop ON pop.order_id =podr.order_id
    LEFT JOIN oc_store AS ocs ON ocs.store_id = pop.store_id
  LEFT JOIN
  (SELECT
        product_id, store_id, price, tax
    FROM
        (SELECT
        p.product_id,
            p2s.product_id AS pid,
            p2s.store_id,
            p.model,
            SUM(p2s.quantity) AS qnty,
            SUM(CASE
                WHEN p2s.store_price = '0.0000' THEN p.price
                ELSE p2s.store_price
            END) AS price,
            (SUM(p2s.quantity) * (price)) AS total,
            ((SELECT
                    (CASE
                            WHEN type = 'F' THEN rate
                            WHEN type = 'p' THEN (price * (rate / 100))
                            ELSE rate
                        END) AS rate
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id
                WHERE
                    `tax_class_id` = p.tax_class_id)) AS tax
    FROM
        oc_product p
    LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
    WHERE
        p.status = '1'
            AND p.date_available <= NOW()
           
            AND p2s.store_id = IFNULL(".$data["filter_store"].", p2s.store_id)
    GROUP BY p.product_id , p2s.store_id
    ORDER BY p.sort_order ASC) AS a) AS b ON b.product_id = podr.product_id
        AND b.store_id = podr.store_id
  LEFT JOIN
 
 (select order_id,store_id,product_id,
MAX(case when val=1 OR val=2 and keyy='config_storetype' then store_name
when val=3 OR val=4 and keyy='config_storetype' then delar_name else delar_name end)as store_transfer
from (

select podr.order_id,a.keyy,a.val,a.store_id,podr.product_id,
concat(ops.first_name,' ',ops.last_name)as delar_name,os.name as store_name
 FROM oc_po_receive_details AS podr
left join
(select store_id,`key` as keyy,`value` as val from oc_setting
where `key`='config_storetype')as a on a.store_id = podr.supplier_id
left join oc_po_supplier as ops on ops.id = podr.supplier_id
left join oc_store as os on os.store_id = a.store_id
LEFT JOIN oc_po_order AS poo ON podr.order_id = poo.id
LEFT JOIN oc_po_product AS pop ON pop.product_id = podr.product_id
  WHERE
pop.store_id = IFNULL(".$data["filter_store"].", pop.store_id)
AND pop.product_id = IFNULL(".$data["filter_name_id"].", pop.product_id) ";

if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (poo.order_sup_send) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (poo.order_sup_send) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}


 
$sql.=" GROUP BY podr.order_id,a.store_id,podr.product_id
)as a GROUP BY order_id)as bb ON bb.order_id =  podr.order_id
        
        
 
 where
  
 pop.store_id = IFNULL(".$data["filter_store"].", pop.store_id)
AND pop.product_id = IFNULL(".$data["filter_name_id"].", pop.product_id)

 and ocs.company_id='".$data['filter_company']."' ";
 
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (poo.order_sup_send) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (poo.order_sup_send) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
 
$sql.="  
	
 GROUP BY  podr.order_id
 
 union all
 
   SELECT
        os.name AS store_name,
            osp.order_id,
            osrd.store_id,
            ROUND(IFNULL(b.price, '00.00'), 2) AS price,
            ROUND(IFNULL(b.tax, '00.00'), 2) AS tax,
            ROUND(IFNULL(((b.price + IFNULL((b.tax), '00.00')) * osp.quantity), '00.00'), 2) AS Total,
            osrd.product_id,
            osp.name AS product_name,
            osp.quantity,
            osp.received_products,
            oso.order_date,
            oso.order_sup_send AS recive_date,
            'stock recived' AS Transaction_Type,
            os1.name AS store_Transfer,
            (CASE
                WHEN oso.receive_bit = 1 THEN 'Recived'
                WHEN oso.receive_bit = 0 THEN 'pending'
            END) AS Current_status
    FROM
        oc_stock_receive_details AS osrd
    LEFT JOIN oc_stock_product AS osp ON osp.id = osrd.id
    LEFT JOIN oc_stock_order AS oso ON oso.id = osrd.order_id
    LEFT JOIN oc_store AS os ON os.store_id = osrd.store_id
    LEFT JOIN oc_store AS os1 ON os1.store_id = osrd.supplier_id
    LEFT JOIN (SELECT
        product_id, store_id, price, tax
    FROM
        (SELECT
        p.product_id,
            p2s.product_id AS pid,
            p2s.store_id,
            p.model,
            SUM(p2s.quantity) AS qnty,
            SUM(CASE
                WHEN p2s.store_price = '0.0000' THEN p.price
                ELSE p2s.store_price
            END) AS price,
            (SUM(p2s.quantity) * (price)) AS total,
            ((SELECT
                    (CASE
                            WHEN type = 'F' THEN rate
                            WHEN type = 'p' THEN (price * (rate / 100))
                            ELSE rate
                        END) AS rate
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id
                WHERE
                    `tax_class_id` = p.tax_class_id)) AS tax
    FROM
        oc_product p
    LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
    WHERE
        p.status = '1'
            AND p.date_available <= NOW()
           
            AND p2s.store_id = IFNULL(".$data["filter_store"].", p2s.store_id)
    GROUP BY p.product_id , p2s.store_id
    ORDER BY p.sort_order ASC) AS a) AS b ON b.product_id = osrd.product_id
        AND b.store_id = osrd.store_id
    WHERE
        osrd.store_id = IFNULL(".$data["filter_store"].", osrd.store_id)
            AND osp.product_id = IFNULL(".$data["filter_name_id"].", osp.product_id) ";
			
			if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
           
            $sql.="    AND os.company_id ='".$data['filter_company']."'
         
            group by osp.order_id,osrd.store_id,osrd.product_id)as a  ";
	if (isset($data['status'])) {
	$sql.="  where `Current_status`='".$data['status']."' ";
	}
	$sql.=" order by recive_date desc ";
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
              //  echo $sql;
        return $query->rows;
    }
       
    public function getTotalOrdersReceived_comapnywise($data = array()) {
          $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));    
$sql="select count(*) as total from ( SELECT  podr.order_id,
        (CASE
                WHEN poo.receive_bit = 1 THEN 'Recived'
                WHEN poo.receive_bit = 0 THEN 'pending'
            END) AS Current_status FROM
oc_po_receive_details AS podr
 LEFT JOIN  oc_po_order as poo on poo.id = podr.order_id
  LEFT JOIN oc_store AS ocs1 ON ocs1.store_id = podr.supplier_id
  LEFT JOIN oc_po_product AS pop ON pop.order_id =podr.order_id
    LEFT JOIN oc_store AS ocs ON ocs.store_id = pop.store_id
  LEFT JOIN
  (SELECT
        product_id, store_id
    FROM
        (SELECT
        p.product_id,
            
            p2s.store_id
            
    FROM
        oc_product p
    LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
    WHERE
        p.status = '1'
            AND p.date_available <= NOW()
           
            AND p2s.store_id = IFNULL(NULL, p2s.store_id)
    GROUP BY p.product_id , p2s.store_id
    ORDER BY p.sort_order ASC) AS a) AS b ON b.product_id = podr.product_id
        AND b.store_id = podr.store_id
  LEFT JOIN
 
 (select order_id,store_id,product_id,
MAX(case when val=1 OR val=2 and keyy='config_storetype' then store_name
when val=3 OR val=4 and keyy='config_storetype' then delar_name else delar_name end)as store_transfer
from (

select podr.order_id,a.keyy,a.val,a.store_id,podr.product_id,
concat(ops.first_name,' ',ops.last_name)as delar_name,os.name as store_name
 FROM oc_po_receive_details AS podr
left join
(select store_id,`key` as keyy,`value` as val from oc_setting
where `key`='config_storetype')as a on a.store_id = podr.supplier_id
left join oc_po_supplier as ops on ops.id = podr.supplier_id
left join oc_store as os on os.store_id = a.store_id
LEFT JOIN oc_po_order AS poo ON podr.order_id = poo.id
LEFT JOIN oc_po_product AS pop ON pop.product_id = podr.product_id
  WHERE
pop.store_id = IFNULL(".$data["filter_store"].", pop.store_id)
AND pop.product_id = IFNULL(".$data["filter_name_id"].", pop.product_id) ";

if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (poo.order_sup_send) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (poo.order_sup_send) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
 
$sql.=" GROUP BY podr.order_id,a.store_id,podr.product_id
)as a GROUP BY order_id)as bb ON bb.order_id =  podr.order_id
        
        
 
 where
  
 pop.store_id = IFNULL(".$data["filter_store"].", pop.store_id)
AND pop.product_id = IFNULL(".$data["filter_name_id"].", pop.product_id)
and ocs.company_id='".$data['filter_company']."' ";
if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (poo.order_sup_send) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (poo.order_sup_send) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}

 $sql.=" GROUP BY  podr.order_id
 
 union all
 
   SELECT
        
            osp.order_id,
        (CASE
                WHEN oso.receive_bit = 1 THEN 'Recived'
                WHEN oso.receive_bit = 0 THEN 'pending'
            END) AS Current_status
    FROM
        oc_stock_receive_details AS osrd
    LEFT JOIN oc_stock_product AS osp ON osp.id = osrd.id
    LEFT JOIN oc_stock_order AS oso ON oso.id = osrd.order_id
    LEFT JOIN oc_store AS os ON os.store_id = osrd.store_id
    LEFT JOIN oc_store AS os1 ON os1.store_id = osrd.supplier_id
    LEFT JOIN (SELECT
        product_id, store_id
    FROM
        (SELECT
        p.product_id,
            
            p2s.store_id
    FROM
        oc_product p
    LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
    WHERE
        p.status = '1'
            AND p.date_available <= NOW()
           
            AND p2s.store_id = IFNULL(NULL, p2s.store_id)
    GROUP BY p.product_id , p2s.store_id
    ORDER BY p.sort_order ASC) AS a) AS b ON b.product_id = osrd.product_id
        AND b.store_id = osrd.store_id
    WHERE
        osrd.store_id = IFNULL(".$data["filter_store"].", osrd.store_id)
            AND osp.product_id = IFNULL(".$data["filter_name_id"].", osp.product_id) ";
			
            if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
            $sql.=" and os.company_id='".$data['filter_company']."'    
            group by osp.order_id,osrd.store_id,osrd.product_id)as a ";
	if (isset($data['status'])) {
	$sql.="  where `Current_status`='".$data['status']."' ";
	}
        //echo $sql;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
//////////////////////////////////////////////
            //////////////////////////////////////////////////
public function getOrdersTransit_companywise($data = array()) {
    
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
	
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
 where osrd.supplier_id = ifnull(".$data["filter_store"].",osrd.supplier_id) and oso.receive_bit=0 ";
 
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
 
 
 if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id) "; 
 } 

 $sql.=" and os1.company_id='".$data['filter_company']."'
 
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
    public function getTotalOrdersTransit_companywise($data = array()) {
		$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
                $sql="select count(*) as total from (select osrd.store_id,os.name as store_name, osrd.supplier_id,
 round(ifnull(b.price,'00.00'),2)as price,round(ifnull(b.tax,'00.00'),2)as tax,
 round(((ifnull(b.price,'00.00')+ifnull(b.tax,'00.00'))*osrd.quantity),2)as Total,
 os1.name as store_transfer, osrd.order_id,osrd.product_id,osp.name as product_name,
 oso.receive_date,oso.order_sup_send, osrd.quantity, oso.order_date,
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
 where osrd.supplier_id = ifnull(".$data["filter_store"].",osrd.supplier_id) and oso.receive_bit=0 ";
  
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
 

if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id) "; 
 } 
$sql.=" and os1.company_id='".$data['filter_company']."'

 ) as bb";
//echo $sql;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
    ////////////////////////////////////////////
  public function getOrdersTransit_po_companywise($data = array()) {
    $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
    $sql=" SELECT
    osrd.store_id,
    os.name AS store_name,
    osrd.supplier_id,
    ROUND(IFNULL(b.price, '00.00'), 2) AS price,
    ROUND(IFNULL(b.tax, '00.00'), 2) AS tax,
    ROUND(((IFNULL(b.price, '00.00') + IFNULL(b.tax, '00.00')) * osrd.quantity),
            2) AS Total,
    concat(os1.first_name,' ',os1.last_name) AS store_transfer,
    osrd.order_id,
    osrd.product_id,
    osp.name AS product_name,
    oso.receive_date,
    oso.order_sup_send,
    osrd.quantity,
    osp.quantity AS To_be_Recived,
    oso.order_date,
    'Po Transfer' Transaction_Type,
    (CASE
        WHEN oso.receive_bit = 1 THEN 'Recived'
        WHEN oso.receive_bit = 0 THEN 'Pending'
    END) AS Current_status
FROM
    oc_po_receive_details AS osrd
        LEFT JOIN
    oc_po_product AS osp ON osp.order_id = osrd.order_id
        LEFT JOIN
    oc_po_order AS oso ON oso.id = osrd.order_id
        LEFT JOIN
    oc_store AS os ON os.store_id = osrd.store_id
    and os.company_id='".$data['filter_company']."'
        LEFT JOIN
    oc_po_supplier AS os1 ON os1.id = osrd.supplier_id
       and os1.company_id='".$data['filter_company']."'
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
 p.date_available <= NOW() 
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 where osrd.store_id = ifnull(".$data["filter_store"].",osrd.store_id) ";
 
 if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
 
 
 if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id) "; 
 } 
$sql.=" and (osrd.quantity!=osp.quantity or oso.receive_date='0000-00-00') and oso.receive_bit = 1 order by osrd.order_id desc

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
             //  echo $sql;
        return $query->rows;
    }

    public function getTotalOrdersTransit_po_companywise($data = array()) {
		$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
                $sql="select count(*) as total from (SELECT
    osrd.store_id,
    os.name AS store_name,
    osrd.supplier_id,
    ROUND(IFNULL(b.price, '00.00'), 2) AS price,
    ROUND(IFNULL(b.tax, '00.00'), 2) AS tax,
    ROUND(((IFNULL(b.price, '00.00') + IFNULL(b.tax, '00.00')) * osrd.quantity),
            2) AS Total,
    concat(os1.first_name,' ',os1.last_name) AS store_transfer,
    osrd.order_id,
    osrd.product_id,
    osp.name AS product_name,
    oso.receive_date,
    oso.order_sup_send,
    osrd.quantity,
    osp.quantity AS To_be_Recived,
    oso.order_date,
    'Po Transfer' Transaction_Type,
    (CASE
        WHEN oso.receive_bit = 1 THEN 'Recived'
        WHEN oso.receive_bit = 0 THEN 'Pending'
    END) AS Current_status
FROM
    oc_po_receive_details AS osrd
        LEFT JOIN
    oc_po_product AS osp ON osp.order_id = osrd.order_id
        LEFT JOIN
    oc_po_order AS oso ON oso.id = osrd.order_id
        LEFT JOIN
    oc_store AS os ON os.store_id = osrd.store_id
      and os.company_id='".$data['filter_company']."'
        LEFT JOIN
    oc_po_supplier AS os1 ON os1.id = osrd.supplier_id
         and os1.company_id='".$data['filter_company']."'
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
 p.date_available <= NOW() 
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY
 p.product_id,p2s.store_id ORDER BY p.sort_order ASC )as a)as b
 on b.product_id = osrd.product_id and b.store_id = osrd.supplier_id
 where osrd.store_id = ifnull(".$data["filter_store"].",osrd.store_id) ";
 
  if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (oso.order_date) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (oso.order_date) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
 
if((!empty($data["filter_name_id"])) && ($data["filter_name_id"]!='null'))
 {
	$sql.=" and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id) "; 
 } 
//and osp.product_id= ifnull(".$data["filter_name_id"].",osp.product_id)
$sql.=" and (osrd.quantity!=osp.quantity or oso.receive_date='0000-00-00') and oso.receive_bit = 1 order by osrd.order_id desc
 ) as bb";
//echo $sql;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
   
}