<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    
    
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
    font-size:14px;
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
.size16 {
font-size:16px !important;
}

.top_mar20 {
margin-top:20px;
}
strong
{
font-weight: bold;
}
h3
{
font-weight: bold;
}
td,th{border: 1px solid silver;text-align: center; }
    </style>
</head>
<body >
    <div class="container top_mar20" style="width: 96%;">
        
        <div class="row">
            <div class="col-sm-12">
	<div class="panel-body">
       <div class="subject" style="min-height: 160px;">
	   
	   <div style="text-align: center;">
		<?php 
		$store_to_data2=explode('---',$store_to_data); 
		//print_r($store_to_data2);
		?>
		<strong> 
		<?php
		echo $store_to_data2[1]; 
		?>
		</strong> 
		<br/>
		<?php
		echo $store_to_data2[2]; 
		?>
		<br/>
		<?php echo date('d-m-Y',strtotime($filter_date_start));; ?> To  <?php echo date('d-m-Y',strtotime($filter_date_end)); ?>
		 <br/><br/>
	   </div>
        
 <div id="part_1" style="width: 30%;float: right;">
          
          </div>
       </div>
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 100%;">
            <thead>
	<tr>
                <th class="text-right">Date</th>
                
                <th class="text-right">Transaction Type</th>
                <th class="text-right" style="max-width: 100px;">Transaction Number / Invoice Number</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
	  <th class="text-right">Invoice Status</th>
              </tr>              
           </thead>
            <tbody>
              <?php if ($order_list4) { $total3=0; ?>
				<tr>
                <td class="text-right"><?php echo date('d-m-Y',strtotime($filter_date_start)); ?></td>
                
                <td class="text-right"><?php echo 'Opening Balance'; ?></td>
                <td class="text-right"><?php  echo 'NA';  ?></td>
                <td class="text-right"><?php if($opening_balance>0) { echo number_format((float)$opening_balance, 2, '.', ''); $totaldebit=$opening_balance; } else { echo '0.00'; } ?></td>
                <td class="text-right"><?php if($opening_balance<0) { echo number_format((float)$opening_balance, 2, '.', ''); $totalcredit=$opening_balance;}  else { echo '0.00'; } ?></td>
				  <td class="text-right"><?php  ?></td>     
              </tr>
			  
              <?php foreach ($order_list4 as $order) { 
				if(($order['tr_type']=='NEFT') || ($order['tr_type']=='ECMS') || ($order['tr_type']=='RTGS') || ($order['tr_type']=='IMPS'))
				{
					$order['tr_type']='Payment';
					
				}
				if($order['tr_type']=='CREDIT NOTE')
				{
					//$order['tr_date']=$order['tr_date2'];
					
				}
			  ?>
              <tr>
                <td class="text-right"><?php echo date('d-m-Y',strtotime($order['tr_date'])); ?></td>
                
                <td class="text-right"><?php echo $order['tr_type']; ?></td>
                <td class="text-right"><?php if($order['tr_number']!='') { echo $order['tr_number']; } else { echo 'NA'; } ?></td>
                <td class="text-right"><?php echo $order['total_debit']; ?></td>
                <td class="text-right"><?php echo $order['total_credit']; ?></td>
	   <td class="text-right"><?php if($order['invoice_status']=='1') { echo "Un-Paid"; } else if($order['invoice_status']=='2') { echo "Paid"; } ?></td>     
              </tr>
              <?php 
				$totaldebit=$totaldebit+number_format((float)$order['total_debit'], 2, '.', '');
              	$totalcredit=$totalcredit+number_format((float)$order['total_credit'], 2, '.', '');
              } ?>
              
              <tr>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right" style="text-align: right;"><b>Total : </b></td>   
                <td class="text-right" style="text-align: right;"><?php echo number_format((float)$totaldebit, 2, '.', ''); ?></td>
                <td class="text-right" style="text-align: right;"><?php echo number_format((float)$totalcredit, 2, '.', ''); ?></td>
	  <td class="text-right"></td>
              </tr>   

	<tr>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right" style="text-align: right;"><b> Closing Balance : </b></td>   
                <td class="text-right" style="text-align: right;"><?php echo number_format((float)($totaldebit-$totalcredit), 2, '.', ''); ?></td>
                <td class="text-right" style="text-align: right;"></td>
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


    
</body>
</html>