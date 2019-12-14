<html lang="en">
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
    
<div id="content" style="width: 94%;padding-left: 3%;">
  
  <div class="container-fluid">
    
    <div class="panel panel-default">
      
	  <div class="panel-body">
		
		<div class="row">
            <div class="col-sm-12">
               <div class="table-responsive">
                  <table class="table table-bordered">
                    <tr>
                        <td class="col-sm-12" colspan="2"><strong>From:</strong><br />
                           <strong>Akshamaala Solutions Pvt. Ltd</strong><br />
                            F-35, 1<sup>st</sup> Floor, Sector-8, Noida<br />
                            Uttar Pradesh 201301<br />
                            Ph: 120-4040160<br />
                            <u>accounts@unnati.world</u>, <u>www.unnati.world</u> <br />
                            GSTN : 09AAICA9806D1ZM <br />
                            PAN No: AAICA9806D<br />
                            CIN No: U72200DL2010PTC209266
                         </td>
                     </tr>
                      <tr>
                          <td colspan="2">
                              <h3>Credit Note</h3>
                          </td>
                      </tr>
                      <tr>
                          <td class="col-sm-12" colspan="2">
                              <strong>Credit to:</strong><br />
                              <?php //echo $store_info;
                              $store_info1=explode('---',$store_info); 
                              echo $store_info1[0];
                              ?>
                              
                              :<br />
                              <?php
                              echo $store_info1[1];
                              ?>
                              <br />
                              Ph: <?php
                              echo $store_info1[2];
                              ?>
                              <br />
                              Email : <?php
                              echo $store_info1[3];
                              ?>
                          </td>
                     </tr>
                   </table>
                   <table class="table table-bordered top_mar-21">
                      <tr>
                          <th class="col-sm-1">No.</th>
                          <th class="col-sm-2" colspan="2">Acivity</th>
                          <th class="col-sm-1">QTY</th>
                          <th class="col-sm-2">Rate</th>
                          <th class="col-sm-2">Type</th>
                          <th class="col-sm-2">Amount</th>
                      </tr>
                      <?php $a=1;
                      foreach($results as $result){ //print_r($result);?>
                      <tr>
                          <td><?php echo $a; ?></td>
                          <td colspan="2"><?php echo $result['product_name2']; ?></td>
                          <td><?php echo $result['return_quantity']; ?></td>
                          <td><?php echo $result['product_price']; ?></td>
                          <td><?php echo $result['product_tax_type']; ?></td>
                          <td><?php echo $sub_total=$result['total_price_w_o_tax']; ?></td>
                          <?php $total_tax=$result['total_tax']; ?>
                          <?php $total_price_with_tax=$result['total_price_with_tax']; ?>
                      </tr>
                      <?php $a++; } ?>
                      <tr>
                        <td colspan="3">
                            <small>Being this credit note entry against sale return</small>
                        </td> 
                        <td colspan="2" class="text-left">
                            Sub total<br />
                            <?php 
                            if($result['product_tax_type']=="GST@5%")
                            {
                                echo 'CGST @ 2.5% on '.$sub_total."<br/>";
                                echo 'SGST @ 2.5% on '.$sub_total."<br/>";
                            }
                            if($result['product_tax_type']=="GST@12%")
                            {
                                echo 'CGST @ 6% on '.$sub_total."<br/>";
                                echo 'SGST @ 6% on '.$sub_total."<br/>";
                            }
                            if($result['product_tax_type']=="GST@18%")
                            {
                                echo 'CGST @ 9% on '.$sub_total."<br/>";
                                echo 'SGST @ 9% on '.$sub_total."<br/>";
                            }
		if($result['product_tax_type']=="GST@28%")
                            {
                                echo 'CGST @ 14% on '.$sub_total."<br/>";
                                echo 'SGST @ 14% on '.$sub_total."<br/>";
                            }
                                ?>
                            
                            Total Credit
                        </td> 
                        <td colspan="2" class="text-right">
                            <?php echo $sub_total; ?><br/>
                            <?php echo $total_tax/2; ?><br/>
                            <?php echo $total_tax/2; ?><br/>
                            <strong><?php echo $total_price_with_tax; ?></strong>
                        </td>
                      </tr>
                  </table>
                </div>
            </div>
        </div>
                
	  </div>
  </div>
</div>
</div>
</html>