<div id="content">
 
  <div class="container-fluid">
    <div class="panel panel-default">
      
      <div class="panel-body">
       
          <style>
             td,th{border: 1px solid silver;text-align: center; }
              </style>
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 95%;">
            <thead>
              <tr>
                <th class="text-left">Sl.No.</th>
                <th class="text-left">Requisition id</th>
                <th class="text-right">Customer (Grower ID-Farmer name-Father/Husband name)</th>
                <th class="text-right">Status</th>
                <th  class="text-right">Mobile Number</th>
                <th  class="text-right">Store name</th>
	        <th  class="text-right">Total</th>
                <th class="text-right">Create Date</th>
                <th class="text-right">Expected Date of delivery</th>
                <th class="text-right">Circle code</th>
                <th class="text-right">Products info</th>
                
              </tr>            </thead>
            <tbody>
              <?php if ($orders) { $aa=1; $total=0; ?>
              <?php foreach ($orders as $order) { //print_r($order["products_info"]); 
                if($product_info_string=="")
                           {
                             $product_info_string.=$product['name']." - (".$product['quantity'].")";
                           }
                           else
                           {
                            
                               $product_info_string.=", ".$product['name']." - (".$product['quantity'].")";
                           }
              ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-left"><?php echo $order['order_id']; ?></td>
                <td class="text-right"><?php echo $order['customer']; ?></td>
                <td class="text-right"><?php echo $order['status']; ?></td>
                <td class="text-right"><?php echo $order['telephone']; ?></td>
	        <td class="text-right"><?php echo $order['store_name']; ?></td>
                <td class="text-right"><?php echo number_format((float)$order['total'], 2, '.', ''); ?></td>
                <td class="text-right" ><?php echo $order['date_added']; ?></td>
                <td class="text-right"><?php echo $order['date_potential']; ?></td>
                <td class="text-right"><?php echo $order['shipping_code']; ?></td>
               
                <td class="text-right"><?php echo $order["products_info"]; ?></td>
              </tr>    
              <?php 
              
              $aa++; } ?>

              
              <tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-right"></td>
                <td class="text-right"></td> 
                <td class="text-right"></td>
                
                <td class="text-right" style="text-align: right;"><b>Total : </b></td>
                
                <td class="text-right" style="text-align: right;"><?php echo number_format((float)$total_amount, 2, '.', ''); ?></td>
                <td class="text-right"></td>
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


      </div>
    </div>
  </div>
  </div> 
 