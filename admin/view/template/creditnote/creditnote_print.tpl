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
			   <center> <h4 style="text-align: center;">Credit Note</h4></center>
			   <br/><br/>
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
                            <?php  $store_to_data=explode('---',$store_to_data);  ?>
                            <strong>To:</strong> <br />
                            Name : <span id="to_store_name"><?php  echo $store_to_data[0];   ?></span> <br />
                            Address : <span id="to_store_address"><?php  echo $store_to_data[1];    ?></span> <br />
                            Phone No : <span id="to_store_phone"><?php  echo $store_to_data[3];    ?></span><br />
                            Email Id : <span id="to_store_email"><?php  echo $store_to_data[2];   ?></span> <br />
                            PAN : <span id="to_store_pan"><?php echo $store_to_data[5];   ?></span> <br />
                            GSTN : <span id="to_store_gstn"><?php  echo $store_to_data[4];   ?></span><br />
                        </td>
                    </tr>
                   </table>
				   <br/><br/>
                    <table class="table table-bordered top_mar20 ">
                      <tr>
                        
                          <td style="width: 50%;" class="col-sm-6">
                              <strong>INVOICE NO :</strong>
                                  <?php 
                                    echo  $credit_information[0]['creditno'];
                                  ?>
                              <br/>
                          </td>
                          <td>
                              <strong>DATE: </strong><?php echo $credit_information[0]['o_date']; ?><br />
                              
                          </td>
                      </tr>
                      </table>
					  <br/><br/>
                   <table class="table table-bordered top_mar-20 ">
                       
                      <tr >
                          <th class="col-sm-2" style="font-size:18px;">S.No.</th>
                          <th class="col-sm-4" style="font-size:18px;">Activity</th>
                          <th class="col-sm-2" style="font-size:18px;">Qty</th>
                          <th class="col-sm-2" style="font-size:18px;">Rate</th>
                          <th class="col-sm-2" style="font-size:18px;">Amount</th>
                      </tr>
                <?php
                  $p_count=count($credit_information);
                        
                        //print_r($credit_information[0]['activity']); exit;
                        $a='1';
                        for($i=0;$i<$p_count;$i++)
                        {
                            $activity=$credit_information[$i]['activity'];
                            $qty=$credit_information[$i]['qty'];
                            $rate=$credit_information[$i]['rate'];
                            $amount=$credit_information[$i]['amount'];
                       
			
		?>
            <tr id="tr_<?php echo  $i; ?>">
                    
                          <td class="text-left" id="td_sid_<?php echo  $i; ?>"><?php echo  $a;?></td>
                          <td class="text-left" id="td_p_name_<?php echo  $i; ?>">
                              
                              <?php echo  $activity;?>
                              
                          </td>
			  <td id="td_p_hsn_<?php echo  $i; ?>" class="text-left">
                              <?php echo $qty;?>
                          </td>
			  <td class="text-left" id="td_p_price_<?php echo  $i; ?>">
                              <?php echo  $rate;?>
                           </td>
			  
			  <td class="text-left" id="td_p_tax_type_<?php echo  $i; ?>">
			  <?php echo $amount;?>
                              
			  </td>
                         
                          
	    </tr>
            
		<?php
                     $a++;   }
                ?>
                   </table>
				   <br/>
                      <table class="table table-bordered top_mar-20" style="border:0px solid white !important;">
                <tr>
                           .
                           
                           <td colspan="4" style="text-align: right;">
                               
                               <strong> <?php 
                            echo "TOTAL CREDIT :    Rs  ".$credit_information[0]['total_amount'].".00";
                            ?></strong>
                           </td>
                           
                           
                       </tr>
                       
                       
                  </table>
				  <br/><br/>
				  <div>  
<br/>				    <table class="table table-bordered top_mar-20" style="border:0px solid white !important;">
                <tr>
                           <td colspan="4" >  <?php 
                            echo $credit_information[0]['remarks'];
                            ?></td>
							    
                       </tr>
                       
                       
                  </table>
				 </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>