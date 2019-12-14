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
               <div class="table-responsive">
                   <center> <h4 style="text-align: center;">Tax Invoice</h4></center>
                   <table class="table table-bordered" style="margin-bottom: 0px;">
                       
                    <tr>
                        <td style="width: 50%;" class="col-sm-6"><strong>From:</strong><br />
                           <strong>Akshamaala Solutions Pvt. Ltd</strong><br />
                            F-35, 1<sup>st</sup> Floor, Sector-8, Noida, Uttar Pradesh 201301<br />
                            Ph: +91 120-4040160, <u>accounts@unnati.world</u>, <u>www.unnati.world</u> <br />
                            PAN No: AAICA9806D<br />
                            CIN No: U72200DL2010PTC209266<br />
                            GSTN : 09AAICA9806D1ZM 
                        </td>
                        <td style="width: 50%;" class="col-sm-6">
                            <?php if(!empty($created_po)){ $store_to_data=explode('---',$store_to_data); } ?>
                            <strong>To:</strong> <br />
                            Name : <span id="to_store_name"><?php if(!empty($created_po)){ echo $store_to_data[0];  }  ?></span> <br />
                            Address : <span id="to_store_address"><?php if(!empty($created_po)){ echo $store_to_data[1];  }  ?></span> <br />
                            Phone No : <span id="to_store_phone"><?php if(!empty($created_po)){ echo $store_to_data[2];  }  ?></span><br />
                            Email Id : <span id="to_store_email"><?php if(!empty($created_po)){ echo $store_to_data[3];  }  ?></span> <br />
                            Pan Card : <span id="to_store_pan"><?php if(!empty($created_po)){ echo $store_to_data[4];  }  ?></span> <br />
                            GSTN : <span id="to_store_gst"><?php if(!empty($created_po)){ echo $store_to_data[5];  }  ?></span> <br />
                        </td>
                    </tr>

                      <tr>
                          <td style="width: 50%;" class="col-sm-6">
                              <strong>Ship to:</strong><br />
                              Name : <?php echo $order_information['products'][0]['store_name']; ?><br />
                              Address : <?php echo $order_information['products'][0]['store_address']; ?><br />
                              Phone : <?php echo $order_information['products'][0]['store_phone']; ?><br />
                              Email Id : <?php echo $order_information['products'][0]['store_email']; ?><br />
                              Pan Card : <?php echo $order_information['products'][0]['store_pan']; ?><br />
		  GSTN : <?php echo $order_information['products'][0]['store_gstn']; ?><br />
                          <br/><br/>
                          </td>
                          <td style="width: 50%;" class="col-sm-6">
                              <strong>Warehouse : 
                                  <?php 
                                  foreach($order_information['ware_houses'] as $ware_houses)
                                    {
                                     if($order_information['order_info']['po_ware_house']==$ware_houses['store_id'])
                                     {
                                         echo $ware_houses['name']; 
                                         
                                     }  
                                    } 
                                  ?>
                              </strong><br/>
                              <!--Purchase Order/ Reference ID: <?php echo $order_id; ?><br />-->
                              Invoice Date: <?php echo date('d-m-Y',strtotime($order_information['order_info']['create_date'])); //date('d-m-Y'); ?><br />
                              Invoice Number: <?php if($created_po!=""){ echo $order_information['order_info']['po_invoice_prefix']; ?>/<?php echo $created_po; } ?><br />
                          	  Purchase Order/ Reference ID:  <?php if($created_po!=""){ echo $order_information['order_info']['po_order_id'];  } ?>
                          </td>
                      </tr>
                      </table>
                   <table class="table table-bordered top_mar-21">
                       
                      <tr>
                          <th class="col-sm-1">S.No.</th>
                          <th class="col-sm-2">Product</th>
                          <th class="col-sm-1">HSN</th>
                          <th class="col-sm-2">Rate</th>
                          <th class="col-sm-2">Tax Type</th>
                          <th class="col-sm-2">Quantity</th>
                          <th class="col-sm-2">Amount (Without Tax)</th>
                      </tr>
                       
                     
          
              <input type="hidden" name="order_id" value="<?php echo $order_information['order_info']['id']; ?>" />
              <input type="hidden" name="store_id" value="<?php echo $order_information['products'][0]['store_id']; ?>" />
		  <?php
			$grand_total = 0;$a=1;
                        $p_count=count($order_information['products']);
			foreach($order_information['products'] as $product)
			{ //print_r($product);
		  ?>
            <tr id="tr_<?php echo  $a; ?>">
                    
                <td class="text-left" id="td_sid_<?php echo  $a; ?>"><?php echo  $a;?></td>
                          <td class="text-left" id="td_p_name_<?php echo  $a; ?>">
                              
                              <?php echo  $product['product_name'];?>
                              
                          </td>
			  <td id="td_p_hsn_<?php echo  $a; ?>" class="text-left">
                              <?php echo $product['product_hsn'];?>
                          </td>
			  <td class="text-left" id="td_p_price_<?php echo  $a; ?>">
                              <?php echo round($product['product_price'],PHP_ROUND_HALF_UP);?>
                           </td>
			  
			  <td class="text-left" id="td_p_tax_type_<?php echo  $a; ?>">
			  <?php echo $product['product_tax_type'];?>
                              
			  </td>
                          <!--<td class="text-left" id="td_p_tax_rate_<?php echo  $a; ?>">
			  <?php echo round($product['product_tax_rate'],PHP_ROUND_HALF_UP);?>
                              
			  </td>-->
                          
			  <td class="text-left" id="td_p_qnty_<?php echo  $a; ?>">
			  
                              <?php echo  $product['product_quantity'];?>
			  </td>
			  <td class="text-left" id="td_p_amount_<?php echo  $a; ?>">
			  <?php 
                          echo number_format((float)((round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity']), 2, '.', '');
                          $grand_total=$grand_total+((round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity']);
                          ?>
                              
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
                            <?php
			$grand_total;
                        $p_count=count($order_information['products']);
                        $a=1;
                        $gst_5_array=array();
                        $gst_12_array=array();
                        $gst_18_array=array();
			foreach($order_information['products'] as $product)
			{ //print_r($product);
		   if(trim($product['product_tax_type'])=="GST@5%"){ 
                $cgst="2.5";
                
                $sgst="2.5";
                $gst_5_array[]=array(number_format((float)(((round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity'])*$cgst/100), 2, '.', ''));
                
                 }
                 else if(trim($product['product_tax_type'])=="GST@12%"){ 
                $cgst="6";
                $sgst="6";
                $gst_12_array[]=array(number_format((float)(((round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity'])*$cgst/100), 2, '.', ''));
                 }
                 else if(trim($product['product_tax_type'])=="GST@18%"){ 
                $cgst="9";
                $sgst="9";
                $gst_18_array[]=array(number_format((float)(((round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity'])*$cgst/100), 2, '.', ''));
                 }
                 else{ 
                $cgst="2.5";
                
                $sgst="0";
                //$gst_5_array[]=array(number_format((float)(((round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity'])*$cgst/100), 2, '.', ''));
                
                 }
                  $a++;
                     $grand_total= $grand_total+(((round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity'])*$cgst/100)+(((round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity'])*$sgst/100);  
                 }
                 $total_gst_5=0;$total_gst_12=0;$total_gst_18=0;
                
                //print_r($gst_5_array);
                foreach($gst_5_array as $gst_5)
                {
                   $total_gst_5=$total_gst_5+$gst_5[0]; 
                }
               foreach($gst_12_array as $gst_12)
                {
                   $total_gst_12=$total_gst_12+$gst_12[0]; 
                }
                foreach($gst_18_array as $gst_18)
                {
                   $total_gst_18=$total_gst_18+$gst_18[0]; 
                }
                 ?>
                                <?php echo number_format((float)$grand_total, 2, '.', ''); ?>
                            </strong>
                        </td>  
                      </tr>
                       <tr>
                    
                <?php if(count($gst_5_array)>0){?> 
                        <td colspan="7" style="text-align: right;padding: 10px;">
                            <!--<p>-This will be cumulative sum of Tax added on all products for this category</p>
                            <p>-Only that tax will be displayed that is added included in the invoice</p>
                            --><span class="pull-right">CGST @2.5%  : <?php echo number_format((float)$total_gst_5, 2, '.', ''); ?></span><br />
                            <span class="pull-right">SGST @2.5% : <?php echo number_format((float)$total_gst_5, 2, '.', ''); ?></span>
                        </td> 
                  <?php } ?>
                      </tr>
                       <tr>
                        <?php if(count($gst_12_array)>0){?> 
                        <td colspan="7" style="text-align: right;">
                            <!--<p>-This will be cumulative sum of Tax added on all products for this category</p>
                            <p>-Only that tax will be displayed that is added included in the invoice</p>
                            --><span class="pull-right">CGST @6%  : <?php echo number_format((float)$total_gst_12, 2, '.', ''); ?></span><br />
                            <span class="pull-right">SGST @6% : <?php echo number_format((float)$total_gst_12, 2, '.', ''); ?></span>
                        </td> 
                  <?php } ?>  
                      </tr>
                      <tr>
                        <?php if(count($gst_18_array)>0){?> 
                        <td colspan="7" style="text-align: right;">
                            <!--<p>-This will be cumulative sum of Tax added on all products for this category</p>
                            <p>-Only that tax will be displayed that is added included in the invoice</p>
                            --><span class="pull-right">CGST @9%  : <?php echo number_format((float)$total_gst_18, 2, '.', ''); ?></span><br />
                            <span class="pull-right">SGST @9% : <?php echo number_format((float)$total_gst_18, 2, '.', ''); ?></span>
                        </td> 
                  <?php } ?>  
                      </tr>
                       <tr>
                           <td colspan="3">
                               
                               Truck/Wagon No:<br />
                               Mode of Transport:
                           </td>
                           <td colspan="2">
                               GR/PR No:<br />
                               Transporter:
                           </td>
                           <td colspan="2">
                               
                                No. Packages:
                           </td>
                           
                              <!-- <div class="col-sm-4" style="width: 33%;float: left;">
                                   
                               </div>
                               <div class="col-sm-4" style="width: 33%;float: left;">
                                   
                               </div>
                               <div class="col-sm-4" style="width: 33%;float: left;">
                                  
                               </div>-->
                           
                       </tr>
                       <tr>
                           <td colspan="7">
                               <small>
                                   <h6>General Terms & Conditions : -</h6>
                                   <ol type="1" class="list mar_lft30">
                                       <li>Payment will be made as per sales and payment terms, given in the invoice. In the event of delay, interest @18 % will be charged for the delayed period.</li>
                                       <li>Payment has to be throught RTGS only.</li>
                                       <li>Any complaint about short receipt of material to be lodged in writing within two days of receipt, to the sales person concerned.</li>
                                       <li>After expiry of time no complaint shall be accepted.</li>
                                   </ol>
                               </small>
                           </td>
                       </tr>
                       <tr>
                           <td colspan="6">
                               Certified that the particulars given above are true and the amount indicated represents the price actually charged and there is no additional consideration flowing indirectly form the buyer.
                           <br/><br/>
                           </td>
                           <td style="padding-bottom: 40px;"> Authorised Signatory
                          <br/><br/>
                           </td>
                       </tr>
                  </table>
                </div>
            </div>
        </div>
    </div>


    
</body>
</html>