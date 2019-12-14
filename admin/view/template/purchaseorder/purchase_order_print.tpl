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
                   <center> <h4 style="text-align: center;">Purchase Order</h4></center>
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

                            <strong>Supplier:</strong> <br />
                            Name : <span id="to_store_name"><?php echo  $order_information['order_info']['first_name'].''.$order_information['order_info']['last_name'];  ?></span> <br />
                            Address : <span id="to_store_address"><?php  echo $order_information['order_info']['ADDRESS'];  ?></span> <br />
                            Phone No : <span id="to_store_phone"><?php  echo $order_information['order_info']['telephone'];  ?></span><br />
                            Email Id : <span id="to_store_email"><?php  echo $order_information['order_info']['email'];  ?></span> <br />
                            Pan Card : <span id="to_store_pan"><?php  echo $order_information['order_info']['pan'];  ?></span> <br />
                            GSTN : <span id="to_store_gst"><?php echo $order_information['order_info']['gst'];   ?></span> <br />
                        </td>
                    </tr>

                      <tr>
                          <td style="width: 50%;" class="col-sm-6">
                              <strong>Delivery Address:</strong><br />
                                 <?php $store_to_data=explode('---',$store_to_data);  ?>
                             Name : <span id="to_store_name"><?php echo $store_to_data[0];  ?></span> <br />
                            Address : <span id="to_store_address"><?php  echo $store_to_data[1];  ?></span> <br />
                            Phone No : <span id="to_store_phone"><?php  echo $store_to_data[2];  ?></span><br />
                            Email Id : <span id="to_store_email"><?php  echo $store_to_data[3];  ?></span> <br />
                            Pan Card : <span id="to_store_pan"><?php  echo $store_to_data[4];  ?></span> <br />
                            GSTN : <span id="to_store_gst"><?php echo $store_to_data[5];   ?></span> <br />
		MSMFID : <span id="to_store_gst"><?php echo $store_to_data[6];   ?></span> <br />

                          <br/><br/>
                          </td>
						  <?php
						  
$originalDate=$order_information['order_info']['create_date'];
 $newDate = date("d-m-Y", strtotime($originalDate));

 $vDate=$order_information['order_info']['valid_date'];
 $validDate = date("d-m-Y", strtotime($vDate));

						  ?>
                          <td style="width: 50%;" class="col-sm-6">
                              <strong>PO NO:<?php  echo $order_information['order_info']['id_prefix'].''.$order_information['order_info']['sid'];  ?>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>Date: </strong><?php  echo $newDate;  ?>
                                 
                              <br/><br/>
                              <strong>Contact Person Name:</strong><?php  echo $order_information['order_info']['contact_person_name'];  ?>
                                 
                              </strong><br/><br/>
                              <strong>Contact Person Mobile:</strong><?php  echo $order_information['order_info']['contact_person_mobile'];  ?> 
                                 
                              <br/><br/>
                              <strong>Valid Till Date: <?php  echo $validDate;  ?>
                                 
                              </strong><br/><br/>
                              
                                 
                              
                              
                             
                          </td>
                      </tr>
                      </table>
                   <table class="table table-bordered top_mar-21">
                       
                      <tr>
                         
                          <th class="col-sm-2">Product</th>
                          <th class="col-sm-2">Quantity</th>
                          <th class="col-sm-2">Rate</th>
                          <th class="col-sm-2">Amount</th>                            
                         
                     
                      </tr>
                       
                     
          
              <input type="hidden" name="order_id" value="<?php echo $order_information['order_info']['id']; ?>" />
              <input type="hidden" name="store_id" value="<?php echo $order_information['products'][0]['store_id']; ?>" />
		  <?php
			$grand_total = 0;$a=1;
                //        $p_count=count($order_information['order_info']);
			//foreach($order_information['order_info'] as $product)
			//{ print_r($product);
		  ?>
                    <tr id="tr_<?php echo  $a; ?>">
                    
                      
                        <td class="text-left" id="td_p_name_<?php echo  $a; ?>">
                              
                              <?php  echo $order_information['order_info']['product'];  ?>
                              
                        </td>
			
			  
			  <td class="text-left" id="td_p_tax_type_<?php echo  $a; ?>">
			  <?php  echo $order_information['order_info']['Quantity'];  ?>
                              
			  </td>
                         
                          
			  
			  <td class="text-left" id="td_p_amount_<?php echo  $a; ?>">
			   <?php  echo $order_information['order_info']['rate'];  ?>
			  </td>
                          <td class="text-left" id="td_p_amount_<?php echo  $a; ?>">
			   <?php  echo $order_information['order_info']['amount'];  ?>
			  </td>
                          
			</tr>
		<?php
                       
			//}
		?>
		

                      <tr>
                          <td colspan="4" style="text-align: right;">
                            
                            <strong class="pull-right">Total :   <?php  echo $order_information['order_info']['amount'];  ?>
                            </td>
                     </tr>
                  </table>
                     <table class="table table-bordered top_mar-21">
                          <tr>
                          <td colspan="4" >
                            
                            <strong >Remarks :   <?php  echo $order_information['order_info']['remarks'];  ?>
                            </td>
                     </tr>
	<tr>
                          <td colspan="4" >
                            
                            <strong >Delivery Type :   <?php  echo $order_information['order_info']['delivery_type'];  ?>
                            </td>
                     </tr>

	<tr>
                           <td colspan="4">
                               <small>
                                   <h6>Terms & Conditions : -</h6>
                                   <ol type="1" class="list mar_lft30">
                                       <li>Material shall be delivered in good conditions which is salable to partners.</li>
                                       <li>Supplier shall be responsible for the quality and quantity parameters as per PO.</li>
                                       <li>In case of any dispute the same shall be addressed to ASPL accounts / billing team on real time basis.</li>
                                   </ol>
                               </small>
                           </td>
                       </tr>
                       <tr>
                           <td colspan="4"> 
                               <small>
                                   <strong>Note :</strong> This is a system generated purchase order and does not require any signature.
                               </small>
                           </td>
                           
                       </tr>
                  </table>
                </div>
            </div>
        </div>
    </div>


    
</body>
</html>