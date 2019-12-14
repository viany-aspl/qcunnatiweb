<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <link href="https://unnati.world/shop/admin/view/stylesheet/pos/bootstrap.min.css" rel="stylesheet" />
    
    <style>
        td{
            border-left:2px #000000 solid;
            padding: 10px;
            
        }
        body{
            padding: 30px;
        }
       

*{ margin:0;
    padding:0;
    font-family: 'Rubik', sans-serif;
    font-size:12px;
    color:#484545;
    line-height:25px;
    letter-spacing:0.05em;
}

.top_mar-21 {

}
.mar_lft30 {
margin-left:30px;
}
.list li {
font-size:12px !important;
}


.top_mar20 {
margin-top:20px;
}
    </style>
</head>
<body >

    <div class="container top_mar20" style="width: 89%;">
        <div class="row">
            <div class="col-sm-12">
               <div class="table-responsive"> <table class="table table-bordered top_mar-21">
                   <table class="table table-bordered top_mar-21">   
                      <tr>                          
                          <th class="col-sm-2">Order Id</th>
                          <th class="col-sm-1">Store Name</th>
                          <th class="col-sm-2">Grower Name</th>                          
                          <th class="col-sm-2">Cash</th>
                          <th class="col-sm-2">Tagged</th>
						    <th class="col-sm-2">Subsidy</th>
                      </tr>
					    <tr>
				
						<?php foreach ($order_information as $order) { 	
							 $growername=explode('-',$order['payment_firstname']); 

						?>				
                          <td class="col-sm-2"><?php echo $order['order_id']; ?></td>
                          <td class="col-sm-1"><?php echo $order['store_name']; ?></td>
                          <td class="col-sm-2"><?php echo $growername[0]; ?></td>                          
                          <td class="col-sm-2"><?php echo number_format((float)$order['cash'], 2, '.', ''); ?></td>
                          <td class="col-sm-2"><?php echo number_format((float)$order['tagged'], 2, '.', ''); ?></td>
						    <td class="col-sm-2"><?php echo number_format((float)$order['subsidy'], 2, '.', ''); ?></td>
							<?php } ?>
						</tr>
                   </table>
                   <table class="table table-bordered top_mar-21">
                       
                      <tr>
                          <th class="col-sm-1">S.No.</th>
                          <th class="col-sm-2">Product Name</th>
                          <th class="col-sm-1">Quantity</th>
                          <th class="col-sm-2">Price</th>                          
                          <th class="col-sm-2">Tax</th>
                          <th class="col-sm-2">Amount</th>
						 
                      </tr>
                       
                   
		  <?php
			$grand_total = 0;$a=1;
            $p_count=count($product_information);
			$amount=0;
			foreach($product_information as $product)
			{
			$amount=(($product['price'])+($product['tax'])*($product['quantity']));
			$grand_total=$grand_total+$amount;
			// print_r($product);
		  ?>
            <tr id="tr_<?php echo  $a; ?>">
                    
                  <td class="text-left" id="td_sid_<?php echo  $a; ?>"><?php echo  $a;?></td>
                  <td class="text-left" id="td_p_name_<?php echo  $a; ?>">
                       <?php echo  $product['name'];?>
                  </td>
					<td id="td_p_hsn_<?php echo  $a; ?>" class="text-left">
                       <?php echo $product['quantity'];?>
					</td>
					<td class="text-left" id="td_p_price_<?php echo  $a; ?>">
                      <?php echo round($product['price'],PHP_ROUND_HALF_UP);?>
                  </td>
			  
					<td class="text-left" id="td_p_tax_type_<?php echo  $a; ?>">
						<?php echo round($product['tax'],2);?>                            
					</td>                 
                          
					<td class="text-left" id="td_p_qnty_<?php echo  $a; ?>">			  
                      <?php echo   number_format((float)$amount, 2, '.', ''); ?>
					</td>
			       
                          
			</tr>
		<?php
                        $a++;
			}
		?>
		

                      <tr>
                          <td colspan="7" style="text-align: right;">
                            <strong class="pull-right">Sub Total : 
                            <?php if($grand_total == 0) 
                                { 
                                echo ''; 
                                
                                }
                                else 
                                { 
                                    echo number_format((float)$grand_total, 2, '.', ''); 
                                    
                                } 
                                ?>
                            </strong><br />
                            <strong class="pull-right">Total : 
                          
			
                                <?php echo number_format((float)$grand_total, 2, '.', ''); ?>
                            </strong>
                        </td>  
                      </tr>
                      
                  </table>
                </div>
            </div>
        </div>
    </div>


    
</body>
</html>