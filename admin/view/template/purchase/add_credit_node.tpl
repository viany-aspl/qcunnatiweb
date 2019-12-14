<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="return" class="btn btn-primary">
              <i class="fa fa-reply"></i>
          </a>
        
      </div>
      <h2>Credit Note</h2>
      
    </div>
  </div>
  <div class="container-fluid">
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

<?php if ($error) {  ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
       <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-search"></i> Add Credit Note for Supplier</h3>
      </div>
      <div class="panel-body" style="min-height: 345px;">
        <div class="well">
        <div class="row">
		<div class="col-sm-4">
            <div class="form-group">
            <label class="control-label" for="input-date-end"><?php echo "Supplier"; ?></label>
            <select  name="filter_supplier" id="input-supplier" required="required" style="width: 100%;" class="select2 form-control">
                              <option value="" >Select Supplier</option>
                  <?php foreach ($suppliers as $supplier) { ?>
                  <?php if ($supplier['id'] == $filter_supplier) { ?>
                  <option value="<?php echo $supplier['id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
            </select>
            </div>
        </div>
		<div class="col-sm-4">
            <div class="form-group">
            <label class="control-label" for="input-date-end">Invoice Number</label>
            <input type="text" style="margin-top: 5px;"  name="invoice_number" value="" placeholder="Invoice Number" id="invoice_number" class="form-control"  required="required" />
            </div>
        </div>
            
                
	<div class="col-sm-2">
	<br/>
		<img id="search_img" src="view/image/processing_image.gif" style="height: 48px;width: 48px;display: none;" />
                <input id="search_btn" type="button" style="margin-top: 9px;" name="order_search" value="Seacrh" onclick="return search_order();" class="btn btn-primary"   />
                  
                </div>
            
        </div></div>   
       
      <br/> <br/>
        <div class="table-responsive"  id="tbl_header_main">
          <table class="table table-bordered">
            <thead>
              <tr>
                
                <td class="text-left">Invoice Number</td>
               <td class="text-left">PO Number</td>
                <td class="text-left">Quantity</td>
                <td class="text-left">Rate</td>
                <td class="text-left">CGST Type</td>
                <td class="text-left">CGST Value</td>
				  <td class="text-left">SGST Type</td>
                <td class="text-left">SGST Value</td>
                
                <td class="text-left">Sub TOTAL</td>
                <td class="text-left">Grand Total</td>
                <td class="text-left">Discount</td>
                <td class="text-left">Create Date</td>
	  <td class="text-left">Invoice Date</td>
              </tr>
            </thead>
            <tbody>
	<tr>                
               
                <td class="text-left" id="invoice_no"></td>
                <td class="text-left" id="po_number"></td>
                <td class="text-left" id="Quantity"></td>
                <td class="text-left" id="rate"></td>
				
                <td class="text-left" id="cgst_type"></td>
				<td class="text-left" id="cgst_value"></td>
                  <td class="text-left" id="sgst_type"></td>
				  
                 
                  <td class="text-left" id="sgst_value"></td>
               
                <td class="text-left" id="sub_total"></td>
                <td class="text-left" id="grand_total"></td>
                <td class="text-left" id="discount"></td>
                <td class="text-left" id="create_date"></td>
                <td class="text-left" id="invoice_date"></td>
              </tr>
            </tbody>
          </table>
            
            <form name="frm" id="frm" method="post" action="">
            <div class="container-fluid">
			<input  name="po_no" value="" id="po_no" class="form-control" type="hidden">
			<input  name="supplier_id" value="" id="supplier_id" class="form-control" type="hidden">
			<input  name="invoice_num" value="" id="invoice_num" class="form-control" type="hidden">
			<input  name="product_id" value="" id="product_id" class="form-control" type="hidden">
			
                <input  name="sub_total" value="" id="sub_total_val" class="form-control" type="hidden">
					<input  name="cn_status" value="" id="cn_status" class="form-control" type="hidden">
                 <input  name="cgst_total_val" value="" id="cgst_total_val" class="form-control" type="hidden">
                  <input  name="sgst_total_val" value="" id="sgst_total_val" class="form-control" type="hidden">
				  
			<br/><br/>
			<div class="col-md-6" >
			<div class="form-group required"><!--------onkeypress="return isNumber(event)"----->
                
                <label class="col-sm-4 control-label" for="input-meta-title" style="text-align:right;font-size:10px;" >Rebate/Percentage</label>
                <div class="col-sm-8" style="padding-left: 0px;padding-right: 0px; margin-bottom:5px;">
                    <input maxlength="3" onkeypress="return isNumber(event)" onkeyup="cal_subtotal();"  name="percentage" id="percentage" class="form-control" required="required">
                </div>
				</div>
				<div class="form-group required">
				
				
				
				
				
                <label class="col-sm-4 control-label" for="input-meta-title" style="margin-top:10px;" >Product Name</label>
                <div class="col-sm-8" style="padding-left: 0px;padding-right: 0px; margin-top:10px;">
                    <span id="product_name"></span>
                </div>
                
              
                
             </div>
			</div>
			
          <div class="col-sm-6" style="padding-left: 0px;padding-right: 0px;padding-top:10px;">
           <div class="form-group required"><!--------onkeypress="return isNumber(event)"----->
			
			<label class="col-sm-4 control-label required" for="input-meta-title" style="font-size: 10px;" >C N./Invoice Number</label>
                <div class="col-sm-8" style="padding-left: 0px;padding-right: 0px; margin-bottom:5px;">
                    <input  name="cn_no" id="cn_no" class="form-control" placeholder="C N./Invoice Number" required="required">
                </div>
               <label class="col-sm-4 control-label form-group " for="input-meta-title" syle="font-size: 10px;" >Invoice Date</label>
                <div class="col-sm-8" style="padding-left: 0px;padding-right: 0px; margin-bottom:5px;">
					<div class="input-group date">
                  <input onkeypress="return false" type="text" name="invoice_date" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-invoice_date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
                
                </div>
               <!-- <input  style="margin-top: 5px;"  value="Calculate" class="btn btn-primary" type="button">--->
                <label class="col-sm-4 control-label required"  for="input-meta-title" >Subtotal</label>
                <div class="col-sm-8" style="padding-left: 0px;padding-right: 0px; margin-bottom:5px;">
                    <input   name="get_val" id="get_val" placeholder="" class="form-control" disabled="disabled" />
                </div>
				
				<label class="col-sm-4 control-label required" for="input-meta-title" >CGST</label>
                <div class="col-sm-8" style="padding-left: 0px;padding-right: 0px; margin-bottom:5px;">
                    <input    name="get_cgst" value="" disabled="disabled" id="get_cgst" disabled="disabled" class="form-control" required="required" type="text">
                </div>
				
				<label class="col-sm-4 control-label required" for="input-meta-title" >SGST</label>
                <div class="col-sm-8" style="padding-left: 0px;padding-right: 0px; margin-bottom:5px;">
                    <input    name="get_sgst" value="" disabled="disabled" id="get_sgst" disabled="disabled" class="form-control" required="required" type="text">
                </div>
				
				<label class="col-sm-4 control-label" for="input-meta-title" >Round Off</label>
                <div class="col-sm-8" style="padding-left: 0px;padding-right: 0px; margin-bottom:5px;">
                    <input maxlength="4" name="round_off" id="round_off" value="" onkeypress="return isNumber(event)" onkeyup="cal_total(this.value);"  class="form-control" required="required" type="text">
                </div>
				
				<label class="col-sm-4 control-label required" for="input-meta-title" >Grand Total</label>
                <div class="col-sm-8" style="padding-left: 0px;padding-right: 0px; margin-bottom:5px;">
                    <input name="grand_total" id="get_grand_total" disabled="disabled" value=""  class="form-control" required="required" type="text">
					<input name="get_grand_total_hidden" id="get_grand_total_hidden" disabled="disabled" value=""  class="form-control" required="required" type="hidden">
                </div>
				
				
                
             </div>
            </div>
			
			
			</div>
				<img id="upload_img" src="view/image/processing_image.gif" style="height: 48px;width: 48px;display: none" ></img>
                  
                  <input id="upload" type="button" style="margin-top: 5px;float:right;margin-right: 19px;" name="submit_invoice" value="Submit"  class="btn btn-primary"   />
               
           </div> 
            </form> 
        </div>
        </div>
         
      
    </div>
    </div>
  </div>
    
	<!-- Modal -->
  <div class="modal fade" id="myModal_create_bill" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Select Invoice</h4>
        </div>
        <div class="modal-body">
        <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">Invoice Number</td>
					<td class="text-left">Invoice Date</td>
					<td class="text-left">PO Number</td>
					<td class="text-left">Product</td>
					<td class="text-left">Supplier </td>
					<td class="text-left">Action</td>
                </tr>
              </thead>
			  <tbody id="tbody">
			  
			  </tbody>
			  </table>
            
        
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>
	
<?php echo $footer; ?>
<script type="text/javascript">
$("#input-supplier").select2();
$('#upload').on('click', function() 
{
	var po_no=$("#po_no").val();
	var invoice_num=$("#invoice_num").val();
    var supplier_id=$("#supplier_id").val(); 
	var product_id=$("#product_id").val();
	
    var invoice_date=$('#input-invoice_date').val();
	var cn_no=$('#cn_no').val();
	var percentage=$('#percentage').val();
    var sgst=$('#get_sgst').val();
    var cgst=$('#get_cgst').val();
    var val=$('#get_val').val();
	var round_off=$("#round_off").val();
					
	var grand_total=$("#get_grand_total").val();
    var total=$("#get_grand_total_hidden").val();
	var cn_status=$("#cn_status").val();
	
	if(!invoice_date)
	{
	   alertify.error('Please select Invoice Date');
	   $('#input-invoice_date').focus();
	   return false;
	}
	else if(!cn_no)
	{
	   alertify.error('Please enter C N. Number');
	   $('#cn_no').focus();
	   return false;
	}
	else if(!percentage)
	{
	   alertify.error('Please enter rebate percentage');
	   $('#percentage').focus();
	   return false;
	}
	else if(percentage=='0')
	{
	   alertify.error('Please enter rebate percentage');
	   $('#percentage').focus();
	   return false;
	}
	else if(percentage=='0.')
	{
	   alertify.error('Please enter rebate percentage');
	   $('#percentage').focus();
	   return false;
	}
	else if(percentage=='0.0')
	{
	   alertify.error('Please enter rebate percentage');
	   $('#percentage').focus();
	   return false;
	}
	else if(percentage=='00')
	{
	   alertify.error('Please enter rebate percentage');
	   $('#percentage').focus();
	   return false;
	}
	else if(percentage=='.')
	{
	   alertify.error('Please enter rebate percentage');
	   $('#percentage').focus();
	   return false;
	}
	else
	{
     $.ajax({ 
                   type: 'post',
                   url: 'index.php?route=purchase/credit_note/insert_data&token=<?php echo $token;?>',
                   data: {po_no:po_no,invoice_num:invoice_num,supplier_id:supplier_id,product_id:product_id,cn_status:cn_status,invoice_date:invoice_date,cn_no:cn_no,percentage: percentage,sgst: sgst,cgst:cgst,sub_total:val,round_off:round_off,total:total,grand_total:grand_total},
                   cache: false,
				   beforeSend: function() 
					{
							$("#upload").hide();
							$("#upload_img").show();
					},
                   success: function(data) 
				   {
					   if(data=='0')
					   {
						   alertify.error('This Invoice is already added');
						   $("#upload").show();
							$("#upload_img").hide();
						   return false;
					   }
						else
						{
							$("#tbl_header_main").hide();
							$('#input-invoice_date').val('');
							$('#cn_no').val('');
							$('#percentage').val('');
							$('#get_val').val('');
							$('#get_cgst').val('');
							$('#get_sgst').val('');
							$('#round_off').val('');
							$('#get_grand_total').val('');
							$('#get_grand_total_hidden').val('');
							$('#invoice_number').val('');
							$("#upload").show();
							$("#upload_img").hide();
							alertify.success('Insert  Successfully');
							return true;
						}
                }
              });
    
	}
	
 });
  

	$('.date').datetimepicker({
		pickTime: false
	});
	
	function reset_form()
	{
		$('[name=from]').val('');
		$('[name=to]').val('');
		$('[name=filter_id]').val('');
		$('[name=status]').prop('selectedIndex', 0);
	}
  </script>
  <script type="text/javascript">
 $("#tbl_header_main").hide();  
 function cal_total(valll)
 {
	var grandtotal = document.getElementById("get_grand_total_hidden").value;
	grandtotal=parseFloat(grandtotal);
	var updated_total=0;
	if((valll!='.') &&(valll!='-.') &&(valll!='-0.') && (valll) && (valll!='-') && (valll!='0'))
	{
	
		updated_total=parseFloat(grandtotal)+parseFloat(valll);
		updated_total.toFixed(2);
		document.getElementById("get_grand_total").value=updated_total;
	 }
	 else
	 {
		 document.getElementById("get_grand_total").value=grandtotal;
	 }
 }
	function cal_subtotal()
	{
		var total=document.getElementById('sub_total_val').value;
        var percentage=document.getElementById('percentage').value;
        var cgst=document.getElementById('cgst_total_val').value;
        var sgst=document.getElementById('sgst_total_val').value;
	  
		if((percentage!='.') &&(percentage!='0.')&& (percentage!='0.0') && (percentage!='0.00') &&(percentage!='.0') && (percentage) && (percentage!='.00')  && (percentage!='0'))
		{
			var total=document.getElementById('sub_total_val').value;
			var percentage=document.getElementById('percentage').value;
			var cgst=document.getElementById('cgst_total_val').value;
			var sgst=document.getElementById('sgst_total_val').value;
       
			var calculation=((percentage*total)/100);
			document.getElementById("get_val").value = calculation.toFixed(2); 
			var calculationcgst=((percentage*cgst)/100);
			document.getElementById("get_cgst").value = calculationcgst.toFixed(2); 
        
			var calculationsgst=((percentage*sgst)/100);
			document.getElementById("get_sgst").value = calculationsgst.toFixed(2); 
			var calculationgrandtotal=(calculation+calculationcgst+calculationsgst);
			calculationgrandtotal=calculationgrandtotal.toFixed(2);
			document.getElementById("get_grand_total").value = calculationgrandtotal; 
			document.getElementById("get_grand_total_hidden").value = calculationgrandtotal;
		}
		else
		{
			document.getElementById("get_val").value = ''; 
			document.getElementById("get_cgst").value = '';
			document.getElementById("get_sgst").value = '';
			document.getElementById("get_grand_total").value = ''; 
			document.getElementById("get_grand_total_hidden").value = '';
        }
  }  
  function search_order()
  {
	var invoice_number=$("#invoice_number").val();
	var supplier_id=$("#input-supplier").val();
	if(invoice_number!='')
	{
		try
		{
			$.ajax({
			url: 'index.php?route=purchase/credit_note/search_order&token=<?php echo $token; ?>&invoice_number=' + encodeURIComponent(invoice_number)+'&supplier_id=' + encodeURIComponent(supplier_id),
			dataType: 'text',
			beforeSend: function() 
			{
				$("#search_btn").hide();
				$("#search_img").show();
			},
			success: function(data) 
			{
				//data=JSON.parse(data);
				//alert(JSON.stringify(data));
				if(!data)
				{
					select_order(invoice_number,0,'',supplier_id);
				}
				else
				{
					if(data=='No data found for this invoice number')
					{ 
						$("#tbl_header_main").hide();
						$('#percentage').val('');
						alertify.error(data);
					}
					else
					{ 
						$("#tbl_header_main").hide();
						$("#tbody").html(data);
						$('#myModal_create_bill').modal('show');
					}
				}
				$("#search_btn").show();
				$("#search_img").hide();
			},
			error: function(xhr, ajaxOptions, thrownError) 
			{
                    
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				$("#search_btn").show();
				$("#search_img").hide();
				return false;
			}
			});
		}
		catch(e)
		{
			alert(e);
			$("#search_btn").show();
			$("#search_img").hide();
			return false;
		}
    }
    else
    {
        alertify.error('Please enter Invoice Number');
    }
		return false;
  }  
  function select_order(invoice_number,a,po_number,supplier_id)
  {
    //var invoice_number=$("#invoice_number").val();
    //alert(invoice_number);
    if(invoice_number!='')
    {
	try{
	  $.ajax({
		url: 'index.php?route=purchase/credit_note/select_order&token=<?php echo $token; ?>&invoice_number=' + encodeURIComponent(invoice_number)+'&po_number=' + encodeURIComponent(po_number)+'&supplier_id=' + encodeURIComponent(supplier_id),
		dataType: 'json',
		beforeSend: function() 
			{
				$("#select_btn"+a).hide();
				$("#select_img"+a).show();
			},
		success: function(data) 
		{
                //alert(JSON.stringify(data));
				
				if(data['cn_status']=='0')
				{
							$("#sid").html(data['sid']);
                            $("#po_number").html(data['po_no']); 
							$("#invoice_no").html(data['invoice_no']); 							
                                 
                            $("#product_id").html(data['product_id']);      
                            $("#Quantity").html(data['Quantity']);      
                            $("#rate").html(data['rate']);      
                                  
                            $("#create_date").html(data['create_date']);      
                            $("#cash").html(data['cash']);      
                            $("#invoice_date").html(data['invoice_date']);      
                            $("#discount").html(data['discount']); 
                            $("#sub_total").html(data['sub_total']); 
                            
                            $("#invoice_number").html(data['invoice_number']);
                            $("#grand_total").html(data['grand_total']);
                            $("#grand_total").html(data['grand_total']);
							
							$("#cgst_type").html(data['cgst_type']);
                            $("#sgst_type").html(data['sgst_type']);
							
                            $("#cgst_value").html(data['cgst_value']);
                            $("#sgst_value").html(data['sgst_value']);
							
							$("#po_no").val(data['po_no']);
							$("#invoice_num").val(data['invoice_no']); 
                            $("#supplier_id").val(data['supplier_id']); 
							$("#product_id").val(data['product_id']);
							
							$("#sub_total_val").val(data['sub_total']);
                            $("#cgst_total_val").val(data['cgst_value']);
                            $("#sgst_total_val").val(data['sgst_value']);
							
							$("#cn_status").val(data['cn_status']);
							
							$("#product_name").html(data['product_name']);
							$('#myModal_create_bill').modal('hide');
                            $("#tbl_header_main").show();
				}
				else
				{
					$("#tbl_header_main").hide();
					$('#percentage').val('');
					alertify.error(data['msg']);
					
				}	
                     
		},
		error: function(xhr, ajaxOptions, thrownError) {
                    
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			return false;
                       
    
    }
	});
	  }
	catch(e)
	{
		alert(e);
		
		return false;
	}
    }
    else
    {
        alertify.error('Please enter Invoice Number');
    }
    return false;
  }
  
  function isNumber(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode != 45 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
  </script> 