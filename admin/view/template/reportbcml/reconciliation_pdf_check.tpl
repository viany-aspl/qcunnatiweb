<div id="content">
 
  <div class="container-fluid">
    <div class="panel panel-default">
      
      <div class="panel-body">
       <div class="subject" style="min-height: 160px;">
        <div id="part_1" style="width: 50%;float: left;">
          Subject - Farmer Payment Request
          <br/>
          Period - Start Date : <?php echo $start_date; ?> / End date : <?php echo $end_date; ?>
          <br/>
          
          Factory unit - <?php echo $orders[0]['unit']; ?>
          <br/>
		  Store name - <?php echo $store; ?>
          <br/>
          Prepared by - 
          <br/>
          Checked by - 
          <br/>
          Submitted by - 
          <br/><br/>
          </div>
 <div id="part_1" style="width: 40%;float: right;">
          Name - Akshamaala Solutions Pvt Ltd
<br/>
Address - C-84/A, 1<sup>st</sup> Floor, Sector 8, Noida 201301 
<br/>
PAN - AAICA9806D 
<br/>
TIN - 09965726393
<br/>
GSTIN - 09AAICA9806D1ZM
<br/>
CIN - U72200DL2010PTC209266
<br/>
Letter No. - ASPL-<?php echo $file_aspl; ?>
          <br/><br/>
          </div>
       </div>

          <style>
             td,th{border: 1px solid silver;text-align: center; }
              </style>
			  
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 95%;">
            <thead>
              <tr>
                <th class="text-left">Sl.No.</th>
				
                <!--<th class="text-left">Unit</th>
                <th class="text-right">Store Name</th>-->
             
                <th  class="text-right">Order ID</th>
				<th  class="text-right">Inv no.</th>
				<th class="text-left">FM Code</th>
                <th class="text-right">Grower ID</th>
                <th class="text-right">Grower Detail</th>
			
                <th class="text-right">Date</th>
                <th class="text-right">BCML Tagged</th>
                <th class="text-right">Tagged Amount</th>
				<th class="text-right">Tagged Diff</th>
				<th class="text-right">Subsidy Amount</th>
				<th class="text-right">Total Amount</th>
              </tr>            </thead>
			 
            <tbody>
              <?php if ($orders) { $aa=1; $total=0;$totalbcml_invoice_value=0;$total_difference=0 ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr style="font-size: 10px;">
                <td class="text-left"><?php echo $aa; ?></td>
                <!--<td class="text-left"><?php echo $order['unit']; ?></td>
                <td class="text-right"><?php echo $order['store_name']; ?></td>-->
          
                <td class="text-right"><?php echo $order['order_id']; ?></td>
				<td class="text-right"><?php echo $order['inv_no']; ?></td>
				<td class="text-right"><?php echo $order['fmcode']; ?></td>
                <td class="text-right"><?php echo $order['grower_id']; ?></td>
                <td class="text-right" style="text-align: left;font-size: 8px;"><span style="font-size: 10px;"><?php echo $order['farmer_name']; ?> </span>
		<br/> F : <?php echo $order['father_name']; ?>
		<br/> V : <?php echo $order['o_payment_address_1']; ?></td>
		
                <td class="text-right"><?php echo $order['date']; ?></td>
                <td class="text-right" style="text-align: right;"><?php echo number_format((float)$order['bcml_invoice_value'], 2, '.', ''); ?></td>
               <td class="text-right" style="text-align: right;"><?php echo number_format((float)$order['tagged'], 2, '.', ''); ?></td>
			   <td class="text-right" style="text-align: right;"><?php echo number_format((float)($order['tagged']-$order['bcml_invoice_value']), 2, '.', ''); ?></td>
			   <td class="text-right" style="text-align: right;"><?php echo number_format((float)$order['subsidy'], 2, '.', ''); ?></td>
				<td class="text-right" style="text-align: right;"><?php echo number_format((float)($order['tagged']+$order['subsidy']), 2, '.', ''); ?></td>
              </tr>              <?php 
              $total=$total+$order['tagged'];
			  $total_subsidy=$total_subsidy+$order['subsidy'];
	$total_cash=$total_cash+$order['cash'];
	$total_total=$total_total+$order['total'];
	$totalbcml_invoice_value=$totalbcml_invoice_value+$order['bcml_invoice_value'];
	$total_difference=$total_difference+($order['tagged']-$order['bcml_invoice_value']);
              $aa++; } ?>

              
              <tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-right"></td>
                
                
				<td class="text-right"></td>
                <td class="text-right"></td>
				<td class="text-right"></td>
				<td class="text-right"></td>
                <td class="text-right" style="text-align: right;"><b>Total : </b></td>
                
                <td class="text-right" style="text-align: right;"><?php echo number_format((float)$total, 2, '.', ''); ?></td>
				<td class="text-right" style="text-align: right;"><?php echo number_format((float)$total_difference, 2, '.', ''); ?></td>
                <td class="text-right" style="text-align: right;"><?php echo number_format((float)$total_subsidy, 2, '.', ''); ?></td>
				<td class="text-right" style="text-align: right;"><?php echo number_format((float)($total+$total_subsidy), 2, '.', ''); ?></td>
              </tr>   
              <?php  } else { ?>
              <tr>
                <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>


<div class="table-responsive">
	<br/>
	<h2> Product Summary</h2>
	<br/>
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 95%;">
            <thead>
              <tr style="font-size: 10px;">
                <th class="text-left" style="text-align: left;">Sl.No.</th>
                <th class="text-left" style="text-align: left;">Product Name</th>
                <th class="text-left" style="text-align: left;">Quantity</th>
				
                <th class="text-left" style="text-align: left;">Product Price</th>
                <th  class="text-left" style="text-align: left;">Product Tax</th>
				<th  class="text-left" style="text-align: left;">Subsidy Amount</th>
				<th  class="text-left" style="text-align: left;">Tagged Amount</th>
				<th  class="text-left" style="text-align: left;">Total Product Amount</th>
               
				
              </tr>            </thead>
            <tbody>
              <?php if ($product_results) { $aa2=1; ?>
              <?php foreach ($product_results as $product_result) { //print_r($order); 
	 
	
	?>
              <tr>
                <td class="text-left" style="text-align: left;"><?php echo $aa2; ?></td>
                <td class="text-left" style="text-align: left;"><?php echo $product_result['product_name']; ?></td>
                <td class="text-left" style="text-align: left;"><?php echo $product_result['total_quantity']; ?></td>
				
                <td class="text-left" style="text-align: left;"><?php echo number_format((float)$product_result['product_price'],2,'.',''); ?></td>
                <td class="text-left" style="text-align: left;"><?php echo number_format((float)$product_result['product_tax'],2,'.',''); ?></td>
				<td class="text-left" style="text-align: left;">
            <?php 
echo number_format((float)($product_result['total_SubsidyAmount']),2,'.','');

?></td>
<td class="text-left" style="text-align: left;">
            <?php 
echo number_format((float)(($product_result['total_quantity']*((number_format((float)$product_result['product_price'],2,'.',''))+(number_format((float)$product_result['product_tax'],2,'.',''))))-($product_result['total_SubsidyAmount'])),2,'.','');

?></td>
	<td class="text-left" style="text-align: left;">
            <?php 
echo number_format((float)($product_result['total_quantity']*((number_format((float)$product_result['product_price'],2,'.',''))+(number_format((float)$product_result['product_tax'],2,'.','')))),2,'.','');

?></td>

              
              </tr>
			  <?php 
              $all_product_total=$all_product_total+(number_format((float)($product_result['total_quantity']*((number_format((float)$product_result['product_price'],2,'.',''))+(number_format((float)$product_result['product_tax'],2,'.','')))),2,'.',''));//(($product_result['total_quantity']*$product_result['product_price'])+($product_result['total_quantity']*$product_result['product_tax']));
              $all_product_total_tagged=$all_product_total_tagged+(number_format((float)(($product_result['total_quantity']*((number_format((float)$product_result['product_price'],2,'.',''))+(number_format((float)$product_result['product_tax'],2,'.',''))))-($product_result['total_SubsidyAmount'])),2,'.',''));//(($product_result['total_tagged_quantity']*$product_result['product_price'])+($product_result['total_quantity']*$product_result['product_tax']));
			  $all_product_total_subsidy=$all_product_total_subsidy+$product_result['total_SubsidyAmount'];
			  $aa2++; } ?>

              
              <tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-left"></td>
                		<td class="text-left"></td>
                <td class="text-right" style="text-align: right;"><b>Total : </b></td>
                <td class="text-right" style="text-align: left;" style="text-align: left;"><?php echo number_format((float)$all_product_total_subsidy, 2, '.', ''); ?></td>
				<td class="text-right" style="text-align: left;" style="text-align: left;"><?php echo number_format((float)$all_product_total_tagged, 2, '.', ''); ?></td>
                <td class="text-right" style="text-align: left;" style="text-align: left;"><?php echo number_format((float)$all_product_total, 2, '.', ''); ?></td>
                
				
              </tr>   
              <?php  } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td> 
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>


	<div class="table-responsive">
	<br/>
	<h2>Bill Summary</h2>
	<br/>
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 95%;">
            <thead>
              <tr>
                <th class="text-left" style="text-align: left;">Total Sale</th>
                <th class="text-left" style="text-align: left;">Total Tagged</th>
                <th class="text-left" style="text-align: left;">Total Cash</th>
                <th class="text-left" style="text-align: left;">Total Subsidy</th>
              </tr>            </thead>
            <tbody>
              
              <tr>
              
           
                <td class="text-left" style="text-align: left;"><?php echo number_format((float)$all_product_total,2,'.',''); ?></td>
                <td class="text-left" style="text-align: left;"><?php echo number_format((float)$total,2,'.',''); ?></td>
	<td class="text-left" style="text-align: left;"><?php echo number_format((float)$total_cash,2,'.',''); ?></td>
        <td class="text-left" style="text-align: left;"><?php echo number_format((float)$total_subsidy,2,'.',''); ?></td>     
              </tr>
			  

            </tbody>
          </table>
		  <br/>
		  <strong>Paybale Amount : </strong><?php echo number_format((float)$all_product_total,2,'.',''); ?>
        </div>


      </div>
    </div>
  </div>
  </div> 
 