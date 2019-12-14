<?php  echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
    <div class="container-fluid"> 
      <div class="pull-right"><!--<button onclick ="print_order()" data-toggle="tooltip" title="<?php echo "Print Order"; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>--><!--<a href="<?php echo $shipping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></a> <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>--> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo "Cancel Button"; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
  <h1><?php echo "Purchase Return/Credit Note"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Purchase Return/Credit Note  </a></li>
        <?php } ?>
      </ul>
      
      <?php if ($error_warning) { ?> 
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    </div>
  </div>
  <div class="panel panel-default" id = "print_div">
	<div class="panel-heading">
		
	</div>
      <form method="post" action="" enctype="multipart/form-data" id="invoice_form">
	<div class="panel-body">
            
            <div class="row">
            <div class="col-sm-12">
               <div class="table-responsive">
                  <table class="table table-bordered">
                    <tr>
                        <td class="col-sm-6">
                            <strong>From:</strong>
								<select onchange="return get_supplier_to_data(this.value);" name="filter_supplier" id="input-supplier" required="required" style="width: 100%;" class="select2 form-control">
									<option value="" >Select Supplier</option>
									<?php foreach ($suppliers as $supplier) { ?>
											<?php if ($supplier['id'] == $filter_suplier) { ?>
											<option value="<?php echo $supplier['id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
											<?php } ?>
									<?php } ?>
								</select>
                            <br /><br />
                            <?php  $store_to_data=explode('---',$store_to_data);  ?>
                            Name : <span id="to_supplier_name"><?php echo $store_to_data[0];    ?></span> <br />
                            Address : <span id="to_supplier_address"><?php  echo $store_to_data[1];    ?></span> <br />
                            Phone No : <span id="to_supplier_phone"><?php  echo $store_to_data[2];    ?></span><br />
                            Email Id : <span id="to_supplier_email"><?php  echo $store_to_data[3];    ?></span> <br />
                            Pan Card : <span id="to_supplier_pan"><?php echo $store_to_data[4];   ?></span> <br />
								GSTN : <span id="to_supplier_gstn"><?php  echo $store_to_data[5];   ?></span> <br />
                        </td>
							<td class="col-sm-6"><strong>To:</strong>
                            <br />
                             <?php echo OFFICE_ADDRESS; ?>
                        </td>
                    </tr>

                      <tr>
						<td class="col-sm-6">
                         <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="input-date-end"><?php echo "Invoice No"; ?></label>
									<input autocomplete="off"  name="invoiceno" id="invoiceno" type="text" placeholder="Invoice No" required="required" class="form-control"/>
								</div>
                         </div>  
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="input-date-end"><?php echo "Invoice Date"; ?></label>
									<div class="input-group date">
										<input type="text" name="filter_date" autocomplete="off" readonly="readonly"  value="" placeholder="<?php echo "Invoice Date"; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" required="required"/>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
						</td>
						<td >
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="input-date-end">Warehouse</label>
									
									
									<select name="received_store" id="input-received_store"  class="form-control" required>
										<option value=""> SELECT WAREHOUSE</option>
										<?php foreach ($stores as $store) { ?>
											<?php if ($store['store_id'] == $filter_store) { ?>
												<option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
									
									
                              </div>
							  <div style="float: left;">
									<div style="float: left;">NO-WAREHOUSE</div>
									<input type="checkbox" onclick="return set_unset_ware_house();" id="no_ware_house" name="no_ware_house" value="1"  />
								</div>
                         </div>
							 
							
                         </td>
                      </tr>
					  
                      </table>
			
                   
                </div>
            </div>
        </div>  
	    
      
		<table class="table table-bordered" id="print_table" border="1">
          <thead>
                <tr>
                          
                          <td class="text-left" style="width: 11.11%;">Product</td>
                           <td class="text-left" style="width: 11.11%;">Rate</td>
                          <td class="text-left" style="width: 11.11%;">Quantity</td>
							 <td class="text-left" style="width: 11.11%;">Unit</td>
							<td class="text-left" style="width: 11.11%;">Discount</td>
                           <td class="text-left" style="width: 11.11%;">Amount</td>
                       
                          
                </tr>
				<input type="hidden" id="sub_total"  />
          </thead>
          <tbody id="t_body">
          
              
             
		  <?php
			$grand_total = 0;$a=1;
                        $p_count=count($order_information['products']);
			//foreach($order_information['products'] as $product)
			//{ print_r($product);
		  ?>
            <tr id="tr_<?php echo  $a; ?>">
                <input name="product_id" id="p_id_<?php echo  $a; ?>" type="hidden" value="<?php echo  $product['product_id'];?>" />
                <input name="product_hsn[]" id="p_hsn_<?php echo  $a; ?>" type="hidden" value="<?php echo  $product['product_hsn'];?>" />
                
                 <input name="buttonvalue" id="buttonvalue" type="hidden" value="save" />
               
                  
                <input class="form-control" name="p_tax_type" id="p_tax_type_<?php echo  $a; ?>" type="hidden" value="<?php echo  $product['product_tax_type'];?>" />
                <input class="form-control" name="p_tax_rate" id="p_tax_rate_<?php echo  $a; ?>" type="hidden" value="<?php echo  round($product['product_tax_rate'],PHP_ROUND_HALF_UP);?>" />
                <input class="form-control" name="p_amount" id="p_amount_<?php echo  $a; ?>" type="hidden" value="<?php echo (round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity']; ?>" />
                     
                      
                         <td class="text-left" id="td_p_name_<?php echo  $a; ?>">
                              
                              <input  required="required" class="form-control" name="product_name[]" id="p_name_<?php echo  $a; ?>" type="text" value="<?php echo  $product['product_name'];?>" />
                               
 
                          </td>
                           <!--<td class="text-left" id="td_p_tax_rate_<?php echo  $a; ?>">
			  <?php echo round($product['product_tax_rate'],PHP_ROUND_HALF_UP);?>
                              
			  </td>-->
                          
			 
			  
			  <td class="text-left" id="td_p_price_<?php echo  $a; ?>">
		 
                              <input required="required" autocomplete="off" class="form-control"  onkeypress="return remove_zero(this.value,<?php echo $a; ?>,event);" onkeyup="return update_by_price(this.value,<?php echo $a; ?>);" name="p_price" id="p_price_<?php echo  $a; ?>" type="text" value="<?php echo round($product['product_price'],PHP_ROUND_HALF_UP);?>" />
		
                           </td>
                           
			   <td class="text-left" id="td_p_qnty_<?php echo  $a; ?>">
			   
                              <input autocomplete="off"  required="required" onkeypress="return remove_zero_q(this.value,<?php echo $a; ?>,event);" onkeyup="return update_by_q(this.value,<?php echo $a; ?>);" class="form-control" name="p_qnty" id="p_qnty_<?php echo  $a; ?>" type="text" value="0" />
			
			  </td>
			  <td class="text-left" id="td_p_unit_<?php echo  $a; ?>">
			   
                  <select class="form-control" name="p_unit" id="p_unit_<?php echo  $a; ?>">
						<option value="">SELECT</option>
						<option value="KG">KG</option>
						<option value="BAG">BAG</option>
						<option value="TON">TON</option>
						<option value="LTR">LTR</option>
						<option value="PCS">PCS</option>
						<option value="BOTTLE">BOTTLE</option>
				  </select>
			
			  </td>
			  <td class="text-left" id="td_p_qnty_<?php echo  $a; ?>">
			   
                              <input autocomplete="off"  required="required" onkeypress="return remove_zero_d(this.value,<?php echo $a; ?>,event);" onkeyup="return update_by_d(this.value,<?php echo $a; ?>);" class="form-control" name="p_discount" id="p_discount_<?php echo  $a; ?>" type="text" value="0" />
			
			  </td>
                         
			  <td class="text-left" id="td_p_amount_<?php echo  $a; ?>">
						<?php //+$product['product_tax_rate']
                          echo number_format((float)((round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity']), 2, '.', '');
                          $grand_total=$grand_total+((round($product['product_price'],PHP_ROUND_HALF_UP))*$product['product_quantity']);
                          ?>
                              
			  </td>
               
			</tr>
		<?php
                        //$a++;
			//}
		?>
			
          
</tbody>
        </table>
		<input type="hidden" name="span_cgst_1" id="span_cgst_1" />
		<input type="hidden" name="span_cgst_type_1" id="span_cgst_type_1" />
		
		<input type="hidden" name="span_sgst_1" id="span_sgst_1" />
		<input type="hidden" name="span_sgst_type_1" id="span_sgst_type_1" />
							
		<table class="table table-bordered" id="tax_table">
          <tbody>
		  <tr style="display: none;" id="tr_scgst_1">
                    <td class="text-right" style="width: 78%;" >
					
					<b>
                            <span id="span_cgst_txt_1" >
                           
                            </span>
                            <br/>
                            <span id="span_sgst_txt_1" >
                            
                            </span>
							
                        </b></td>
                    <td style="width: 22%;" class ="text-left" >
							
                        <span id="span_cgst_1">
                         </span>
                        <br/>
                        <span id="span_sgst_1">
                         </span>
							
                    </td>
	            
		
		</tr>
		  
		  <tr id="tr_scgst_by_5" style="display: none;">
                    <td class="text-right" style="width: 78%;"><b>
                            <span id="span_cgst_txt_by_5">
                            CGST @2.5%
                            </span>
                            <br>
                            <span id="span_sgst_txt_by_5">
                            SGST @2.5%
                            </span>
                        </b></td>
                    <td style="width: 22%;" class="text-left">
                        <span id="span_cgst_by_5">
                        15.72                        </span>
                        <br>
                        <span id="span_sgst_by_5">
                        15.72                        </span>
                    </td>
		</tr>  
                
               <tr id="tr_scgst_by_12" style="display: none;">
                    <td class="text-right" style="width: 78%;"><b>
                            <span id="span_cgst_txt_by_12">
                            CGST @6%
                            </span>
                            <br>
                            <span id="span_sgst_txt_by_12">
                            SGST @6%
                            </span>
                        </b></td>
                    <td style="width: 22%;" class="text-left">
                        <span id="span_cgst_by_12">
                        0.00                        </span>
                        <br>
                        <span id="span_sgst_by_12">
                        0.00                        </span>
                    </td>
		</tr>  
                
                <tr id="tr_scgst_by_18" style="display: none;">
                    <td class="text-right" style="width: 78%;"><b>
                            <span id="span_cgst_txt_by_18">
                            CGST @9%
                            </span>
                            <br>
                            <span id="span_sgst_txt_by_18">
                            SGST @9%
                            </span>
                        </b></td>
                    <td style="width: 22%;" class="text-left">
                        <span id="span_cgst_by_18">
                        0.00                        </span>
                        <br>
                        <span id="span_sgst_by_18">
                        0.00                        </span>
                    </td>
		</tr>
 <tr id="tr_scgst_by_0" style="display: none;">
                    <td class="text-right" style="width: 78%;"><b>
                            <span id="span_cgst_txt_by_0">
                            CGST @0%
                            </span>
                            <br>
                            <span id="span_sgst_txt_by_0">
                            SGST @0%
                            </span>
                        </b></td>
                    <td style="width: 22%;" class="text-left">
                        <span id="span_cgst_by_0">
                        0.00                        </span>
                        <br>
                        <span id="span_sgst_by_0">
                        0.00                        </span>
                    </td>
		</tr> 		
            </tbody> 

		</table>
            <table class="table table-bordered">
          <tbody>
                <tr id="tr_scgst_by_18" >
                    <td class="text-right" style="width: 78%;">
					<div style="float: left;"><div style="float: left;">NO-TAX</div><input type="checkbox" id="no_tax_check_box" onclick="return set_unset_tax();" /></div>
					<b>
                            <span id="span_cgst_txt_by_18">
                            Rebate & Discount / Freight Charge
                            </span>
                            
                        </b></td>
                    <td style="width: 22%;" class="text-left">
                        
                        
							<input type="text" autocomplete="off"  required="required" onkeypress="return remove_zero_t(this.value,event);" onkeyup="return update_by_t(this.value);" class="form-control" name="transport_charge" id="transport_charge" value="0" />
                    </td>
		</tr>
                <tr id="tr_scgst_by_18" >
                    <td class="text-right" style="width: 78%;"><b>
                            <span id="span_cgst_txt_by_18">
                            Grand Total
                            </span>
                            
                        </b></td>
                    <td style="width: 22%;" class="text-left">
                        
                        <span id="td_grand_total">
                        0.00                       
							</span>
							<input type="hidden" name="grand_total" id="grand_total" value="0" />
                    </td>
		</tr>      
            </tbody> 

		</table>
		<div class="col-sm-12">  
                              <div class="form-group">
                             <label class="control-label" for="input-date-end">Purchase Invoice Number</label>
                              <select style="width:100%;" name="received_prn" id="input-received_prn" class="form-control">
										<option value=""> SELECT PURCHASE INVOICE NUMBER</option>
										
									</select>
                              </div>
                              </div> 
            <button  type="submit" onclick="return updatebuttonvalue('save');"  class="btn btn-primary pull-right" id="cr_btn1" >Save</button> &nbsp; &nbsp;
            <img id="cr_img" src="http://www.danubis-dcm.org/Content/Images/processing.gif" style="float: right;height: 60px;display: none;"/>
            
        
	
	</div>
	</form>
  </div>
  
</div>
<style>
#LoadingDiv{
	margin:0px 0px 0px 0px;
	position:fixed;
	height: 100%;
	z-index:9999;
	padding-top:200px;
	padding-left:50px;
	width:100%;
	clear:none;
	background:url(/img/transbg.png);
	/*background-color:#666666;
	border:1px solid #000000;*/
	}
/*IE will need an 'adjustment'*/
* html #LoadingDiv{
     position: absolute;
     height: expression(document.body.scrollHeight &gt; document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight + 'px');
	}
</style>

<script type="text/javascript">
function set_unset_ware_house()
{
	var checked=$('#no_ware_house').prop('checked');
	if(checked) 
	{
		$('#input-received_store').prop('required',false);
	}
	else
	{
		$('#input-received_store').prop('required',true);
	}
}
function set_unset_tax()
{
	var checked=$('#no_tax_check_box').prop('checked');
	
	document.getElementById("no_tax_check_box").checked = true;
	var prd_id=$("#p_id_1").val();
	if(checked) 
	{
		$('#no_tax_check_box').prop('checked', true); // Checks it
		if(prd_id!='')
		{
			var sub_total=$("#sub_total").val();
			var transport_charge=$("#transport_charge").val();
			var grand_total=parseFloat(sub_total)+parseFloat(transport_charge);
			grand_total=grand_total.toFixed(2)
		//$("#tax_table").hide();
		$("#span_cgst_1").val('0.00');
		$("#span_cgst_type_1").val('');
		$("#p_tax_type_1").val('GST@0%');
		$("#span_sgst_1").val('0.00');
		$("#span_sgst_type_1").val('');
		$("#tr_scgst_by_0").show();
		$("#tr_scgst_by_5").hide();
		$("#tr_scgst_by_12").hide();
		$("#tr_scgst_by_18").hide();
		$("#tr_scgst_by_28").hide();
		$("#grand_total").val(grand_total);
		$("#td_grand_total").html(grand_total);
		}
	} 
	else 
	{
		$('#no_tax_check_box').prop('checked', false); // Unchecks it
		if(prd_id!='')
		{
		//$("#tax_table").show();
		$("#p_id_1").val('');
		$("#p_name_1").val('');
		$("#tr_scgst_by_5").hide();
		$("#tr_scgst_by_12").hide();
		$("#tr_scgst_by_18").hide();
		$("#tr_scgst_by_28").hide();
		$("#tr_scgst_by_0").hide();
		$("#p_qnty_1").val('0');
		$("#p_price_1").val('0.00');
		$("#td_p_amount_1").html('0.00');
		$("#td_p_amount_1").html('0.00');
		$("#td_grand_total").html('0.00');
		$("#grand_total").val('0.00');
		$("#transport_charge").val('0.00');
		}
	}
	
	return true;
}
$("#input-supplier").select2();
$("#input-received_prn").select2();
$('.date').datetimepicker({
	pickTime: false,
	maxDate: new Date()
});
$("#input-store").select2();
</script>
<script type="text/javascript">

function get_supplier_to_data(supplier_id)
{
    //alert(supplier_id);
   if((supplier_id!="") && (supplier_id!="0"))
    {
        $.ajax({
            url: 'index.php?route=purchaseorder/purchase_order/get_to_supplier_data&token=<?php echo $token; ?>&supplier_id=' +  encodeURIComponent(supplier_id),
            
            success: function(json) {
                var data=json.split('---');
               //alert(json);
                $("#to_supplier_name").html(data[1]);
                $("#to_supplier_address").html(data[2]);
                $("#to_supplier_phone").html(data[3]);
                $("#to_supplier_email").html(data[4]);
                $("#to_supplier_pan").html(data[5]);
                $("#to_supplier_gstn").html(data[6]);
				get_prn_list(supplier_id);
            }
        });
        $("#supplier_id").val(supplier_id);
    }
    else
    {
                $("#to_supplier_name").html('');
                $("#to_supplier_address").html('');
                $("#to_supplier_phone").html('');
                $("#to_supplier_email").html('');
                $("#to_supplier_pan").html('');
                $("#to_supplier_gstn").html('');
                $("#to_supplier_phone").val('');
                $("#to_supplier_email").val('');
    }
    if(supplier_id!="")
    {
     $("#p_name_1").prop('disabled', false);
    }
    else
    {
        $("#p_name_1").prop('disabled', true);
    }
    //return false;
}

function get_prn_list(store_id,product_id)
{
	if(!store_id)
	{
		store_id=$("#input-supplier").val();
	}
	if(store_id=='')
	{
		$("#input-received_prn").html('<option value=""> SELECT PURCHASE INVOICE NUMBER</option>');
	}
	else
	{
		if(!product_id)
		{
		product_id=$("#p_id_1").val();
		}
		if(product_id)
		{
		$.ajax({
		url:'index.php?route=purchaseorder/purchase_return/get_prn_list&token=<?php echo $token; ?>&store_id='+encodeURIComponent(store_id)+'&product_id=' +encodeURIComponent(product_id),
		success:function(data){
			//alert(data);
			$("#input-received_prn").html(data);
		},
		error:function(data){
			
			alert(stringify.JSON(data));
		}
		
	});
		}
		else
		{
			$("#input-received_prn").html('<option value=""> SELECT PURCHASE INVOICE NUMBER</option>');
		}
	}
	return false;
}

function updatebuttonvalue(data)
{

$("#buttonvalue").val(data);
var input_supplier=$("#input-supplier").val();
var input_store=$("#input-received_store").val();
var input_date=$("#input-date-start").val();
var p_name=$("#p_id_1").val();
var p_price=$("#p_price_1").val();
var p_qnty=$("#p_qnty_1").val();
var invoiceno=$("#invoiceno").val();
var p_unit=$("#p_unit_1").val();
var received_prn=$("#input-received_prn").val();
if(input_supplier=="")
    {
        alertify.error('Please Select Supplier');
        $("#input-supplier").focus();
        return false;
    }
if(invoiceno=="")
    {
        alertify.error('Please Enter Invoice No');
        $("#invoiceno").focus();
        return false;
    }
if(input_date=="")
    {
        alertify.error('Please Select  Date');
        $("#input-date-start").focus();
        return false;
    }
	if(input_store=="")
    {
		//no_ware_house
		//alert($('#no_ware_house').prop("checked"));
		if($('#no_ware_house').prop("checked") == true)
		{
			//alertify.error('No Warehouse');
		}
		else
		{
			alertify.error('Please Select Warehouse');
			$("#input-received_store").focus();
			return false;
		}
    }
	
if(!p_name)
{
alertify.error('Please select Product');
$("#p_name_1").focus();
return false;
}

if(p_price=='0')
{
alertify.error('Please Fill Product Price');
$("#p_price_1").focus();
return false;
}
if(!p_qnty)
{
alertify.error('Please Fill Quantity');
$("#p_qnty_1").focus();
return false;
}
if(p_qnty=='0')
{
alertify.error('Please Fill Quantity');
$("#p_qnty_1").focus();
return false;
}
if(!p_unit)
{
alertify.error('Please Select Unit');
$("#p_unit_1").focus();
return false;
}
if(!received_prn)
{
//alertify.error('Please Select Purchase Invoice Number');
//$("#input-received_prn").focus();
//return false;
}

     $("#cr_btn1").hide();
     $("#cr_btn2").hide();
     $("#cr_img").show(); 
     return true;

}

function remove_zero_t(valuee,evt) 
{
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46)
   {
             if(valuee==0)
    	{
    		$("#transport_charge").val('');
    
    	}
    	return true;
    }
   if (charCode == 45)
   {        //alert(valuee);
             if(valuee=='0')
    	{ 
    		$("#transport_charge").val('0');
    
    	}
	if(valuee=='-')
    	{ 
    		$("#transport_charge").val('0');
    
    	}
    	return true;
    }
          if (charCode > 31 && (charCode < 48 || charCode > 57))
          {
             return false;
          }
          
    if(valuee==0)
    {
    $("#transport_charge").val('');
    }
}

function update_by_t(valuee)
{   //alert(valuee);
	var a=1;
    if(valuee=="")
    { 
       $("#transport_charge").val('0');
    } 
    if(valuee=="")
    {
        valuee=0;
    }
     if(valuee=='-')
    {    
     valuee=0;
   }
    valuee=parseFloat(valuee);
    var p_price=parseFloat($("#p_price_"+a).val());
	var discount=parseFloat($("#p_discount_"+a).val());
	
    var total_amount=(((p_price)*($("#p_qnty_"+a).val()))-parseFloat(discount));
    total_amount=parseFloat(total_amount).toFixed(2);
	
	//alert(total_amount);
    $('#p_amount_'+a).val(total_amount);
    $('#td_p_amount_'+a).html(total_amount);
    var thisid=a;
    var tax_type=$('#p_tax_type_'+thisid).val();//$('#td_p_tax_type_'+thisid).html();
    var cgst="";
    var sgst="";
    if(tax_type.trim()=="GST@5%")
    {
       cgst="2.5";
       sgst="2.5";
       
    }
    else if(tax_type.trim()=="GST@12%")
    {
      cgst="6";
      sgst="6";  
    }
    else if(tax_type.trim()=="GST@18%")
    {
       cgst="9";
       sgst="9";
    }
	else if(tax_type.trim()=="GST@28%")
    {
       cgst="14";
       sgst="14";
    }
    else 
    {
       cgst="0";
       sgst="0";
    }
    var tax_cgst=(parseFloat(total_amount)*parseFloat(cgst))/parseFloat(100);
    var tax_sgst=(parseFloat(total_amount)*parseFloat(sgst))/parseFloat(100);
    tax_cgst=parseFloat(tax_cgst).toFixed(2);
    tax_sgst=parseFloat(tax_sgst).toFixed(2);
    //alert(cgst+"+"+sgst);
	//alert('thisid: '+thisid);
	//alert(tax_cgst+"+"+tax_sgst);
	
    $("#span_cgst_txt_"+thisid).html('CGST @'+cgst+'% ');
    $("#span_sgst_txt_"+thisid).html('SGST @'+sgst+'% ');
    $("#span_cgst_"+thisid).val(tax_cgst);
    $("#span_sgst_"+thisid).val(tax_sgst);
    $("#span_cgst_type_"+thisid).val('CGST @'+cgst+'% ');
    $("#span_sgst_type_"+thisid).val('CGST @'+sgst+'% '); 
        
        
        var grand_total=0; 
        var sub_total=0;
        var total_tax=0;
        var total_tax_by_5=0;
        var total_tax_by_12=0;
        var total_tax_by_18=0;
		var total_tax_by_28=0;
        var count = parseFloat($('#print_table tr').length);//
        for(c=1;c<=count;c++)
        {
        
        var p_amount=$('#p_amount_'+c).val();
		
        if(p_amount!=undefined)
        {
        
        var sub_total = parseFloat(sub_total) + parseFloat(p_amount) ;
        }
        
        }
        var count = 1;//parseFloat($('#print_table_2 tr').length)-parseFloat(1);//
        //alert(sub_total);
        for(c=1;c<=count;c++)
        {
         var span_cgst=tax_cgst;//$("#span_cgst_"+c).html();
         var span_sgst=tax_sgst;//$("#span_cgst_"+c).html();
         if((span_cgst!="") && (span_sgst!=""))
         {
         total_tax=parseFloat(total_tax)+parseFloat(span_cgst)+parseFloat(span_sgst);//
         }
		 //alert('total_tax: '+total_tax);
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @2.5%")
         {
             total_tax_by_5=parseFloat(total_tax_by_5)+parseFloat(span_cgst);//
         }
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @6%")
         {
             total_tax_by_12=parseFloat(total_tax_by_12)+parseFloat(span_cgst);//
         }
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @9%")
         {
             total_tax_by_18=parseFloat(total_tax_by_18)+parseFloat(span_cgst);//
         }
		 if($("#span_cgst_txt_"+c).html().trim()=="CGST @14%")
         {
             total_tax_by_28=parseFloat(total_tax_by_28)+parseFloat(span_cgst);//
         }
         
        }
        total_tax_by_5=parseFloat(total_tax_by_5).toFixed(2);
        total_tax_by_12=parseFloat(total_tax_by_12).toFixed(2);
        total_tax_by_18=parseFloat(total_tax_by_18).toFixed(2);
		total_tax_by_28=parseFloat(total_tax_by_28).toFixed(2);
        //alert(total_tax_by_5+','+total_tax_by_12+','+total_tax_by_18);
        
        $("#span_cgst_by_5").html(total_tax_by_5);
        $("#span_sgst_by_5").html(total_tax_by_5);
        $("#span_cgst_by_12").html(total_tax_by_12);
        $("#span_sgst_by_12").html(total_tax_by_12);
        $("#span_cgst_by_18").html(total_tax_by_18);
        $("#span_sgst_by_18").html(total_tax_by_18);
        $("#span_cgst_by_28").html(total_tax_by_28);
        $("#span_sgst_by_288").html(total_tax_by_28);
        if(total_tax_by_5>0)
        {
            $("#tr_scgst_by_5").show();
        }
        else
        {
            $("#tr_scgst_by_5").hide();  
        }
        if(total_tax_by_12>0)
        {
            $("#tr_scgst_by_12").show();
        }
        else
        {
            $("#tr_scgst_by_12").hide();  
        }
        if(total_tax_by_18>0)
        {
            $("#tr_scgst_by_18").show();
        }
		
        else
        {
            $("#tr_scgst_by_18").hide();  
        }
        if(total_tax_by_28>0)
        {
            $("#tr_scgst_by_28").show();
        }
		else
        {
            $("#tr_scgst_by_28").hide();  
        }
		//alert(sub_total);
		//alert(total_tax);
        grand_total=parseFloat(total_tax)+parseFloat(sub_total);//
        sub_total=parseFloat(sub_total).toFixed(2);
		var tranport_charge=valuee;
		grand_total=parseFloat(grand_total)+parseFloat(tranport_charge);//
        grand_total=parseFloat(grand_total).toFixed(2);
        $('#td_sub_total').html(sub_total);
        $('#sub_total').val(sub_total);
        //alert(grand_total);
        $('#td_grand_total').html(grand_total);
        $('#grand_total').val(grand_total);
        return false;
}
function remove_zero_q(valuee,a,evt)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46)
          {
             if(valuee==0)
    {
    $("#p_qnty_"+a).val('');
    
    }
    return true;
          }
          if (charCode > 31 && (charCode < 48 || charCode > 57))
          {
             return false;
          }
          
    if(valuee==0)
    {
    $("#p_qnty_"+a).val('');
    }
}
function update_by_q(valuee,a)
{
    if(valuee=="")
    { 
       $("#p_qnty_"+a).val('0');
    } 
    if(valuee=="")
    {
        valuee=0;
        //return false;
    }
    //valuee=valuee;
    //var tax_rate=parseFloat($("#p_tax_rate_"+a).val());
    var p_price=parseFloat($("#p_price_"+a).val());
    //alert(p_price+'+'+tax_rate);+tax_rate
	
    var p_discount=$('#p_discount_1').val();
    var total_amount=(((p_price)*(valuee))-parseFloat(p_discount));
    total_amount=parseFloat(total_amount).toFixed(2);
	//alert(total_amount);
    $('#p_amount_'+a).val(total_amount);
    $('#td_p_amount_'+a).html(total_amount);
    var thisid=a;
    var tax_type=$('#p_tax_type_'+thisid).val();//$('#td_p_tax_type_'+thisid).html();
    var cgst="";
    var sgst="";
    if(tax_type.trim()=="GST@5%")
    {
       cgst="2.5";
       sgst="2.5";
       
    }
    else if(tax_type.trim()=="GST@12%")
    {
      cgst="6";
      sgst="6";  
    }
    else if(tax_type.trim()=="GST@18%")
    {
       cgst="9";
       sgst="9";
    }
	else if(tax_type.trim()=="GST@28%")
    {
       cgst="14";
       sgst="14";
    }
    else 
    {
       cgst="0";
       sgst="0";
    }
    var tax_cgst=(parseFloat(total_amount)*parseFloat(cgst))/parseFloat(100);
    var tax_sgst=(parseFloat(total_amount)*parseFloat(sgst))/parseFloat(100);
    tax_cgst=parseFloat(tax_cgst).toFixed(2);
    tax_sgst=parseFloat(tax_sgst).toFixed(2);
    //alert(cgst+"+"+sgst);
	//alert('thisid: '+thisid);
	//alert(tax_cgst+"+"+tax_sgst);
	
    $("#span_cgst_txt_"+thisid).html('CGST @'+cgst+'% ');
    $("#span_sgst_txt_"+thisid).html('SGST @'+sgst+'% ');
    $("#span_cgst_"+thisid).val(tax_cgst);
    $("#span_sgst_"+thisid).val(tax_sgst);
    $("#span_cgst_type_"+thisid).val('CGST @'+cgst+'% ');
    $("#span_sgst_type_"+thisid).val('CGST @'+sgst+'% '); 
        
        
        var grand_total=0; 
        var sub_total=0;
        var total_tax=0;
        var total_tax_by_5=0;
        var total_tax_by_12=0;
        var total_tax_by_18=0;
		var total_tax_by_28=0;
        var count = parseFloat($('#print_table tr').length);//
        for(c=1;c<=count;c++)
        {
        
        var p_amount=$('#p_amount_'+c).val();
		
        if(p_amount!=undefined)
        {
        
        var sub_total = parseFloat(sub_total) + parseFloat(p_amount) ;
        }
        
        }
        var count = 1;//parseFloat($('#print_table_2 tr').length)-parseFloat(1);//
        //alert(sub_total);
        for(c=1;c<=count;c++)
        {
         var span_cgst=tax_cgst;//$("#span_cgst_"+c).html();
         var span_sgst=tax_sgst;//$("#span_cgst_"+c).html();
         if((span_cgst!="") && (span_sgst!=""))
         {
         total_tax=parseFloat(total_tax)+parseFloat(span_cgst)+parseFloat(span_sgst);//
         }
		 //alert('total_tax: '+total_tax);
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @2.5%")
         {
             total_tax_by_5=parseFloat(total_tax_by_5)+parseFloat(span_cgst);//
         }
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @6%")
         {
             total_tax_by_12=parseFloat(total_tax_by_12)+parseFloat(span_cgst);//
         }
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @9%")
         {
             total_tax_by_18=parseFloat(total_tax_by_18)+parseFloat(span_cgst);//
         }
		 if($("#span_cgst_txt_"+c).html().trim()=="CGST @14%")
         {
             total_tax_by_28=parseFloat(total_tax_by_28)+parseFloat(span_cgst);//
         }
         
        }
        total_tax_by_5=parseFloat(total_tax_by_5).toFixed(2);
        total_tax_by_12=parseFloat(total_tax_by_12).toFixed(2);
        total_tax_by_18=parseFloat(total_tax_by_18).toFixed(2);
		total_tax_by_28=parseFloat(total_tax_by_28).toFixed(2);
        //alert(total_tax_by_5+','+total_tax_by_12+','+total_tax_by_18);
        
        $("#span_cgst_by_5").html(total_tax_by_5);
        $("#span_sgst_by_5").html(total_tax_by_5);
        $("#span_cgst_by_12").html(total_tax_by_12);
        $("#span_sgst_by_12").html(total_tax_by_12);
        $("#span_cgst_by_18").html(total_tax_by_18);
        $("#span_sgst_by_18").html(total_tax_by_18);
        $("#span_cgst_by_28").html(total_tax_by_28);
        $("#span_sgst_by_288").html(total_tax_by_28);
        if(total_tax_by_5>0)
        {
            $("#tr_scgst_by_5").show();
        }
        else
        {
            $("#tr_scgst_by_5").hide();  
        }
        if(total_tax_by_12>0)
        {
            $("#tr_scgst_by_12").show();
        }
        else
        {
            $("#tr_scgst_by_12").hide();  
        }
        if(total_tax_by_18>0)
        {
            $("#tr_scgst_by_18").show();
        }
		
        else
        {
            $("#tr_scgst_by_18").hide();  
        }
        if(total_tax_by_28>0)
        {
            $("#tr_scgst_by_28").show();
        }
		else
        {
            $("#tr_scgst_by_28").hide();  
        }
		//alert(sub_total);
		//alert(total_tax);
        grand_total=parseFloat(total_tax)+parseFloat(sub_total);//
        sub_total=parseFloat(sub_total).toFixed(2);
	var tranport_charge=$("#transport_charge").val();
		grand_total=parseFloat(grand_total)+parseFloat(tranport_charge);//
        grand_total=parseFloat(grand_total).toFixed(2);
        $('#td_sub_total').html(sub_total);
        $('#sub_total').val(sub_total);
        //alert(grand_total);
        $('#td_grand_total').html(grand_total);
        $('#grand_total').val(grand_total);
}
    
function remove_zero(valuee,a,evt)
{
    
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46)
          {
             if(valuee==0)
    {
    $("#p_price_"+a).val('');
    
    }
    return true;
          }
          if (charCode > 31 && (charCode < 48 || charCode > 57))
          {
             return false;
          }
          
    if(valuee==0)
    {
    $("#p_price_"+a).val('');
    }
}
function remove_zero_d(valuee,a,evt)
{
    
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46)
          {
             if(valuee==0)
			{
				$("#p_discount_"+a).val('');
    
			}
			return true;
          }
          if (charCode > 31 && (charCode < 48 || charCode > 57))
          {
             return false;
          }
          
    if(valuee==0)
    {
    $("#p_discount_"+a).val('');
    }
	//alert('kk');
}
function update_by_d(valuee,a)
{   //alert(valuee);
    if(valuee=="")
    { 
       $("#p_discount_"+a).val('0');
    } 
    if(valuee=="")
    {
        valuee=0;
    }
    valuee=parseFloat(valuee);
    var p_price=parseFloat($("#p_price_"+a).val());
	
    var total_amount=(((p_price)*($("#p_qnty_"+a).val()))-parseFloat(valuee));
    total_amount=parseFloat(total_amount).toFixed(2);
	if(valuee>total_amount)
    { 
       $("#p_discount_"+a).val('0');
	   alertify.error('Discount Should be less then Amount');
	   
	  
	
		total_amount=(((p_price)*($("#p_qnty_"+a).val()))-parseFloat(0));
		total_amount=parseFloat(total_amount).toFixed(2);
    } 
	//alert(total_amount);
    $('#p_amount_'+a).val(total_amount);
    $('#td_p_amount_'+a).html(total_amount);
    var thisid=a;
    var tax_type=$('#p_tax_type_'+thisid).val();//$('#td_p_tax_type_'+thisid).html();
    var cgst="";
    var sgst="";
    if(tax_type.trim()=="GST@5%")
    {
       cgst="2.5";
       sgst="2.5";
       
    }
    else if(tax_type.trim()=="GST@12%")
    {
      cgst="6";
      sgst="6";  
    }
    else if(tax_type.trim()=="GST@18%")
    {
       cgst="9";
       sgst="9";
    }
	else if(tax_type.trim()=="GST@28%")
    {
       cgst="14";
       sgst="14";
    }
    else 
    {
       cgst="0";
       sgst="0";
    }
    var tax_cgst=(parseFloat(total_amount)*parseFloat(cgst))/parseFloat(100);
    var tax_sgst=(parseFloat(total_amount)*parseFloat(sgst))/parseFloat(100);
    tax_cgst=parseFloat(tax_cgst).toFixed(2);
    tax_sgst=parseFloat(tax_sgst).toFixed(2);
    //alert(cgst+"+"+sgst);
	//alert('thisid: '+thisid);
	//alert(tax_cgst+"+"+tax_sgst);
	
    $("#span_cgst_txt_"+thisid).html('CGST @'+cgst+'% ');
    $("#span_sgst_txt_"+thisid).html('SGST @'+sgst+'% ');
    $("#span_cgst_"+thisid).val(tax_cgst);
    $("#span_sgst_"+thisid).val(tax_sgst);
    $("#span_cgst_type_"+thisid).val('CGST @'+cgst+'% ');
    $("#span_sgst_type_"+thisid).val('CGST @'+sgst+'% '); 
        
        
        var grand_total=0; 
        var sub_total=0;
        var total_tax=0;
        var total_tax_by_5=0;
        var total_tax_by_12=0;
        var total_tax_by_18=0;
		var total_tax_by_28=0;
        var count = parseFloat($('#print_table tr').length);//
        for(c=1;c<=count;c++)
        {
        
        var p_amount=$('#p_amount_'+c).val();
		
        if(p_amount!=undefined)
        {
        
        var sub_total = parseFloat(sub_total) + parseFloat(p_amount) ;
        }
        
        }
        var count = 1;//parseFloat($('#print_table_2 tr').length)-parseFloat(1);//
        //alert(sub_total);
        for(c=1;c<=count;c++)
        {
         var span_cgst=tax_cgst;//$("#span_cgst_"+c).html();
         var span_sgst=tax_sgst;//$("#span_cgst_"+c).html();
         if((span_cgst!="") && (span_sgst!=""))
         {
         total_tax=parseFloat(total_tax)+parseFloat(span_cgst)+parseFloat(span_sgst);//
         }
		 //alert('total_tax: '+total_tax);
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @2.5%")
         {
             total_tax_by_5=parseFloat(total_tax_by_5)+parseFloat(span_cgst);//
         }
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @6%")
         {
             total_tax_by_12=parseFloat(total_tax_by_12)+parseFloat(span_cgst);//
         }
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @9%")
         {
             total_tax_by_18=parseFloat(total_tax_by_18)+parseFloat(span_cgst);//
         }
		 if($("#span_cgst_txt_"+c).html().trim()=="CGST @14%")
         {
             total_tax_by_28=parseFloat(total_tax_by_28)+parseFloat(span_cgst);//
         }
         
        }
        total_tax_by_5=parseFloat(total_tax_by_5).toFixed(2);
        total_tax_by_12=parseFloat(total_tax_by_12).toFixed(2);
        total_tax_by_18=parseFloat(total_tax_by_18).toFixed(2);
		total_tax_by_28=parseFloat(total_tax_by_28).toFixed(2);
        //alert(total_tax_by_5+','+total_tax_by_12+','+total_tax_by_18);
        
        $("#span_cgst_by_5").html(total_tax_by_5);
        $("#span_sgst_by_5").html(total_tax_by_5);
        $("#span_cgst_by_12").html(total_tax_by_12);
        $("#span_sgst_by_12").html(total_tax_by_12);
        $("#span_cgst_by_18").html(total_tax_by_18);
        $("#span_sgst_by_18").html(total_tax_by_18);
        $("#span_cgst_by_28").html(total_tax_by_28);
        $("#span_sgst_by_288").html(total_tax_by_28);
        if(total_tax_by_5>0)
        {
            $("#tr_scgst_by_5").show();
        }
        else
        {
            $("#tr_scgst_by_5").hide();  
        }
        if(total_tax_by_12>0)
        {
            $("#tr_scgst_by_12").show();
        }
        else
        {
            $("#tr_scgst_by_12").hide();  
        }
        if(total_tax_by_18>0)
        {
            $("#tr_scgst_by_18").show();
        }
		
        else
        {
            $("#tr_scgst_by_18").hide();  
        }
        if(total_tax_by_28>0)
        {
            $("#tr_scgst_by_28").show();
        }
		else
        {
            $("#tr_scgst_by_28").hide();  
        }
		//alert(sub_total);
		//alert(total_tax);
        grand_total=parseFloat(total_tax)+parseFloat(sub_total);//
        sub_total=parseFloat(sub_total).toFixed(2);
	var tranport_charge=$("#transport_charge").val();
		grand_total=parseFloat(grand_total)+parseFloat(tranport_charge);//
        grand_total=parseFloat(grand_total).toFixed(2);
        $('#td_sub_total').html(sub_total);
        $('#sub_total').val(sub_total);
        //alert(grand_total);
        $('#td_grand_total').html(grand_total);
        $('#grand_total').val(grand_total);
        return false;
}
function update_by_price(valuee,a)
{
    if(valuee=="")
    { 
       $("#p_price_"+a).val('0');
    } 
    if(valuee=="")
    {
        valuee=0;
    }
    valuee=parseFloat(valuee);
    //var tax_rate=parseFloat($("#p_tax_rate_"+a).val());
    //alert(tax_rate);
	var p_discount=$('#p_discount_1').val();
	 
    var total_amount=(((valuee)*($("#p_qnty_"+a).val()))-parseFloat(p_discount));
    total_amount=parseFloat(total_amount).toFixed(2);
	if(p_discount>total_amount)
    { 
       $("#p_discount_"+a).val('0');
	   alertify.error('Price Should be greater then Discount');
	   
	    total_amount=(((valuee)*($("#p_qnty_"+a).val()))-parseFloat(0));
		total_amount=parseFloat(total_amount).toFixed(2);
    }
	//alert(total_amount);
    $('#p_amount_'+a).val(total_amount);
    $('#td_p_amount_'+a).html(total_amount);
    var thisid=a;
    var tax_type=$('#p_tax_type_'+thisid).val();//$('#td_p_tax_type_'+thisid).html();
    var cgst="";
    var sgst="";
    if(tax_type.trim()=="GST@5%")
    {
       cgst="2.5";
       sgst="2.5";
       
    }
    else if(tax_type.trim()=="GST@12%")
    {
      cgst="6";
      sgst="6";  
    }
    else if(tax_type.trim()=="GST@18%")
    {
       cgst="9";
       sgst="9";
    }
	else if(tax_type.trim()=="GST@28%")
    {
       cgst="14";
       sgst="14";
    }
    else 
    {
       cgst="0";
       sgst="0";
    }
    var tax_cgst=(parseFloat(total_amount)*parseFloat(cgst))/parseFloat(100);
    var tax_sgst=(parseFloat(total_amount)*parseFloat(sgst))/parseFloat(100);
    tax_cgst=parseFloat(tax_cgst).toFixed(2);
    tax_sgst=parseFloat(tax_sgst).toFixed(2);
    //alert(cgst+"+"+sgst);
	//alert('thisid: '+thisid);
	//alert(tax_cgst+"+"+tax_sgst);
	
    $("#span_cgst_txt_"+thisid).html('CGST @'+cgst+'% ');
    $("#span_sgst_txt_"+thisid).html('SGST @'+sgst+'% ');
    $("#span_cgst_"+thisid).val(tax_cgst);
    $("#span_sgst_"+thisid).val(tax_sgst);
    $("#span_cgst_type_"+thisid).val('CGST @'+cgst+'% ');
    $("#span_sgst_type_"+thisid).val('CGST @'+sgst+'% '); 
        
        
        var grand_total=0; 
        var sub_total=0;
        var total_tax=0;
        var total_tax_by_5=0;
        var total_tax_by_12=0;
        var total_tax_by_18=0;
		var total_tax_by_28=0;
        var count = parseFloat($('#print_table tr').length);//
        for(c=1;c<=count;c++)
        {
        
        var p_amount=$('#p_amount_'+c).val();
		
        if(p_amount!=undefined)
        {
        
        var sub_total = parseFloat(sub_total) + parseFloat(p_amount) ;
        }
        
        }
        var count = 1;//parseFloat($('#print_table_2 tr').length)-parseFloat(1);//
        //alert(sub_total);
        for(c=1;c<=count;c++)
        {
         var span_cgst=tax_cgst;//$("#span_cgst_"+c).html();
         var span_sgst=tax_sgst;//$("#span_cgst_"+c).html();
         if((span_cgst!="") && (span_sgst!=""))
         {
         total_tax=parseFloat(total_tax)+parseFloat(span_cgst)+parseFloat(span_sgst);//
         }
		 //alert('total_tax: '+total_tax);
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @2.5%")
         {
             total_tax_by_5=parseFloat(total_tax_by_5)+parseFloat(span_cgst);//
         }
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @6%")
         {
             total_tax_by_12=parseFloat(total_tax_by_12)+parseFloat(span_cgst);//
         }
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @9%")
         {
             total_tax_by_18=parseFloat(total_tax_by_18)+parseFloat(span_cgst);//
         }
		 if($("#span_cgst_txt_"+c).html().trim()=="CGST @14%")
         {
             total_tax_by_28=parseFloat(total_tax_by_28)+parseFloat(span_cgst);//
         }
         
        }
        total_tax_by_5=parseFloat(total_tax_by_5).toFixed(2);
        total_tax_by_12=parseFloat(total_tax_by_12).toFixed(2);
        total_tax_by_18=parseFloat(total_tax_by_18).toFixed(2);
		total_tax_by_28=parseFloat(total_tax_by_28).toFixed(2);
        //alert(total_tax_by_5+','+total_tax_by_12+','+total_tax_by_18);
        
        $("#span_cgst_by_5").html(total_tax_by_5);
        $("#span_sgst_by_5").html(total_tax_by_5);
        $("#span_cgst_by_12").html(total_tax_by_12);
        $("#span_sgst_by_12").html(total_tax_by_12);
        $("#span_cgst_by_18").html(total_tax_by_18);
        $("#span_sgst_by_18").html(total_tax_by_18);
        $("#span_cgst_by_28").html(total_tax_by_28);
        $("#span_sgst_by_288").html(total_tax_by_28);
        if(total_tax_by_5>0)
        {
            $("#tr_scgst_by_5").show();
        }
        else
        {
            $("#tr_scgst_by_5").hide();  
        }
        if(total_tax_by_12>0)
        {
            $("#tr_scgst_by_12").show();
        }
        else
        {
            $("#tr_scgst_by_12").hide();  
        }
        if(total_tax_by_18>0)
        {
            $("#tr_scgst_by_18").show();
        }
		
        else
        {
            $("#tr_scgst_by_18").hide();  
        }
        if(total_tax_by_28>0)
        {
            $("#tr_scgst_by_28").show();
        }
		else
        {
            $("#tr_scgst_by_28").hide();  
        }
		//alert(sub_total);
		//alert(total_tax);
        grand_total=parseFloat(total_tax)+parseFloat(sub_total);//
        sub_total=parseFloat(sub_total).toFixed(2);
	var tranport_charge=$("#transport_charge").val();
		grand_total=parseFloat(grand_total)+parseFloat(tranport_charge);//
        grand_total=parseFloat(grand_total).toFixed(2);
        $('#td_sub_total').html(sub_total);
        $('#sub_total').val(sub_total);
        //alert(grand_total);
        $('#td_grand_total').html(grand_total);
        $('#grand_total').val(grand_total);
        return false;
}
</script>

 <script type="text/javascript">
   $('input[name=\'product_name[]\']').autocomplete({
     //$('#p_name_<?php echo $b; ?>').autocomplete({
		 
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=purchaseorder/purchase_order/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) { 
			$("#p_id_1").val('');
                console.log(JSON.stringify(json));
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['product_id'],
                        price: item['price'],
                        price_wo_t: item['price_wo_t'],
                        hstn: item['hstn'],
                        product_tax_type: item['product_tax_type'],
                        product_tax_rate: item['product_tax_rate'],
                        
                    }
                }));
            }
        });
    },
    'select': function(item) {
        
        var this_id=(this.id).split('_');
        var thisid=this_id.pop();
		
        get_prn_list('',item['value']);
		
        $('#p_name_'+thisid).val(item['label']);
        $('#p_id_'+thisid).val(item['value']);
        $('#td_p_tax_type_'+thisid).html(item['product_tax_type']);
        $('#td_p_hsn_'+thisid).html(item['hstn']);
        $('#p_hsn_'+thisid).val(item['hstn']);
        $('#p_price_'+thisid).val(item['price_wo_t']);
		var p_discount=$('#p_discount_1').val();
		//alert((parseFloat(item['price_wo_t'])+'----'+parseFloat(p_discount))+'----'+($("#p_qnty_"+thisid).val()));
        var total_amount=(((parseFloat(item['price_wo_t']))*($("#p_qnty_"+thisid).val()))-parseFloat(p_discount));
        total_amount=parseFloat(total_amount).toFixed(2);
        $('#p_amount_'+thisid).val(total_amount);
        $('#td_p_amount_'+thisid).html(total_amount);
        $('#p_tax_rate_'+thisid).val(item['product_tax_rate']);
        $('#p_tax_type_'+thisid).val(item['product_tax_type']);
        //alert(total_amount);
    var tax_type=$('#p_tax_type_'+thisid).val();//$('#td_p_tax_type_'+thisid).html();
    var cgst="";
    var sgst="";
    if(tax_type.trim()=="GST@5%")
    {
       cgst="2.5";
       sgst="2.5";
       
    }
    else if(tax_type.trim()=="GST@12%")
    {
      cgst="6";
      sgst="6";  
    }
    else if(tax_type.trim()=="GST@18%")
    {
       cgst="9";
       sgst="9";
    }
	else if(tax_type.trim()=="GST@28%")
    {
       cgst="14";
       sgst="14";
    }
    else 
    {
       cgst="0";
       sgst="0";
    }
    var tax_cgst=(parseFloat(total_amount)*parseFloat(cgst))/parseFloat(100);
    var tax_sgst=(parseFloat(total_amount)*parseFloat(sgst))/parseFloat(100);
    tax_cgst=parseFloat(tax_cgst).toFixed(2);
    tax_sgst=parseFloat(tax_sgst).toFixed(2);
    //alert(cgst+"+"+sgst);
	//alert('thisid: '+thisid);
	//alert(tax_cgst+"+"+tax_sgst);
	
    $("#span_cgst_txt_"+thisid).html('CGST @'+cgst+'% ');
    $("#span_sgst_txt_"+thisid).html('SGST @'+sgst+'% ');
    $("#span_cgst_"+thisid).val(tax_cgst);
    $("#span_sgst_"+thisid).val(tax_sgst);
    $("#span_cgst_type_"+thisid).val('CGST @'+cgst+'% ');
    $("#span_sgst_type_"+thisid).val('CGST @'+sgst+'% ');   
        //alert('check');
        var grand_total=0; 
        var sub_total=0;
        var total_tax=0;
        var total_tax_by_5=0;
        var total_tax_by_12=0;
        var total_tax_by_18=0;
		var total_tax_by_28=0;
        var count = parseFloat($('#print_table tr').length);//
        for(c=1;c<=count;c++)
        {
        
        var p_amount=$('#p_amount_'+c).val();
		
        if(p_amount!=undefined)
        {
        
        var sub_total = parseFloat(sub_total) + parseFloat(p_amount) ;
        }
        
        }
        var count = 1;//parseFloat($('#print_table_2 tr').length)-parseFloat(1);//
        //alert(sub_total);
        for(c=1;c<=count;c++)
        {
         var span_cgst=tax_cgst;//$("#span_cgst_"+c).html();
         var span_sgst=tax_sgst;//$("#span_cgst_"+c).html();
         if((span_cgst!="") && (span_sgst!=""))
         {
         total_tax=parseFloat(total_tax)+parseFloat(span_cgst)+parseFloat(span_sgst);//
         }
		 //alert('total_tax: '+total_tax);
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @2.5%")
         {
             total_tax_by_5=parseFloat(total_tax_by_5)+parseFloat(span_cgst);//
         }
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @6%")
         {
             total_tax_by_12=parseFloat(total_tax_by_12)+parseFloat(span_cgst);//
         }
         if($("#span_cgst_txt_"+c).html().trim()=="CGST @9%")
         {
             total_tax_by_18=parseFloat(total_tax_by_18)+parseFloat(span_cgst);//
         }
		 if($("#span_cgst_txt_"+c).html().trim()=="CGST @14%")
         {
             total_tax_by_28=parseFloat(total_tax_by_28)+parseFloat(span_cgst);//
         }
         
        }
        total_tax_by_5=parseFloat(total_tax_by_5).toFixed(2);
        total_tax_by_12=parseFloat(total_tax_by_12).toFixed(2);
        total_tax_by_18=parseFloat(total_tax_by_18).toFixed(2);
		total_tax_by_28=parseFloat(total_tax_by_28).toFixed(2);
        //alert(total_tax_by_5+','+total_tax_by_12+','+total_tax_by_18);
        
        $("#span_cgst_by_5").html(total_tax_by_5);
        $("#span_sgst_by_5").html(total_tax_by_5);
        $("#span_cgst_by_12").html(total_tax_by_12);
        $("#span_sgst_by_12").html(total_tax_by_12);
        $("#span_cgst_by_18").html(total_tax_by_18);
        $("#span_sgst_by_18").html(total_tax_by_18);
        $("#span_cgst_by_28").html(total_tax_by_28);
        $("#span_sgst_by_288").html(total_tax_by_28);
        if(total_tax_by_5>0)
        {
            $("#tr_scgst_by_5").show();
        }
        else
        {
            $("#tr_scgst_by_5").hide();  
        }
        if(total_tax_by_12>0)
        {
            $("#tr_scgst_by_12").show();
        }
        else
        {
            $("#tr_scgst_by_12").hide();  
        }
        if(total_tax_by_18>0)
        {
            $("#tr_scgst_by_18").show();
        }
		
        else
        {
            $("#tr_scgst_by_18").hide();  
        }
        if(total_tax_by_28>0)
        {
            $("#tr_scgst_by_28").show();
        }
		else
        {
            $("#tr_scgst_by_28").hide();  
        }
		//alert(sub_total);
		//alert(total_tax);
        grand_total=parseFloat(total_tax)+parseFloat(sub_total);//
        sub_total=parseFloat(sub_total).toFixed(2);
        grand_total=parseFloat(grand_total).toFixed(2);
        $('#td_sub_total').html(sub_total);
        $('#sub_total').val(sub_total);
        //alert(grand_total);
        $('#td_grand_total').html(grand_total);
        $('#grand_total').val(grand_total);
        
        
    }
});

</script>

<?php echo $footer; ?>