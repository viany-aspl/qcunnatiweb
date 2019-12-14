<div id="content">
 
  <div class="container-fluid">
    <div class="panel panel-default">
	<br/>
      <h3 class="panel-title" style="text-align:center;">Sub User Order Detail  ( <?php echo $orders[0]['username']; ?> )</h3>
	  <br/>
      <div class="panel-body">
       

          <style>
             td,th{border: 1px solid silver;text-align: center; }
              </style>
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 95%;">
            <thead>
			 
              <tr colspan="13">
               
                
                <th colspan="2" style="border-right: none;" class="text-right">Order Id</th>              
				<th colspan="2" style="border-right: none;"  class="text-right">Store Name</th>               
                <th colspan="2" style="border-right: none;" class="text-right">Grower Name</th>
                <th colspan="2" style="border-right: none;" class="text-right">Cash</th>            
				<th colspan="2" style="border-right: none;" class="text-right">Tagged</th>
                <th colspan="2"  class="text-right">Subsidy</th>
				<th colspan="2"  class="text-right">Date</th>
		
              </tr>  
			  </thead>
            <tbody>
              <?php if ($orders) { $aa=1; $total=0; ?>
              <?php foreach ($orders as $order) { //print_r($order); exit;?>
			  <?php  $growername=explode('-',$order['payment_firstname']);  ?>
              <tr colspan="14">
					
					<td colspan="2"  style="border-bottom: none;border-top: none;border-right: none;"><?php echo $order['order_id']; ?></td>
					<td colspan="2"  style="border-bottom: none;border-top: none;border-right: none;"><?php echo $order['store_name']; ?></td>
					<td colspan="2"  style="border-bottom: none;border-top: none;border-right: none;"><?php echo $growername[0]; ?></td>
					
					<td colspan="2"  style="border-bottom: none;border-top: none;border-right: none;"><?php echo round($order['cash'],2); ?></td>
					<td colspan="2"  style="border-bottom: none;border-top: none;border-right: none;"><?php echo round($order['tagged'],2); ?></td>
					<td colspan="2"  style="border-bottom: none;border-top: none;border-right: none;"><?php echo round($order['subsidy'],2); ?></td>
					<td colspan="2"  style="border-top: none;"><?php echo $order['dat']; ?></td>
					</tr>
					<tr >
					
					<td colspan="14">
					<table style="width:100%; border:none !important;border-spacing: 0px;" >
					<tr style="font-weight: bold;"> 
										
						<td  colspan="3" style="border-bottom:none;border-top:none;border-left:none; ">Product Name</td>
						<td  colspan="3"  style="border-bottom:none;border-top:none;border-left:none; ">Quantity</td>
						<td  colspan="2" style="border-bottom:none;border-top:none;border-left:none; " >Tax</td>
						<td  colspan="3" style="border-bottom:none;border-top:none;border-left:none; " >Price (without tax)</td>	
						<td  colspan="3" style="border-bottom:none;border-top:none;border-right:none;border-left:none; " >Total Amount</td>	
					</tr>
					<?php if ($order['orderproducts']) { $aaa=1;  $total_sales=0; ?>
                  <?php foreach ($order['orderproducts'] as $uorder) { ?>
					<tr style="border-bottom:0px solid #fff;" > 
						
						<td colspan="3" style="border-bottom:none;border-left:none;"><?php echo $uorder['name']; ?></td>
						<td colspan="3" style="border-bottom:none;border-left:none;"><?php echo $uorder['quantity']; ?></td>
						<td colspan="2" style="border-bottom:none;border-left:none;"><?php echo round($uorder['tax'],2); ?></td>
						<td colspan="3" style="border-bottom:none;border-left:none;"><?php echo round($uorder['price'],2); ?></td>
						<td colspan="3" style="border-bottom:none;border-right:none;border-left:none;"><?php echo round((($uorder['price']*$uorder['quantity'])+($uorder['tax']*$uorder['quantity'])),2); ?></td>
						
					</tr>
					<?php 
              
                    $aaa++; } } ?>

					</table>
					</td>
					</tr>
					<tr > 
											
						<td colspan="12" style="text-align: right; border-bottom:none;border-top:none;  ">Order Total :-</td>
						<td style=" border-bottom:none;border-top:none; border-left:none; "><?php echo number_format((float)$order['total'], 2, '.', ''); ?></td>
						
						
					</tr>
					<tr >
					
					<td colspan="14"> &nbsp;
					</td>
					</tr>
					
					
					
					</tr>
					
              <?php $aa++; } }?>
            </tbody>
          </table>
        </div>
        </div>
        </div>
		
 