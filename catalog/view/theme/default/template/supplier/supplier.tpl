
<body>
<title>Purchase Order Details</title>
<div id="content">
<div class="page-header">
    
  </div>
	<div class="panel panel-default" id = "print_div">
	
	<div class="panel-body">
            <div class="row col-lg-12"  >
			<div class="col-lg-6">
            <h3 class="panel-title">
		<?php //print_r($order_information['products']); 
                echo "Order #" . $order_information['order_info']['id'] . ""; ?>
                </h3>
		</div>
                <div class="col-lg-6">&nbsp;</div>
                </div>
            <div class="row col-lg-12"  >
			<div class="col-lg-6">
			<label><b>Store : </b></label>
			<?php echo $store_information['name']?>
			</div>
			<div class="col-lg-6">
			<label><b>Supplier name : </b></label>
			<?php 
                        //print_r($order_information['products'][0]);
                        echo $order_information['products'][0]['supplier_names'][0]; ?>
			</div>
		</div>
		<div class="row col-lg-12" >
			<div class="col-lg-6">
				<label><b>Ordered By : </b></label>
				<?php echo $order_information['order_info']['firstname'] ." ".$order_information['order_info']['lastname']; ?>
			</div>
                    <div class="col-lg-6">
			<label><b>Supplier email : </b></label>
			<?php 
                        //print_r($order_information['products'][0]);
                        echo $order_information['products'][0]['supplier_email']; ?>
			</div>
		</div>
		
		
		
		<div class="row col-lg-12" >
			<div class="col-lg-6">
			<label><b>Purchase order date : </b></label>
			<?php echo $order_information['order_info']['order_date']; ?>
			</div>
			
		</div>
		
		<table class="table table-bordered table-responsive col-lg-12" border="1">
          <thead>
            <tr>
              <td class="text-left" style="width: 11.11%;"><b>Product Name</b></td>
              <td class="text-left" style="width: 11.11%;"><b>Demand</b></td>
			  <td class="text-left" style="width: 11.11%;text-align: left !important;"><b>Sent Quantity (By Supplier)</b></td>
	      <!--<td class="text-left" style="width: 11.11%;text-align: left;"><b>Supplier</b></td>
              -->
			</tr>
          </thead>
          <tbody>
		  <?php //print_r($order_information['order_info']['driver_otp']);
			$grand_total = 0;
		    $start_loop = 0;
			foreach($order_information['products'] as $product)
			{
			//print_r($product);
		  ?>
            <tr>
              <td class="text-left"><?php echo  $product['name'];?></td>
			  <input type="hidden" name="product_id" id="product_id" value="<?php echo $product['product_id']; ?>" />
			  <input type="hidden" name="p_quantity" id="p_quantity" value="<?php echo $product['quantities'][0]; ?>" />
			  <td class="text-left"><?php echo  $product['quantities'][0];?></td>
			  <td class="text-center" style="text-align: center;">
			  <input onkeypress="return isNumber(event)" type="text" style="float: left;margin-right: 20px;max-width: 163px;" class="form-control" maxlength="3"  
			  <?php if($order_information['order_info']['driver_mobile']!=""){ ?> readonly='readonly' <?php }?>
			  value="<?php echo $product['quantities'][0]; ?>" name="supplier_quantity" id="supplier_quantity" />
			  
			  </td>
			  <!--<td class="text-left">
			  <?php if(isset($product['supplier_names'])){
				for($i=0; $i<count($product['supplier_names']); $i++)
				{					
			  ?>
			  <?php echo $product['supplier_names'][$i] . "<br />"; ?>
			  <?php }} ?>
			  </td>
			  -->
			</tr>
		<?php
			}
		?>
                        <tr>
                            <td class="text-right" id="set_colspan" colspan="3">
                                <img id="cr_img" src="http://www.danubis-dcm.org/Content/Images/processing.gif" style="float: right;height: 60px;display: none;"/>
								<?php if($order_information['order_info']['driver_otp']==""){ ?>
                                <input id='sbmt_btn' onclick="return submit_order();" class="btn btn-primary pull-right" type="button" value="Submit" />
								<?php } ?>
                             <span class="pull-right">
                                    <b style="float: left;padding-top: 7px;font-size: 15px;">
                                        Driver Mobile Number : 
                                    </b>
                                 <input onkeypress="return isNumber(event)" type="text" 
                                           style="float: left;max-width: 163px;margin-left: 20px;margin-right: 20px;"
                                           class="form-control" maxlength="10" <?php if($order_information['order_info']['driver_mobile']!=""){ ?> readonly='readonly' <?php }?> value="<?php echo $order_information['order_info']['driver_mobile']; ?>" name="driver_mobile" id="driver_mobile" />
                                </span>
                            </td>
                               
			</tr>
                        
			<!--<tr>
				<td class="text-right" id="set_colspan" colspan="8"><b>Grand Total:</b></td>
				<td class ="text-left"><?php if($grand_total == 0) echo ''; else echo $grand_total; ?></td>
			</tr>-->
			
			</tbody>
        </table>
		
	</div>
  </div>
</div>
</body>
<script type="text/javascript" src="https://unnati.world/shop/admin/view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script>
    function isNumber(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
    function submit_order()
    {
        var supplier_id='<?php echo $order_information['products'][0]['supplier_id']; ?>';
        var order_id='<?php echo $order_information['products'][0]['order_id']; ?>';
        var store_id='<?php echo $order_information['products'][0]['store_id']; ?>';
        var driver_mobile=$("#driver_mobile").val();
		var product_id=$("#product_id").val();
		
         var supplier_quantity=$("#supplier_quantity").val();
		 var p_quantity=$("#p_quantity").val();
		 
		if((supplier_quantity=="") || (supplier_quantity=="0"))
		{
		    alert('Please enter sent quantity');
			$("#supplier_quantity").val('');
            $("#supplier_quantity").focus();
            return false;
		}
		if(supplier_quantity>p_quantity)
		{
		    alert('Sent quantity can not be greater then requested quantity');
            $("#supplier_quantity").focus();
            return false;
		}
        //alert(store_id);
        if(driver_mobile.length<10)
        {
            alert('Please enter 10 digit mobile number');
            $("#driver_mobile").focus();
            return false;
        }
        else
        {
		 
        $("#cr_img").show();
		$("#sbmt_btn").hide();
    $.ajax({
              url: 'index.php?route=supplier/supplier/submit_order_by_supplier&supplier_id=' +  encodeURIComponent(supplier_id)+'&order_id=' +  encodeURIComponent(order_id)+'&driver_mobile=' +  encodeURIComponent(driver_mobile)+'&store_id='+store_id+'&supplier_quantity='+supplier_quantity+'&product_id='+product_id,
              // dataType: 'json',
               success: function(json) 
               {
					//alert(json);
					 $("#cr_img").hide();
					 $("#driver_mobile").prop("readonly", true);
					 $("#supplier_quantity").prop("readonly", true);
                   alert('Successfully submited');
                  
                   //alert(json);
                   //var json2=json.split('----and----');
                   //$("#tab-order").html(json2[0]);
                   
               }
                       
              });
    return false;  
    }
    }
    </script>
	<style type="text/css">
	thead tr td{
		font-weight:bold;
		font-size:10px;
	}
	.table-bordered thead td {
    background: #eeeeee none repeat scroll 0 0 !important;
    padding: 5px !important;
    text-align: center !important;
	border-bottom:2px solid #cccccc !important;
}
.table-responsive tbody td {
    padding: 5px !important;
	line-height: 1.7;
	font-size:11px;
}
.panel-heading {
    background: #eeeeee none repeat scroll 0 0;
}
.page-header{
	text-align:center;
}
table table-bordered tbody tr:nth-child(2n) {
    background-color: #eeeeee !important;
}
#content{
	
        margin-top: 50px auto;
	width:95%;
}
.header{
	width: 100%;
}
.logo{
	width: 25%;
	float:left;
	margin-top:20px !important;
}
.company{
	width: 72%;
	float:right;
	margin-top:20px !important;
}
.logo img, .company_info p{
	width:100%;
}
.company h2{
	float:right;
}
.panel-heading{
	display:none;
}
.table-bordered, .table-bordered td {
    border: 1px solid #dddddd;
	border-collapse:collapse;
}
.company_info p{
	width: 100%;
	font-weight: bold;
	margin:0px;
	font-size:11px;
	font-family:Verdana, Geneva, sans-serif;
}
.company_info p span{
	font-size:11px;
	font-weight: normal;
}
.date span{
	float:right;
}
.owner-date{
	width: 100%;
}
.owner{
	float:left;
	width: 50%;
}
.date{
	float:right;
	width: 17%;
	font-weight: normal;
}
.date span{
	float:right;
	font-size:11px;
	font-weight:normal;
}

/*mail type*/
.mail_type{
	width:100%;
}
.mail{
	float:left;
	width:50%;
}
.type{
	float:right;
	width:10%;
	font-weight:bold;
	font-size:11px;
	font-family:Verdana, Geneva, sans-serif;
}
.type span{
	float:right;
	font-size:11px;
	font-weight:normal;
}
/*mail type*/

table tr td{
	font-family:Verdana, Geneva, sans-serif;
	font-size:10px;
	text-align:center;
}
table{
	margin-top:30px;
}
.order-info{
	font-size:11px;
	font-family:Verdana, Geneva, sans-serif;
}
div.order_empty{
	width:100%;
}
.empty_div{
	float:left;
	width:50%;
}
.order-no{
	float:right;
	width:15%;
	font-weight:bold;
	font-size:11px;
}
.order-no span{
	font-family:Verdana, Geneva, sans-serif;
}
.footer{
	width:100%;
}
.address
{
	width:70%;
	float:left;
}
.pageno{
	width:4%;
	float:right;
}
.col-lg-3
{
    width:25%;
    float: left;
}
.col-lg-6
{
    width:50%;
    float: left;
}
.col-lg-12
{
    width:100%;
    float: left;
}
.form-control {
    display: block;
    width: 100%;
    height: 35px;
    padding: 8px 13px;
    font-size: 12px;
    line-height: 1.42857;
    color: #555;
    background-color: #FFF;
    background-image: none;
    border: 1px solid #CCC;
    border-radius: 3px;
    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
}
.btn {
    display: inline-block;
    margin-bottom: 0px;
    font-weight: normal;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    white-space: nowrap;
    padding: 7px 13px;
    font-size: 12px;
    line-height: 1.42857;
    border-radius: 3px;
    -moz-user-select: none;
}
.btn-primary {
    color: #FFF;
    background-color: #1E91CF;
    border-color: #1978AB;
}
.pull-right {
    float: right !important;
}
</style>

