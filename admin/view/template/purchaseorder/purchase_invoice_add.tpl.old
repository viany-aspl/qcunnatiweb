<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
    <div class="container-fluid"> 
      <div class="pull-right"><!--<button onclick ="print_order()" data-toggle="tooltip" title="<?php echo "Print Order"; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>--><!--<a href="<?php echo $shipping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></a> <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>--> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo "Cancel Button"; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
  <h1><?php echo "Purchase Invoice "; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Purchase Invoice  </a></li>
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
		<h3 class="panel-title">
                    <strong>PO NO : </strong>
			 <?php  echo $order_information['order_info']['id_prefix'].''.$order_information['order_info']['sid'];  ?>
		</h3>
	</div>
      <form method="post" action="" enctype="multipart/form-data" id="invoice_form">
	<div class="panel-body">
            
            <div class="row">
            <div class="col-sm-12">
               <div class="table-responsive">
                  <table class="table table-bordered">
                    <tr>
                        <td class="col-sm-6"><strong>From:</strong>
                            <br />
                             <?php echo OFFICE_ADDRESS; ?>
                        </td>
                        <td class="col-sm-6">
                            <strong>Supplier:</strong>
                            <br />
                           <?php// print_r($order_information['order_info']); ?>
                            <strong>Name :</strong> <span id="to_store_name"><?php echo  $order_information['order_info']['first_name'].''.$order_information['order_info']['last_name'];  ?></span> <br />
                            <strong>Address :</strong> <span id="to_store_address"><?php  echo $order_information['order_info']['ADDRESS'];  ?></span> <br />
                            <strong>Phone No :</strong> <span id="to_store_phone"><?php  echo $order_information['order_info']['telephone'];  ?></span><br />
                            <strong>Email Id :</strong> <span id="to_store_email"><?php  echo $order_information['order_info']['email'];  ?></span> <br />
                            <strong>Pan Card :</strong> <span id="to_store_pan"><?php  echo $order_information['order_info']['pan'];  ?></span> <br />
                            <strong>GSTN : <span id="to_store_gst"><?php echo $order_information['order_info']['gst'];   ?></span> <br />
                        </td>
                    </tr>

                      <tr>
					  <td class="col-sm-6">
                              <strong>Delivery Address:</strong>
                            
                              <br /><br />
                                  <?php //echo $store_to_data; 
												$store_to_data=explode('---',$store_to_data);  ?>
                           <strong>  Name :</strong> <span id="to_store_name"><?php echo $store_to_data[0];  ?></span> <br />
                           <strong> Address :</strong> <span id="to_store_address"><?php  echo $store_to_data[1];  ?></span> <br />
                           <strong> Phone No :</strong> <span id="to_store_phone"><?php  echo $store_to_data[2];  ?></span><br />
                           <strong> Email Id :</strong> <span id="to_store_email"><?php  echo $store_to_data[3];  ?></span> <br />
                           <strong> Pan Card :</strong> <span id="to_store_pan"><?php  echo $store_to_data[4];  ?></span> <br />
                           <strong>GSTN :</strong> <span id="to_store_gst"><?php echo $store_to_data[5];   ?></span> <br />
                           <strong>MSMFID :</strong> <span id="to_store_gst"><?php echo $store_to_data[6];   ?></span> <br /> 
                         </td>
                          <td >   
						  
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
                                  <input type="text" name="filter_date" autocomplete="off"  value="" placeholder="<?php echo "Valid Date"; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" required="required"/>
                                <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                </span>
                              </div>
                              </div>
                              </div>
							  <div class="col-sm-6">
                              <div class="form-group">
                             <label class="control-label" for="input-date-end"><?php echo "Invoice Upload"; ?></label>
                               <input type="file" name="snapshot" id="input-snapshot" class="form-control" />
                  <?php if ($error_snapshot) { ?>
                  <div class="text-danger"><?php echo $error_snapshot; ?></div>
                  <?php } ?>
                              </div>
                              </div>
                              </div>
							
                         </td>
                      </tr>
					  
                      </table>
			
                   
                </div>
            </div>
        </div>  
	    <h4><strong>PO Product Details</strong></h4>
		<table class="table table-bordered" id="print_table" border="1" style="margin-top:0px;">
            <thead>
                <tr>                          
                    <td class="text-left" style="width: 11.11%;">Product</td>
                    <td class="text-left" style="width: 11.11%;">Rate</td>
                    <td class="text-left" style="width: 11.11%;">Quantity</td>
					<td class="text-left" style="width: 11.11%;">Amount</td> 
                </tr>
            </thead>
            <tbody id="t_body">
				   <td class="text-left" style="width: 11.11%;"><?php echo $order_information['order_info']['product']; ?></td>
				  <td class="text-left" style="width: 11.11%;"><?php echo $order_information['order_info']['rate']; ?></td>
                    <td class="text-left" style="width: 11.11%;"><?php echo $order_information['order_info']['Quantity']; ?></td>
                  
					<td class="text-left" style="width: 11.11%;"><?php echo $order_information['order_info']['amount']; ?></td> 
          
            </tbody>
        </table>
      
		<table class="table table-bordered" id="print_table" border="1">
          <thead>
                <tr>
                          
                          <td class="text-left" style="width: 11.11%;">Product</td>
                           <td class="text-left" style="width: 11.11%;">Rate</td>
                          <td class="text-left" style="width: 11.11%;">Quantity</td>
							<td class="text-left" style="width: 11.11%;">Discount</td>
                           <td class="text-left" style="width: 11.11%;">Amount</td>
                       
                          
                </tr>
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
							
		<table class="table table-bordered">
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
            </tbody> 

		</table>
            <table class="table table-bordered">
          <tbody>
                <tr id="tr_scgst_by_18" >
                    <td class="text-right" style="width: 78%;"><b>
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
$('.date').datetimepicker({
	pickTime: false
});
$("#input-store").select2();
</script>
<script type="text/javascript">
function updatebuttonvalue(data)
{

$("#buttonvalue").val(data);
var input_supplier=$("#input-supplier").val();
var input_store=$("#input-store").val();
var contactname=$("#contactname").val();
var contactmobile=$("#contactmobile").val();
var input_date=$("#input-date-start").val();
var p_name=$("#p_name_1").val();
var p_price=$("#p_price_1").val();
var p_qnty=$("#p_qnty_1").val();
var invoiceno=$("#invoiceno").val();

//alert(input_supplier+'--'+input_store+'--'+contactname+'--'+contactmobile+'--'+input_date+'--'+p_name+'--'+p_price+'--'+p_qnty);
//alert(input_store);

if(invoiceno=="")
    {
        alertify.error('Please Enter Invoice No');
        $("#invoiceno").focus();
        return false;
    }
if(input_date=="")
    {
        alertify.error('Please Select Invoice Date');
        $("#input-date-start").focus();
        return false;
    }
if(!p_name)
{
alertify.error('Please select Product');
$("#p_name_1").focus();
return false;
}

else if(p_price=='0')
{
alertify.error('Please Fill Product Price');
$("#p_price_1").focus();
return false;
}
else if(!p_qnty)
{
alertify.error('Please Fill Quantity');
$("#p_qnty_1").focus();
return false;
}
else if(p_qnty=='0')
{
alertify.error('Please Fill Quantity');
$("#p_qnty_1").focus();
return false;
}


else
{
     $("#cr_btn1").hide();
     $("#cr_btn2").hide();
     $("#cr_img").show(); 
     return true;
}
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