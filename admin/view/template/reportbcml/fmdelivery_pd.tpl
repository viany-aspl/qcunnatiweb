<div id="content">
 <script src="https://unnati.world/shop/admin/view/javascript/jquery/jquery-2.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jsbarcode/3.3.20/JsBarcode.all.min.js"></script>
 
  <script type="text/javascript">
              $(document).ready(function () {
              JsBarcode(".barcode").init();
              });
              </script>
              <style type="text/css">
              .barcode
              {
              	float:right;
              }
              </style>
  <div class="container-fluid">
    <div class="panel panel-default">
	<br/>
      <h3 class="panel-title" style="text-align:center;">FM Material Delivery ( <?php echo !empty($summaryorders)?$summaryorders[0]['fmname']:$orders[0]['farmer_name']; ?> )</h3>
	  <br/>
      <div class="panel-body">
       

          <style>
             td,th{border: 1px solid silver;text-align: center; }
              </style>
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 95%;">
            <thead>
			 
              <tr>
               
               <th style="border-right: none;" class="text-left">Sl.No.</th>
                <th style="border-right: none;" class="text-right">Store Name</th>
              
				<th style="border-right: none;"  class="text-right">Inv no.</th>
                <th style="border-right: none;" class="text-right">Grower ID</th>
                <th style="border-right: none;" class="text-right">Grower Name</th>
                <th style="border-right: none;" class="text-right">Invoice Date</th>
                <!--<th class="text-right">Amount</th>-->
				<th style="border-right: none;" class="text-right">Village Name</th>
                <th style="border-right: none;" class="text-right">Tagged Amount</th>
				<th style="border-right: none;" class="text-right">Subsidy Amount</th>
				
				<th class="text-right">Farmer Signature</th>
                
              </tr>            </thead>
            <tbody>
              <?php if ($orders) { $aa=1; $total=0; ?>
              <?php foreach ($orders as $order) { //print_r($order); exit;?>
			  
              <tr>
					<th style="font-weight: bold;border-bottom: none !important;border-top: none;border-right: none;"><?php echo $aa; ?></th>
					<td style="border-bottom: none;border-top: none;border-right: none;"><?php echo $order['store_name']; ?></td>
					<td style="border-bottom: none;border-top: none;border-right: none;">
					<img src='https://unnati.world/shop/admin/barcode.php?inv=<?php echo $order['inv_no']; ?>' />
					</td>
					<td style="border-bottom: none;border-top: none;border-right: none;"><?php echo $order['grower_id']; ?></td>
					<td style="border-bottom: none;border-top: none;border-right: none;"><?php echo explode('-',$order['grower_name'])[0]; ?></td>
					<td style="border-bottom: none;border-top: none;border-right: none;"><?php echo $order['date']; ?></td>
					<td style="border-bottom: none;border-top: none;border-right: none;"><?php echo $order['village_name']; ?></td>
					<td style="border-bottom: none;border-top: none;border-right: none;"><?php echo number_format((float)($order['tagged']), 2, '.', ''); ?></td>
					<td style="border-bottom: none;border-top: none;border-right: none;"><?php echo number_format((float)($order['subsidy']), 2, '.', ''); ?></td>
					
					<td style="border-bottom: none;border-top: none;"></td>
					</tr>
					<tr >
					
					<td colspan="9">
					<table style="width:100%; border:none !important;border-spacing: 0px;" >
					<tr style="font-weight: bold;"> 
										
						<td  colspan="3" style="border-bottom:none;border-top:none;border-left:none; ">Product Name</td>
						<td  colspan="2"  style="border-bottom:none;border-top:none;border-left:none; ">Quantity</td>
						<td  colspan="2" style="border-bottom:none;border-top:none;border-left:none; " >Tax</td>
						<td  colspan="2" style="border-bottom:none;border-top:none;border-left:none; " >Price (without tax)</td>	
						<td  colspan="2" style="border-bottom:none;border-top:none;border-right:none;border-left:none; " >Total Amount</td>	
					</tr>
					<?php $orderstotal=0; if ($order['orderproducts']) { $aaa=1;  $total_sales=0;  ?>
                  <?php foreach ($order['orderproducts'] as $uorder) { ?>
				  <?php $orderstotal=$orderstotal+((($uorder['price']*$uorder['quantity'])+($uorder['tax']*$uorder['quantity'])));?>
					<tr style="border-bottom:0px solid #fff;" > 
						
						<td colspan="3" style="border-bottom:none;border-left:none;"><?php echo $uorder['name']; ?></td>
						<td colspan="2" style="border-bottom:none;border-left:none;"><?php echo $uorder['quantity']; ?></td>
						<td colspan="2" style="border-bottom:none;border-left:none;"><?php echo number_format((float)($uorder['tax']), 2, '.', ''); ?></td>
						<td colspan="2" style="border-bottom:none;border-left:none;"><?php echo number_format((float)($uorder['price']), 2, '.', ''); ?></td>
						<td colspan="2" style="border-bottom:none;border-right:none;border-left:none;"><?php echo number_format((float)((($uorder['price']*$uorder['quantity'])+($uorder['tax']*$uorder['quantity']))), 2, '.', ''); ?></td>
						
					</tr>
					<?php 
              
                    $aaa++; } } ?>

					</table>
					</td>
					</tr>
					<tr > 
											
						<td colspan="8" style="text-align: right; border-bottom:none;border-top:none;  ">Order Total :-</td>
						<td style=" border-bottom:none;border-top:none; border-left:none; "><?php echo number_format((float)$orderstotal, 2, '.', ''); ?></td>
						
						
					</tr>
					<tr >
					
					<td colspan="10"> &nbsp;
					</td>
					</tr>
					
					
					
					</tr>
					
              <?php $aa++; } }?>
            </tbody>
          </table>
        </div>
        <?php if(!empty($summaryorders)){?>      
		<br/>
		<br/>
		<h3 class="panel-title" style="text-align:center;">FM (Delivery Item Wise Summary)</h3>
		<br/>
        <div class="table-responsive">
	
          <table class="table table-bordered" style="border-spacing: 0px;width: 95%;">
            <thead>
	<tr>
                <th class="text-left">Sl.No.</th>
                
				<th class="text-left">Fm Code</th>
              
                <th class="text-left">Product Name</th>
          
                <th class="text-left">Quantity</th>
                <th class="text-left">Total Amount</th>
                <!--<td class="text-left">Total Invoice</td>-->
                
              </tr>
            </thead>
            <tbody>
              <?php if ($summaryorders) { $aa=1;  $total_sales=0;$last_total_amount=0; ?>
              <?php foreach ($summaryorders as $suorder) { ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
               
          <td class="text-left"><?php echo $suorder['fmcode']; ?></td>
         
	  <td class="text-left"><?php echo strtoupper($suorder['model']); ?></td>
                <td class="text-left"><?php echo $suorder['qnty']; ?></td>
               <td class="text-right"><?php echo number_format((float)$suorder['ttotal'], 2, '.', ''); ?></td>
                <!--<td class="text-left"><?php echo $suorder['cnt']; ?></td>-->
              </tr>
              <?php 
              
              $aa++; 
			  $last_total_amount=$last_total_amount+$suorder['ttotal'];
			  } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
			  <tr>
                <td class="text-left" colspan="4">Total</td>
               <td class="text-right"><?php echo number_format((float)$last_total_amount, 2, '.', ''); ?></td>
                
              </tr>
            </tbody>
          </table>
        </div>
		<br/>
		<br/>
		<strong >Batch No :&nbsp;<?php echo $batch_no; ?></strong><br/><br/>
		
		<div style="text-align:left;"> FM Signature<div style="text-align:right; margin-top:0px;">CANE Manager Signature</div></div>
		<div style="text-align:left;">------------<div style="text-align:right; margin-top:0px;">------------</div></div>
                <br/>
		<br/>
		<div style="font-size: 11px;">
                <strong>Instruction</strong>
                <br/>
                <strong>How to download the mobile application?</strong>
                <br/>
                <ul style="list-style: disc">
                    <li>Please use your Andorid phone having data connection.</li>
                    <li>Go to “Play Store” and search the app “BCML Agri-Input Delivery” application.</li>
                    <li>Download the application “BCML Agri-Input Delivery.</li>
                </ul>
               
                <strong>How to acknowledge the farmer delivery of Agri input material? </strong>
                <br/>
                <ul style="list-style: disc">
                    <li>Login using your “user id” and “password”. For user id and password please contact your local IT department.</li>
                    <li>Go to tab “Pending Delivery”, Scan the bar code marked against the invoice in this sheet.</li>
                    <li>Farmer will receive the OTP on the registered mobile number. Enter the OTP into the application and save.</li>
                    <li>You can also view your pending deliveries by grower id, invoice and directly update it by using the tab “Pending Deliveries”, “All Deliveries”.</li>
                </ul>
				<div>
	<?php } ?>
      </div>
    </div>
  </div>
  </div> 
 