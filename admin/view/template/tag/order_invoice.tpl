<head>
 <link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
</head>
<style>
 table{ margin-left: 20px; }
</style>
  <?php foreach ($orders as $order) {
    
      ?>
      <table style="width: 94%;">
        <tr>
            <td style="width: 50%;"><h1><?php  echo $text_invoice; ?> #<?php echo $invoice_no; ?></h1></td> 
            <td style="width: 50%;text-align: right;">
                <div style="border: 1px solid silver;width: 120px;float: right;height: 120px;margin-bottom: 5px;margin-top: 5px;">
                    <?php  if($profile_pic!="") { ?>
                       <img style="width: 120px;height: 120px;margin-bottom: 5px;" src="<?php echo HTTPS_CATALOG; ?>/system/upload/<?php echo $profile_pic; ?>" />
                   <?php } ?>
                </div>
                
            </td>
        </tr>
        
    </table>
    <table class="table table-bordered" style="width: 94%;">
      <thead>
        <tr>
          <td colspan="2" style="font-weight: bold;"><?php echo $text_order_detail; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
<td style="width: 50%;">
 <strong>Store Name : </strong> <?php echo $order['store_name']; ?>
</td>
<td style="width: 50%;">
	<b>Father Name : </b> <?php echo $father_name; ?>
  
</td>
</tr>

<tr>
<td style="width: 50%;">
 <b><?php echo $text_order_id; ?></b> <?php echo $order['order_id']; ?>
</td>
<td style="width: 50%;">
  <b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?>
</td>
</tr>

<tr>
<td style="width: 50%;">
 <b>Grower ID : </b> <?php echo $grower_id; ?>
</td>
<td style="width: 50%;">
  <b><?php echo $text_payment_method; ?></b> <?php echo $order['payment_method']; ?>
            
</td>
</tr>

<tr>
<td style="width: 50%;">
 	<b>Farmer Name : </b> <?php echo $farmer_name; ?>
</td>
<td style="width: 50%;">
  <?php if ($order['shipping_method']) { ?>
            <b><?php echo $text_shipping_method; ?></b> <?php echo $order['shipping_method']; ?>
            <?php } ?>
</td>
</tr>

<tr>
<td style="width: 50%;">
  <b>Village Name : </b> <?php echo $village_name; ?>
</td>
<td style="width: 50%;">
  
</td>
</tr>
<!--
<tr>
          <td style="width: 50%;"><address>
            <strong>Store Name : </strong> <?php echo $order['store_name']; ?><br /><br />
            
            </address>
             
            <b><?php echo $text_order_id; ?></b> <?php echo $order['order_id']; ?><br /><br />
            <b>Grower ID : </b> <?php echo $grower_id; ?><br /><br />
            
            <b>Farmer Name : </b> <?php echo $farmer_name; ?><br /><br />
            <b>Village Name : </b> <?php echo $village_name; ?></td>
          
            <td style="width: 50%;">
                <b>Father's Name  : </b> <?php echo $father_name; ?><br /><br />
                <b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?><br /><br />
            
            
            <b><?php echo $text_payment_method; ?></b> <?php echo $order['payment_method']; ?><br /><br />
            <?php if ($order['shipping_method']) { ?>
            <b><?php echo $text_shipping_method; ?></b> <?php echo $order['shipping_method']; ?><br /><br />
            <?php } ?>
            </td>
        </tr>-->
      </tbody>
    </table>
   <br />
<table class="table table-bordered" style="width: 94%;">
      <thead>
        <tr>
          <td colspan="2" style="font-weight: bold;">Product Details (Tagged)</td>
        </tr>
      </thead>
</table>
    <table class="table table-bordered" style="width: 94%;">
      <thead>
        <tr>
          <td><b><?php echo $column_product; ?></b></td>
          <td><b><?php echo $column_model; ?></b></td>
          <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
          <td class="text-right"><b><?php echo $column_price; ?></b></td>
          <td class="text-right"><b><?php echo $column_total; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($order['product'] as $product) { ?>
        <tr>
          <td><?php echo $product['name']; ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?></td>
          <td><?php echo $product['model']; ?></td>
          <td class="text-right"><?php echo $product['quantity']; ?></td>
          <td class="text-right"><?php echo $product['price']; ?></td>
          <td class="text-right"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($order['voucher'] as $voucher) { ?>
        <tr>
          <td><?php echo $voucher['description']; ?></td>
          <td></td>
          <td class="text-right">1</td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($order['total'] as $total) { ?>
        <tr>
          <td class="text-right" colspan="4"><b><?php echo $total['title']; ?></b></td>
          <td class="text-right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

<br />
<table class="table table-bordered" style="width: 94%;">
      <thead>
        <tr>
          <td colspan="2" style="font-weight: bold;">Product Details (Actual Purchase)</td>
        </tr>
      </thead>
</table>
    <table class="table table-bordered" style="width: 94%;">
      <thead>
        <tr>
          <td><b><?php echo $column_product; ?></b></td>
          <td><b><?php echo $column_model; ?></b></td>
          <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
          <td class="text-right"><b><?php echo $column_price; ?></b></td>
          <td class="text-right"><b><?php echo $column_total; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($order['product_2'] as $product_2) { ?>
        <tr>
          <td><?php echo $product_2['name_2']; ?>
            <?php foreach ($product_2['option_2'] as $option_2) { ?>
            <br />
            &nbsp;<small> - <?php echo $option_2['name_2']; ?>: <?php echo $option_2['value_2']; ?></small>
            <?php } ?></td>
          <td><?php echo $product_2['model_2']; ?></td>
          <td class="text-right"><?php echo $product_2['quantity_2']; ?></td>
          <td class="text-right"><?php echo $product_2['price_2']; ?></td>
          <td class="text-right"><?php echo $product_2['total_2']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($order['voucher_2'] as $voucher_2) { ?>
        <tr>
          <td><?php echo $voucher_2['description_2']; ?></td>
          <td></td>
          <td class="text-right">1</td>
          <td class="text-right"><?php echo $voucher_2['amount_2']; ?></td>
          <td class="text-right"><?php echo $voucher_2['amount_2']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($order['total_2'] as $total_2) { ?>
        <tr>
          <td class="text-right" colspan="4"><b><?php echo $total_2['title_2']; ?></b></td>
          <td class="text-right"><?php echo $total_2['text_2']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <br />
    <table class="table table-bordered" style="width: 94%;">
      <thead>
        <tr>
          <td style="width: 30%;"><b>Bank ID Number</b></td>
          <td style="width: 30%;"><b>Identity Type</b></td>
          <td style="width: 30%;"><b>Identity Number</b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><address>
            <?php echo $bank_id_number; ?>
            </address></td>
          <td><address>
                    	<?php echo $identity_type; ?>
            </address></td>
             <td><address>
            <?php echo $identity_number; ?>
            </address></td>
        </tr>
      </tbody>
    </table>
    <!--<br /><br /><br /><br />
    <table class="table " style="width: 94%;">
      
        <tr>
            <td style="width: 60%;border: none;"><b>Signature of the Zonal Office Clerk </b> 
                <font style="font-weight: normal;">........................................</font></td>
           
        
            <td style="width: 40%;text-align: right;border: none;"><b>Thumb Signature </b> 
                <font style="font-weight: normal;">.........................................</font></td>
           
        </tr>
      
      
    </table>-->
    <?php if ($order['comment']) { ?>
    <table class="table table-bordered" style="width: 94%;">
      <thead>
        <tr>
          <td><b><?php echo $column_comment; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $order['comment']; ?></td>
        </tr>
      </tbody>
    </table>
    <?php } ?>
 
  <?php } ?>
 
