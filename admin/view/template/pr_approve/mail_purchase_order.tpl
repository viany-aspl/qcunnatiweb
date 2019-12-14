<?php //echo $header; ?>
<?php //print_r($order_information); exit;?>
<style type="text/css">
	thead tr td{
		font-weight:bold;
		font-size:10px;
	}
	.table-bordered thead td {
    background: #eeeeee none repeat scroll 0 0 !important;
    padding: 5px !important;
    text-align: center !important;
	border-bottom:2px solid #cccccc !important;
}
.table-responsive tbody td {
    padding: 5px !important;
	line-height: 1.7;
	font-size:11px;
}
.panel-heading {
    background: #eeeeee none repeat scroll 0 0;
}
.page-header{
	text-align:center;
}
table table-bordered tbody tr:nth-child(2n) {
    background-color: #eeeeee !important;
}
#content{
	margin: 10% auto;
	width:95%;
}
.header{
	width: 100%;
}
.logo{
	width: 25%;
	float:left;
	margin-top:20px !important;
}
.company{
	width: 72%;
	float:right;
	margin-top:20px !important;
}
.logo img, .company_info p{
	width:100%;
}
.company h2{
	float:right;
}
.panel-heading{
	display:none;
}
.table-bordered, .table-bordered td {
    border: 1px solid #dddddd;
	border-collapse:collapse;
}
.company_info p{
	width: 100%;
	font-weight: bold;
	margin:0px;
	font-size:11px;
	font-family:Verdana, Geneva, sans-serif;
}
.company_info p span{
	font-size:11px;
	font-weight: normal;
}
.date span{
	float:right;
}
.owner-date{
	width: 100%;
}
.owner{
	float:left;
	width: 50%;
}
.date{
	float:right;
	width: 17%;
	font-weight: normal;
}
.date span{
	float:right;
	font-size:11px;
	font-weight:normal;
}

/*mail type*/
.mail_type{
	width:100%;
}
.mail{
	float:left;
	width:50%;
}
.type{
	float:right;
	width:10%;
	font-weight:bold;
	font-size:11px;
	font-family:Verdana, Geneva, sans-serif;
}
.type span{
	float:right;
	font-size:11px;
	font-weight:normal;
}
/*mail type*/

table tr td{
	font-family:Verdana, Geneva, sans-serif;
	font-size:10px;
	text-align:center;
}
table{
	margin-top:30px;
}
.order-info{
	font-size:11px;
	font-family:Verdana, Geneva, sans-serif;
}
div.order_empty{
	width:100%;
}
.empty_div{
	float:left;
	width:50%;
}
.order-no{
	float:right;
	width:15%;
	font-weight:bold;
	font-size:11px;
}
.order-no span{
	font-family:Verdana, Geneva, sans-serif;
}
.footer{
	width:100%;
}
.address
{
	width:70%;
	float:left;
}
.pageno{
	width:4%;
	float:right;
}
</style>
<div id="content">
<div class="page-header">
    <!--<div class="container-fluid">
      <h1><?php echo "Order(#" . $order_information['order_info']['id'] . ")"; ?></h1>
    </div>-->
  </div>
	<div class="panel panel-default" id = "print_div">
	<!--<div class="panel-heading">
		<h3 class="panel-title">
			<?php echo "Order(#" . $order_information['order_info']['id'] . ")"; ?>
		</h3>
	</div>-->
	<div class="panel-body">
		<!--<div class="order_empty">
			<div class="empty_div" style="display:none;"></div>
			<div class="order-no">
					<p><span><?php echo "Order(#" . $order_information['order_info']['id'] . ")"; ?></span></p>
			</div>
		</div>-->
		<div class="company_info">
		<p>Company Owner: <span><?php echo $company_owner?></span></p>
		<div class="mail_type">
			<div class="mail"><p>Company Email: <span><?php echo $company_email?></span></p></div>
			<div class="type"><?php echo "Order(#" . $order_information['order_info']['id'] . ")"; ?></span></div>
		</div>
		<p>Date: <span><?php echo date('Y-m-d');?></span></p>
		</div>
		<div class="row" class="order-info">
			<div class="col-lg-3">
				<label><b>Ordered By:</b></label>
				<?php echo $order_information['order_info']['firstname'] ." ".$order_information['order_info']['lastname']; ?>
			</div>
		</div>
		
		
		
		<div class="row" class="order-info">
			<div class="col-lg-3">
			<label><b>Purchase order date:</b></label>
			<?php echo $order_information['order_info']['order_date']; ?>
			</div>
			
		</div>
		<?php if($order_information['order_info']['receive_date'] != '0000-00-00'){?>
		<div class="row" class="order-info">
			<div class="col-lg-3">
				<label><b>Purchase order receive date:</b></label>
				<?php echo $order_information['order_info']['receive_date']; ?>
			</div>
		</div>
		<?php } ?>
		<table class="table table-bordered table-responsive" border="1">
          <thead>
            <tr>
              <td class="text-left" style="width: 11.11%;"><b>Product Name</b></td>
              <!--<td class="text-left" style="width: 25%; visibility:hidden;">Attribute Group</td>-->
			  <td class="text-left" style="width: 11.11%;"><b>Option Values</b></td>
			  <td class="text-left" style="width: 11.11%;"><b>Demand</b></td>
			  <td class="text-left" style="width: 11.11%;"><b>Received</b></td>
			  <td class="text-left remaining_quantity" style="width: 11.11%;"><b>Remaining</b></td>
			  <td class="text-left" style="width: 11.11%;"><b>Supplier</b></td>
			  <td class="text-left" style="width: 11.11%;"><b>Supplier Quantity</b></td>
			  <td class="text-left" style="width: 11.11%;"><b>Price</b></td>
			  <td class="text-left" style="width: 11.11%;"><b>Total Price</b></td>
			</tr>
          </thead>
          <tbody>
		  <?php
			$grand_total = 0;
		    $start_loop = 0;
			foreach($order_information['products'] as $product)
			{
		  ?>
            <tr>
              <td class="text-left"><?php echo  $product['name'];?></td>
			  <!--<td class="text-left" style="visibility:hidden;">
			  <?php for($i=0; $i<count($product['attribute_groups']);$i++){
				echo  $product['attribute_groups'][$i] . "<br />";
			  }?>
			  </td>-->
			  <td class="text-left">
			  <?php for($j=$start_loop; $j<($start_loop + count($product['attribute_category']));$j++){
				if($product['attribute_category'][$j] != "optionvalue") echo  $product['attribute_category'][$j] . "<br />";
			  } 
			  $start_loop = $j;
			  ?>
			  </td>
			  <td class="text-left"><?php echo  $product['quantity'];?></td>
			  <td class="text-left"><?php if($product['received_products'] == 0) echo ''; else echo $product['received_products'];?></td>
			  <td class="text-left remaining_quantity"><?php if($product['received_products'] == 0) echo $product['quantity'] - $product['received_products']; else echo $product['quantity'] - $product['received_products'];?></td>
			  <td class="text-left">
			  <?php if(isset($product['supplier_names'])){
				for($i=0; $i<count($product['supplier_names']); $i++)
				{					
			  ?>
			  <?php echo $product['supplier_names'][$i] . "<br />"; ?>
			  <?php }} ?>
			  </td>
			  <td class="text-left">
			  <?php if(isset($product['quantities'])){
				for($i=0; $i<count($product['quantities']); $i++)
				{					
			  ?>
			  <?php if($product['quantities'][$i] == 0) echo ''; else echo $product['quantities'][$i] . "<br />"; ?>
			  <?php }} ?>
			  </td>
			  <td class="text-left">
			  <?php if(isset($product['prices'])){
				for($i=0; $i<count($product['prices']); $i++)
				{					
			  ?>
			  <?php if($product['prices'][$i] == 0) echo ''; else echo $product['prices'][$i] . "<br />"; ?>
			  <?php }} ?>
			  </td>
			  <td class="text-left">
			  <?php if(isset($product['prices'])){
				for($i=0; $i<count($product['prices']); $i++)
				{
					$grand_total += intval($product['prices'][$i]) * intval($product['quantities'][$i]);
			  ?>
			  <?php if(intval($product['prices'][$i]) * intval($product['quantities'][$i]) == 0) echo ''; else echo (intval($product['prices'][$i]) * intval($product['quantities'][$i])) . "<br />"; ?>
			  <?php }} ?>
			  </td>
			</tr>
		<?php
			}
		?>
			<tr>
				<td class="text-right" id="set_colspan" colspan="8"><b>Grand Total:</b></td>
				<td class ="text-left"><?php if($grand_total == 0) echo ''; else echo $grand_total; ?></td>
			</tr>
			<!--<tr>
				<td class="text-right" colspan="4"><b>Ordered By:</b></td>
				<td class="text-left"><?php echo $order_information['order_info']['firstname'] ." ".$order_information['order_info']['lastname']; ?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="4"><b>Purchase order date:</b></td>
				<td class="text-left"><?php echo $order_information['order_info']['order_date']; ?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="4"><b>Purchase order receive date:</b></td>
				<td class="text-left"><?php echo $order_information['order_info']['receive_date']; ?></td>
			</tr>-->
			</tbody>
        </table>
		<!--<div class="row" align="right">
			<div class="col-lg-10">
				<label>Grand Total:</label>
			</div>
			<div class="col-lg-2">
				<?php echo $grand_total; ?>
			</div>
		</div>-->
	</div>
  </div>
</div>
</body>
</html>
<?php //echo $footer; ?>