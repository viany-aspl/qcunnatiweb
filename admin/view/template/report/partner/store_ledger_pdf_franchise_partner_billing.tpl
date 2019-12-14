<link href="view/javascript/bootstrap/less/bootstrap.less" rel="stylesheet/less" />
<script src="view/javascript/bootstrap/less-1.7.4.min.js"></script>
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />

<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />

 <div class="panel-body" style="padding: 15px;padding-left: 40px;padding-right: 40px;">
       
          <div class="row">
            <div class="col-sm-6">
	<?php if($store_name!="") { ?>
		<div class="well" style="background-color: white;border-radius: 3px;box-shadow: none;border: none;">
		<strong style="text-transform: capitalize;font-size: 17px;"><?php echo $store_incharge; ?></strong>
		<br/><br/>
		<strong style="text-transform: capitalize;font-size: 17px;"><?php echo $store_name; ?></strong>
		<br/>
		<?php $store_address1=explode(',',$store_address); 
			$aa=1; 
			foreach($store_address1 as $store_address2) 
			{ 
			if($aa!=1)
			{
				echo ","; 
			}
			echo $store_address2;
			echo "</br>";
			 $aa++;
		 } ?>
		<br/>
		GSTN : <?php echo $store_gstn; ?>
		<br/>
		Current Outstanding : <?php echo $closed_credit; ?>
		</div>
	<?php } ?>
            </div>
           <div class="col-sm-6" style="width: 46%;">
	<div class="well">
		<i class="fa fa-globe" aria-hidden="true"></i> <strong>Visit </strong><a href="https://unnati.world/">https://unnati.world/</a>
		<br/>
		<i class="fa fa-phone-square" aria-hidden="true"></i> <strong>Dial </strong>+91 120 4040160
	</div>
           </div>
         </div>
       <div class="row">
            <div class="col-sm-12">
		<div class="well">
			<strong>Statement of Transactions of <?php echo $store_name; ?> in INR for the period <?php echo date('M d,Y',strtotime($filter_date_start)); ?> - <?php echo date('M d,Y',strtotime($filter_date_end)); ?> </strong>
		</div>
	</div>
         </div>
</div>
<div class="table-responsive" style="padding: 15px;padding-left: 40px;padding-right: 40px;">
<?php if($store_name!="") { ?>
	<!-- <table class="table table-bordered">
            <thead>
               <tr>
                
                <td class="text-left">Party Name</td>
	<td class="text-left">Store Name</td>
	<td class="text-left">Date of Invoice</td>
                <td class="text-left">Invoice Number</td>
                <td class="text-left"> Amount</td>
                <td class="text-left">Payment Status</td>
                
              </tr>
            </thead>
	<tbody>
              
              <?php  ?>
               <tr>
                
                <td class="text-left"><?php echo $product['party_name']; ?></td>
	<td class="text-left"><?php echo $product['store_name']; ?></td>
	<td class="text-left"><?php echo date('d-m-Y',strtotime($product['Date'])); ?></td>
	<td class="text-left"><?php echo $product['order_id']; ?></td>
                
                <td class="text-left"><?php if($product['Withdrawals']!='0.00') { echo $product['Withdrawals']; }  ?></td>
                
                <td class="text-left" style="max-width: 200px;"><?php if($product['paid_status']=="0"){ echo "Un-Paid"; } else if($product['paid_status']=="1"){ echo "Paid"; } ?></td>
	
              </tr>
	</tbody>
	</table>
	-->
 <?php } ?>
          <table class="table table-bordered">
            <thead>
              <tr>
                
                <td class="text-left">Party Name</td>
	<td class="text-left">Store Name</td>
	<td class="text-left">Date of Invoice</td>
                <td class="text-left">Invoice Number</td>
                <td class="text-left"> Amount</td>
                <td class="text-left">Payment Status</td>
                
              </tr>
            </thead>
            <tbody>
              
              <?php  
		$total_deposit=0;
		$total_Withdrawals=0;
		foreach ($products as $product) { ?>
              <tr>
                
                <td class="text-left"><?php echo $product['party_name']; ?></td>
	<td class="text-left"><?php echo $product['store_name']; ?></td>
	<td class="text-left"><?php echo date('d-m-Y',strtotime($product['Date'])); ?></td>
	<td class="text-left"><?php echo $product['order_id']; ?></td>
                
                <td class="text-left"><?php if($product['Withdrawals']!='0.00') { echo $product['Withdrawals']; }  ?></td>
                
                <td class="text-left" style="max-width: 200px;"><?php if($product['paid_status']=="0"){ echo "Un-Paid"; } else if($product['paid_status']=="1"){ echo "Paid"; } ?></td>
	
              </tr>
              <?php 
		
		} ?>
              
            </tbody>
          </table>
        </div>
        <style>
* {
    box-sizing: border-box;
}
html, body {
    font-family: "Open Sans",sans-serif;
    font-size: 12px;
    color: #666;
    font-weight: 500;
    line-height: 18px;
    text-rendering: optimizelegibility;
}
.row {
    margin-left: -15px;
    margin-right: -15px;
}
.col-sm-6 {
    width: 45%;
}
.col-sm-12 {
    width: 96%;
}
.well {
    min-height: 20px;
    padding: 19px;
    margin-bottom: 20px;
    background-color: #F5F5F5;
    border: 1px solid #E3E3E3;
    border-radius: 3px;
    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.05) inset;
}
.table-responsive {
    overflow-x: auto;
}
.table-bordered {
    border: 1px solid #DDD;
}
.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 17px;
}
table {
    background-color: transparent;
}
table {
    border-collapse: collapse;
    border-spacing: 0px;
}
.table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > td {
    border-top: 0px none;
}
.table thead > tr > td, .table tbody > tr > td {
    vertical-align: middle;
}
.table-bordered > thead > tr > th, .table-bordered > thead > tr > td {
    border-bottom-width: 2px;
}
.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
    border: 1px solid #DDD;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    padding: 8px;
    line-height: 1.42857;
    vertical-align: top;
    border-top: 1px solid #DDD;
}
.table thead td {
    font-weight: bold;
}
.text-left {
    text-align: left;
}
td, th {
    padding: 0px;
}
.table thead > tr > td, .table tbody > tr > td {
    vertical-align: middle;
}
body{
padding-left: 80px;
padding-right: 80px;
}
.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
    float: left;
}
.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
    position: relative;
    min-height: 1px;
    padding-left: 15px;
    padding-right: 15px;
}
td
{
    border: 1px solid #DDD;
    padding: 8px;
    line-height: 1.42857;
    vertical-align: top;
    border-top: 1px solid #DDD;
}
</style>