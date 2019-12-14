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
	<?php if(!empty($store)) { ?>
          Store name - <?php echo $store; ?>
          <br/>
	<?php } ?>
          Factory unit - <?php echo $unit; ?>
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
                <!--<th class="text-left">Unit</th>-->
                <th class="text-right">Store Name</th>
                <th class="text-right">Payment Method</th>
                <th  class="text-right">Order ID</th>
	<th  class="text-right">Inv no.</th>
                <th class="text-right">Grower ID</th>
                <th class="text-right">Grower Details</th>
	<th class="text-right">Material BiIlled</th>
                <th class="text-right">Date</th>
                <th class="text-right">Order Total</th>
                <th class="text-right">Tagged Amount</th>
				<th class="text-right">Cash Amount</th>
				<th class="text-right">Subsidy Amount</th>
                <th class="text-right">Document Check</th>
              </tr>            </thead>
            <tbody style="font-size: 10px !important;">
              <?php if ($orders) { $aa=1; $total=0; ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr >
                <td class="text-left" style="font-size: 12px;text-align: left;"><?php echo $aa; ?></td>
                <!--<td class="text-left" style="font-size: 12px;text-align: left;"><?php echo $order['unit']; ?></td>-->
                <td class="text-right" style="font-size: 12px;text-align: left;"><?php echo $order['store_name']; ?></td>
                <td class="text-right" style="font-size: 12px;text-align: left;"><?php echo $order['payment_method']; ?></td>
                <td class="text-right" style="font-size: 12px;"><?php echo $order['inv_no']; ?></td>
	<td class="text-right" style="font-size: 12px;"><?php echo $order['o_bill_no']; ?></td>
                <td class="text-right" style="font-size: 12px;"><?php echo $order['grower_id']; ?></td>
                <td class="text-right" style="text-align: left;font-size: 8px;width: 90px;">
				<span style="font-size: 10px;"><?php echo $order['farmer_name']; ?> </span>
		<br/> F : <?php echo $order['father_name']; ?>
		<br/> V : <?php echo $order['o_payment_address_1']; ?>
		<br/> CSN : <?php echo $order['o_csn']; ?>
	</td>

	<td class="text-right" style="text-align: left;font-size: 7px;width: 150px;">
		 <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 100%;font-size: 7px;" >
            			<thead style="font-size: 7px !important;">
              			<tr>
                				<th class="text-left" style="font-size: 7px !important;width: 45%;">Material Name</th>
                				<th class="text-left" style="font-size: 7px !important;width: 18%;">Qnty</th>
					<th class="text-left" style="font-size: 7px !important;width: 17%;">Rate</th>
					<th class="text-left" style="font-size: 7px !important;width: 18%;">Amount</th>
				</tr>
			</thead>
		<?php foreach($order['orderproducts'] as $products){ ?>	
		<tr>
			<td class="text-right" style="text-align: left;font-size: 8px;">
				<span style="font-size: 8px;"><?php echo $products['name']; ?>  </span>
			</td>
			<td class="text-right" style="text-align: center;font-size: 8px;">
				<span style="font-size: 8px;"><?php 
							if($products['tagged_quantity']==$products['quantity'])
							{
								echo $products['quantity'];
							}
							else
							{
								echo $products['tagged_quantity'];
							}
						 ?></span>
			</td>
			<td class="text-right" style="text-align: center;font-size: 8px;">
				<span style="font-size: 8px;"><?php echo number_format((float)($products['price']+($products['tax'])),2,'.',''); ?></span>
			</td>
			<td class="text-right" style="text-align: center;font-size: 8px;">
				<span style="font-size: 8px;"><?php echo number_format((float)($products['total']+($products['tax']*$products['quantity'])),2,'.',''); ?></span>
			</td>
		</tr>
		<?php } ?>
		</table>
	</td>

                <td class="text-right" style="font-size: 12px;"><?php echo $order['date']; ?></td>
                <td class="text-right"><?php echo number_format((float)$order['total'], 2, '.', ''); ?></td>
                <td class="text-left" style="font-size: 12px;"><?php echo number_format((float)$order['tagged'], 2, '.', ''); ?></td>
				<td class="text-left" style="font-size: 12px;"><?php echo number_format((float)$order['cash'], 2, '.', ''); ?></td>
				<td class="text-left" style="font-size: 12px;"><?php echo number_format((float)$order['subsidy'], 2, '.', ''); ?></td>
                <td class="text-right"></td>
              </tr>              <?php 
					$total_total=$total_total+$order['total'];
					$total=$total+$order['tagged'];
					$cash_total=$cash_total+$order['cash'];
					$subsidy_total=$subsidy_total+$order['subsidy'];
					
					$aa++; } ?>

              
              <tr>
                <td class="text-left"></td>
                
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
	<td class="text-right"></td>
                <td class="text-right"></td>
                		<td class="text-right"></td>
                <td class="text-right" style="text-align: right;"><b>Total : </b></td>
                
                <td class="text-right" style=""><?php echo number_format((float)$total_total, 2, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format((float)$total, 2, '.', ''); ?></td>
				<td class="text-right"><?php echo number_format((float)$cash_total, 2, '.', ''); ?></td>
				<td class="text-right"><?php echo number_format((float)$subsidy_total, 2, '.', ''); ?></td>
	<td class="text-right"></td>
              </tr>   
              <?php  } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>


      </div>
    </div>
  </div>
  </div> 
 