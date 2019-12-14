<div id="content">
 
  <div class="container-fluid">
    <div class="panel panel-default">
	<br/>
      <h3 class="panel-title" style="text-align:center;">FM Material Delivery ( <?php echo $summaryorders[0]['fmname']; ?> )</h3>
	  <br/>
      <div class="panel-body">
       

          <style>
             td,th{border: 1px solid silver;text-align: center; }
              </style>
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 95%;">
            <thead>
              <tr>
                <th class="text-left">Sl.No.</th>
               
                <th class="text-right">Store Name</th>
              
				<th  class="text-right">Inv no.</th>
                <th class="text-right">Grower ID</th>
                <th class="text-right">Grower Name</th>
                <th class="text-right">Date</th>
                <!--<th class="text-right">Amount</th>-->
				<th class="text-right">Village Name</th>
                <th class="text-right">Tagged Amount</th>
				
				<th class="text-right">OTP</th>
				<th class="text-right">Farmer Signature</th>
                
              </tr>            </thead>
            <tbody>
              <?php if ($orders) { $aa=1; $total=0; ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>

                <td class="text-right"><?php echo $order['store_name']; ?></td>
               
	<td class="text-right"><?php echo $order['inv_no']; ?></td>
                <td class="text-right"><?php echo $order['grower_id']; ?></td>
                <td class="text-right" style="text-align: left;"><?php echo explode('-',$order['grower_name'])[0]; ?></td>
                <td class="text-right"><?php echo $order['date']; ?></td>
				<td class="text-right"><?php echo $order['village_name']; ?></td>
				<td class="text-right"><?php echo round($order['tagged'],2); ?></td>
				<td class="text-right"></td>
				<td class="text-right"></td>
                <!--<td class="text-right"><?php echo $order['total']; ?></td>-->
               
                <td class="text-right"></td>
              </tr>              <?php 
              $total=$total+$order['tagged'];
              $aa++; } ?>

              
              <tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
               <td class="text-right"></td>
			   
                <td class="text-right" style="text-align: right;"></td>
                <td class="text-right"></td>
                <td class="text-right" style="text-align: right;"><b>Total : </b><?php echo number_format((float)$total, 2, '.', ''); ?></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
               
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
		<br/>
		<h3 class="panel-title" style="text-align:center;">FM (Delivery Item Wise Summary)</h3>
		<br/>
        <div class="table-responsive">
	
          <table class="table table-bordered">
            <thead>
	<tr>
                <td class="text-left">Sl.No.</td>
                
				<td class="text-left">Fm Code</td>
                <!---<td class="text-left">Fm Name</td>--->
                <td class="text-left">Product Name</td>
          
                <td class="text-left">Quantity</td>
                <td class="text-left">Total Amount</td>
                <td class="text-left">Total Invoice</td>
                
              </tr>
            </thead>
            <tbody>
              <?php if ($summaryorders) { $aa=1;  $total_sales=0; ?>
              <?php foreach ($summaryorders as $suorder) { ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
               
          <td class="text-left"><?php echo $suorder['fmcode']; ?></td>
              <!---  <td class="text-left"><?php echo $suorder['fmname']; ?></td>-->
	  <td class="text-left"><?php echo $suorder['model']; ?></td>
                <td class="text-left"><?php echo $suorder['qnty']; ?></td>
               <td class="text-right"><?php echo number_format((float)$suorder['ttotal'], 2, '.', ''); ?></td>
                <td class="text-left"><?php echo $suorder['cnt']; ?></td>
              </tr>
              <?php 
              
              $aa++; } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
		<br/>
		<strong >Batch No :&nbsp;<?php echo $batch_no; ?></strong><br/><br/>
		
		<div style="text-align:left;"> FM Signature<div style="text-align:right; margin-top:0px;">CANE Manager Signature</div></div>
			<div style="text-align:left;">------------<div style="text-align:right; margin-top:0px;">------------</div></div>
	

      </div>
    </div>
  </div>
  </div> 
 